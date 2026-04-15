@extends('admin.layouts.app')

@section('pagetitle')
    Customer Management
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page">CMS</li>
                        <li class="breadcrumb-item active" aria-current="page">Customers</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Customers</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12">

                <div class="filter-buttons mg-b-10">
                    <div class="d-md-flex bd-highlight">
                        <div class="bd-highlight mg-r-10 mg-t-10">
                            <div class="dropdown d-inline mg-r-5">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filters
                                </button>
                                <div class="dropdown-menu">
                                    <form id="filterForm" class="pd-20">
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_by')}}</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="updated_at" @if ($filter->orderBy == 'updated_at') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">{{__('common.date_modified')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="name" @if ($filter->orderBy == 'name') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">{{__('common.name')}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_order')}}</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="sortByAsc" name="sortBy" class="custom-control-input" value="asc" @if ($filter->sortBy == 'asc') checked @endif>
                                                <label class="custom-control-label" for="sortByAsc">{{__('common.ascending')}}</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="sortByDesc" name="sortBy" class="custom-control-input" value="desc"  @if ($filter->sortBy == 'desc') checked @endif>
                                                <label class="custom-control-label" for="sortByDesc">{{__('common.descending')}}</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="showInactive" name="showDeleted" class="custom-control-input" @if ($filter->showDeleted) checked @endif>
                                                <label class="custom-control-label" for="showInactive">Show Inactive Customers</label>
                                            </div>
                                        </div>
                                        <div class="form-group mg-b-40">
                                            <label class="d-block">{{__('common.item_displayed')}}</label>
                                            <input id="displaySize" type="text" class="js-range-slider" name="perPage" value="{{ $filter->perPage }}"/>
                                        </div>
                                        <button id="filter" type="button" class="btn btn-sm btn-primary">{{__('common.apply_filters')}}</button>
                                    </form>
                                </div>
                            </div>

                            @if (auth()->user()->has_access_to_route('customer.deactivate'))
                                <div class="list-search d-inline">
                                    <div class="dropdown d-inline mg-r-10">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if (auth()->user()->has_access_to_route('customer.deactivate'))
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="bulkAction(1)">Activate</a>
                                                <a class="dropdown-item tx-danger" href="javascript:void(0)" onclick="bulkAction(0)">Deactivate</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="ml-auto bd-highlight mg-t-10 mg-r-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-r-10">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Name, Email..." value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="mg-t-10">
                            @if(auth()->user()->has_access_to_route('customers.create'))
                                <a class="btn btn-primary btn-sm" href="{{ route('customers.create') }}">Create a Customer</a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Filters -->


            <!-- Start Pages -->
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                            <label class="custom-control-label" for="checkbox_all"></label>
                                        </div>
                                    </th>
                                    <th scope="col" width="30%">Name</th>
                                    <th scope="col">Email</th>
                                    {{-- <th scope="col">Role</th> --}}
                                    <th scope="col">Date Registered</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input cb" id="cb{{ $user->id }}">
                                                <label class="custom-control-label" for="cb{{ $user->id }}"></label>
                                            </div>
                                        </th>
                                        <th>
                                            <strong @if($user->is_active == 0) style="text-decoration:line-through;" @endif> {{$user->fullname}}</strong>
                                        </th>
                                        <td>{{ $user->email }}</td>
                                        {{-- <td><span class="badge badge-primary">{{ \App\Models\User::userRole($user->role_id) }}</span></td> --}}
                                        <td>
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td>
                                            @if($user->is_active == 1)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->registration_type == 'registered')
                                                <span class="badge badge-primary">Registered</span>
                                            @else
                                                <span class="badge badge-secondary">Guest</span>
                                            @endif
                                        </td>
                                        <td>
                                            <nav class="nav table-options justify-content-begin">
                                                @if (auth()->user()->has_access_to_route('customers.edit'))
                                                    @if($user->is_active == 1)
                                                        <a class="nav-link" href="{{ route('customers.edit', $user->id) }}" title="Edit User"><i data-feather="edit"></i></a>
                                                     @endif
                                                @endif
                                                <a class="nav-link" target="_blank" href="{{ route('admin.report.audit_trail_per_user') }}?pb={{ $user->id }}&startdate={{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}&enddate={{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}" title="View User"><i data-feather="eye"></i></a>
                                                @if (auth()->user()->has_access_to_route('customer.deactivate'))
                                                    @if($user->is_active == 1)
                                                        <a class="nav-link deactivate_user" data-user_id="{{ $user->id }}" href="#" title="Deactivate User" data-toggle="modal" data-target="#modalUserDeactivate"><i data-feather="user-x"></i></a>
                                                    @endif
                                                @endif
                                            </nav>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center;"> <p class="text-danger">No customers found.</p></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Pages -->

            <!-- Start Navigation -->
            <div class="col-md-6">
                <div class="mg-t-5">
                    @if ($users->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{$users->firstItem()}} to {{$users->lastItem()}} of {{$users->total()}} users</p>
                    @endif

                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $users->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
            <!-- End Navigation -->

        </div>

        <form action="" id="posting_form" style="display:none;" method="post">
            @csrf
            <input type="text" id="users" name="users">
            <input type="text" id="status" name="status">
        </form>

        <div class="modal effect-scale" id="prompt-multiple-deactivate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Deactivate Users</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        You are about to <span class="prompt-status"></span> the selected users. Do you want to continue?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger" id="btnDeactivateMultiple">Yes, Deactivate</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal effect-scale" id="prompt-no-selected" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.no_selected_title')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{__('common.no_selected')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@include('admin.customers.modals')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('scripts/user/scripts.js') }}"></script>

    <script>
        let listingUrl = "{{ route('customers.index') }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
<script>
    function bulkAction(status){
        var counter = 0;
        var selected_users = '';
        $(".cb:checked").each(function(){
            counter++;
            fid = $(this).attr('id');
            selected_users += fid.substring(2, fid.length)+'|';
        });

        if(parseInt(counter) < 1){
            $('#prompt-no-selected').modal('show');
            return false;
        }
        else{
            $('.prompt-status').text(status == 1 ? 'activate' : 'deactivate');  
            $('#prompt-multiple-deactivate').modal('show');
            $('#btnDeactivateMultiple').on('click', function() {
                post_form("{{route('customers.multiple.deactivate')}}", status ,selected_users);
            });
        }
    }

    function post_form(url,status,users){
        $('#posting_form').attr('action',url);
        $('#users').val(users);
        $('#status').val(status);
        $('#posting_form').submit();
    }
</script>

@endsection
