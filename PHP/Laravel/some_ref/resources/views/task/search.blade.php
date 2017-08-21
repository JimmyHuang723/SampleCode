@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="columns">
            <div class="columns">
                <div class="column">
                    <div class="content">
                        <h2>{{trans_choice('mycare.task',2)}}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">

                <table class="table">
                    <tr>
                        <th>{{__('mycare.title')}}</th>
                        <th>{{__('mycare.description')}}</th>
                        <th>{{__('mycare.date_started')}}</th>
                        <th>{{__('mycare.due_date')}}</th>
                    </tr>
                    @foreach($tasks as $t)
                        <tr>
                            <td>{{$t->Title}}</td>
                            <td>
                                <a href="{{url('task/action/'.$t->_id)}}">{{$t->Title}}</a>
                            </td>
                            <td>{{$t->BeginDate}}</td>
                            <td>{{$t->DueDate}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>



    </div>

@endsection