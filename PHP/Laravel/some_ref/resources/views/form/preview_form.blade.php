@extends('layouts.app')

@section('content')


    <div class="container container-top">

        <div class="columns">
            <div class="column is-12">
                    <a class="button is-outlined is-primary" href="{{url('/form/listing')}}">Return to Forms</a>
                    <a class="button is-outlined is-primary" href="{{url('/form/template/'.$form->_id)}}">{{__('mycare.edit_form_template')}}</a>
                    <a class="button is-outlined is-primary" href="{{url('/form/edit/'.$form->_id)}}">{{__('mycare.edit_form_header')}}</a>
            </div>
        </div>

            <div class="box">
                <h1 class="title">{{$form->FormName}}</h1>

                @include('template.form_controls', ['controls' => $controls])

                <div class="field is-grouped">
                    <p class="control">
                        <a class="button is-primary" href="{{url('/form/template/'.$form->_id)}}">Edit again</a>
                    </p>
                    <p class="control">
                        <a class="button is-link" href="{{url('/form/listing')}}">Return to Forms</a>
                    </p>
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



    </div>


@endsection
