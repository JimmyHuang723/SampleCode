<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Checklist extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Checklist';

    public function GetObjectAttribute(){
        $data = array();
        $data['ChecklistId'] = $this->_id;
        $data['Title'] = $this->Title;
        return (Object)$data;
    }
}
