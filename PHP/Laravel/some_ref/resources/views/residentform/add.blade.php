@extends('layouts.app')

@section('content')
    <div class="container container-top">

        <div class="columns">
            <div class="column is-8">
                @include('template.resident_header', ['resident' => $resident])
            </div>
            <div class="column is-4">
                <a class="button is-outlined is-primary" href="{{url('/resident/select/'.$resident->_id)}}">{{__('mycare.resident_view')}}</a>
            </div>
        </div>


            <div class="columns">

                <div class="column is-4">
                    <div class="block">
                        <h2 class="subtitle">{{trans_choice('mycare.select_chart',2)}}</h2>
                    </div>

                    <table class="table is-striped">
                        @foreach($forms as $form)
                            <tr>
                                <td>{{$form->FormID}}</td>
                                <td><a href="{{url('/assessment/select/'.$resident->_id.'/'.$form->_id)}}">{{$form->FormName}}</a></td>
                            </tr>
                        @endforeach
                    </table>

                </div>



            </div>

        </div>
@endsection
