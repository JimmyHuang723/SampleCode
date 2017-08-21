@extends('layouts.app')

@section('content')

<div class="container container-top">

    <div class="columns">
        <div class="column is-8">
        
        </div>
        <div class="column is-4">
            <a class="button is-primary is-outlined" 
                href="{{url('/workflow?token=').env('WORKFLOW_TOKEN')}}">{{__('mycare.workflow')}}</a>
            <a class="button is-primary is-outlined" 
                href="{{url('/businessrules/add')}}">{{__('mycare.add_rule')}}</a>
        </div>
    </div>

    <div class="columns">
        <div class="column is-12">
            <table class="table">
                <tr>
                    <th width="5%">{{__('mycare.code')}}</th>
                    <th width="40%">{{__('mycare.title')}}</th>
                    <th>{{__('mycare.process')}}</th>
                    <th>{{trans_choice('mycare.form',1)}}</th>
                    <th width="5%">{{__('mycare.active')}}</th>
                    <th width="5%">{{trans_choice('mycare.rule',2)}}</th>
                    <th width="5%">&nbsp;</th>
                </tr>
                @foreach($rules as $rule)
                    <tr>
                        <td>{{$rule->Code}}</td>
                        <td>{{$rule->Title}}</td>
                        <td>{{$rule->Process}}</td>
                        <td>{{array_get($rule->Form, 'FormName')}}</td>
                        <td>@if($rule->IsActive==1)<i class="fa fa-check"></i>@endif</td>
                        <td>@if($rule->HasError)<i class="fa fa-thumbs-down" style="color:red"></i> @else <i class="fa fa-thumbs-up"></i> @endif</td>
                        <td><a href="{{url('/businessrules/edit/'.$rule->_id)}}">{{__('mycare.edit')}}</a></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

@endsection