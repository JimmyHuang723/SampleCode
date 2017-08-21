<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use App\Domains\HubUser;
use App\Domains\Facility;
use App\Domains\Resident;
use App\Domains\ResidentCCS;
use App\Domains\Assessment;
use App;
use App\Domains\AssessmentForm;
use App\Utils\RmqHelper;
use App\Domains\TimelineLog;
use App\Domains\FormControl;
use App\Domains\MyCareTask;
use App\Utils\Toolkit;
use Carbon\Carbon;

class AssessmentController extends Controller
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
                ->where('AssessmentCategory',1)->orderBy('FormName')
                ->paginate(15);

            return view('assessment.add',[
                'facility' => $facility,
                'resident' => $resident,
                'forms' => $forms,
                'return_to' => 'assessment.add',
            ]);
        }
        else
            return view('/resident/select/'.$residentId.'/'.$formId);

    }

    // select an assessments based on the parent form
    public function selectparent($residentId, $parentForm, $formId){
        $resident = Resident::find($residentId);
        $parentId = intval($parentForm);
        $form = AssessmentForm::where('FormID', $parentId)->get()->first();
        $childForm = AssessmentForm::find($formId);
        $responses = Assessment::orderBy('updated_at', 'desc')
            ->where('Form.FormID', $parentId)
            ->where('Resident.ResidentId', $residentId)->get();
        return view('assessment.selectparent',
            [ 
                'form' => $form,
                'childForm' => $childForm,
                'resident' => $resident,
                'responses' => $responses ]);
    }

    // the user has confirmed the parent form to use
    public function confirmparent($residentId, $parentAssessmentId, $childFormId){
        $resident = Resident::find($residentId);
        $childForm = AssessmentForm::find($childFormId);
        $parentAssessment = Assessment::find($parentAssessmentId);

        // echo $resident->Fullname . $childForm->FormName . ' of '.$parentAssessment->Form['FormName'];
        
        return redirect('/assessment/select/'.$resident->_id.'/'.$childForm->_id.'/'.$parentAssessment->_id);
    }

    // select the form and open it for the user to enter
    public function select(Request $request, $residentId, $formId, $parentAssessmentId=null){
        $facilityId = $request->session()->get('facility');
        if($facilityId==null) return redirect('/');

        $facility = Facility::find($facilityId);
        $resident = Resident::find($residentId);
        $form = AssessmentForm::find($formId);
        $resident->CCS = ResidentCCS::GetScore($residentId);

        // if this form has a parent
        // check if the parent has been selected
        // if not, prompt the user to select or complete a parent form first
        if($form->ParentFormID != null && $form->ParentFormID > 0 && $parentAssessmentId == null)
        {
            return redirect('/assessment/selectparent/'.$resident->_id.'/'.$form->ParentFormID.'/'.$formId);
        }
        $parentAssessment = null;
        $parentForm = null;
        if($parentAssessmentId != null){
            $parentAssessment = Assessment::find($parentAssessmentId);
            $parentForm = AssessmentForm::find($parentAssessment->Form['FormId']);
        }

        if(substr($form->template,0,9)=='template.') {
            $form_controls = [];
            $use_template_file = true;
            $controls = new FormControl();
        }
        else {
            $form_controls = $form->template_json;
            $use_template_file = false;

            $controls = new FormControl();
            foreach ($form_controls as $cnt){
                $controls->AddControl($cnt);
            }
        }
        return view('assessment.select',[
            'facility' => $facility,
            'resident' => $resident,
            'selectedForm' => $form,
            'use_template_file' => $use_template_file,
            'form_controls' => $form_controls,
            'FormState' => 0,
            'controls' => $controls->controls,
            'data' => [],
            'parentAssessmentId' => $parentAssessmentId,
            'parentAssessment' => $parentAssessment,
            'parentForm' => $parentForm,
        ]);
    }

    public function edit($assessmentId){
        $assessment = Assessment::find($assessmentId);
        $facility = Facility::find($assessment->Facility['FacilityId']);
        $resident = Resident::find($assessment->Resident['ResidentId']);
        $form = AssessmentForm::find($assessment->Form['FormId']);
        $resident->CCS = ResidentCCS::GetScore($resident->_id);
        if(substr($form->template,0,9)=='template.') {
            $form_controls = [];
            $use_template_file = true;
            $controls = new FormControl();
        }
        else {
            $form_controls = $form->template_json;
            $use_template_file = false;

            $controls = new FormControl();
            foreach ($form_controls as $cnt){
                $controls->AddControl($cnt);
            }
        }
        $parentAssessmentId = $assessment->ParentAssessmentId;
        $parentAssessment = null;
        $parentForm = null;
        if($parentAssessmentId != null){
            $parentAssessment = Assessment::find($parentAssessmentId);
            $parentForm = AssessmentForm::find($parentAssessment->Form['FormId']);
        }

        return view('assessment.edit', [
            'facility' => $facility,
            'resident' => $resident,
            'selectedForm' => $form,
            'use_template_file' => $use_template_file,
            'form_controls' => $form_controls,
            'FormState' => $assessment->FormState,
            'controls' => $controls->controls,
            'data' => $assessment->data,
            'assessmentId' => $assessment->_id,
            'parentAssessmentId' => $assessment->ParentAssessmentId,
            'parentAssessment' => $parentAssessment,
            'parentForm' => $parentForm,
        ]);
    }

    public function store(Request $request){
        $facilityId = $request->session()->get('facility');
        if($facilityId==null) return redirect('/');

        $residentId = $request->input('residentId');
        $formId = $request->input('formId');
        $formState = $request->input('FormState');
        $taskId = $request->input('taskId');
        $assessmentId = $request->input('assessmentId');
        $parentAssessmentId = $request->input('parentAssessmentId');
        $data = $request->all();

        $sid = Auth::user()->SID;
        $user = HubUser::where('SID', $sid)->get()->first();

        $resident = Resident::find($residentId);
        $facility = Facility::find($facilityId);
        $form = AssessmentForm::find($formId);

        if($assessmentId == '')
            $assessment = new Assessment();
        else
            $assessment = Assessment::find($assessmentId);

        $assessment->Resident = $resident->Object;
        $assessment->Facility = $facility->Object;
        $assessment->Form = $form->Object;
        $assessment->Version = $form->version;
        $assessment->data = $data;
        $assessment->CreatedBy = $user->Object;
        $assessment->FormState = 0;
        $assessment->ParentAssessmentId = $parentAssessmentId;
        if($formState == '1') {
            $assessment->FormState = 1;
            $assessment->CompletedBy = $user->Object;
            $assessment->CompletedOn = App\Utils\Toolkit::GetTodayObject();
        }
        $assessment->Category = 'assessment';
        if($form->ChartingCategory == 1) $assessment->Category = 'chart';
        else if($form->FormCategory == 1) $assessment->Category = 'form';

        $assessment->save();

        // if this form is linked to a Task and the form is completed
        // set the Task to complete
        if($taskId != ''){
            $task = MyCareTask::find($taskId);
            $task->Assessment = $assessment->Object;
            $task->UpdatedBy = $user->Object;
            if($assessment->FormState == 1){
                $task->Status = 1;
                $task->CompletedBy = $user->Object;
                $task->CompletedOn = App\Utils\Toolkit::GetTodayObject();
            }
            $task->save();
        }

        TimelineLog::Log($assessment->Category, 'create','AssessmentController',
            [
                'facility' => $facility->Object,
                'resident' => $resident->Object,
                'form' => $form->Object,
                'user' => $user->Object,
                'assessment' => $assessment->Object
            ]);


        // post to IFTTN for follow up workflow
        $assessment->process = 'assessment';
        $assessment->step = 'new';
        $assessment->locale = Toolkit::GetLocale();

        RmqHelper::send(env('RMQ_ASSESSMENT_QUEUE'), $assessment);

        if($taskId == '')
            return redirect('/resident/select/'.$residentId);
        else
            return redirect('/task/goback/'.$taskId);
    }

    public function show(){
        $forms = AssessmentForm::where('IsActive', 1)->orderBy('FormName')->get();

        return view('assessment.show', [
            'forms' => $forms
        ]);
    }



    public function view($assessmentId){
        $assessment = Assessment::find($assessmentId);
        $facility = Facility::find($assessment->Facility['FacilityId']);
        $resident = Resident::find($assessment->Resident['ResidentId']);
        $form = AssessmentForm::find($assessment->Form['FormId']);
        $resident->CCS = ResidentCCS::GetScore($resident->_id);
        if(substr($form->template,0,9)=='template.') {
            $form_controls = [];
            $use_template_file = true;
            $controls = new FormControl();
        }
        else {
            $form_controls = $form->template_json;
            $use_template_file = false;

            $controls = new FormControl();
            foreach ($form_controls as $cnt){
                $controls->AddControl($cnt);
            }
        }
        $parentAssessmentId = $assessment->ParentAssessmentId;
        $parentAssessment = null;
        $parentForm = null;
        if($parentAssessmentId != null){
            $parentAssessment = Assessment::find($parentAssessmentId);
            $parentForm = AssessmentForm::find($parentAssessment->Form['FormId']);
        }

        return view('assessment.view', [
            'facility' => $facility,
            'resident' => $resident,
            'selectedForm' => $form,
            'use_template_file' => $use_template_file,
            'form_controls' => $form_controls,
            'FormState' => $assessment->FormState,
            'controls' => $controls->controls,
            'data' => $assessment->data,
            'assessment' => $assessment,
            'parentAssessmentId' => $assessment->ParentAssessmentId,
            'parentAssessment' => $parentAssessment,
            'parentForm' => $parentForm,
        ]);
    }

    public function search($residentId){

        $resident = Resident::find($residentId);
        $resident->CCS = ResidentCCS::GetScore($resident->_id);

        $assessments = Assessment::where('Resident.ResidentId', $residentId)
                                 ->where('Category', 'assessment');

        if(Input::has('date_start')){
            $start = Carbon::createFromFormat('d/m/Y', Input::get('date_start'));
            $assessments = $assessments->where('created_at', '>=', $start);
        }
        if(Input::has('date_end')){
            $end = Carbon::createFromFormat('d/m/Y', Input::get('date_end'));
            //$end = $end->addDay();
            $assessments = $assessments->where('created_at', '<=', $end);
        }

        $assessments = $assessments->orderBy('updated_at', 'desc')
                                   ->paginate(15);

        return view('assessment.search', [
            'resident' => $resident,
            'assessments' => $assessments,
            'category' => 'assessment'
        ]);
    }

    // Find Assessment for <typeahead>
    // $category : 'assessment', 'chart', ...
    public function findahead($category, $residentId){
      
        $name = Input::get('name');
        $assessments = Assessment::where('Resident.ResidentId', $residentId)
                                 ->where('Category', $category)
                                 ->where('FormState', 1)
                                 ->where('Form.FormName', 'like', $name .'%')
                                 ->orderBy('updated_at', 'desc')->take(50)->get();

        // For <typeahead> to show text on auto-complete dropdown list
        foreach ($assessments as $item) {
           $item['screen_name'] = $item->Form['FormName'];
        }

        return $assessments;
    }

    // Find AssessmentForm for <typeahead>
    public function findaheadForm(){
      
        $name = Input::get('name');
        $forms = AssessmentForm::where('IsActive', 1)
                ->where('AssessmentCategory',1)
                ->where('FormName', 'like', $name .'%')
                ->orderBy('FormName')->get();

        // For <typeahead> to show text on auto-complete dropdown list
        foreach ($forms as $item) {
           $item['screen_name'] = $item->FormName;
        }

        return $forms;
    }

}