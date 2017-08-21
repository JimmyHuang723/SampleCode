<?php

namespace App\Processes;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use League\Flysystem\Exception;
use App\Domains\Topic;
use App\Domains\HubUser;
use App\Domains\Message;
use App\Utils\HubHelper;
use Illuminate\Support\Facades\Log;

//const OI_NEW = 0;
//const OI_APPROVED = 2;
//const OI_CANCELED = 4;
const TOPIC_TYPE_OI = 14;

class OiApplication extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'wfOiApplication';


    function __construct($handle, $message)
    {
        $this->ReceiptHandle = $handle;
        $this->Message = $message;

        Debugbar::debug($message);

        $this->State = $this->Message->Payload->State;
        $this->Payload = $this->Message->Payload;
    }


    public function run(){
        Debugbar::info("State = ". $this->State);
        Log::debug("State = ". $this->State);

        $ret=true;
        try {

            $sender = HubUser::where('SID', $this->Payload->CreateUser)->get()->first();

            $topic = new Topic();
            $topic->EndOiId = $this->Payload->_id;
            $topic->Type = TOPIC_TYPE_OI;

            if($this->Message->Step == 'create') {

                $this->OnCreate($topic, $sender);

            } else if($this->Message->Step == 'approve') {

                $this->OnApproveOrDecline($topic, $sender);

            } else if($this->Message->Step == 'concerning-rm') {

                $this->OnConfidentialOi($topic, $sender);

            } else if($this->Message->Step == 'genearte-oi'){

            }
            $this->save();
            Debugbar::debug("after save Topic");
            Log::debug("after save Topic");

        }catch(Exception $ex){
            $ret = true;
            $this->OnException($ex);
        }
        return $ret;
    }

    public function OnConfidentialOi($topic, $sender){
        Log::debug('OnConfidentialOi');

        $manager = $this->findManagers($sender);
        if($manager == null) throw new Exception('failed to find manager');
        $recipients = HubHelper::PrepareRecipientsForTopic($manager, $sender);
        $recipientsMessage = HubHelper::GetRecipientsForMessage($manager, $sender);
        $title = 'myHub has received a confidential MyOi';

        $content = 'Use Confidential Oi to view the submission';

        if(sizeof($recipients) > 0 && $topic->EndOiId != null && $topic->EndOiId != '') {
            $topic->Users = $recipients;
            $topic->Title = $title;
            $topic->Content = $content;
            $topic->EndOiId = '';
            $topic->save();

            $message = new Message();
            $message->Subject = $title;
            $message->Content = $content;
            $message->To = $recipientsMessage;
            $message->TId = $topic->_id;

            $message->save();

            HubHelper::CallbackToHub($manager, $title, $topic->_id);

        } else {
            Debugbar::debug('no recipient found ');
            Log::debug('no recipient found ');
        }
        Debugbar::debug($title);
        Log::debug($title);
    }

    public function OnCreate($topic, $sender){
        Log::debug('OnCreate');

        $manager = $this->findManagers($sender);
        if($manager == null) throw new Exception('failed to find manager');
        $recipients = HubHelper::PrepareRecipientsForTopic($manager, $sender);
        $recipientsMessage = HubHelper::GetRecipientsForMessage($manager, $sender);
        $title = $sender->GetFullnameWithFacility($this->Payload) . ' has submitted a MyOi';

        if($this->Payload->ConfidentialTreat)
            $title = 'Hub has received a confidential Oi';

        $content = '';

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

            HubHelper::CallbackToHub($manager, $title, $topic->_id);

        } else {
            Debugbar::debug('no recipient found ');
            Log::debug('no recipient found ');
        }
        Debugbar::debug($title);
        Log::debug($title);
    }

    public function OnApproveOrDecline($topic, $sender){
        Log::debug('OnApproveOrDecline');

        $recipients = HubHelper::GetSenderAsRecipientForTopic($sender);
        $recipientsMessage = HubHelper::GetSenderAsRecipientForMessage($sender);
        $approver = HubUser::where('SID', $this->Payload->Approved->UpdateUser)->get()->first();

        if($this->Payload->AuditState == 1) {
            $title = $approver->Fullname . ' has approved your Oi';
        }
        else if($this->Payload->AuditState == 2) {
            $title = $approver->Fullname . ' has rejected your Oi';
        }

        $content = '';

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
        }else {
            Debugbar::debug('no recipient found ');
            Log::debug('no recipient found ');
        }
        Debugbar::debug($title);
        Log::debug($title);
    }

    public function findManagers($sender){
        $manager = null;
        $isAboutRM = false;

        if($this->Payload->FacilityName == env('HEAD_OFFICE'))
        {
            Debugbar::debug("notify Supervisors");
            Log::debug("notify Supervisors");
            $manager = $sender->Supervisors;
        } else
        {
            if(property_exists($this->Payload, 'IsConcerningRM')){
                $isAboutRM = $this->Payload->IsConcerningRM;
            }
            if($isAboutRM) {
                Debugbar::debug("notify GM");
                Log::debug("notify GM");
                $manager = $sender->GetGeneralManagers($this->Payload->FacilityID);

            } else {
                Debugbar::debug("notify RM");
                Log::debug("notify RM");
                $manager = $sender->GetResidentialManagers($this->Payload->FacilityID);
            }
        }
        return $manager;
    }

    public function onException($ex){
        Debugbar::debug("onException");
        Debugbar::debug($ex->getMessage());

        // notify helpdesk
        $topic = new Topic();
        $title = 'OiApplication - '.$ex->getMessage();
        $content = $this->Payload;
        $topic->CallHelpdesk($title, json_encode($content), $this->Message);

    }



}