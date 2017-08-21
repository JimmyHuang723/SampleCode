<?php

namespace App\Processes;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Debugbar;
use Illuminate\Http\Request;
use League\Flysystem\Exception;
use App\Domains\Topic;
use App\Domains\HubUser;
use App\Domains\Role;
use App\Domains\Message;
use App\Utils\HubHelper;
use Illuminate\Support\Facades\Log;

const LEAVE_DECLINED = 0;
const LEAVE_DRAFT = 1;
const LEAVE_APPROVED = 2;
const LEAVE_PAYROLLED = 3;
const LEAVE_CANCELED = 4;
const LEAVE_APPLIED = 5;
const LEAVE_RESPONSE = 6;
const LEAVE_FORWARD = 7;
const TOPIC_TYPE_LEAVE = 8;

class LeaveApplication extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'wfLeaveApplication';


    function __construct($handle, $message)
    {
        $this->ReceiptHandle = $handle;
        $this->Message = $message;

        Debugbar::debug($message);

        $this->State = $this->Message->Payload->State;
        $this->Payload = $this->Message->Payload;
    }


    public function run(){
        Debugbar::info("State = ". $this->State);
        Log::debug('state='.$this->State);

        $ret=true;
        try {

            $sender = HubUser::where('SID', $this->Payload->Created->UpdateUser)->get()->first();
            Log::debug('sender is '.$sender->Fullname);

            $topic = new Topic();
            $topic->LeaveId = $this->Payload->_id;
            $topic->Type = TOPIC_TYPE_LEAVE;

            if ($this->State == LEAVE_DECLINED) {

                if($this->Message->Step == 'Comment'){
                    $this->OnResponse($topic, $sender);
                } else
                    $this->OnDecline($topic, $sender);

            } else if ($this->State == LEAVE_DRAFT) {

                $this->OnDraft();

            } else if ($this->State == LEAVE_APPROVED) {

                $this->OnApprove($topic, $sender);

            } else if ($this->State == LEAVE_PAYROLLED) {

                $this->OnPayroll($topic, $sender);

            } else if ($this->State == LEAVE_CANCELED) {

                $this->OnCancel($topic, $sender);

            } else if ($this->State == LEAVE_APPLIED) {

                if($this->Message->Step == 'Forward'){
                    $this->onForward($topic, $sender);
                } else
                    $this->onApply($topic, $sender);

            } else if ($this->State == LEAVE_RESPONSE) {

                $this->onResponse();
            }
            $this->save();
            Log::debug('Topic saved');

        }catch(Exception $ex){
            $ret = true;
            $this->OnException($ex);
        }
        return $ret;

    }

    public function OnDecline($topic, $sender){
        Log::debug('OnDecline');
        // notify the sender

        $recipients = HubHelper::GetSenderAsRecipientForTopic($sender);
        $recipientsMessage = HubHelper::GetSenderAsRecipientForMessage($sender);
        $approveHistories = $this->Payload->ApproveHistorys;
        if(sizeof($approveHistories) == 0) throw new Exception('No approve history for approved leave');
        $approveHistory = $approveHistories[sizeof($approveHistories)-1];
        $approver = HubUser::where('SID', $approveHistory->Created->UpdateUser)->get()->first();

        $title = $approver->Fullname .' has declined your leave application';
        $content = '';
        $topic->LeaveId = "0";

        if(sizeof($recipients) > 0) {
            $topic->Users = $recipients;
            $topic->Title = $title;
            $topic->Content = $content;

            $topic->save();

            $message = new Message();
            $message->Subject = $title;
            $message->Content = $content;
            $message->To = $recipientsMessage;
            $message->TId = $topic->_id;

            $message->save();

            HubHelper::CallbackToHub($recipients, $title, $topic->_id);

            Log::debug($title);
        } else {
            Log::debug('no recipient found');
        }

    }

    public function OnDraft(){
        Log::debug("OnDraft");
        // do nothing
    }

    public function OnApprove($topic, $sender){
        Log::debug("OnApprove");

        $recipients = HubHelper::GetSenderAsRecipientForTopic($sender);
        $recipientsMessage = HubHelper::GetSenderAsRecipientForMessage($sender);
        $approveHistories = $this->Payload->ApproveHistorys;
        if(sizeof($approveHistories) == 0) throw new Exception('No approve history for approved leave');
        $approveHistory = $approveHistories[sizeof($approveHistories)-1];
        $approver = HubUser::where('SID', $approveHistory->Created->UpdateUser)->get()->first();

        $title = $approver->Fullname .' has approved your leave application';
        $content = '';
        $topic->LeaveId = "0";

        if(sizeof($recipients) > 0) {
            $topic->Users = $recipients;
            $topic->Title = $title;
            $topic->Content = $content;

            $topic->save();

            $message = new Message();
            $message->Subject = $title;
            $message->Content = $content;
            $message->To = $recipientsMessage;
            $message->TId = $topic->_id;

            $message->save();

            HubHelper::CallbackToHub($recipients, $title, $topic->_id);

        } else {
            Log::debug('no recipient found ');
        }
        Log::debug($title);

        if($sender->IsHeadOfficeStaff){
            Log::info('head office staff, notify HO - Payroll');
            $cso = HubHelper::GetHOPayrollCoordinators();
            Log:debug('HOPayrollCoordinators found '. sizeof($cso));
        } else {

            Log::debug('ready to check if any CSO to notify');

            // send messages to CSO
            $cso = $sender->GetCSOs($this->Payload->FacilityID);

            if ((property_exists($this->Payload, 'CashOut') && $this->Payload->CashOut) ||
                (property_exists($this->Payload, 'Advance') && $this->Payload->Advance)
            ) {
                Log::debug("CashOut or Advance and approved, notify Payroll");
                $payroll = HubHelper::GetPayrollCoordinators();
                $cso = array_merge($cso, $payroll);
            }
        }

        $title = $approver->Fullname .' has approved a leave application by '.$sender->GetFullnameWithFacility($this->Payload) ;
        $content = '';

        if(is_array($cso) && sizeof($cso) > 0) {
            $recipients = HubHelper::PrepareRecipientsForTopic($cso, $sender);
            $recipientsMessage = HubHelper::GetRecipientsForMessage($cso, $sender);

            if(sizeof($recipients) > 0) {

                Log::debug('cso found = '.sizeof($recipients));

                $topic = new Topic();
                $topic->LeaveId = $this->Payload->_id;
                $topic->Type = TOPIC_TYPE_LEAVE;
                $topic->Users = $recipients;
                $topic->Title = $title;
                $topic->Content = $content;
                $topic->save();

                $message = new Message();
                $message->Subject = $title;
                $message->Content = $content;
                $message->To = $recipientsMessage;
                $message->TId = $topic->_id;
                $message->save();

                HubHelper::CallbackToHub($recipients, $title, $topic->_id);

                Log::debug($title);
            } else {
                Log::debug('no CSO found ');
            }
        } else
        {
            Log::info('no cso found');
        }
    }

    public function OnPayroll($topic, $sender){
        Log::debug("OnPayroll");

        $approveHistories = $this->Payload->ApproveHistorys;
        if(sizeof($approveHistories) == 0) throw new Exception('No approve history for approved leave');
        $approveHistory = $approveHistories[sizeof($approveHistories)-1];
        $approver = HubUser::where('SID', $approveHistory->Created->UpdateUser)->get()->first();

        if((property_exists($this->Payload, 'SickLeave') && $this->Payload->SickLeave)) {
            Debugbar::debug("sick leave - previous pay period, notify Payroll");
            Log::debug("sick leave - previous pay period, notify Payroll");
            $payroll = HubHelper::GetPayrollCoordinators();
            $title = $sender->GetFullnameWithFacility($this->Payload) . ' has applied for a sick leave - previous pay period';
            $content = '';

            if(is_array($payroll)) {
                $recipients = HubHelper::PrepareRecipientsForTopic($payroll, $sender);
                $recipientsMessage = HubHelper::GetRecipientsForMessage($payroll, $sender);

                if(sizeof($recipients) > 0) {
                    $topic = new Topic();
                    $topic->LeaveId = $this->Payload->_id;
                    $topic->Type = TOPIC_TYPE_LEAVE;
                    $topic->Users = $recipients;
                    $topic->Title = $title;
                    $topic->Content = $content;
                    $topic->save();

                    $message = new Message();
                    $message->Subject = $title;
                    $message->Content = $content;
                    $message->To = $recipientsMessage;
                    $message->TId = $topic->_id;
                    $message->save();

                    HubHelper::CallbackToHub($recipients, $title, $topic->_id);

                    Log::debug($title);
                } else {
                    Log::debug('no PayrollCoordinator found ');
                }
            }

        }
    }

    public function OnCancel($topic, $sender){
        Log::debug("OnCancel");

        // check this leave has been approved
        $approveHistories = $this->Payload->ApproveHistorys;
        if(sizeof($approveHistories) == 0) throw new Exception('No approve history for approved leave');
        $approveHistory = $approveHistories[sizeof($approveHistories)-1];
        if($approveHistory->State != LEAVE_APPROVED) return;

        // once it is approved we will need to notify GM/RM and CSO
        $manager = $this->findManagers($sender);
        if($manager == null) throw new Exception('failed to find manager');
        $recipients = HubHelper::PrepareRecipientsForTopic($manager, $sender);
        $recipientsMessage = HubHelper::GetRecipientsForMessage($manager, $sender);
        $title = $sender->GetFullnameWithFacility($this->Payload) . ' has decided to cancel the leave';
        $content = 'Click on View to see details of the leave';

        if(sizeof($recipients) > 0) {
            $topic->Users = $recipients;
            $topic->Title = $title;
            $topic->Content = $content;

            $topic->save();

            $message = new Message();
            $message->Subject = $title;
            $message->Content = $content;
            $message->To = $recipientsMessage;
            $message->TId = $topic->_id;

            $message->save();

            HubHelper::CallbackToHub($recipients, $title, $topic->_id);

            Log::debug($title);

        } else {
            Log::debug('no recipient found ');
        }

    }

    public function onApply($topic, $sender){
        Log::debug("onApply");

        $manager = $this->findManagers($sender);
        if($manager == null) throw new Exception('failed to find manager');
        $recipients = HubHelper::PrepareRecipientsForTopic($manager, $sender);
        $recipientsMessage = HubHelper::GetRecipientsForMessage($manager, $sender);
        $title = $sender->GetFullnameWithFacility($this->Payload) . ' is applying for leave';
        $content = 'Click on View to see details of the leave and to action on it';

        if(sizeof($recipients) > 0) {
            $topic->Users = $recipients;
            $topic->Title = $title;
            $topic->Content = $content;

            $topic->save();

            $message = new Message();
            $message->Subject = $title;
            $message->Content = $content;
            $message->To = $recipientsMessage;
            $message->TId = $topic->_id;

            $message->save();

            HubHelper::CallbackToHub($recipients, $title, $topic->_id);

            Log::debug($title);
        } else {
            Log::debug('no recipient found ');
        }

    }

    public function onResponse($topic, $sender){
        Log::debug("onResponse");

        $approveHistories = $this->Payload->ApproveHistorys;
        if(sizeof($approveHistories) < 2) throw new Exception('Insufficient history to respond to a declined leave application');
        $managerHistory = $approveHistories[sizeof($approveHistories)-2];
        if($managerHistory->State != LEAVE_DECLINED) return;
        $senderHistory = $approveHistories[sizeof($approveHistories)-1];
        if($senderHistory->State != LEAVE_RESPONSE) return;

        $title = $sender->GetFullnameWithFacility($this->Payload) . ' is asking something about the leave application';
        $content = $senderHistory->Comment;

        $manager = HubUser::where('SID', $managerHistory->Created->UpdateUser)->get()->first();
        $recipients=HubHelper::GetSenderAsRecipientForTopic($manager);
        $recipientsMessage = HubHelper::GetSenderAsRecipientForMessage($manager);

        if(sizeof($recipients) > 0) {
            $topic->Users = $recipients;
            $topic->Title = $title;
            $topic->Content = $content;

            $topic->save();

            $message = new Message();
            $message->Subject = $title;
            $message->Content = $content;
            $message->To = $recipientsMessage;
            $message->TId = $topic->_id;

            $message->save();

            HubHelper::CallbackToHub($recipients, $title, $topic->_id);

            Log::debug($title);
        } else {
            Log::debug('no recipient found ');
        }


    }

    public function onForward($topic, $sender){
        Log::debug("onResponse");

        $title = 'FW: '.$sender->GetFullnameWithFacility($this->Payload) . ' is applying for leave';
        $content = 'Click on View to see details of the leave and to action on it';

        $manager = HubUser::where('SID', $this->Payload->ForwardTo->SID)->get()->first();
        $recipients=HubHelper::GetSenderAsRecipientForTopic($manager);
        $recipientsMessage = HubHelper::GetSenderAsRecipientForMessage($manager);

        if(sizeof($recipients) > 0) {
            $topic->Users = $recipients;
            $topic->Title = $title;
            $topic->Content = $content;

            $topic->save();

            $message = new Message();
            $message->Subject = $title;
            $message->Content = $content;
            $message->To = $recipientsMessage;
            $message->TId = $topic->_id;

            $message->save();

            HubHelper::CallbackToHub($recipients, $title, $topic->_id);

            Log::debug($title);
        } else {
            Log::debug('no recipient found ');
        }
    }

    public function onException($ex){
        Log::debug("onException");

        Debugbar::debug($ex->getMessage());
        Log::debug($ex->getMessage());

        // notify helpdesk
        $topic = new Topic();
        $title = 'LeaveApplication - '.$ex->getMessage();
        $content = $this->Payload;
        $topic->CallHelpdesk($title, json_encode($content), $this->Message);
    }

    public function findManagers($sender){
        $manager = null;

        if($this->Payload->FacilityName == env('HEAD_OFFICE'))
        {
            if($sender->IsGardener){
                Log::debug("gardener, notify maintenance supervisor");
                $manager = Role::GetMaintenanceSupervisors();
            } else {
                Log::debug("notify Supervisors");
                $manager = $sender->Supervisors;
            }
        } else
        {
            if($sender->IsResentialManager){
                Log::debug("RM applying for leave, notify GM");
                $manager = $sender->GetGeneralManagers($this->Payload->FacilityID);
            } else if($sender->IsGardener){
                Log::debug("gardener, notify maintenance supervisor");
                $manager = Role::GetMaintenanceSupervisors();
            } else if($sender->IsAlliedHealthTeam) {
                Log::debug("allied health team, notify allied health manager");
                $manager = Role::GetAlliedHealthManagers();
            }
            // if leave without pay then find GM
            else if(property_exists($this->Payload, 'LWOP') && $this->Payload->LWOP) {
                Log::debug("LWOP, notify GM");
                $manager = $sender->GetGeneralManagers($this->Payload->FacilityID);
            }
            if($manager == null)
                $manager = $sender->GetResidentialManagers($this->Payload->FacilityID);
        }
        return $manager;
    }


}