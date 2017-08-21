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

class ProgressNoteAdditionalController extends Controller
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

    public function add($residentId, $pnId){

        $resident = Resident::find($residentId);
        $pnotes = ProgressNote::find($pnId);
        // last 5 progress notes
        $apnotes = ProgressNoteAdditional::GetLastNAdditionalProgressNotes($pnId, 5);
        $resident->CCS = ResidentCCS::GetScore($resident->_id);

        return view('progressnote.additional_note',[
            'resident' => $resident,
            'apnotes' => $apnotes,
            'pnotes'  => $pnotes
        ]);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'notes' => 'required',
            'residentId' => 'required'
        ]);

        $facilityId = $request->session()->get('facility');
        $facility = Facility::find($facilityId);
        $sid = Auth::user()->SID;

        $user = HubUser::where('SID', $sid)->get()->first();


        $pnId = $request->input('pnId');
        $notes = $request->input('notes');
        $handover_flag = $request->input('handover_flag');
        $residentId = $request->input('residentId');
        $resident = Resident::find($residentId);

        $handover_flag = !($handover_flag == null);

        $apnote = new ProgressNoteAdditional();
        $apnote->ProgressNoteId = $pnId;
        $apnote->notes = $notes;
        $apnote->handover_flag = $handover_flag;
        $apnote->archive = 0;
        $apnote->save();

        App\Domains\TimelineLog::Log('progress-note', 'create', 'ProgressNoteAdditionalController',
            [
                'userId' => $user->_id,
                'facilityId' => $facilityId,
                'residentId' => $residentId,
                'progressNoteId' => $pnId
            ]);

        return redirect('/progressnote/additional_note/'.$residentId.'/'.$pnId);
    }

}