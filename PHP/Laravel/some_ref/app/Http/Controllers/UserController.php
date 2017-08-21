<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

use App;
use App\Domains\HubUser;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(){
        $user = Auth::user();
        return view('user.dashboard',[
            'title' => 'user_dashboard',
            'user' => $user,
        ]);
    }

    public function link_to_hub($userNo){
        $user = Auth::user();

        return view('user.link_to_hub',[
            'title' => 'link_to_hub',
            'user' => $user,
            'result' => []
        ]);
    }

    public function search(){
        $user = Auth::user();
        $name = Input::get('name');
        $result = HubUser::where('SStatus', 'A')
            ->where(function($query) use ($name){
                $query->where('SGivenNames', 'like', $name .'%')
                    ->orWhere('SSurname', 'like', $name .'%');
            })->orderBy('SSurname')
            ->orderBy('SGivenNames')->get()->take(50);

        return view('user.link_to_hub',[
            'title' => 'link_to_hub',
            'user' => $user,
            'result' => $result,
        ]);
    }

    public function linkuser($userNo, $userId){
        $user = Auth::user();
        $user->SID = $userId;
        $user->save();

        return redirect('/home');
    }

    public function create_role($userNo){
        $user = Auth::user();
        return view('user.create_role',[
            'title' => 'create_role',
            'user' => $user,
        ]);
    }

    public function assign_role($userNo){
        $user = Auth::user();
        return view('user.assign_role',[
            'title' => 'assign_role',
            'user' => $user,
        ]);
    }

    public function assign_facility($userNo){
        $user = Auth::user();
        return view('user.assign_facility',[
            'title' => 'assign_facility',
            'user' => $user,
        ]);
    }

}