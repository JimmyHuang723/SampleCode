<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domains\Payload;
use App\Domains\Facility;
use App\Processes\LeaveApplication;
use Debugbar;

class WorkflowController extends Controller
{


    public function test(){

        $facilities = Facility::where('FacilityID', 8888)->get();

        echo '<pre>'; print_r($facilities); echo '</pre>';

//        return 'OK';
    }

    public function step(Request $request){

        $pay = new LeaveApplication($request);

        $pay->run();

        return 'OK';

    }
}
