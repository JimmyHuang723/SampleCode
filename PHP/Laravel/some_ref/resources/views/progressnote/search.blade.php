@extends('layouts.app')

@section('title', 'Residents')

@section('sidebar')
    @parent
@endsection

@section('content')

    @push('style')
    <style>
    .modal-backdrop {display: none !important;}
    </style>
    @endpush

@push('scripts')

<script>
    $(document).ready(function () {

        $('#memberModal').modal('show');

    });
</script>

@endpush

    <div class="container container-top">
        <div class="columns">
            <div class="column is-8">
                @include('template.resident_header', ['resident' => $resident, 'editable' => false])
            </div>
            <div class="column is-4">
                <a class="button is-outlined is-primary" href="{{url('/resident/select/'.$resident->_id)}}">{{__('mycare.resident_view')}}</a>
            </div>
        </div>
    

        <div class="columns">

            <div class="column">

                <form class="form-inline" method="post" action="{{url('/progressnote/search_result/'.$resident->_id)}}" >

                    {{csrf_field()}}

                    <legend style="margin-bottom: 10px;">Filter</legend>
                    <div class="form-group">
                        <div class='input-group '>
                            <input type='text' class="form-control" value=""  id="notes"  name="notes" placeholder="Search notes.." />

                        </div>

                    </div>
                    <div class="form-group">
                        <div class='input-group date' id='date_start'>
                            <input  type='text' class="form-control"  id="datepicker"  name="date_start" placeholder="Start Date" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>

                    </div>
                    <div class="form-group">
                     to
                    </div>
                    <div class="form-group">
                        <div class='input-group date' id='date_end'>
                            <input  type='text' class="form-control" id="datepicker" name="date_end" placeholder="End Date" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='input-group date' id='user'>
                            <input type='text' class="form-control" name="fullname" placeholder="User (Optional)" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-user"></span>
                            </span>
                        </div>
                    </div>



                    <button type="submit" class="btn btn-default">Search</button>
                </form>
            </div>


        </div>

        <div class="columns">
            <div class="column is-4">
                {{ $pnotes->links()}}
            </div>
            
        </div>
        <div class="columns">
            <div class="column ">
                
                <table class="table is-striped">
                    <tr>
                        <th>&nbsp;</th>
                        <th width="70%">{{__('mycare.notes')}}</th>
                        <th>{{__('mycare.created_at')}}</th>
                        <th >&nbsp;</th>
                    </tr>
                    @foreach($pnotes as $pn)
                        <tr>
                            <td>
                                @if($pn->handover_flag)
                                    <i class="fa fa-bell"></i>
                                @else
                                    &nbsp;
                                @endif
                            </td>
                            <td><span @if($pn->archive > 0) style="text-decoration: line-through" @else @endif>{!! $pn->notes !!}</span></td>
                            <td>{{$pn->CreatedDate}}</td>
                            <td>
{{--                                <a  data-toggle="tooltip" data-placement="top"  href="{{url('/progressnote/additional_note/'.$resident->_id.'/'.$pn->_id)}}" title="Add Additional Note"><i class="fa fa-plus"></i></a>  &nbsp;--}}
                                {{--<a href="/progressnote/edit/{{$pn->_id}}" >--}}
                                    {{--<span class="glyphicon glyphicon-pencil"></span>--}}
                                {{--</a>   &nbsp;--}}
                                {{--<span data-toggle="tooltip" data-placement="top" title="Add Additional Note"><a data-toggle="modal" data-target="#an_{{$pn->_id}}"  >--}}
                                    {{--<span class="glyphicon glyphicon-plus"></span>--}}
                                {{--</a></span>   &nbsp;--}}
                                <span data-toggle="tooltip" data-placement="top" title="View Note"><a data-toggle="modal" data-target="#{{$pn->_id}}"  >
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </a></span>   &nbsp;
                                <a  data-toggle="tooltip" data-placement="top"  title="Strike-Through" onclick="if(confirm('Are you sure you want to archive {{strip_tags($pn->notes)}} note?')){window.location.href='/progressnote/archive/{{$pn->_id}}'}" >
                                    <span class="glyphicon glyphicon-folder-close"></span>
                                </a>   &nbsp;

                                <div class="modal fade" id="{{$pn->_id}}" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content" style="height: 400px;">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h2 class="modal-title">{{strip_tags($pn->notes)}}</h2>
                                            </div>
                                            <div class="modal-body">

                                                <div id="progressnote">

                                                    <!-- Nav tabs -->
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <li role="presentation" class="active"><a href="#an{{$pn->_id}}" aria-controls="an{{$pn->_id}}" role="tab" data-toggle="tab">Notes</a></li>
                                                        <li role="presentation"><a href="#add_an{{$pn->_id}}" aria-controls="add_an{{$pn->_id}}" role="tab" data-toggle="tab">Add Additional Note</a></li>
                                                         </ul>

                                                    <!-- Tab panes -->
                                                    <div class="tab-content">
                                                        <div role="tabpanel" class="tab-pane active" id="an{{$pn->_id}}">

                                                            <table>
                                                                <tr><th width="75%">Additional Notes</th>
                                                                    <th>Create At</th></tr>
                                                                <?php
//                                                                echo '<pre>'; print_r($pn->additional_notes); echo '</pre>';
                                                                if(is_array($pn->additional_notes)){
                                                                    foreach($pn->additional_notes as $k=>$add_note){
                                                                    ?>
                                                                    <tr>
                                                                        <td>{!! $add_note['notes'] !!}</td>
                                                                        <td>{{$add_note['created_at']}}
                                                                        <?php

                                                                            $time    = microtime(true);
                                                                            $dFormat = "d-M-Y";
                                                                            $mSecs   =  $time - floor($time);
                                                                            $mSecs   =  substr($mSecs,1);

    //                                                                        echo '<br />$time ==' .$time;
    //                                                                        echo '<br />';
    //                                                                        echo '<br />' .sprintf('%s', date($dFormat));

                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php

                                                                    }
                                                                }

                                                                ?>
                                                            </table>
                                                        </div>
                                                        <div role="tabpanel" class="tab-pane" id="add_an{{$pn->_id}}">
                                                            @include('progressnote.anote', ['resident' => $resident, 'pnId' => $pn->_id ])
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="modal fade" id="an_{{$pn->_id}}" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h2 class="modal-title">Add Additional Notes</h2>
                                            </div>
                                            <div class="modal-body">
                                                @include('progressnote.anote', ['resident' => $resident, 'pnId' => $pn->_id ])
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </table>


            </div>
        </div>

    </div>


@endsection

@section('page-script')
    <script type="text/javascript">


        $(document).ready(function () {

            $('#5930dd568e2ef523080036e6').modal('show');

        });

    </script>
@stop