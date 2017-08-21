@extends('layouts.app')

@section('title', "{{__('mycare.careplan')}}")

@section('sidebar')
    @parent
@endsection

@section('content')

    <div class="container container-top">

        <div class="columns">
            <div class="column is-8">
                @include('template.resident_header', ['resident' => $resident])
            </div>
            <div class="column is-4">
                <a class="button is-outlined is-primary" href="{{url('/resident/select/'.$resident->_id)}}">{{__('mycare.resident_view')}}</a>
                <a class="button is-outlined is-primary" target="_blank" href="{{url('/careplan/print/'.$resident->_id)}}">{{__('mycare.print')}}</a>
            </div>
        </div>

        @foreach($careplanView->Domains() as $domain)

        <div class="columns">
            <p class="title">{{$domain}}</p>
        </div>
        <div class="columns">
                <div class="column is-4">
                    <div class="panel">
                        <p class="panel-heading">
                            {{trans_choice('mycare.goal',2)}}
                        </p>
                        <div class="content">
                        <ul>
                        @foreach($careplanView->goals as $obs)
                            @if($obs['domain']==$domain)
                                <li >{{$obs['goal']}}
                                </li>
                            @endif
                        @endforeach
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="panel">
                        <p class="panel-heading">
                            {{trans_choice('mycare.observation',2)}}
                        </p>
                        <div class="content">
                            <ul>
                                @foreach($careplanView->observations as $obs)
                                    @if($obs['domain']==$domain)
                                        <li>{{$obs['text']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="column is-4">
                    <div class="panel">
                        <p class="panel-heading">
                            {{trans_choice('mycare.intervention',2)}}
                        </p>
                        <div class="content">
                            <ul>
                                @foreach($careplanView->interventions as $obs)
                                    @if($obs['domain']==$domain)
                                        <li>{{$obs['text']}}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>

        </div>
        @endforeach

    </div>


@endsection