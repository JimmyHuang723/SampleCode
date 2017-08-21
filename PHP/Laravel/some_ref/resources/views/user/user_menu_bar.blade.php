<div class="columns">
    <div class="column">
        <a class="button is-primary" href="{{url('/user/link_to_hub/'.$user->id)}}">{{__('mycare.link_to_hub_user')}}</a>
        <a class="button is-primary" href="{{url('/user/create_role/'.$user->id)}}">{{__('mycare.create_role')}}</a>
        <a class="button is-primary" href="{{url('/user/assign_role/'.$user->id)}}">{{__('mycare.assign_role')}}</a>
        <a class="button is-primary" href="{{url('/user/assign_facility/'.$user->id)}}">{{__('mycare.assign_facility')}}</a>
    </div>
</div>