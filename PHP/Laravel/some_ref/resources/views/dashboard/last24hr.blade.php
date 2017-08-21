@extends('layouts.app')

@section('content')
    <div class="container content">
        <h1 class="content-title">Alert</h1>
        <div class="content-main">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox">
                        <h2 class="ibox-title">Filter</h2>
                        <div class="ibox-content">
                            <div class="clear">
                                <div class="left item_left_btm">
                                    <label class="label-control">FACILITIES</label>
                                    <select class="form-control">
                                        <option value="">-- All Facilities --</option>
                                        <option value="1134">Brighton</option>
                                        <option value="1141">Caulfield</option>
                                        <option value="1117">Hilltop</option>
                                        <option value="1140">Oakleigh</option>
                                        <option value="1138">Ruckers Hill</option>
                                        <option value="1133">The Gables</option>
                                        <option value="1066">The Mews</option>
                                        <option value="1139">Westgarth</option>
                                    </select>
                                </div>
                                <!--<div class="left item_left_btm hidden">
                                    <label class="label-control">INCIDENT BY TYPE</label>
                                    <input type="text" class="form-control">
                                </div>-->
                                <div class="left item_left_btm">
                                    <button class="btn btn-styles btn-primary2 btn-search m-l-15 height-md">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-groups m-t-md m-b-md">
                        <button class="btn btn-styles btn-primary position-r height-md active">Resident Incidents for 24Hr<span class="nav-counter">2</span></button>
                        <button class="btn btn-styles btn-primary position-r height-md">Medication Incidents for 24Hr<span class="nav-counter">0</span></button>
                        <button class="btn btn-styles btn-primary position-r height-md">Infections for 24Hr<span class="nav-counter">0</span></button>
                        <button class="btn btn-styles btn-primary position-r height-md">Bowel Alert<span class="nav-counter">0</span></button>
                        <button class="btn btn-styles btn-primary position-r height-md">Blood Glucose Alert<span class="nav-counter">0</span></button>
                        <button class="btn btn-styles btn-primary position-r height-md">Data Anomaly<span class="nav-counter">0</span></button>
                    </div>
                    <div>
                        <div class="ibox">
                            <h2 class="ibox-title"></h2>
                            <div class="ibox-content p-none">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="sortHead">Facility</th>
                                            <th class="sortHead">Location</th>
                                            <th class="sortHead">Resident</th>
                                            <th class="sortHead">Room</th>
                                            <th class="sortHead">Date Completed</th>
                                            <th class="sortHead">Incident Type</th>
                                            <th class="sortHead">Update Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>The Gables</td>
                                            <td>GROUND Floor The Gables	</td>
                                            <td><a href="#">Julius THURZO</a></td>
                                            <td>10</td>
                                            <td>04.05.2017</td>
                                            <td>Incident Type</td>
                                            <td>17:23:45</td>
                                        </tr>
                                        <tr>
                                            <td>The Gables</td>
                                            <td>GROUND Floor The Gables	</td>
                                            <td><a href="#">Julius THURZO</a></td>
                                            <td>10</td>
                                            <td>04.05.2017</td>
                                            <td>Incident Type</td>
                                            <td>17:23:45</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="page-list">
                                    <div class="no-items">No Result</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection