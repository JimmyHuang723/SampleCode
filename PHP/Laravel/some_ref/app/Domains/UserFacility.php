<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class UserFacility extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'UserFacility';

//    private $userId      // link to HubUser.SID
//        , $facilityId   // link to Facility.FacilityID
//        ;

}
