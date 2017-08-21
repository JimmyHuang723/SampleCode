@extends('layouts.app')
@section('content')
<div class="container content container-top m-t-none">
    <div class="columns m-none p-none clear">
        <div class="column is-8 content left m-none p-none">
            <h1 class="content-title">{{__('mycare.select_facility')}}</h1>
            
        </div>
        <div class="column is-4 right m-none p-none">
            <a class="btn btn-styles btn-primary2 p-t-7 m-t-sm m-b-sm right" href="{{url('facility/add')}}">{{__('mycare.add_facility')}}</a>
        </div>
    </div>
    <div class="columns">
        <div class="column">
            <div class="ibox">
                <div class="ibox-content p-none">
                    <table class="table table2">
                        <tr>
                            <th>{{trans_choice('mycare.facility',1)}}</th>
                            <th width="200">&nbsp;</th>
                            <!--<th width="10%">&nbsp;</th>-->
                        </tr>
                    @foreach($facilities as $fac)
                        <tr>
                            <td><a href="{{url('/facility/search/'.$fac->_id)}}">{{$fac->FacilityName}}</a></td>
                            <td>
                                <a class="btn btn-styles btn-primary2 height-sm m-r-xs" href="{{url('facility/facilityview/'.$fac->_id)}}">{{__('mycare.facility_view')}}</a>
                                <a class="btn btn-styles btn-primary2 height-sm" href="{{url('facility/edit/'.$fac->_id)}}">{{__('mycare.edit')}}</a>
                            </td>
                            <!--<td>
                                <a class="btn btn-styles btn-primary2 height-sm" href="{{url('facility/edit/'.$fac->_id)}}">{{__('mycare.edit')}}</a>
                            </td>-->
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
