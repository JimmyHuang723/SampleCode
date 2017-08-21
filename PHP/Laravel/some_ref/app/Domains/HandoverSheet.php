<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class HandoverSheet extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'MyHandoverSheet';


}
