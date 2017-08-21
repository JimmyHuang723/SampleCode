

<table border="1">
    <tr>
        <td>Name</td>
        <td>UserName</td>
        <td>facilities</td>
        <td>roles</td>
    </tr>
@foreach ($users as $user)
    <tr>
    <td>
         {{$user->SGivenNames}}  {{$user->SSurname}}
    </td>
        <td>[{{$user->UserName}}]</td>
    <td>
        @foreach($user->facilities as $fac)
            {{$fac->NameLong}}
        @endforeach
    </td>
    <td>
        @foreach($user->roles as $role)
            {{$role->roleName}}
        @endforeach

    </td>
    </tr>
@endforeach
</table>