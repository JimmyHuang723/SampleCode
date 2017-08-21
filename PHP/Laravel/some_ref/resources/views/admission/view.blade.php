@extends('layouts.app')
@section('content')
    <div class="container container-top">
        <div class="columns">
            <div class="column is-8">
                @include('template.resident_header', ['resident' => $resident])
            </div>
            <div class="column is-4">
                <a class="button is-outlined is-primary" href="{{url('admission/listing')}}">{{trans_choice('mycare.admission', 2)}}</a>
                <a class="button is-outlined is-primary" href="{{url('resident/select/'.$resident->_id)}}">{{trans_choice('mycare.resident_view', 2)}}</a>
            </div>
        </div>

        <div class="columns">

            <div class="column is-12">
                <div class="content">
                    <h2>{{trans_choice('mycare.checklist',2)}}</h2>

                    @foreach($checklists as $chk)
                        <h3>{{$chk['Title']}}</h3>

                        <table class="table">
                            <tr>
                                <th>&nbsp;</th>
                                <th>{{trans_choice('mycare.assessment',1)}}</th>
                                <th>{{__('mycare.due_date')}}</th>
                                <th>{{__('mycare.complete_date')}}</th>
                            </tr>
                            @foreach($chk['Tasks'] as $task)
                                <tr>
                                    <td>@if($task->Status == 1)<i class="fa fa-check"></i>@else &nbsp; @endif</td>
                                    <td width="70%"><a href="{{url('task/action/'.$task->TaskId)}}">{{$task->Title}}</a></td>
                                    <td width="15%">{{$task->StopDate}}</td>
                                    <td width="15%">{{$task->CompletedDate}}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endforeach

                    <a href="{{url('admission/archive/'.$admission->_id)}}">{{__('mycare.archive')}}</a>
                </div>
            </div>


        </div>
    </div>
@endsection