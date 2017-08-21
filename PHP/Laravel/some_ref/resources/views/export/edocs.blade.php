<table>
    <tr>
        <td>Title</td>
        <td>Created by</td>
    </tr>
    @foreach($edocs as $doc)
        <tr>
            <td>{{$doc->DocTitle}}</td>
            <td>{{$doc->Creator['EnterUserName']}}</td>
        </tr>
    @endforeach
</table>