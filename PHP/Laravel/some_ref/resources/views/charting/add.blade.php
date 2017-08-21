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
                <typeahead src_url="/charting/findaheadForm" onselect="onSelectTypeahead" ></typeahead>
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

                <div class="column is-2">
                </div>
                    
                <div class="column is-6">
                    <div class="block">
                        <h2 class="subtitle">{{trans_choice('mycare.assessments_recent',2)}}</h2>
                    </div>
                    <table class="table">
                        <tr>
                            <th>{{trans_choice('mycare.assessment',1)}}</th>
                            <th>{{__('mycare.date_started')}}</th>
                            <th>{{__('mycare.complete_date')}}</th>
                            
                        </tr>
                        @foreach($assessments as $a)
                            <tr>
                                <td><a href="{{url('assessment/view/'.$a->_id)}}">{{$a->Form['FormName']}}</a></td>
                                <td>{{$a->created_at->format('d-M-Y')}}</td>
                                <td>
                                    {{$a->CompletedByUser}}
                                </td>
                               
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>


        <div class="columns">
            <div class="column is-1">
            </div>
            <div class="column is-1">
                {{ $forms->links() }}
            </div>
            <div class="column is-1">
            </div>
        </div>

    </div>
@endsection


@section('script')
    <script>
        function onSelectTypeahead(item){ 
            window.location = "/assessment/select/{{$resident->_id}}/"+item._id;
        }
    </script>
@endsection


