@extends('admin.layouts.app')

@section('pagetitle')
    Manage Video Category
@endsection

@section('pagecss')
<link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
<style>
    .table {
        table-layout: fixed;
        word-wrap: break-word;
        /*border-collapse: separate;*/
        /*border-spacing:0 12px;*/
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
                        <li class="breadcrumb-item active" aria-current="page">Video Category</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Video Categories</h4>
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
                                    <form class="pd-20">
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">Sort by</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="sortPage" name="sortFilter" class="custom-control-input" @if(($_GET['sort'] ?? '') == 'name') checked="checked" @endif>
                                                <label class="custom-control-label" for="sortPage">Category Name</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">Sort order</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderAsc" name="orderFilter" class="custom-control-input" @if(($_GET['order'] ?? '') == 'asc') checked="checked" @endif>
                                                <label class="custom-control-label" for="orderAsc">Ascending</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderDesc" name="orderFilter" @if(($_GET['order'] ?? '') == 'desc') checked="checked" @endif class="custom-control-input">
                                                <label class="custom-control-label" for="orderDesc">Descending</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="showDeleted" name="showDeleted" class="custom-control-input" @if(($_GET['showDeleted'] ?? '') == 'show') checked="checked" @endif>
                                                <label class="custom-control-label" for="showDeleted">{{__('common.show_deleted')}}</label>
                                            </div>

                                        </div>
                                        <div class="form-group mg-b-40">
                                            <label class="d-block">Items displayed</label>
                                            <input type="text" class="js-range-slider" id="pageLimit" name="pageLimit" value="{{$_GET['pageLimit'] ?? "10"}}" />
                                        </div>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-primary filter">Apply filters</a>
                                    </form>
                                </div>
                            </div>
                            <div class="list-search d-inline">
                                <div class="dropdown d-inline mg-r-10">
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item tx-danger" href="javascript:void(0)" onclick="delete_category()">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto bd-highlight mg-t-10">
                            <form class="form-inline" id="search_form" method="get">
                                @csrf
                                <div class="search-form mg-r-10">
                                    <input type="text" name="search" id="search" class="form-control" placeholder="Search" @isset($_GET['search']) value="{{$_GET['search']}}" @endisset>
                                    <button class="btn filter" type="button"><i data-feather="search"></i></button>
                                </div>
                                @if(\App\ViewPermissions::check_permission(Auth::user()->role_id, 'admin/news-categories/create') == 1)
                                    <a class="btn btn-primary btn-sm mg-b-5" href="{{ route('video-categories.create') }}">Create a Category</a>
                                @endif
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
                        <table class="table mg-b-0 table-light table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                            <label class="custom-control-label" for="checkbox_all"></label>
                                        </div>
                                    </th>

                                    <th scope="col" width="90%">Category Name</th>
                                    <th scope="col" width="10%">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)

                                    <tr id="row{{$category->id}}">
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input cb" id="cb{{ $category->id }}">
                                                <label class="custom-control-label" for="cb{{ $category->id }}"></label>
                                            </div>
                                        </th>
                                        <td><strong @if($category->trashed()) style="text-decoration:line-through;" @endif> {{ $category->name }}</strong></td>
                                        <td>
                                            @if($category->trashed())
                                                <nav class="nav table-options">
                                                        <a class="nav-link" href="{{route('video.category.restore',$category->id)}}" title="Restore this category"><i data-feather="rotate-ccw"></i></a>
                                                </nav>
                                            @else
                                                <nav class="nav table-options">
                                                    <a class="nav-link" target="_blank" href="{{route('videos.front.index')}}?type=category&criteria={{$category->id}}" title="View Videos"><i data-feather="eye"></i></a>
                                                    <a class="nav-link" href="{{ route('video-categories.edit',$category->id) }}" title="Edit Category"><i data-feather="edit"></i></a>
                                                    <a class="nav-link" href="javascript:void(0)" onclick="delete_one_category({{$category->id}},'{{$category->name}}')" title="Delete Category"><i data-feather="trash"></i></a>
                                                </nav>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th colspan="4" style="text-align: center;"> <p class="text-danger">No categories found.</p></th>
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
                    @if ($categories->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{($categories->firstItem() ?? 0)}} to {{($categories->lastItem() ?? 0)}} of {{$categories->total()}} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    {{ $categories->appends($param)->links() }}
                </div>
            </div>

        </div>
    </div>



    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="videos" name="videos">
        <input type="text" id="status" name="status">
    </form>

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

@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        let selectedPerPage = "{{ (isset($param['pageLimit'])) ? $param['pageLimit'] : '' }}";
        if (selectedPerPage.toLocaleLowerCase() == 'all') {
            selectedPerPage = 'All';
        }
        let perPage = ["10", "25", "50", "75", "100"];
        let index = $.inArray(selectedPerPage, perPage);
        let selected = (index >= 0) ? index : 0;

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });

        /*** Handles the Select All Checkbox ***/
        $("#checkbox_all").click(function(){
            $('.cb').not(this).prop('checked', this.checked);
        });

        /*** Handles the search form, redirects it to function filter ***/
        $('#search_form').submit(function(e){
            e.preventDefault();
            filter();
        });

        /*** handles the click function on filter classes, redirects it to function filter ***/
        $('.filter').click(function(){
            filter();
        });

        /*** generate the parameters for filtering of pages ***/
        function filter(){
            var url = '';

            if($('#sortPage').is(':checked'))
                url += '&sort=name';
            if($('#sortDate').is(':checked'))
                url += '&sort=updated_at';
            if($('#orderAsc').is(':checked'))
                url += '&order=asc';
            if($('#orderDesc').is(':checked'))
                url += '&order=desc';
            if($('#pageLimit').val())
                url += '&pageLimit='+$('#pageLimit').val();
            if(($('#search').val().length)>0)
                url += '&search='+$('#search').val();
            if($('#showDeleted').is(':checked'))
                url += '&showDeleted=show';
            url = url.substring(1, url.length);

            window.location.href = "{{route('video.category.search')}}?"+url;
        }

        function post_form(url,status,videos){
            $('#posting_form').attr('action',url);
            $('#videos').val(videos);
            $('#status').val(status);
            $('#posting_form').submit();
        }

        function delete_category(){
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
                $('#prompt-multiple-delete').modal('show');
                $('#btnDeleteMultiple').on('click', function() {
                    post_form("{{route('video.category.multiple.delete')}}",'',selected_videos);
                    //post_form('/admin/video-category/delete','',selected_videos);
                });
            }
        }

        function delete_one_category(id,page){
            $('#prompt-delete').modal('show');
            $('#btnDelete').on('click', function() {
                post_form("{{route('video.category.single.delete')}}",'',id);
            });
            // var r = confirm("You're about to delete the "+page+" category, Do you want to continue?");
            // if (r == true) {
            //     post_form('/admin/single-video-category/delete/'+id);
            // } else {
            //     return false;
            // }
        }

        $('.cb').change(function() {
            var id = ($(this).attr('id')).replace("cb", "");
            if(this.checked) {
                $('#row'+id).addClass("table-warning");
            }
            else{
                $('#row'+id).removeClass("table-warning");
            }

        });
    </script>
@endsection
