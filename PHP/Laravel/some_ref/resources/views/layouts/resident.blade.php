<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SapphireHub') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    {{--<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">--}}

    {{--<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>--}}

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- Styles -->

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
   
    <link rel="stylesheet" href="{{url('css/jquery.timepicker.css')}}">
    <link rel="stylesheet" href="{{url('css/bulma.css')}}">
    <link rel="stylesheet" href="{{url('css/bulma-docs.css')}}">
   <link rel="stylesheet" href="{{url('css/magic-check.css')}}">
    <link rel="stylesheet" href="{{url('css/style.css')}}">
   

    <!-- pick a date -->
    <link rel="stylesheet" href="{{url('css/default.css')}}">
    <link rel="stylesheet" href="{{url('css/default.date.css')}}">


    <link rel="stylesheet" href="{{url('css/bootstrap-switch.css')}}">
    


    {{-- Froala editor --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_style.min.css" rel="stylesheet" type="text/css" />

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>







</head>




<body class="layout-documentation page-components">

    <div id="app">



        <!-- Page Content -->
        <div id="content-wrapper" >

            <!-- Top Menu -->
            <div class="navbar navbar-default navbar-static-top" id="topbar-wrapper">



                    <div class="" id="app-navbar-collapse" >
                        <!-- Left Side Of Navbar -->
                        <!--
                        <ul class="nav navbar-nav" >
                            &nbsp;
                        </ul>
                        -->

                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav" id ="topbar-right" >
                            <!-- Authentication Links -->
                            @if (Auth::guest())
                                <li><a href="{{ route('login') }}">Login</a></li>
                                {{--<li><a href="{{ route('register') }}">Register</a></li>--}}
                            @else


                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        {{trans_choice('mycare.language',2)}} <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{url('home/en')}}">English</a></li>
                                        <li><a href="{{url('home/zh')}}">中文</a></li>
                                    </ul>
                                </li>

                                <!--<li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                                {{__('mycare.logout')}}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                    </ul>
                                </li>-->
                                <li class="dropdown signout">
                                    <i class="fa fa-sign-out"></i>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                        {{ Auth::user()->name }}
                                    </a>
                                </li>


                            @endif
                        </ul>
                    </div>

            </div>
            <!-- End of Top Menu -->

          
            @yield('content')
         
        </div>
        <!-- End of Page Content -->

    </div>



    <!-- Scripts -->
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>--}}
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ asset('js/app.js') }}"></script>
  
    <script src="{{ asset('js/autosize.min.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/jquery.timepicker.js') }}"></script>
    <script src="{{ asset('js/picker.js') }}"></script>
    <script src="{{ asset('js/picker.date.js') }}"></script>
    <script src="{{ asset('js/bootstrap-switch.js') }}"></script>

    <script language="JavaScript">

        autosize(document.querySelectorAll('textarea'));

        // http://jonthornton.github.io/jquery-timepicker/
        $("#timepicker").timepicker({
            timeFormat: 'H:i',
            disableTextInput:true,
            show2400:true,
            step:15
        });

//       source http://amsul.ca/pickadate.js/date/
        $( "#datepicker" ).pickadate({
            format: 'dd/mm/yyyy',
            formatSubmit: 'yyyy-mm-dd',
            editable: false,
            selectMonths: true,
            selectYears: 50
        });

        // http://bootstrapswitch.com/options.html
        $(".radio-switch").bootstrapSwitch({
            size: 'small',
            onText: 'Yes',
            offText:'No'
        });

        $(".checkbox-switch").bootstrapSwitch({
            size: 'small',
            onColor: 'success',
        });

    </script>



    {{-- Froala editor --}}
    {{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>--}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>

    <!-- Include Editor JS files. -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1//js/froala_editor.pkgd.min.js"></script>

    <script> 
    $(document).ready(function(e) {
        $(".alert-success").fadeOut(5000);

        $('[data-toggle="tooltip"]').tooltip()
    });
    $(function() { $('#froala-editor').froalaEditor({
            toolbarInline: false,
            charCounterCount: false,
            toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript',
                'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', 'insertImage',
                'insertLink', 'insertFile', 'insertVideo', 'undo', 'redo', 'html'],
            toolbarVisibleWithoutSelection: true,

            // Set the image upload parameter.
            imageUploadParam: 'image_param',

            // Set the image upload URL.
            imageUploadURL: '/upload/image',

            // Additional upload params.
            imageUploadParams: {id: 'lms'},

            // Set request type.
            imageUploadMethod: 'POST',

            // Set max image size to 5MB.
            imageMaxSize: 5 * 1024 * 1024,

            // Allow to upload PNG and JPG.
            imageAllowedTypes: ['jpeg', 'jpg', 'png']
        })
            .on('froalaEditor.image.beforeUpload', function (e, editor, images) {
                // Return false if you want to stop the image upload.
                console.log('beforeUpload');
            })
            .on('froalaEditor.image.uploaded', function (e, editor, response) {
                // Image was uploaded to the server.
                console.log('uploaded');

            })
            .on('froalaEditor.image.inserted', function (e, editor, $img, response) {
                // Image was inserted in the editor.
                console.log('inserted');
            })
            .on('froalaEditor.image.replaced', function (e, editor, $img, response) {
                // Image was replaced in the editor.
                console.log('replaced');
            })
            .on('froalaEditor.image.error', function (e, editor, error, response) {
                console.log('error' + error.code);
                // Bad link.
                if (error.code == 1) {  }

                // No link in upload response.
                else if (error.code == 2) {  }

                // Error during image upload.
                else if (error.code == 3) {  }

                // Parsing response failed.
                else if (error.code == 4) {  }

                // Image too text-large.
                else if (error.code == 5) {  }

                // Invalid image type.
                else if (error.code == 6) { }

                // Image can be uploaded only to same domain in IE 8 and IE 9.
                else if (error.code == 7) { }

                // Response contains the original server response to the request if available.
            });

        }); </script>

    @if(url('')=='http://mycare.dev')
        <script id="fr-fek">try{(function (k){localStorage.FEK=k;t=document.getElementById('fr-fek');t.parentNode.removeChild(t);})('gmxabvc1D7cmF-11==')}catch(e){}</script>
    @elseif(url('')=='http://mycare-dev.sapphirecare.com')
        <script id="fr-fek">try{(function (k){localStorage.FEK=k;t=document.getElementById('fr-fek');t.parentNode.removeChild(t);})('5tcxA-9oH-8F2ddtE4wdvwj1c1xnolA-8jG1rA-21C-16==')}catch(e){}</script>
    @elseif(url('')=='http://mycare.sapphirecare.com')
        <script id="fr-fek">try{(function (k){localStorage.FEK=k;t=document.getElementById('fr-fek');t.parentNode.removeChild(t);})('Ocvspj1vC3fwghqsiyB-21D-16meD3ali==')}catch(e){}</script>
    @endif


</body>
</html>
