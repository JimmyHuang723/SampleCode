<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use App\Domains\HubUser;
use App\Domains\UserRole;

class Role extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Role';

    public function getPermissionsAttribute(){ 
        $permissions = Permission::where('roleId', $this->_id)->get();
        $ret = [];
        foreach($permissions as $p){
            array_push($ret, $p->menuName);
        }
        // dd($ret);
        return $ret;
    }

    public function getUsersAttribute(){

//        var_dump('getUsersAttribute');
//        var_dump($this->_id);
        $users = [];
        $userRoles = UserRole::where('roleId', $this->_id)->get();
//        Debugbar::debug('sizeof='.sizeof($userRoles));

        foreach ($userRoles as $ur){
//            Debugbar::debug($ur->userId);
            $usersOfRole = HubUser::where('SID', $ur->userId)->get()->first();
            if(!is_object($usersOfRole)) continue;
//            Debugbar::debug('sizeof='.sizeof($usersOfRole));
//            foreach ($usersOfRole as $u)
            if($usersOfRole->SStatus == 'A')
                array_push($users, $usersOfRole);
        }
        return $users;
    }

    public function GetUsers($roleId){

        $users = [];
        $userRoles = UserRole::where('roleId', $roleId)->get();

        foreach ($userRoles as $ur){
//            Debugbar::debug($ur->userId);
            $usersOfRole = HubUser::where('SID', $ur->userId)->get()->first();
            if(!is_object($usersOfRole)) continue;
//            Debugbar::debug('sizeof='.sizeof($usersOfRole));
//            foreach ($usersOfRole as $u)
            if($usersOfRole->SStatus == 'A')
                array_push($users, $usersOfRole);
        }
        return $users;
    }

    public static function GetMaintenanceSupervisors(){
        $role = Role::where('roleName', env('MAINTENANCE_SUPERVISOR'))->get()->first();
        return $role->Users;
    }

    public static function GetHRManagers(){
        $role = Role::where('roleName', env('HR_MANAGER'))->get()->first();
        return $role->Users;
    }

    public static function GetAlliedHealthManagers(){
        $role = Role::where('roleName', env('ALLIED_HEALTH_MANAGER'))->get()->first();
        return $role->Users;
    }

}
