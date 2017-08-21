@extends('layouts.app')

@section('content')
    <div class="container container-top">

        <div class="columns">
            <div class="column is-8">
                @include('template.resident_header', ['resident' => $resident])
            </div>
            <div class="column is-4">
                <a class="button is-outlined is-primary" href="{{url('/resident/select/'.$resident->_id)}}">{{__('mycare.resident_view')}}</a>
                @if(isset($taskId))
                    <a class="button is-outlined is-primary" href="{{url('task/goback/'.$taskId)}}">{{__('mycare.return')}}</a>
               
                @endif
            </div>
        </div>
        <div class="columns">


            <div class="column is-8">


                @if(isset($selectedForm))
                    <div class="block">
                        <h2 class="title">{{$selectedForm->FormName}}</h2>
                    </div>
                    <form method="post" action="{{url('/assessment/store')}}">

                        {{ csrf_field() }}

                        @if($use_template_file)
                            @include($selectedForm->template, ['data' => $data, 
                                'resident' => $resident ])
                        @else
                            @include('template.form_controls', ['controls' => $controls, 'data' => $data])
                        @endif

                        @include('template.form_state')

                        <div class="field is-grouped">
                            <p class="control">
                                <button class="button is-primary"
                                        onclick='$("input[name=FormState][value=\"1\"]").attr("checked", "checked");'>{{__('mycare.save_and_complete')}}</button>
                            </p>
                            @if($FormState==0)
                            <p class="control">
                                <button class="button is-primary"
                                        onclick='$("input[name=FormState][value=\"0\"]").attr("checked", "checked");'>{{__('mycare.save_only')}}</button>
                            </p>
                            @endif
                            <p class="control">
                                <a class="button is-link" href="{{url('resident/select/'.$resident->_id)}}">{{__('mycare.cancel')}}</a>
                            </p>
                        </div>

                        <input type="hidden" name="residentId" value="{{$resident->_id}}"/>
                        <input type="hidden" name="formName" value="{{$selectedForm->FormName}}"/>
                        <input type="hidden" name="formId" value="{{$selectedForm->_id}}"/>
                        <input type="hidden" name="facilityId" value="{{$facility->_id}}"/>
                        <input type="hidden" name="parentAssessmentId" value="{{$parentAssessmentId}}"/>
                        @if(isset($taskId))
                        <input type="hidden" name="taskId" value="{{$taskId}}"/>
                        @endif
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
