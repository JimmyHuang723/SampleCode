<div class="panel is-hidden">
    <p class="panel-heading">{{__('mycare.form_state')}} </p>

    <p class="panel-block is-paddingless">
        <label class="label is-pulled-left">
            <input type="radio" name="FormState" value="1" @if($FormState==1) checked @endif>
            {{__('mycare.complete')}}
        </label>
    </p>
    <p class="panel-block is-paddingless">
        <label class="label is-pulled-left">
            <input type="radio" name="FormState" value="0" @if($FormState==0) checked @endif>
            {{__('mycare.in_progress')}}
        </label>
    </p>
</div>