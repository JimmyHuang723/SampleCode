<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Domains\BusinessRules;
use App\Domains\AssessmentForm;

class BusinessRulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    // get all business rules
        $rules = BusinessRules::orderBy('Code')->get();

        return view('businessrules.index', [
            'rules' => $rules
        ]);

    }

    public function add(){
        $rule = new BusinessRules();

        return view('businessrules.edit',[
            'rule' => $rule
        ]);
    }


    public function edit($ruleId){
        $rule = BusinessRules::find($ruleId);

        return view('businessrules.edit',[
            'rule' => $rule
        ]);
    }

    public function store(Request $request){
        
        $this->validate($request, [
            'title' => 'required',
            // 'process' => 'required',
            'isactive' => 'required',
            'rules' => 'required'
        ]);

        $ruleId = Input::get('ruleId');

        $code = Input::get('code');
        $formId = Input::get('formId');
        $title = Input::get('title');
        $process = Input::get('process');
        $isactive = intval(Input::get('isactive'));
        $rules = Input::get('rules');

        $form = AssessmentForm::find($formId);

        $b = json_decode($rules);

        if(isset($ruleId))
            $rule = BusinessRules::find($ruleId);
        else
            $rule = new BusinessRules();

        $rule->Code = $code;
        $rule->Title = $title;
        $rule->Process = $process;
        $rule->IsActive = $isactive;
        $rule->RulesJson = $rules;
        $rule->Rules = $b;
        $rule->HasError = ($b==null);
        if($form != null)
            $rule->Form = $form->Object;
        else
            $rule->Form = null;

        $rule->save();

        return redirect('/businessrules');
    }

    public function archive($ruleId){

    }
}