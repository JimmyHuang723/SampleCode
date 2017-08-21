@extends('layouts.app')

@section('content')
    <div class="container container-top">

        <div class="columns">
            <div class="column">
                <form class="form-inline" method="get" action="{{url('/residentmovement/listing/')}}" >
                    <legend style="margin-bottom: 10px;">Filter</legend>
                    <div class="form-group">
                        <div class='input-group date'>
                            <input  type='text' class="form-control datepicker"  id="date_start"  name="date_start" placeholder="Start Date" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                     to
                    </div>
                    <div class="form-group">
                        <div class='input-group date'>
                            <input  type='text' class="form-control datepicker" id="date_end" name="date_end" placeholder="End Date" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-default">Search</button>
                </form>
            </div>
        </div>

        <div class="columns">
            <div class="column is-6">
                <typeahead src_url="/residentmovement/findahead" onselect="onSelectTypeahead" ></typeahead>
            </div>
        </div>

        <div class="columns">
            <div class="column is-10">

                <table class="table">
                    <tr>
                        <th>{{trans_choice('mycare.resident_name',1)}}</th>
                        <th>{{__('mycare.date_of_movement')}}</th>
                        <th>{{__('mycare.activity')}}</th>
                        <th>{{__('mycare.reason')}}</th>
                        <th>{{__('mycare.complete_by')}}</th>
                        <th>{{__('mycare.state')}}</th>
                    </tr>
                    @foreach($residentmovements as $m)
                        <tr>
                            <td>{{$m->Resident['ResidentName']}}</td>
                            <td>{{ substr($m->MovementDate['Date'],0,10) }}</td>
                            <td> {{ $m->activity }}</td>
                            <td>
                            @if (isset($m->Reason))
                            {{$m->Reason}}
                            @endif    
                            </td>
                            <td>{{$m->CompletedBy['FullName']}}</td>
                            <td>
                            @if ($m->State == 'new')
                                {{__('mycare.new')}}
                            @elseif ($m->State == 'resident-updated')
                                {{__('mycare.updated')}}
                            @endif  
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>

        <div class="columns">
            <div class="column is-4">
            </div>
            <div class="column is-2">
                {{ $residentmovements->appends(Request::query())->links() }}
            </div>
            <div class="column is-4">
            </div>
        </div>

    </div>


@endsection


@section('script')
    <script>
        // Init date from query url
        @if (Request::has("date_start"))
            @php
                $date_start = DateTime::createFromFormat('d/m/Y',  Request::input("date_start"));
                $datestring_start = $date_start->format('M d, Y H:i:s');
            @endphp
            var date_start = new Date("{{$datestring_start}}");
            $('#date_start').pickadate('picker').set('select', date_start);
        @endif 

        @if (Request::has("date_end"))
            @php    
                $date_end = DateTime::createFromFormat('d/m/Y',  Request::input("date_end"));
                $datestring_end = $date_end->format('M d, Y H:i:s');
            @endphp
            var date_end = new Date("{{$datestring_end}}");     
            $('#date_end').pickadate('picker').set('select', date_end);
        @endif

        // Default search date range is last 7 days : 
        @if ( (!Request::has("date_start")) and (!Request::has("date_end")) )
            var date = new Date();
            var date_seven_days_ago = new Date();
            date_seven_days_ago.setDate(date.getDate() - 7);
            $('#date_start').pickadate('picker').set('select', date_seven_days_ago);
            $('#date_end').pickadate('picker').set('select', date);
        @endif 

        function onSelectTypeahead(item){ 
            window.location = "/residentmovement/listing/"+item.Resident['ResidentId'];
        }
    </script>
@endsection

