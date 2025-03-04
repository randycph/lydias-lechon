@extends('admin.layouts.app')

@section('pagetitle')
    Branch Manager
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
                        <li class="breadcrumb-item active" aria-current="page">Branch</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Branch Manager</h4>
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
                                                <label class="custom-control-label" for="orderBy1">Branch Name</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="name" @if ($filter->orderBy == 'name') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">Contact Person</label>
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
                                                <input type="checkbox" id="showDeleted" name="showDeleted" class="custom-control-input" @if ($filter->showDeleted) checked @endif>
                                                <label class="custom-control-label" for="showDeleted">{{__('common.show_deleted')}}</label>
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
                            @if (auth()->user()->has_access_to_route('branch.multiple.delete'))
                                <div class="list-search d-inline">
                                    <div class="dropdown d-inline mg-r-10">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item tx-danger" href="javascript:void(0)" onclick="delete_category()">{{__('common.delete')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="ml-auto bd-highlight mg-t-10 mg-r-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-r-10">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Name" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="mg-t-10">
                            @if (auth()->user()->has_access_to_route('branch.create'))
                                <a class="btn btn-primary btn-sm mg-b-20" href="{{route('branch.create')}}">Create a Branch</a>
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
                            <tr>
                                <th style="width: 10%;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                        <label class="custom-control-label" for="checkbox_all"></label>
                                    </div>
                                </th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Address</th>
                                <th>Contact Number</th>
                                <th>Hotline</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($branches as $branch)
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{ $branch->id }}">
                                            <label class="custom-control-label" for="cb{{ $branch->id }}"></label>
                                        </div>
                                    </th>
                                    <td> <strong @if($branch->trashed()) style="text-decoration:line-through;" @endif> {{$branch->name }}</strong></td>
                                    <td>{{ $branch->code }}</td>
                                    <td style="width:20%;">{{ $branch->address }}</td>
                                    <td>{{ $branch->contact_nos }}</td>
                                    <td><a href="javascript:void(0)" onclick="show_numbers({{$branch->id}});">{{ $branch->numbers->count() }}</a></td>
                                    <td>{{ $branch->contact_person }}</td>
                                    <td>{{ $branch->email_address }}</td>
                                    <td>
                                        <nav class="nav table-options">
                                            @if($branch->trashed())
                                                @if (auth()->user()->has_access_to_route('branch.restore'))
                                                    <nav class="nav table-options">
                                                        <a class="nav-link" href="{{route('branch.restore',$branch->id)}}" title="Restore this product"><i data-feather="rotate-ccw"></i></a>
                                                    </nav>
                                                @endif
                                            @else
                                                @if (auth()->user()->has_access_to_route('branch.edit'))
                                                    <a class="nav-link" href="{{ route('branch.edit',$branch->id) }}" title="Edit Product"><i data-feather="edit"></i></a>
                                                @endif
                                                @if (auth()->user()->has_access_to_route('branch.single.delete'))
                                                    <a class="nav-link" href="javascript:void(0)" onclick="delete_one_category({{$branch->id}},'{{$branch->name}}')" title="Delete Product"><i data-feather="trash"></i></a>
                                                @endif
                                            @endif
                                        </nav>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <th colspan="7" style="text-align: center;"> <p class="text-danger">No branch found.</p></th>
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
                    @if ($branches->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} of {{ $branches->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $branches->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="pages" name="pages">
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


    <div class="modal effect-scale" id="prompt-show-numbers" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Contact Numbers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Delete</th>
                            </thead>
                            <tbody id="numbers_div">

                            </tbody>
                        </table>
                    </div>
                    <div class="row" style="margin-top:30px;">
                        <div class="col-md-12">
                            <h5 style="font-size:14px;text-decoration: underline;text-transform: capitalize;">Add new contact number</h5>
                            <form class="form-inline" id="add_number_form">
                                @csrf
                                <input type="hidden" id="br_id" name="br_id">
                                <label class="sr-only" for="inlineFormInputName2">Name</label>
                                <input type="text" required="required" class="form-control mb-4 mr-sm-4" id="inlineFormInputName2" name="name" placeholder="Name">

                                <label class="sr-only" for="inlineFormInputGroupUsername2">Contact Number</label>
                                <div class="input-group mb-4 mr-sm-4">
                                    <input type="text" required="required" class="form-control" id="inlineFormInputGroupUsername2" name="number" placeholder="Contact Number">
                                </div>
                                <button type="submit" class="btn btn-primary mb-4">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal effect-scale" id="prompt-delete-number" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Delete Number</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('branch.delete.number') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p>You are about to delete this number. Do you want to continue?</p>
                        <input type="hidden" name="con_id" id="con_id">
                        <input type="hidden" name="branch_id" id="branch_id">
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
        let listingUrl = "{{ route('branch.index') }}";
        let searchType = "{{ $searchType }}";
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(document).on('click','.delete-number',function(){
            var x = $(this).data('id').split("|");

            $('#con_id').val(x[0]);
            $('#branch_id').val(x[1]);
        });

        $('#add_number_form').submit(function(e){
            e.preventDefault();
            let data = $('#add_number_form').serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                data: data,
                type: "post",
                url: "{{route('branch.add.number')}}",
                success: function(returnData) {

                    $.ajax({
                        type: "POST",
                        url: "{{ route('branch.show.numbers') }}",
                        data: { id : returnData },
                        success: function( response ) {
                            $('#numbers_div').html(response);
                        }
                    });
                    $('#add_number_form').trigger("reset");
                }
            });
        })
        function show_numbers(id){
            $('#br_id').val(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('branch.show.numbers') }}",
                data: { id : id },
                success: function( response ) {

                    $('#numbers_div').html(response);
                    $('#prompt-show-numbers').modal('show');
                }
            });
        }

        function post_form(id,status,pages){

            $('#posting_form').attr('action',id);
            $('#pages').val(pages);
            $('#status').val(status);
            $('#posting_form').submit();
        }

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });

        /*** Handles the Select All Checkbox ***/
        $("#checkbox_all").click(function(){
            $('.cb').not(this).prop('checked', this.checked);
        });

        function delete_category(){
            var counter = 0;
            var selected_products = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_products += fid.substring(2, fid.length)+'|';
            });

            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                $('#prompt-multiple-delete').modal('show');
                $('#btnDeleteMultiple').on('click', function() {
                    post_form("{{route('branch.multiple.delete')}}",'',selected_products);
                });
            }
        }

        function delete_one_category(id){
            $('#prompt-delete').modal('show');
            $('#btnDelete').on('click', function() {
                // console.log(id);
                post_form("{{route('branch.single.delete')}}",'',id);
            });
        }

    </script>
@endsection
