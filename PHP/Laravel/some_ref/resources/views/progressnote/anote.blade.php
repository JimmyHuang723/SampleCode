
<div class="column ">
    <form method="post" action="{{url('/progressnote/additional_note/'.$pnId)}}">

        {{ csrf_field() }}

        <div class="field">
            <p class="control">
                        <textarea id="_froala-editor" class="textarea" name="notes"
                                  required></textarea>
            </p>
        </div>


        <div class="field is-grouped">
            <p class="control">
                <button class="button is-primary">{{__('mycare.save')}}</button>
            </p>
            <p class="control">
                {{--                            <a class="button is-link" href="{{url('progressnote/search/'.$resident->_id)}}">Cancel</a>--}}
                <button type="button" class="button " data-dismiss="modal">Cancel</button>
            </p>
        </div>

        <input type="hidden" name="residentId" value="{{$resident->_id}}"/>
        <input type="hidden" name="pnId" value="{{$pnId}}"/>

    </form>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
</div>
