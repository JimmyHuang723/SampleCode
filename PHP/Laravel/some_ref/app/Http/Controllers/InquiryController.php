<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Domains\Facility;
use App\Domains\HubUser;
use App\Domains\Inquiry;

class InquiryController extends Controller
{
    public function listing(Request $request){
        $facilityId = $request->session()->get('facility');

        // if the user is yet to select a facility, redirect to the view to select a facility
        if($facilityId == '')
            return redirect('facility/show');

        $facility = Facility::find($facilityId);

        $inquiries = Inquiry::where('Facility.FacilityId', $facilityId)
            ->where('status', 1)
            ->where('is_admitted','<>',1)
            ->orderBy('created_at', 'desc');

        // Filter by name
        if(Input::has('name')){
            $name = Input::get('name');
            $inquiries = $inquiries->where(function($query) use ($name){
                $query->where('data.first_name', 'like', $name .'%')
                    ->orWhere('data.last_name', 'like', $name .'%');
            });
        }

        $inquiries = $inquiries->get();

        return view('inquiry.listing',[
            'facility' => $facility,
            'inquiries' => $inquiries
        ]);
    }

    public function add(Request $request){
        $facilityId = $request->session()->get('facility');

        // if the user is yet to select a facility, redirect to the view to select a facility
        if($facilityId == '')
            return redirect('facility/show');

        $facility = Facility::find($facilityId);

        return view('inquiry.add',[
            'facility' => $facility
        ]);
    }

    /**
     * View the inquiry
     * Author Li
     * */
    public function view(Request $request, $inquiryId){
        $facilityId = $request->session()->get('facility');

        // if the user is yet to select a facility, redirect to the view to select a facility
        if($facilityId == '')
            return redirect('facility/show');
        if(empty($inquiryId))
            return redirect('inquiry/listing');

        $facility = Facility::find($facilityId);
        $inquiry_detail = Inquiry::find($inquiryId);

        $user = HubUser::where('SID', Auth::user()->SID)->get()->first();

        /* get the last comment wrote by other user */
        $comments = (isset($inquiry_detail->data['comments']))?$inquiry_detail->data['comments']:[];

        return view('inquiry.view',[
            'facility' => $facility,
            'inquiry_detail' => $inquiry_detail,
            'comments' => $comments
        ]);
    }

    public function store(Request $request){

        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            "contact_phone" => "required"
        ]);
//        Log::debug('enter store_inquiry');
        $facilityId = $request->session()->get('facility');
        $facility = Facility::find($facilityId);
//
        $user = HubUser::where('SID', Auth::user()->SID)->get()->first();

        $inquiry = new Inquiry();
        $inquiry->Facility = $facility->FacilityObject;
        $inquiry->CreatedBy = $user->UserObject;
        $inquiry->data = $request->all();
        $inquiry->status = 1;

        $inquiry->save();

        return redirect('inquiry/listing');
    }

    public function store_comment(Request $request, $inquiryId){

        $this->validate($request, [
            'comment' => 'required'
        ]);

        $user = HubUser::where('SID', Auth::user()->SID)->get()->first();

        $inquiry = Inquiry::find($inquiryId);
        $data = $inquiry->data;
        $data['comments'][] = [
            'content'=>$request['comment'],
            'created_at'=>date('Y-m-d'),
            'UserId' => $user->_id,
            'SID' => $user->SID,
            'FullName' => $user->SGivenNames.', '.$user->SSurname,

        ];
        $inquiry->data = $data;
        $inquiry->save();

        return redirect('inquiry/view/'.$inquiryId);
    }

    public function archive(Request $request){
        $inquiryId = $request->input("inquiryId");
        $inquiry_detail = Inquiry::find($inquiryId);
        if(!empty($inquiry_detail)){
            $inquiry_detail->status = 0;
            $inquiry_detail->save();
        }

        return redirect('inquiry/listing');
    }

}