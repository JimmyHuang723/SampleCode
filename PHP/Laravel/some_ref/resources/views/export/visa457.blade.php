<table>
    <tr>
        <td>Facility</td>
        <td>Last name</td>
        <td>First name</td>
        <td>Country</td>
        <td>DOB</td>
        <td>Passport #</td>
        <td>Visa class</td>
        <td>Date Checked</td>
    </tr>
    @foreach($visa as $doc)
        <tr>
            <td>{{$doc->FacilityName}}</td>
            <td>{{$doc->SSurname}}</td>
            <td>{{$doc->SGivenNames}}</td>
            <td>{{$doc->Country}}</td>
            <td>{{$doc->SDOB}}</td>
            <td>{{$doc->Passport}}</td>
            <td>{{$doc->VisaClass}}</td>
            @if(isset($doc->DateChecked))
            <td>{{$doc->DateChecked->toDateTime()->format('d-m-Y')}}</td>
            @else
                <td>&nbsp;</td>
            @endif
        </tr>
    @endforeach
</table>