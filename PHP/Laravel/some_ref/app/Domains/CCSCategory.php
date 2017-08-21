<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
//use Debugbar;

class CCSCategory extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'CCSCategory';


}