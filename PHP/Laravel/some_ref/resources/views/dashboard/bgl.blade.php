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
        <div class="column btn-p-t m-t-lg">
            

            @include('dashboard.tabs')
            
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
