@extends('layouts.app')

@section('content')

<div class="container content container-top">

    <div class="columns">
        <div class="column">
            <a class="button is-primary btn btn-styles btn-primary2" href="{{url('export/forms')}}">{{trans_choice('mycare.form',2)}}</a>
            <a class="button is-primary btn btn-styles btn-primary2" href="{{url('export/documents')}}">{{trans_choice('mycare.document',2)}}</a>
            <a class="button is-primary btn btn-styles btn-primary2" href="{{url('export/edocs')}}">{{trans_choice('mycare.edoc',2)}}</a>
            <a class="button is-primary btn btn-styles btn-primary2" href="{{url('export/visa457')}}">{{trans_choice('mycare.visa457',1)}}</a>
            <a class="button is-primary btn btn-styles btn-primary2" href="{{url('export/visanot457')}}">{{trans_choice('mycare.visanot457',1)}}</a>
            <a class="button is-primary btn btn-styles btn-primary2" href="{{url('export/aussie')}}">{{trans_choice('mycare.aussie',1)}}</a>
        </div>
    </div>

</div>

@endsection