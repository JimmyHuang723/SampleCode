@extends('layouts.app')

@section('content')
    <div class="container container-top">

        <div class="columns">
            <div class="column is-8">

                @include('template.resident_header', ['resident' => $resident, 'editable' => false])

            </div>
            <div class="column is-4">
                <a class="button is-outlined is-primary" href="{{url('/resident/select/'.$resident->_id)}}">{{__('mycare.resident_view')}}</a>

            </div>
        </div>

        <div class="columns">
            <div class="column">
                @php
                    if ($category == 'assessment'){
                        $formAction = url('/assessment/search/'.$resident->_id);
                    }else if($category == 'chart'){
                        $formAction = url('/charting/search/'.$resident->_id);
                    }else if($category == 'form'){
                        $formAction = url('/residentform/search/'.$resident->_id);
                    }else{
                        $formAction = '';
                    }
                @endphp
                <form class="form-inline" method="get" action="{{$formAction}}" >
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
                <typeahead src_url="/assessment/findahead/{{$category}}/{{$resident->_id}}" onselect="onSelectTypeahead" ></typeahead>
            </div>
        </div>

        <div class="columns">
            <div class="column is-10">

                <table class="table">
                    <tr>
                        <th width="60%">{{trans_choice('mycare.assessment',1)}}</th>
                        <th>{{__('mycare.date_started')}}</th>
                        <th>{{__('mycare.complete_date')}}</th>
                        <th>&nbsp;</th>
                    </tr>
                    @foreach($assessments as $a)
                        <tr>
                            <td><a href="{{url('assessment/view/'.$a->_id)}}">{{$a->Form['FormName']}}</a></td>
                            <td>{{$a->created_at->format('d-M-Y')}}</td>
                            <td>
                                {{$a->CompletedByUser}}
                            </td>
                            <td>
                                <a href="{{url('assessment/edit/'.$a->_id)}}">{{__('mycare.edit')}}</a>
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
                {{ $assessments->appends(Request::query())->links() }}
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
            window.location = "/assessment/edit/"+item._id;
        }
    </script>
@endsection

