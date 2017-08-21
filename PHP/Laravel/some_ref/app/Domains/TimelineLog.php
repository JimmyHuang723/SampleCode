<?php

namespace App\Domains;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Domains\HubUser;
use App\Domains\Resident;

class TimelineLog extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'TimelineLog';

    public static function Log($process, $step, $module, $related){

        $tlog = new TimelineLog();
        $tlog->process = $process;
        $tlog->step = $step;
        $tlog->module = $module;
        $tlog->related = $related;
        $today = Carbon::now();
        $tlog->hour = $today->hour;

        if(array_key_exists('userId', $related)){
            $user = HubUser::find($related['userId']);
            $tlog->UserName = $user->Fullname;
        }
        if(array_key_exists('residentId', $related)){
            $resident = Resident::find($related['residentId']);
            $tlog->ResidentName = $resident->Fullname;
        }
        $tlog->save();

    }

    public static function GetUserActivities($userId, $max){
        $activities = TimelineLog::where('related.user.UserId', $userId)->orderBy('created_at', 'desc')->get()->take($max);
        return $activities;
    }

    public static function GetResidentActivities($residentId, $fromDate, $toDate){
        $activities = TimelineLog::where('related.resident.ResidentId', $residentId)->orderBy('created_at', 'desc')->get();
        return $activities;
    }
}
