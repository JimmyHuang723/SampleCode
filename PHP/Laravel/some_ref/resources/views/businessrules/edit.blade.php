@extends('layouts.app')

@section('content')

<div class="container container-top">

    <div class="columns">
        <div class="column is-8 content">
           <h2>{{__('mycare.business_rules')}}</h2>
        </div>
        <div class="column is-4">
            <a class="button is-primary is-outlined" 
                href="{{url('/businessrules')}}">{{__('mycare.business_rules')}}</a>
            <a class="button is-primary is-outlined" 
                href="{{url('/workflow?token=').env('WORKFLOW_TOKEN')}}">{{__('mycare.workflow')}}</a>
        </div>
    </div>

    <form role="form" method="post" action="{{url('/businessrules/store')}}">
        {{csrf_field()}}
        <input type="hidden" name="ruleId" value="{{$rule->_id}}"/>

    <div class="columns">
        <div class="column is-5">

            <div class="field">
                <label>{{__('mycare.code')}}</label>
                <p class="contorl">
                    <input class="input" type="text" name="code" value="{{$rule->Code}}" style="width:150px"/>
                </p>
            </div>

            <div class="field">
                <label>{{__('mycare.title')}}</label>
                <p class="contorl">
                    <input class="input" type="text" name="title" value="{{$rule->Title}}" />
                </p>
            </div>

            <div class="field">
                <label>{{__('mycare.process')}}</label>
                <p class="contorl">
                    <input class="input" type="text" name="process" value="{{$rule->Process}}" />
                </p>
            </div>

            <div class="field">
                <label>{{trans_choice('mycare.form',1)}}</label>
                <p class="contorl">
                    @if(isset($rule->Form))
                        <div><span id="formName">{{__('mycare.selected_form')}}: {{array_get($rule->Form, 'FormName')}}</span>
                            <a href="#" class="button is-primary is-outlined" onclick="clearFormSelected()">{{__('mycare.clear')}}</a>
                        </div>
                    @endif
                    <input class="input" type="text" name="_formId"   id="find_form" placeholder="{{__('mycare.select_form')}}"/>
                    <input  type="hidden" name="formId" value="{{array_get($rule->Form, 'FormId')}}"  id="formId"/>
                    
                </p>
            </div>

            <div class="field">
                <label class="forcheckbox">{{trans_choice('mycare.status',1)}}
                </label>
                <div class="radio">
                    <label class="forcheckbox">
                        <input type="radio" name="isactive" value="1" @if($rule->IsActive==1) checked @endif />
                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                        <label class="label is-pulled-left">
                            {{__('mycare.active')}}
                        </label>
                    </label>

                    <label class="forcheckbox">
                        <input type="radio" name="isactive" value="0"  @if($rule->IsActive!=1) checked @endif/>
                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                        <label class="label is-pulled-left">
                            {{__('mycare.inactive')}}
                        </label>
                    </label>
                </div>
            </div>
        </div>
        <div class="column is-7">

            <div class="field">
                <label>{{trans_choice('mycare.rule',2)}}</label>
                <p class="contorl">
                    <textarea class="textarea" style="height:auto" name="rules">{{$rule->RulesJson}}</textarea>
                </p>
            </div>

            <div class="field is-grouped">
                <p class="control">
                    <button class="button is-primary">{{__('mycare.save')}}</button>
                </p>
                <p class="control">
                    <a class="button is-link" href="{{url('/businessrules')}}">Cancel</a>
                </p>
            </div>

        </div>

        </div>

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
        
        

    </form>

    


</div>

@endsection


@section('script')

<script>
        var options = {
	        url: function(name) {
		        return "{{url('/form/autocomplete?name=')}}" + name;
	        },
	        getValue: "label",
            list: {
                onClickEvent: function() {
                    var value = $("#find_form").getSelectedItemData().id;
                    $('#formId').val(value);
                }	
            }
        };

        $('#find_form').easyAutocomplete(options);


        function clearFormSelected(){
            $('#formId').val('');
            $('#formName').empty();
        }
</script>
@endsection