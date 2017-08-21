@extends('layouts.app')
@section('content')

    <div class="container content container-top m-t-none">
        <div class="columns">
            <div class="column is-8">
                <h1 class="content-title">{{trans_choice('mycare.available_form',2)}}</h1>
            </div>
            <div class="column is-4">
                <div class="p-0 clearfix">
                    <a href="{{url('/form/add')}}" class="button is-primary btn btn-styles btn-primary2 right">{{__('mycare.add_form')}}</a>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-8">
               <form method="get" action="{{url('form/search')}}">
					
                    <div class="">
                        
                        <ul class="list-unstyled list-inline avail-form-filter left">
                            <li>
							
                                <input class="form-control" name="formName" type="text" placeholder="{{__('mycare.enter_a_form_name')}}" value="{{array_get($params, 'form-name')}}">

                            </li>
                            <li>
                                <select name="category" class="form-control">
                                    <option value="all" @if(array_get($params, 'category')=='all') selected @endif>
                                    {{__('mycare.all')}}
                                    </option>
                                    <option value="assessment" @if(array_get($params, 'category')=='assessment') selected @endif>
                                    {{trans_choice('mycare.assessment',2)}}
                                    </option>
                                    <option value="charting" @if(array_get($params, 'category')=='charting') selected @endif>
                                    {{__('mycare.charting')}}
                                    </option>
                                    <option value="form" @if(array_get($params, 'category')=='form') selected @endif>
                                    {{trans_choice('mycare.form',2)}}
                                    </option>

                                </select> 
                            </li>
                            <li>
                                <select name="isActive" class="form-control">
                                    <option value="1" @if(array_get($params, 'state')==1) selected @endif>
                                    {{__('mycare.active')}}
                                    </option>
                                    <option value="-1" @if(array_get($params, 'state')==-1) selected @endif>
                                    {{__('mycare.all')}}
                                    </option>
                                    <option value="0" @if(array_get($params, 'state')==0) selected @endif>
                                    {{__('mycare.inactive')}}
                                    </option>
                                </select> 
                            </li>
                            <li>
                                <select name="language" class="form-control">
                                    <option value="all" @if(array_get($params, 'language')=='all') selected @endif>
                                    {{__('mycare.all')}}
                                    </option>
                                    <option value="en" @if(array_get($params, 'language')=='en') selected @endif>
                                    {{__('mycare.english')}}
                                    </option>
                                    <option value="zh" @if(array_get($params, 'language')=='zh') selected @endif>
                                    {{__('mycare.chinese')}}
                                    </option>
                                </select> 
                            </li>
                            <li>
                                <input type="submit" value="{{__('mycare.search')}}" class="button is-primary">
                            </li>
                        </ul>

					</div>	
			   </form>
                
            </div>
        </div>

        <div class="columns">

            <div class="column is-12">


                <table class="table is-striped">
                    <tr>
                        <th width="5%">{{__('mycare.code')}}</th>
                        <th>{{__('mycare.form_name')}}</th>
                        <th width="15%">{{__('mycare.category')}}</th>
                        <th width="15%">{{__('mycare.last_update')}}</th>
                        <th width="5%">&nbsp;</th>
                        <th width="5%">&nbsp;</th>
                        <th width="5%">&nbsp;</th>
                    </tr>
                    @foreach($forms as $form)
                        <tr>
                            <td>{{$form->FormID}}</td>
                            <td>
                                <a href="{{url('/form/edit/'.$form->_id)}}">{{$form->FormName}}</a>
                            </td>

                            <td>
                                @if($form->FormCategory==1)
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{trans_choice('mycare.form',1)}}
                                @elseif($form->AssessmentCategory==1)
                                    <i class="fa fa-check-square-o" aria-hidden="true"></i> {{trans_choice('mycare.assessment',1)}}
                                @elseif($form->ChartingCategory==1)
                                    <i class="fa fa-line-chart" aria-hidden="true"></i> {{trans_choice('mycare.chart',1)}}
                                @else
                                    ?
                                @endif
                            </td>
                            <td>
                                @if($form->updated_at == null)
                                    &nbsp;
                                @else
                                {{$form->updated_at->format('d-M-Y')}}
                                @endif
                            </td>
                            <td>
                                <a class="button pull-right is-inverted" href="{{url('/form/template/'.$form->_id)}}">{{__('mycare.template')}}</a>
                            </td>
                            <td>
                                <a class="button pull-right is-inverted" href="{{url('/form/preview/'.$form->_id)}}">{{__('mycare.preview')}}</a>
                            </td>
                            <td>
                                <a class="button pull-right is-inverted" href="{{url('/ccs/listq/'.$form->_id)}}">{{__('mycare.ccs')}}</a>
                            </td>
                        </tr>
					
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
