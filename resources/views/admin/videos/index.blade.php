@extends('admin.layouts.app')

@section('pagetitle')
Manage Videos
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
                        <li class="breadcrumb-item active" aria-current="page">Videos</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Videos</h4>
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
                                                    <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="title" @if ($filter->orderBy == 'title') checked @endif>
                                                    <label class="custom-control-label" for="orderBy2">Title</label>
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
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" id="showDeleted" name="showDeleted" class="custom-control-input" @if ($filter->showDeleted) checked @endif>
                                                    <label class="custom-control-label" for="showDeleted">{{__('common.show_deleted')}}</label>
                                                </div>
                                            </div>
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
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="change_status('PUBLISHED')">Publish</a>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="change_status('PRIVATE')">Private</a>
                                            <a class="dropdown-item tx-danger" href="javascript:void(0)" onclick="delete_page()">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto bd-highlight mg-t-10 mg-r-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-r-10">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Title" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                                <a class="btn btn-success btn-sm mg-b-5" href="javascript:void(0)" data-toggle="modal" data-target="#advanceSearchModal">Advanced Search</a>
                            </form>
                        </div>
                        <div class="mg-t-10">
                            @if(\App\ViewPermissions::check_permission(Auth::user()->role_id,'admin/videos/create') == 1)
                                <a class="btn btn-primary btn-sm mg-b-20" href="{{ route('videos.create') }}">Add a Video</a>
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
                        <table class="table mg-b-0 table-light table-hover" style="table-layout: fixed;word-wrap: break-word;">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                            <label class="custom-control-label" for="checkbox_all"></label>
                                        </div>
                                    </th>
                                    <th style="width: 20%;overflow: hidden;">Title</th>
                                    <th style="width: 25%;">Description</th>
                                    <th style="width: 18%;">Tags</th>
                                    <th style="width: 10%;">Visibility</th>
                                    <th style="width: 10%;">Category</th>
                                    <th style="width: 12%;">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($videos as $video)
                                @php $tags = explode(',', $video->tags); @endphp
                                <tr id="row{{$video->id}}">
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{ $video->id }}">
                                            <label class="custom-control-label" for="cb{{ $video->id }}"></label>
                                        </div>
                                    </th>
                                    <td>
                                        <strong @if($video->trashed()) style="text-decoration:line-through;" @endif> {{ $video->title }}</strong>
                                    </td>
                                    <td>{{ str_limit(str_replace(['<p>','</p>'],'',$video->description), 30, $end = '...') }}</td>
                                    <td>
                                        @foreach($tags as $tag)
                                            <span class="badge badge-primary">{{ $tag }}</span>
                                        @endforeach
                                    </td>

                                    <td>{{ ucfirst($video->status)}}</td>
                                    <td>{{ \App\VideoCategory::get_category($video->video_category) }}</td>

                                    <td>
                                        @if($video->trashed())
                                            <nav class="nav table-options justify-content-end">
                                                    <a class="nav-link" href="{{route('videos.restore',$video->id)}}" title="Restore this video"><i data-feather="rotate-ccw"></i></a>
                                            </nav>
                                        @else
                                            <nav class="nav table-options justify-content-end">
                                                <a class="nav-link" target="_blank" href="{{ $video->get_url() }}" title="View Video"><i data-feather="eye"></i></a>
                                                <a class="nav-link" href="{{ route('videos.edit',$video->id) }}" title="Edit Video"><i data-feather="edit"></i></a>
                                                <a class="nav-link" href="javascript:void(0)" onclick="delete_one_video({{$video->id}})" title="Delete Video"><i data-feather="trash"></i></a>
                                                <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="settings"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if($video->status == 'PUBLISHED')
                                                        <a class="dropdown-item" href="{{route('video.change-status',[$video->id,'PRIVATE'])}}" > Private</a>
                                                    @else
                                                        <a class="dropdown-item" href="{{route('video.change-status',[$video->id,'PUBLISHED'])}}"> Publish</a>
                                                    @endif
                                                </div>
                                            </nav>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center;"> <p class="text-danger">No videos found.</p></td>
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
                    @if ($videos->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $videos->firstItem() }} to {{ $videos->lastItem() }} of {{ $videos->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $videos->appends((array) $filter)->links() }}
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
                                    <label class="control-label">Title</label>
                                    <div>
                                        <input type="text" class="form-control input-sm" name="title" value="{{ $advanceSearchData->title }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Description</label>
                                    <div>
                                        <input type="text" class="form-control input-sm" name="description" value="{{ $advanceSearchData->description }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tags</label>
                                    <div>
                                        <input type="text" class="form-control input-sm" name="tags" value="{{ $advanceSearchData->tags }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Visibility</label>
                                    <div>
                                        <select class="form-control input-sm" name="status">
                                            <option value="">- Publish & Private -</option>
                                            <option value="published" @if ($advanceSearchData->status == 'publish') selected @endif>Publish only</option>
                                            <option value="private" @if ($advanceSearchData->status == 'private') selected @endif>Private only</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Category</label>
                                    <div>
                                        <select class="form-control input-sm" name="video_category">
                                            <option value="">- All Categories -</option>
                                            @foreach ($uniqueVideoByCategory as $video)
                                                <option value="{{ $video->category->id }}" @if ($advanceSearchData->video_category == $video->category->id) selected @endif>{{ $video->category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Modified By</label>
                                    <div>
                                        <select class="form-control input-sm" name="user_id">
                                            <option value="">- All Users -</option>
                                            @foreach ($uniqueVideoByUser as $video)
                                                <option value="{{ $video->user->id }}" @if ($advanceSearchData->user_id == $video->user->id) selected @endif>{{ $video->user->full_name }}</option>
                                            @endforeach
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
                        <a class="btn btn-info" href="{{ route('videos.index') }}">Reset</a>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <input type="submit" value="{{__('common.search')}}" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.delete_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('common.delete_confirmation')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnDelete">Yes, Delete</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-multiple-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.delete_mutiple_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{__('common.delete_mutiple_confirmation')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnDeleteMultiple">Yes, Delete</button>
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

    <div class="modal effect-scale" id="prompt-update-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.update_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    You are about to <span id="videoStatus"></span> this item. Do you want to continue?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnUpdateStatus">Yes, Update</button>
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
        let listingUrl = "{{ route('videos.index') }}";
        let advanceListingUrl = "{{ route('videos.index.advance-search') }}";
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
                        post_form("{{route('videos.multiple.change.status')}}",status,selected_videos);
                    });
                }
                else{
                    post_form("{{route('videos.multiple.change.status')}}",status,selected_videos);
                }
            }
        }
        function post_form(url,status,videos){
            $('#posting_form').attr('action',url);
            $('#videos').val(videos);
            $('#status').val(status);
            $('#posting_form').submit();
        }
        function delete_page(){
            var counter = 0;
            var selected_pages = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_pages += fid.substring(2, fid.length)+'|';
            });
            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                $('#prompt-multiple-delete').modal('show');
                $('#btnDeleteMultiple').on('click', function() {
                    post_form("{{route('admin.videos.multiple.delete')}}",'',selected_pages);
                });
            }
        }
        function delete_one_video(id){
            $('#prompt-delete').modal('show');
            $('#btnDelete').on('click', function() {
                post_form("{{route('videos.single.delete')}}",'',id);
            });
        }
        $('.cb').change(function() {
            var id = ($(this).attr('id')).replace("cb", "");
            if(this.checked) {
                $('#row'+id).addClass("row-selected");
            }
            else{
                $('#row'+id).removeClass("row-selected");
            }
        });
    </script>


@endsection
