<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Support\Facades\Auth;

class UserSessionLog extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'UserSessionLog';

    public static function Log($userId, $process, $step, $value){
//        $user = HubUser::where('SID', $sid)->get()->first();

        $log = new UserSessionLog();
        $log->SID = $userId;
        $log->process = $process;
        $log->step = $step;
        $log->value = $value;
        $log->save();
    }

    public static function Get($userId, $process, $step){
        $data = UserSessionLog::where('SID', $userId)
            ->where('process', $process)
            ->where('step', $step)
            ->orderBy('created_at', 'desc')->get()->take(1);
        return $data->value;
    }
}
