@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="columns">
            <div class="column is-8">
                <p class="title">{{$facility->FacilityName}}</p>
            </div>
            <div class="column is-4">
                <a class="button is-primary is-outlined" href="{{url('/facility/inquiry/'.$facility->_id)}}">Inquiry</a>
            </div>
        </div>


        <div class="columns">

            <div class="column is-6">
                <div class="block">
                </div>
            </div>

            <div class="column is-6">
                <h2>{{__('mycare.last_10_movement')}}</h2>
            </div>

        </div>

    </div>
@endsection
