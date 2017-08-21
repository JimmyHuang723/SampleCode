<?php

namespace App\Http\Controllers;
use App\Domains\BatchNotification;
use App\Domains\Topic;
use App\Domains\Message;
use App\Utils\HubHelper;
use PhpAmqpLib\Connection\AMQPStreamConnection;

use Illuminate\Http\Request;
use AWS;
use Debugbar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;

class BatchNotificationController extends Controller
{
    public function fetch(Request $request)
    {
        $token = Input::get('token');
        if($token!=env('WORKFLOW_TOKEN')) return 'Test OK';
        $data = null;

        Debugbar::debug('enter BatchNotificationController');

        $mode = $request->input('mode');
        if($mode == '') $mode = env('IFTTN_MODE');

        $max = $request->input('max');
        if($max == '') $max = env('MAX_MESSAGE_TO_BATCH_PROCESS');

        $connection = new AMQPStreamConnection(env('RMQ_URL'), env('RMQ_PORT'), env('RMQ_USER'), env('RMQ_PASSWORD'));
        $channel = $connection->channel();
        $channel->queue_declare(env('RMQ_BATCH_QUEUE'), false, true, false, false);

        try{
            $handles = [];
            $mails = array();
            $messageHandled=0;
            for($i = 0; $i < $max; $i++) {
                $result = $channel->basic_get(env('RMQ_BATCH_QUEUE'), false);
                if($result == null) {
                    Log::debug('no more message in queue');
                    break;
                }
                $message = base64_decode($result->body);
                if (sizeof($message) > 0) {
                    $messageHandled++;
                    $handle = $result->delivery_info["delivery_tag"];

                    array_push($handles, $handle);
                    $payloadString = $message;
                    $payload = json_decode($payloadString);
    //                Debugbar::debug($payload);
                    if($payload == null || !is_object($payload)) {
                        Log::debug('$payload is NULL, skip');
                        continue;
                    }
                    $key = $payload->topic;
                    if(!array_key_exists($key, $mails)){
    //                    Log::debug('new topic is '.$key);

                        $b = new BatchNotification();
                        $b->receivers = $payload->receivers;
                        $b->topic = $payload->topic;
                        $b->AddTitle($payload->title);
                        $mails[$key]=$b;
    //                    array_push($mails, $a);
                    } else {
    //                    Log::debug('existing topic is '.$key);
                        $b = $mails[$key];
    //                    Debugbar::debug($b);
                        foreach ($payload->receivers as $receiver) {
                            $b->AddReceiver($receiver);
                        }
                        $b->AddTitle($payload->title);
                    }
                }
    //            Log::debug('mails count is '+ sizeof($mails));

            }
            foreach ($mails as $mail) {
                Debugbar::debug($mail);
                $recipients = HubHelper::PrepareRecipientsForTopicByUserId($mail->receivers);
                $recipientsMessage = HubHelper::PrepareRecipientsForMessageByUserId($mail->receivers);
                if ($mail->topic == 'visa-check-expired')
                    $title = 'Visa expired list';
                else if ($mail->topic == 'visa-check-expire in 7')
                    $title = 'Visa to expire in 7 days list';
                else if ($mail->topic == 'visa-check-expire in 30')
                    $title = 'Visa to expire in 30 days list';
                else if ($mail->topic == 'police-check-expired')
                    $title = 'Police check expired list';
                else if ($mail->topic == 'police-check-expire in 7')
                    $title = 'Police check to expire in 7 days list';
                else if ($mail->topic == 'police-check-expire in 30')
                    $title = 'Police check to expire in 30 days list';
                else
                    $title = $mail->topic;
                $content = '<ul class="batch-notification">';

                foreach ($mail->titles as $t) {
                    $content = $content . '<li class="batch-notification-item">'
                        . $t . '</li>';
                }
                $content = $content . '</ul>';

                $topic = new Topic();
                $topic->Type = 0;

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

                Log::debug($title);
            }

            if ($mode == 'live') {
                foreach ($handles as $handle) {
                    Debugbar::info('remove message from RMQ');
                    // remove the message from SQS
                    $channel->basic_ack($handle);
                }
            }
        }catch(Exception $ex){
            $ret = true;
            $this->OnException($ex);
        }
        $channel->close();
        $connection->close();
//        Log::debug('mails count is '+ sizeof($mails));
//        Debugbar::debug($mails);
        return view('sqs',
            ['mode' => $mode,
                'max' => $max,
                'handled' => $messageHandled]);
    }

    public function onException($ex){
        Log::debug("onException");

        Log::debug($ex->getMessage());

        // notify helpdesk
        $topic = new Topic();
        $title = 'BatchNotification - '.$ex->getMessage();
        $content = '';
        $topic->CallHelpdesk($title, $content, $this->Message);
    }

}