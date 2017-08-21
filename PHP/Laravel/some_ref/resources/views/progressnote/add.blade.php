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

            <div class="column is-6">
                <div class="block">
                    <h2 class="subtitle">{{__('mycare.new_progressnote')}}</h2>
                </div>

                <form method="post" action="{{url('/progressnote/store')}}">

                    {{ csrf_field() }}

                    <div class="field">
                        <p class="control">
                        <textarea id="froala-editor" class="textarea" name="notes"
                              required></textarea>
                        </p>
                    </div>

                    <div class="panel">
                        <p class="panel-block">
                            <input type="checkbox" value="true" name="handover_flag"/> {{__('mycare.handover_flag')}}
                        </p>

                    </div>

                    <div class="field is-grouped">
                        <p class="control">
                            <button class="button is-primary">{{__('mycare.save')}}</button>
                        </p>
                        <p class="control">
                            <a class="button is-link" href="{{url('resident/select/'.$resident->_id)}}">Cancel</a>
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



            <div class="column is-6">
                <div class="block">
                    <h2 class="subtitle">{{__('mycare.last_5_progressnotes')}}</h2>
                </div>

                <table class="table is-striped">
                    <tr>
                        <th>&nbsp;</th>
                        <th width="70%">{{__('mycare.notes')}}</th>
                        <th>{{__('mycare.created_at')}}</th>
                    </tr>
                    @foreach($pnotes as $pn)
                    <tr>
                        <td>
                            @if($pn->handover_flag)
                                <i class="fa fa-bell"></i>
                            @else
                                &nbsp;
                            @endif
                        </td>
                        <td>{!! $pn->notes !!}</td>
                        <td>{{$pn->CreatedDate}}</td>
                    </tr>
                    @endforeach
                </table>


            </div>

            

        </div>

    </div>
@endsection
