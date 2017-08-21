<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use App\Domains\HubUser;
use App\Domains\Permission;

class Permission extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Permission';
    public $guarded = ["_id"];

    /*
       View : if(App\Domains\Permission::check()->enableEdit)  (enableDelete/enableRead)
              OR 
              if(App\Domains\Permission::check("menu name")->enableEdit)
    */

    public static function getDefault(){
        $permission = new Permission();
        $permission->enableRead = true; // default
        $permission->enableEdit = true; // default
        $permission->enableDelete =  true; // default
        return $permission;
    }

    public static function check($menuName = null)
    {
        $menuName = ','.$menuName;
        $access_permission = session('access_permission');
        $edit_permission = session('edit_permission');
        $delete_permission = session('delete_permission');
        $accessPermitted = stripos($access_permission, $menuName);
        $editPermitted = stripos($edit_permission, $menuName);
        $deletePermitted = stripos($delete_permission, $menuName);
        $permission = [
            'enableRead' => ($accessPermitted > 0),
            'enableEdit'=> ($editPermitted > 0),
            'enableDelete'=> ($deletePermitted > 0)
        ];
        // dd($access_permission);
        return (object)$permission;
    }

    public static function SetupPermissionBySID($SID){
        $hubUser = HubUser::where('SID', $SID)->get()->first();
        if($hubUser != null)
            return Permission::SetupPermission($hubUser);
        else{
            $access_permission = [];
            $edit_permission = [];
            $delete_permission = [];
            
            return [
                'access_permission' => ','.implode(',', $access_permission),
                'edit_permission' => ','.implode(',', $edit_permission),
                'delete_permission' => ','.implode(',', $delete_permission)];
        }
    }

    public static function SetupPermission($hubUser){
        $roles = $hubUser->Roles;
        $access_permission = [];
        $edit_permission = [];
        $delete_permission = [];
        foreach($roles as $role){
 
            $permissions = Permission::where('roleId', $role->_id)->get();
            foreach($permissions as $p){
                array_push($access_permission, $p->menuName);
                if($p->enableEdit) array_push($edit_permission, $p->menuName);
                if($p->enableDelete) array_push($delete_permission, $p->menuName);
            }
        }
        return [
            'access_permission' => '_,'.implode(',', $access_permission),
            'edit_permission' => '_,'.implode(',', $edit_permission),
            'delete_permission' => '_,'.implode(',', $delete_permission)];
    }
}