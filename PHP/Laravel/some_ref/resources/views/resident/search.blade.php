@extends('layouts.app')
@section('content')

<div class="container content container_top">
	<div class="clearfix">
		<div class="right">
			<typeahead src_url="/resident/findahead" onselect="onSelectTypeahead" ></typeahead>
		</div>
	</div>

	<div class="columns content m-none">
		<div class="column is-12 p-none">
			<div class="ibox">
				<div class="ibox-content p-none">
					<table class="table">
						<tr>							
							<th width="20%">{{__('mycare.location')}}</th>
							<th width="10%">{{__('mycare.room')}}</th>
							<th width="70%">{{__('mycare.resident_name')}}</th>
						</tr>
						@foreach($residents as $resident)
						<tr>
							
							<td>{{$resident->LocationNameLong}}</td>
							<td>{{$resident->Room}}</td>
							<td>
								<p>
								<a href="{{url('resident/select/'.$resident->_id)}}">{{$resident->Fullname}}</a>
								</p>
							@php
								$tasks = App\Domains\MyCareTask::orderBy('updated_at')
									->where('Resident.ResidentId', $resident->_id)
									->where('IsActive', 1)
									->where('State', 'new')
									->take(5)
									->get();
								$taskCount = sizeof($tasks);
							@endphp
							@if($taskCount>0)
								<table class="table">
									<tr>
										<th width="">{{trans_choice('mycare.task',1)}}</th>
										<th width="120px">{{__('mycare.source')}}</th>
										<th width="120px">{{__('mycare.due_date')}}</th>
									</tr>
									@foreach($tasks as $task)
									<tr>
									<td><a href="{{url('/task/action/'.$task->_id)}}">{{$task->Title}}</a></td>
									@php
										$dt = new Carbon\Carbon($task->StopDate['Date']);
									@endphp
									<td>
									@if($task->Source=='admission') {{trans_choice('mycare.admission',1)}}
									@endif
									</td>
									<td>{{$dt->format('d-M-Y')}}</td>
									</tr>
									@endforeach
									
								</table>
								
							@endif
							
							</td>
							
							
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="columns m-none">
		<div class="column is-2 p-none">
			{{ $residents->links() }}
		</div>
	</div>	
</div>

	

					
 @endsection




@section('script')

    <script>
   		function onSelectTypeahead(item){
            // You might want to store item value in some hidden <input> (In case the screen_name of <typeahead> is different from <input> value required.)
   			
   			{{-- console.log(item); --}}
   			{{-- console.log(item.screen_name); --}}
   			window.location = "/resident/select/"+item._id;
   		}

		$(document).ready(function() {
			//hideSideMenu();
		});
		
    </script>

   
@endsection

