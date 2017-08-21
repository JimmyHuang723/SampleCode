<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Domains\HubUser;
use Debugbar;

class HubuserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserFind()
    {
        $sid = '2f372f56-1572-4715-95d5-7cac491397e1';
        $username = 'mahona';

        $user = HubUser::where("SID", $sid)->first();
        //var_dump($user);
        $this->assertTrue($user->UserName == $username);

        for($i = 0; $i < sizeof($user->Facilities); $i++){
//            var_dump($user->Facilities[$i]->NameLong);
            $facilityName = $user->Facilities[$i]->NameLong;
            $this->assertTrue(
                ($facilityName == 'Elly Kay' ||
                $facilityName == 'Grossard Court' ||
                $facilityName == 'Head Office' ||
                $facilityName == 'Hilltop'));
        }

        for($i = 0; $i < sizeof($user->Roles); $i++){
//            var_dump($user->Roles[$i]->roleName);
            $this->assertTrue(
                ($user->Roles[$i]->roleName == 'Doctor' || $user->Roles[$i]->roleName == 'Everyone'));
        }
    }

    public function testUserWithoutSNumber(){
        $sid = '2f372f56-1572-4715-95d5-7cac491397e1';
        $user = HubUser::where("SID", $sid)->first();
        $this->assertNull($user->SNumber);
    }

    public function testUserLineManager(){

        $sid = '7fd5acfc-5587-4b67-863a-fa7b28760e5c';
        $username = '5064';

        $user = HubUser::where("SID", $sid)->first();
        //var_dump($user->SNumber);

        $this->assertTrue($user->UserName == $username);
    }

    public function testDefaultFacility(){

//        var_dump('testDefaultFacility');
        $user = new HubUser();
        $user->SNumber = 'B1234';
//        $fac = $user->DefaultFacility;
//        var_dump($fac->NameLong);
        $this->assertTrue($user->DefaultFacility->NameLong == 'Brighton');

        $user->SNumber = 'SHC34';
        $this->assertTrue($user->DefaultFacility->NameLong == 'Caulfield');

        $user->SNumber = 'EK4256';
        $this->assertTrue($user->DefaultFacility->NameLong == 'Elly Kay');

        $user->SNumber = 'GC4692';
        $this->assertTrue($user->DefaultFacility->NameLong == 'Grossard Court');

        $user->SNumber = 'HO54';
        $this->assertTrue($user->DefaultFacility->NameLong == 'Head Office');

        $user->SNumber = 'H4989';
        $this->assertTrue($user->DefaultFacility->NameLong == 'Hilltop');

        $user->SNumber = 'RH4828';
        $this->assertTrue($user->DefaultFacility->NameLong == 'Ruckers Hill');

        $user->SNumber = 'G4256';
        $this->assertTrue($user->DefaultFacility->NameLong == 'The Gables');

        $user->SNumber = 'M1126';
        $this->assertTrue($user->DefaultFacility->NameLong == 'The Mews');

        $user->SNumber = 'WG4445';
        $this->assertTrue($user->DefaultFacility->NameLong == 'Westgarth');
    }


    public function testRMDefaultFacility(){

        $user = new HubUser();
        $user->SNumber = 'WG4445';
        $rm = $user->ResidentialManagers;

        $this->assertTrue(sizeof($rm) > 0);

        foreach($rm as $m){
            $this->assertTrue($m->UserName == 'broadfootj' ||
            $m->UserName == 'castillo-pinoj');
        }

//        $this->assertTrue(true);
    }

//    public function testSupervisor(){
//        $sid = '8f1df96e-34e4-4e1f-916c-1848a8e78591';
//        $user = HubUser::where("SID", $sid)->first();
////        var_dump('Supervisors sizeof = '.sizeof($user->Supervisors));
//        $supervisors = $user->Supervisors;
//        foreach ($supervisors as $s) {
////            var_dump(gettype($s));
////            if(is_object($s) && property_exists($s, 'Fullname'))
////                var_dump($s->Fullname . $s->SID . $s->SStatus);
//            //$this->assertTrue($s == '57ff85989318a809145aebb7');
//
//            $this->assertTrue($s->Fullname == 'Fabio MAYA' || $s->Fullname == 'Ernest Steven MEDINA');
//        }
//
//    }

    public function testHOStaff()
    {
        $user = HubUser::where('UserName', 'furmanl')->get()->first();
        $this->assertFalse($user->IsHeadOfficeStaff);

        $user = HubUser::where('UserName', 'bennettm1')->get()->first();
        $this->assertTrue($user->IsHeadOfficeStaff);
    }

}

