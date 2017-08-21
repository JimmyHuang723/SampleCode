@extends('layouts.app')

@section('content')
<div class="container content container_top">
    <div class="ibox">
        <div class="ibox-content">
            <div class=columns>
                <div class="column is-1 field-width m-t-sm">
                    <p>{{trans_choice('mycare.find_resident',1)}}</p>
                </div>
                <div class="column is-4 p-t-1">
                    <typeahead src_url="/resident/findahead" onselect="onSelectTypeahead" ></typeahead>
                </div>
            </div>
        </div>
    </div>
    <div class=columns>
        <div class="column m-t-lg btn-p-t">            
            @include('dashboard.tabs', ['stats'=>$stats])
        </div>
    </div>

    <div class=columns>
        <div class="column">  
            <div class="ibox">
                <div class="ibox-content p-none">
                    <table class="table">
                        <tr>
                            <th width="45%">{{trans_choice('mycare.resident_name',1)}}</th>
                            <th width="30%">{{__('mycare.location')}}</th>
                            <th width="20%">{{__('mycare.complete_date')}}</th>
                            <th width="5%">&nbsp;
                        </tr>
                    @foreach($incidents as $incident)
                        <tr>
                            <td><a href="{{url('/resident/select/'.$incident->Resident['ResidentId'])}}">{{$incident->Resident['ResidentName']}}</a></td>
                            <td>{{$incident->GetValue(env('FIELD_CODE_RESIDENT_INCIDENT_LOCATION'))}}</td>
                            <td>{{$incident->CompletedByUser}}</td>
                            <td><a href="{{url('/assessment/view/'.$incident->_id)}}">{{__('mycare.view')}}</a></td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@section('script')

    <script>
   		function onSelectTypeahead(item){
   			window.location = "/resident/select/"+item._id;
   		}
    </script>

   
@endsection
