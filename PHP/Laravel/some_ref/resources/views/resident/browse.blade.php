@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="block">
            <a href="{{url('/resident/search/'.$facility->_id)}}" class="button is-primary">{{__('mycare.search_again')}}</a>
        </div>

        <table class="table is-striped">
            <tr>
                <th>{{__('mycare.first_name')}}</th>
                <th>{{__('mycare.last_name')}}</th>
                <th>&nbsp;</th>
            </tr>
            @foreach($residents as $r)
                <tr>
                <td>{{$r->NameFirst}}</td>
                <td>{{$r->NameLast}}</td>
                    <td>
                        <a href="{{url('/resident/select/'.$r->_id)}}"><i class="fa fa-folder-open-o"></i></a>
                    </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection