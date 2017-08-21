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
                <a href="{{ url('/facility/facilityview/'.$facility_detail['_id']) }}" class="btn btn-styles btn-primary2 p-t-7 m-t-n-6 right">Return</a>
            </h1>
        </div>
    </div>
</div>

<div class="container container2 content white-bg ibox m-t-sm">
    <form id="savelocation" role="form" action="{{url('facility/savelocation/'.$facility_detail->_id)}}" method="post">
        <div class="faciltiy clear">
            <div class="field">
                <label class="label">{{__('mycare.location')}}</label>
                <div class="control">
                    <input class="input is-primary" type="text" required ="true" name="LocationNameLong" value="{{!empty($location_detail)?$location_detail->LocationNameLong:''}}" >
                    <span class="help-block m-none" style="color:red">{{$errors->first('LocationNameLong')}}</span>
                </div>
            </div>
        </div>
        <div class="faciltiy clear">
            <div class="field">
                <label class="label">{{__('mycare.building_number')}}</label>
                <div class="control">
                    <input class="input is-primary" type="text" required ="true" name="BuildingNumber" value="{{!empty($location_detail)?$location_detail->BuildingNumber:''}}" >
                    <span class="help-block m-none" style="color:red">{{$errors->first('BuildingNumber')}}</span>
                </div>
            </div>
        </div>
        <div class="faciltiy clear">
            <div class="field">
                <label class="label">{{__('mycare.building_name')}}</label>
                <div class="control">
                    <input class="input is-primary" type="text" required ="true" name="BuildingName" value="{{!empty($location_detail)?$location_detail->BuildingName:''}}" >
                    <span class="help-block m-none" style="color:red">{{$errors->first('BuildingName')}}</span>
                </div>
            </div>
        </div>
        <div class="faciltiy clear">
            <div class="field">
                <label class="label">{{__('mycare.floor_level')}}</label>
                <div class="control">
                    <input class="input is-primary" type="text" required ="true" name="FloorLevel" value="{{!empty($location_detail)?$location_detail->FloorLevel:''}}" >
                    <span class="help-block m-none" style="color:red">{{$errors->first('FloorLevel')}}</span>
                </div>
            </div>
        </div>
        <input type="hidden" name="Location_id" value="{{!empty($location_detail)?$location_detail->_id:''}}">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="faciltiy clear">
            <div class="field is-grouped m-b-none m-t-sm">
                <div class="control">
                    <button type="submit" class="btn btn-styles btn-primary2 p-t-7">{{__('mycare.save')}}</button>
                </div>
                <div class="control">
                    <button type="button" class="btn btn-styles btn-primary2 is-outlined p-t-7" onclick="$('input[name=action]').val('archive');$('#savelocation').submit();">{{__('mycare.archive')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>


@endsection

