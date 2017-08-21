<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class UserRole extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'UserRole';

//    private $userId, $roleId;

}
