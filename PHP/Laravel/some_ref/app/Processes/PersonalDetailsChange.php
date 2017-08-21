<?php

namespace App\Processes;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use App\Domains\HubUser;
use App\Domains\Topic;
use App\Domains\Message;
use App\Utils\HubHelper;

const TOPIC_TYPE_MESSAGE = 0;
const PDC_CREATE = 'create';
const PDC_REPLY = 'reply';
const PDC_UPDATE = 'update';

class PersonalDetailsChange extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'wfPersonalDetailsChange';

    function __construct($handle, $message)
    {
        $this->ReceiptHandle = $handle;
        $this->Message = $message;

        $this->State = $this->Message->Payload->State;
        $this->Payload = $this->Message->Payload;
    }


    public function run()
    {
        Debugbar::info("PersonalDetailsChange, State = " . $this->State);

        $ret = true;
        try {

            $sender = HubUser::where('SID', $this->Payload->Created->UpdateUser)->get()->first();

            $topic = new Topic();
            $topic->PersonalDetailChangeId = $this->Payload->_id;
            $topic->Type = TOPIC_TYPE_MESSAGE;

            if ($this->State == PDC_CREATE) {

                $this->OnCreate($topic, $sender);

            } else if ($this->State == PDC_REPLY) {

                $this->OnReply($topic, $sender);

            } else if ($this->State == PDC_UPDATE) {

                $this->OnUpdate($topic, $sender);

            }
            $this->save();
            Debugbar::debug("after save Topic");

        }catch(Exception $ex){
            $ret = true;
            $this->OnException($ex);
        }
        return $ret;
    }

    public function OnCreate($topic, $sender){
        Debugbar::debug("OnCreate");

        $title = $sender->Fullname .' has submitted a personal details change form';
        $content = '';

        $payrolls = HubHelper::GetPayrollCoordinators();

        if($payrolls == null || sizeof($payrolls) == 0) throw new Exception('No payroll officer found');

        $recipients = HubHelper::PrepareRecipientsForTopic($payrolls, $sender);
        $recipientsMessage = HubHelper::GetRecipientsForMessage($payrolls, $sender);

        if(sizeof($recipients) > 0) {
            $topic->Users = $recipients;
            $topic->Title = $title;
            $topic->Content = $content;

            $topic->save();

            $message = new Message();
            $message->Subject = $title;
            $message->Content = $content;
            $message->To = $recipientsMessage;
            $message->TId = $topic->_id;

            $message->save();

            HubHelper::CallbackToHub($recipients, $title, $topic->_id);
        } else
            Debugbar::debug('no recipient found ');
        Debugbar::debug($title);

    }


    public function OnReply($topic, $sender){
        Debugbar::debug("OnComment");

        $title = 'Account department needs you to review your request to update details';
        $content = '';

        $recipients = HubHelper::GetSenderAsRecipientForTopic($sender);
        $recipientsMessage = HubHelper::GetSenderAsRecipientForMessage($sender);

        if(sizeof($recipients) > 0) {

            $topic->Users = $recipients;
            $topic->Title = $title;
            $topic->Content = $content;

            $topic->save();

            $message = new Message();
            $message->Subject = $title;
            $message->Content = $content;
            $message->To = $recipientsMessage;
            $message->TId = $topic->_id;

            $message->save();

            HubHelper::CallbackToHub($sender, $title, $topic->_id);
        }else
            Debugbar::debug('no recipient found ');

        Debugbar::debug($title);
    }

    public function OnUpdate($topic, $sender){
        Debugbar::debug("OnUpdate");

        $title = 'Your request to update personal details has been actioned.';
        $content = '';

        $recipients = HubHelper::GetSenderAsRecipientForTopic($sender);
        $recipientsMessage = HubHelper::GetSenderAsRecipientForMessage($sender);

        if(sizeof($recipients) > 0) {

            $topic->Users = $recipients;
            $topic->Title = $title;
            $topic->Content = $content;

            $topic->save();

            $message = new Message();
            $message->Subject = $title;
            $message->Content = $content;
            $message->To = $recipientsMessage;
            $message->TId = $topic->_id;

            $message->save();

            HubHelper::CallbackToHub($sender, $title, $topic->_id);
        }else
            Debugbar::debug('no recipient found ');

        Debugbar::debug($title);

    }

    public function onException($ex){
        Debugbar::debug("onException");
        Debugbar::debug($ex->getMessage());

        // notify helpdesk
        $topic = new Topic();
        $title = 'Personal Details Change - '.$ex->getMessage();
        $content = $this->Payload;
        $topic->CallHelpdesk($title, json_encode($content), $this->Message);
    }

}