<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Domains\TemplateTranslator;
use App\Domains\Resident;
use Illuminate\Support\Facades\Log;

class ResidentTest extends TestCase
{

    public function testGetNextPatientID(){
        $residentId = Resident::GetNextPatientID();
        var_dump($residentId);
        $this->assertTrue($residentId == 6091);

    }
}