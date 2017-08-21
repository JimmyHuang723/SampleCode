<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use App\Domains\Resident;

class ACFI12 extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'ACFIQ12';

    public function getResidentAttribute(){

        $residents = Resident::where('PatientID', $this->ResidentID)->get();
        Debugbar::debug($residents);

        if(sizeof($residents) > 0)
            return $residents[0];
        else
            return null;
    }

}