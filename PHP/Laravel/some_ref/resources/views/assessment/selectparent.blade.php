@extends('layouts.app')

@section('content')
<div class="container container-top">
    <div class="columns">
        <div class="column is-8">
            @include('template.resident_header', ['resident' => $resident])
        </div>
        <div class="column is-4">
            <a class="button is-outlined is-primary" href="{{url('/resident/select/'.$resident->_id)}}">{{__('mycare.resident_view')}}</a>
            <a class="button is-primary is-outlined" href="{{url('/assessment/select/'.$resident->_id.'/'.$form->_id)}}">{{__('mycare.add')}} {{$form->FormName}}</a>
        </div>
    </div>

    <div class="columns">
        <div class="column">
            <p class="title">{{__('mycare.select')}} {{$form->FormName}}</p>
        </div>
    </div>
            
     {{-- loop through each assessment --}}
    @foreach($responses as $resp)
        @php
                $data = $resp->data;
        @endphp

        <div class="columns">
            <div class="column is-10">
                @if($form->template_json == null)
                        @include($form->template, ['data'=>$data, "view_mode" => 'list'])
                    @else
                        @include('template.view_assessment', ['form' => $form, 'assessment' => $resp])
                @endif
            </div>
            <div class="column is-2">
                <a href="{{url('/assessment/confirmparent/'.$resident->_id.'/'.$resp->_id.'/'.$childForm->_id)}}"><i class="fa fa-magic fa-5x"></i> {{__('mycare.select')}}</a>
            </div>
        </div>
    @endforeach
        
</div>
@endsection