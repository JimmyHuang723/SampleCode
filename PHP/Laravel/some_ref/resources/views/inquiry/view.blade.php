@extends('layouts.app')

@section('content')

<div class="container">

    <div class="columns">
        <div class="column is-8">
            <p class="title">{{trans_choice('mycare.inquiry',1)}}</p>
            <p class="subtitle">{{trans_choice('mycare.facility',1)}}: {{$facility->FacilityName}}</p>
        </div>
        <div class="column is-4">
            <a class="button is-primary" href="{{url('inquiry/listing')}}">{{trans_choice('mycare.inquiry',2)}}</a>
        </div>
    </div>

    <div class="columns">

        <div class="column is-6">

            <div class="field">
                <label>{{__('mycare.first_name')}}</label>
                {{ $inquiry_detail->data['first_name'] }}
            </div>

            <div class="field">
                <label>{{__('mycare.last_name')}}</label>
                {{ $inquiry_detail->data['last_name'] }}
            </div>

            <div class="field">
                <label>{{__('mycare.dob')}}</label>
                {{ $inquiry_detail->data['dob'] }}
            </div>

            <div class="field">
                <label>{{__('mycare.contact_name')}}</label>
                {{ $inquiry_detail->data['contact_name'] }}
            </div>

            <div class="field">
                <label>{{__('mycare.relationship')}}</label>
                {{ $inquiry_detail->data['relationship'] }}
            </div>

            <div class="field">
                <label>{{__('mycare.contact_phone')}}</label>
                {{ $inquiry_detail->data['contact_phone'] }}
            </div>

            <div class="field">
                <label>{{__('mycare.contact_email')}}</label>
                {{ $inquiry_detail->data['contact_email'] }}
            </div>

        </div>


        <div class="column is-6">

            <div class="field">
                <label>{{__('mycare.address')}}</label>
                {{ $inquiry_detail->data['address'] }}
            </div>

            <div class="field">
                <label>{{__('mycare.suburb')}}</label>
                {{ $inquiry_detail->data['suburb'] }}
            </div>

            <div class="field">
                <label>{{__('mycare.postcode')}}</label>
                {{ $inquiry_detail->data['postcode'] }}
            </div>

            <div class="field">
                <label>{{__('mycare.address_state')}}</label>
                {{ $inquiry_detail->data['address_state'] }}
            </div>

            <div class="field">
                <label>{{__('mycare.notes')}}</label>
                {!! $inquiry_detail->data['notes'] !!}
            </div>

        </div>
    </div>

    <!-- add comment -->
    <form role="form" method="post" action="{{url('inquiry/store_comment/'.$inquiry_detail->_id)}}">
        {{csrf_field()}}
        <div class="columns">
            <div class="column">
                <div class="field">
                    <p class="control">
                        <textarea class="textarea" name="comment"></textarea>
                    </p>
                </div>
            </div>
            <div class="column">
                <button class="button is-primary">{{__('mycare.addcomment')}}</button>
            </div>
        </div>
    </form>

    <div>
        <h1 class="title">{{__('mycare.comments')}}</h1>
        <table class="table">
            <thead>
            <tr>
                <th>{{__('mycare.content')}}</th>
                <th>{{__('mycare.fullname')}}</th>
                <th>{{__('mycare.created_at')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($comments as $comment)
                <tr>
                    <td>{{ $comment['content'] }}</td>
                    <td>{{ $comment['FullName'] }}</td>
                    <td>{{ $comment['created_at'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif



</div>
@endsection
