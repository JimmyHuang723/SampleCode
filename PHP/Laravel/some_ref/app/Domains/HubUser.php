<?php

namespace App\Domains;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use App\Domains\Topic;
use App\Domains\Facility;
use App\Domains\Role;
use Illuminate\Support\Facades\Log;

class HubUser extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'User';

//    private $SID,        // GUID assigned to each staff/user
//        $SNumber,
//        $UserName,
//        $SGivenNames,
//        $SSurname,
//        $SHomePhone,
//        $SMobile,
//        $SEmail,
//        $SDOB,
//        $SGender,   // F = Female, M = Male
//        $SStatus;   // A = Active, T = terminate

    public function getFacilitiesAttribute(){
        $facilities=[];
        $userFacilities = UserFacility::where('userId', $this->SID)->get();
        foreach($userFacilities as $uf) {
            $fac = Facility::where('FacilityID', $uf->facilityId)->get();
            if(sizeof($fac) > 0 )
                array_push($facilities, $fac[0]);
        }
        return $facilities;
    }

    public function getRolesAttribute(){

//        Debugbar::info($this->SID);
        $roles = [];
        $userRoles = UserRole::where('userId', $this->SID)->get();
        foreach($userRoles as $uf) {
            $role = Role::find($uf->roleId);
            if($role == null) continue;
//            Debugbar::info($role);
            array_push($roles, $role);
        }
        return $roles;
    }

    public function getResidentailManagerAttribute(){
        Log::info('SID: '. $this->SID);
        if($this->SNumber == null){

            $topic = new Topic();
            $topic->CallHelpdesk('Missing SNumber for ' . $this->UserName, $this->SID);

        } else {

            $userFacilities = UserFacility::where('userId', $this->SID)->get();
            foreach($userFacilities as $uf) {

            }

        }

    }

    public function getDefaultFacilityAttribute(){
        if($this->SNumber == null) return null;
        $p1 = substr($this->SNumber, 0, 1);
        $p2 = substr($this->SNumber, 0, 2);
        $p3 = substr($this->SNumber, 0, 3);

//        var_dump('snumber = '.$this->SNumber . ', p1=' . $p1 . ', p2=' . $p2 . ', p3=' . $p3);

        if($p1 == 'B') return Facility::where('FacilityID', 1134)->get()->first();
        else if($p3 == 'SHC') return Facility::where('FacilityID', 1141)->get()->first();
        else if($p2 == 'EK') return Facility::where('FacilityID', 1136)->get()->first();
        else if($p2 == 'GC') return Facility::where('FacilityID', 1137)->get()->first();
        else if($p2 == 'HO') return Facility::where('FacilityID', 1135)->get()->first();
        else if($p1 == 'H') return Facility::where('FacilityID', 1117)->get()->first();
        else if($p2 == 'OK') return Facility::where('FacilityID', 1140)->get()->first();
        else if($p2 == 'RH') return Facility::where('FacilityID', 1138)->get()->first();
        else if($p1 == 'G') return Facility::where('FacilityID', 1133)->get()->first();
        else if($p1 == 'M') return Facility::where('FacilityID', 1066)->get()->first();
        else if($p2 == 'WG') return Facility::where('FacilityID', 1139)->get()->first();
    }

    public function GetResidentialManagers($facilityId){
        $facility = Facility::where('FacilityID', $facilityId)->get()->first();
        if(!is_object($facility)) return null;

        return $facility->ResidentialManagers;
    }

    public function GetGeneralManagers($facilityId){
        $facility = Facility::where('FacilityID', $facilityId)->get()->first();
        if(!is_object($facility)) return null;

        return $facility->GeneralManagers;
    }

    public function GetCSOs($facilityId){
        $facility = Facility::where('FacilityID', $facilityId)->get()->first();
        if(!is_object($facility)) return null;

        return $facility->CSOs;
    }


    public function getResidentialManagersAttribute()
    {
        $defaultFacility = $this->DefaultFacility;
        if($defaultFacility == null) return null;

        return $defaultFacility->ResidentialManagers;

    }

    public function getGeneralManagersAttribute()
    {
        $defaultFacility = $this->DefaultFacility;
        if($defaultFacility == null) return null;

        return $defaultFacility->GeneralManagers;

    }

    public function getCSOsAttribute()
    {
        $defaultFacility = $this->DefaultFacility;
        if($defaultFacility == null) return null;

        return $defaultFacility->CSOs;

    }

    public function getSupervisors($roleId)
    {
        $role = Role::find($roleId)->get()->first();
        if($role->parentRoleId == null || $role->parentRoleId == '') return null;
        $parentRole = Role::find($role->parentRoleId)->get()->first();
        return $parentRole->Users;
    }

    // return an array of roleId of all supervisors
    public function getSupervisorsAttribute()
    {
        Debugbar::debug('getSupervisorsAttribute');
        Log::debug('getSupervisorsAttribute');
        $supervisors = [];
        $hays = [];
        // what if this person has multiple role?
        $roles = $this->Roles;
        foreach($roles as $role){
            if($role->parentRoleId == null || $role->parentRoleId == '' || $role->roleName == 'Everyone') continue;
            Debugbar::debug('find users of parent role of '.$role->roleName);
            Log::debug('find users of parent role of '.$role->roleName);
            if(property_exists($role, 'parentRoleName')) {
                Debugbar::debug('parent role = ' . $role->parentRoleName);
                Log::debug('parent role = ' . $role->parentRoleName);
            }
            $users = $role->GetUsers($role->parentRoleId);
            foreach($users as $u) {
                if($u->SID == $this->SID) continue;
                if(!in_array($u->SID, $hays)) {
                    array_push($hays, $u->SID);
                    array_push($supervisors, $u);
                    Debugbar::debug('user found '.$u->Fullname);
                    Log::debug('user found '.$u->Fullname);
                }
            }
//            var_dump('sizeof $supervisors = '.sizeof($supervisors));
        }

        return $supervisors;
    }

    public function getFullnameAttribute(){
        if($this->SGivenNames == null || $this->SSurname == null)
            return '';
        else
            return strtoupper($this->SSurname) . ', '.$this->SGivenNames;
    }

    public function getFullnameAndUserNameAttribute(){
        if($this->SGivenNames == null || $this->SSurname == null)
            return '';
        else
            return strtoupper($this->SSurname) . ', '.$this->SGivenNames . ' ('.$this->UserName.')';
    }

    public function getFullnameAndSNumberAttribute(){
        if($this->SGivenNames == null || $this->SSurname == null)
            return '';
        else {
            if($this->SNumber == null || $this->SNumber == '')
                return strtoupper($this->SSurname) . ', ' . $this->SGivenNames ;
            else
                return strtoupper($this->SSurname) . ', ' . $this->SGivenNames . ' (' . $this->SNumber . ')';
        }
    }

    public function getFullnameWithFacilityAttribute(){
        if($this->SGivenNames == null || $this->SSurname == null)
            return '';
        else {
            if($this->DefaultFacility == null)
                return strtoupper($this->SSurname) . ', '.$this->SGivenNames;
            else
                return strtoupper($this->SSurname) . ', '.$this->SGivenNames . ' ('. $this->DefaultFacility->NameLong .')';
        }
    }

    public function GetFullNameWithFacility($payload){
        if(property_exists($payload, 'FacilityName'))
            return strtoupper($this->SSurname) . ', '.$this->SGivenNames . ' ('. $payload->FacilityName .')';
        else
            return strtoupper($this->SSurname) . ', '.$this->SGivenNames ;
    }

    public function getNominationsAttribute(){
        return Nominate::where('User.SID', $this->SID)->get();
    }

    public function getIsResentialManagerAttribute(){
        $ret=false;
        $roles = $this->Roles;
        foreach($roles as $role){
            if($role->roleName == env('RESIDENTIAL_MANAGER')){
                $ret=true;
                break;
            }
        }
        return $ret;
    }

    public function getIsGardenerAttribute(){
        $ret=false;
        $roles = $this->Roles;
        if(sizeof($roles) > 0 ) {
            foreach ($roles as $role) {
                if ($role->roleName == env('GARDENER')) {
                    $ret = true;
                    break;
                }
            }
        }
        return $ret;
    }

    public function getIsAlliedHealthTeamAttribute(){
        $ret=false;
        $roles = $this->Roles;
        if(sizeof($roles) > 0 ) {
            foreach ($roles as $role) {
                if ($role->roleName == env('ALLIED_HEALTH_TEAM')) {
                    $ret = true;
                    break;
                }
            }
        }
        return $ret;
    }

    public function getIsActiveAttribute(){
//        Debugbar::debug($this);
        $ret = false;
        if($this->SStatus == 'A')
            $ret=true;
        if($this->HubStatus)
            $ret=false;
        return $ret;
    }

    public function getIsHeadOfficeStaffAttribute(){
        $ret=false;

        $roles = $this->Roles;
        foreach ($roles as $role){
            if(in_array($role->parentRoleName, array(env('CHIEF_EXECUTIVE_OFFICER') ,
                env('FINANCIAL_CONTROLLER') ,
                env('GM_HUMAN_RESOURCES') ,
                env('GM_FUNDING_SERVICES') ,
                env('GM_RESIDENTIAL_MANAGER') ,
                env('PAYROLL_COORDINATOR') ,
                env('HR_MANAGER') ,
                env('ALLIED_HEALTH_MANAGER') ,
                env('ACCOUNT_ADMINISTRATOR') ,
                env('ACCOUNT_RECEIVABLE') ||
                env('ACCOUNTANT') ||
                env('GM_CLIENT_SERVICE') ||
                env('GM_IT_COMMS') ||
                env('GM_PROJECT_INNOVATION') ||
                env('GM_PROPERTY_SERVICE') ||
                env('OHS_COORDINATOR') ||
                env('OFFICE_MANAGER') ||
                env('TRAINING_MANAGER')
            )))
            {
                $ret=true;
                break;
            }
        }

        return $ret;
    }

    public function getUserObjectAttribute(){
        return (object)[
            'UserId' => $this->_id,
            'SID' => $this->SID,
            'FullName' => $this->Fullname
        ]   ;
    }

    public function GetObjectAttribute(){
        return (object)[
            'UserId' => $this->_id,
            'SID' => $this->SID,
            'FullName' => $this->Fullname
        ]   ;
    }

    public function getFirstNameAttribute(){
        return $this->SGivenNames;
    }

    public function getLastNameAttribute(){
        return $this->SSurname;
    }
}
