<?php

namespace App\Http\Controllers;

use App\Processes\LeaveApplication;
use App\Processes\OiApplication;
use App\Processes\NotifyProcess;
use App\Processes\PersonalDetailsChange;
use Illuminate\Http\Request;
use AWS;
use Debugbar;

class SqsController extends Controller
{
    public function index(){

    }

    public function show(Request $request){
        return 'mode=['.$request->input('mode').']';
//        return 'OK';
    }

    public function createq($qname){
        if($qname == null) $qname = 'q1';

        $client = AWS::createClient('sqs');
        $result = $client->createQueue(array('QueueName' => $qname));
        $queueUrl = $result->get('QueueUrl');
        return $queueUrl;
    }

    public function send(){
        $msg = request('msg');
        if($msg == null) return 'OK';

        $qurl = env('SQS_URL', 'https://sqs.ap-southeast-2.amazonaws.com/259299767513/hub-dev');
        $client = AWS::createClient('sqs');
        $result = $client->sendMessage(array(
            'QueueUrl' => $qurl,
            'MessageBody' => $msg));
        return 'PASS';
    }

    public function fetch(Request $request){

        $data = null;

        $mode = $request->input('mode');
        if($mode == '') $mode = env('IFTTN_MODE');

        $max = $request->input('max');
        if($max == '') $max = env('MAX_MESSAGE_TO_PROCESS');

        $qurl = env('SQS_URL', 'https://sqs.ap-southeast-2.amazonaws.com/259299767513/hub-dev');
        $client = AWS::createClient('sqs');

        $messageHandled=0;
        for($i = 0; $i < $max; $i++) {

            $result = $client->receiveMessage(array(
                'QueueUrl' => $qurl));

            $message = $result['Messages'];
//            Debugbar::info($message);

            if (sizeof($message) > 0) {

                $handle = $message[0]['ReceiptHandle'];
                $payloadString = $message[0]['Body'];
                $payload = json_decode($payloadString);

                Debugbar::debug($payload);

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
                }
                if ($runOk) {
                    if ($mode == 'live') {
                        Debugbar::info('remove message from SQS');
                        // remove the message from SQS
                        $client->deleteMessage(array(
                            'QueueUrl' => $qurl,
                            'ReceiptHandle' => $handle));
                    }
                }
            }
            if (sizeof($message) > 0) {
                Debugbar::info($message);
                $messageHandled++;
//                return view('sqs', ['message' => (object)$message[0]]);
            } else {
                break;
//                return view('sqs', ['message' => null]);
            }
        }
        return view('sqs',
            ['mode' => $mode,
            'max' => $max,
            'handled' => $messageHandled]);
    }

}
