<?php

namespace App\Http\Controllers;

use App\Domains\MyCareTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Domains\Facility;
use App\Domains\HubUser;
use App\Domains\Admission;
use App\Domains\Room;
use App\Domains\Location;
use App\Domains\Inquiry;
use App\Utils\RmqHelper;
use App\Domains\Resident;
use App\Domains\ResidentCCS;
use App;
use App\Utils\Toolkit;

class AdmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing(Request $request){
        $facilityId = $request->session()->get('facility');
        if($facilityId==null) return redirect('/');

        // if the user is yet to select a facility, redirect to the view to select a facility
        if($facilityId == '')
            return redirect('facility/show');

        $facility = Facility::find($facilityId);

        $data = Admission::where('Facility.FacilityId', $facilityId)
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admission.listing',[
            'facility' => $facility,
            'admissions' => $data
        ]);
    }

    public function findahead(Request $request){
        $facilityId = $request->session()->get('facility');
        if(empty($facilityId)) return null;

        $facility = Facility::find($facilityId);

        $name = Input::get('name');

        $admissions = Admission::where('Facility.FacilityId', $facilityId)
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->where(function($query) use ($name){
                $query->where('data.last_name', 'like', $name .'%')
                      ->orWhere('data.first_name', 'like', $name .'%');
            })->get();
            
        // For <typeahead> to show text on auto-complete dropdown list
        foreach ($admissions as $item) {
           $item['screen_name'] = $item->data['last_name']. ", ". $item->data['first_name'];
        }

        return $admissions;
    }

    public function add(Request $request){
        $facilityId = $request->session()->get('facility');
        if($facilityId==null) return redirect('/');

        // if the user is yet to select a facility, redirect to the view to select a facility
        if($facilityId == '')
            return redirect('facility/show');

        $facility = Facility::find($facilityId);

        $rooms = Room::where('FacilityID', $facility->FacilityID)
            ->orderBy('RoomName')
            ->get();

        $locations = Location::where('FacilityID', $facility->FacilityID)
            ->orderBy('LocationNameLong')
            ->get();

        // Check if there is data carried from inquiry listing view
        if($request->has("inquiryId")){
            $inquiry_detail = Inquiry::find($request->input("inquiryId"));
        }else{
            $inquiry_detail = NULL;
        }

        $ur = App\Utils\Toolkit::GetURNumber();
        $services = App\Domains\ServiceProduct::orderBy('text')->get();
        return view('admission.add',[
            'facility' => $facility,
            'locations' => $locations,
            'rooms' => $rooms,
            'inquiry_detail' => $inquiry_detail,
            'services' => $services,
            'ur' => $ur
        ]);
    }

    public function store(Request $request){

        Log::debug('enter AdmissionController.store');
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            'location' => 'required',
            'room' => 'required',
        ]);

        $locationId = Input::get('location');
        $roomId = Input::get('room');

        $location = Location::find($locationId);
        $room = Room::find($roomId);
        $serviceId = Input::get('AdmissionType');

        $service = App\Domains\ServiceProduct::find($serviceId);

//        Log::debug('enter store_inquiry');
        $facilityId = $request->session()->get('facility');
        $facility = Facility::find($facilityId);

        $user = HubUser::where('SID', Auth::user()->SID)->get()->first();

        $adm = new Admission();
        $adm->Facility = $facility->FacilityObject;
        $adm->CreatedBy = $user->UserObject;
        $adm->data = $request->all();
        $adm->status = 1;
        $adm->Location = $location->Object;
        $adm->Room = $room->Object;
        $adm->CompletionRate = 0;
        $adm->Language = App::getLocale();
        $adm->Service = $service->Object;

        $resident = $this->createResident($adm, $facility, $location, $room);
        $adm->Resident = $resident->Object;

        $adm->save();

        $adm->process = 'admission';
        $adm->step = 'new';
        $adm->locale = Toolkit::GetLocale();

        RmqHelper::send(env('RMQ_ASSESSMENT_QUEUE'), $adm);

        Resident::addPhoto($resident->ResidentId, 'croppedImage');

        // Set inquiry to "admitted" if data in this admission is carried from inquiry.
        if($request->has("inquiryId")){
            $inquiry_detail = Inquiry::find($request->input("inquiryId"));
            $inquiry_detail->is_admitted = 1;
            $inquiry_detail->save();
        }

        return redirect('admission/listing');
    }

    private function createResident($admission, $facility, $location, $room){
        $first_name = $admission->data['first_name'];
        $last_name = $admission->data['last_name'];
        $dob = $admission->data['dob_submit'];
        $locationId = $admission->data['location'];
        $roomId = $admission->data['room'];
        $urNumber = $admission->data['URNumber'];

        $resident = new Resident();
        $resident->PatientID = Resident::GetNextPatientID();
        $resident->Status = 0;
        $resident->IsTransferred = false;
        $resident->EntryType = 0;
        $resident->NameFirst = $first_name;
        $resident->NameLast = $last_name;
        $resident->DOB = Toolkit::GetDateObject($dob);
        $resident->CaseNumber = $urNumber;

        $resident->LocationID = $location->LocationID;
        $resident->LocationNameLong = $location->LocationNameLong;
        $resident->FacilityID = $facility->FacilityID;
        $resident->FacilityName = $facility->FacilityName;

        $resident->DateTerminated = Toolkit::GetEmptyDateObject();
        $resident->DateDeleted = Toolkit::GetEmptyDateObject();
        $resident->StatusID = 1;

        $resident->Room = $room->RoomName;

        $resident->Facility = $facility->Object;
        $resident->Location = $location->Object;
        $resident->CurrentRoom = $room->Object;
        $resident->Admission = $admission->Object;
        $resident->save();

        return $resident;
    }

    public function view(Request $request, $admissionId){

        $admission = Admission::find($admissionId);
        $resident = Resident::find($admission->Resident['ResidentId']);

        if($resident == null)
            return redirect('admission/listing');

        $resident->CCS = ResidentCCS::GetScore($resident->_id);

        $checklists = array();
        if($admission->Checklist != null) {
            foreach ($admission->Checklist as $checklist) {
                $data = array();
                $tasks = MyCareTask::where('Checklist.ChecklistId', $checklist['ChecklistId'])->get();
                foreach ($tasks as $t) {
                    array_push($data, $t->Object);
                }
                $ret = array();
                $ret['Title'] = $checklist['Title'];
                $ret['Tasks'] = $data;
                array_push($checklists, $ret);
            }
        }
        return view('admission.view', [
                'admission' => $admission,
                'checklists' => $checklists,
                'resident' => $resident
            ]
        );
    }

    public function archive($admissionId){
        $adm = Admission::find($admissionId);
        $adm->status = 0;
        $adm->save();
        return redirect('admission/listing');
    }
}