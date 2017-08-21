<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class eDocument extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'eDocument';
}