<?php

namespace App\Http\Controllers;
use App\Domains\CarePlan;
use App\Domains\HubUser;
use App\Domains\Resident;
use App\Domains\AssessmentForm;
use App\Domains\Assessment;
use App\Domains\Facility;
use App\Domains\ProgressNote;
use App\Domains\Topic;
use App\Domains\TimelineLog;
use App\Processes\CalculateCCSProcess;
use App\Processes\CarePlanProcess;
use App\Processes\PostAssessmentProcess;
use League\Flysystem\Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Debugbar;
use App\Processes\AdmissionProcess;
use Illuminate\Support\Facades\Input;
use App\Processes\ResidentOfTheDayProcess;

class ClinicalWorkflowController extends Controller
{
    public function index(Request $request){

        $token = Input::get('token');
        if($token!=env('WORKFLOW_TOKEN')) return 'Test OK';

        $mode = $request->input('mode');
        if($mode == '') $mode = env('IFTTN_MODE');

        $max = $request->input('max');
        if($max == '') $max = env('MAX_MESSAGE_TO_BATCH_PROCESS');

        $messageHandled=0;
        return view('sqs',
        ['mode' => $mode,
            'max' => $max,
            'handled' => $messageHandled]);
    }

    public function fetch(Request $request)
    {
        $token = Input::get('token');
        if($token!=env('WORKFLOW_TOKEN')) return 'WF OK';
        $data = null;

        Log::debug('enter ClinicalWorkflowController');

        $mode = $request->input('mode');
        if($mode == '') $mode = env('IFTTN_MODE');

        $max = $request->input('max');
        if($max == '') $max = env('MAX_MESSAGE_TO_BATCH_PROCESS');

        $connection = new AMQPStreamConnection(env('RMQ_URL'), env('RMQ_PORT'), env('RMQ_USER'), env('RMQ_PASSWORD'));
        $channel = $connection->channel();
        $channel->queue_declare(env('RMQ_ASSESSMENT_QUEUE'), false, true, false, false);

        try{
            $handles = [];
            $mails = array();
            $messageHandled=0;
            for($i = 0; $i < $max; $i++) {
                $result = $channel->basic_get(env('RMQ_ASSESSMENT_QUEUE'), false);
                if($result == null) {
                    Log::debug('no more message in queue');
                    break;
                }
                $message = base64_decode($result->body);
                if (sizeof($message) > 0) {
                    try {
                        $messageHandled++;
                        $handle = $result->delivery_info["delivery_tag"];

                        array_push($handles, $handle);
                        $payloadString = $message;
                        $payload = json_decode($payloadString);
                        Debugbar::debug($payload);
                        if ($payload == null) {
                            Log::debug('$payload is NULL, skip');
                            continue;
                        }
                        // only create progress notes if the form is complete
                        if(property_exists($payload, 'FormState')) {
                            if ($payload->FormState == 1) {
                                if ($payload->process == 'assessment' || $payload->process == 'charting' 
                                    || $payload->process == 'form') {
                                    $this->createProgressNoteForAssessment($payload);
                                }
                            }
                        } else {
                            if($payload->process == 'admission' || $payload->process == 'resident-of-the-day'){
                                $adm = new AdmissionProcess($payload);
                                $adm->Run();
                            } 
                        }
                    }catch(Exception $ex){
                        $this->OnException($ex);
                    }
                }

            }
            if ($mode == 'live') {
                foreach ($handles as $handle) {
                    Log::info('remove message from RMQ');
                    // remove the message from SQS
                    $channel->basic_ack($handle);
                }
            }
        }catch(Exception $ex){
            $ret = true;
            $this->OnException($ex);
        }
        $channel->close();
        $connection->close();
        return view('sqs',
            ['mode' => $mode,
                'max' => $max,
                'handled' => $messageHandled]);
    }

