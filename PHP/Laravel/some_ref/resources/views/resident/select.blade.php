@extends('layouts.app')

@section('title', 'Residents')

@section('sidebar')
    @parent
@endsection

@section('content')

<style type="text/css">
    #page-content-wrapper {
        padding-bottom: 29px;
    }
</style>

    <div class="container container_top">
        <div class="columns">
            <div class="column is-8">
                @include('template.resident_header', ['resident' => $resident, 'editable' => true])
            </div>
            <div class="column is-4">
                <div class="text-left">
                    <a href="{{url('resident/search/'.$facility->_id)}}" class="button is-outlined is-primary">{{__('mycare.search_again')}}</a>
                    {{--<a href="" class="button is-outlined is-primary">{{__('mycare.movement')}}</a>--}}
                    <a href="{{url('/careplan/view/'.$resident->_id)}}" class="button is-outlined is-primary">{{__('mycare.care_plan')}}</a>
                    <a href="{{url('resident/bgl/'.$resident->_id)}}" class="button is-outlined is-primary">{{__('mycare.bgl')}}</a>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-3">
                <ul class="list-unstyled list-inline">
                    <li><i class="fa fa-sticky-note-o" aria-hidden="true"></i> {{__('mycare.progress_notes')}}</li>
                    <li><a href="{{url('/progressnote/add/'.$resident->_id)}}"><i class="fa fa-plus"></i></a></li>
                    <li><a href="{{url('/progressnote/search/'.$resident->_id)}}"><i class="fa fa-search"></i></a></li>
                </ul>

            </div>
            <div class="column is-3">
                <ul class="list-unstyled list-inline">
                    <li><i class="fa fa-check-square-o" aria-hidden="true"></i> {{trans_choice('mycare.assessment',2)}}</li>
                    <li><a href="{{url('/assessment/add/'.$resident->_id)}}"><i class="fa fa-plus"></i></a></li>
                    <li><a href="{{url('/assessment/search/'.$resident->_id)}}"><i class="fa fa-search"></i></a></li>
                </ul>

            </div>
            <div class="column is-3">
                <ul class="list-unstyled list-inline">
                    <li><i class="fa fa-line-chart" aria-hidden="true"></i> {{trans_choice('mycare.charting',2)}}</li>
                    <li><a href="{{url('/charting/add/'.$resident->_id)}}"><i class="fa fa-plus"></i></a></li>
                    <li><a href="{{url('/charting/search/'.$resident->_id)}}"><i class="fa fa-search"></i></a></li>
                </ul>

            </div>

            <div class="column is-3">
                <ul class="list-unstyled list-inline">
                    <li><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{trans_choice('mycare.form',2)}}</li>
                    <li><a href="{{url('/residentform/add/'.$resident->_id)}}"><i class="fa fa-plus"></i></a></li>
                    <li><a href="{{url('/residentform/search/'.$resident->_id)}}"><i class="fa fa-search"></i></a></li>
                </ul>

            </div>
            
        </div>

        <!-- timelines -->
        <div class="columns">
            <div class="column">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-title">
                            {{__('mycare.timeline_chart')}}
                        </div>
                        <div class="card-header-icon">
                            <span class="icon"><a href="{{'/resident/timeline/'.$resident->_id.'/'.$selectedDay.'/-5'}}"><i class="fa fa-arrow-left"></i></a></span>
                            <span class="icon"><a href="{{'/resident/timeline/'.$resident->_id.'/'.$today.'/0'}}"><i class="fa fa-circle"></i></a></span>
                            <span class="icon"><a href="{{'/resident/timeline/'.$resident->_id.'/'.$selectedDay.'/5'}}"><i class="fa fa-arrow-right"></i></a></span>
                        </div>


                    </div>
                    <div class="content">

                        <table class="table is-bordered is-striped">
                            <tr>
                                <th>{{__('mycare.hour')}}</th>
                                @foreach($timeline_days as $d)
                                    <th width="20%">{{$d['text']}}</th>
                                @endforeach
                            </tr>
                            @foreach($timeline_hours as $h)
                                <tr>
                                    <td>{{$h['text']}}</td>
                                    @foreach($timeline_days as $g)
                                        <td>
                                            @if(array_key_exists($g['code'].'-'.$h['code'], $timeline_values))
                                                @foreach($timeline_values[$g['code'].'-'.$h['code']] as $t)
                                                    <span>
                                                      @if($t['action']=='pn')
                                                            <a href="{{$t['link']}}"><i class="fa fa-sticky-note-o"></i></a>
                                                        @elseif($t['action']=='fm')
                                                            <a href="{{$t['link']}}"><i class="fa fa-check-square-o"></i></a>
                                                        @elseif($t['action']=='cp')
                                                            <a href="{{$t['link']}}"><i class="fa fa-calendar-check-o"></i></a>
                                                        @elseif($t['action']=='ch')
                                                            <a href="{{$t['link']}}"><i class="fa fa-line-chart"></i></a>
                                                        @else
                                                            <a href="{{$t['link']}}">{{$t['text']}}</a>
                                                        @endif
                                                  </span>
                                                @endforeach
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>









    </div>

@endsection


@section('script')

    <script>
   		
		$(document).ready(function() {
			//hideSideMenu();
		});
		
    </script>

@endsection

