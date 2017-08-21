<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Debugbar;
use App;
// use Illuminate\Support\Facades\Log;
use App\Domains\AssessmentForm;
use App\Domains\Document;
use App\Domains\eDocument;
use Excel;
use App\Domains\HubUser;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        
        return view('export.index');
    }

    public function forms(){
        Excel::create('Forms', function($excel) {
            $excel->sheet('Forms', function($sheet) {
                $fms = AssessmentForm::orderBy('FormName')
                    ->where('IsActive', 1)->get();
                $sheet->loadView('export.forms', [
                    'forms' => $fms
                ]);
            });
        })->download('xlsx');
    }

    public function documents(){
        Excel::create('Documents', function($excel) {
            $excel->sheet('Documents', function($sheet) {
                $fms = Document::orderBy('DocTitle')
                    // ->where('IsActive', 1)
                    ->get();
                $sheet->loadView('export.documents', [
                    'documents' => $fms
                ]);
            });
        })->download('xlsx');
    }

    public function edocs(){
        Excel::create('edocs', function($excel) {
            $excel->sheet('edocs', function($sheet) {
                $fms = eDocument::orderBy('DocTitle')
                    // ->where('IsActive', 1)
                    ->get();
                $sheet->loadView('export.edocs', [
                    'edocs' => $fms
                ]);
            });
        })->download('xlsx');
    }

    public function visa457(){
        Excel::create('visa457', function($excel) {
            $excel->sheet('visa457', function($sheet) {
                $fms = \App\Domains\VisaDetail::orderBy('FacilityName')
                    ->orderBy('SSurname')
                    ->orderBy('SGivenNames')
                    ->where('VisaClass', 'like', '%457%')
                    ->get();
                $data = [];
                foreach($fms as $f){
                    $staff = HubUser::where('SID', $f->SID)->get()->first();
                    if($staff->STerminationDate['Epoch'] < 0)
                        array_push($data, $f);
                }
                $sheet->loadView('export.visa457', [
                    'visa' => $data
                ]);
            });
        })->download('xlsx');
    }

    public function aussie(){
        Excel::create('aussie', function($excel) {
            $excel->sheet('aussie', function($sheet) {
                $fms = \App\Domains\VisaDetail::orderBy('FacilityName')
                    ->orderBy('SSurname')
                    ->orderBy('SGivenNames')
                    ->where('AustraliaCitizen', true)
                    ->get();
                $data = [];
                foreach($fms as $f){
                    $staff = HubUser::where('SID', $f->SID)->get()->first();
                    if($staff->STerminationDate['Epoch'] < 0)
                        array_push($data, $f);
                }
                $sheet->loadView('export.visa457', [
                    'visa' => $data
                ]);
            });
        })->download('xlsx');
    }

    public function visanot457(){
        Excel::create('non457', function($excel) {
            $excel->sheet('non457', function($sheet) {
                $fms = \App\Domains\VisaDetail::orderBy('FacilityName')
                    ->orderBy('SSurname')
                    ->orderBy('SGivenNames')
                    // ->where('AustraliaCitizen', false)
                    ->get();

                $data = [];
                foreach($fms as $f){
                    $staff = HubUser::where('SID', $f->SID)->get()->first();
                    if(isset($f->Passport) && $staff->STerminationDate['Epoch'] < 0)
                        array_push($data, $f);
                }
                $sheet->loadView('export.visa457', [
                    'visa' => $data
                ]);
            });
        })->download('xlsx');
    }

}