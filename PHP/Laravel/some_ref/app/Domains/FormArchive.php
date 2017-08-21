<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;

class FormArchive extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'FormArchive';

    public function Archive($form){
        $this->formId = $form->_id;
        $this->FormID = $form->FormID;
        $this->FormName = $form->FormName;
        $this->version = $form->version;
        $this->template_json = $form->template_json;
        $this->dateArchived = Carbon::now();
        $this->save();

        return $this->_id;
    }
}
