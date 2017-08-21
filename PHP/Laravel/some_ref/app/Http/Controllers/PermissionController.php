<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


use App\Domains\Menu;
use App\Domains\Role;
use App\Domains\Permission;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing()
    {                
        $roles = Role::orderBy('roleName')->get();

        return view('permission.listing', ['roles' => $roles]);
    }

    public function edit(Request $request, $roleId)
    {
        $role = Role::find($roleId);

        // find mainMenus : menu with no parents
        $mainMenus = Menu::where("parentId", "")->get();

        // find subMenus for mainMenus
        foreach ($mainMenus as $mainMenu) {
            $subMenus = Menu::where("parentId", $mainMenu->_id)->get();
            $mainMenu['subMenus'] = $subMenus;
        }
        
        // find permission
        foreach ($mainMenus as $mainMenu){
            //find permission : with roleId + MenuId
            $permission = Permission::where("roleId", $roleId)->where("menuId",$mainMenu->_id)->get()->first();
            if(!empty($permission)){
                $mainMenu['permission'] = $permission;
            }
            
            // find permission for subMenus : with roleId + MenuId     
            $subMenus = $mainMenu['subMenus'];
            foreach ($subMenus as $subMenu) {
                $permission = Permission::where("roleId", $roleId)->where("menuId",$subMenu->_id)->get()->first();
                if(!empty($permission)){
                    $subMenu['permission'] = $permission;
                }
            }
        }

        return view('permission.edit', ['role' => $role, 
                                        'mainMenus' => $mainMenus
                                       ]);
    }

    public function update(Request $request){
        $roleId = Input::get('roleId');

        // find mainMenus : menu with no parents
        $mainMenus = Menu::where("parentId", "")->get();

        // find subMenus for mainMenus
        foreach ($mainMenus as $mainMenu) {
            $subMenus = Menu::where("parentId", $mainMenu->_id)->get();
            $mainMenu['subMenus'] = $subMenus;
        }

        // update permission
        foreach ($mainMenus as $mainMenu){
            $menuId = $mainMenu->_id;
            $menuName = $mainMenu->menuName;
            $permission = Permission::where("roleId", $roleId)->where("menuId",$menuId)->get()->first();
            if(!empty($permission)){
                $permission->update([ 
                                      'enableRead' => Input::has('read_'.$menuId),
                                      'enableEdit' => Input::has('edit_'.$menuId),
                                      'enableDelete' => Input::has('delete_'.$menuId)
                                    ]);
            }else{
                $permission = new Permission();
                $permission->roleId = $roleId;
                $permission->menuId = $menuId;
                $permission->menuName = $menuName;
                $permission->enableRead = Input::has('read_'.$menuId);
                $permission->enableEdit = Input::has('edit_'.$menuId);
                $permission->enableDelete =  Input::has('delete_'.$menuId);
                $permission->save();
            }
            
            $subMenus = $mainMenu['subMenus'];
            foreach ($subMenus as $subMenu) {
                $menuId = $subMenu->_id;
                $menuName = $subMenu->menuName;
                $permission = Permission::where("roleId", $roleId)->where("menuId",$menuId)->get()->first();
                if(!empty($permission)){
                    $permission->update([ 
                                          'enableRead' => Input::has('read_'.$menuId),
                                          'enableEdit' => Input::has('edit_'.$menuId),
                                          'enableDelete' => Input::has('delete_'.$menuId)
                                        ]);
                }else{
                    $permission = new Permission();
                    $permission->roleId = $roleId;
                    $permission->menuId = $menuId;
                    $permission->menuName = $menuName;
                    $permission->enableRead = Input::has('read_'.$menuId);
                    $permission->enableEdit = Input::has('edit_'.$menuId);
                    $permission->enableDelete =  Input::has('delete_'.$menuId);
                    $permission->save();
                }
            }
        }

        return redirect('/permission/listing');
    }
}