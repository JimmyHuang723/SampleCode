<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use Carbon\Carbon;
use App\Domains\Message;

class Topic extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Topic';

    function __construct()
    {
        $today = new Carbon();

        $this->_id = new \MongoDB\BSON\ObjectId();
        $this->Title = '';
        $this->Content='';
        $this->Users = [];
        $this->Roles = [];
        $this->FacilityId = 0;
        $this->HideUsers = [];
        $this->Created =  (object) array(
            'Note' => '',
            'EnterUser' => null,
            'EnterUserName' => '',
            'EnterDate' => (object)array(
                'Date' => (new \MongoDB\BSON\UTCDateTime()),
                'Epoch' => $today->getTimestamp()
            )
        );
        $this->State = 0;
        $this->Comments = [];
        $this->Likes = [];
        $this->Type = 0;
        $this->IFTTN = env('IFTTN_VERSION');
    }

    public function CallHelpdesk($title, $content, $message){

        $helpdeskUser = HubUser::where('UserName', '1801')->get()->first();
        $user = (object)array(
            'SID' => $helpdeskUser->SID,
            'UserName' => $helpdeskUser->UserName,
            'Name' => $helpdeskUser->SGivenNames . ' ' .$helpdeskUser->SSurname
        );
        $this->Users = [$user];
        $this->Title = $title;
        $this->Content = $content;
        $this->Message = $message;

        $this->save();

        $user = (object)array(
            'ToUser' => (object) array(
                'SID' => $helpdeskUser->SID,
                'UserName' => $helpdeskUser->UserName,
                'Name' => $helpdeskUser->SGivenNames . ' ' .$helpdeskUser->SSurname
            ),
            'IsStar' => 0,
            'State' => 0,
            'Color' => null
        );

        $message = new Message();
        $message->Subject = $title;
        $message->Content = $content;
        $message->To = [$user];
        $message->TId = $this->_id;

        $message->save();
    }

}