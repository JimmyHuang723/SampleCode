<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class VisaDetail extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'VisaDetail';

}
