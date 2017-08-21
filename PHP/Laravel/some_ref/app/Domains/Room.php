<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Room extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Room';

//    private
//        $LocationID  // a value assigned
//        $FacilityID  // a value assigned
//        , $LocationNameLong     // Location full name
//        , $FacilityName    // Location short name
//        , $RoomName    // Location short name

    public function getObjectAttribute(){
        return (object)[
            'RoomId' => $this->_id,
            'RoomName' => $this->RoomName
        ]   ;
    }
}
