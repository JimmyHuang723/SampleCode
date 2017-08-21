<?php

namespace App\Domains;

use Faker\Provider\DateTime;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
// http://carbon.nesbot.com/docs/
use Carbon\Carbon;
use MongoDB\BSON\UTCDatetime;


class ProgressNote extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'ProgressNote';

    protected $dates = ['created_at', 'updated_at'];

    public static function GetLastNProgressNotes($residentId, $max){

        $pnotes = ProgressNote::where('Resident.ResidentId', $residentId)
            ->orderBy('created_at', 'desc')->take($max)->get();

        return $pnotes;
    }

    public function GetCreatedDateAttribute(){
        return $this->created_at->format('d-M-Y');
    }

    public static function GetLastSevenDaysProgressNotes($residentId, $max){

        $date = Carbon::now()->subWeek()->startOfDay();
        $fromDate = new UTCDateTime($date->timestamp*1000);

        $pnotes = ProgressNote::orderBy('created_at', 'desc')
            ->where('Resident.ResidentId', $residentId)
            ->where('created_at', '>=', $fromDate)
            ->take($max)
            ->paginate(15);

        return $pnotes;
    }

    public static function GetSearchNotesResult($notes, $residentId, $date_start, $date_end, $user){

//        if($date_start AND $date_end){
            $startDate = (new Carbon($date_start))->startOfDay();
            $endDate = (new Carbon($date_end))->endOfDay();
            $st = new UTCDateTime($startDate->timestamp*1000);
            $et = new UTCDateTime($endDate->timestamp*1000);
            Debugbar::debug($date_start);
            Debugbar::debug($st->toDateTime());
            Debugbar::debug($date_end);
            Debugbar::debug($et->toDateTime());

//        }

//        $pnotes = ProgressNote::where('Resident.ResidentId', $residentId)
//                    ->when($notes, function ($query) use ($notes) {
//                        return $query->where('notes', 'LIKE', "%$notes%");
//                    })
//                    ->when($st, function ($query) use ($st) {
//                        return $query->where('created_at', '>=', $st);
//                    })
//                    ->when($et, function ($query) use ($et) {
//                        return $query->where('created_at', '<=', $et);
//                    })
//                    ->when($user, function ($query) use ($user) {
//                        return $query->where('CreatedBy.FullName', 'LIKE', "%$user%");
//                    })
//                    ->paginate(15);
//
//        Debugbar::debug($pnotes);
//        if(strlen(trim($notes)) > 0){
//            $pnotes->where('notes', 'LIKE', "%$notes%");
//        }
//        if($date_start AND $date_end){
//            $pnotes->where('updated_at', '>=', $st);
//            $pnotes->where('updated_at', '<=', $et);
//        }
//
//        if(strlen(trim($user)) > 0){
//            $pnotes->where('CreatedBy.FullName', 'LIKE', "%$user%");
//        }
//
//        $pnotes->where(1)->paginate(15);

        if(trim($notes) != '' AND $date_start AND $date_end AND strlen($user)>0){
            $pnotes = ProgressNote::where('Resident.ResidentId', $residentId)
                ->where('created_at', '>=', $st)
                ->where('created_at', '<=', $et)
                ->where('notes', 'LIKE', "%$notes%")
                ->where('CreatedBy.FullName', 'LIKE', "%$user%")
                ->paginate(15);
        }

        if(trim($notes) != '' AND $date_start AND $date_end){
            $pnotes = ProgressNote::where('Resident.ResidentId', $residentId)
                ->where('created_at', '>=', $st)
                ->where('created_at', '<=', $et)
                ->where('notes', 'LIKE', "%$notes%")
                ->paginate(15);
        }

        if(strlen($notes) > 0){
            $pnotes = ProgressNote::where('Resident.ResidentId', $residentId)
                ->where('notes', 'LIKE', "%$notes%")
                ->paginate(15);
        }

        if($date_start AND $date_end){
            $pnotes = ProgressNote::where('Resident.ResidentId', $residentId)
                ->where('created_at', '>=', $st)
                ->where('created_at', '<=', $et)
                ->paginate(15);
        }

        if(strlen($user) > 0){
            $pnotes = ProgressNote::where('Resident.ResidentId', $residentId)
                ->where('CreatedBy.FullName', 'LIKE',  "%$user%")
                ->paginate(15);
        }

        if(trim($notes) != '' AND strlen($user)>0){
            $pnotes = ProgressNote::where('Resident.ResidentId', $residentId)
                ->where('notes', 'LIKE', "%$notes%")
                ->where('CreatedBy.FullName', 'LIKE', "%$user%")
                ->paginate(15);
        }


        return $pnotes;

    }

}
