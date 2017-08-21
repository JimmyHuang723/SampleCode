<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
Use App\Domains\Topic;

class TopicTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTopicCreate()
    {
        $topic = new Topic();
        //var_dump($topic);

        $topic->save();

        $this->assertTrue(true);
    }

    public function testCallHelpdesk()
    {
        $topic = new Topic();
        $topic->CallHelpdesk('test title', 'test content', $topic);
        $this->assertTrue(true);
    }
}
