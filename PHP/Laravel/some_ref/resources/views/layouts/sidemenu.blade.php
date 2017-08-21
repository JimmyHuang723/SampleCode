<div class="header-mains">
    <img src="{{URL::to('/')}}/images/logo3.png" style="height:38px">
</div>

<ul class="sidebar-nav">
    <!-- remove class nav ? -->
    <!-- <li class="sidebar-brand">
                    <a href="{{ url('home') }}"  style="background-color : #093d62;">
                        {{ config('app.name', 'MyCare') }}
                    </a>
                </li> -->
    
    @if (App\Domains\Permission::check("Spotlight")->enableRead)
    <li>
        <input class="input is-medium spotlight-search" name="name" id="spotlight" 
            placeholder="{{__('mycare.spotlight_search')}}"/>
    </li>
    @endif

    @if (App\Domains\Permission::check("Dashboard")->enableRead)
    <li>
        <a href="{{url('/dashboard')}}">
            <div class="nav-label"> <i class="fa fa-dashboard"></i> {{__('mycare.dashboard')}} </div>
        </a>
    </li>
    @endif

    @if (App\Domains\Permission::check("Residents")->enableRead)
    <li>
        <a href="{{url('resident/search1')}}">
            <div class="nav-label">
                <i class="fa fa-users"></i>
                {{trans_choice('mycare.resident',2)}} 
            </div>
        </a>
    </li>
    @endif

    @if (App\Domains\Permission::check("Care")->enableRead)
    <li>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                aria-haspopup="true" aria-expanded="false" id="dLabel">
                <div class="nav-label"><i class="fa fa-heartbeat"></i> {{__('mycare.clinical_care')}} </div>
                <span class="fa fa-angle-left"></span>
                <span class="fa fa-angle-down"></span>
            </a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                
                @if (App\Domains\Permission::check("Care Plan")->enableRead)
                <li>
                    <a href="{{url('/careplan/search')}}">
                        <div class="nav-label">
                            <i class="fa fa-calendar-check-o"></i>
                            {{__('mycare.care_plan')}} 
                        </div>
                    </a>
                </li>
                @endif
                @if (App\Domains\Permission::check("Progress notes")->enableRead)
                <li>
                    <a href="#">
                        <div class="nav-label">
                            <i class="fa fa-sticky-note-o"></i>
                            {{__('mycare.progress_notes')}} 
                        </div>
                    </a>
                </li>
                @endif
                @if (App\Domains\Permission::check("BGL Charts")->enableRead)
                <li>
                    <a href="#">
                        <div class="nav-label">
                            <i class="fa fa-line-chart"></i>
                            {{trans_choice('mycare.bgl_chart',2)}} 
                        </div>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </li>
    @endif 

    @if (App\Domains\Permission::check("Operations")->enableRead)
    <li>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
            <div class="nav-label"><i class="fa fa-briefcase"></i> {{__('mycare.clinical_operation')}} </div>
            <span class="fa fa-angle-left"></span>
            <span class="fa fa-angle-down"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            @if (App\Domains\Permission::check("Handover")->enableRead)
            <li>
                <a href="#">
                    <div class="nav-label">
                        <i class="fa fa-handshake-o"></i> 
                        {{__('mycare.handover')}} 
                    </div>
                </a>
            </li>
            @endif
            @if (App\Domains\Permission::check("Catering Dietary")->enableRead)            
            <li>
                <a href="#">
                    <div class="nav-label">
                        <i class="fa fa-cutlery"></i>
                        {{__('mycare.catering_dietary_change')}} 
                    </div>
                </a>
            </li>
            @endif
            @if (App\Domains\Permission::check("Breakfast Beverage")->enableRead)            
            <li>
                <a href="#">
                    <div class="nav-label">
                        <i class="fa fa-coffee"></i>
                        {{__('mycare.breakfast_beverage_list')}} 
                    </div>
                </a>
            </li>
            @endif
            @if (App\Domains\Permission::check("Toileting Times")->enableRead)            
            <li>
                <a href="#">
                    <div class="nav-label">
                        <i class="fa fa-bath"></i>
                        {{__('mycare.toileting_times')}} 
                    </div>
                </a>
            </li>
            @endif
            @if (App\Domains\Permission::check("Wound Review")->enableRead)            
            <li>
                <a href="#">
                    <div class="nav-label">
                        <i class="fa fa-medkit"></i>
                        {{__('mycare.wound_next_review_date')}} 
                    </div>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif 

    @if (App\Domains\Permission::check("Reports")->enableRead)
    <li>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
            <div class="nav-label"><i class="fa fa-newspaper-o"></i> {{__('mycare.clinical_report')}} </div>
            <span class="fa fa-angle-left"></span>
            <span class="fa fa-angle-down"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            @if (App\Domains\Permission::check("Assessment Matrix")->enableRead)
            <li>
                <a href="#">
                    <div class="nav-label">
                        <i class="fa fa-map-o"></i>
                        {{__('mycare.assessment_matrix')}} 
                    </div>
                </a>
            </li>
            @endif 
            @if (App\Domains\Permission::check("Care Plan Matrix")->enableRead)
            <li>
                <a href="#">
                    <div class="nav-label">
                        <i class="fa fa-th"></i>
                        {{__('mycare.care_plan_matrix')}} 
                    </div>
                </a>
            </li>
            @endif 
            @if (App\Domains\Permission::check("Incidents")->enableRead)
            <li>
                <a href="#">
                    <div class="nav-label">
                        <i class="fa fa-car"></i>
                        {{__('mycare.incidents')}} 
                    </div>
                </a>
            </li>
            @endif 
            @if (App\Domains\Permission::check("Infections")->enableRead)
            <li>
                <a href="#">
                    <div class="nav-label">
                        <i class="fa fa-bug"></i>
                        {{__('mycare.infections')}} 
                    </div>
                </a>
            </li>
            @endif 
            @if (App\Domains\Permission::check("Export")->enableRead)
            <li>
                <a href="{{url('export')}}">
                    <div class="nav-label">
                        <i class="fa fa-file-excel-o"></i>
                        {{__('mycare.export')}} 
                    </div>
                </a>
            </li>
            @endif 
        </ul>
    </li>
    @endif 
    
    {{-- <li>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
            <div class="nav-label"><i class="fa fa-check-circle-o"></i> {{trans_choice('mycare.task',2)}} </div>
            <span class="fa fa-angle-left"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="#">
                    <div class="nav-label">
                        <i class="fa fa-search"></i>
                        {{__('mycare.search')}} 
                    </div>
                </a>
            </li>
            <li>
                <a href="{{url('task/add')}}">
                    <div class="nav-label">
                        <i class="fa fa-plus"></i>
                        {{__('mycare.add_task')}} 
                    </div>
                </a>
            </li>
        </ul>
    </li> --}}
    
    @if (App\Domains\Permission::check("Admin")->enableRead)
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
            <div class="nav-label"> <i class="fa fa-cog"></i> {{__('mycare.admin_menu')}} </div>
            <span class="fa fa-angle-left"></span>
            <span class="fa fa-angle-down"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            @if (App\Domains\Permission::check("Enquiries")->enableRead)
            <li>
                <a href="{{url('inquiry/listing')}}">
                    <div class="nav-label">
                        <i class="fa fa-phone"></i>
                        {{trans_choice('mycare.inquiry',2)}} 
                    </div>
                </a>
            </li>
            @endif 
            @if (App\Domains\Permission::check("Admissions")->enableRead)
            <li>
                <a href="{{url('admission/listing')}}">
                    <div class="nav-label">
                        <i class="fa fa-edit"></i>
                        {{trans_choice('mycare.admission',2)}} 
                    </div>
                </a>
            </li>
            @endif 

            @if (App\Domains\Permission::check("Resident Movements")->enableRead)
            <li>
                <a href="{{url('residentmovement/listing')}}">
                    <div class="nav-label">
                        <i class="fa fa-car"></i>
                        {{trans_choice('mycare.resident_movement',2)}} 
                    </div>
                </a>
            </li>
            @endif 
            {{-- @if (App\Domains\Permission::check("Movements")->enableRead)
            <li>
                <a href="#">
                    <div class="nav-label"> 
                        <i class="fa fa-automobile"></i>
                        {{trans_choice('mycare.movement',2)}} 
                    </div>
                </a>
            </li>
            @endif  --}}
            @if (App\Domains\Permission::check("Facilities")->enableRead)
            <li>
                <a href="{{url('facility/show')}}">
                    <div class="nav-label">
                        <i class="fa fa-institution"></i>
                        {{trans_choice('mycare.facility',2)}} 
                    </div>
                </a>
            </li>
            @endif 
            {{-- @if (App\Domains\Permission::check("Users")->enableRead)
            <li>
                <a href="#">
                    <div class="nav-label"> 
                        <i class="fa fa-users"></i>
                        {{trans_choice('mycare.user',2)}} 
                    </div>
                </a>
            </li>
            @endif  --}}
            @if (App\Domains\Permission::check("Permissions")->enableRead)
            <li>
                <a href="{{url('permission/listing')}}">
                    <div class="nav-label"> 
                        <i class="fa fa-users"></i>
                        {{trans_choice('mycare.permission',2)}} 
                    </div>
                </a>
            </li>
            @endif 
            @if (App\Domains\Permission::check("Forms")->enableRead)
            <li>
                <a href="{{url('form/listing')}}">
                    <div class="nav-label">
                        <i class="fa fa-pencil-square-o"></i>
                        {{trans_choice('mycare.form',2)}} 
                    </div>
                </a>
            </li>
            @endif 
            @if (App\Domains\Permission::check("Workflow")->enableRead)
            <li>
                <a href="{{url('workflow?token=').env('WORKFLOW_TOKEN')}}">
                    <div class="nav-label">
                        <i class="fa fa-cogs"></i>
                        {{trans_choice('mycare.workflow',2)}} 
                    </div>
                </a>
            </li>
            @endif 
        </ul>
    </li>
    @endif 

    @if (App\Domains\Permission::check("MyHub")->enableRead)
    <li>
        <a href="{{env('URL_MYHUB')}}" target="_blank">
            <div class="nav-label"> <i class="fa fa-rocket"></i> {{__('mycare.return_to_myhub')}} </div>
        </a>
    </li>
    @endif
</ul>
@if (App\Domains\Permission::check("Facilities")->enableRead)
<p class="facility_name"><a href="{{url('/facility/show/')}}">{{array_get($_COOKIE,'FacilityName')}} </a></p>
@endif
<p class="facility_name"><a href="{{route('logout')}}"><i class="fa fa-arrow-circle-right"></i></a></p>
<p class="build_number">{{__('mycare.build')}} {{env('BUILD_NUMBER')}}</p>
