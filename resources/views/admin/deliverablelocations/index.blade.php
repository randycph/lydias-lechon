@extends('admin.layouts.app')

@section('pagetitle')
    Manage Customer
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
    </style>
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Delivery Location</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Delivery Location</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12">
                <div class="filter-buttons">
                    <div class="d-md-flex bd-highlight">
                        <div class="bd-highlight mg-r-10 mg-t-10">
                            <div class="dropdown d-inline mg-r-5">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{__('common.filters')}}
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
                                                <label class="custom-control-label" for="orderBy2">{{__('common.title')}}</label>
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
{{--                                        <div class="form-group">--}}
{{--                                            <div class="custom-control custom-checkbox">--}}
{{--                                                <input type="checkbox" id="showDeleted" name="showDeleted" class="custom-control-input" @if ($filter->showDeleted) checked @endif>--}}
{{--                                                <label class="custom-control-label" for="showDeleted">{{__('common.show_deleted')}}</label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="form-group mg-b-40">
                                            <label class="d-block">{{__('common.item_displayed')}}</label>
                                            <input id="displaySize" type="text" class="js-range-slider" name="perPage" value="{{ $filter->perPage }}"/>
                                        </div>
                                        <button id="filter" type="button" class="btn btn-sm btn-primary">{{__('common.apply_filters')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="ml-auto bd-highlight mg-t-10 mg-r-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-r-10">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Title" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="mg-t-10">
                            @if (auth()->user()->has_access_to_route('admin.locations.create'))
                                <a class="btn btn-primary btn-sm mg-b-20" href="{{ route('admin.locations.create') }}">Add New Location</a>
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
                        <table class="table mg-b-0 table-light table-hover">
                            <thead>
                            <tr class="d-flex">
                                <th class="col-4">Location</th>
                                <th class="col-2">Area</th>
                                <th class="col-1">Item Type</th>
                                <th class="col-1">Outside Manila</th>
                                <th class="col-2">Rate</th>
                                <th class="col-2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($address as $add)
                                    <tr class="d-flex">
                                        <td class="col-4">{{ $add->name }}</td>
                                        <td class="col-2">{{ $add->area }}</td>
                                        <td class="col-1">{{ $add->item_type }}</td>
                                        <td class="col-1">{{ ($add->outside_manila == '1') ? 'Yes' : 'No' }}</td>
                                        <td class="col-2">{{ number_format($add->rate,2) }}</td>
                                        <td class="col-2">
                                            <nav class="nav table-options">
                                                @if (auth()->user()->has_access_to_route('admin.locations.edit'))
                                                    <a class="nav-link" href="{{ route('admin.locations.edit',$add->id) }}" title="Edit Location"><i data-feather="edit"></i></a>
                                                @endif
                                                @if (auth()->user()->has_access_to_route('admin.location.delete'))
                                                    <a class="nav-link delete-address" href="javascript:;" data-toggle="modal" data-id="{{$add->id}}" data-target="#prompt-delete-address"><i data-feather="delete"></i></a>
                                                @endif
                                            </nav>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th colspan="10" style="text-align: center;"> <p class="text-danger">No Address found.</p></th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Pages -->
            <div class="col-md-6">
                <div class="mg-t-5">
                    @if ($address->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $address->firstItem() }} to {{ $address->lastItem() }} of {{ $address->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $address->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal effect-scale" id="prompt-delete-address" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Delete Deliverable Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.location.delete') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p>You are about to delete this location. Do you want to continue?</p>
                        <input type="hidden" name="add_id" id="add_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger" id="btnDelete">Yes, Delete</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        let listingUrl = "{{ route('admin.locations.index') }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
    <script>
        $(document).on('click','.delete-address',function(){
            $('#add_id').val($(this).data('id'));
        });
    </script>
@endsection

@section('customjs')
@endsection

