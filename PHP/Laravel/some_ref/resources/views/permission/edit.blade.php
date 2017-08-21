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
                            <h1 class="page-title">{{__('mycare.role_edit_permission')}}</h1>
                        </div>
                    </div>
                </div>
</div>

<div class="dashboard-section bg-light">
    <div class="container">

        <div class="columns">
            <div class="column">
                <h1 class="page-title">{{__('mycare.role_name')}} : {{$role->roleName}}</h1>
            </div>
        </div>

        <form method="post" action="{{url('/permission/update')}}">
        {{csrf_field()}}
        <div class="columns">
            <div class="column">
                <table class="table">
                    <tr>
                        <th>{{__('mycare.menu_name')}}</th>
                        <th>{{__('mycare.menu_permission')}}</th>
                        <th>{{__('mycare.menu_actions_permission')}}</th>
                        
                    </tr>
                    
                    @foreach($mainMenus as $mainMenu)
                        <tbody>
                        <tr class="">
                            <td>
                                <span class="icon is-small">
                                @if ($mainMenu['subMenus']->count() != 0)
                                    <i class="fa fa-plus main-menu"></i>    
                                @else
                                    <i class="fa fa-file"></i>
                                @endif
                                </span>
                                {{$mainMenu->menuName}}
                            </td>
                            <td>
                           
                               
                                <input type="checkbox" name="read_{{$mainMenu->_id}}" value="1"
                                @if (isset($mainMenu['permission']) and $mainMenu['permission']->enableRead)
                                checked
                                @endif
                                >
                      
                            </td>
                            <td>        
                                <input type="checkbox" name="edit_{{$mainMenu->_id}}" value="1"
                                @if (isset($mainMenu['permission']) and $mainMenu['permission']->enableEdit)
                                checked
                                @endif
                                > Modify
                                
                                &nbsp;&nbsp;
                                
                                <input type="checkbox" name="delete_{{$mainMenu->_id}}" value="1"
                                @if (isset($mainMenu['permission']) and $mainMenu['permission']->enableDelete)
                                checked
                                @endif
                                > Remove
                            </td>
                        </tr>
                        
                        <!--sub menus-->
                        @foreach ($mainMenu['subMenus'] as $subMenu)
                        <tr class="sub-menu" style="display: none">
                            <td> 
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                <span class="icon is-small">  
                                    <i class="fa fa-file"></i>
                                </span>
                                {{$subMenu->menuName}}
                            </td>
                            <td>
                          
                                <input type="checkbox" name="read_{{$subMenu->_id}}" value="1"
                                @if (isset($subMenu['permission']) and $subMenu['permission']->enableRead)
                                checked
                                @endif
                                >
                            
                            </td>
                            <td>        
                                <input type="checkbox" name="edit_{{$subMenu->_id}}" value="1"
                                @if (isset($subMenu['permission']) and $subMenu['permission']->enableEdit)
                                checked
                                @endif
                                > Modify
                                
                                &nbsp;&nbsp;
                                
                                <input type="checkbox" name="delete_{{$subMenu->_id}}" value="1"
                                @if (isset($subMenu['permission']) and $subMenu['permission']->enableDelete)
                                checked
                                @endif
                                > Remove
                            </td>
                        </tr>
                        @endforeach
                        <!--end of sub menus-->

                        </tbody>
                    @endforeach <!-- foreach($mainMenus as $mainMenu) -->

                </table>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <div class="field">
                    <input type="hidden" name="roleId" value="{{$role->_id}}"/>
                    <button class="button is-primary" >{{__('mycare.save')}}</button>
                </div>
            </div>
        </div>
        </form>

    </div>
</div>
@endsection




@section('script')

    <script>
        $(document).ready(function(){
            //$(".main-menu").addClass("xxx");
            //$(".sub-menu").hide();
           
            $(".main-menu").click(function(){
                $(this).closest("tbody").find(".sub-menu").toggle();
                //$(this).find(".arrow").toggleClass("up");

                $(this).toggleClass("fa-plus");
                $(this).toggleClass("fa-minus");
            });
        });
    </script>

@endsection