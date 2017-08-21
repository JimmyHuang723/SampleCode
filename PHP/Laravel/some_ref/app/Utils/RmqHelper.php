<?php

namespace App\Utils;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;
use Debugbar;


class RmqHelper {

    // $message is a json object
    public static function Send($queue, $message)
    {
        $connection = new AMQPStreamConnection(env('RMQ_URL'), env('RMQ_PORT'), env('RMQ_USER'), env('RMQ_PASSWORD'));
        $channel = $connection->channel();

        $json = json_encode($message);
//        Debugbar::debug($json);
        $body = base64_encode($json);


        $msg = new AMQPMessage($body);

        $channel->queue_declare($queue, false, true, false, false);
        $channel->basic_publish($msg, '', $queue);
        $channel->close();
        $connection->close();

    }

    public static function Receive($queue, $toSendAck = false){
        $connection = new AMQPStreamConnection(env('RMQ_URL'), env('RMQ_PORT'), env('RMQ_USER'), env('RMQ_PASSWORD'));
        $channel = $connection->channel();
        $channel->queue_declare($queue , false, true, false, false);

        $msg = $channel->basic_get($queue, false);
//        print_r($msg->body);
//        var_dump($msg->delivery_info);
        if($toSendAck)
            $channel->basic_ack($msg->delivery_info["delivery_tag"]);
        return base64_decode($msg->body);
    }

}