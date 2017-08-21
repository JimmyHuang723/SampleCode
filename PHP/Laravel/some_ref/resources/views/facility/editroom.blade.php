@extends('layouts.app')
@section('content')

@if ( session()->has('message') )
<div class="alert alert-success alert-dismissable">{{ session()->get('message') }}</div>
@endif
<div class="container content m-b-none">
    <div class="row">
        <div class="col-md-12">
            <h1 class="content-title clear">
                {{$facility_detail['NameLong']}}
                <a class="btn btn-styles btn-primary2 p-t-7 m-t-n-6 right" href="{{ url('/facility/facilityview/'.$facility_detail['_id']) }}">Return</a>
            </h1>
        </div>
    </div>
</div>

<div class="container container2 content white-bg ibox m-t-sm">
    <form id="saveroom" role="form" action="{{url('facility/saveroom/'.$facility_detail->_id)}}" method="post">
        <div class="faciltiy clear">
            <div class="field">
                <label class="label">{{__('mycare.room')}}</label>
                <div class="control">
                    <input class="input is-primary" type="text" required ="true" name="RoomName" value="{{!empty($room_detail)?$room_detail->RoomName:''}}" >
                    <span class="help-block m-none" style="color:red">{{$errors->first('RoomName')}}</span>
                </div>
            </div>
        </div>
        <div class="faciltiy clear">
            <div class="field">
                <label class="label">{{__('mycare.roomtype')}}</label>
                <div class="control">
                    <select class="select" name="RoomType" id="RoomType">
                        <option></option>
                        @foreach ($roombonds as $bond)
                        <option {{(!empty($room_detail)&&$room_detail->RoomType==$bond->RoomType)?'selected="selected"':''}}>{{$bond->RoomType}}</option>
                        @endforeach
                    </select>
                    <span class="help-block m-none" style="color:red">{{$errors->first('RoomType')}}</span>
                </div>
            </div>
        </div>
        <div class="faciltiy clear">
            <div class="field">
                <label class="label">{{__('mycare.location')}}</label>
                <div class="control">
                    <select class="select" name="Location">
                        <option></option>
                        @foreach ($locations as $location)

                        <option value="{{$location->_id}}" {{(!empty($room_detail)&&$room_detail->LocationNameLong==$location->LocationNameLong)?'selected="selected"':''}}>{{$location->LocationNameLong}}</option>
                        @endforeach
                    </select>
                    <span class="help-block m-none" style="color:red">{{$errors->first('Location')}}</span>
                </div>
            </div>
        </div>
        <div class="faciltiy clear">
            <div class="field is-grouped m-b-none m-t-sm">
                <div class="control">
                    <button type="submit" class="btn btn-styles btn-primary2 p-t-7">{{__('mycare.save')}}</button>
                </div>
                <div class="control">
                    <button type="button" class="btn btn-styles btn-primary2 is-outlined p-t-7" onclick="$('input[name=action]').val('archive');$('#saveroom').submit();">{{__('mycare.archive')}}</button>
                </div>
            </div>
        </div>
        <input type="hidden" name="Room_id" value="{{!empty($room_detail)?$room_detail->_id:''}}">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>


@endsection



@section('script')

<script language="JavaScript">
    var roombonds = {};
    @foreach ($roombonds as $bond)
    roombonds['{{$bond->RoomType}}'] = {{$bond->Bond}};
    @endforeach

    $(document).ready(function () {
        $("#RoomType").change(function () {
            if ($(this).val().trim()) {
                $('input[name="RoomBond"]').val(roombonds[$(this).val()]);
            }
        });
    });

</script>

@endsection
