<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ServiceProduct extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'ServiceProduct';

    public function GetObjectAttribute(){
        return (object)[
            'ServiceId' => $this->_id,
            'ServiceCode' => $this->code,
            'ServiceName' => $this->text
        ]   ;
    }
}