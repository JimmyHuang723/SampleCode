<table>
    <tr>
        <td>Title</td>
        <td>Created by</td>
    </tr>
    @foreach($documents as $doc)
        <tr>
            <td>{{$doc->DocTitle}}</td>
            <td>{{$doc->CreateUserName}}</td>
        </tr>
    @endforeach
</table>