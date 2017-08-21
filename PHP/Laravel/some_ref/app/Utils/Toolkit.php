<?php

namespace App\Utils;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use App\Domains\Resident;
use App\Domains\MyCareTask;
use App\Domains\AssessmentForm;

class Toolkit {

    public static function GetShortcode($name){
//        $name = str_replace('  ', '', $name);
        $name = str_replace('-', '', $name);
        if(strlen($name) <= 3) return strtoupper($name);
        $code = substr($name, 0, 3);
        $a = explode(' ', $name);
        if(sizeof($a) >= 3){
            $code = substr($a[0],0,1).substr($a[1],0,1).substr($a[2],0,1);
        } else if(sizeof($a) == 2){
            $code = substr($a[0],0,2).substr($a[1],0,1);
        }
        return (int)(strtoupper($code));
    }

    // $date must be a string that can be converted into a Carbon date
    public static function GetDateObject($date){
        $carbon = new Carbon($date);
        $data = array();
        $data['Date'] = $carbon->format(DATE_ISO8601);
        $data['Epoch'] = $carbon->timestamp;
        $data['NoneUtcDate'] = $carbon->format('Y-m-d\TH:i:s');
        return (object)$data;
    }

    public static function GetTodayObject()
    {
        $carbon = new Carbon();
        $data = array();
        $data['Date'] = $carbon->format(DATE_ISO8601);
        $data['Epoch'] = $carbon->timestamp;
        $data['NoneUtcDate'] = $carbon->format('Y-m-d\TH:i:s');
        return (object)$data;
    }

    public static function GetCarbonObject($carbon){
        $data = array();
        $data['Date'] = $carbon->format(DATE_ISO8601);
        $data['Epoch'] = $carbon->timestamp;
        $data['NoneUtcDate'] = $carbon->format('Y-m-d\TH:i:s');
        return (object)$data;
    }

    public static function GetEmptyDateObject(){
        $dt = '0001-01-01T00:00:00';
        return Toolkit::GetDateObject($dt);
    }

    public static function GetURNumber(){

        $ur = rand(100001, 999999);
        for($i = 0; $i < 5000; $i++) {
            $ur = rand(100001, 999999);
            // check if this number is being used
            $residents = Resident::project(['CaseNumber'])->get();
            $numberUsed = false;
            foreach ($residents as $r) {
                if ($r->CaseNumber == $ur) {
                    $numberUsed = true;
                    break;
                }
            }
            if(!$numberUsed) break;
        }

        return $ur;
    }

    public static function Markup($lines){
        $html = '';
        $begin_ul = 0;
        $begin_ol = 0;
        foreach($lines as $line){
            $tag = $tag = substr($line, 0, 1);
            if($tag=='*'){
                if($begin_ul == 0){
                    $begin_ul = 1;
                    $html = $html . '<ul>';  
                }
                $line = str_replace('*', '<li>', $line);
                $html = $html . $line . '</li>';
            }else if($tag=='>'){
                if($begin_ol == 0){
                    $begin_ol = 1;
                    $html = $html . '<ol>';  
                }
                $line = str_replace('>', '<li>', $line);
                $html = $html . $line . '</li>';
            } else {
                if($begin_ul == 1){
                    $begin_ul = 0;
                    $begin_ol = 0;
                    $html = $html . '</ul>';  
                }
                else if($begin_ol == 1){
                    $begin_ul = 0;
                    $begin_ol = 0;
                    $html = $html . '</ol>';  
                }
                $html = $html . $line .'</br>';
            }
        }
        return $html;
    }

    public static function CreateTasks($facility, $resident, $formID, $action, $source, $startDay, $stopDay, $locale){

        Log::debug('create tasks');

        $startDate = (new Carbon())->addDays($startDay);
        $stopDate = (new Carbon())->addDays($stopDay);

        $form = AssessmentForm::where('FormID', $formID)
            ->where('language', $locale)->get()->first();

        if(sizeof($form) > 0) {
            $task = new MyCareTask();
            $task->Title = $form->FormName;
            $task->Form = $form->Object;
            $task->Facility = $facility;
            $task->Resident = $resident;
            $task->Action = $action;
            $task->Source = $source;
            $task->Roles = array();
            $task->StartDate = Toolkit::GetCarbonObject($startDate);
            $task->StopDate = Toolkit::GetCarbonObject($stopDate);
            $task->IsActive = 1;
            $task->State = 'new';
            $task->save();
        }

    }

    public static function GetLocale(){
        $ret = array_get($_COOKIE, 'user_locale');
        if(isset($ret) && !empty($ret))
            return $ret;
        else
            return "en";
    }


}