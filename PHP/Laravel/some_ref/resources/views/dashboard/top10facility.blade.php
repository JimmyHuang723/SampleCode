@extends('layouts.app')

@section('content')
    <div class="container content">
        <h1 class="content-title">Top 10 for Facility</h1>
        <div class="content-main">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox">
                        <h2 class="ibox-title">Filter</h2>
                        <div class="ibox-content">
                            <div class="clear">
                                <div class="left item_left_btm">
                                    <label class="label-control">DATE</label>
                                    <input type="text" class="form-control min-Width" placeholder="Start and end date" value="2017/03/01 - 2017/06/01">
                                </div>
                                <div class="left item_left_btm m-l-15">
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
                            </div>
                        </div>
                    </div>
                    <div class="btn-groups m-t-md m-b-md">
                        <button class="btn btn-styles btn-primary position-r height-md active">Risk Of Fall By Facility</button>
                        <button class="btn btn-styles btn-primary position-r height-md">Highest Risk Of Medication By Facility</button>
                        <button class="btn btn-styles btn-primary position-r height-md">Weight Loss By Facility</button>
                    </div>
                    <div class="ibox">
                        <h2 class="ibox-title"></h2>
                        <div class="ibox-content p-none">
                            <div class="page-list">
                                <div class="no-items">No Data</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection