@extends('layouts.app')


@section('style')

<link rel="stylesheet" href="{{url('css/cropper/cropper.css')}}">
<link rel="stylesheet" href="{{url('css/cropper/main.css')}}">

<style>
.cropper_button {
    
    font-size: 26px;
    text-align: center;
    text-decoration: none;
    
}
</style>
@endsection




@section('content')





    <div class="container">

        <div class="columns">
            <div class="column is-8">
                <p class="title">{{trans_choice('mycare.admission',1)}}</p>
                <p class="subtitle">{{trans_choice('mycare.facility',1)}}: {{$facility->FacilityName}}</p>
            </div>
            <div class="column is-4">
                <a class="button is-primary is-outlined" href="{{url('admission/listing')}}">{{trans_choice('mycare.admission',2)}}</a>
            </div>
        </div>

        <form role="form" method="post" action="{{url('/admission/store')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="columns">

                <div class="column is-6">

                    <div class="field">
                        <label>{{__('mycare.admission_type')}}</label>
                        <p class="control">
                            <span class="select">
                                <select name="AdmissionType">
                                    @foreach($services as $s)
                                        <option value="{{$s->_id}}">{{$s->text}}</option>
                                    @endforeach
                                </select>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.ur_number')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="URNumber" value="{{$ur}}"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.title')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="Title"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.first_name')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="first_name"
                            @if ($inquiry_detail)
                            value="{{$inquiry_detail->data['first_name']}}"
                            @endif
                            />
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.middle_name')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="middle_name"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.last_name')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="last_name"
                            @if ($inquiry_detail)
                            value="{{$inquiry_detail->data['last_name']}}"
                            @endif
                            />
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.preferred_name')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="preferred_name"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.dob')}}</label>
                        <p class="contorl">
                            <input class="input" id="datepicker" name="dob"
                            @if ($inquiry_detail)
                            value="{{$inquiry_detail->data['dob']}}"
                            @endif
                            />
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.gender')}}</label>
                        <p class="control">
                            <span class="select">
                                <select name="Gender">
                                <option value="male">{{__('mycare.male')}}</option>
                                <option value="female">{{__('mycare.female')}}</option>
                                </select>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.location')}}</label>
                        <p class="control">
                            <span class="select">
                                <select name="location">
                                @foreach($locations as $location)
                                <option value="{{$location->_id}}">{{$location->LocationNameLong}}</option>
                                @endforeach
                                </select>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.room')}}</label>
                        <p class="control">
                            <span class="select">
                                <select name="room">
                                @foreach($rooms as $room)
                                <option value="{{$room->_id}}">{{$room->RoomName}}</option>
                                @endforeach
                                </select>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.bed')}}</label>
                        <p class="control">
                            <span class="select">
                                <select name="bed">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                </select>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.medication_trolley')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="medication_trolley"/>
                        </p>
                    </div>

                    <div class="field">
                        @if ($inquiry_detail)
                        <input type="hidden" name="inquiryId" value="{{$inquiry_detail->_id}}"/>
                        @endif
                        <button class="button is-primary" >{{__('mycare.save')}}</button>
                    </div>
                </div>

                <div class="column is-6">

                    <div class="field">
                        <label>{{__('mycare.resident_photo')}}</label>
                        <p class="control">
                            <div class="field has-addons" >
                                <a class="button is-primary" onclick="event.preventDefault();onClickAddPhoto(this)">Add photo</a>
                                <label id="label_photo_uploaded" class="label" style="visibility: hidden"> {{__('mycare.photo_uploaded')}} </label>
                            </div>    
                            <input type="hidden" id="upload_hidden" name="croppedImage" />
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.photo_taken_date')}}</label>
                        <p class="contorl">
                            <input class="input datepicker" name="photo_taken_date" />
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.province')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="province"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.primary_language')}}</label>
                        <p class="control">
                            <span class="select">
                                <select name="primary_language">
                                <option value="1">English</option>
                                <option value="2">Chinese</option>
                                </select>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.secondary_language')}}</label>
                        <p class="control">
                            <span class="select">
                                <select name="secondary_language">
                                <option value="1">English</option>
                                <option value="2">Chinese</option>
                                </select>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.social_security_number')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="social_security_number"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.pension_number')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="pension_number"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.private_health_insurance')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="private_health_insurance"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.private_health_insurance_provider')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="private_health_insurance_provider"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.nominated_hospital')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="nominated_hospital"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.funeral_arrangement')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="funeral_arrangement"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.funeral_directory')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="funeral_directory"/>
                        </p>
                    </div>

                    <div class="field">
                        <label>{{__('mycare.additional_information')}}</label>
                        <p class="control">
                            <input type="text" class="input" name="additional_information"/>
                        </p>
                    </div>

                </div>
            </div>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </form>



    </div>





@endsection



<!--Modal for uploading photo.   Must put outside of main content-->
<div class="modal fade" id="upload_modal" style="background-color: rgba(224, 224, 224, 0.5)">
    <button type="button" class="cropper_button button is-primary" data-dismiss="modal">Close</button>
    @include('cropper.cropper_modal')
</div>



@section('script')

    <script>
      function onClickAddPhoto(){
        $('#upload_modal').modal();
      }

      // Callback from Cropper
      function onImgDataURLReady(imageDataURL){
        $("#label_photo_uploaded").css({ "visibility": "visible" });
      }
    </script>

    <script src="{{ url('js/cropper/common.js')}}"></script>
    <script src="{{ url('js/cropper/cropper.js')}}"></script>
    <script src="{{ url('js/cropper/main.js')}}"></script>

@endsection


