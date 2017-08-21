<?php
/**
 * Created by PhpStorm.
 * User: cchang
 * Date: 15/4/17
 * Time: 6:40 PM
 */
namespace App\Domains;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CarePlanView
{
    public $domains;
    public $goals;
    public $observations;
    public $interventions;

    public function __construct()
    {
        $this->domains = Array();
        $this->goals = Array();
        $this->observations = Array();
        $this->interventions = Array();
    }
    public function AddDomain($domain){
        if(!in_array($domain, $this->domains))
            array_push($this->domains, $domain);
    }

    public function AddObservation($data)
    {
        $code = $data['code'];
        Log::debug('obs code = '.$code);
        $text = strtolower($data['text']);
        if($text=='na' || $text=='n/a') return;
        $dt = new Carbon($data['created_at']['date']);
        $data['CreatedAt'] = $dt;
        if (!array_key_exists($code, $this->observations)) {
//            Log::debug('new obs '.$data['text']);
            $this->observations[$code] =  $data;
        } else {

            $oldData = $this->observations[$code];
            $oldCreatedAt = $oldData['CreatedAt'];
//            Log::debug('new obs '.$data['text'] . ' created at '.$data['CreatedAt']);
//            Log::debug('old obs '.$oldData['text'] . ' created at '.$oldData['CreatedAt']);
            // use the latest data
            if($data['CreatedAt'] > $oldCreatedAt) {
//                Log::debug('swap old with new');
                $this->observations[$code] = $data;
            }
        }
    }

    public function AddGoal($data)
    {
        $code = $data['code'];
        Log::debug('goal code = '.$code);
        $text = strtolower($data['goal']);
        if($text=='na' || $text=='n/a' ) return;
        $dt = new Carbon($data['created_at']['date']);
        $data['CreatedAt'] = $dt;
        if(!array_key_exists($code, $this->goals)){
            $this->goals[$code] = $data;
        } else {
            $oldData = $this->goals[$code];
            $oldCreatedAt = $oldData['CreatedAt'];
            // use the latest data
            if($data['CreatedAt'] > $oldCreatedAt)
                $this->goals[$code] = $data;
        }
    }

    public function AddIntervention($data)
    {
        $code = $data['code'];
        Log::debug('intv code = '.$code);
        $text = strtolower($data['text']);
        if($text=='na' || $text=='n/a') return;
        $dt = new Carbon($data['created_at']['date']);
        $data['CreatedAt'] = $dt;
        if(!array_key_exists($code, $this->interventions)){
            $this->interventions[$code] =  $data;
        } else {
            $oldData = $this->interventions[$code];
            $oldCreatedAt = $oldData['CreatedAt'];
            // use the latest data
            if($data['CreatedAt'] > $oldCreatedAt)
                $this->interventions[$code] = $data;
        }
    }

    public function Domains(){
        asort($this->domains);
        return $this->domains;
    }
}