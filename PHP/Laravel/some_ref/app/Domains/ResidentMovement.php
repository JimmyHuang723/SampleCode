<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ResidentMovement extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'ResidentMovement';
}