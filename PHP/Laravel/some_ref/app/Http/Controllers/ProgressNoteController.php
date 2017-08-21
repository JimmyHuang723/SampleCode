<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
use Illuminate\Support\Facades\Auth;
use App\Domains\HubUser;
use App\Domains\Resident;
use App\Domains\ResidentCCS;
use App\Domains\Facility;
use App;
use App\Domains\ProgressNote;
use App\Domains\ProgressNoteAdditional;
use Carbon\Carbon;
use MongoDB\BSON\UTCDatetime;

class ProgressNoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add($residentId){

        $resident = Resident::find($residentId);

        // last 5 progress notes
        $pnotes = ProgressNote::GetLastNProgressNotes($residentId, 5);
        $resident->CCS = ResidentCCS::GetScore($resident->_id);

        return view('progressnote.add',[
            'resident' => $resident,
            'pnotes' => $pnotes,
        ]);
    }

    public function store(Request $request){

        $this->validate($request, [
            'notes' => 'required',
            'residentId' => 'required'
        ]);

        $facilityId = $request->session()->get('facility');
        $facility = Facility::find($facilityId);
        $sid = Auth::user()->SID;

        $user = HubUser::where('SID', $sid)->get()->first();

        $notes = $request->input('notes');
        $handover_flag = $request->input('handover_flag');
        $residentId = $request->input('residentId');
        $resident = Resident::find($residentId);

        $handover_flag = !($handover_flag == null);

        $pnote = new ProgressNote();
        $pnote->Resident = $resident->Object;
        $pnote->notes = $notes;
        $pnote->handover_flag = $handover_flag;
        $pnote->archive = 0;
        $pnote->Facility = $facility->Object;
        $pnote->CreatedBy = $user->Object;
        $pnote->DataSource = 'user';
        $pnote->save();

        App\Domains\TimelineLog::Log('progress-note', 'create', 'ProgressNoteController',
            [
                'user' => $user->Object,
                'facility' => $facility->Object,
                'resident' => $resident->Object,
                'progressNoteId' => $pnote->_id
            ]);

        return redirect('/progressnote/add/'.$residentId);
    }

    public function search($residentId){
        $resident = Resident::find($residentId);

        // last 5 progress notes
//        $pnotes = ProgressNote::GetLastNProgressNotes($residentId, 5);
        $pnotes = ProgressNote::GetLastSevenDaysProgressNotes($residentId, 7);
        return view('progressnote.search',[
            'resident' => $resident,
            'pnotes' => $pnotes
        ]);
    }

    public function search_result(Request $request, $residentId){


        $resident = Resident::find($residentId);

        $inputs = $request->all();
        Debugbar::debug($inputs);

        $notes = trim($inputs['notes']);
        $date_start = $inputs['date_start_submit'];
        $date_end = $inputs['date_end_submit'];
        $user = $inputs['fullname'];

        $pnotes = ProgressNote::GetSearchNotesResult($notes, $residentId, $date_start, $date_end, $user);


        return view('progressnote.search_result',[
            'resident' => $resident,
            'pnotes' => $pnotes,
            'request' => $request
        ]);
    }

    public function view($pnId){

        $pnote = ProgressNote::find($pnId);
        $resident = Resident::find($pnote->Resident['ResidentId']);

        return view('progressnote.view', [
            'resident' => $resident,
            'pnote' => $pnote
        ]);
    }

    public function edit($pnId){

        $pnote = ProgressNote::find($pnId);
        $resident = Resident::find($pnote->Resident['ResidentId']);

        return view('progressnote.edit', compact('pnote', 'resident'));

    }

    public function update(Request $request, $pnId){

        $pnote = ProgressNote::find($pnId);

        $residentId = Resident::find($pnote->Resident['ResidentId']);

        $pnote->notes = $request->input('notes');
        $pnote->handover_flag = $request->input('handover_flag');
        $pnote->save();

        return redirect('progressnote/search/'.$residentId->_id);

    }

    public function archive($pnId){

        $pnote = ProgressNote::find($pnId);

        $residentId = Resident::find($pnote->Resident['ResidentId']);

        $pnote->archive = 1;
        $pnote->save();

        return redirect('progressnote/search/'.$residentId->_id);

    }

    public function add_additional_note(Request $request, $pnId){

        $pnote = ProgressNote::find($pnId);

        $uniqueid = strtotime(Carbon::now());

        $date = Carbon::now()->startOfDay();
        $usethisDate = new UTCDateTime($date->timestamp*1000);

        $anotes[$uniqueid] =  [

                'notes' => $request->input('notes'),
                'created_at' => $usethisDate,
                'updated_at' => $usethisDate

        ];

        if(is_array($pnote->additional_notes)){

//            array_push($anotes, $pnote->additional_notes);
            $anotes = array_merge_recursive($pnote->additional_notes, $anotes);

        }
        $pnote->additional_notes = $anotes;

        $pnote->save();

        $residentId = Resident::find($pnote->Resident['ResidentId']);

        return redirect('progressnote/search/'.$residentId->_id);

    }


}