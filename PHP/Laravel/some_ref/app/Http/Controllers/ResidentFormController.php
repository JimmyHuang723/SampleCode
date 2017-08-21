<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Domains\HubUser;
use App\Domains\Resident;
use App\Domains\ResidentCCS;
use App\Domains\Facility;
use App;
use App\Domains\AssessmentForm;
use App\Domains\Assessment;
use App\Domains\TimelineLog;
use App\Utils\RmqHelper;
use App\Domains\FormControl;
use Carbon\Carbon;

class ResidentFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(Request $request, $residentId, $formId = 0){
        $facilityId = $request->session()->get('facility');
        if($facilityId==null) return redirect('/');

        $resident = Resident::find($residentId);


        $facility = Facility::find($facilityId);
        $resident->CCS = ResidentCCS::GetScore($resident->_id);

        if($formId == 0) {
            $forms = AssessmentForm::where('IsActive', 1)
                ->where('FormCategory',1)->orderBy('FormName')->get();
            return view('residentform.add',[
                'facility' => $facility,
                'resident' => $resident,
                'forms' => $forms,
                'return_to' => 'charting.add',
            ]);
        }
        else
            return view('/resident/select/'.$residentId.'/'.$formId);
    }

    public function select(Request $request, $residentId, $formId){
        $facilityId = $request->session()->get('facility');
        if($facilityId==null) return redirect('/');

        $facility = Facility::find($facilityId);
        $resident = Resident::find($residentId);
        $form = AssessmentForm::find($formId);
        if(substr($form->template,0,9)=='template.') {
            $form_controls = [];
            $use_template_file = true;
        }
        else {
            $form_controls = $form->template_json;
            $use_template_file = false;
            $controls = new FormControl();
            foreach ($form_controls as $cnt){
                $controls->AddControl($cnt);
            }
        }
        return view('residentform.select',[
            'facility' => $facility,
            'resident' => $resident,
            'selectedForm' => $form,
            'use_template_file' => $use_template_file,
            'form_controls' => $form_controls,
            'FormState' => 0,
            'controls' => $controls->controls,
        ]);
    }

    public function store(Request $request){
        $facilityId = $request->session()->get('facility');
        if($facilityId==null) return redirect('/');

        $residentId = $request->input('residentId');
        $template = $request->input('template');
        $formName = $request->input('formName');
        $formId = $request->input('formId');
        $formState = $request->input('FormState');

        $sid = Auth::user()->SID;
        $user = HubUser::where('SID', $sid)->get()->first();
        $form = AssessmentForm::find($formId);

        $data = $request->all();
        $assessment = new Assessment();
        $assessment->residentId = $residentId;
        $assessment->facilityId = $facilityId;
        $assessment->formId = $form->_id;
        $assessment->version = $form->version;
        $assessment->formName = $formName;
        $assessment->data = $data;
        $assessment->CreatedBy = $user->UserObject;
        $assessment->category = 'chart';
        $assessment->FormState = 0;
        if($formState == '1') $assessment->FormState = 1;

        $assessment->save();

        TimelineLog::Log('residentform', 'create','ChartingController',
            [
                'facility' => $facility->Object,
                'resident' => $resident->Object,
                'form' => $form->Object,
                'user' => $user->Object,
                'assessmentId' => $assessment->_id
            ]);


        // post to IFTTN for follow up workflow
        $assessment->process = 'residentform';
        $assessment->step = 'new';
        $assessment->locale = Toolkit::GetLocale();

        RmqHelper::send(env('RMQ_ASSESSMENT_QUEUE'), $assessment);

        return redirect('/resident/select/'.$residentId);
    }

    public function view($assessmentId){
        return redirect('/assessment/view/'.$assessmentId);
    }

    public function search($residentId){

        $resident = Resident::find($residentId);
        $resident->CCS = ResidentCCS::GetScore($resident->_id);

        $assessments = Assessment::where('Resident.ResidentId', $residentId)
                                 ->where('Category', 'form');
            
        if(Input::has('date_start')){
            $start = Carbon::createFromFormat('d/m/Y', Input::get('date_start'));
            $assessments = $assessments->where('created_at', '>=', $start);
        }
        if(Input::has('date_end')){
            $end = Carbon::createFromFormat('d/m/Y', Input::get('date_end'));
            $assessments = $assessments->where('created_at', '<=', $end);
        }

        $assessments = $assessments->orderBy('updated_at', 'desc')
                                   ->paginate(15);

        return view('assessment.search', [
            'resident' => $resident,
            'assessments' => $assessments,
            'category' => 'form'
        ]);
    }
}