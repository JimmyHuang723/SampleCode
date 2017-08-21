<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
// http://carbon.nesbot.com/docs/
use Carbon\Carbon;

class AssessmentForm extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'AssessmentForm';


    public function GetObjectAttribute(){
        $data = array();
        $data['FormId'] = $this->_id;
        $data['FormName'] = $this->FormName;
        $data['FormCode'] = $this->FormCode;
        $data['FormID'] = $this->FormID;
        return (Object)$data;
    }

    public static function NextFormCode(){
        $form = AssessmentForm::orderBy('FormID', 'desc')->take(1)->get()->first();
//        Debugbar::debug($form);
        $formCode = rand(2,10) + $form->FormID;
        return $formCode;
    }

    // return a question given a code
    public function GetQuestion($code){
        $question = [];
        $question['text'] = '';
        $question['code'] = $code;
        foreach ($this->template_json as $item) {

            if($item['field_type']=='radio' || $item['field_type']=='checkbox' || $item['field_type']=='dropdown'){

                foreach($item['fields'] as $field){
                    if($item['code'].'-'.$field['code']==$code){
                        $question['text']=$field['text'];
                        $question['parent_code'] = $item['code'];
                        $question['type'] = $item['field_type'];
                        break;
                    }
                }
            } else{
                if($item['code']==$code){
                    $question['text']=$item['question']['text'];
                    $question['type'] = $item['field_type'];
                    break;
                }
            }
        }
        return $question;
    }

    public function GetField($code){
        if($this->template_json == null) return null;
        $questions = $this->template_json;
        $ret= null;
        foreach($questions as $qtn){
            if($qtn['code']==$code){
                $ret = $qtn;
                break;
            }
        }
        return $ret;
    }
}
