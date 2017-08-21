<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use Carbon\Carbon;
use JsonSerializable;

class BatchNotification  extends Eloquent implements JsonSerializable
{
    protected $connection = 'mongodb';
    protected $collection = 'BatchNotification';

    public $receivers;       // who to receive this notification
    public $senders;         // who send this notification
    public $relatedParties;  // who this message is related to;
    public $topic;           // topic of this notification for grouping purpose
    public $titles;          // a list of titles
    public $title;           // title of this notification, will be different per notification

    function __construct()
    {
        $this->today = new Carbon();
        $this->receivers = [];
        $this->senders = [];
        $this->relatedParties = [];
        $this->topic = [];
        $this->titles = [];
        $this->title = '';
    }

    public function jsonSerialize() {
        return [
            'receivers' => $this->receivers,
            'senders' => $this->senders,
            'relatedParties' => $this->relatedParties,
            'topic' => $this->topic,
            'title' => $this->title,
            'today' => $this->today
        ];
    }

    public function AddReceiver($userId){
        if(!in_array($userId, $this->receivers))
            array_push($this->receivers, $userId);
    }

    public function AddTitle($title){
        if(!in_array($title, $this->titles))
            array_push($this->titles, $title);
    }

}