    private function createProgressNoteForAssessment($payload){

//        Debugbar::debug($payload);

        $userId = $payload->CreatedBy->UserId;
        $formId = $payload->Form->FormId;
        $residentId = $payload->Resident->ResidentId;
        $facilityId = $payload->Facility->FacilityId;

        Log::debug('userId='.$userId);
        Log::debug('$formId='.$formId);
        Log::debug('$residentId='.$residentId);
        Log::debug('$facilityId='.$facilityId);

        $resident = Resident::find($residentId);
        $facility = Facility::find($facilityId);
        $user = HubUser::find($userId);
        $form = AssessmentForm::find($formId);
        $today = new Carbon($payload->created_at);

        if($resident == null){
            Log::debug('resident is null, skip');
            return;
        }
        if($facility == null){
            Log::debug('facility is null, skip');
            return;
        }
        if($form == null) {
            Log::debug('form is null, skip');
            return;
        }
        $pn = new ProgressNote();
        $pn->Resident = $resident->Object;
        $pn->notes = $user->Fullname . ' submitted ' . $form->FormName . ' on ' . $today;
        $pn->Form = $form->Object;
        $pn->handover_flag = false;
        $pn->Facility = $facility->Object;
        $pn->CreatedBy = $user->UserObject;
        $pn->process = $payload->process;
        $pn->step = $payload->step;
        $pn->AssessmentId = $payload->_id;
        $pn->DataSource = 'workflow';
        $pn->save();

        TimelineLog::Log('progress-note', 'create','ClinicalWorkflowController',
            [
                'user' => $user->Object,
                'facility' => $facility->Object,
                'resident' => $resident->Object,
                'form' => $form->Object,
                'assessmentId' => $pn->AssessmentId,
                'progressNoteId' => $pn->_id
            ]);

        $cp = new CarePlanProcess();
        $cp->Run($payload, $resident, $facility, $user, $form);

        $pap = new PostAssessmentProcess();
        $pap->Run($payload, $resident, $facility, $user, $form);

        $ccs = new CalculateCCSProcess();
        $ccs->Run($payload, $resident, $facility, $user, $form);


    }

    private function initCarePlan($resident, $facility, $payload, $user){

        $assessment = Assessment::find($payload->_id);

        $careplan = new CarePlan();
        $careplan->Resident = $resident->Object;
        $careplan->Facility = $facility->Object;
        $careplan->Assessment = $assessment->Object;
        $careplan->CreatedBy = $user->UserObject;
        $careplan->UpdatedBy = $user->UserObject;
        $careplan->IsActive = 1;
        $careplan->Goals = [];
        $careplan->Observations = [];
        $careplan->Interventions = [];
        $careplan->Evalutions = [];
        $step = 'create';
        return $careplan;
    }

