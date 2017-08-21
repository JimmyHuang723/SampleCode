@php
$count = 1;
@endphp
<table border="1" cellspacing="0">
    <ttr>
        <th>#</th>
        <th>Facility</th>
        <th>Location</th>
        <th>Room</th>
        <th>Resident</th>
        <th>Q12.2</th>
        <th>Q12.3</th>
        <th>Q12.4a</th>
        <th>Q12.5</th>
        <th>Q12.8</th>
        <th>Q12.9</th>
        <th>Q12.13</th>
        <th>Q12.15</th>
    </ttr>
    @foreach($results as $r)
        @if(($r->q12_2 == "true") ||
            ($r->q12_3 == "true") ||
            ($r->q12_4a == "true") ||
            ($r->q12_5 == "true") ||
            ($r->q12_8 == "true") ||
            ($r->q12_9 == "true") ||
            ($r->q12_13 == "true") ||
            ($r->q12_15 == "true") )
            <tr>
                <td>{{$count++}}</td>
                <td>{{$r->FacilityName}}</td>
                <td>{{$r->LocationName}}</td>
                <td>{{$r->Resident->Room}} </td>
                <td>{{$r->Resident->NameFirst . ' ' . $r->Resident->NameLast}}</td>
                <td>
                    @if($r->q12_2 == "true") 1 @else &nbsp; @endif
                </td>
                <td>
                    @if($r->q12_3 == "true") 1 @else &nbsp; @endif
                </td>
                <td>
                    @if($r->q12_4a == "true") 1 @else &nbsp; @endif
                </td>
                <td>
                    @if($r->q12_5 == "true") 1 @else &nbsp; @endif
                </td>
                <td>
                    @if($r->q12_8 == "true") 1 @else &nbsp; @endif
                </td>
                <td>
                    @if($r->q12_9 == "true") 1 @else &nbsp; @endif
                </td>
                <td>
                    @if($r->q12_13 == "true") 1 @else &nbsp; @endif
                </td>
                <td>
                    @if($r->q12_15 == "true") 1 @else &nbsp; @endif
                </td>
            </tr>
        @endif
    @endforeach
</table>

