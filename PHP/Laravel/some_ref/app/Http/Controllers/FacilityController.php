<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Debugbar;
use Illuminate\Support\Facades\Auth;
use App\Domains\HubUser;
use App\Domains\UserFacility;
use App\Domains\Facility;
use App\Domains\Location;
use App\Domains\Room;
use App\Domains\RoomBond;
use App;
use Illuminate\Support\Facades\Log;
use App\Domains\Inquiry;

class FacilityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function select(Request $request, $facilityId){
        $facility = Facility::where('FacilityID', $facilityId);
        if($facility == null)
            return redirect('/facility/show');
        else {
            $request->session()->put('facility', $facilityId);
            return redirect('/resident/search/'.$facilityId);
        }
    }

    public function show(){
        Log::debug('locale is ' .App::getLocale());

        return view('facility.show', $this->getUserAndFacilities());
    }

    public function add(){
        return view('facility.add');
    }

    public function search(Request $request, $facilityId){
        Log::debug('enter facility.search');
        $facility = Facility::find($facilityId);
        $request->session()->put('facility', $facilityId);
        setcookie('facility', $facilityId, 0, "/");
        setcookie('FacilityName', $facility->FacilityName, 0, "/");
        
        return redirect('/resident/search/'.$facilityId);
    }

    public function edit($facilityId){

        return view('facility.edit' , $this->getEditFacilities($facilityId));
    }

    /**
     * mc 23 Add link to Facility view page
     * Author Li
     * */
    public function facilityview($facilityId){

        return view('facility.facilityview' , $this->getFacilityViewContent($facilityId));
    }

    /**
     * mc 24 Add/Update Room to Facility view page
     * Author Li
     * */
    public function editroom($facilityId, $roomId=''){

        return view('facility.editroom' , $this->getRoomContent($facilityId, $roomId));
    }

    /**
     * mc 24 Add/Update Room to Facility view page
     * post method
     * Author Li
     * */
    public function saveroom ($facilityId){

        $rules = [
            'RoomName'=>'required',
            'action'=>'required'
        ];

        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $action = Input::get('action');
        if ($action == 'save' && empty(Input::get('Room_id'))) {
            $action = 'add';
        }

        $facility = Facility::find($facilityId);

        switch ($action) {
            case 'add':
                $room = new Room;
                $room->FacilityID = $facility->FacilityID;
                $room->FacilityName = $facility->FacilityName;
                break;
            case 'save':
                $room = Room::find(Input::get('Room_id'));
                break;
            case 'archive':
                $room = Room::find(Input::get('Room_id'));
                $room->Status = 'archive';
                break;
        }

        $room->RoomName = Input::get('RoomName');
        $room->RoomType = Input::get('RoomType');
        $room->RoomBond = (float)Input::get('RoomBond');
        $room->RoomDayRate = (float)Input::get('RoomDayRate');

        $location = Location::find(Input::get('Location'));
        $room->LocationID = $location->LocationID;
        $room->LocationNameLong = $location->LocationNameLong;
        $room->LocationNameShort = $location->LocationNameShort;

        $room->save();

        return Redirect::to('facility/facilityview/'.$facility->_id)->with('message',trans('mycare.Successfully_Updated'))->withErrors($validator)->withInput();

    }


    /**
     * mc 19 Add/Update Location in a Facility
     * Author Li
     * */
    public function editlocation($facilityId, $locationId=''){

        return view('facility.editlocation' , $this->getLocationContent($facilityId, $locationId));
    }

    /**
     * mc 19 Add/Update Location in a Facility
     * post method
     * Author Li
     * */
    public function savelocation ($facilityId){

        $rules = [
            'LocationNameLong'=>'required',
            'action'=>'required'
        ];

        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $action = Input::get('action');
        if ($action == 'save' && empty(Input::get('Location_id'))) {
            $action = 'add';
        }

        $facility = Facility::find($facilityId);

        switch ($action) {
            case 'add':
                $location = new Location;
                $location->FacilityID = $facility->FacilityID;
                $location->FacilityName = $facility->FacilityName;
                $location->LocationID = Location::max('LocationID') +1;
                break;
            case 'save':
                $location = Location::find(Input::get('Location_id'));
                break;
            case 'archive':
                $location = Location::find(Input::get('Location_id'));
                $location->Status = 'archive';
                break;
        }

        $location->LocationNameLong = Input::get('LocationNameLong');
        $location->LocationNameShort = Input::get('LocationNameLong');
        $location->BuildingNumber = Input::get('BuildingNumber');
        $location->BuildingName = Input::get('BuildingName');
        $location->FloorLevel = Input::get('FloorLevel');
        $location->save();

        return Redirect::to('facility/facilityview/'.$facility->_id)->with('message',trans('mycare.Successfully_Updated'))->withErrors($validator)->withInput();

    }

    public function addfacility(){
        $rules = [
            'FacilityName'=>'required',
            'FacilityCode'=>'required'
        ];
        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $facility = new Facility;
            $facility->FacilityName = Input::get('FacilityName');
            $facility->FacilityCode = Input::get('FacilityCode');
            $facility->RegionCode = trim(Input::get('RegionCode'));
            $facility->RegionName = Input::get('RegionName');
            $facility->AddressLine2 = Input::get('AddressLine2');
            $facility->County = Input::get('County');
            $facility->AddressLine1 = Input::get('AddressLine1');
            $facility->Suburb = Input::get('Suburb');
            $facility->Postcode = Input::get('Postcode');
            $facility->FacilityPhone = Input::get('FacilityPhone');
            $facility->FacilityFax = Input::get('FacilityFax');
            $facility->FacilityEmail = Input::get('FacilityEmail');
            $facility->ContactFirstName = Input::get('ContactFirstName');
            $facility->ContactLastName = Input::get('ContactLastName');
            $facility->ContactPhone = Input::get('ContactPhone');
            $facility->ContactEmail = Input::get('ContactEmail');
            $facility->save();
            return Redirect::to('facility/show')->with('message',trans('mycare.Successfully_Inserted'))->withErrors($validator)->withInput();
        }
    }
    public function updatefacility($facilityId){
        $rules = [
            'FacilityName'=>'required',
            'FacilityCode'=>'required'
        ];
        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $facility = Facility::find($facilityId);
            $facility->FacilityName = Input::get('FacilityName');
            $facility->FacilityID = Input::get('FacilityCode');
            $facility->RegionCode = trim(Input::get('RegionCode'));
            $facility->RegionName = Input::get('RegionName');
            $facility->AddressLine2 = Input::get('AddressLine2');
            $facility->County = Input::get('County');
            $facility->AddressLine1 = Input::get('AddressLine1');
            $facility->Suburb = Input::get('Suburb');
            $facility->Postcode = Input::get('Postcode');
            $facility->FacilityPhone = Input::get('FacilityPhone');
            $facility->FacilityFax = Input::get('FacilityFax');
            $facility->FacilityEmail = Input::get('FacilityEmail');
            $facility->ContactFirstName = Input::get('ContactFirstName');
            $facility->ContactLastName = Input::get('ContactLastName');
            $facility->ContactPhone = Input::get('ContactPhone');
            $facility->ContactEmail = Input::get('ContactEmail');
            $facility->save();
            return Redirect::to('facility/show')->with('message',trans('mycare.Successfully_Updated'))->withErrors($validator)->withInput();
        }

    }
    public function getEditFacilities($facilityId){
        $facility_detail =  Facility::where('_id', $facilityId)->get();

        return [
            'facility_detail' => $facility_detail,
        ];
    }

    private function getUserAndFacilities(){
        /* $facArray = [];
        $sid = Auth::user()->SID;

        $user = HubUser::where('SID', $sid)->get()->first();
//        Debugbar::debug($user);

        $userFacilities = UserFacility::where('userId', $sid)->get();
        foreach ($userFacilities as $uf){
            array_push($facArray, $uf->facilityId);
        }
        $facilities = Facility::whereIn('FacilityID', $facArray)->orderBy('NameLong')->get(); */

        // $facilities = Facility::where('FacilityID', 8888)->get();
        $facilities = Facility::orderBy('NameLong')->get();
        return [
            'facilities' => $facilities,
        ];
    }

    /**
     * Get content for page facilityview
     * Author Li
     * */
    private function getFacilityViewContent($facilityId){
        $facility_detail =  Facility::where('_id', $facilityId)->first();
        $location_list =  Location::where('FacilityID', $facility_detail['FacilityID'])->where('Status', '<>', 'archive')->get();
        $room_list =  Room::where('FacilityID', $facility_detail['FacilityID'])
            ->where('Status', '<>', 'archive')
//            ->orderBy('RoomName', 'asc')
            ->get();

        return [
            'facility_detail' => $facility_detail,
            'location_list' => $location_list,
            'room_list' => $this->natsortArrayOfArray($room_list, 'RoomName')
        ];

    }

    private function natsortArrayOfArray ($arrayInput) {

        $response = array();

        foreach ($arrayInput as $_input) {
            $response[] = $_input;
        }

        usort($response, function($a, $b) {
            preg_match('/^((([\w]+)[\ ])+)([\w]+)?([\d]+)/', $a->RoomName, $match_a);
            preg_match('/^((([\w]+)[\ ])+)([\w]+)?([\d]+)/', $b->RoomName, $match_b);
            if (count($match_a) > 1 && count($match_b) > 1 && trim($match_a[1]) == trim($match_b[1])) {
                return strnatcmp((int)$match_a[count($match_a) -1], (int)$match_b[count($match_b) -1]);
            }
            return strnatcmp($a->RoomName, $b->RoomName); // return (0 if ==), (-1 if <), (1 if >)
        });

        return $response;
    }

    /**
     * Get content for page add_room
     * Author Li
     * */
    private function getRoomContent($facilityId, $roomId){
        $facility = Facility::where('_id', $facilityId)->first();

        return [
            'facility_detail' => $facility,
            'room_detail' => (!empty($roomId))?Room::where('_id', $roomId)->first():array(),
            'locations' => (!empty($facilityId))?Location::where('FacilityID', $facility->FacilityID)->get():array(),
            'roombonds' => RoomBond::all()
        ];

    }

    /**
     * Get content for page add_location
     * Author Li
     * */
    private function getLocationContent($facilityId, $locationId){

        return [
            'facility_detail' => Facility::where('_id', $facilityId)->first(),
            'location_detail' => (!empty($locationId))?Location::where('_id', $locationId)->first():array()
        ];

    }

    public function movement($facilityId){

        $facility = Facility::find($facilityId);

        return view('facility.movement',[
            'facility' => $facility
        ]);
    }




}
