<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
use Illuminate\Support\Facades\Auth;
use App;
use App\Domains\AssessmentForm;
use App\Domains\FormControl;
use App\Domains\CCSMapping;
use Illuminate\Support\Facades\Input;
use App\Domains\CCSCategory;

class ClassificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listq($formId){
        $form = AssessmentForm::find($formId);

        if(substr($form->template,0,9)=='template.') {
            $form_controls = [];
            $use_template_file = true;
        }
        else {
            $form_controls = $form->template_json;
            $use_template_file = false;

            $controls = new FormControl();
            foreach ($form_controls as $cnt) {
                $controls->AddControl($cnt);
            }
        }
        $categories = [];
        $ccsCategories = CCSCategory::get();
        foreach ($ccsCategories as $ccsCategory) {
            $categories[$ccsCategory->code] = $ccsCategory->text;
        }
        $ccs = [];
        $ccsMapping = CCSMapping::where('Form.FormId', $form->_id)->get()->first();
        if($ccsMapping!=null) {
            $mapping = $ccsMapping->Mapping;
            foreach ($mapping as $m) {
                $ccs[$m['code']] = [
                    'category' => $categories[$m['category']],
                    'score' => $m['score']
                ];
            }
        }
        return view('ccs.listq', [
            'form' => $form,
            'form_controls' => $form_controls,
            'controls' => $controls->controls,
            'ccs' => $ccs
        ]);
    }



    public function edit($formId, $code){
        $form = AssessmentForm::find($formId);
        $map = CCSMapping::where('Form.FormId', $formId)->get()->first();
        $categories = CCSCategory::orderBy('text')->get();

        if($map == null) {
            $map = new CCSMapping();
            $map->Mapping = [];
            $question = [
                'code' => $code,
                'text' => '',
                'score' => 0,
                'category' => ''
            ];
        } else{
            $question = array_get($map->Mapping, $code);
            if($question == null){
                $question = [
                    'code' => $code,
                    'text' => '',
                    'score' => 0,
                    'category' => ''
                ];
            }
        }

        $q = $form->GetQuestion($code);
        $question['text'] = $q['text'];

        return view('ccs.edit', [
            'form' => $form,
            'map' => $map,
            'question' => $question,
            'categories' => $categories
        ]);

    }

    public function delete($formId, $code)
    {
        $ccsMapping = CCSMapping::where('Form.FormId', $formId)->get()->first();
        $mapping = $ccsMapping->Mapping;
        $ccs = [];
        foreach ($mapping as $m){
            if($m['code'] != $code)
                array_push($ccs, $m);
        }
        $ccsMapping->Mapping = $ccs;
        $ccsMapping->save();

        return redirect('ccs/listq/'.$formId);
    }

        public function store(Request $request){
        $this->validate($request, [
            'category' => 'required',
            'score' => 'required',
            'form_id' => 'required',
            'code' => 'required'
        ]);

        $formId = Input::get('form_id');
        $code = Input::get('code');
        $category = Input::get('category');
        $score = floatval(Input::get('score'));

        $form = AssessmentForm::find($formId);
        $question = $form->GetQuestion($code);
        $q = [
            'code' => $code,
            'category' => $category,
            'score' => $score,
            'text' => $question['text']
        ];
        $map = CCSMapping::where('Form.FormId', $formId)->get()->first();
        if($map == null) {
            $map = new CCSMapping();
            $map->Form = $form->Object;
            $mapping = [];
            array_push($mapping, $q);
            $map->Mapping = $mapping;
            $map->save();
        } else {
            $mapping = $map->Mapping;
            $newMapping = [];
            foreach ($mapping as $m){
                if($m['code']!=$code){
                    array_push($newMapping, $m);
                }
            }
            array_push($newMapping, $q);
            $map->Mapping = $newMapping;
            $map->save();
        }

        return redirect('ccs/listq/'.$form->_id);
    }
}