<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Domains\UserFacility;
use App\Domains\User;

class Facility extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'Facility';

//    private
//        $FacilityID  // a value assigned
//        , $NameLong     // facility full name
//        , $NameShort    // facility short name
//        , $Address1
//        , $Address2
//        , $Town
//        , $City
//        , $State
//        , $County
//        , $PostCode
//        , $Country;

    // this will return all RM of this facility
    public function getResidentialManagersAttribute(){
        $managers=[];
        $users = UserFacility::where('facilityId', $this->FacilityID)->get();
//        var_dump($users);
        foreach($users as $uf) {
            $user = HubUser::where('SID', $uf->userId)->get()->first();
//            var_dump($rm);
            if($user != null ) {
                $roles = $user->Roles;
                foreach($roles as $role) {
                    if($role->roleName == env('RESIDENTIAL_MANAGER'))
                        array_push($managers, $user);
                }
            }
        }
        return $managers;
    }

    public function getGeneralManagersAttribute(){
        $managers=[];
        $users = UserFacility::where('facilityId', $this->FacilityID)->get();
//        var_dump($users);
        foreach($users as $uf) {
            $user = HubUser::where('SID', $uf->userId)->get()->first();
//            var_dump($rm);
            if($user != null ) {
                $roles = $user->Roles;
                foreach($roles as $role) {
                    if($role->roleName == env('GM_RESIDENTIAL_MANAGER'))
                        array_push($managers, $user);
                }
            }
        }
        return $managers;
    }


    public function getCSOsAttribute(){
        $managers=[];
        $users = UserFacility::where('facilityId', $this->FacilityID)->get();
//        var_dump($users);
        foreach($users as $uf) {
            $user = HubUser::where('SID', $uf->userId)->get()->first();
//            var_dump($rm);
            if($user != null ) {
                $roles = $user->Roles;
                foreach($roles as $role) {
                    if($role->roleName == env('CUSTOMER_SERVICE_OFFICER'))
                        array_push($managers, $user);
                }
            }
        }
        return $managers;
    }

    public function getFacilityNameAttribute(){
        return $this->NameLong;
    }

    public function getFacilityObjectAttribute(){
        return (object)[
            'FacilityId' => $this->_id,
            'FacilityName' => $this->FacilityName
        ]   ;
    }

    public function GetObjectAttribute(){
        return (object)[
            'FacilityId' => $this->_id,
            'FacilityName' => $this->FacilityName
        ]   ;
    }
}
