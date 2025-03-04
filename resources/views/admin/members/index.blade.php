@extends('admin.layouts.app')

@section('pagetitle')
    Members
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
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
                        <li class="breadcrumb-item active" aria-current="page">Members</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Members</h4>
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
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="created_at" @if ($filter->orderBy == 'created_at') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">Join Date</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="first_name" @if ($filter->orderBy == 'first_name') checked @endif>
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
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Name" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                                <a class="btn btn-success btn-sm mg-b-5" href="javascript:void(0)" data-toggle="modal" data-target="#advanceSearchModal">{{__('common.advance_search')}}</a>
                            </form>
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
                            <tr>
                                <th style="width: 20%;overflow: hidden;">Code</th>
                                <th style="width: 20%;">Full Name</th>
                                <th style="width: 15%;">Rank</th>
                                <th style="width: 20%;">Sponsor</th>
                                <th style="width: 10%;">Join Date</th>
                                <th style="width: 5%;">Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($members as $member)
                                <tr>
                                    <td>{{ $member->code }}</td>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ ucfirst($member->class) }}</td>
                                    <td>@if($member->sponsor) {{ $member->sponsor->name }} @else <span class="badge badge-light">Abandoned</span> @endif</td>
                                    <td>{{ Setting::date_for_listing($member->created_at) }}</td>
                                    <th>
                                        <nav class="nav table-options justify-content-end">
                                            <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="settings"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" data-target="#prompt-change-sponsor" data-toggle="modal" data-animation="effect-scale" data-id="{{ $member->id }}" data-name="{{ $member->name }}" data-sponsor-id="{{ $member->sponsor_id }}">Change Sponsor</a>
                                            </div>
                                        </nav>
                                    </th>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center;"> <p class="text-danger">No members found.</p></td>
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
                    @if ($members->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $members->firstItem() }} to {{ $members->lastItem() }} of {{ $members->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $members->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="advanceSearchModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form role="form" id="advanceFilterForm" method="GET" action="{{route('pages.index.advance-search')}}">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('common.advance_search')}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Code</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="code" value="{{ $advanceSearchData->code }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">First Name</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="name" value="{{ $advanceSearchData->first_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Middle Name</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="name" value="{{ $advanceSearchData->middle_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Last Name</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="name" value="{{ $advanceSearchData->last_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Sponsor</label>
                            <div>
                                <select name="sponsor_id" class="select2 input-sm" style="width:100%">
                                    <option value="">- All Sponsor -</option>
                                    <option value="#^!!" @if ($advanceSearchData->sponsor_id == "#^!!") selected @endif>Abandoned</option>
                                    @foreach($uniqueMembersBySponsorId as $sponsor)
                                        <option value="{{$sponsor->id}}" @if ($advanceSearchData->sponsor_id == $sponsor->id) selected @endif>{{$sponsor->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Entity Type</label>
                            <div>
                                <select name="entity_type" class="form-control input-sm">
                                    <option value="">- All Entity Type -</option>
                                    @foreach($entityTypes as $type)
                                        <option value="{{$type}}" @if (strtolower($advanceSearchData->entity_type) == strtolower($type)) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Rank</label>
                            <div>
                                <select name="class" class="form-control input-sm">
                                    <option value="">- All Rank -</option>
                                    @foreach($ranks as $rank)
                                        <option value="{{$rank}}" @if (strtolower($advanceSearchData->class) == strtolower($rank)) selected @endif>{{$rank}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="email" value="{{ $advanceSearchData->email }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Mobile</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="mobile" value="{{ $advanceSearchData->mobile }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Home Telephone</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="phone" value="{{ $advanceSearchData->phone }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Work Telephone</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="work_phone" value="{{ $advanceSearchData->work_phone }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Fax</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="fax" value="{{ $advanceSearchData->fax }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Street</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="address_street" value="{{ $advanceSearchData->address_street }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">City/Municipal</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="address_city" value="{{ $advanceSearchData->address_city }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Province</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="address_province" value="{{ $advanceSearchData->address_province }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Zip/Postal Code</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="address_zip" value="{{ $advanceSearchData->address_zip }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Country</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="address_country" value="{{ $advanceSearchData->address_country }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Delivery Street</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="address_delivery_street" value="{{ $advanceSearchData->address_delivery_street }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Delivery City/Municipal</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="address_delivery_city" value="{{ $advanceSearchData->address_delivery_city }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Delivery Province</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="address_delivery_province" value="{{ $advanceSearchData->address_delivery_province }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Delivery Zip/Postal Code</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="address_delivery_zip" value="{{ $advanceSearchData->address_delivery_zip }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Delivery Country</label>
                            <div>
                                <input type="text" class="form-control input-sm" name="address_delivery_country" value="{{ $advanceSearchData->address_delivery_country }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <div>
                                <select name="status" class="form-control input-sm">
                                    <option value="">- All Status -</option>
                                    <option value="active" @if (strtolower($advanceSearchData->status) == 'active') selected @endif>Active</option>
                                    <option value="inactive" @if (strtolower($advanceSearchData->status) == 'inactive') selected @endif>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label" for="updated_at1">Join Date (From)</label>
                                    <input type="date" class="form-control input-sm" id="updated_at1" name="created_at1" value="{{ $advanceSearchData->created_at1 }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="updated_at2">Join Date (To)</label>
                                    <input type="date" class="form-control input-sm" id="updated_at2" name="created_at2" value="{{ $advanceSearchData->created_at2 }}" min="{{ $advanceSearchData->created_at1 }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-info" href="{{ route('members.index') }}">Reset</a>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <input type="submit" value="{{__('common.search')}}" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-change-sponsor" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="{{route('members.change-sponsor')}}">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="id" id="memberId">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Change sponsor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Members name *</label>
                            <div>
                                <input id="memberName" type="text" class="form-control" readonly value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Sponsor *</label>
                            <div>
                                <select id="sponsorId" name="sponsor_id" class="input-sm" style="width: 100%;">
                                    <option value="">Abandoned</option>
                                    @foreach ($sponsors as $sponsor)
                                        <option value="{{$sponsor->id}}" id="sponsor{{$sponsor->id}}">{{$sponsor->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-sm btn-primary">Change Sponsor</button>
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
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script>
        let listingUrl = "{{ route('members.index') }}";
        let advanceListingUrl = "{{ route('members.index.advance-search') }}";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('.select2').select2({
                selectOnClose: true
            });

            $('#sponsorId').select2({
                selectOnClose: true,
                dropdownParent: $('#prompt-change-sponsor')
            });

            let memberId;
            $('#prompt-change-sponsor').on('show.bs.modal', function(e) {
                let member = e.relatedTarget;
                let memberName = $(member).data('name');
                memberId = $(member).data('id');
                sponsorId = $(member).data('sponsor-id');
                $('#memberId').val(memberId);
                $('#memberName').val(memberName);
                $('#sponsorId').val(sponsorId).trigger('change');
                $('#sponsor'+memberId).hide();
            });

            $('#prompt-change-sponsor').on('hide.bs.modal', function(e) {
                $('#memberId').val('');
                $('#memberName').val('');
                $('#sponsorId').val('').trigger('change');
                $('#sponsor'+memberId).show();
            });
        });
    </script>
@endsection
