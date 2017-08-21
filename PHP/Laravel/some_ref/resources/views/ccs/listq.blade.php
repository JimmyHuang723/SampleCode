@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="content">
            <h2>{{$form->FormID}} - {{$form->FormName}}</h2>

            <a class="button is-outlined is-primary" href="{{url('form/listing')}}">{{trans_choice('mycare.form',2)}}</a>
        </div>
        <table class="table">
            <tr>
                <th>#</th>
                <th>{{trans_choice('mycare.question_text',1)}}</th>
                <th>{{__('mycare.code')}}</th>
                <th>{{__('mycare.question_type')}}</th>
                <th>{{__('mycare.category')}}</th>
                <th>{{__('mycare.ccs_score')}}</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            @foreach($controls as $cnt)

                <tr>
                    <td>{{$cnt->qn}}</td>
                    <td>{{$cnt->question}}</td>
                    <td width="10%">{{$cnt->code}}</td>
                    <td>{{$cnt->type}}</td>
                    <td>{{array_get($ccs, $cnt->code)['category']}}</td>
                    <td>{{array_get($ccs, $cnt->code)['score']}}</td>
                    @if($cnt->type=='radio' || $cnt->type=='checkbox')
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        @else
                        <td><a href="{{url('ccs/edit/'.$form->_id.'/'.$cnt->code)}}"><i class="fa fa-edit"></i></a></td>
                        <td><a href="{{url('ccs/delete/'.$form->_id.'/'.$cnt->code)}}"><i class="fa fa-remove"></i></a></td>
                    @endif
                </tr>

                @if($cnt->type=='radio' || $cnt->type=='checkbox')
                    @foreach($cnt->fields as $fld)
                        <tr>
                            <td>&nbsp;</td>
                            <td>{{$fld['text']}}</td>
                            <td><span class="is-small">{{$fld['code']}}</span></td>
                            <td>&nbsp;</td>
                            <td>{{array_get($ccs, $fld['code'])['category']}}</td>
                            <td>{{array_get($ccs, $fld['code'])['score']}}</td>
                            <td><a href="{{url('ccs/edit/'.$form->_id.'/'.$fld['code'])}}"><i class="fa fa-edit"></i></a></td>
                            <td><a href="{{url('ccs/delete/'.$form->_id.'/'.$fld['code'])}}"><i class="fa fa-remove"></i></a></td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </table>

    </div>

@endsection
