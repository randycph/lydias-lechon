@extends('admin.layouts.app')

@section('pagetitle')
Manage Download Request
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('videos.index')}}">Videos</a></li>
                         <li class="breadcrumb-item active" aria-current="page">Request</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Download Request</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12">
                <div class="filter-buttons mg-b-10">
                    <div class="d-md-flex bd-highlight">
                        <div class="col-md-3" style="padding:0px !important;">
                            <div class="bd-highlight mg-r-10 mg-t-10">
                                <div class="dropdown d-inline mg-r-5">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Filters
                                    </button>
                                    <div class="dropdown-menu">
                                        <form id="filterForm" class="pd-20">
                                            <div class="form-group">
                                                <label for="exampleDropdownFormEmail1">Sort by</label>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="updated_at" @if ($filter->orderBy == 'updated_at') checked @endif>
                                                    <label class="custom-control-label" for="orderBy1">Date Modified</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="name" @if ($filter->orderBy == 'name') checked @endif>
                                                    <label class="custom-control-label" for="orderBy2">Name</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleDropdownFormEmail1">Sort order</label>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="sortByAsc" name="sortBy" class="custom-control-input" value="asc" @if ($filter->sortBy == 'asc') checked @endif>
                                                    <label class="custom-control-label" for="sortByAsc">Ascending</label>
                                                </div>

                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="sortByDesc" name="sortBy" class="custom-control-input" value="desc"  @if ($filter->sortBy == 'desc') checked @endif>
                                                    <label class="custom-control-label" for="sortByDesc">Descending</label>
                                                </div>
                                            </div>
{{--                                            <div class="form-group">--}}
{{--                                                <div class="custom-control custom-checkbox">--}}
{{--                                                    <input type="checkbox" id="showDeleted" name="showDeleted" class="custom-control-input" @if ($filter->showDeleted) checked @endif>--}}
{{--                                                    <label class="custom-control-label" for="showDeleted">Show deleted items</label>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                            <div class="form-group mg-b-40">
                                                <label class="d-block">Items displayed</label>
                                                <input id="displaySize" type="text" class="js-range-slider" name="perPage" value="{{ $filter->perPage }}"/>
                                            </div>
                                            <button id="filter" type="button" class="btn btn-sm btn-primary">Apply filters</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="list-search d-inline">
                                    <div class="dropdown d-inline mg-r-10">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <button id="requestApproved"  class="dropdown-item">Approved</button>
                                            <button id="requestDenied" class="dropdown-item">Denied</button>
                                            <form id="requestApprovedForm" method="POST" action="{{ route('videos.download-requests.update_status_approved') }}">
                                                @method('PUT')
                                                @csrf
                                                <input name="ids" id="requestApprovedIds" type="hidden">
                                            </form>
                                            <form id="requestDeniedForm" method="POST" action="{{ route('videos.download-requests.update_status_denied') }}">
                                                @method('PUT')
                                                @csrf
                                                <input name="ids" id="requestDeniedIds" type="hidden">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto bd-highlight mg-t-10 mg-r-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-r-10">
                                    <input name="search" type="search" id="search" class="form-control" placeholder="Search" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                                <a class="btn btn-success btn-sm mg-b-5" href="javascript:void(0)" data-toggle="modal" data-target="#advanceSearchModal">Advanced Search</a>
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
                                    <th style="width: 5%;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                            <label class="custom-control-label" for="checkbox_all"></label>
                                        </div>
                                    </th>
                                    <th style="width: 10%;">Name</th>
                                    <th style="width: 10%;">Email</th>
                                    <th style="width: 10%;">Video</th>
                                    <th style="width: 10%;">URL</th>
                                    <th style="width: 10%;">Date</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 25%;overflow: hidden;">Message</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($requests as $request)
                                <tr id="row{{$request->id}}" @if($request->is_approved == 0) class="row_cb" @endif>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input @if($request->is_approved == 0) cb @endif"  id="cb{{ $request->id }}" @if($request->is_approved == 0) data-id="{{ $request->id }}" @else disabled @endif>
                                            <label class="custom-control-label" for="cb{{ $request->id }}"></label>
                                        </div>
                                    </th>
                                    <td>{{ $request->name }}</td>
                                    <td>{{ $request->email }} </td>
                                    <td><a target="_blank" href="{{route('videos.front.show',$request->video->slug)}}">{{ $request->video->title }}</a></td>
                                    <td>{{ $request->url }} </td>
                                    <td>{{ $request->created_at->diffForHumans() }} </td>
                                    <td>{{ ucfirst(strtolower($request->status)) }} </td>
                                    <td>{{ $request->message }}</td>
                                    <td>
                                        @if($request->is_approved==0)

                                            <nav class="nav table-options justify-content-end">
                                                <a class="nav-link" href="{{ route('videos.download-requests.approved_request',$request->id) }}" title="Approve Request"><i data-feather="check"></i></a>
                                                <a class="nav-link" onclick="var r = confirm('Are you sure you want to deny this request?');
                                                if(r == true) window.location.replace('{{ route('videos.download-requests.deny_request',$request->id) }}');" href="#" title="Deny Request"><i data-feather="x"></i></a>
                                            </nav>
                                        @elseif($request->is_approved==1)
                                            <nav class="nav table-options justify-content-end">
                                                <a class="nav-link" href="javascript:void(0)" onclick="show_video_url('{{$request->url}}');" title="Show Download URL"><i data-feather="eye"></i></a>

                                            </nav>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" style="text-align: center;"> <p class="text-danger">No video requests found.</p></td>
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
                    @if ($requests->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">Showing 0 of 0 items</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} of {{ $requests->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $requests->appends((array) $requests)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="videos" name="videos">
        <input type="text" id="status" name="status">
    </form>


    <div id="advanceSearchModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form role="form" id="advanceFilterForm" method="GET" action="{{route('videos.index.advance-search')}}">
                    <div class="modal-header">
                        <h4 class="modal-title">Advanced Search</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Name</label>
                                    <div>
                                        <input type="text" class="form-control input-sm" name="name" value="{{ $advanceSearchData->name }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <div>
                                        <input type="text" class="form-control input-sm" name="email" value="{{ $advanceSearchData->email }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Message</label>
                                    <div>
                                        <input type="text" class="form-control input-sm" name="message" value="{{ $advanceSearchData->message }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Video</label>
                                    <div>
                                        <select class="form-control input-sm" name="video_id">
                                            <option value="">- All Video -</option>
                                            @foreach ($uniqueRequestsByVideo as $request)
                                                <option value="{{ $request->video_id }}" @if ($advanceSearchData->video_id != null && $advanceSearchData->video_id == $request->video_id) selected @endif>{{ $request->video->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Status</label>
                                    <div>
                                        <select class="form-control input-sm" name="is_approved">
                                            <option value="">- All Status -</option>
                                            <option value="0" @if ($advanceSearchData->is_approved != null && $advanceSearchData->is_approved == 0) selected @endif>Pending only</option>
                                            <option value="1" @if ($advanceSearchData->is_approved == 1) selected @endif>Approve only</option>
                                            <option value="2" @if ($advanceSearchData->is_approved == 2) selected @endif>Denied only</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label" for="updated_at1">Date Modified (From)</label>
                                            <input type="date" class="form-control input-sm" id="updated_at1" name="updated_at1" value="{{ $advanceSearchData->updated_at1 }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label" for="updated_at2">Date Modified (To)</label>
                                            <input type="date" class="form-control input-sm" id="updated_at2" name="updated_at2" value="{{ $advanceSearchData->updated_at2 }}" min="{{ $advanceSearchData->updated_at1 }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-info" href="{{ route('videos.download-requests') }}">Reset</a>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <input type="submit" value="{{__('common.search')}}" class="btn btn-success">
                    </div>
                </form>
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
    <div class="modal effect-scale" id="video_url_modal" tabindex="-1" role="dialog" aria-labelledby="video_url_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Video Url</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="video_url_content"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-approved-many" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('video.approved_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('video.approved_confirmation')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnApprovedMany">Yes, approve</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-denied-many" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('video.denied_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('video.denied_confirmation')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnDeniedMany">Yes, deny</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        let listingUrl = "{{ route('videos.download-requests') }}";
        let advanceListingUrl = "{{ route('videos.download-requests.advance-search') }}";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        /*** handles the changing of status of multiple pages ***/
        function change_status(status){
            var counter = 0;
            var selected_videos = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_videos += fid.substring(2, fid.length)+'|';
            });
            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                if(parseInt(counter)>1){ // ask for confirmation when multiple pages was selected
                    $('#videoStatus').html(status)
                    $('#prompt-update-status').modal('show');

                    $('#btnUpdateStatus').on('click', function() {
                        post_form('{{route('videos.multiple.change.status')}}',status,selected_videos);
                    });

                }
                else{
                    post_form('{{route('videos.multiple.change.status')}}',status,selected_videos);
                }
            }
        }

        let approvedIds;
        $('#requestApproved').on('click', function() {
            if($(".cb:checked").length <= 0){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else {
                approvedIds = [];
                $.each($(".cb:checked"), function() {
                    approvedIds.push($(this).data('id'));
                });

                $('#prompt-approved-many').modal('show');
            }
        });

        $('#btnApprovedMany').on('click', function () {
            $('#requestApprovedIds').val(approvedIds);
            $('#requestApprovedForm').submit();
        });

        let deniedIds;
        $('#requestDenied').on('click', function() {
            if($(".cb:checked").length <= 0){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else {
                deniedIds = [];
                $.each($(".cb:checked"), function() {
                    deniedIds.push($(this).data('id'));
                });

                $('#prompt-denied-many').modal('show');
            }
        });

        $('#btnDeniedMany').on('click', function () {
            $('#requestDeniedIds').val(deniedIds);
            $('#requestDeniedForm').submit();
        });

    </script>
    <script>
        function show_video_url(url){

            $('#video_url_content').html('<a target="_blank" href="{{env('APP_URL')}}/download/video/'+url+'">{{env('APP_URL')}}/download/video/'+url+'</a>');
            $('#video_url_modal').modal('show');
        }
    </script>


@endsection
