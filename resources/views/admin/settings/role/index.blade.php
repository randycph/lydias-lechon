@extends('admin.layouts.app')

@section('pagetitle')
    Role Management
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Roles</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Manage Roles</h4>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-md-12">
            <div class="filter-buttons mg-b-10">
                <div class="d-md-flex bd-highlight">
                    <div class="ml-auto bd-highlight mg-t-10">
                    @if(\App\ViewPermissions::check_permission(Auth::user()->role_id,'admin/role/create') == 1)
                        <a class="btn btn-primary btn-sm mg-b-5" href="{{ route('role.create') }}">Create a Role</a>
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-list mg-b-10">
                <div class="table-responsive-lg">
                    <table class="table mg-b-0 table-light table-hover" style="width:100%;">
                        <thead>
                            <tr>
                                <th scope="col" width="40%">Name</th>
                                <th scope="col" width="50%">Description</th>
                                <th scope="col" width="10%">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <th>
                                        <strong> {{ $role->name }}</strong>
                                    </th>
                                    <td>{{ $role->description }}</td>
                                    <td>
                                        <nav class="nav table-options justify-content-end">
                                        @if(\App\ViewPermissions::check_permission(Auth::user()->role_id,'admin/role/edit') == 1)
                                            <a href="{{ route('role.edit',$role->id) }}" class="nav-link"><i data-feather="edit"></i></a>
                                        @endif
                                        @if(\App\ViewPermissions::check_permission(Auth::user()->role_id,'admin/role/delete') == 1)
                                            <a href="#modalDeleteRole" class="nav-link delete_role"  data-rid="{{ $role->id }}" data-toggle="modal"><i data-feather="trash"></i></a>
                                        @endif
                                        </nav>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mg-t-5">
                <p class="tx-gray-400 tx-12 d-inline">Showing {{$roles->firstItem()}} to {{$roles->lastItem()}} of {{$roles->total()}} roles</p>
            </div>
        </div>
	    <div class="col-md-6">
	        <div class="text-md-right float-md-right mg-t-5">
	            <div>
                    {{ $roles->links() }}
	            </div>
	        </div>
	    </div>
    </div>
</div>
@include('admin.settings.role.modal')
@endsection


@section('customjs')
	<script>
        $(document).on('click','.delete_role', function(){
            $('#modalDeleteRole').show();

            $('#rid').val($(this).data('rid'));
        });
    </script>
@endsection
