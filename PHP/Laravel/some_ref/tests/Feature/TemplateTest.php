<?php

namespace Tests\Feature;

use App\Utils\HubHelper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Domains\Role;
use App\Domains\HubUser;
use App\Domains\TemplateTranslator;
use App\Utils\Toolkit;

class TemplateTest extends TestCase
{
    public function testMarkup(){
        $lines = ["* this is line 1", "* this is line 2", "this is normal text", "#order1", "#order2"];
        $html = Toolkit::Markup($lines);
        var_dump($html);
    }

    public function testTemplate()
    {
        $content = <<<EOT
#1
\$T1
?question 1
@dropdown
(11) option 1
(12) option 2
^^
=
* this is line 1
* this is line 2
this is normal text
# order 1
# order 2
=
^^
EOT;
        $t = new TemplateTranslator($content);
        print_r('test TemplateTranslator');
        var_dump($t->Translate());
//        \GuzzleHttp\json_decode($t->Translate());
        $this->assertTrue(true);
    }
    
}