@extends('layouts.app')

@section('content')
    <div class="container container-top">

        <div class="columns">
            <div class="column is-8">

                @include('template.resident_header', ['resident' => $resident])

            </div>
            <div class="column is-4">
                <a class="button is-primary is-outlined" href="{{url('/resident/select/'.$resident->_id)}}">{{__('mycare.resident_view')}}</a>

            </div>
        </div>


        <div class="columns">


            <div class="column is-half">
                <div class="block">
                    <h2 class="subtitle">{{__('mycare.edit_progressnote')}}</h2>
                </div>

                <form method="post" action="{{url('/progressnote/update/'.$pnote->_id)}}">

                    {{ csrf_field() }}

                    <div class="field">
                        <p class="control">
                        <textarea id="froala-editor" class="textarea" name="notes"
                                  required>
                            {{$pnote->notes}}
                        </textarea>
                        </p>
                    </div>

                    <div class="panel">

                        <p class="panel-block">
                            <input type="checkbox" @if($pnote->handover_flag == 'true') checked="checked" @endif value="true" name="handover_flag"/> {{__('mycare.handover_flag')}}
                        </p>

                    </div>

                    <div class="field is-grouped">
                        <p class="control">
                            <button class="button is-primary">{{__('mycare.edit')}}</button>
                        </p>
                        <p class="control">
                            <a class="button is-link" href="{{url('progressnote/search/'.$resident->_id)}}">Cancel</a>
                        </p>
                    </div>

                    <input type="hidden" name="residentId" value="{{$resident->_id}}"/>

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


        </div>

    </div>
@endsection