<?php

namespace App\Processes;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use App\Domains\Topic;
use App\Domains\HubUser;
use App\Domains\Role;
use App\Domains\Message;
use App\Domains\BatchNotification;
use App\Utils\HubHelper;
use App\Utils\RmqHelper;
use Illuminate\Support\Facades\Log;

const VISA_CHECK_EXPIRED = 'expired';
const VISA_CHECK_EXPIRE_IN_7_DAYS = 'expire in 7';
const VISA_CHECK_EXPIRE_IN_30_DAYS = 'expire in 30';

class VisaCheck extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'wfVisaCheck';

    function __construct()
    {

    }


    public function run($handle, $message)
    {
        $this->ReceiptHandle = $handle;
        $this->Message = $message;

//        Debugbar::debug($message);

        $this->Step = $this->Message->Step;
        $this->Payload = $this->Message->Payload;

        $ret = true;
        try {

            $sender = HubUser::where('SID', $this->Payload->SID)->get()->first();
            Log::debug('staff is ' . $sender->FullnameAndUserName);

            // if staff is not active, no need to go further
            if(!$sender->IsActive) {
                Log::debug('inactive staff, skip');
                return $ret;
            }
            $topic = new Topic();
            $topic->Type = 0;

            $expiryDate = new Carbon($this->Payload->ExpiryDate);
            if ($this->Step == VISA_CHECK_EXPIRED) {
                $title = $sender->FullnameAndSNumber . ' police check expired on ' . $expiryDate->format('d/m/Y');
            } else if ($this->Step == VISA_CHECK_EXPIRE_IN_7_DAYS) {
                $title = $sender->FullnameAndSNumber . ' police check will expire in 7 days by ' . $expiryDate->format('d/m/Y');
            } else if ($this->Step == VISA_CHECK_EXPIRE_IN_30_DAYS) {
                $title = $sender->FullnameAndSNumber . ' police check will expire in 30 days by ' . $expiryDate->format('d/m/Y');
            }else{
                Log::error($this->Step);
            }
            Log::debug($title);
            $content = '';

            if($this->hasBeenNotifiedLast24Hours($title, $sender))
            {
                Log::info("staff is already being notified in the last 24 hours, skip");
                return $ret;
            }
            $recipients = HubHelper::GetSenderAsRecipientForTopic($sender);
            $recipientsMessage = HubHelper::GetSenderAsRecipientForMessage($sender);


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

            // put into HR mailbox
            $managers = Role::GetHRManagers();
            $notice = new BatchNotification();
            foreach ($managers as $m){
                array_push($notice->receivers, $m->_id);
            }
            array_push($notice->relatedParties, $sender->_id);
            $notice->topic = 'visa-check-'.$this->Step;
            $notice->title = $title;

            RmqHelper::send('BatchNotification', $notice);

            $this->title = $title;
            $this->senderId = $sender->_id;
            $this->save();

        } catch (Exception $ex) {
            $ret = true;
            $this->OnException($ex);
        }
        return $ret;
    }

    private function hasBeenNotifiedLast24Hours($title, $sender){
        $ret = false;

        $data = VisaCheck::where('title', $title)
            ->where('senderId', $sender->_id)
            ->get();
        foreach ($data as $d){
            $hoursOld = $d->created_at->diffInHours(Carbon::now());
            if($hoursOld <= 24){
                $ret=true;
                break;
            }
        }
        return $ret;
    }
}