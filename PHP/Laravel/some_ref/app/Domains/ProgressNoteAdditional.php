<?php

namespace App\Domains;

use Faker\Provider\DateTime;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
//use Debugbar;
// http://carbon.nesbot.com/docs/
use Carbon\Carbon;
//use MongoDB\BSON\UTCDatetime;


class ProgressNoteAdditional extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'ProgressNoteAdditional';

    protected $dates = ['created_at', 'updated_at'];

    protected $fillable =['notes', 'created_at', 'updated_at'];

    public static function GetLastNAdditionalProgressNotes($pnId, $max){

        $pnotes = ProgressNoteAdditional::where('ProgressNoteId', $pnId)
            ->orderBy('created_at', 'desc')->take($max)->get();

        return $pnotes;
    }
    public function GetCreatedDateAttribute(){
        return $this->created_at->format('d-M-Y');
    }


}
