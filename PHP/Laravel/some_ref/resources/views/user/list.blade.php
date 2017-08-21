{{sizeof($users)}}
<table border="1" cellspacing="0">
    <tr>
        <th>Staff Name</th>
        <th>Facility</th>
        <th>Role</th>
        <th>Nominations</th>
    </tr>
@foreach($users as $user)
    <tr>
        <td valign="top">{{$user->Fullname}}</td>
        <td valign="top">
            <table>
            @foreach($user->Facilities as $fac)
                <tr>
                    <td valign="top">{{$fac->NameLong}}</td>
                </tr>
            @endforeach
            </table>
        </td>
        <td valign="top">
            <table>
                @foreach($user->Roles as $role)
                    @if($role->roleName == 'Everyone' || $role->roleName == 'Tester')
                    @else
                    <tr>
                        <td valign="top">{{$role->roleName}}</td>
                    </tr>
                    @endif
                @endforeach
            </table>
        </td>
        <td valign="top">
            @if(sizeof($user->Nominations) > 0)
            <table border="0" cellspacing="0" cellpadding="4">
                <tr valign="top">
                    <th valign="top">Facility</th>
                    <th valign="top">Category</th>
                    <th valign="top">Nominated by</th>
                    <th valign="top">Seconded by</th>
                    <th valign="top">Comment</th>
                </tr>
                @foreach($user->Nominations as $n)
                    <tr>
                        <td valign="top">
                            {{$n->FacilityName}}
                        </td>
                        <td valign="top">
                            {{$n->Category}}
                        </td>
                        <td valign="top">
                            {{$n->Created['EnterUserName']}}
                        </td>
                        <td valign="top">
                            {{$n->SecondUser['EnterUserName']}}
                        </td>
                        <td valign="top">
                            {{$n->Comments}}
                        </td>
                    </tr>



                    {{--{{$n->Comment}}--}}
                @endforeach
            </table>
            @else
                &nbsp;
            @endif
        </td>
    </tr>

@endforeach

</table>