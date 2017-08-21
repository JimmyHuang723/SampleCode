@extends('layouts.app')

@section('content')
    <div class="container content">
        <h1 class="content-title">Incidents</h1>
        <div class="content-main">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox">
                        <h2 class="ibox-title">Filter</h2>
                        <div class="ibox-content">
                            <div class="clear">
                                <div class="left item_left_btm">
                                    <label class="label-control">DATE RANGE</label>
                                    <div class="btn-groups m-b-sm">
                                        <button class="btn btn-styles btn-primary3 active">Last 12 Months</button>
                                        <button class="btn btn-styles btn-success2">Last 3 Months</button>
                                        <button class="btn btn-styles btn-danger2">Last Month</button>
                                        <button class="btn btn-styles btn-primary4">Current Month</button>
                                        <button class="btn btn-styles btn-yellow">Custom Range</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-groups m-t-md m-b-sm">
                        <button class="btn btn-styles btn-primary position-r height-md m-b-sm active">All Incidents</button>
                        <button class="btn btn-styles btn-primary position-r height-md m-b-sm">All Falls</button>
                        <button class="btn btn-styles btn-primary position-r height-md m-b-sm">Falls with Injury</button>
                        <button class="btn btn-styles btn-primary position-r height-md m-b-sm">Type of Incidents</button>
                        <button class="btn btn-styles btn-primary position-r height-md m-b-sm">Location of Incidents</button>
                        <button class="btn btn-styles btn-primary position-r height-md m-b-sm">Incident by Day</button>
                        <button class="btn btn-styles btn-primary position-r height-md m-b-sm">Medication Incidents</button>
                        <button class="btn btn-styles btn-primary position-r height-md m-b-sm">Critical Incident</button>
                        <button class="btn btn-styles btn-primary position-r height-md m-b-sm">Medication Incident by Type</button>
                        <button class="btn btn-styles btn-primary position-r height-md m-b-sm">Incidents by Type</button>
                    </div>
                    <div class="ibox">
                        <h2 class="ibox-title"></h2>
                        <div class="ibox-content p-none">
                            <div class="page-list">
                                <div class="no-items">No Result</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection