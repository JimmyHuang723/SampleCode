@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="block">
            <h1 class="title">{{__('mycare.edit_assessment_form')}}
                <a href="{{url('/form/add')}}" class="button is-primary">{{__('mycare.add_form')}}</a>
            </h1>

        </div>

        <div class="columns">

            <div class="column is-4">
                <div class="block">
                    <h2 class="subtitle">{{trans_choice('mycare.available_assessment',2)}}</h2>
                </div>

                <table class="table is-striped">
                    @foreach($forms as $form)
                        <tr>
                            <td><a href="{{url('/form/edit/'.$form->_id)}}">{{$form->FormID}} {{$form->FormName}}</a>
                            <a class="button pull-right is-info" href="{{url('/assessment/edit/'.$form->_id)}}">{{__('mycare.edit')}}</a>
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>

            <div class="column is-8">



            </div>


        </div>

    </div>
@endsection
