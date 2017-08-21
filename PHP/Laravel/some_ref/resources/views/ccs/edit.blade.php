@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="columns">
            <div class="column is-6">
                <div class="content">
                    <h2>{{$form->FormID}} {{$form->FormName}}</h2>

                </div>
            </div>
            <div class="column is-6">
                <a class="button is-outlined is-primary" href="{{url('ccs/listq/'.$form->_id)}}">{{__('mycare.ccs')}}</a>
            </div>
        </div>

        <form role="form" action="{{url('ccs/store')}}" method="post">

            {{csrf_field()}}
            <div class="columns">
                <div class="column is-6">
                    <h3 class="subtitle">{{$question['text']}}</h3>


                    <div class="field">
                        <label class="label is-pulled-left">{{__('mycare.category')}}</label>
                        <p class="control">
                            <select type="text" class="select" name="category" value="{{$question['category']}}">
                                @foreach($categories as $cat)
                                    <option value="{{$cat->code}}">{{$cat->text}}</option>
                                @endforeach
                            </select>
                        </p>

                    </div>

                    <div class="field">
                        <label class="label is-pulled-left">{{__('mycare.ccs_score')}}</label>
                        <p class="control">
                            <input type="number" class="input" name="score" value="{{$question['score']}}"
                                   step="any"
                                   onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46'/>

                        </p>
                        </div>

                    <div class="field">
                        <input class="button is-primary" type="submit" />
                    </div>

                </div>
            </div>

            <input type="hidden" name="form_id" value="{{$form->_id}}"/>
            <input type="hidden" name="code" value="{{$question['code']}}"/>

        </form>
    </div>

@endsection
