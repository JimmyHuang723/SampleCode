<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
use App\Domains\Facility;
use App\Domains\Resident;
use App\Domains\ResidentCCS;
use App\Domains\CarePlan;
use App\Domains\CarePlanView;
use App;
use App\Domains\CarePlanAssessment;
use PDF;

class CarePlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(){
        $plans = CarePlan::orderBy('updated_at', 'desc')
            // ->where('IsActive', 1)
            ->paginate(10);

        return view('careplan.search', [
            'plans' => $plans
        ]);
    }

    public function view($residentId){
        $resident = Resident::find($residentId);
        $careplan = CarePlan::where('Resident.ResidentId', $resident->_id)
            ->orderBy('updated_at', 'desc')
            ->where('IsActive', 1)
            ->get()->first();

        if($careplan == null)
            return redirect('resident/select/'.$residentId);

        $resident->CCS = ResidentCCS::GetScore($resident->_id);

        $facilityId = $careplan->Facility['FacilityId'];
        $facility = Facility::find($facilityId);

        $createdBy = $careplan->CreatedBy;
        $updatedBy = $careplan->UpdatedBy;

//        Debugbar::debug($careplan);

        $careplanView = new CarePlanView();

        $cpas = CarePlanAssessment::where('CarePlan.CarePlanId', $careplan->_id)
            ->get();

        foreach($cpas as $cpa){
            foreach ($cpa->Observations as $data){
                $careplanView->AddDomain($data['domain']);
                $careplanView->AddObservation($data);
            }

            foreach ($cpa->Goals as $data){
                $careplanView->AddDomain($data['domain']);
                $careplanView->AddGoal($data);
            }

            foreach ($cpa->Interventions as $data){
                $careplanView->AddDomain($data['domain']);
                $careplanView->AddIntervention($data);
            }
        }

//        Debugbar::debug($careplanView->Domains());

        return view('careplan.view',[
            'resident'=>$resident,
            'facility' => $facility,
            'has_careplan' => ($careplan==null?false:true),
            'careplan' => $careplan,
            'careplanView' => $careplanView,
        ]);
    }

    public function evaluate($residentId){
        $resident = Resident::find($residentId);
        $careplan = CarePlan::where('Resident.ResidentId', $resident->_id)
            ->where('IsActive', 1)
            ->get()->first();

        if($careplan == null)
            return redirect('resident/select/'.$residentId);
    }

    public function print_care_plan($residentId){
        $resident = Resident::find($residentId);
        $careplan = CarePlan::where('Resident.ResidentId', $resident->_id)
            ->orderBy('updated_at', 'desc')
            ->where('IsActive', 1)
            ->get()->first();

        if($careplan == null)
            return redirect('resident/select/'.$residentId);

        $resident->CCS = ResidentCCS::GetScore($resident->_id);

        $facilityId = $careplan->Facility['FacilityId'];
        $facility = Facility::find($facilityId);

        $createdBy = $careplan->CreatedBy;
        $updatedBy = $careplan->UpdatedBy;

        $careplanView = new CarePlanView();

        $cpas = CarePlanAssessment::where('CarePlan.CarePlanId', $careplan->_id)
            ->get();

        foreach($cpas as $cpa){
            foreach ($cpa->Observations as $data){
                $careplanView->AddDomain($data['domain']);
                $careplanView->AddObservation($data);
            }

            foreach ($cpa->Goals as $data){
                $careplanView->AddDomain($data['domain']);
                $careplanView->AddGoal($data);
            }

            foreach ($cpa->Interventions as $data){
                $careplanView->AddDomain($data['domain']);
                $careplanView->AddIntervention($data);
            }
        }
        $pdf = PDF::loadView('careplan.print_view',[
            'resident'=>$resident,
            'facility' => $facility,
            'has_careplan' => ($careplan==null?false:true),
            'careplan' => $careplan,
            'careplanView' => $careplanView,
        ]);
        return $pdf->inline();
    }
}