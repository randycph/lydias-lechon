@extends('admin.layouts.app')

@section('pagecss')
    <style>
        td {
            padding: 5px 10px !important;
        }
        .access-tbl {
            border-radius: 6px;
            background: #f9f9f9;
        }
    </style>
@endsection

@section('pagetitle')
    Manage Access Permission
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Access Rights</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Access Rights</h4>
            </div>
            <div>
                <button type="submit" class="btn btn-sm btn-primary" id="saveRolePermission"><i data-feather="save"></i> Save Changes</button>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12 mg-t-10">

                <form id="rolePermissionForm" action="{{ route('role-permission.update') }}" method="post" role="form">
                    @csrf

                    <div class="table-responsive text-nowrap access-tbl">
                        <table class="table table-hover" style="width:100%;">
                            <thead class="thead-primary">
                            <td>Module</td>
                            @foreach($roles as $role)
                                @if ($role->is_not_admin())
                                    <td>{{ $role->name }}</td>
                                @endif
                            @endforeach
                            </thead>
                            <tbody>
                            @foreach($modules as $module)
                                <tr>
                                    <td><p class="mg-0 pd-t-5 pd-b-5 tx-uppercase tx-semibold tx-primary">{{ $module->module }} module</p></td>
                                    @foreach($roles as $role)
                                        @if ($role->is_not_admin())
                                            @php
                                                $moduleKey = $module->module;
                                                $permissionCount = $permissions->filter(function ($permission) use($moduleKey) {
                                                    return $permission->module === $moduleKey;
                                                })->count();
                                                $rolePermissionCount = $role->permissions($moduleKey)->get()->unique()->count();
                                                $checked = $rolePermissionCount > 0 && $permissionCount === $rolePermissionCount ? 'checked' : '';
                                            @endphp
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" {{ $checked }} {{ $moduleKey }} class="custom-control-input access module_{{$role->id}}_{{$module->module_code()}}" data-role="{{$role->id}}" data-module="{{$module->module_code()}}" id="{{$moduleKey}}_{{ $loop->iteration }}">
                                                    <label class="custom-control-label" for="{{$moduleKey}}_{{ $loop->iteration }}"></label>
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                                @forelse($permissions as $permission)
                                    @php $row = $loop->iteration; @endphp
                                    <tr>
                                        @if($permission->module == $module->module)
                                            <td>{{ $permission->description }}</td>

                                            @foreach($roles as $role)
                                                @if ($role->is_not_admin())
                                                    @php
                                                        $roleId = $role->id;
                                                        $permissionId = $permission->id;
                                                        $checked = $access->search(function ($item) use ($roleId, $permissionId) {
                                                                    return $item->role_id == $roleId && $item->permission_id == $permissionId;
                                                                }) > -1 ? "checked" : "";
                                                    @endphp
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input trigger_view access_{{$roleId}}_{{$module->module_code()}} @if($permission->is_view_page) view_permission view_{{$roleId}}_{{$module->module_code()}} @endif"
                                                                   {{ $checked }} name="cb['{{$permission->id}}_{{$role->id}}']" id="{{$permission->name}}_{{ $loop->iteration }}" data-role="{{$role->id}}" data-module="{{$module->module_code()}}">
                                                            <label class="custom-control-label" for="{{$permission->name}}_{{ $loop->iteration }}"></label>
                                                        </div>
                                                    </td>
                                                @endif
                                            @endforeach

                                        @endif
                                    </tr>
                                @empty
                                    <tr><td>No Permssions</td></tr>
                                @endforelse
                            @endforeach
                            </tbody>
                        </table>
                    </div><!-- table-responsive -->


                </form>

            </div>
        </div>

    </div>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function(){

            $('form').submit(function(){
                $(this).find('button[type=submit]').prop('disabled', true);
            });
        });

        $('.access').on('click', function() {
            let module = $(this).data('module');
            let role = $(this).data('role');
            let checked = $(this).is(':checked');
            let objectName = '.access_'+role+'_'+module;
            $(objectName).each(function() {
                this.checked = checked;
            });

            $(objectName).each(function() {
                this.checked = checked;
            });
        });

        $('.trigger_view').on('change', function() {
            let module = $(this).data('module');
            let role = $(this).data('role');
            let objectName = '.view_'+role+'_'+module;
            let viewObj = '.access_'+role+'_'+module;

            if($(this).is(':checked')) {
                $(objectName).prop('checked', true);
            }

            let allChecked = true;
            $(viewObj).each(function () {
                if (!$(this).is(':checked') && !$(this).hasClass('view_permission')) {
                    allChecked = false;
                    return false;
                }
            });

            console.log(allChecked);

            if (allChecked) {
                $('.module_'+role+'_'+module).prop('checked', true);
            } else {
                $('.module_'+role+'_'+module).prop('checked', false);
            }
        });

        $('.view_permission').on('click', function() {
            let module = $(this).data('module');
            let role = $(this).data('role');
            let objectName = '.view_'+role+'_'+module;
            let viewObj = '.access_'+role+'_'+module;
            let viewObject = $(this);
            $(objectName).each(function() {
                if($(this).is(':checked') && !$(this).hasClass('view_permission')) {
                    viewObject.prop('checked', true);
                    return false;
                }
            });

            if (!$(viewObject).is(':checked')) {
                $(viewObj).each(function() {
                    $(this).prop('checked', false);
                })
            }
        });

        $('#saveRolePermission').on('click', function() {
            $('#rolePermissionForm').submit();
        });
    </script>
@endsection
