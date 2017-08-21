<table>
    <tr>
        <td>ID</td>
        <td>Form</td>
        <td>Has template</td>
    </tr>
    @foreach($forms as $form)
        <tr>
            <td>{{$form->FormID}}</td>
            <td>{{$form->FormName}}</td>
            <td>@if(strlen($form->template)>100)Y @else &nbsp; @endif</td>
        </tr>
    @endforeach
</table>