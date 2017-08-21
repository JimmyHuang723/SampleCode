<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
//use Debugbar;
// http://carbon.nesbot.com/docs/
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Resident extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Resident';
    public $guarded = ["_id"];

//    private
//        $PatientID      // a sequential number assigned by iCare to uniquely identify a patient
//        , $NameFirst
//        , $NameLast
//        , $NameMiddle
//        , $preferredName
//        , $CaseNumber
//        , $Room         // current room
//        , $Title
//        , $DOB          // This is an object in this format
//                        //        "DOB" : {
//                        //        "Date" : "1930-02-24T00:00:00",
//                        //        "Epoch" : -1257638400,
//                        //        "NoneUtcDate" : "1930-02-24T00:00:00"
//                        //       },
//
//        , $Gender       // 1 = Male, 2 = Female, 0 = Unknown
//
//        , $Languages    // array
//        , $Religions    // array
//        , $Allergies    // array
//        , $Status       // P = Permanent, R = Respite, D = Day care
//        , $Archived
//
//    ;


    public function getFullnameAttribute(){
        if($this->NameFirst == null || $this->NameLast == null)
            return '';
        else
            return strtoupper($this->NameLast) .', '.$this->NameFirst;
    }

    public function getDateOfBirthAttribute(){
        if($this->DOB == null) return null;
        else {
            $date = new Carbon($this->DOB["Date"]);
            return $date->toFormattedDateString();
        }
    }

    public function getLocationNameAttribute(){
        return $this->LocationNameLong;
    }

    public function getFirstNameAttribute(){
        return $this->NameFirst;
    }

    public function getLastNameAttribute(){
        return $this->NameLast;
    }

    public static function GetNextPatientID()
    {
        $resident = Resident::orderBy('PatientID', 'desc')->get()->take(1);
        if($resident == null)
            $residentId = 1;
        else
            $residentId = $resident[0]->PatientID + 1;
        return $residentId;
    }

    public function GetObjectAttribute(){
        $data = array();
        $data['ResidentId'] = $this->_id;
        $data['ResidentName'] = $this->Fullname;
        return (Object)$data;
    }

    public static function addPhoto($residentId, $fieldName){
        if(request()->has($fieldName)){
            $data = request()->input($fieldName);
            if(!empty($data)){        
                $encodedData = substr($data,strpos($data,",")+1);
                $encodedData = str_replace(' ','+',$encodedData);
                $decodedData = base64_decode($encodedData);
                $filename = 'public/resident_photos/'.'residentId_' . (string)$residentId . '.' . "jpg";
                Storage::put($filename, $decodedData);
            }
        }
    }
}