    public function updateCarePlan($payload, $resident, $facility, $user, $form){

        $step = '';
        $careplan = CarePlan::where('Resident.ResidentId', $resident->_id)
            ->orderBy('updated_at', 'desc')
            ->where('IsActive', 1)
            ->get()->first();
        if($careplan == null) {
            Log::debug('not care plan found, create a new one');
            $careplan = $this->initCarePlan($resident, $facility, $payload, $user);
        } else {
            $careplan->UpdatedBy = $user->UserObject;
            $step = 'update';
        }
        $careplan->uniqid = uniqid("", true);
        $today = new Carbon();

        Debugbar::debug($payload->data);
        $response = $payload->data;
        $form_controls = $form->template_json;
        $updateCarePlanRequired=false;

        $archive = $this->initCarePlan($resident, $facility, $payload, $user);
        $archive->IsActive = 0;

        // go through every control/question in the form to find out if the user has entered something
        foreach($form_controls as $cnt1){
            $cnt = (object)$cnt1;
            // only process question linked to care plan
            if(!property_exists($cnt, 'care_plan')) continue;

            Debugbar::debug($cnt);

            if($cnt->field_type=='text' || $cnt->field_type=='memo'){
                $code = $cnt->code;
//                    Log::debug('text code is '.$code);
                if(property_exists($response, $code)){
                    $array = get_object_vars($response);
                    if($array[$code]!=null){
                        $update = (object)[
                            'code' => $code,
                            'text' => $array[$code],
                            'goal' => $cnt->question['goal'],
                            'user' => $user->UserObject,
                            'created_at' => $today
                        ];
//                            Log::debug('write to care plan');
                        Debugbar::debug($update);

                        $this->appendToCarePlan($careplan, $cnt, $update, $archive);
                        $updateCarePlanRequired=true;

                    } else{
//                            Log::debug('response is null, skip');
                    }
                } else{
//                        Log::debug($code.' not found in response, skip');
                }
            } else if($cnt->field_type=='checkbox'){
                foreach ($cnt->fields as $fld1){
                    $fld = (object)$fld1;
                    $code = $cnt->code.'-'.$fld->code;
//                        Log::debug('checkbox or radio code is '.$code);
                    if(property_exists($response, $code)){
                        $array = get_object_vars($response);
                        if($array[$code]!=null){
                            $update = (object)[
                                'code' => $code,
                                'text' => $fld->text,
                                'goal' => $fld->goal,
                                'user' => $user->UserObject,
                                'created_at' => $today
                            ];
                            Log::debug('write '.$code.' to care plan');
                            Debugbar::debug($update);

                            $this->appendToCarePlan($careplan, $cnt, $update, $archive);
                            $updateCarePlanRequired=true;

                        } else{
//                                Log::debug('response is null, skip');
                        }
                    } else{
//                            Log::debug($code.' not found in response, skip');
                    }
                }
            } else if($cnt->field_type=='radio') {
                foreach ($cnt->fields as $fld1) {
                    $fld = (object)$fld1;
                    $code = $cnt->code;
                    Log::debug('radio code is '.$code);
                    if (property_exists($response, $code)) {
                        $array = get_object_vars($response);
                        if ($array[$code] != null) {
                            $data = (array)$response;
//                            Debugbar::debug($response);
                            if($data[$code] == ($cnt->code.'-'.$fld->code)) {
                                $update = (object)[
                                    'code' => $code,
                                    'text' => $fld->text,
                                    'goal' => $fld->goal,
                                    'user' => $user->UserObject,
                                    'created_at' => $today
                                ];
                                Log::debug('write ' . $code . ' to care plan');

//                                Log::debug('update is');
//                                Debugbar::debug($update);

                                $this->appendToCarePlan($careplan, $cnt, $update, $archive);
                                $updateCarePlanRequired = true;
                            }
                        } else {
//                                Log::debug('response is null, skip');
                        }
                    } else {
//                            Log::debug($code.' not found in response, skip');
                    }
                }
            }
        }
//        if(sizeof($archive->data) > 0)
            $archive->save();

        if($updateCarePlanRequired) {
            $careplan->save();

            TimelineLog::Log('care-plan', $step, 'ClinicalWorkflowController',
                [
                    'facilityId' => $facility->_id,
                    'residentId' => $resident->_id,
                    'formId' => $form->_id,
                    'userId' => $user->_id,
                    'careplanId' => $careplan->_id
                ]);
        }
    }

