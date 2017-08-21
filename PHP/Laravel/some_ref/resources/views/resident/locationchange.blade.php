@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="columns">
            <div class="column is-6">
                @if($resident->StatusID == 1)
                    <div class="callout is-primary">
                        @else
                            <div class="">
                                @endif
                                <h1 class="title">{{$resident->NameLast}}, {{$resident->NameFirst}}</h1>
                                <h2 class="subtitle">{{$facility->FacilityName}} {{__('mycare.room')}}: {{$resident->Room}}</h2>
                            </div>

                    </div>
                    <div class="column is-6">
                        <a class="button is-outlined is-primary" href="{{url('/resident/select/'.$resident->_id)}}">{{__('mycare.resident_view')}}</a>
                        <a class="button is-outlined is-primary" href="{{url('/resident/edit/'.$resident->_id)}}">{{__('mycare.edit')}}</a>

                    </div>
            </div>

            <div>

                <div class="columns">
                    <div class="column is-10">
                        <a class="button" href="{{url('resident/discharge/'.$resident->_id)}}">{{__('mycare.discharge')}}</a>
                        <a class="button" href="{{url('resident/transfer/'.$resident->_id)}}">{{__('mycare.transfer')}}</a>
                        <a class="button" href="{{url('resident/leave/'.$resident->_id)}}">{{__('mycare.leave')}}</a>
                        <a class="button" href="{{url('resident/roomchange/'.$resident->_id)}}">{{__('mycare.room_change')}}</a>
                    </div>
                </div>
            </div>

@endsection