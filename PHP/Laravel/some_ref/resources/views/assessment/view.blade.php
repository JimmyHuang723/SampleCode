@extends('layouts.app')

@section('content')
    <div class="container container_top">

        <div class="columns">
            <div class="column is-8">

                @include('template.resident_header', ['resident' => $resident, 'editable' => false])

            </div>
            <div class="column is-4">
                <a class="button is-outlined is-primary" href="{{url('/resident/select/'.$resident->_id)}}">{{__('mycare.resident_view')}}</a>
                <a class="button is-outlined is-primary" href="{{url('/assessment/search/'.$resident->_id)}}">{{trans_choice('mycare.resident_assessment',2)}}</a>
                <a class="button is-outlined is-primary" href="{{url('/charting/search/'.$resident->_id)}}">{{trans_choice('mycare.chart',2)}}</a>

            </div>
        </div>
    
        <div class="columns">
            <div class="column is-8">
                @if(isset($selectedForm))
                    <div class="block">
                        <h2 class="title">{{$selectedForm->FormName}}</h2>
                    </div>

                        @if($use_template_file)
                            @include($selectedForm->template, ['data' => $assessment->data])
                        @else
                            @include('template.view_assessment', ['form' => $selectedForm, 'assessment' => $assessment])
                        @endif

                        @include('template.form_state', ['FormState' => $FormState])

                        <div class="field is-grouped">
                           
                            <p class="control">
                                <a class="button is-link" href="#" onclick="window.history.back();">{{__('mycare.go_back')}}</a>
                            </p>
                        </div>

                        <input type="hidden" name="residentId" value="{{$resident->_id}}"/>
                        <input type="hidden" name="formName" value="{{$selectedForm->FormName}}"/>
                        <input type="hidden" name="formId" value="{{$selectedForm->_id}}"/>
                        <input type="hidden" name="facilityId" value="{{$facility->_id}}"/>
                        <input type="hidden" name="assessmentId" value="{{$assessment->_id}}"/>
                        @if(isset($taskId))
                            <input type="hidden" name="taskId" value="{{$taskId}}"/>
                        @endif
                    
                @else
                    <p class="subtitle">{{__('mycare.select_assessment_form')}}</p>
                @endif
            </div>

            <div class="column is-4">
                @if($parentAssessment != null && $parentForm != null)
                    <span class="subtitle">{{$parentForm->FormName}}</span>
                    @if($parentForm->template_json== null)
                        @include($parentForm->template, ['data'=> $parentAssessment->data]);
                    @else
                        @include('template.view_assessment', ['form' => $parentForm, 'assessment' => $parentAssessment])
                    @endif
                @endif
            </div>


        </div>

    </div>
@endsection