<html>
<head>
    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.4.0/css/bulma.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="{{URL::to('css/style.css')}}">

    <script type="text/javascript" src="{{URL::to('js/sidebar.js')}}"></script>


    <title>@yield('title')</title>
</head>
<body>


    <div class="wrapper">
        <div class="row row-offcanvas row-offcanvas-left">
            <!-- sidebar -->
            <div class="column col-sm-2 col-xs-1 sidebar-offcanvas" id="sidebar">

                <ul class="nav" id="menu">
                   <li>
                        <a href="#" data-target="#item1" data-toggle="collapse">
                            <i class="fa fa-list"></i> <span class="collapse in hidden-xs">CRM <span class="caret"></span></span></a>
                        <ul class="nav nav-stacked collapse left-submenu" id="item1">
                            <li><a href="{{URL::to('/crm/inquiry')}}">Inquiry</a></li>
                            <li><a href="{{URL::to('/crm/waitinglist')}}">Waiting List</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" data-target="#item2" data-toggle="collapse">
                            <i class="fa fa-list"></i> <span class="collapse in hidden-xs">Resident <span class="caret"></span></span></a>
                        <ul class="nav nav-stacked collapse left-submenu" id="item2">
                            <li><a href="{{URL::to('/resident')}}">Resident</a></li>
                            <li><a href="{{URL::to('/resident/movement')}}">Movement</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" data-target="#item3" data-toggle="collapse">
                            <i class="fa fa-list"></i> <span class="collapse in hidden-xs">Facility <span class="caret"></span></span></a>
                        <ul class="nav nav-stacked collapse left-submenu" id="item3">
                            <li><a href="{{URL::to('/facility/room')}}">Room</a></li>
                            <li><a href="{{URL::to('/facility/occupancy')}}">Occupancy</a></li>
                        </ul>
                    </li>
                    </ul>
            </div>
            <!-- /sidebar -->

            <!-- main right col -->
            <div class="column col-sm-10 col-xs-11" id="main">
                <p><a href="#" data-toggle="offcanvas" onclick="toggle_sidebar()"><i class="fa fa-navicon fa-2x" ></i></a></p>
                <div class="container">
                    @yield('content')
                </div>

            </div>
            <!-- /main -->
        </div>
    </div>



</body>
</html>