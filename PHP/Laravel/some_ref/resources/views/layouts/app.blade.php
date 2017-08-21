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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- Styles -->

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{url('css/jquery.timepicker.css')}}">
    
    <!-- custom scrollbar stylesheet -->
    <link rel="stylesheet" href="{{url('css/jquery.mCustomScrollbar.css')}}"  type="text/css" media="all" >
    <link rel="stylesheet" href="{{url('css/magic-check.css')}}">
    


    <!-- pick a date -->
    <link rel="stylesheet" href="{{url('css/default.css')}}">
    <link rel="stylesheet" href="{{url('css/default.date.css')}}">

    {{-- Bootstrap 3 --}}
    <link rel="stylesheet" href="{{url('css/bootstrap-switch.css')}}"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" 
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    {{-- Froala editor --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css"/>>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_style.min.css" rel="stylesheet" type="text/css"/> 

    <link rel="stylesheet" href="{{url('css/bulma.css')}}">
    <link rel="stylesheet" href="{{url('css/bulma-docs.css')}}">
    <link rel="stylesheet" href="{{url('css/easy-autocomplete.min.css')}}">
    <link rel="stylesheet" href="{{url('css/style.css')}}">

    @yield('style')

    <!-- put the style specific to each view(xxx.blade.php) here-->

    <!-- remove the gray background of progress note view -->
    <style>

        .modal-backdrop {display:none !important;}

        .tooltipclass {background: #000; color: #fff; opacity: 9;}

        @media screen and (min-width: 768px) {
            .modal-dialog {
                width: 700px; /* New width for default modal */
            }
            .modal-sm {
                width: 350px; /* New width for small modal */
            }
        }
        @media screen and (min-width: 992px) {
            .modal-lg {
                width: 950px; /* New width for large modal */
            }
        }

    </style>
    
</head>

<body class="layout-documentation page-components">
    <div id="app">

        <!-- Side Menu -->
        <nav class="navbar navbar-inverse navbar-fixed-top left-navigation" id="sidebar-wrapper" role="navigation">
            @include ('layouts.sidemenu')
        </nav>
        <!-- End of Side Menu -->

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <!-- Top Menu -->
            <div class="navbar-styles">
                <div class="navbar navbar-default navbar-static-top" id="topbar-wrapper">

                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <!--<button type="button" class="hamburger is-open" data-toggle="offcanvas">
                            <span class="hamb-top"></span>
                            <span class="hamb-middle"></span>
                            <span class="hamb-bottom"></span>
                    </button>-->
                    <button type="button" class="hamburger-styles is-open" data-toggle="offcanvas" >
                        <span class="bars-item"></span>
                        <span class="bars-item"></span>
                        <span class="bars-item"></span>
                    </button>


                </div>

                <div class="m-l-70" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->

                    <div class="row">
                        <div class="col-md-5">
                            <!--<div class="logo">
                                <a href="{{url('/dashboard')}}"><img class="img-responsive" src="{{url('/')}}/images/logo.jpg" style="height:50px" alt=""
                                        title=""></a>
                            </div>-->
                           
                            <timer-bar>
                                <div class="time">
                                    <span class="time-day ng-binding">{{date('d-M-Y')}}</span>
                                    <span class="time-detail ng-binding">{{date('H:i')}}</span>
                                </div>
                            </timer-bar>
                        </div>
                        <div class="col-md-7">
                            <!-- Right Side Of Navbar -->
                            @include ('layouts.topmenu')
                        </div>
                    </div>

                </div>

                </div>
            </div>
            
            <!-- End of Top Menu -->

            @yield('content')

        </div>
        <!-- End of Page Content -->

        <footer class="container-fluid footer-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        {{__('mycare.sapphire_copyright')}}
                    </div>
                </div>
            </div>
        </footer>

    </div>


<!-- Scripts -->
    <script>
        window.Laravel = {!!json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
    </script>
    
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <!-- http://www.outsharked.com/imagemapster/default.aspx?what.html -->
    <script src="{{ asset('js/jquery.imagemapster.min.js') }}"></script>
    
    <!-- Must be executed here before DOM
	render-->
    <!-- custom scrollbar plugin -->
    <script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/autosize.min.js') }}"></script>
    <script src="{{ asset('js/jquery.timepicker.js') }}"></script>
    <script src="{{ asset('js/picker.js') }}"></script>
    <script src="{{ asset('js/picker.date.js') }}"></script>
    <script src="{{ asset('js/bootstrap-switch.js') }}"></script>
    <script src="{{ asset('js/jquery.easy-autocomplete.min.js') }}"></script>

    <script language="JavaScript">
        autosize(document.querySelectorAll('textarea'));

        // http://jonthornton.github.io/jquery-timepicker/
        $("#timepicker").timepicker({
            timeFormat: 'H:i',
            disableTextInput: true,
            show2400: true,
            step: 15
        });

        //       source http://amsul.ca/pickadate.js/date/
        $("#datepicker, .datepicker").pickadate({
            format: 'dd/mm/yyyy',
            formatSubmit: 'yyyy-mm-dd',
            editable: false,
            selectMonths: true,
            selectYears: 180
        });

        // http://bootstrapswitch.com/options.html
        $("[type='radioz']").bootstrapSwitch({
            size: 'small',
            onText: 'Yes',
            offText: 'No'
        });

        $("[type='checkboxz']").bootstrapSwitch({
            size: 'small',
            onColor: 'success',
        });

        var options = {
	        url: function(name) {
		        return "{{url('/resident/autocomplete?key=')}}" + name;
	        },
	        getValue: "label",
            list: {
                onClickEvent: function() {
                    var value = $("#spotlight").getSelectedItemData().id;
                    window.location="{{url('/resident/select/')}}"+"/"+value;
                }	
            }
        };

        $('#spotlight').easyAutocomplete(options);

    </script>

    <script src="http://code.highcharts.com/highcharts.js"></script>

    {{-- Froala editor --}} {{--
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>--}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>

    <!-- Include Editor JS files. -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1//js/froala_editor.pkgd.min.js"></script>

    <script>
        $(document).ready(function (e) {
            $(".alert-success").fadeOut(5000);

            $('[data-toggle="tooltip"]').tooltip()
        });
        $(function () {
            $('#froala-editor').froalaEditor({
                    toolbarInline: false,
                    charCounterCount: false,
                    toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript',
                        'superscript',
                        'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent',
                        'insertImage',
                        'insertLink', 'insertFile', 'insertVideo', 'undo', 'redo', 'html'
                    ],
                    toolbarVisibleWithoutSelection: true,

                    // Set the image upload parameter.
                    imageUploadParam: 'image_param',

                    // Set the image upload URL.
                    imageUploadURL: '/upload/image',

                    // Additional upload params.
                    imageUploadParams: {
                        id: 'lms'
                    },

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
                    if (error.code == 1) {}

                    // No link in upload response.
                    else if (error.code == 2) {}

                    // Error during image upload.
                    else if (error.code == 3) {}

                    // Parsing response failed.
                    else if (error.code == 4) {}

                    // Image too text-large.
                    else if (error.code == 5) {}

                    // Invalid image type.
                    else if (error.code == 6) {}

                    // Image can be uploaded only to same domain in IE 8 and IE 9.
                    else if (error.code == 7) {}

                    // Response contains the original server response to the request if available.
                });

        });
    </script>

    @if(url('')=='http://mycare.dev')
    <script id="fr-fek">
        try {
            (function (k) {
                localStorage.FEK = k;
                t = document.getElementById('fr-fek');
                t.parentNode.removeChild(t);
            })('gmxabvc1D7cmF-11==')
        } catch (e) {}
    </script>
    @elseif(url('')=='http://mycare-dev.sapphirecare.com')
    <script id="fr-fek">
        try {
            (function (k) {
                localStorage.FEK = k;
                t = document.getElementById('fr-fek');
                t.parentNode.removeChild(t);
            })('5tcxA-9oH-8F2ddtE4wdvwj1c1xnolA-8jG1rA-21C-16==')
        } catch (e) {}
    </script>
    @elseif(url('')=='http://mycare.sapphirecare.com')
    <script id="fr-fek">
        try {
            (function (k) {
                localStorage.FEK = k;
                t = document.getElementById('fr-fek');
                t.parentNode.removeChild(t);
            })('Ocvspj1vC3fwghqsiyB-21D-16meD3ali==')
        } catch (e) {}
    </script>
    @endif 
    
    @yield('script')
    <!-- put the script specific to each view(xxx.blade.php) here-->






</body>

</html>