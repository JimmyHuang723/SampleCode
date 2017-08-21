<?php

namespace App\Domains;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MyCareTask extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Task';

    function __construct()
    {
        $this->Status = 0;
    }

    public function GetObjectAttribute(){
        $data = array();
        $data['TaskId'] = $this->_id;
        $data['Title'] = $this->Title;
        $data['Status'] = $this->Status;
        $dt = new Carbon($this->StartDate['Date']);
        $data['StartDate'] = $dt->format('d-M-Y');
        $dt = new Carbon($this->StopDate['Date']);
        $data['StopDate'] = $dt->format('d-M-Y');
        if(isset($this->CompletedOn)) {
            $dt = new Carbon($this->CompletedOn['Date']);
            $data['CompletedDate'] = $dt->format('d-M-Y');
        } else
            $data['CompletedDate'] = '';
        return (Object)$data;
    }

    public function GetDueDateAttribute(){
        if(isset($this->StopDate)) {
            $dt = new Carbon($this->StartDate['Date']);
            return  $dt->format('d-M-Y');
        } else
            return '';
    }

    public function GetBeginDateAttribute(){
        if(isset($this->StartDate)) {
            $dt = new Carbon($this->StartDate['Date']);
            return  $dt->format('d-M-Y');
        } else
            return '';
    }

    public function GetDueTimeAttribute(){
        if(isset($this->StopDate)) {
            $dt = new Carbon($this->StopDate['Date']);
            return  $dt->format('H:i');
        } else
            return '';
    }
}
