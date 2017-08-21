<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Debugbar;
use Illuminate\Support\Facades\Input;
use App\Domains\ResidentMovement;
use App\Domains\AssessmentForm;
use App\Domains\Assessment;
use App\Domains\Resident;
use App\Domains\Room;


class ResidentMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['fetch']]);
    }

    public function fetch(Request $request)
    {
        $token = Input::get('token');
        if($token!=env('WORKFLOW_TOKEN')) return 'WF OK';
        $mode = $request->input('mode');
        if($mode == '') $mode = env('IFTTN_MODE');

        Log::debug('enter ResidentMovementController');

        $movements = ResidentMovement::orderBy('updated_at')
            ->where('State', 'new')->get();

        $messageHandled = 0;
        foreach($movements as $m){
            if($this->processMovement($m))
                $messageHandled++;
        }
        return view('sqs',
            ['mode' => $mode,
                'max' => -1,
                'handled' => $messageHandled]);


        
    }

    private function processMovement($movement){
        $ret=false;

        $today = Carbon::now();
        $movementDate = new Carbon(array_get($movement->MovementDate,'Date'));
        if($movementDate <= $today){
            $assessment = Assessment::find($movement->AssessmentId);
            $resident = Resident::find(array_get($movement->Resident, 'ResidentId'));
            $formID = array_get($movement->Form, 'FormID');
            if($formID==env('DISCHARGE_FORM_ID')){
                if(isset($resident)){
                    $resident->StatusID = 0;
                    $resident->State = 'discharged';
                    $resident->save();
                }
                $movement->Reason = $assessment->GetValue(env('DISCHARGE_REASON'));
            } else if($formID==env('ROOMCHANGE_FORM_ID')){
                $roomId = array_get($assessment->data, env('ROOMCHANGE_NEW_ROOM'));
                $room = Room::find($roomId);
                if(isset($room)){
                    $resident->Room = $room->RoomName;
                    $resident->save();
                }
            }
            $movement->State = 'resident-updated';
            $movement->save();
            $ret=true;
        } else {
            $movement->save();
        }

        return $ret;
    }

    public function listing($residentId = null){

        $residentmovements = ResidentMovement::orderBy('updated_at', 'desc');

        if($residentId){
            $residentmovements = $residentmovements->where('Resident.ResidentId', $residentId);
        }

        if(Input::has('date_start')){
            $start = Carbon::createFromFormat('d/m/Y', Input::get('date_start'));
            $residentmovements = $residentmovements->where('created_at', '>=', $start);
        }
        if(Input::has('date_end')){
            $end = Carbon::createFromFormat('d/m/Y', Input::get('date_end'));
            $residentmovements = $residentmovements->where('created_at', '<=', $end);
        }

        $residentmovements = $residentmovements->paginate(15);

        // activity :
        foreach ($residentmovements as $m) {
            if ($m->Form['FormID'] == env('DISCHARGE_FORM_ID') ){
                $m->activity = 'Discharge';
            }else if ( $m->Form['FormID'] == env('ROOMCHANGE_FORM_ID') ){
                $m->activity = 'Room Change';
            }else if ( $m->Form['FormID'] == env('RESIDENT_LEAVE_FORM_ID') ){ 
                $m->activity = 'On Leave';
            }else{
                $m->activity = '';
            }            
        }

        return view('residentmovement.listing', [
            'residentmovements' => $residentmovements
        ]);
    }

    // Find ResidentMovement for <typeahead>
    public function findahead(){
      
        $name = Input::get('name');
        $residentmovements = ResidentMovement::where('Resident.ResidentName', 'like', '%'.$name.'%')
                                       ->orderBy('updated_at', 'desc')->take(50)->get();

        // For <typeahead> to show text on auto-complete dropdown list
        foreach ($residentmovements as $item) {
           $item['screen_name'] = $item->Resident['ResidentName'];
        }

        return $residentmovements;
    }


}