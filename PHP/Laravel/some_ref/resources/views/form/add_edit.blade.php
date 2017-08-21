@extends('layouts.app')

@section('content')
    <div class="container container-top">

        <div class="columns">
            <div class="column">
                <h1 class="title">{{__('mycare.edit_assessment_form')}}
            </div>
            <div class="column">
                @isset($form->_id)
                <a class="button is-primary is-outlined" href="{{url('/form/template/'.$form->_id)}}">{{__('mycare.edit_form_template')}}</a>
                <a class="button is-primary is-outlined" href="{{url('/form/preview/'.$form->_id)}}">{{__('mycare.preview')}}</a>
                @endisset
            </div>
        </div>

        <form role="form" action="{{url('/form/store')}}" method="post">

            {{csrf_field()}}

        <div class="box has-shadow">

            <div class="field">
                <div class="control">
                    <label class="label has-text-left">{{__('mycare.form_name')}}</label>
                    <input class="input" name="formName" value="{{$form->FormName}}"/>
                </div>
            </div>

            <div class="columns">
                <div class="column is-4">
                    <div class="field">
                        <div class="control">
                            <label class="label has-text-left">{{__('mycare.form_code')}}</label>
                            <input class="input" name="form_code" value="{{$form->FormID}}"/>
                        </div>
                    </div>
                </div>
                <div class="column is-8">
                    <div class="field">
                        <div class="control">
                            <label class="label has-text-left">{{__('mycare.parent_form')}}</label>
                            <!--set typeahead <input> class in JS-->
                            <typeahead id="typeahead_input" src_url="/form/findbyname" onselect="onSelectForm" ></typeahead>
                            <input type="hidden" id="parentFormID" name="parentFormID" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="columns">

            <div class="column is-3">
                    <div class="field">
                        <label class="forcheckbox">{{__('mycare.category')}}</label>
                        <div class="radio">
                            <label class="forcheckbox">
                                <input type="radio" name="category" value="form" @if($form->FormCategory==1) checked @endif />
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                <label class="label is-pulled-left">
                                    {{trans_choice('mycare.form', 1)}}
                                </label>
                            </label>

                            <label class="forcheckbox">
                                <input type="radio" name="category" value="assessment" @if($form->AssessmentCategory==1) checked @endif />
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                <label class="label is-pulled-left">
                                    {{trans_choice('mycare.assessment', 1)}}
                                </label>
                            </label>

                            <label class="forcheckbox">
                                <input type="radio" name="category" value="charting" @if($form->ChartingCategory==1) checked @endif />
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                <label class="label is-pulled-left">
                                    {{trans_choice('mycare.charting', 1)}}
                                </label>
                            </label>
                            
                        </div>
                    </div>
                </div>


                
                <div class="column is-3">
                    <div class="field">
                        <label class="forcheckbox">{{trans_choice('mycare.status', 1)}}</label>
                        <div class="radio">
                            <label class="forcheckbox">
                                <input type="radio" name="status" value="active" @if($form->IsActive) checked @endif />
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                <label class="label is-pulled-left">
                                    {{trans_choice('mycare.active', 1)}}
                                </label>
                            </label>

                            <label class="forcheckbox">
                                <input type="radio" name="status" value="inactive" @if(!$form->IsActive) checked @endif />
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                <label class="label is-pulled-left">
                                    {{trans_choice('mycare.inactive', 1)}}
                                </label>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="column is-3">
                    <div class="field">
                        <label class="forcheckbox">{{trans_choice('mycare.language', 1)}}</label>
                        <div class="radio">
                            <label class="forcheckbox">
                                <input type="radio" name="language" value="en" @if($form->language=='en') checked @endif />
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                <label class="label is-pulled-left">
                                    {{trans_choice('mycare.english', 1)}}
                                </label>
                            </label>

                            <label class="forcheckbox">
                                <input type="radio" name="language" value="zh" @if($form->language=='zh') checked @endif />
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                <label class="label is-pulled-left">
                                    {{trans_choice('mycare.chinese', 1)}}
                                </label>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- <div class="column is-3">
                    <div class="field">
                        <label class="label has-text-left">{{__('mycare.require')}}</label>
                        <div class="control">
                            <label class="label has-text-left">
                                <input type="checkbox" name="residentRequired" value="1" @if($form->ResidentRequired) checked @endif> {{trans_choice('mycare.resident', 1)}}</label>
                        </div>
                        <div class="control">
                            <label class="label has-text-left">
                                <input type="checkbox" name="staffRequired" value="1" @if($form->StaffRequired) checked @endif> {{__('mycare.staff')}}</label>
                        </div>

                    </div>
                </div> --}}

            </div>


            


        </div>

            <div class="field is-grouped">
                <p class="control">
                    <button class="button is-primary" >{{__('mycare.save')}}</button>
                </p>
                <p class="control">
                    <a class="button is-link" href="{{url('/form/listing')}}">{{__('mycare.cancel')}}</a>
                </p>
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

            <input type="hidden" name="formId" value="{{$form->_id}}"/>

        </form>

    </div>
@endsection




@section('script')
    <script>
        // Initiaize typeahead 
        $('#typeahead_input input').attr("class", "input");
        @if (isset($form->ParentFormID) and $form->ParentFormID != 0)
        var parentFormScreenName = "{{$form->ParentFormID . ' - '.$form['ParentFormName']}}";
        var parentFormID = "{{$form->ParentFormID}}";
        $('#typeahead_input input').val(parentFormScreenName);
        $('#parentFormID').val(parentFormID);   
        @endif
      

        function onSelectForm(item){
            // You might want to store item value in some hidden <input> (In case the screen_name of <typeahead> is different from <input> value required.)
            
            console.log(item.screen_name);
            //console.log(item.FormID);
            $('#parentFormID').val(item.FormID);   
        }
    </script>
@endsection
