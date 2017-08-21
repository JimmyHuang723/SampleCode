<?php

namespace App\Processes;

use App\Domains\Admission;
use App\Domains\BusinessRules;
use App\Domains\Checklist;
use App\Domains\MyCareTask;
use App\Domains\Resident;
use App\Domains\AssessmentForm;
use App\Domains\Facility;
use App\Domains\Location;
use App\Domains\Room;
use App\Utils\Toolkit;
use App\Utils\RmqHelper;
use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Log;
use Debugbar;

class AdmissionProcess extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'wfAdmissionProcess';

    function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function Run(){
        Log::debug('enter AdmissionProcess');

        $admission = Admission::find($this->payload->_id);

//        dd($admission);

        if($admission != null && $this->payload->step == 'new') {

            $needSaving = false;
            if($admission->Resident != null) {
                Log::debug('resident created, skip');
                $resident = Resident::find($admission->Resident['ResidentId']);
            } else {
                Log::debug('create resident');
                // create a Resident
                $resident = $this->createResident($admission);
                $admission->Resident = $resident->Object;
                $needSaving=true;
            }

            if($admission->Checklist != null)
                Log::debug('checklist created, skip');
            else {
                Log::debug('create checklist');
                // create checklist
                $checklist = $this->createChecklist($admission, $resident);
                $admission->Checklist = $checklist;

                $needSaving=true;
            }
            if($needSaving)
                $admission->save();

        }
        Log::debug('exit AdmissionProcess');
     }
    private function createChecklist($admission, $resident)
    {
        // check to make sure we are not creating checklist again
        Log::debug('enter createChecklist');

        $bizrules = BusinessRules::where('Process', 'admission')->get()->first();
        if(sizeof($bizrules) == 0) {
            Log::debug('missing business rules');
            return;
        }
        $source = 'admission';
        $checklists = array();
        $rules = $bizrules->Rules;
        foreach ($rules as $rule){
            $list = (array)$rule;
            $title = $list['Title'];
            $startDay = $list['StartDay'];
            $stopDay = $list['StopDay'];

            $chk = new Checklist();
            $chk->Title = $title;
            $chk->Admission = $admission->Object;
            $chk->save();
            array_push($checklists, $chk->Object);

            foreach ($list['Forms'] as $formCode){
                // create tasks
                $this->createTasks($admission, $resident, $chk, $formCode, 'assessment',
                    $source, $startDay, $stopDay);
            }
        }
        return $checklists;
    }

    private function createTasks($admission, $resident, $checklist,
            $formCode, $action, $source, $startDay, $stopDay){

        Log::debug('create admission tasks under Checklist');

        $startDate = (new Carbon())->addDays($startDay);
        $stopDate = (new Carbon())->addDays($stopDay);

        $form = AssessmentForm::where('FormID', $formCode)
            ->where('language', $admission->Language)->get()->first();

        if(sizeof($form) > 0) {
            $task = new MyCareTask();
            $task->Title = $form->FormName;
            $task->Form = $form->Object;
            $task->Facility = $admission->Facility;
            $task->Admission = $admission->Object;
            $task->Resident = $resident->Object;
            $task->Checklist = $checklist->Object;
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

     private function createResident($admission){

        // need to make sure we only create one resident for this admission

         $result = Resident::where('Admission.AdmissionId', $admission->_id)->get()->first();

         if($result == null) {
             $first_name = $admission->data['first_name'];
             $last_name = $admission->data['last_name'];
             $dob = $admission->data['dob_submit'];
             $locationId = $admission->data['location'];
             $roomId = $admission->data['room'];
             $urNumber = $admission->data['URNumber'];

             $location = Location::find($locationId);
             $room = Room::find($roomId);
             $facility = Facility::find($admission->Facility['FacilityId']);

             $resident = new Resident();
             $resident->PatientID = Resident::GetNextPatientID();
             $resident->Status = 0;
             $resident->IsTransferred = false;
             $resident->EntryType = 0;
             $resident->NameFirst = $first_name;
             $resident->NameLast = $last_name;
             $resident->DOB = Toolkit::GetDateObject($dob);
             $resident->CaseNumber = $urNumber;

             $resident->LocationID = $location->LocationID;
             $resident->LocationNameLong = $location->LocationNameLong;
             $resident->FacilityID = $facility->FacilityID;
             $resident->FacilityName = $admission->Facility['FacilityName'];

             $resident->DateTerminated = Toolkit::GetEmptyDateObject();
             $resident->DateDeleted = Toolkit::GetEmptyDateObject();
             $resident->StatusID = 1;

             $resident->Room = $room->RoomName;

             $resident->Facility = $facility->Object;
             $resident->Location = $location->Object;
             $resident->CurrentRoom = $room->Object;
             $resident->Admission = $admission->Object;
             $resident->save();

             // need to write to occupancy
             $resident->process = 'admission';
             $resident->step = 'new';
             $resident->object = 'resident';
             $resident->locale = Toolkit::GetLocale();

             RmqHelper::send(env('RMQ_ASSESSMENT_QUEUE'), $resident);


         } else
             $resident = $result;

         return $resident;
     }

}
