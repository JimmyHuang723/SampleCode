<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Domains\TemplateTranslator;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testString(){
        $s = "[demt] The Resident has a diagnosis of Dementia";
        preg_match_all('~\[(.+?)\]~', $s, $m);
        var_dump($m[1]);
        $this->assertTrue(true);
    }

    public function testTranslator(){
        $content = <<<EOT
#1
\$ADR010
?Assessment information source
@checkbox
[010] Family meeting
[020] The Resident
[030] ACCR
[040] The Residents GP
[050] Hospital Transfer Letter
^^
#2
\$ADR20
?Other information source
@text
^^
#3
\$ADR30
?Reason for admission /diagnosis
~Use complete sentence
@text
^^
#4
\$ADR40
?Admission baseline Temperature, Pulse,Respiration & BGL
@text
#1
\$ADR010
?Assessment information source
@checkbox
[010] Family meeting
[020] The Resident
[030] ACCR
[040] The Residents GP
[050] Hospital Transfer Letter
^^
#2
\$ADR20
?Other information source
@text
^^
#3
\$ADR30
?Reason for admission /diagnosis
~Use complete sentence
@text
^^
#4
\$ADR40
?Admission baseline Temperature, Pulse,Respiration & BGL
@text
^^
#5
\$ADR50
?Admission baseline Blood Pressure
@text
^^
#6
\$ADR60
?Admission baseline pupils reacting and size
@text
^^
#7
\$ADR70
?Physical Appearance
~Use a sentence
@text
>obs=Body Care and Personal Hygiene
^^
#8
\$ADR80
?Language Spoken
~Use a sentence
@text
>obs=Communication and Language
^^
#9
\$ADR90
?Communication
@checkbox
[010] The resident has NO  hearing deficits
[020] The resident has  NO vision deficits
[030] The resident has NO  speech deficits
[040] The resident has NO comprehension deficits
[050] The resident has vision deficits
[060] The resident has hearing deficits
[070] The resident has speech deficits
[080] The resident has comprehension deficits
>obs=Communication and Language
^^
#10
\$ADR100
?Descibe Communication Deficits
~Descibe in a sentence
@text
>obs=Communication and Language
^^
#11
\$ADR110
?Communication Interventions
~Descibe in a sentence
@text
>intv=Communication and Language
^^
#12
\$ADR120
?Mobility and Transfers
@radio
(020) The resident requires NO assistance  with mobility and transfers
(030) The resident requires assistance with mobility and transfers
>obs=Movement and Transfers
^^
#13
\$ADR130
?Falls
~If yes complete Falls Risk assessment
(020) The resident has NO History of Falls
(030) The resident has a history of Falls
>obs=Movement and Transfers
EOT;
        $t = new TemplateTranslator($content);
        print_r('test TemplateTranslator');
        var_dump($t->Translate());
//        \GuzzleHttp\json_decode($t->Translate());
        $this->assertTrue(true);
    }
}
