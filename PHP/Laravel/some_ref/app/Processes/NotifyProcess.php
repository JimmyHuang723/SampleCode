<?php

namespace App\Processes;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use App\Domains\HubUser;
use App\Domains\Topic;
use App\Domains\Message;
use App\Utils\HubHelper;
use Illuminate\Support\Facades\Log;

const TOPIC_TYPE_MESSAGE = 0;

class NotifyProcess extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'wfNotifyProcess';

    function __construct($handle, $message)
    {
        $this->ReceiptHandle = $handle;
        $this->Message = $message;

//        Debugbar::debug($message);

        $this->State = $this->Message->Payload->State;
        $this->Payload = $this->Message->Payload;
    }

    public function run()
    {
        Debugbar::info("NotifyProcess, State = " . $this->State);
        Log::debug("NotifyProcess, State = " . $this->State);

        $ret = true;
        try {

            $sender = HubUser::where('SID', $this->Payload->Created->UpdateUser)->get()->first();

            if(property_exists($this->Payload, 'NewUser')){

                // add new user to Message
                $msg = Message::where('TId', $this->Payload->_id)->get()->first();
                if($msg != null) {
                    $newUser = (object)array(
                        'ToUser' => (object)array(
                            'SID' => $this->Payload->NewUser->SID,
                            'UserName' => $this->Payload->NewUser->UserName,
                            'Name' => $this->Payload->NewUser->Name
                        ),
                        'IsStar' => 0,
                        'State' => 0,
                        'Color' => null
                    );
//                    Debugbar::debug($newUser);
//                    Debugbar::debug($msg->To);
                    $users = [];
                    foreach ($msg->To as $u)
                        array_push($users, $u);
                    array_push($users, $newUser);
                    $msg->To = $users;
                    $msg->save();
//                    Debugbar::debug($msg->To);
                    //Debugbar::debug($msg);
                }

                if($sender != null) {
                    $title = 'You are invited by ' . $sender->Fullname . 'to join a conversation';
                    HubHelper::CallbackToHub($this->Payload->NewUser, $title, $this->Payload->_id);
                }
            }

        }catch(Exception $ex){
            $ret = true;
            $this->OnException($ex);
        }
        return $ret;
    }

    public function onException($ex){
        Debugbar::debug("onException");
        Log::debug("onException");

        Debugbar::debug($ex->getMessage());
        Log::debug($ex->getMessage());

        // notify helpdesk
        $topic = new Topic();
        $title = 'LeaveApplication - '.$ex->getMessage();
        $content = $this->Payload;
        $topic->CallHelpdesk($title, json_encode($content), $this->Message);
    }
}