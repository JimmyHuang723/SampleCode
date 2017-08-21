<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class BusinessRules extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'BusinessRules';


}
