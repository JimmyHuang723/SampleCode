@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="columns">


            <div class="column is-8">

                @if(isset($selectedForm))
                    <div class="block">
                        <h2 class="title">{{$selectedForm->FormName}}</h2>
                    </div>
                    <form method="post" action="{{url('/charting/store')}}">

                        {{ csrf_field() }}

                        @if($use_template_file)
                            @include($selectedForm->template)
                        @else
                            @include('template.form_controls', ['controls' => $controls])
                        @endif
                @endif

                @include('template.form_state')

            <div class="field is-grouped">
                <p class="control">
                    <button class="button is-primary">Save</button>
                </p>
                <p class="control">
                    <a class="button is-link" href="{{url('resident/select/'.$resident->_id)}}">Cancel</a>
                </p>
            </div>

            <input type="hidden" name="residentId" value="{{$resident->_id}}"/>
            <input type="hidden" name="formName" value="{{$selectedForm->FormName}}"/>
            <input type="hidden" name="formId" value="{{$selectedForm->_id}}"/>
            <input type="hidden" name="facilityId" value="{{$facility->_id}}"/>

            </form>
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

        <div class="column is-4">
            <div class="callout is-primary">
                <h1 class="title">{{$resident->NameLast}}, {{$resident->NameFirst}}</h1>
                <h2 class="subtitle">{{__('mycare.room')}}: {{$resident->Room}}</h2>
            </div>
            <a class="button is-outlined is-primary" href="{{url('/charting/add/'.$resident->_id)}}">{{__('mycare.select_chart')}}</a>

        </div>


    </div>



    </div>
@endsection
