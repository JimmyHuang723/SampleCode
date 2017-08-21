@extends('layouts.app')


@php
    $render = $chart->render();
    $scriptEnds = strrpos($render, '</script>') + strlen('</script>');

    $chart_script = substr($render, 0, $scriptEnds);
    $chart_html = substr($render, $scriptEnds);
@endphp


@section('content')
    <div class="container">

        <div class="columns">
            <div class="column is-8">

                @include('template.resident_header', ['resident' => $resident, 'editable' => true])

            </div>
            <div class="column is-4">
                <a class="button is-outlined is-primary" href="{{url('/resident/select/'.$resident->_id)}}">{{__('mycare.resident_view')}}</a>

            </div>
        </div>

        <div class="columns">
            <div class="column">
                 {!! $chart_html !!}               
            </div>

        </div>




    </div>
@endsection


@section('script')
 {!! $chart_script !!}      
@endsection