@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="columns">
            <div class="column is-6">
                @include('template.resident_header', ['resident' => $resident])
            </div>
            <div class="column is-6">
                <a href="{{url('/resident/select/'.$resident->_id)}}" class="button is-outlined is-primary">{{__('mycare.resident_view')}}</a>

            </div>
        </div>

        <div class="columns">
            <div class="column">
                <p class="subtitle">{!! $pnote->notes !!}</p>
            </div>
        </div>


    </div>
@endsection