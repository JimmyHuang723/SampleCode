@extends('layouts.app')

@section('content')

    <div class="container container-top">

        <div class="columns">
            <div class="column is-12">
                    <a class="button is-primary is-outlined" href="{{url('/form/listing')}}">Return to Forms</a>
                    <a class="button is-outlined is-primary" href="{{url('/form/template/'.$form->_id)}}">{{__('mycare.edit_form_template')}}</a>
                    <a class="button is-outlined is-primary" href="{{url('/form/edit/'.$form->_id)}}">{{__('mycare.edit_form_header')}}</a>
            </div>
        </div>


        @include($template)

    </div>

@endsection