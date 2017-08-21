@extends('layouts.app')

@section('content')
    <div class="container content">

        <div class="columns">
            <div class="column is-4">
                <h1 class="content-title">{{trans_choice('mycare.inquiry',2)}}
                    <p class="subtitle">{{trans_choice('mycare.facility',1)}}: {{$facility->FacilityName}}</p>
                </h1>
            </div>
            <div class="column is-8 clear">
                <a class="button is-primary btn btn-styles btn-primary2 m-t-15 right m-l-lg" href="{{url('inquiry/add')}}">{{__('mycare.add')}}</a>
                <form method="get">
                    <ul class="list-unstyled list-inline avail-form-filter search2-styles white-bg">
                        <li>
                            <input class="input" type="text" name="name" placeholder="{{__('mycare.search')}}" value="{{Request::input('name')}}">
                        </li>
                        <li class="right">
                            <input type="submit" value="{{__('mycare.search')}}" class="button is-info btn btn-styles btn-primary2">
                        </li>
                    </ul>    
                </form>
            </div>
        </div>

        <!--<div class="columns">
            <div class="column is-12">
                <form method="get">
                    <ul class="list-unstyled list-inline avail-form-filter">
                        <li>
                            <input class="input" type="text" name="name" placeholder="{{__('mycare.search')}}" value="{{Request::input('name')}}">
                        </li>
                        <li>
                            <input type="submit" value="{{__('mycare.search')}}" class="button is-info">
                        </li>
                    </ul>    
                </form>
            </div>
        </div>-->


        <div class="columns">

            <div class="column">
                <div class="ibox">
                    <div class="ibox-content p-none">
                        <table class="table">
                            <tr>
                                <th>{{__('mycare.first_name')}}</th>
                                <th>{{__('mycare.last_name')}}</th>
                                <th>{{__('mycare.created_at')}}</th>
                                <th width="20%">&nbsp;</th>
                            </tr>
                            @foreach($inquiries as $i)
                                <tr>
                                    <td>{{$i->data['first_name']}}</td>
                                    <td>{{$i->data['last_name']}}</td>
                                    <td>{{$i->created_at->format('d-M-Y')}}</td>
                                    <td>
                                        <a class="button is-primary btn btn-styles btn-primary2 height-sm p-t-none" href="{{ url('inquiry/view/'.$i->_id) }}">{{__('mycare.view')}}</a>
                                        <a class="button is-primary btn btn-styles btn-primary2 height-sm p-t-none" href="{{ url('admission/add'.'?inquiryId='.$i->_id) }}">{{__('mycare.admit')}}</a>
                                        <form method="post" action="{{url('inquiry/archive')}}" style="display:inline;">
                                            {{csrf_field()}}
                                            <input type="hidden" name="inquiryId" value="{{$i->_id}}"/>
                                            <a class="button is-primary btn btn-styles btn-primary2 height-sm p-t-none" onclick="event.preventDefault();onClickArchive(this)">{{__('mycare.archive')}}</a>
                                        </form>
                                    </td>
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
        function onClickArchive(obj) {
            if (confirm("{{__('mycare.inquiry_are_you_sure_archive')}}") == true) {
                $(obj).parents("form").submit();
            }
        }
    </script>

@endsection
