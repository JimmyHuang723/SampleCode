@extends('layouts.app')
@section('content')
<div class="container content container-top m-t-none m-b-lg">

    <div class="columns m-none">
        <div class="column p-none">
            <h1 class="content-title">{{__('mycare.care_plan')}}</h1>
        </div>
    </div>

    <div class="columns m-none">
        <div class="column p-none">
            <div class="ibox">
                <div class="ibox-content p-none">
                    <table class="table table2 is-stripped">
                        <tr>
                        <th>{{__('mycare.facility_name')}}</th>
                        <th>{{__('mycare.resident_name')}}</th>
                        <th width="200px">{{__('mycare.last_update')}}</th>
                        <th width="50px">{{trans_choice('mycare.status',1)}}</th>
                        <th width="120px">&nbsp;</th>
                        </tr>
                        @foreach($plans as $plan)
                        <tr>
                            <td>{{$plan->Facility['FacilityName']}}</td>
                            <td>{{$plan->Resident['ResidentName']}}</td>
                            <td>{{$plan->updated_at->format('d-M-Y H:i')}}</td>
                            <td class="text-styles">@if($plan->IsActive)<i class="fa fa-gavel"></i>@endif</td>
                            <td><a class="btn btn-styles btn-primary2 height-sm" href="{{url('careplan/view/'.$plan->Resident['ResidentId'])}}">{{__('mycare.view')}}</a></td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="columns m-none">
        <div class="column is-2 p-none">
            {{ $plans->links() }}
        </div>
    </div>

</div>
@endsection