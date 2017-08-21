@extends('layouts.app')

@section('content')


<div class="container container-top">

    <div class="columns">

        <div class="column is-4">
            <div class="card">
                <div class="card-header">
                    <p class="card-header-title"> Clinical Workflow</p>

                </div>
                <div class="card-content">
                    <p>
                        <a class="button is-inverted is-primary" href={{url("/workflow/fetch?mode=test&amp;token=").env('WORKFLOW_TOKEN')}}>Run in test mode</a>
                    </p>
                    
                    <p>
                        <a class="button is-inverted is-primary" href={{url("/workflow/fetch?mode=live&amp;token=").env('WORKFLOW_TOKEN')}}>Run in live mode</a>
                    </p>
                    <p>
                        <a class="button is-inverted is-primary" href={{url("/residentmovement/fetch?mode=test&amp;token=").env('WORKFLOW_TOKEN')}}>Process Resident Movements</a>
                    </p>
                    
                </div>
            </div>
        </div>

        <div class="column is-4">
            <div class="card">
                <div class="card-header">
                    <p class="card-header-title">IFTTN</p>

                </div>
                <div class="card-content">
                    <p>
                        <a class="button is-inverted is-primary" href={{url("/rmq/fetch?mode=test&amp;token=").env('WORKFLOW_TOKEN')}}>Run in test mode</a>
                    </p>
                    <p>
                        <a class="button is-inverted is-primary" href={{url("/rmq/fetch?mode=live&amp;token=").env('WORKFLOW_TOKEN')}}>Run in live mode</a>
                    </p>

                </div>
            </div>
        </div>

        <div class="column is-4">
            <div class="card">
                <div class="card-header">
                    <p class="card-header-title">Batch Notification</p>

                </div>
                <div class="card-content">
                    <p>
                        <a class="button is-inverted is-primary" href={{url("/batchnotify/fetch?mode=test&amp;token=").env('WORKFLOW_TOKEN')}}>Run in test mode</a>
                    </p>
                    <p>
                        <a class="button is-inverted is-primary" href={{url("/batchnotify/fetch?mode=live&amp;token=").env('WORKFLOW_TOKEN')}}>Run in live mode</a>
                    </p>

                </div>
            </div>
        </div>

        
    </div>
    <div class="columns">
        <div class="column is-12">
            <div class="message">
                <div class="message-header">
                    <p>Process result<p></p>
                </div>
                <div class="message-body">
                    mode = {{$mode}}
                    max = {{$max}}
                    handled = {{$handled}}
                </div>

            </div>

        </div>

    </div>

    <div class="columns">
        <div class="column is-12">
            <a class="button is-primary is-outlined" href="{{url('/businessrules')}}">{{__('mycare.business_rules')}}</a>
        </div>
    </div>
</div>



@endsection