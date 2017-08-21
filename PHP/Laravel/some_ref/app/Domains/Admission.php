<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use Illuminate\Support\Facades\Log;
use App\Domains\MyCareTask;

class Admission extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'MyCareAdmission';

    public function GetResidentNameAttribute(){
        return $this->data['first_name'].' '.$this->data['last_name'];
    }

    public function GetLocationNameAttribute()
    {
        return $this->Location['LocationName'];
    }

    public function GetRoomNameAttribute()
    {
        return $this->Room['RoomName'];
    }

    public function GetCreatedByUserNameAttribute()
    {
        return $this->CreatedBy['FullName'];
    }

    public function GetCompletionRateAttribute(){
        $totalTasks = MyCareTask::where("Admission.AdmissionId", $this->_id);     
        $completedTasks = MyCareTask::where("Admission.AdmissionId", $this->_id)->where('Status', 1);
        
        // Log::debug('task complete '. $completedTasks->count(). "   ". $totalTasks->count() );        
        if( $totalTasks->count() != 0 ){
            return $completedTasks->count() / $totalTasks->count() * 100;
        }else{
            return 100; // no task
        }
    }

    public function GetObjectAttribute(){
        $data = array();
        $data['AdmissionId'] = $this->_id;
        return (Object)$data;
    }
}