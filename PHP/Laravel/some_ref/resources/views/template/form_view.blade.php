@foreach($controls as $cnt)

    @if($cnt->type == 'date' || $cnt->type == 'time' || $cnt->type == 'number' || $cnt->type == 'memo'
        || $cnt->type == 'text')
        <div class="field">
            <label>{{$cnt->qn}}. {{$cnt->question}}</label>
            <p class="contorl">
                @if(array_get($data, $cnt->code)!=null)
                    {{array_get($data, $cnt->code)}}
                @else
                N/A
                @endif
            </p>
        </div>
   
    

    @elseif($cnt->type == 'radio' || $cnt->type == 'checkbox')
        <div class="field">
            <label>{{$cnt->qn}}. {{$cnt->question}}</label>
            <p class="control">
            @foreach($cnt->fields as $fld)
                @if(array_get($data, $cnt->code)==$fld['code'])
                {{$fld['text']}}
                @endif
            @endforeach
            </p>
        </div>
    @endif
@endforeach