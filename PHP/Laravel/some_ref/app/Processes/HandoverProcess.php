<?php

namespace App\Processes;

use App\Domains\ResidentMovement;
use App\Domains\Assessment;
use App\Domains\HandoverSheet;
use Carbon\Carbon;
use App\Utils\Toolkit;
use Illuminate\Support\Facades\Log;
use Debugbar;
use MongoDB\BSON\UTCDateTime;

class HandoverProcess 
{
    function __construct()
    {
    }

    public function Run($payload, $rule){
        $data = (array)$payload->data;
        $assessmentId = $payload->_id;
        $residentId = $payload->Resident->ResidentId;
        $sourceField = array_get($rule['Then'], 'SourceField');
        $targetField = array_get($rule['Then'], 'TargetField');
        // Log::debug($residentId);
        $assessment = Assessment::find($assessmentId);

        $handover = HandoverSheet::orderBy('updated_at', 'desc')
            ->where('Resident.ResidentId', $residentId)->get()->first();
        // Debugbar::debug($handover);
        if($handover==null){
            $handover = new HandoverSheet();
            $handover->Resident = $payload->Resident;
            $handover->Facility = $payload->Facility;
            $handover->Form = $payload->Form;
            $handover->CompletedBy = $payload->CompletedBy;
            $handover->data = [];
        } 
        $targetData = $assessment->GetValue($sourceField);
        Log::debug('targetData='.$targetData);
        Log::debug('sourceField='.$sourceField);
        $result = $this->updateResultSet($handover->data, $targetField, $targetData, $payload->updated_at);
        // Debugbar::debug($result);
        
        $handover->data = $result;
        $handover->save();
    }

    private function updateResultSet($data, $targetField, $targetData, $updated_at){
        Debugbar::debug($data);
        $result = [];
        $dt = new Carbon($updated_at);
        Log::debug('timestamp='.$dt->timestamp);
        $utcDate = new UTCDateTime($dt->timestamp*1000);
        $foundit = false;
        foreach($data as $d){
            if(array_key_exists($targetField, $d)){
                $d = [$targetField => $targetData, 
                    'updated_at' => $utcDate];
                $foundit = true;
            } 
            array_push($result, (object)$d);
        }
        if(!$foundit){
            $d = [$targetField => $targetData,
                'updated_at' => $utcDate];
            array_push($result, (object)$d);
        }
        return $result;
    }

}