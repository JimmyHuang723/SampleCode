@extends('layouts.app')

@section('content')
    <div class="container container-top">

        <div class="columns">
            <div class="column is-8">
                <p class="title">{{trans_choice('mycare.admission',2)}}</p>
                <p class="subtitle">{{trans_choice('mycare.facility',1)}}: {{$facility->FacilityName}}</p>
            </div>
            <div class="column is-4">
                <a class="button is-primary is-outlined " href="{{url('inquiry/listing')}}">{{trans_choice('mycare.inquiry', 2)}}</a>
                @if (App\Domains\Permission::check("Admissions")->enableEdit)
                <a class="button is-primary is-outlined" href="{{url('admission/add')}}">{{__('mycare.new_admission')}}</a>
                @endif
            </div>
        </div>

        <div class="columns">
            <div class="column is-6">
                <p class="control has-icons-left">
                    <typeahead id="typeahead_input" src_url="/admission/findahead" onselect="onSelectTypeahead" ></typeahead>
                </p>
            </div>
        </div>


        <div class="columns">

            <div class="column">

                <table class="table">
                    <tr>
                        <th width="40%">{{__('mycare.resident_name')}}</th>
                        <th width="20%">{{__('mycare.location')}}</th>
                        <th>{{__('mycare.room')}}</th>
                        <th>{{__('mycare.admitted_at')}}</th>
                        <th width="5%">&nbsp;</th>
                    </tr>
                    @foreach($admissions as $i)
                        <tr>
                            <td><a href="{{url('/resident/select/'.$i->Resident['ResidentId'])}}">{{strtoupper($i->data['last_name'])}}, {{$i->data['first_name']}}</a></td>
                            <td>{{$i->Location['LocationName']}}</td>
                            <td>{{$i->Room['RoomName']}}</td>
                            <td>{{$i->created_at->format('d-M-Y')}}</td>
                            <td><a href="{{url('admission/view/'.$i->_id)}}">{{__('mycare.checklist')}}</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="columns">
            <div class="column is-5">
            </div>
            <div class="column is-1">
                {{ $admissions->links() }}
            </div>
            <div class="column is-6">
            </div>
        </div>

    </div>

@endsection



@section('script')
    <script>
        $('#typeahead_input input').attr("class", "input is-large");

        function onSelectTypeahead(item){ 
            window.location = "/admission/view/"+item._id;
        }
    </script>
@endsection

