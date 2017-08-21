@extends('layouts.app')
@section('content')

@if ( session()->has('message') )
<div class="alert alert-success alert-dismissable">{{ session()->get('message') }}</div>
@endif

<div class="container content">
    <h1 class="content-title">{{$facility_detail['NameLong']}}</h1>
    <div class="ibox m-b-md">
        <div class="ibox-content">
            {{__('mycare.address')}}: {{$facility_detail['Address1']}}
            {{!empty($facility_detail['Town'])?', '.$facility_detail['Town']:''}}
            {{!empty($facility_detail['City'])?', '.$facility_detail['City']:''}}
            {{!empty($facility_detail['State'])?', '.$facility_detail['State']:''}}
            {{!empty($facility_detail['Country'])?', '.$facility_detail['Country']:''}}
            {{!empty($facility_detail['PostCode'])?', '.$facility_detail['PostCode']:''}}
        </div>
    </div>
  <div class="columns">
      <div class="column is-6">
          <!--<h1 class="title">{{__('mycare.room')}}</h1>
          <table class="table">
            <tr>
                <td>{{__('mycare.room')}}</td>
                <td>{{__('mycare.roomtype')}}</td>

                <td>&nbsp;</td>
            </tr>

            @foreach($room_list as $_room)
            <tr>
                <td>{{ $_room->RoomName }}</td>
                <td>{{ $_room->RoomType }}</td>

                <td>
                    <a href="{{url('facility/editroom/'.$facility_detail['_id'].'/'.$_room['_id'])}}" class="button is-primary">{{__('mycare.edit')}}</a>
                </td>
            </tr>
            @endforeach
          </table>-->
          <!--<a href="{{url('facility/editroom/'.$facility_detail['_id'])}}" class="button is-primary">{{__('mycare.add_room')}}</a>-->
          <div class="content">
              <div class="ibox">
                <div class="ibox-title">{{__('mycare.room')}}</div>
                <div class="ibox-content p-none">
                    <table class="table table2">
                        <thead>
                            <tr>
                                <th>{{__('mycare.room')}}</th>
                                <th>{{__('mycare.roomtype')}}</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>                       
                        <tbody>
                            @foreach($room_list as $_room)
                            <tr>
                                <td>{{ $_room->RoomName }}</td>
                                <td>{{ $_room->RoomType }}</td>
                                <td>
                                    <a href="{{url('facility/editroom/'.$facility_detail['_id'].'/'.$_room['_id'])}}" class="btn btn-styles btn-primary2 height-sm p-t-7">{{__('mycare.edit')}}</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>                        
                    </table>
                    <a href="{{url('facility/editroom/'.$facility_detail['_id'])}}" class="btn btn-styles btn-primary2 p-t-7 m-15">{{__('mycare.add_room')}}</a>
                </div>
              </div>
          </div>
      </div>
      <div class="column is-6">
              <!--<h1 class="title">{{__('mycare.location')}}</h1>
              <table class="table table-bordered">
                  <tr>
                      <td>{{__('mycare.location')}}</td>
                      <td>&nbsp;</td>
                  </tr>
                  @foreach($location_list as $_location)
                  <tr>
                      <td>{{ $_location['LocationNameLong'] }}</td>
                      <td>
                          <a href="{{url('facility/editlocation/'.$facility_detail['_id'].'/'.$_location['_id'])}}" class="button is-primary">{{__('mycare.edit')}}</a>
                      </td>
                  </tr>
                  @endforeach
              </table>
              <a href="{{url('facility/editlocation/'.$facility_detail['_id'])}}" class="button is-primary">{{__('mycare.add_area')}}</a>-->
              <div class="content">
                <div class="ibox">
                    <div class="ibox-title">{{__('mycare.location')}}</div>
                    <div class="ibox-content p-none">
                        <table class="table table2">
                            <thead>
                                <tr>
                                    <th class="table-title">{{__('mycare.location')}}</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>                       
                            <tbody>
                                @foreach($location_list as $_location)
                                <tr>
                                    <td>{{ $_location['LocationNameLong'] }}</td>
                                    <td>
                                        <a href="{{url('facility/editlocation/'.$facility_detail['_id'].'/'.$_location['_id'])}}" class="btn btn-styles btn-primary2 height-sm p-t-7">{{__('mycare.edit')}}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>                        
                        </table>
                        <a href="{{url('facility/editlocation/'.$facility_detail['_id'])}}" class="btn btn-styles btn-primary2 p-t-7 m-15">{{__('mycare.add_area')}}</a>
                    </div>
                </div>
            </div>

      </div>
  </div>


</div>



@endsection
