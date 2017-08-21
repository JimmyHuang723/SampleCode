<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CarePlan extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'CarePlan';


    public function getLastUpdateDateAttribute(){
        return $this->updated_at;
    }

    public function GetObjectAttribute(){
        $data = array();
        $data['CarePlanId'] = $this->_id;
        return (Object)$data;
    }
}
