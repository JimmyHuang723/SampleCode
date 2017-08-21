<link rel="stylesheet" href="{{url('css/bulma.css')}}">
<link rel="stylesheet" href="{{url('css/bulma-docs.css')}}">

<link rel="stylesheet" href="{{url('css/style.css')}}">
@foreach($forms as $form)
    <div class="content">
        <h1>{{$form->FormName}} {{$form->FormCode}}</h1>

    </div>

    <table class="table">
        <tr>
            <th>Form</th>
            <th>Code</th>
            <th>#</th>
            <th>Type</th>
            <th>Code</th>
            <th>Question</th>
            <th>Goal</th>
        </tr>
    @foreach($form->controls as $cnt)
        <tr>
            <td>{{$form->FormName}}</td>
            <td>{{$form->FormID}}</td>
            <td>{{$cnt->qn}}</td>
            <td>{{$cnt->type}}</td>
            <td>{{$cnt->code}}</td>
            <td>{{$cnt->question}}</td>
            <td>{{$cnt->goal}}</td>
        </tr>
        @if($cnt->type == 'radio' || $cnt->type == 'checkbox')
        @foreach($cnt->fields as $fld)
            <tr>
                <td>{{$form->FormName}}</td>
                <td>{{$form->FormID}}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{$fld['code']}}</td>
                <td>{{$fld['text']}}</td>
                <td>{{$fld['goal']}}</td>
            </tr>
        @endforeach
        @endif
    @endforeach
    </table>
@endforeach