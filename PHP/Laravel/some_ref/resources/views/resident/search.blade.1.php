@extends('layouts.app')
@section('content')
<div class="pagehead-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="page-title">{{ __('mycare.search_resident') }} {{$facility->FacilityName}}</h1>
                        </div>
                    </div>
                </div>
</div>
<div class="dashboard-section bg-light">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							{{--<form method="post" action="{{url('/resident/browse')}}">--}}
								{{--{{csrf_field()}}--}}
								{{--<div class="field">--}}
									{{--<p class="control">--}}
										{{--<input class="input is-large" type="text" name="name" placeholder="{{__('mycare.enter_user_name')}}">--}}
									{{--</p>--}}
								{{--</div>--}}
							{{--</form>--}}

							<typeahead src_url="/resident/findahead" onselect="onSelectTypeahead" ></typeahead>

						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="content">
								<h2>{{trans_choice('mycare.task',2)}}</h2>
							</div>
							<div class="box">
								@foreach($tasks as $task)
									<div class="media">
										<div class="media-left">
											<div class="content">
												<p>{{$task->DueDate}}<br/>
													{{$task->DueTime}}
												</p>
											</div>
										</div>
										<div class="media-content">
											<div class="content">
												<p><a href="{{url('task/action/'.$task->_id)}}">{{$task->Title}} {{__('mycare.for')}} {{$task->Resident['ResidentName']}}</a>
												</p>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						</div>

						<div class="col-md-6">
							<div class="content">
								<h2>{{__('mycare.whats_happening')}}</h2>
							</div>
							<div class="box">
								@foreach($activities as $act)
								<div class="media">
									<div class="media-left">
										<div class="content">
											<p>{{$act->updated_at->format('d-M-Y')}}<br/>
												{{$act->updated_at->format('H:i')}}
											</p>
										</div>
									</div>
									<div class="media-content">
										<div class="content">
											<p><a href="{{$act->link}}">{{$act->text}}</a>
											</p>
										</div>
									</div>
								</div>
								@endforeach
							</div>

						</div>
					</div>


				</div>
			</div>
 @endsection




@section('script')

    <script>
   		function onSelectTypeahead(item){
            // You might want to store item value in some hidden <input> (In case the screen_name of <typeahead> is different from <input> value required.)
   			
   			console.log(item);
   			console.log(item.screen_name);
   			window.location = "/resident/select/"+item._id;
   		}
    </script>

   
@endsection

