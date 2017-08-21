<?php

namespace App\Processes;

use App\Domains\Assessment;
use App\Domains\CarePlan;
use App\Domains\CarePlanAssessment;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Log;
use Debugbar;
use Carbon\Carbon;

class CarePlanProcess extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'wfCarePlanProcess';

    function __construct()
    {
    }

    public function Run($payload, $resident, $facility, $user, $form)
    {
        $assessmentId = $payload->_id;
        $assessment = Assessment::find($assessmentId);
        if($assessment == null){
            return;
        }
        // get resident's care plan, if none, create one

        $careplan = $this->getCarePlan($resident, $facility, $user, $assessmentId);

        $response = $payload->data;
        $form_controls = $form->template_json;

        // if there is template_json, we are unable to create care plan
        if($form_controls == null) return;

        $cpa = CarePlanAssessment::where('CarePlan.CarePlanId', $careplan->_id)
            ->where('Form.FormId', $form->_id)->get()->first();
        if($cpa == null) {
            $cpa = new CarePlanAssessment();
            $cpa->Form = $form->Object;
            $cpa->Resident = $resident->Object;
            $cpa->CarePlan = $careplan->Object;
            $cpa->Goals = [];
            $cpa->Observations = [];
            $cpa->Interventions = [];
        } else {
            // TODO: archive previous data
            $cpa->Resident = $resident->Object;
            $cpa->Goals = [];
            $cpa->Observations = [];
            $cpa->Interventions = [];
        }
        $cpa->LastAssessment = $assessment->Object;
        $cpa->save();

        foreach($form_controls as $cnt1){

            $cnt = (object)$cnt1;
            // only process question linked to care plan
            if(!property_exists($cnt, 'care_plan')) continue;

            if(!$this->userResponseFound($cnt, $response)) continue;

            $this->populateCarePlan($cnt, $response, $careplan, $user, $assessment, $form);


        }
//        Debugbar::debug($careplan);
        $careplan->LastAssessment = $assessment->Object;
        $careplan->save();
    }

    // $cnt = complete question
    // $update = user response
    private function updateCarePlan($careplan, $cnt, $update, $form){
        Log::debug('enter updateCarePlan, field_type='.$cnt->field_type.', code='.$cnt->code);

        $cpa = CarePlanAssessment::where('CarePlan.CarePlanId', $careplan->_id)
            ->where('Form.FormId', $form->_id)->get()->first();

        // work out goal, obs and intv given $cnt and $update
        foreach ($cnt->care_plan as $cp) {
            $update->domain = $cp['domain'];
            if($cp['map_to']=='obs') {
                Log::debug('write to care plan - obs');
                $a = (array)$cpa->Observations;
                array_push($a, $update);
                $cpa->Observations = $a;
            }
            if($cp['map_to']=='goal') {
                if($update->goal != '') {
                    Log::debug('write to care plan - goal');
                    $a = (array)$cpa->Goals;
                    array_push($a, $update);
                    $cpa->Goals = $a;
                }
            }
            if($cp['map_to']=='intv') {
                Log::debug('write to care plan - intv');
                $a = (array)$cpa->Interventions;
                array_push($a, $update);
                $cpa->Interventions = $a;
            }
        }
        $cpa->save();
    }

    private function populateCarePlan($cnt, $response, $careplan, $user, $assessment, $form)
    {
        Log::debug('enter populateCarePlan');
        $today = new Carbon();
        if($cnt->field_type=='text' || $cnt->field_type=='memo'){
            $code = $cnt->code;
            $array = get_object_vars($response);
            $update = (object)[
                'code' => $code,
                'text' => $array[$code],
                'goal' => $cnt->question['goal'],
                'user' => $user->UserObject,
                'created_at' => $today
            ];
            Log::debug('write ' . $code . ' to care plan');
            Debugbar::debug($update);
            $this->updateCarePlan($careplan, $cnt, $update, $form);

        } else if($cnt->field_type=='checkbox'){
            foreach ($cnt->fields as $fld1){
                $fld = (object)$fld1;
                $code = $cnt->code.'-'.$fld->code;
                Log::debug('checkbox code is '.$code);
                if(property_exists($response, $code)) {
                    $array = get_object_vars($response);
                    if ($array[$code] != null) {
                        $update = (object)[
                            'code' => $code,
                            'text' => $fld->text,
                            'goal' => $fld->goal,
                            'user' => $user->UserObject,
                            'created_at' => $today
                        ];
                        Log::debug('write ' . $code . ' to care plan');
                        Debugbar::debug($update);

                        $this->updateCarePlan($careplan, $cnt, $update, $form);

                    } else {
                        Log::debug('response is null, skip');
                    }
                }
            }
        } else if($cnt->field_type=='radio') {
            foreach ($cnt->fields as $fld1) {
                $fld = (object)$fld1;
                $code = $cnt->code;
                Log::debug('radio code is '.$code);
                $array = get_object_vars($response);
                if ($array[$code] != null) {
                    $data = (array)$response;
                    if($data[$code] == ($cnt->code.'-'.$fld->code)) {
                        $update = (object)[
                            'code' => $code,
                            'text' => $fld->text,
                            'goal' => $fld->goal,
                            'user' => $user->UserObject,
                            'created_at' => $today
                        ];
                        Log::debug('write ' . $code . ' to care plan');
                        Debugbar::debug($update);
                        $this->updateCarePlan($careplan, $cnt, $update, $form);
                    }
                } else {
                    Log::debug('response is null, skip');
                }
            }
        }
    }

    private function userResponseFound($cnt, $response)
    {
        Log::debug('enter userResponseFound');
        $foundIt=false;
        if($cnt->field_type=='text' || $cnt->field_type=='memo'){
            $code = $cnt->code;
            Log::debug('text code is '.$code);
            if(property_exists($response, $code)){
                $array = get_object_vars($response);
                if($array[$code]!=null){
                    $foundIt = true;
                } else{
                    Log::debug('response is null, skip');
                }
            } else{
                Log::debug($code.' not found in response, skip');
            }
        } else if($cnt->field_type=='checkbox'){
            foreach ($cnt->fields as $fld1){
                $fld = (object)$fld1;
                $code = $cnt->code.'-'.$fld->code;
                Log::debug('checkbox or radio code is '.$code);
                if(property_exists($response, $code)){
                    $array = get_object_vars($response);
                    if($array[$code]!=null){
                        $foundIt=true;
                    } else{
                        Log::debug('response is null, skip');
                    }
                } else{
                    Log::debug($code.' not found in response, skip');
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
                            $foundIt=true;
                        }
                    } else {
                        Log::debug('response is null, skip');
                    }
                } else {
                    Log::debug($code.' not found in response, skip');
                }
            }
        }
        return $foundIt;
    }

    private function getCarePlan($resident, $facility, $user, $assessmentId)
    {
        Log::debug('enter getCarePlan');
        $careplan = CarePlan::where('Resident.ResidentId', $resident->_id)
            ->orderBy('updated_at', 'desc')
            ->where('IsActive', 1)
            ->get()->first();
        if($careplan == null) {
            Log::debug('not care plan found, create a new one');
            $careplan = $this->initCarePlan($resident, $facility, $assessmentId, $user);
        } else {
            $careplan->UpdatedBy = $user->UserObject;
        }
        $careplan->uniqid = uniqid("", true);
        return $careplan;
    }


    private function initCarePlan($resident, $facility, $assessmentId, $user){
        Log::debug('enter initCarePlan');

        $assessment = Assessment::find($assessmentId);

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
        return $careplan;
    }
}