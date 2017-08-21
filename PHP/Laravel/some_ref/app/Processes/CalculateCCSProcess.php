<?php

namespace App\Processes;

use App\Domains\AssessmentForm;
use App\Domains\CCSMapping;
use App\Domains\ResidentCCS;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Log;
use Debugbar;

class CalculateCCSProcess extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'wfCalculateCCSProcess';

    function __construct()
    {
    }

    public function Run($payload, $resident, $facility, $user, $form)
    {
        Log::debug('enter CalculateCCSProcess');

        $response = (array)$payload->data;

        $ccsMapping = CCSMapping::where('Form.FormId', $form->_id)->get()->first();
        if($ccsMapping == null) {
            Log::debug('no CCSMapping for this form '.$form->_id);
            return;
        }

        $form = AssessmentForm::find($form->_id);
        $mapping = $ccsMapping->Mapping;

        // set previous CSS to inactive
        ResidentCCS::where('Resident.ResidentId', $resident->_id)
            ->where('Form.FormId', $form->_id)
            ->where('Facility.FacilityId', $facility->_id)
            ->update(['IsActive' => false]);

        $residentCCS = new ResidentCCS();
        $residentCCS->Resident = $resident->Object;
        $residentCCS->Form = $form->Object;
        $residentCCS->Facility = $facility->Object;
        $residentCCS->User = $user->Object;
        $residentCCS->IsActive = true;

        Debugbar::debug($response);
        $ccs = [];
        foreach ($mapping as $item) {
            $code = $item['code'];
            $score = $item['score'];
            $category = $item['category'];

            Log::debug('code='.$code .', score='.$score);
            $question = $form->GetQuestion($code);
            Log::debug($question);
            $scoreit=false;

            if(!array_key_exists('parent_code', $question))
                continue;

            $parentCode = $question['parent_code'];

            if($question['type']=='checkbox'){
                Log::debug('checkbox');
                if(array_key_exists($code, $response)){
                    if($response[$code]=='on'){
                        $scoreit = true;
                    }
                }
            } else if($question['type']=='radio'){
                Log::debug('radio');
                if(array_key_exists($parentCode, $response)){
                    if($response[$parentCode]==$code){
                        $scoreit = true;
                    }
                }
            } else {
//                if($response[$code]!=''){
//                    $scoreit = true;
//                }
            }
            if($scoreit){
                $d = [
                    'code' => $code,
                    'category' => $category,
                    'score' => $score
                ];
                array_push($ccs, $d);
            }
        }
        if(sizeof($ccs)>0){
            $residentCCS->CCS = $ccs;
            $residentCCS->save();
        }

    }
}