<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use Carbon\Carbon;

class Message extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Message';

    function __construct()
    {
        $today = new Carbon();

        $this->From = (object) array(
            'SID' => '',
            'UserName' => '',
            'Name' => ''
        );
        $this->To = [];
        $this->Subject = '';
        $this->Content='';
        $this->DateCreated =  (object) array(
            'Date' => (new \MongoDB\BSON\UTCDateTime()),
            'Epoch' => $today->getTimestamp()
        );
        $this->IsStar = 0;
        $this->State = 0;
        $this->TId = '';
        $this->IFTTN = env('IFTTN_VERSION');
    }

}