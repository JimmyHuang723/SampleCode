@extends('layouts.app')

@section('content')
<div class="pagehead-section">
    <div class="container">

        <div class="columns">
            <div class="column is-6">
                    <div class="callout is-primary">
                        <h1 class="title">{{$resident->NameLast}}, {{$resident->NameFirst}}</h1>
                        <h2 class="subtitle">{{__('mycare.room')}}: {{$resident->Room}}</h2>
                    </div>
            </div>
            <div class="column is-6">
                <a href="{{url('/resident/select/'.$resident->_id)}}" class="button is-outlined is-primary">{{__('mycare.resident_view')}}</a>

            </div>
        </div>

        <div class="columns">
            <div class="column">
                <p class="title">{{$assessment->formName}}</p>
            </div>
        </div>


    </div>
</div>
@endsection