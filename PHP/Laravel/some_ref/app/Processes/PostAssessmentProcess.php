<?php

namespace App\Processes;

use App\Domains\AssessmentForm;
use App\Domains\BusinessRules;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Log;
use Debugbar;
use App\Domains\MyCareTask;
use App\Utils\Toolkit;
use Carbon\Carbon;
use App\Utils\RmqHelper;
use App\Processes\ResidentMovementProcess;
use App\Processes\HandoverProcess;

class PostAssessmentProcess extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'wfPostAssessmentProcess';

    function __construct()
    {
    }

    public function Run($payload, $resident, $facility, $user, $form){

        Log::debug('enter PostAssessmentProcess.Run');
        Log::debug('form='.$form->FormName.', FormID='.$form->FormID);
        Debugbar::debug($payload);
        // dd($payload);
        $bizrules = BusinessRules::where('Form.FormID', $form->FormID)->get();
        Log::debug('bizrules found = '.sizeof($bizrules));
        foreach ($bizrules as $bizrule){
            $rules = $bizrule->Rules;
            Log::debug('rules found = '.sizeof($rules));
            
            foreach($rules as $rule){
                // Debugbar::debug($rule);
                if($this->passRule($payload, $rule)){
                    Log::debug('passed');
                    $this->doRule($payload, $rule);
                }
            }
        }
        Log::debug('exit PostAssessmentProcess.Run');

    }

    public function doRule($payload, $rule){
        Debugbar::debug($rule);
        $action = array_get($rule['Then'], 'Action');
        Log::debug('action='.$action);

        $data = (array)$payload->data;
        if($action=="post-message"){
            $queue = array_get($rule['Then'], 'Queue');
            if(isset($queue) && !empty($queue)){
                RmqHelper::Send($queue, $payload);
                Log::debug('post message to '.$queue);
            }
        } else if($action == 'resident-movement' || $action=='room-change'){
            $rm = new ResidentMovementProcess();
            $rm->Run($payload, $action);
        } else if($action=="update-handover"){
            $rm = new HandoverProcess();
            $rm->Run($payload, $rule);
        } else if($action == 'assessment'){
            $facility = $payload->Facility;
            $resident = $payload->Resident;
            $locale = $payload->locale;
            $formID = array_get($rule['Then'], 'FormID');
            $startDay = intval(array_get($rule['Then'], 'StartDay'));
            $stopDay = intval(array_get($rule['Then'], 'StopDay'));
            $source = "workflow";
            Toolkit::CreateTasks($facility, $resident, $formID, $action, $source, $startDay, $stopDay, $locale);
        }
        
    }

    public function passRule($payload, $rule){
        $ret = false;
        $data = (array)$payload->data;
        $fieldCode = $rule['If']['Field'];
        if($fieldCode=='') return true;

        $operand = $rule['If']['Operand'];
        $value = $rule['If']['Value'];
        if($value=="NULL") $value=null;

        $val = array_get($data, $fieldCode);
        //dd($val);
        if(isset($val) && !empty($val)){
            if($operand=="=")
                $ret = ($value==$val);
            else if($operand==">=")
                $ret = ($value>=$val);
            else if($operand==">")
                $ret = ($value>$val);
            else if($operand=="<")
                $ret = ($value<$val);
            else if($operand=="<=")
                $ret = ($value <= $val);
            else if($operand=="!=")
                $ret = ($value != $val);
        }
        return $ret;
    }
}