

{{$count = 1}}
<table border="1" cellspacing="0">
    <tr>
        <th>#</th>
        <th>Facility</th>
        <th>Submitted by</th>
        <th>Category</th>
        <th>Reason</th>
        <th>Description</th>
        <th>Describe</th>
        <th>State</th>

        <th>Second by</th>
        <th>Comment</th>
    </tr>
@foreach($nominations as $n)
    <tr>
        <td valign="top">{{ $count++}}</td>
        <td valign="top">{{ $n->FacilityName}}</td>
        <td valign="top">
            @if(is_array($n->User))
                {{ $n->User["Name"]}}
            @endif
        </td>
        <td valign="top">{{ $n->Category}}</td>
        <td valign="top">{{ $n->Reason}}</td>
        <td valign="top">{{ $n->Description}}</td>
        <td valign="top">{{ $n->Describe}}</td>
        <td valign="top">
            @if($n->State == 0)
            Draft
            @else
            @endif
        </td>

        <td valign="top">
            @if(is_array($n->SecondUser))
                {{ $n->SecondUser["EnterUserName"]}}
            @endif
        </td>
        <td valign="top">{{ $n->Comments}}</td>
    </tr>

@endforeach
</table>