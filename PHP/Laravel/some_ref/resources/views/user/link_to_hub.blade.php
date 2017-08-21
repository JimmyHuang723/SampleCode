@extends('layouts.app')

@section('content')
    <div class="container">

        <h2 class="title">{{__('mycare.'.$title)}}</h2>

        @include('user.user_menu_bar', ['user' => $user])

        <div class="columns">
            <div class="column is-6">

                <form method="post" action="{{url('/user/search')}}">
                    {{csrf_field()}}
                <div class="field has-addons">
                    <label class="label has-text-left">{{__('mycare.enter_user_name')}}</label>

                    <p class="control">
                        <input type="text" class="input" name="name"/>
                    </p>
                    <p class="control">
                        <button class="button is-primary" href="{{url('/user/search')}}">{{__('mycare.search')}}</button>
                    </p>
                </div>
                </form>

                <table class="table">
                    <tr>
                        <td>{{__('mycare.user_name')}}</td>
                        <td>{{$user->name}}</td>
                    </tr>
                    <tr>
                        <td>{{__('mycare.user_email')}}</td>
                        <td>{{$user->email}}</td>
                    </tr>

                </table>
            </div>
            @if(sizeof($result) > 0)
            <div class="column is-6">
                <div class="panel">
                    <div class="panel-heading">
                        <p class="subtitle">{{__('mycare.top_50_result')}} - {{__('mycare.select_a_user_to_link')}}</p>
                    </div>
                    @foreach($result as $u)
                    <a class="panel-block" href="{{url('/user/linkuser/'.$user->id.'/'.$u->SID)}}">
                        <span class="panel-icon">
                            <i class="fa fa-handshake-o"></i>
                        </span>
                        {{$u->Fullname}} ({{$u->UserName}})
                    </a>
                    @endforeach
                </div>

            </div>
            @endif
        </div>

    </div>
@endsection
