<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class EmployeePhoto extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'EmployeePhoto';

    // to show the employee photo 
    // pass the $photo to a view then use this following syntax
    // <img src="{!! $photo !!}"/>
    public static function GetPhoto($sid){
        
        $photo = EmployeePhoto::where('staffId', strtoupper($sid))->get()->first();
        if(isset($photo)){
            $base64 = 'data:image/png'  . ';base64,' . base64_encode($photo->dataStorage);
            return $base64;
        }
        else
            return null;
    }
}
