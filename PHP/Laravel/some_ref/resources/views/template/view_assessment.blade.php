@php
    $data = $assessment->data;
@endphp
<table class="table">
    @foreach($form->template_json as $fld)
        @php
            $code = $fld['code'];
            $question = $fld['question'];
            if(array_key_exists($code, $data))
                $ans = $data[$code];
            else
                $ans='';
            $fieldType = $fld['field_type'];
        @endphp
        <tr>
        <td width="5%">{{$fld['qn']}}</td>
        <td width="55%">{{$fld['question']['text']}}</td>
        <td width="40">
            @if($fieldType=='radio' || $fieldType=='dropdown')
                @foreach($fld['fields'] as $f)
                    @php
                        $itemCode = $code.'-'.$f['code'];
                    @endphp
                    @if($itemCode==$ans)
                    {{$f['text']}}
                    @endif
                @endforeach
            @elseif($fieldType=='checkbox')
                @php
                    $ret = [];
                @endphp
                @foreach($fld['fields'] as $f)
                    @php
                        $itemCode = $code.'-'.$f['code'];
                        $val = array_get($data, $itemCode);
                    @endphp
                    @if($val=="on")
                        @php
                            array_push($ret, $f['text']);
                        @endphp
                    @endif
                @endforeach
                {{implode(',', $ret)}}
            @else
            {{$ans}}
            @endif
        </td>
        </tr>
    @endforeach
</table>
<span class="completed_by">{{__('mycare.complete_date')}} {{$assessment->CompletedByUser}}</span>