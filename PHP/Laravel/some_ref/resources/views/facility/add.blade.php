@extends('layouts.app')

@section('content')
@if ( session()->has('message') )
    <div class="alert alert-success alert-dismissable">{{ session()->get('message') }}</div>
@endif
<div class="pagehead-section p-none">
    <div class="container content">
        <div class="row">
            <div class="col-md-12">
                <h1 class="content-title">Add Facility</h1>
            </div>
        </div>
    </div>
</div>
<form role="form" action="{{url('facility/addfacility')}}" method="post">
    <div class="container white-bg container2 ibox m-b-md">
        <div class="faciltiy">
            <div class="field">
                <label class="label">{{__('mycare.facility_facilityName')}}*</label>
                <p class="control">
                    <input class="input is-primary" type="text" required ="true" name ="FacilityName" placeholder="{{__('mycare.facility_facilityName')}}">
                    <span class="help-block m-none" style="color:red">{{$errors->first('FacilityName')}}</span>
                </p>
            </div>

            <div class="field">
                <label class="label">{{__('mycare.facility_facility_Code')}}*</label>
                <p class="control">
                    <input class="input is-primary" type="text" required ="true" name ="FacilityCode" placeholder="{{__('mycare.facility_facility_Code')}}">
                    <span class="help-block m-none" style="color:red">{{$errors->first('FacilityCode')}}</span>
                </p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="faciltiy">
            <div class="field">
                <label class="label">{{__('mycare.facility_region_Code')}}</label>
                <p class="control">
                    <input class="input is-primary" type="text" name ="RegionCode" placeholder="{{__('mycare.facility_region_Code')}}">
                </p>
            </div>

            <div class="field">
                <label class="label">{{__('mycare.facility_regionName')}}</label>
                <p class="control">
                    <input class="input is-primary" type="text" name ="RegionName" placeholder="{{__('mycare.facility_regionName')}}">
                </p>
            </div>
            <div class="clear"></div>
        </div>



        <div class="faciltiy">
            <div class="field">
                <label class="label">{{__('mycare.facility_addressLine2')}}</label>
                <p class="control">
                    <input class="input is-primary" type="text" name ="AddressLine2" placeholder="{{__('mycare.facility_addressLine2')}}">
                </p>
            </div>

            <div class="field">
            <label class="label">{{__('mycare.facility_county')}}</label>
            <p class="control">
            <input class="input is-primary" type="text" name ="County" placeholder="{{__('mycare.facility_county')}}">
        </p>
    </div>
    <div class="clear"></div>
    </div>

    <div class="faciltiy">
    <div class="field">
        <label class="label">{{__('mycare.facility_addressLine1')}}</label>
        <p class="control">
            <input class="input is-primary" type="text" name ="AddressLine1" placeholder="{{__('mycare.facility_addressLine1')}}">
        </p>
    </div>
    <div class="field">
    <label class="label">{{__('mycare.facility_suburb')}}</label>
    <p class="control">
        <textarea class="textarea" name ="Suburb" placeholder="{{__('mycare.facility_suburb')}}"></textarea>
    </p>
    </div>
    <div class="clear"></div>
    </div>


    <div class="faciltiy">
        <div class="field">
            <label class="label">{{__('mycare.facility_postcode')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="Postcode" placeholder="{{__('mycare.facility_postcode')}}">
            </p>
        </div>

        <div class="field">
            <label class="label">{{__('mycare.facility_facilityPhone')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="FacilityPhone" placeholder="{{__('mycare.facility_facilityPhone')}}">
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="faciltiy">
        <div class="field">
            <label class="label">{{__('mycare.facility_facilityFax')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="FacilityFax" placeholder="{{__('mycare.facility_facilityFax')}}">
            </p>
        </div>

        <div class="field">
            <label class="label">{{__('mycare.facility_facilityEmail')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="FacilityEmail" placeholder="{{__('mycare.facility_facilityEmail')}}">
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="faciltiy">
        <div class="field">
            <label class="label">{{__('mycare.facility_contactFirstName')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="ContactFirstName" placeholder="{{__('mycare.facility_contactFirstName')}}">
            </p>
        </div>
        <div class="field">
            <label class="label">{{__('mycare.facility_contactLastName')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="ContactLastName" placeholder="{{__('mycare.facility_contactLastName')}}">
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="faciltiy">
        <div class="field">
            <label class="label">{{__('mycare.facility_contactPhone')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="ContactPhone" placeholder="{{__('mycare.facility_contactPhone')}}">
            </p>
        </div>

        <div class="field">
            <label class="label">{{__('mycare.facility_contactEmail')}}</label>
            <p class="control">
                <input class="input is-primary" type="text" name ="ContactEmail" placeholder="{{__('mycare.facility_contactEmail')}}">
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="faciltiy">
    <div class="field is-grouped m-t-sm">
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
