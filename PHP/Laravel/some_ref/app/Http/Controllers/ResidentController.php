<?php

namespace App\Http\Controllers;

use App\Domains\AssessmentForm;
use App\Domains\BusinessRules;
use App\Domains\ResidentCCS;
use App\Domains\TimelineLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Debugbar;

use App\Domains\Resident;
use App\Domains\Facility;
use App\Domains\ProgressNote;
use App\Domains\Assessment;
use App\Domains\CarePlan;
use App\Domains\HubUser;
use App\Domains\MyCareTask;
use Charts;
use App;
use Session;

class ResidentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        $currPage = $request->input('currpage');
        if($currPage == '' || $currPage <= 1) $currPage = 1;
        Debugbar::debug($currPage);

        $limit = $request->input('limit');
        if($limit == '') $limit = 10;

        $result = Resident::all()->forPage($currPage, $limit);

//        Debugbar::info($result);
        return view('resident', ['residents' => $result,
        'currpage' => $currPage,
        'limit' => $limit]);
    }
	
	
    public function autocomplete(Request $request) {
        $name = $request->input('key');
        $facilityId = $request->session()->get('facility');
        if($facilityId==null) return redirect('/');

        $facility = Facility::find($facilityId);
//        Debugbar::debug($facilityId);
        $residents = Resident::where('FacilityID', $facility->FacilityID)
            ->where('StatusID', 1)
            ->where(function($query) use ($name){
                $query->where('NameFirst', 'like', $name .'%')
                    ->orWhere('NameLast', 'like', $name .'%');
            })->orderBy('NameLast')
            ->orderBy('NameFirst')->get();
            $result = $residents->toArray();
            $data = array();
            foreach($result as $resident){
              	$d = array(
                    'label' => $resident['NameLast']. ', '.$resident['NameFirst'],
                    'id' => $resident['_id']
                );
                array_push($data, $d);
            }
        return json_encode($data);
       
    }

    public function facility($FacilityID){
//        Debugbar::debug($FacilityID);
        $currPage = 1;
        $limit = 10;
        $result = Resident::where('FacilityID', (int)$FacilityID)->get()->forPage($currPage, $limit);

        Debugbar::debug(sizeof($result));

        return view('resident', ['residents' => $result,
            'currpage' => $currPage,
            'limit' => $limit]);
    }

    public function view($ResidentId){

        $result = Resident::find($ResidentId);
//        echo $result;

        return view('resident.view', [
            'resident' => $result
        ]);
    }

    public function search1(Request $request){
        $facilityId = $request->session()->get('facility');

        if($facilityId == null)
            return redirect('/facility/show');
        else
            return redirect('/resident/search/'.$facilityId);
    }

    public function search($facilityId){
        $facility = Facility::find($facilityId);
        $residents = Resident::
            orderBy('Room')
            ->orderBy('NameLast')
            ->orderBy('NameFirst')
            ->where('FacilityID', $facility->FacilityID)
            ->where('StatusID', 1)
            //->where('DateTerminated.Epoch', '<', 0)
            ->paginate(15);

            return view('resident.search', [
                'residents' => $residents
            ]);
    }

    public function search_with_activities($facilityId){

        $sid = Auth::user()->SID;

        if($sid == null || $sid == ''){
            return redirect('/user');
        }
        $user = HubUser::where('SID', $sid)->get()->first();
        $facility = Facility::find($facilityId);
        $activities = TimelineLog::GetUserActivities($user->_id, 10);
        foreach ($activities as $act) {
            $aFacility = Facility::find($act->related['facilityId']);
            $aResident = Resident::find($act->related['residentId']);
            $langcode = 'mycare.'.$act->step;
            if($act->process == 'progress-note'){
                $act->text = __($langcode).' ' . __('mycare.progress_notes') .' - ' . $aResident->Fullname;
                $act->link = '/progressnote/view/'.$act->related['progressNoteId'];
            } else if($act->process == 'care-plan'){
                $act->text = __($langcode).' ' . __('mycare.care_plan') .' - ' . $aResident->Fullname;
                $act->link = '/careplan/view/'.$aResident->_id;
            } else if($act->process == 'assessment'){
                $aForm = AssessmentForm::find($act->related['formId']);
//                Debugbar::debug($form);
                $act->text = __($langcode).' ' . $aForm->FormName .' - ' . $aResident->Fullname;
                $act->link = '/assessment/view/'.$act->related['assessmentId'];
            } else if($act->process == 'chart') {
                $aForm = AssessmentForm::find($act->related['formId']);
//                Debugbar::debug($form);
                $act->text = __($langcode) . ' ' . $aForm->FormName . ' - ' . $aResident->Fullname;
                $act->link = '/assessment/view/' . $act->related['assessmentId'];
            }
            $act->timestamp = new Carbon($act->created_at);
        }
//        Debugbar::debug($facility);

        $tasks = MyCareTask::where('Facility.FacilityId', $facilityId)
            ->orderBy('StopDate.Date')
            ->take(20)
            ->get();

//        dd($activities);
        return view('resident.search', [
            'facility' => $facility,
            'activities' => $activities,
            'tasks' => $tasks,
        ]);
    }

    public function browse(Request $request){

//        $this->validate($request, [
//            'name' => 'required'
//        ]);

        $name = $request->input('name');

        $facilityId = $request->session()->get('facility');

        if($facilityId == null)
            return redirect('/facility/show');

        $facility = Facility::find($facilityId);
        $residents = Resident::where('FacilityID', $facility->FacilityID)
            ->where('StatusID', 1)
            ->where(function($query) use ($name){
                $query->where('NameFirst', 'like', $name .'%')
                    ->orWhere('NameLast', 'like', $name .'%');
            })->orderBy('NameLast')
            ->orderBy('NameFirst')->take(50)->get();

        return view('resident.browse',[
            'facility' => $facility,
            'residents' => $residents
        ]);
    }

    public function find(Request $request, $name){
        $facilityId = $request->session()->get('facility');
//        Debugbar::debug($facilityId);

        if($facilityId == null)
            return redirect('/facility/show');

        $facility = Facility::find($facilityId);
        $result = Resident::where('FacilityID', $facility->FacilityID)
            ->where(function($query) use ($name){
                $query->where('NameFirst', 'like', $name .'%')
                    ->orWhere('NameLast', 'like', $name .'%');
            })->get();

        return $result;
    }

    public function findahead(Request $request){
        $facilityId = $request->session()->get('facility');

        $name = Input::get('name');
        $facility = Facility::find($facilityId);
        $result = Resident::orderBy('NameLast')
            ->orderBy('NameFirst')
            ->where('FacilityID', $facility->FacilityID)
            ->where('StatusID', 1)
            //->where('Status', 1)
            ->where(function($query) use ($name){
                $query->where('NameFirst', 'like', $name .'%')
                    ->orWhere('NameLast', 'like', $name .'%');
            })->get();


        // For <typeahead> to show text on auto-complete dropdown list
        foreach ($result as $item) {
           $item['screen_name'] = $item['NameLast'] . ', ' . $item['NameFirst'];
        }

        return $result;
    }


    public function select(Request $request, $residentId, $today = null){
        $facilityId = $request->session()->get('facility');
        if($facilityId==null) return redirect('/');

        $facility = Facility::find($facilityId);
        $resident = Resident::find($residentId);
        if($resident == null){
            Log::debug('no resident found');
        }
        $resident->CCS = ResidentCCS::GetScore($residentId);

        $careplan = CarePlan::where('Resident.ResidentId', $resident->_id)
            ->where('IsActive', 1)
            ->get()->first();

        $maxDays = 5;
        if($today == null)
            $toDate= Carbon::now();
        else
            $toDate = new Carbon($today);

        $fromDate= $toDate->subDays($maxDays);
        $timelines = TimelineLog::GetResidentActivities($resident->_id, $fromDate, $toDate);

        $dt = $fromDate;
        $timeline_days=[];
        for($i = 0; $i < $maxDays; $i++){
            $dt->addDay(1);

            $code = $dt->format('Y-m-d');
            $text = $dt->format('d/m/Y');
            $a = [
                'text' => $text,
                'code' => $code
            ];
            array_push($timeline_days, $a);
        }
        $timeline_hours=[
            [ 'text' => 'AM', 'code' => 'am'],
            [ 'text' => 'PM', 'code' => 'pm'],
            [ 'text' => 'Night', 'code' => 'night'],
        ];

//        for($i = 0; $i < 24; $i++) {
//            if($i )
//            $h = ($i + 7 > 23 ? $i + 7 - 24: $i + 7);
//            $code = number_format($h);
//            $text = $h;
//            $a = [
//                'text' => $text,
//                'code' => $code
//            ];
//            array_push($timeline_hours, $a);
//        }
//        Debugbar::debug($timeline_hours);

        $timeline_values=[];
        foreach ($timelines as $t) {
            // skip automatic generated timelines, we only need user created timeline
            if($t->process == 'progress-note' && $t->module=='ClinicalWorkflowController') {
                    continue;
            }
            $t->timestamp = new Carbon($t->created_at);
            $hour = $t->timestamp->hour;
            if($hour >=7 && $hour <=15)
                $code = 'am';
            else if($hour > 15 && $hour <= 23)
                $code = 'pm';
            else
                $code = 'night';
            $code = $t->timestamp->format('Y-m-d').'-'.$code;
            $link = '';
            $text = '';
            $action = '';
            if($t->process == 'progress-note'){
                $action = 'pn';
                $text = 'pn';
                $link = '/progressnote/view/'.$t->related['progressNoteId'];
            } else if($t->process == 'care-plan'){
                $action = 'cp';
                $text = 'cp';
                $link = '/careplan/view/'.$t->related['resident']['ResidnetId'];
            } else if($t->process == 'assessment'){
                $action = 'fm';
                $text = 'fm';
                $link = '/assessment/view/'.$t->related['assessment']['AssessmentId'];
            } else if($t->process == 'chart'){
                $action = 'ch';
                $text = 'ch';
                $link = '/charting/view/'.$t->related['assessment']['AssessmentId'];
            }
            $a = [
                'action' => $action,
                'text' => $text,
                'link' => $link,
            ];
            if(!array_key_exists($code, $timeline_values))
                $timeline_values[$code] = [];
            array_push($timeline_values[$code], $a);
        }
        Debugbar::debug($timeline_days);
        Debugbar::debug($timeline_hours);
        Debugbar::debug($timeline_values);

        return view('resident.select', [
            'facility' => $facility,
            'resident' => $resident,
            'has_careplan' => ($careplan==null?false:true),
            'careplan' => $careplan,
            'timelines' => $timelines,
            'timeline_days' => $timeline_days,
            'timeline_hours' => $timeline_hours,
            'timeline_values' => $timeline_values,
            'today' => Carbon::now()->format('y-m-d'),
            'selectedDay' => $toDate->format('y-m-d'),
            ]);
    }

    public function edit(Request $request, $residentId){
        $resident = Resident::find($residentId);
        $facilityId = $request->session()->get('facility');
        $facility = Facility::find($facilityId);
        $services = App\Domains\ServiceProduct::orderBy('text')->get();

        return view('resident.edit',
            [
                'facility' => $facility,
                'resident' => $resident,
                'services' => $services
            ]);
    }

    public function update(Request $request){
        $this->validate($request, [
            'residentId' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'status_id' => 'required',
        ]);

        $residentId = Input::get('residentId');
        $resident = Resident::find($residentId);

        $residentData = $request->except([
                                      "residentId", 
                                      "URNumber", // read only
                                      "admission_type", // read only
                                      "croppedImage",
                                      "first_name", 
                                      "last_name",  
                                      "status_id"   
                                       ]);
        $resident->update($residentData);

        $resident->NameFirst = Input::get('first_name');
        $resident->NameLast = Input::get('last_name');
        $resident->StatusID = (Input::get('status_id') == '1'? 1: 0);
        $resident->save();

        Resident::addPhoto($residentId, 'croppedImage');

        return redirect('/resident/select/'.$residentId);
    }

    public function movement(Request $request, $residentId){
        $resident = Resident::find($residentId);
        $facilityId = $request->session()->get('facility');
        $facility = Facility::find($facilityId);

        return view('resident.movement',[
            'resident' => $resident,
            'facility' => $facility,
        ]);
    }

    public function roomchange(Request $request, $residentId){
        $resident = Resident::find($residentId);
        $facilityId = $request->session()->get('facility');
        $facility = Facility::find($facilityId);

        return view('resident.roomchange',[
            'resident' => $resident,
            'facility' => $facility,
        ]);
    }

    public function locationchange(Request $request, $residentId){
        $resident = Resident::find($residentId);
        $facilityId = $request->session()->get('facility');
        $facility = Facility::find($facilityId);

        return view('resident.locationchange',[
            'resident' => $resident,
            'facility' => $facility,
        ]);
    }

    public function timeline($residentId, $today, $days){
        $days = (int) $days;
        $dt = new Carbon($today);
        if($days > 0)
            $dt->addDay($days);
        else
            $dt->subDays($days * -1);

        return redirect('/resident/select/'.$residentId.'/'.$dt->format('Y-m-d'));
    }

    public function bgl($residentId){
        $resident = Resident::find($residentId);

        $rule = BusinessRules::where('Process', 'bgl')
            ->where('Action', 'plot_chart')
            ->get()
            ->first();
        $formCode = $rule->FormID;
//        dd($formCode);

        $data = Assessment::where('Resident.ResidentId', $residentId)
            ->where('Form.FormID', $formCode)
            ->where('FormState', 1)
            ->orderBy('updated_at')
            ->get();

        $dataset = [];
        $labels = [];
        foreach ($data as $d){
            $val = $d->data[$rule->FieldCode];
            array_push($dataset, $val);
            array_push($labels, $d->data['BGL1_submit']);
        }

        if(sizeof($dataset) > 0) {
            $chart = Charts::multi('line', 'highcharts')
                // Setup the chart settings
                ->title(__('mycare.bgl_chart'))
                ->dimensions(600, 400)// Width x Height
                ->dataset(__('mycare.bgl'), $dataset)
                ->labels($labels);


            return view('resident.bgl', [
                'resident' => $resident,
                'bgl_data' => $data,
                'field_code' => $rule->FieldCode,
                'chart' => $chart
            ]);
        } else {
            return redirect('resident/select/'.$residentId);
        }
    }
}