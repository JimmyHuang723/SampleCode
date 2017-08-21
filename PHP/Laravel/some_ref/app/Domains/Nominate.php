<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;

class Nominate extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Nominate';
}