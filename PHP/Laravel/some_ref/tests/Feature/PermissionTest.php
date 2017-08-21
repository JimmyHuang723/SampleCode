<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Domains\Permission;

class PermissionTest extends TestCase
{
    public function testSetupPermissionBySID(){
        $result = Permission::SetupPermissionBySID('');
        // var_dump($result);

        $this->assertTrue($result['access_permission']==',');
        $this->assertTrue($result['edit_permission']==',');
        $this->assertTrue($result['delete_permission']==',');


        $sid = '8f1df96e-34e4-4e1f-916c-1848a8e78591';
        $result = Permission::SetupPermissionBySID($sid);
        var_dump($result);

        $this->assertTrue($result['access_permission']==',MyCare,Residents,Spotlight,Dashboard,Care,Care Plan');
        $this->assertTrue($result['edit_permission']==',Residents');
        $this->assertTrue($result['delete_permission']==',Residents');
    }
}