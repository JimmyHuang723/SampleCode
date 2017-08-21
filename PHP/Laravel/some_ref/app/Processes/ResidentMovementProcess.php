<?php

namespace App\Processes;

use App\Domains\ResidentMovement;
use App\Domains\Assessment;
use Carbon\Carbon;
use App\Utils\Toolkit;

class ResidentMovementProcess 
{
    function __construct()
    {
    }

    public function Run($payload, $action){
        $assessmentId = $payload->_id;
        if($action=='resident-movement')
            $fieldCode = env('DISCHARGE_DATE');
        else if($action=='room-change')
            $fieldCode = env('ROOMCHANGE_DATE');
        $rm = ResidentMovement::where('AssessmentId', $assessmentId)->get()->first();
        if(!isset($rm)){
            $data = $payload->data;
            $movementDate = array_get($data, $fieldCode);
            $dt = new Carbon($movementDate);
            $rm = new ResidentMovement();
            $rm->Resident = $payload->Resident;
            $rm->Facility = $payload->Facility;
            $rm->Form = $payload->Form;
            $rm->CompletedBy = $payload->CompletedBy;
            $rm->AssessmentId = $assessmentId;
            $rm->MovementDate = Toolkit::GetCarbonObject($dt);
            $rm->State = 'new';
            $rm->save();
        }
    }

}