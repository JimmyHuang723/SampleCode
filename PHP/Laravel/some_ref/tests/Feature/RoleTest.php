<?php

namespace Tests\Feature;

use App\Utils\HubHelper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Domains\Role;
use App\Domains\HubUser;

class RoleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUsers()
    {
//        var_dump('testUser');
        $role = new Role();
        $role->_id = '5704952c97f48f0000000565';
        $users = $role->Users;
//        var_dump('sizeof $users = '.sizeof($users));
//        foreach($users as $u);
//            var_dump($u->SID);
        $this->assertTrue(sizeof($users) == 37);
    }

    public function testGetPayrollCoordinator(){
        $users = HubHelper::GetPayrollCoordinators();
//        var_dump($users);
        $seen=false;
        foreach($users as $u){
//            print_r($u->SSurname=="BENNETT");
            $seen=($u->SSurname=="BENNETT");
            if($seen) break;
        }
        $this->assertTrue($seen);
    }

    public function testMaintenanceSupervisor(){
        $users = Role::GetMaintenanceSupervisors();
//        var_dump($users);
        $this->assertTrue(sizeof($users)>0);
    }

    public function testIsGardener(){
        $user = HubUser::where('SID', '2a6e5a00-74fd-41f4-abd1-005cb7ee3710')->get()->first();
        $this->assertTrue($user->IsGardener);
    }
}
