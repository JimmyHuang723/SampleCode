<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Utils\RmqHelper;
use Illuminate\Support\Facades\Log;

class RabbitMQTest extends TestCase
{

    public function testSend(){
        Log::debug('testSend');

        RmqHelper::Send('BatchNotification', "testing123");

        $this->assertTrue(true);

    }

    public function testReceive(){
        Log::debug('testReceive');
        $msg = RmqHelper::Receive('BatchNotification', true);
        $obj = \GuzzleHttp\json_decode($msg);
        var_dump($obj);
        $this->assertTrue($obj->topic == 'expired');
    }

    public function testAssessment(){
        $msg = RmqHelper::Receive(env('RMQ_ASSESSMENT_QUEUE'), true);
        $obj = \GuzzleHttp\json_decode($msg);
        var_dump($obj);
        $this->assertTrue($obj->topic == 'expired');
    }
}
