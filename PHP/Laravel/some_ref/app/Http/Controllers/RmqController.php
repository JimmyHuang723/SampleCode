<?php

namespace App\Http\Controllers;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use App\Processes\LeaveApplication;
use App\Processes\OiApplication;
use App\Processes\NotifyProcess;
use App\Processes\PersonalDetailsChange;
use App\Processes\PoliceCheck;
use App\Processes\VisaCheck;

use Illuminate\Http\Request;
use AWS;
use Debugbar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;

class RmqController extends Controller
{
    public function fetch(Request $request)
    {
        $token = Input::get('token');
        if($token!=env('WORKFLOW_TOKEN')) return 'Test OK';

        $data = null;

        Log::debug('enter RmqController');

        $mode = $request->input('mode');
        if($mode == '') $mode = env('IFTTN_MODE');

        $max = $request->input('max');
        if($max == '') $max = env('MAX_MESSAGE_TO_PROCESS');

        $connection = new AMQPStreamConnection(env('RMQ_URL'), env('RMQ_PORT'), env('RMQ_USER'), env('RMQ_PASSWORD'));
        $channel = $connection->channel();
        $channel->queue_declare(env('RMQ_QUEUE'), false, true, false, false);

        $messageHandled=0;
        for($i = 0; $i < $max; $i++) {

            $result = $channel->basic_get(env('RMQ_QUEUE'), false);
            if($result == null) {
                Log::debug('no more message in queue');
                break;
            }

//            Debugbar::info($result->body);
            $message = base64_decode(utf8_decode($result->body));
//            dd(base64_decode($message));


            if (sizeof($message) > 0) {

                $handle = $result->delivery_info["delivery_tag"];
                $payloadString = utf8_encode($message);
//                Log::debug('$payloadString='.$payloadString);
//                Debugbar::debug($payloadString);

                $payload = json_decode($payloadString);

                Debugbar::debug($payload);
                if($payload == null) {
                    Log::debug('$payload is NULL, skip');
                    continue;
                }
                Log::debug('process is '.$payload->Process);

                $runOk = false;
                if ($payload->Process == "leave-application") {

                    $leave = new LeaveApplication($handle, $payload);
                    $runOk = $leave->run();

                } else if ($payload->Process == "oi-application") {
                    $oi = new OiApplication($handle, $payload);
                    $runOk = $oi->run();

                } else if ($payload->Process == "notify-application") {
                    $np = new NotifyProcess($handle, $payload);
                    $runOk = $np->run();

                } else if ($payload->Process == "personal-details-change") {
                    $np = new PersonalDetailsChange($handle, $payload);
                    $runOk = $np->run();

                } else if ($payload->Process == "police-check-application") {
                    $np = new PoliceCheck();
                    $runOk = $np->run($handle, $payload);
                } else if ($payload->Process == "visa-check-application") {
                    $np = new VisaCheck();
                    $runOk = $np->run($handle, $payload);
                }
                if ($runOk) {
                    if ($mode == 'live') {
                        Debugbar::info('remove message from RMQ');
                        // remove the message from SQS
                        $channel->basic_ack($handle);
                    }
                }
            }
            if (sizeof($message) > 0) {
//                Debugbar::info($message);
                $messageHandled++;
//                return view('sqs', ['message' => (object)$message[0]]);
            } else {
                break;
//                return view('sqs', ['message' => null]);
            }
        }

        Log::debug($messageHandled . ' message  handled');

        return view('sqs',
            ['mode' => $mode,
                'max' => $max,
                'handled' => $messageHandled]);


    }
}
