@extends('layouts.app')
@section('content')

@if ( session()->has('message') )
    <div class="alert alert-success alert-dismissable">{{ session()->get('message') }}</div>
@endif
<div class="pagehead-section p-none">
    <div class="container content">
        <div class="row">
            <div class="col-md-12">
                <h1 class="content-title">Edit Facility</h1>
            </div>
        </div>
    </div>
</div>
<form role="form" action="{{url('facility/updatefacility/'.$facility_detail[0]['_id'])}}" method="post">
    <div class="container white-bg container2 ibox m-b-md">
        <div class="faciltiy">
            <div class="field">
                <label class="label">{{__('mycare.facility_facilityName')}}*</label>
                <p class="control">
                    <input class="input is-primary" type="text" required ="true" name ="FacilityName" value="{{$facility_detail[0]['FacilityName']}}" >
                    <span class="help-block m-none" style="color:red">{{$errors->first('FacilityName')}}</span>
                </p>
            </div>

            <div class="field">
                <label class="label">{{__('mycare.facility_facility_Code')}}*</label>
                <p class="control">
                    <input class="input is-primary" type="text" required ="true" name ="FacilityCode" value="{{$facility_detail[0]['FacilityID']}}" >
                    <span class="help-block m-none" style="color:red">{{$errors->first('FacilityCode')}}</span>
                </p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="faciltiy">
            <div class="field">
                <label class="label">{{__('mycare.facility_region_Code')}}</label>
                <p class="control">
                    <input class="input is-primary" type="text" name ="RegionCode" value="{{$facility_detail[0]['RegionCode']}}" >
                </p>
            </div>

            <div class="field">
                <label class="label">{{__('mycare.facility_regionName')}}</label>
                <p class="control">
                    <input class="input is-primary" type="text" name ="RegionName" value="{{$facility_detail[0]['RegionName']}}" >
                </p>
            </div>
            <div class="clear"></div>
        </div>



        <div class="faciltiy">
            <div class="field">
                <label class="label">{{__('mycare.facility_addressLine2')}}</label>
                <p class="control">
                    <input class="input is-primary" type="text" name ="AddressLine2" value="{{$facility_detail[0]['AddressLine2']}}" >
                </p>
            </div>

            <div class="field">
            <label class="label">{{__('mycare.facility_county')}}</label>
            <p class="control">
            <input class="input is-primary" type="text" name ="County" value="{{$facility_detail[0]['County']}}" >
        </p>
    </div>
    <div class="clear"></div>
    </div>

    <div class="faciltiy">
    <div class="field">
        <label class="label">{{__('mycare.facility_addressLine1')}}</label>
        <p class="control">
            <input class="input is-primary" type="text" name ="AddressLine1" value="{{$facility_detail[0]['AddressLine1']}}" >
        </p>
    </div>
    <div class="field">
    <label class="label">{{__('mycare.facility_suburb')}}</label>
    <p class="control">
        <textarea class="textarea" name ="Suburb" value="{{$facility_detail[0]['Suburb']}}" ></textarea>
    </p>
    </div>
    <div class="clear"></div>
    </div>


    <div class="faciltiy">
        <div class="field">
            <label class="label">{{__('mycare.facility_postcode')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="Postcode" value="{{$facility_detail[0]['Postcode']}}" >
            </p>
        </div>

        <div class="field">
            <label class="label">{{__('mycare.facility_facilityPhone')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="FacilityPhone" value="{{$facility_detail[0]['FacilityPhone']}}" >
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="faciltiy">
        <div class="field">
            <label class="label">{{__('mycare.facility_facilityFax')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="FacilityFax" value="{{$facility_detail[0]['FacilityFax']}}" >
            </p>
        </div>

        <div class="field">
            <label class="label">{{__('mycare.facility_facilityEmail')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="FacilityEmail" value="{{$facility_detail[0]['FacilityEmail']}}" >
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="faciltiy">
        <div class="field">
            <label class="label">{{__('mycare.facility_contactFirstName')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="ContactFirstName" value="{{$facility_detail[0]['ContactFirstName']}}" >
            </p>
        </div>
        <div class="field">
            <label class="label">{{__('mycare.facility_contactLastName')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="ContactLastName" value="{{$facility_detail[0]['ContactLastName']}}" >
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="faciltiy">
        <div class="field">
            <label class="label">{{__('mycare.facility_contactPhone')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="ContactPhone" value="{{$facility_detail[0]['ContactPhone']}}" >
            </p>
        </div>

        <div class="field">
            <label class="label">{{__('mycare.facility_contactEmail')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="ContactEmail" value="{{$facility_detail[0]['ContactEmail']}}" >
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="faciltiy faciltiybtns">
    <div class="field is-grouped">
    <p class="control">
        <button class="btn btn-styles btn-primary2 p-t-7" >{{__('mycare.submit')}}</button>
    </p>
    <p class="control">
        <a class="btn btn-styles btn-primary2 is-outlined p-t-7" href="{{url('facility/show')}}">{{__('mycare.cancel')}}</a>
    </p>
    </div>
    </div>

    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>
@endsection
