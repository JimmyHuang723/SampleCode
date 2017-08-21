@php
    use App\Domains\Facility;
    use App\Domains\Room;
    $facilityId = session('facility');
    $facility = Facility::find($facilityId);
    $rooms = Room::orderBy('RoomName')
        ->where('FacilityID', $facility->FacilityID)
        ->get();
@endphp

<div class="box">
    @if(isset($resicdent))
    @else
        Resident section available when a resident is present
    @endif
    <div class="columns">
        <div class="column is-4">

            <div class="field">
                <label>1. Date commenced</label>
                <p class="contorl">
                    <input class="input" id="datepicker" name="RMC01" value="{{array_get($data, 'RMC01')}}" 
                        style="width:150px" />
                </p>
            </div>

            <div class="field">
                <label>2. New Room</label>
                <p class="contorl">
                    <select class="select" name="RMC02">
                        @foreach($rooms as $room)
                            <option value="{{$room->_id}}" @if(array_get($data, 'RMC02')==$room->_id) selected @endif>{{$room->RoomName}}</option>
                        @endforeach
                    </select>
                </p>
            </div>

        </div>
    </div>
</div>