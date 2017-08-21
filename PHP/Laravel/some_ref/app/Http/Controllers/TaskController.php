<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Domains\MyCareTask;
use App\Domains\HubUser;
use App\Domains\Facility;
use App\Domains\Location;
use App\Domains\Resident;
use App\Domains\ResidentCCS;
use App\Domains\AssessmentForm;
use App\Domains\FormControl;
use App\Domains\Assessment;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function action($taskId){

        $task = MyCareTask::find($taskId);

        if($task->Action=='assessment'){

            $facilityId = $task->Facility['FacilityId'];
            $residentId = $task->Resident['ResidentId'];
            $formId = $task->Form['FormId'];

            $facility = Facility::find($facilityId);
            $resident = Resident::find($residentId);
            $form = AssessmentForm::find($formId);
            $resident->CCS = ResidentCCS::GetScore($resident->_id);

            $controls = new FormControl();
            if(substr($form->template,0,9)=='template.') {
                $form_controls = [];
                $use_template_file = true;
            }
            else {
                $form_controls = $form->template_json;
                $use_template_file = false;
                foreach ($form_controls as $cnt){
                    $controls->AddControl($cnt);
                }

            }
            // this task already has an assessment started or even possibly completed
            $data = [];
            if($task->Assessment != null){
                $assessment = Assessment::find($task->Assessment['AssessmentId']);
                $data = $assessment->data;
            }
            return view('assessment.select',[
                'facility' => $facility,
                'resident' => $resident,
                'selectedForm' => $form,
                'use_template_file' => $use_template_file,
                'form_controls' => $form_controls,
                'FormState' => 0,
                'controls' => $controls->controls,
                'taskId' => $taskId,
                'data' => $data,
                'parentAssessmentId' => null,
                'parentAssessment' => null,
                'parentForm' => null,
            ]);
        }
    }


    public function goback($taskId){
        $task = MyCareTask::find($taskId);
        if($task->Source == 'admission'){
            // return to admission check page

            return redirect('admission/view/'.$task->Admission['AdmissionId']);
        } else if($task->Source == 'post-assessment'){
            return redirect('resident/select/'.$task->Resident['ResidentId']);
        }
    }

    public function search(Request $request){
        $facilityId = $request->session()->get('facility');

        $tasks = MyCareTask::where('Facility.FacilityId', $facilityId)
            ->orderBy('StopDate.Date')
            ->get();

        return view('task.search', [
            'tasks' => $tasks
        ]);
    }


    /**
     * create task list view (MC-148)
     * method: view
     * Author Li
     * */
    public function show () {
        return view('task.show', $this->getTaskListContent());
    }

    private function getTaskListContent () {
        return [
            'tasks' => MyCareTask::all()
        ];
    }

    /**
     * create add Task view (MC-147)
     * method: view
     * Author Li
     * */
    public function add () {
        return view('task.add', $this->getAddTaskContent());
    }

    /**
     * create add Task view (MC-147)
     * method: post
     * Author Li
     * */
    public function addtask(){
        $users = [];
        foreach (HubUser::whereIn('_id', Input::get('AssignedTo'))->get() as $user) {
            $users[] = (object)[
                '_id' => $user->_id,
                'SID' => $user->SID,
                'SGivenNames' => $user->SGivenNames
            ];
        }

        $rules = [
            'Title'=>'required',
            'StartDate'=>'required',
            'AssignedTo'=>'required',
            'FacilityID'=>'required',
            'Status'=>'required'
        ];
        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $task = new MyCareTask;
            $task->Title = Input::get('Title');
            $task->StartDate = ['Date' => Input::get('StartDate')];
            $task->AssignedTo = $users;
            $task->LocationID = Input::get('LocationID');
            $task->ResidentID = Input::get('ResidentID');
            $task->Description = Input::get('Description');
            $task->StopDate = ['Date' => Input::get('StopDate')];
            $task->FacilityID = Input::get('FacilityID');
            $task->Form = Input::get('Form');
            $task->Status = Input::get('Status');
            $task->save();
            return Redirect::to('task/show')->with('message',trans('mycare.Successfully_Inserted'))->withErrors($validator)->withInput();
        }
    }

    private function getAddTaskContent () {
        return [
            'facilities' => Facility::all(),
            'locations' => Location::all(),
            'residents' => Resident::where('StatusID', 1)->get(),
            'users' => HubUser::all()
        ];
    }

}