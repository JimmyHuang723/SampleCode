<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Location extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Location';

//    private
//        $LocationID  // a value assigned
//        $FacilityID  // a value assigned
//        , $LocationNameLong     // Location full name
//        , $LocationNameShort    // Location short name

    public function getObjectAttribute(){
        return (object)[
            'LocationId' => $this->_id,
            'LocationName' => $this->LocationNameLong
        ]   ;
    }
}
