@extends('layouts.app')

@section('content')
@if ( session()->has('message') )
<div class="alert alert-success alert-dismissable">{{ session()->get('message') }}</div>
@endif
<div class="pagehead-section">
    <div class="container content">
        <div class="row">
            <div class="col-md-12">
                <h1 class="content-title">Add Task</h1>
            </div>
        </div>
    </div>
</div>
<form role="form" action="{{url('task/addtask')}}" method="post">

    <div class="container white-bg ibox m-b-md">

        <div class="columns is-mobile">
            <div class="column is-one-half-desktop m-r-35">

                <div class="field">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.title')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input is-primary" type="text" name="Title">
                            </div>
                            <span class="help-block m-none" style="color:red">{{$errors->first('Title')}}</span>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.date_started')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input is-primary datepicker" type="text" name="StartDate">
                            </div>
                            <span class="help-block m-none" style="color:red">{{$errors->first('StartDate')}}</span>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.due_date')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input is-primary" type="text" name="StopDate">
                            </div>
                            <span class="help-block m-none" style="color:red">{{$errors->first('StopDate')}}</span>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.assignedto')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="container">
                                <p class="control">
                                    <select id="AssignedTo" name="AssignedTo[]" class="chosen-select" multiple>
                                        @foreach ($users as $user)
                                        <option value="{{$user->_id}}">{{$user->SGivenNames}} {{$user->SSurname}}</option>
                                        @endforeach
                                    </select>
                                </p>
                            </div>
                            <span class="help-block m-none" style="color:red">{{$errors->first('AssignedTo')}}</span>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.location')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <select class="select" name="LocationID">
                                    @foreach ($locations as $location)
                                    <option value="{{ $location->_id }}">{{ $location->LocationNameLong }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.resident')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <select class="select" name="ResidentID">
                                    <option value=""></option>
                                    @foreach ($residents as $resident)
                                    <option value="{{ $resident->_id }}">{{ $resident->NameFirst }} {{ $resident->NameLast }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block m-none" style="color:red">{{$errors->first('ResidentID')}}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
                       
            <div class="column is-one-half-desktop">

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.task_description')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input is-primary" type="text" name="Description">
                            </div>
                            <span class="help-block m-none" style="color:red">{{$errors->first('Description')}}</span>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.due_date')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input is-primary datepicker" type="text" name="StopDate">
                            </div>
                            <span class="help-block m-none" style="color:red">{{$errors->first('StopDate')}}</span>
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.assign_facility')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <select class="select" name="FacilityID">
                                    @foreach ($facilities as $facility)
                                    <option value="{{ $facility->_id }}">{{ $facility->NameLong }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="help-block m-none" style="color:red">{{$errors->first('FacilityID')}}</span>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.form')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input is-primary" type="text" name="Form">
                            </div>
                            <span class="help-block m-none" style="color:red">{{$errors->first('Form')}}</span>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.status')}}</label>
                    </div>
                    <div class="field-body">
                       <div class="field mt-8">
                            <div class="control">
                                <div class="opt">
                                    <input class="magic-radio" name="radio" id="r1" value="option1" checked="" type="radio">
                                    <label for="r1">{{__('mycare.not_started')}}</label>
                                </div>
                                <div class="opt">
                                    <input class="magic-radio" name="radio" id="r2" value="option2" type="radio">
                                    <label for="r2">{{__('mycare.in_progress')}}</label>
                                </div>
                                <div class="opt">
                                    <input class="magic-radio" name="radio" id="r3" value="option3" type="radio">
                                    <label for="r3">{{__('mycare.done')}}</label>
                                </div>
                            </div>
                            <span class="help-block m-none" style="color:red">{{$errors->first('Status')}}</span>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="field-label is-normal">
                        <label class="label">{{__('mycare.task_description')}}</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <textarea name="Description" id="" cols="10" rows="5"></textarea>
                            </div>
                            <span class="help-block m-none" style="color:red">{{$errors->first('Description')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="faciltiy faciltiybtns">
            <div class="field is-grouped">
                <p class="control">
                    <button class="btn btn-styles btn-primary2 p-t-7" >{{__('mycare.submit')}}</button>
                </p>
            </div>
        </div>

    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('.datepicker').pickadate({
            format: 'yyyy-mm-dd'
        });
    });
</script>

<script src="http://harvesthq.github.io/chosen/chosen.jquery.js"></script>
<script>
    $(function() {
        $('#AssignedTo').chosen();
//        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
    });
</script>

@endsection


@section('style')
<link rel="stylesheet" href="/css/bootstrap-chosen/bootstrap-chosen.css" />
@endsection
