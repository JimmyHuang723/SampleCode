<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use Illuminate\Support\Facades\Log;

class Inquiry extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Inquiry';
}