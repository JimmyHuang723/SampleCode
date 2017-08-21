<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DomainsNominate;
use App\Domains\ACFI12;
use App\Domains\Role;
use App\Domains\UserRole;
use App\Domains\HubUser;
use App\Domains\AssessmentForm;
use App\Domains\FormControl;
use Debugbar;

class MongoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function nominate()
    {
        $result = Nominate::all();
        Debugbar::info($result);
        return view('nominate', ['nominations' => $result]);
    }


    public function acfi12(){
        $result = ACFI12::where('IsDischarged', 0)->orderBy('FacilityName')->get();
        //Debugbar::info($result);
        return view('acfi12', ['results' => $result]);
    }

    public function whoare($roleName){
        $users = [];
        $role = Role::where('roleName', $roleName)->get()->first();
        $userRoles = UserRole::where('roleId', $role->_id)->get();
        foreach($userRoles as $ur){
            $user = HubUser::where('SID', $ur->userId)->get()->first();
            array_push($users, $user);
        }
        return View('user.list', [
            'users' => $users
        ]);
    }

    public function set_form_code(){
        $forms = AssessmentForm::orderBy('FormName')->get();
        $code = 1020;
        foreach ($forms as $form){
            $form->FormCode = $code;
            $code = $code + 2;
            $form->save();
        }
    }


    public function listq(){
        $forms = AssessmentForm::where('IsActive', 1)
            ->orderBy('FormName')->get();

        foreach ($forms as $form){
            $form->controls = array();
            if(property_exists($form, 'template_json')) continue;

            $form_controls = $form->template_json;
            if($form_controls == null) continue;

//            Debugbar::debug($form_controls);

            $controls = new FormControl();
            foreach ($form_controls as $cnt){
                $controls->AddControl($cnt);
            }
            $form->controls = $controls->controls;
        }
        return view('form.listq',
            [
                'forms' => $forms
            ]);
    }
}
