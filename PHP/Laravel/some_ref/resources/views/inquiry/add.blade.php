@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="columns">
            <div class="column is-8">
                <p class="title">{{trans_choice('mycare.inquiry',1)}}</p>
                <p class="subtitle">{{trans_choice('mycare.facility',1)}}: {{$facility->FacilityName}}</p>
            </div>
            <div class="column is-4">
                <a class="button is-primary is-outlined" href="{{url('inquiry/listing')}}">{{trans_choice('mycare.inquiry',2)}}</a>
            </div>
        </div>

        <form role="form" method="post" action="{{url('/inquiry/store')}}">
            {{csrf_field()}}
            <div class="columns">

                <div class="column is-6">

                    <div class="field">
                        <label>{{__('mycare.first_name')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="first_name"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.last_name')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="last_name"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.dob')}}</label>
                        <p class="contorl">
                            <input class="input" id="datepicker" name="dob" value=""/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.contact_name')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="contact_name"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.relationship')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="relationship"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.contact_phone')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="contact_phone"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.contact_email')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="contact_email"/>
                        </p>
                    </div>

                </div>


                <div class="column is-6">

                    <div class="field">
                        <label>{{__('mycare.address')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="address"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.suburb')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="suburb"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.postcode')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="postcode"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.address_state')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="address_state"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.notes')}}</label>
                        <p class="contorl">
                            <textarea class="textarea" id="froala-editor" name="notes"></textarea>
                        </p>
                    </div>

                    <div class="field">
                        <button class="button is-primary" >{{__('mycare.save')}}</button>
                    </div>
                </div>
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

        </form>



    </div>
@endsection
