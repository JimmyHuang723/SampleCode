@extends('vendor.crud.single-page-templates.common.app')

@section('content')

	<h2>Users</h2>

	@include('user.create')

	<table class="table table-striped grid-view-tbl">
	    
	    <thead>
		<tr class="header-row">
			{!!\Nvd\Crud\Html::sortableTh('id','user.index','Id')!!}
			{!!\Nvd\Crud\Html::sortableTh('name','user.index','Name')!!}
			{!!\Nvd\Crud\Html::sortableTh('email','user.index','Email')!!}
			{!!\Nvd\Crud\Html::sortableTh('password','user.index','Password')!!}
			{!!\Nvd\Crud\Html::sortableTh('remember_token','user.index','Remember Token')!!}
			{!!\Nvd\Crud\Html::sortableTh('created_at','user.index','Created At')!!}
			{!!\Nvd\Crud\Html::sortableTh('updated_at','user.index','Updated At')!!}
			<th></th>
		</tr>
		<tr class="search-row">
			<form class="search-form">
				<td><input type="text" class="form-control" name="id" value="{{Request::input("id")}}"></td>
				<td><input type="text" class="form-control" name="name" value="{{Request::input("name")}}"></td>
				<td><input type="text" class="form-control" name="email" value="{{Request::input("email")}}"></td>
				<td><input type="text" class="form-control" name="password" value="{{Request::input("password")}}"></td>
				<td><input type="text" class="form-control" name="remember_token" value="{{Request::input("remember_token")}}"></td>
				<td><input type="text" class="form-control" name="created_at" value="{{Request::input("created_at")}}"></td>
				<td><input type="text" class="form-control" name="updated_at" value="{{Request::input("updated_at")}}"></td>
				<td style="min-width: 6em;">@include('vendor.crud.single-page-templates.common.search-btn')</td>
			</form>
		</tr>
	    </thead>

	    <tbody>
	    	@forelse ( $records as $record )
		    	<tr>
					<td>
						{{ $record->id }}
						</td>
					<td>
						<span class="editable"
							  data-type="text"
							  data-name="name"
							  data-value="{{ $record->name }}"
							  data-pk="{{ $record->{$record->getKeyName()} }}"
							  data-url="/user/{{ $record->{$record->getKeyName()} }}"
							  >{{ $record->name }}</span>
						</td>
					<td>
						<span class="editable"
							  data-type="email"
							  data-name="email"
							  data-value="{{ $record->email }}"
							  data-pk="{{ $record->{$record->getKeyName()} }}"
							  data-url="/user/{{ $record->{$record->getKeyName()} }}"
							  >{{ $record->email }}</span>
						</td>
					<td>
						<span class="editable"
							  data-type="text"
							  data-name="password"
							  data-value="{{ $record->password }}"
							  data-pk="{{ $record->{$record->getKeyName()} }}"
							  data-url="/user/{{ $record->{$record->getKeyName()} }}"
							  >{{ $record->password }}</span>
						</td>
					<td>
						<span class="editable"
							  data-type="text"
							  data-name="remember_token"
							  data-value="{{ $record->remember_token }}"
							  data-pk="{{ $record->{$record->getKeyName()} }}"
							  data-url="/user/{{ $record->{$record->getKeyName()} }}"
							  >{{ $record->remember_token }}</span>
						</td>
					<td>
						{{ $record->created_at }}
						</td>
					<td>
						{{ $record->updated_at }}
						</td>
					@include( 'vendor.crud.single-page-templates.common.actions', [ 'url' => 'test/login3/public/user', 'record' => $record ] )
		    	</tr>
			@empty
				@include ('vendor.crud.single-page-templates.common.not-found-tr',['colspan' => 8])
	    	@endforelse
	    </tbody>

	</table>

	@include('vendor.crud.single-page-templates.common.pagination', [ 'records' => $records ] )

<script>
	$(".editable").editable({ajaxOptions:{method:'PUT'}});
</script>
@endsection