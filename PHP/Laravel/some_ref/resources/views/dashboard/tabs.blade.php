<div class="content">
    <!--<h2>{{__('mycare.last_24hr')}}</h2>-->
    <h1 class="content-title">{{__('mycare.last_24hr')}}</h1>
</div>
@if(Request::is('dashboard/res_incident') || Request::is('dashboard'))
    <a class="button is-primary btn btn-styles btn-primary2"  href="{{url('/dashboard/res_incident')}}">{{array_get($stats,'resident-incident')}} {{__('mycare.resident_incident_last24hr')}}</a>
@else
    <a class="button is-primary is-outlined btn btn-styles btn-primary"  href="{{url('/dashboard/res_incident')}}">{{array_get($stats,'resident-incident')}} {{__('mycare.resident_incident_last24hr')}}</a>
@endif

@if(Request::is('dashboard/med_incident'))
    <a class="button is-primary btn btn-styles btn-primary2"  href="{{url('/dashboard/med_incident')}}">{{array_get($stats,'med-incident')}} {{__('mycare.medication_incident_last24hr')}}</a>
@else
    <a class="button is-primary is-outlined btn btn-styles btn-primary"  href="{{url('/dashboard/med_incident')}}">{{array_get($stats,'med-incident')}}  {{__('mycare.medication_incident_last24hr')}}</a>
@endif

@if(Request::is('dashboard/infection'))
    <a class="button is-primary btn btn-styles btn-primary2"  href="{{url('/dashboard/infection')}}">{{array_get($stats,'infection')}} {{__('mycare.infection_last24hr')}}</a>
@else
    <a class="button is-primary is-outlined btn btn-styles btn-primary"  href="{{url('/dashboard/infection')}}">{{array_get($stats,'infection')}} {{__('mycare.infection_last24hr')}}</a>
@endif

@if(Request::is('dashboard/bowel'))
    <a class="button is-primary btn btn-styles btn-primary2"  href="{{url('/dashboard/bowel')}}">{{array_get($stats,'bowel-alert')}} {{__('mycare.bowel_alert')}}</a>
@else
    <a class="button is-primary is-outlined btn btn-styles btn-primary"  href="{{url('/dashboard/bowel')}}">{{array_get($stats,'bowel-alert')}} {{__('mycare.bowel_alert')}}</a>
@endif

@if(Request::is('dashboard/bgl'))
    <a class="button is-primary btn btn-styles btn-primary2"  href="{{url('/dashboard/bgl')}}">{{array_get($stats,'bgl-alert')}} {{__('mycare.bgl_alert')}}</a>
@else
    <a class="button is-primary is-outlined btn btn-styles btn-primary"  href="{{url('/dashboard/bgl')}}">{{array_get($stats,'bgl-alert')}} {{__('mycare.bgl_alert')}}</a>
@endif

