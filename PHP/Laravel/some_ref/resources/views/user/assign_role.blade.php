@extends('layouts.app')

@section('content')
    <div class="container">

        <h2 class="title">{{__('mycare.'.$title)}}</h2>

        @include('user.user_menu_bar', ['user' => $user])


    </div>
@endsection
