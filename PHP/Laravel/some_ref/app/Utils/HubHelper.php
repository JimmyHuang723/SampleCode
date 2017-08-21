<?php

namespace App\Utils;
use Debugbar;
use GuzzleHttp\Psr7\Request;
use App\Domains\Role;
use App\Domains\UserRole;
use App\Domains\HubUser;
use Illuminate\Support\Facades\Log;


class HubHelper {

    public static function PrepareRecipientsForTopicByUserId($users){
        Debugbar::debug('PrepareRecipientsForTopicUsingUserId');
        $recipients=[];
        foreach($users as $u) {
            $m = HubUser::find($u);
            Debugbar::debug($m->UserName);
            // exclude sender from himself or herself
            $user = (object)array(
                'SID' => $m->SID,
                'UserName' => $m->UserName,
                'Name' => $m->Fullname
            );
            array_push($recipients, $user);
        }
        return $recipients;
    }

    public static function PrepareRecipientsForMessageByUserId($users){
        Debugbar::debug('PrepareRecipientsForMessageByUserId');
        $recipients=[];
        foreach($users as $u) {
            $m = HubUser::find($u);
            Debugbar::debug($m->UserName);
            // exclude sender from himself or herself
            $user = (object)array(
                'ToUser' => (object) array(
                    'SID' => $m->SID,
                    'UserName' => $m->UserName,
                    'Name' => $m->Fullname
                ),
                'IsStar' => 0,
                'State' => 0,
                'Color' => null
            );
            array_push($recipients, $user);
        }
        return $recipients;
    }

    public static function PrepareRecipientsForTopic($users, $sender){
        Debugbar::debug('GetRecipientsForTopic');
        $recipients=[];
        foreach($users as $m) {
            Debugbar::debug($m->UserName);
            // exclude sender from himself or herself
            if($m->SID == $sender->SID) continue;
            $user = (object)array(
                'SID' => $m->SID,
                'UserName' => $m->UserName,
                'Name' => $m->Fullname
            );
            array_push($recipients, $user);
        }
        return $recipients;
    }

    public static function AppendRecipientsForTopic($users, $sender, $recipients){
        Debugbar::debug('AppendRecipientsForTopic');
        foreach($users as $m) {
            Debugbar::debug($m->UserName);
            // exclude sender from himself or herself
            if($m->SID == $sender->SID) continue;
            $user = (object)array(
                'SID' => $m->SID,
                'UserName' => $m->UserName,
                'Name' => $m->Fullname
            );
            array_push($recipients, $user);
        }
        return $recipients;
    }

    public static function GetSenderAsRecipientForTopic($sender){
        Debugbar::debug('GetSenderAsRecipientForTopic');
        Debugbar::debug($sender->UserName);
        $recipients=[];
        $m = $sender;
        // exclude sender from himself or herself
        $user = (object)array(
            'SID' => $m->SID,
            'UserName' => $m->UserName,
            'Name' => $m->Fullname
        );
        array_push($recipients, $user);
        return $recipients;
    }

    public static function GetSenderAsRecipientForMessage($sender){
        Debugbar::debug('GetSenderAsRecipientForMessage');
        Debugbar::debug($sender->UserName);
        $recipients=[];
        $m = $sender;
        // exclude sender from himself or herself
        $user = (object)array(
            'ToUser' => (object) array(
                'SID' => $m->SID,
                'UserName' => $m->UserName,
                'Name' => $m->Fullname
            ),
            'IsStar' => 0,
            'State' => 0,
            'Color' => null
        );
        array_push($recipients, $user);
        return $recipients;
    }


    public static function GetRecipientsForMessage($users, $sender){
        Debugbar::debug('GetRecipientsForMessage');
        $recipients=[];
        foreach($users as $m) {
            Debugbar::debug($m->UserName);
            // exclude sender from himself or herself
            if($m->SID == $sender->SID) continue;
            $user = (object)array(
                'ToUser' => (object) array(
                    'SID' => $m->SID,
                    'UserName' => $m->UserName,
                    'Name' => $m->Fullname
                ),
                'IsStar' => 0,
                'State' => 0,
                'Color' => null
            );
            array_push($recipients, $user);
        }
        return $recipients;
    }

    // append $users to $recipients
    // skip $sender
    public static function AppendRecipientsForMessage($users, $sender, $recipients){
        Debugbar::debug('AppendRecipientsForMessage');
        foreach($users as $m) {
            Debugbar::debug($m->UserName);
            // exclude sender from himself or herself
            if($m->SID == $sender->SID) continue;
            $user = (object)array(
                'ToUser' => (object) array(
                    'SID' => $m->SID,
                    'UserName' => $m->UserName,
                    'Name' => $m->Fullname
                ),
                'IsStar' => 0,
                'State' => 0,
                'Color' => null
            );
            array_push($recipients, $user);
        }
        return $recipients;
    }

    public static function CallbackToHub($recipients, $title, $topicId){
        Log::debug('CallbackToHub ' . $title);
            $receivers = [];
            if(is_array($recipients)) {
                foreach ($recipients as $r) {
                    array_push($receivers, $r->SID);
                    Log::debug('CallbackToHub ' . $r->UserName);
                }
            } else {
                array_push($receivers, $recipients->SID);
                Log::debug('CallbackToHub ' . $recipients->UserName);
            }
//            Debugbar::debug($receivers);

            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', env('HUB_URL'), [
                'headers' => ['Authorization' => 'Basic d41d8cd98f00b204e9800998ecf8427e'],
                'json' => (object)[
                    'ReceiveUsers' => $receivers,
                    'Subject' => $title ,
                    "SendUser" => "myHub",
                    "TopicId" => $topicId
                ]
            ]);
            Log::debug('status code = '.$res->getStatusCode());
    }

    public static function GetPayrollCoordinators(){
//        print_r('GetPayrollCoordinator');
        $result = [];
        $role = Role::where('roleName', env('PAYROLL_COORDINATOR'))->get()->first();
        if($role == null) return $result;
//        var_dump( $role->_id);
        $userRoles = UserRole::where('roleId', $role->_id)->get();

        foreach ($userRoles as $ur){
//            print_r($ur->userId);

            $user = HubUser::where("SID", $ur->userId)->get()->first();
            if($user != null)
                array_push($result, $user);
        }
        return $result;
    }

    public static function GetHOPayrollCoordinators(){
//        print_r('GetPayrollCoordinator');
        $result = [];
        $role = Role::where('roleName', env('HO_PAYROLL_COORDINATOR'))->get()->first();
//        var_dump( $role->_id);
        if($role == null) return $result;

        $userRoles = UserRole::where('roleId', $role->_id)->get();

        foreach ($userRoles as $ur){
//            print_r($ur->userId);

            $user = HubUser::where("SID", $ur->userId)->get()->first();
            if($user != null)
                array_push($result, $user);
        }
        return $result;
    }

}