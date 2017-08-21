<?php
namespace App\Domains;
use Debugbar;
use phpDocumentor\Reflection\Types\Object_;
use stdClass;
use Illuminate\Support\Facades\Log;
use App\Utils\Toolkit;

class FormControl
{
    public $controls;

    function __construct()
    {
        $this->controls = [];
    }

    public function AddControl($cnt){
//        Debugbar::debug($cnt);

        // prepare the common fields
        $control = new stdClass();

        $control->qn = array_get($cnt, 'qn');
        $control->code = array_get($cnt, 'code');
        $control->type = array_get($cnt, 'field_type');
        $control->question = array_get($cnt['question'], 'text');
        $control->question_attr = array_get($cnt['question'], 'attr');
        if(array_key_exists('goal', $cnt['question']))
            $control->goal = $cnt['question']['goal'];
        else
            $control->goal = '';
        if(array_key_exists('prompt', $cnt)){
            $control->has_prompt = true;
            $control->prompt = array_get($cnt['prompt'], 'text');
            $control->prompt_attr = array_get($cnt['prompt'], 'attr');
        } else{
            $control->has_prompt = false;
        }
        if($cnt['field_type']=='checkbox' || $cnt['field_type']=='radio' || $cnt['field_type']=='dropdown'){
            $field = [];
            $control->fields = [];
            $fields = $cnt['fields'];
            foreach ($fields as $fld){
                $field['code'] = $cnt['code'].'-'.$fld['code'];
                $field['text'] = $fld['text'];
                if(array_key_exists('goal', $fld))
                    $field['goal'] = $fld['goal'];
                else
                    $field['goal'] = '';
//                Debugbar::debug($field);
                array_push($control->fields, $field);
            }
        } else if($cnt['field_type']=='message'){
            $control->question = Toolkit::Markup($control->question);
        }
        
//        Debugbar::debug($control);
        array_push($this->controls, $control);
    }



}