    private function appendToCarePlan($careplan, $cnt, $update, $archive){
        Log::debug('enter appendToCarePlan');
        Log::debug('$careplan is');
        Debugbar::debug($careplan);

        Log::debug('$cnt is');
        Debugbar::debug($cnt);

        $update->uniqid = $careplan->uniqid;
        Log::debug('$update is');
        Debugbar::debug($update);

        foreach ($cnt->care_plan as $cp) {
            $update->domain = $cp['domain'];
            if($cp['map_to']=='obs') {
                Log::debug('write to care plan - obs');

                $a = (array)$careplan->Observations;

                if(sizeof($a) > 0){
                    Log::debug('code '.$update->code);
                    $this->archivePreviousData($a, $update, $cnt->field_type, $archive, $cp['map_to']);
                    Log::debug('$update now is');
                    Debugbar::debug($update);
                }
                array_push($a, $update);
                $careplan->Observations = $a;

            }
            if($cp['map_to']=='goal') {
                if($update->goal != '') {
                    Log::debug('write to care plan - goal');
                    $a = (array)$careplan->Goals;

                    if(sizeof($a) > 0){
                        Log::debug('code '.$update->code);
                        $this->archivePreviousData($a, $update, $cnt->field_type, $archive, $cp['map_to']);
                        Log::debug('$update now is');
                        Debugbar::debug($update);
                    }

                    array_push($a, $update);
                    $careplan->Goals = $a;
                }
            }
            if($cp['map_to']=='intv') {
                Log::debug('write to care plan - intv');
                $a = (array)$careplan->Interventions;

                if(sizeof($a) > 0){
                    Log::debug('code '.$update->code);
                    $this->archivePreviousData($a, $update, $cnt->field_type, $archive, $cp['map_to']);
                    Log::debug('$update now is');
                    Debugbar::debug($update);
                }
                array_push($a, $update);
                $careplan->Interventions = $a;
            }
        }
    }

    private function archivePreviousData(&$array, $data, $field_type, &$archive, $mapto)
    {
        Log::debug('enter archivePreviousData');
        Log::debug('$array = ');
        Debugbar::debug($array);

        Log::debug('$data = ');
        Debugbar::debug($data);

        Log::debug('$field_type = '.$field_type);

        $archiveBag = array();
        if($mapto=='obs')
            $archiveBag=$archive->Observations;
        else if($mapto == 'goal')
            $archiveBag=$archive->Goals;
        else if($mapto == 'intv')
            $archiveBag=$archive->Interventions;

        $result = array();
        foreach ($array as $a){
            $a = (array)$a;
            $old_uniqid = $a['uniqid'];
            $new_uniqid = $data->uniqid;
            if($field_type=='checkbox'){
                // every time a care plan is created or updated we will obtain a uniqid
                // any item of this uniqid will not be archived
                // this is to prevent items in checkbox got archived by accident
                if(($old_uniqid == $new_uniqid) || !$this->same_parent_code($a['code'], $data->code)) {
                    array_push($result, (object)$a);
                } else {
                    // keep in CarePlanArchive
                    array_push($archiveBag, (object)$a);
                }
            } else if($field_type=='radio') {
                if (($old_uniqid == $new_uniqid) || $a['code'] != $data->code) {
                    array_push($result, (object)$a);
                } else {
                    // keep in CarePlanArchive
                    array_push($archiveBag, (object)$a);
                }
            } else{
                if ($old_uniqid == $new_uniqid) {
                    array_push($result, (object)$a);
                }else{
                    // keep in CarePlanArchive
                    array_push($archiveBag, (object)$a);
                }
            }
        }
        if($mapto=='obs')
            $archive->Observations = $archiveBag;
        else if($mapto == 'goal')
            $archive->Goals = $archiveBag;
        else if($mapto == 'intv')
            $archive->Interventions = $archiveBag;
        $array = $result;
    }

    private function same_parent_code($code1, $code2){

        Log::debug('code1='.$code1);
        Log::debug('code2='.$code2);
        $ret = false;
        $c1 = explode('-', $code1);
        $c2 = explode('-', $code2);
        if(sizeof($c1) >= 2 && sizeof($c2) >= 2){
            $ret = ($c1[0]==$c2[0]);
        }
        return $ret;
    }

    public function onException($ex){
        Log::debug("onException");
        Log::debug($ex->getMessage());

        // notify helpdesk
        $topic = new Topic();
        $title = 'ClinicalWorkflow - '.$ex->getMessage();
        $content = '';
        $topic->CallHelpdesk($title, $content, $this->Message);
    }

}