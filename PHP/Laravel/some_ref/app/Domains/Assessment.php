<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
// http://carbon.nesbot.com/docs/
use Carbon\Carbon;
use App\Domains\AssessmentForm;

class Assessment extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Assessment';

    public static function GetLastNAssessments($residentId, $max){

        $assessments = Assessment::where('Resident.ResidentId', $residentId)
            ->where('Category', 'assessment')
            ->orderBy('updated_at', 'desc')->take($max)->get();

        return $assessments;
    }

    public static function GetLastNCharts($residentId, $max){

        $assessments = Assessment::where('Resident.ResidentId', $residentId)
            ->where('Category', 'chart')
            ->orderBy('updated_at', 'desc')->take($max)->get();

        return $assessments;
    }

    public static function GetLastNForms($residentId, $max){

        $assessments = Assessment::where('Resident.ResidentId', $residentId)
            ->where('Category', 'form')
            ->orderBy('updated_at', 'desc')->take($max)->get();

        return $assessments;
    }

    public function GetObjectAttribute(){
        $data = array();
        $data['AssessmentId'] = $this->_id;
        if(is_array($this->Form))
            $data['FormName'] = $this->Form['FormName'];
        else
            $data['FormName'] = $this->Form->FormName;

        return (Object)$data;
    }

    public function GetCompletedByUserAttribute(){
        if(!isset($this->CompletedOn)) return '';
        if(!isset($this->CompletedBy)) return '';
        $dt = new Carbon($this->CompletedOn['Date']);
        return $dt->format('d-M-Y').' '.__('mycare.complete_by').' '.$this->CompletedBy['FullName'];
    }

    public function GetCompletedDateAttribute(){
        if(!isset($this->CompletedOn)) return '';
        $dt = new Carbon($this->CompletedOn['Date']);
        return $dt->format('d-M-Y');
    }

    public function GetValue($code){
        $data = $this->data;
        $form = AssessmentForm::find($this->Form['FormId']);
        $fld = $form->GetField($code);
        // Debugbar::debug($fld);
        if($fld == null) return '';
        
        $val = array_get($data, $code);
        if($fld['field_type']=='radio'){
            foreach($fld['fields'] as $f){
                $fldCode = $code.'-'.$f['code'];
                if($fldCode==$val){
                    $val = $f['text'];
                    break;
                }    
            }
        }elseif($fld['field_type']=='checkbox'){
            $ret = [];
            foreach($fld['fields'] as $f){
                $fldCode = $code.'-'.$f['code'];
                // Debugbar::debug($fldCode);
                $v = array_get($data, $fldCode);
                if($v=='on'){
                    array_push($ret, $f['text']);
                }    
            }
            $val = implode(', ', $ret);
        }
        return $val;
        
    }
}
