@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="title">{{$user->Fullname}}</h2>

    <h3 class="subtitle">Select a facility</h3>

    @foreach($facilities as $fac)
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <a href="{{url('/facility/select/'.$fac->FacilityID)}}">{{$fac->NameLong}}</a>
            </div>
        </div>
    </div>
    @endforeach



</div>
@endsection
