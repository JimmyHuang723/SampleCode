<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
//use Debugbar;
// http://carbon.nesbot.com/docs/
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ResidentCCS extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'ResidentCCS';


    public static function GetScore($residentId){

        $data = ResidentCCS::where('Resident.ResidentId', $residentId)
            ->where('IsActive', true)->get();

        $scores = [];
        foreach ($data as $item) {
            $ccs = $item->CCS;
            foreach ($ccs as $c){
                $category = array_get($c, 'category');
                $score = array_get($c, 'score');
                $s = array_get($scores, $category);
                if(isset($s)){
                    if($s < $score)
                        $scores[$category] = $score;
                }
                else
                    $scores[$category] = $score;
            }
        }
        
        $score = 0;
        foreach($scores as $s)
            $score = $score + $s;
        return $score;
    }


}