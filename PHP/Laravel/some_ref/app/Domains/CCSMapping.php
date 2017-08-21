<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use App\Domains\Resident;

class CCSMapping extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'CCSMapping';


}