<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domains\HubUser;
use App\Domains\UserFacility;
use App\Domains\Facility;
use Debugbar;
use Auth;

class HubUserController extends Controller
{
    public function index(){

//        $users = HubUser::where('UserName', 'mahona')->get();
        $users = HubUser::all()->take(1000);

        return view('hubuser', ['users' => $users]);
    }

    public function profile(){
        $user = Auth::user();
        $sid = $user->SID;
        if(!isset($sid))
            return redirect('/user');

        $hubUser = HubUser::where('SID', $sid)->get()->first();
        if(!isset($hubUser))
            return redirect('/user');

        return view('hubuser.profile', ['hubUser' => $hubUser]);

    }

}
