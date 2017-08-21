@foreach($controls as $cnt)
    @if($cnt->type == 'text')
        <div class="field">
            <label>{{$cnt->qn}}. {{$cnt->question}}</label>
            @if($cnt->goal!='')<a href="#" data-toggle="tooltip" data-placement="right" title="{{$cnt->goal}}"><i class="fa fa-bullseye"></i></a>@endif
            <p class="contorl">
                <input class="input" type="text" name="{{$cnt->code}}" value="{{array_get($data, $cnt->code)}}" style="width:80%"/>
            </p>
        </div>
    @elseif($cnt->type == 'memo')
        <div class="field">
            <label>{{$cnt->qn}}. {{$cnt->question}}</label>
            <p class="contorl">
                <textarea class="textarea" id="froala-editor" name="{{$cnt->code}}">{{array_get($data, $cnt->code)}}</textarea>
            </p>
        </div>
    @elseif($cnt->type == 'number')
        <div class="field">
            <label>{{$cnt->qn}}. {{$cnt->question}}</label>
            <p class="contorl">
                <input class="input" type="number" name="{{$cnt->code}}" step="any" value="{{array_get($data, $cnt->code)}}"
                       onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46' style="width:150px"/>
            </p>
        </div>
    @elseif($cnt->type == 'date')
        <div class="field">
            <label>{{$cnt->qn}}. {{$cnt->question}}</label>
            <p class="contorl">
                <input class="input" id="datepicker" name="{{$cnt->code}}" value="{{array_get($data, $cnt->code)}}"  style="width:150px"/>
            </p>
        </div>
    @elseif($cnt->type == 'time')
        <div class="field">
            <label>{{$cnt->qn}}. {{$cnt->question}}</label>
            <p class="contorl">
                <input class="input" id="timepicker" name="{{$cnt->code}}" value="{{array_get($data, $cnt->code)}}"  style="width:150px"/>
            </p>
        </div>
    @elseif($cnt->type == 'checkbox')
        <div class="field">
            <label  class="forcheckbox">{{$cnt->qn}}. {{$cnt->question}}</label>
            <div class="checkbox">
            @foreach($cnt->fields as $fld)
                
                <label class="forcheckbox">
                    <input type="checkbox" name="{{$fld['code']}}" @if(array_get($data, $fld['code'])=='on')checked @endif />
                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                    <label class="label is-pulled-left">
                        {{$fld['text']}}
                        @if($fld['goal']!='')<a href="#" data-toggle="tooltip" data-placement="right" title="{{$fld['goal']}}"><i class="fa fa-bullseye"></i></a>@endif
                    </label>

                </label>
                
            @endforeach
            </div>
        </div>

    @elseif($cnt->type == 'radio')
        <div class="field">
            <label class="forcheckbox">{{$cnt->qn}}. {{$cnt->question}}
            </label>
            <div class="radio">
            @foreach($cnt->fields as $fld)
                <label class="forcheckbox">
                    <input type="radio" name="{{$cnt->code}}" value="{{$fld['code']}}" @if(array_get($data, $cnt->code)==$fld['code'])checked @endif />
                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                    <label class="label is-pulled-left">
                        {{$fld['text']}}
                        @if($fld['goal']!='')<a href="#" data-toggle="tooltip" data-placement="right" title="{{$fld['goal']}}"><i class="fa fa-bullseye"></i></a>@endif
                    </label>

                </label>
            @endforeach
            </div>
        </div>
    @elseif($cnt->type == 'dropdown')
        <div class="field">
            <label class="forcheckbox">{{$cnt->qn}}. {{$cnt->question}}
            </label>
            <p class="control">
                <span class="select fordropdown">
                    <select name="{{$cnt->code}}">
                    @foreach($cnt->fields as $fld)
                        <option name="{{$cnt->code}}" value="{{$fld['code']}}" @if(array_get($data, $cnt->code)==$fld['code'])selected @endif>{{$fld['text']}}</option>
                    @endforeach
                    </select>
                </span>
            </p>
                
        </div>
    @elseif($cnt->type="message")
        <div class="content">
            {!!$cnt->question!!}
        </div>
    @endif
@endforeach