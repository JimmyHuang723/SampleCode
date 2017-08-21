<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Payload extends Eloquent
{
    protected $connection = 'mongodb';

}
