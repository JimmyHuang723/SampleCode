<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App;
use App\Domains\AssessmentForm;
use App\Domains\TemplateTranslator;
use Carbon\Carbon;
use App\Domains\FormArchive;
use App\Domains\FormControl;
use Illuminate\Support\Facades\Auth;
use App\Domains\HubUser;
use App\Utils\Toolkit;
use Illuminate\Support\Facades\Log;
use Debugbar;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing($showAll=0){
        if($showAll)
            $forms = AssessmentForm::orderBy('FormName')->get();
        else
            $forms = AssessmentForm::where('IsActive', 1)->orderBy('FormName')->get();

        return view('form.listing', [
            'forms' => $forms,
            'params' => [
            'form-name' => '',
            'category' => 'all',
            'state' => 1,
            'language' => 'rn'
        ]
        ]);
    }

	public function listingSearch(){
		$formName = Input::get('formName');
		$category = Input::get('category'); 
		$isActive = (int)Input::get('isActive');
		$language = Input::get('language');

		$data = [];
        $forms = AssessmentForm::orderBy('FormName')->get();
        foreach ($forms as $form) {
            $addIt = false;
            if ($category != 'all') {
                if ($category == 'assessment' && $form->AssessmentCategory == 1) $addIt = true;
                else if ($category == 'form' && $form->FormCategory == 1) $addIt = true;
                else if ($category == 'charting' && $form->ChartingCategory == 1) $addIt = true;
            }
            else
                $addIt=true;
            if($addIt) array_push($data, $form);
        }
        $forms = $data;
        $data = [];
        foreach ($forms as $form) {
            if ($isActive != -1) {
                if ($isActive == $form->IsActive) array_push($data, $form);
            } else
                array_push($data, $form);
        }
        $forms = $data;
        $data = [];
        foreach ($forms as $form) {
            if ($language != 'all') {
                if ($language == $form->language) array_push($data, $form);
            } else
                array_push($data, $form);
        }
        $forms = $data;
        $data = [];
        foreach ($forms as $form) {
            if($formName != ''){
                if(stripos('-'.$form->FormName, $formName)) array_push($data, $form);
            } else
                array_push($data, $form);
        }
		return view('form.listing', [
		'forms' => $data,
        'params' => [
            'form-name' => $formName,
            'category' => $category,
            'state' => $isActive,
            'language' => $language
        ]
		]); 
	} 
	
	
    public function add(){
        $form = (object)[
            '_id' => null,
            'FormID' => AssessmentForm::NextFormCode(),
            'FormName' => '',
            'IsActive' => 1,
            'ResidentRequired' => 0,
            'StaffRequired' => 0,
            'FormCategory' => 1,
            'ChartingCategory' => 0,
            'AssessmentCategory' => 0,
            'language' => 'en',
            'FormCode' => AssessmentForm::NextFormCode()
        ];
        return view('form.add_edit',
            ['form' => $form]);
    }

    public function edit($formId){

        $form = AssessmentForm::find($formId);
        if($form->FormID == 0)
            $form->FormID = AssessmentForm::NextFormCode();

        if(isset($form['ParentFormID'])){ 
            $parentForm = AssessmentForm::where('FormID', $form['ParentFormID'])->get()->first();
            $form['ParentFormName'] = $parentForm['FormName'];
        }

        return view('form.add_edit', [
            'form' => $form,
        ]);
    }

    public function template($formId){

        $form = AssessmentForm::find($formId);

        return view('form.template', [
            'form' => $form,
            'validated' => false,
            'content' => '',
            'data' => [],
        ]);
    }

    public function store(Request $request){
        $this->validate($request, [
            'formName' => 'required',
            'status' => 'required',
            'form_code' => 'required'
        ]);
        $formId = Input::get('formId');
        $formName = Input::get('formName');
        $parentFormID = (int)Input::get('parentFormID');
        $residentRequired = Input::get('residentRequired');
        $staffRequired = Input::get('staffRequired');
        $category = Input::get('category');
        $status = Input::get('status');
        $language = Input::get('language');
        $formID = (int)Input::get('form_code');
        Log::debug($formID);
        if($formId == '')
            $form = new AssessmentForm();
        else
            $form = AssessmentForm::find($formId);

        $form->FormName = $formName;
        $form->ResidentRequired = ($residentRequired=='1'? 1: 0);
        $form->StaffRequired = ($staffRequired=='1'? 1: 0);
        $form->FormCategory = ($category=='form'? 1: 0);
        $form->AssessmentCategory = ($category=='assessment'? 1: 0);
        $form->ChartingCategory = ($category=='charting'? 1: 0);
        $form->IsActive = ($status=='active'? 1: 0);
        $form->language = $language;
        $form->FormID = $formID;
        $form->ParentFormID = $parentFormID;

        $sid = Auth::user()->SID;
        $user = HubUser::where('SID', $sid)->get()->first();
        $form->updated_by = $user->UserObject;

        $form->save();

        return redirect('/form/listing');
    }

    public function validateTemplate(Request $request){
        $this->validate($request, [
            'formId' => 'required',
            'template_content' => 'required'
        ]);

        $formId = $request->input('formId');
        $content = $request->input('template_content');
        $form = AssessmentForm::find($formId);

        if(substr($content, 0, 9)=='template.'){
            $form->template = $content;
            $form->save();
            return redirect('/form/listing');
        }
        // translate content
        $translator = new TemplateTranslator($content);

        $templateString = $translator->Translate();
        $form_controls = json_decode($templateString);

//        dd($json);
        if($form_controls == null){
            $form = AssessmentForm::find($formId);
//            $this->validator->errors()->add('template', 'error in template, please fix and try again.');
            return view('form.template', [
                'form' => $form,
                'validated' => false,
                'content' => $content,
            ]);
        }
        $form->template = $content;
        $form->template_json = $form_controls;

        // convert to array instead of an object here
        $form_controls = json_decode($templateString, true);

        $controls = new FormControl();
        foreach ($form_controls as $cnt){
            $controls->AddControl($cnt);
        }
        if($form->version == null) $form->version = 1;
        else {
            // archive the current version
            $archive = new FormArchive();
            $archive->Archive($form);

            $form->version++;
        }
        $form->save();

        return view('form.preview_form', [
            'form' => $form,
            'template' => $templateString,
            'controls' => $controls->controls,
            'data' => [],
        ]);
    }

    public function preview($formId){
        $form = AssessmentForm::find($formId);

        if(substr($form->template,0,9)=='template.') {
            return view('form.fixed_template',
                [
                    'form' => $form,
                    'template' => $form->template,
                    'data' => []]);
        } else {
            $form_controls = $form->template_json;
            if(is_array($form_controls)) {
                $controls = new FormControl();
                foreach ($form_controls as $cnt) {
                    $controls->AddControl($cnt);
                }
                return view('form.preview_form', [
                    'form' => $form,
                    'template' => $form->template,
                    'controls' => $controls->controls,
                    'data' => [],
                ]);
            } else
                return redirect('/form/template/'.$formId);
        }
    }

    public function findByName(Request $request){
        $name = Input::get('name');
        $result = AssessmentForm::orderBy('FormName')
            ->where('IsActive',1)
            ->where('FormName', 'like', '%'.$name .'%')->get();

        // For <typeahead> to show text on auto-complete dropdown list
        foreach ($result as $item) {
           $item['screen_name'] = $item['FormID'].' - '.$item['FormName'];
        }

        return $result;
    }

    public function autocomplete(Request $request){
        $name = Input::get('name');
        $result = AssessmentForm::orderBy('FormName')
            ->where('IsActive',1)
            ->where('FormName', 'like', '%'.$name .'%')->get();

        $data = array();
        foreach($result as $item){
            $d = array(
                'label' => $item['FormName']. ', '.$item['FormID'],
                'id' => $item['_id']
            );
            array_push($data, $d);
        }
        return json_encode($data);
    }
}