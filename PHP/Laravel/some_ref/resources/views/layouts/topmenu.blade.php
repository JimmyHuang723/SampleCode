<ul class="nav navbar-nav" id="topbar-right">
    <!-- Authentication Links -->
    @if (Auth::guest())
    <li><a href="{{ route('login') }}">Login</a></li>
    {{--
    <li><a href="{{ route('register') }}">Register</a></li>--}} @else

    <!--<li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{trans_choice('mycare.language',2)}} <span class="caret"></span>
            </a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{url('home/en')}}">English</a></li>
            <li><a href="{{url('home/zh')}}">中文</a></li>
        </ul>
    </li>-->
    <li class="dropdown list-style-none language-m-r language-styles">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            @if(App::getLocale()=="en")
                <span><img width="20" src="{{URL::to('/')}}/images/flags/australian.png"></span>
                <span class="ng-binding">English</span>
            @elseif(App::getLocale()=="zh")
                <span></span><img src="{{URL::to('/')}}/images/flags/china.png" width="25"></span>
                <span class="ng-binding">Chinese</span>
            @endif
            <span class="caret"></span>
            <!--{{trans_choice('mycare.language',2)}} <span class="caret"></span>-->
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="{{url('home/en')}}">
                    <img src="{{URL::to('/')}}/images/flags/australian.png" width="25"> English
                </a>
            </li>
            <li>
                <a href="{{url('home/zh')}}">
                    <img src="{{URL::to('/')}}/images/flags/china.png" width="25"> Chinese
                </a>
            </li>
        </ul>
    </li>
    <li class="msg">
        <a href="#" class="">
            {{-- <i class="fa fa-bell fa-2x"></i>
            <span class="msg-number">3</span> --}}
        </a>
    </li>
    <!--<li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>

        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="{{ url('/hubuser/profile') }}">
                        {{__('mycare.profile')}}
                    </a>
            </li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        {{__('mycare.logout')}}
                    </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </li>-->
    <div class="navbar-user is-clearfix">
        <div class="is-pulled-left">
            {{-- <img class="user-img" src="{{URL::to('/')}}/images/profile_small.jpg" alt=""> --}}
        </div>  
        <div class="navbar-user-right is-pulled-left">
            <li class="dropdown">
                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle inline-block">
                    {{session('username')}} 
                    <span class="caret"></span>
                </a> 
                <ul role="menu" class="dropdown-menu">
                    <li>
                        <a href="{{url('/logout')}}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                            {{__('mycare.logout')}}
                        </a> 
                        <form id="logout-form" action="{{url('/logout')}}" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="AcoKD1jlF42853yIRLNOwYKtaWLjJv2K8GH5AvsF">
                        </form>
                    </li>
                </ul>
            </li>
        </div>              
    </div>

    @endif
</ul>