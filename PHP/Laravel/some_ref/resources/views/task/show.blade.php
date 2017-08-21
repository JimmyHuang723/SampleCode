@extends('layouts.app')
@section('content')
<div class="pagehead-section p-none">
                <div class="container content">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="content-title">{{__('mycare.select_facility')}}</h1>
                        </div>
                    </div>
                </div>
</div>
    <div class="container container2">

        <h3 class="title"> <a class="button is-primary" href="{{url('facility/add')}}">{{__('mycare.add_facility')}}</a></h3>
         @if ( session()->has('message') )
        <div class="alert alert-success alert-dismissable">{{ session()->get('message') }}</div>
        @endif
        @foreach($tasks as $task)
            <div class="card facility-cart-item">
                <div class="card-header">
                    <div class="card-header-title">

                        123

                    </div>
                    <a class="button is-outlined" href="">456</a>
                    <a class="button is-outlined" href="">{{__('mycare.edit')}}</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
