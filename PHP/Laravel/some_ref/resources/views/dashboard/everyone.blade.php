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
        <div class="column">
            <a class="button is-primary"  href="{{url('/dashboard/res_incident')}}">{{__('resident_incident_last24hr')}}</a>
            <a class="button is-outlined is-primary"  href="{{url('/dashboard/med_incident')}}">{{__('medication_incident_last24hr')}}</a>
            <a class="button is-outlined is-primary"  href="{{url('/dashboard/infection')}}">{{__('infection_last24hr')}}</a>
            <a class="button is-outlined is-primary"  href="{{url('/dashboard/bowel')}}">{{__('bowel_alert')}}</a>
            <a class="button is-outlined is-primary"  href="{{url('/dashboard/bgl')}}">{{__('bgl_alert')}}</a>
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
