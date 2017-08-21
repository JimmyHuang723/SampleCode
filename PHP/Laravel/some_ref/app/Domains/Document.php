<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Document extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Documents';
}