@extends('layouts.resident')

<div class="sing-content">
    @section('content')
    <div class="container">
        <!---->
        <div class="sing-contain-content">
			<div class="sing-contain-left">
				<ul>
					<li class="before-icon">Clinical & Analytical</li>
					<li class="before-icon">Document Management</li>
					<li class="before-icon">Quality & Safety</li>
					<li class="before-icon">Risk Management</li>
					<li class="before-icon">Social & Messaging</li>
				</ul>
				<div class="leftbg-image">
					<img src="{{URL::to('/')}}/images/bg-logo.png" alt="">
				</div>
			</div>
			<div class="sing-contain-right">
                <div class="sing-contain">
                    <h3 class="col-md-12 sign-logo">
                        <img src="{{URL::to('/')}}/images/logo2.png" alt="">
                    </h3>
                    @if(env('USE_OAUTH'))
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/oauth/login') }}">
                    @else
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                    @endif
                        {{ csrf_field() }}

                        <div class="form-group m-b-xl">
                            <li class="dropdown list-style-none">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span><img width="20" src="{{URL::to('/')}}/images/flags/australian.png"></span>
                                    <span class="ng-binding">English</span>
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
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="sing-label">USERNAME</label>

                            <div class="">
                                <input id="email"  class="form-control" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="sing-label">PASSWORD</label>

                            <div class="">
                                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--<div class="form-group">
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <div class="opt">
                                        <input class="magic-checkbox" id="c1" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="c1">Remember Me</label>
                                    </div>
                                </div>
                            </div>
                        </div>                    -->
                        <div class="form-group m-t-xl">
                            <div class="">
                                <button type="submit" class="btn btn-primary sign-in-up">
                                    Login
                                </button>
                            </div>
                            <div class="forgot-pwd">
								<a href="{{ route('password.request') }}">
                                    Forgotten your password?
                                </a>
							</div>
                        </div>
                    </form>
                </div>
			</div>
		</div>
        <!---->
    </div>
</div>
@endsection
