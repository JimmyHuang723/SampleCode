@extends('layouts.app')


@section('style')
<style>

</style>
@endsection




@section('content')
<div class="pagehead-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="page-title">{{__('mycare.role_list')}}</h1>
                        </div>
                    </div>
                </div>
</div>

<div class="dashboard-section bg-light">
    <div class="container">
        <div class="columns">
            <div class="column">
                <table class="table">
                    <tr>
                        <th>{{__('mycare.role_name')}}</th>
                        <th>{{__('mycare.role_to_approve_leave')}}</th>
                        <th>{{__('mycare.is_editable')}}</th>
                        <th width="20%">&nbsp;</th>
                    </tr>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{$role->roleName}}</td>
                            <td>{{$role->parentRoleName}}</td>
                            <td>{{$role->isEditable ? "true" : "false"}}</td>
                            <td>
                                <a class="button is-primary" href="{{ url('permission/edit/'.$role->_id) }}">{{__('mycare.edit')}}</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection




@section('script')

    <script>
      
    </script>

@endsection