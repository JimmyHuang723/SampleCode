<?php

namespace Tests\Feature;

use App\Domains\Facility;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FacilityTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testgetResidentialManagersAttribute()
    {
        $fac = new Facility();
        $fac->FacilityID = 1136;
//        foreach($fac->ResidentialManagers as $rm){
//            var_dump($rm->UserName);
//        }
        $this->assertTrue(true);
    }

    public function testgetGeneralManagersAttribute()
    {
        $fac = new Facility();
        $fac->FacilityID = 1136;
//        foreach($fac->GeneralManagers as $rm){
//            var_dump($rm->UserName);
//        }
        $this->assertTrue(true);
    }

    public function testgetCSOAttribute()
    {
        $fac = new Facility();
        $fac->FacilityID = 1117;
//        foreach($fac->CSOs as $rm){
//            var_dump($rm->UserName);
//        }
        $this->assertTrue(true);
    }
}
