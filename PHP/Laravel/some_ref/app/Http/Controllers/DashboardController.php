<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
use Illuminate\Support\Facades\Auth;
use App\Domains\HubUser;
use App\Domains\UserFacility;
use App\Domains\Facility;
use App;
use Illuminate\Support\Facades\Log;
use App\Domains\Assessment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function everyone(){
        return view('dashboard.everyone');
    }

    

    public function res_incident(Request $request){
        $stats = [];
        $incidents = $this->checkData('resident-incident', $request, $stats);        
        
        return view('dashboard.res_incident', [
            'incidents' => $incidents,
            'stats' => $stats
        ]);
    }



    public function med_incident(Request $request){
        $stats = [];
        $incidents = $this->checkData('med-incident', $request, $stats);        
        
        return view('dashboard.med_incident', [
            'incidents' => $incidents,
            'stats' => $stats
        ]);
    }

    public function infection(Request $request){
        
        $stats = [];
        $incidents = $this->checkData('infection', $request, $stats);   

        return view('dashboard.infection', [
            'incidents' => $incidents,
            'stats' => $stats
        ]);
    }

    public function bowel(Request $request){
        $stats = [];
        $incidents = $this->checkData('bowel-alert', $request, $stats);   
        return view('dashboard.bowel',[
            'incidents' => $incidents,
            'stats' => $stats
        ]);
    }

    public function bgl(){
        $stats = [];
        $incidents=[];
        return view('dashboard.bgl',[
            'incidents' => $incidents,
            'stats' => $stats
        ]);
    }

    private function checkData($key, $request, &$stats){
        $facilityId = $request->session()->get('facility');
        $yesterday = Carbon::now()->subDays(1);
        $formId = intval(env('RESIDENT_INCIDENT_FORM_ID'));
        $data = Assessment::where('Form.FormID', $formId)
            ->where('Facility.FacilityId', $facilityId)
            ->where('FormState', 1)
            ->where('updated_at', '>=', $yesterday)
            ->get();
        
        $stats['resident-incident'] = sizeof($data);
        if($key=='resident-incident') $incidents = $data;

        $formId = intval(env('MEDICATION_INCIDENT_FORM_ID'));
        $data = Assessment::where('Form.FormID', $formId)
            ->where('Facility.FacilityId', $facilityId)
            ->where('FormState', 1)
            ->where('updated_at', '>=', $yesterday)
            ->get();
        $stats['med-incident'] = sizeof($data);
        if($key=='med-incident') $incidents = $data;

        $formId = intval(env('INFECTION_MONITORING_FORM_ID'));
        $data = Assessment::where('Form.FormID', $formId)
            ->where('Facility.FacilityId', $facilityId)
            ->where('FormState', 1)
            ->where('updated_at', '>=', $yesterday)
            ->get();
        $stats['infection'] = sizeof($data);
        if($key=='infection') $incidents = $data;

        $dt = Carbon::now()->subDays(14);
        $formId = intval(env('BOWEL_CHART_FORM_ID'));
        $rows = Assessment::orderBy('updated_at', 'desc')
            ->where('Form.FormID', $formId)
            ->where('Facility.FacilityId', $facilityId)
            ->where('FormState', 1)
            ->where('updated_at', '>=', $dt)
            ->get();
        
        $residentChecked = [];
        $data = [];
        foreach($rows as $row){
            $date = $row->GetValue(env('FIELD_CODE_BOWEL_ACTION_DATE'));
            $action = $row->GetValue(env('FIELD_CODE_BOWEL_ACTION'));
            if(in_array($row->Resident['ResidentId'], $residentChecked)) continue;
            array_push($residentChecked, $row->Resident['ResidentId']);
            if($action != 'Bowels not opened') continue;
            $today = Carbon::now();
            $actionDate = Carbon::createFromFormat('d/m/Y', $date);
            $row->BowelActionDate = $actionDate;
            $row->DaysOld = $actionDate->diffInDays($today);
            $row->BowelAction = $action;

            if($row->DaysOld >= 3 && $row->DaysOld <= 14)
                array_push($data, $row);
        }
        $stats['bowel-alert'] = sizeof($data);
        if($key=='bowel-alert') $incidents = $data;

        return $incidents;
    }
    
}