<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CarePlanAssessment extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'CarePlanAssessment';


    public function getLastUpdateDateAttribute(){
        return $this->updated_at;
    }
}
