@extends('admin.layouts.app')

@section('pagetitle')
    Gift Certificate Manager
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
                        <li class="breadcrumb-item active" aria-current="page">Gift Certificate</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Gift Certificate Manager</h4>
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
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="code" @if ($filter->orderBy == 'code') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">Code</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="status" @if ($filter->orderBy == 'status') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">Status</label>
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
                            @if (auth()->user()->has_access_to_route('gift-certificate.multiple.delete'))
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
                            @if (auth()->user()->has_access_to_route('gift-certificate.create'))
                                <a class="btn btn-primary btn-sm mg-b-20" href="{{ route('gift-certificate.create') }}">Create Gift Certificate</a>
                                <a class="btn btn-info btn-sm mg-b-20" href="{{ route('gift-certificate.upload') }}">Batch Upload</a>
                            @endif
                            <a class="btn btn-success btn-sm mg-b-20" href="{{ route('gift-certificate.export') }}">Export All</a>
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
                                <th>Code</th>
                                <th>Amount</th>
                                <th>GC Type</th>
                                <th>Status</th>
                                <th>Sales Transaction</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($giftcertificate as $gc)
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{ $gc->id }}">
                                            <label class="custom-control-label" for="cb{{ $gc->id }}"></label>
                                        </div>
                                    </th>
                                    <td> <strong @if($gc->trashed()) style="text-decoration:line-through;" @endif> {{$gc->code }}</strong></td>
                                    <td>{{ $gc->amount }}</td>
                                    <td>{{ $gc->gc_type }}</td>
                                    <td>{{ $gc->status }}</td>
                                    <td>@if($gc->sales_header_id == NULL) - @else <a href="{{route('sales-transaction.view',$gc->sales_header_id) }}" target="_blank">{{ $gc->sales->order_number }}</a> @endif</td>
                                    <td>
                                        <nav class="nav table-options">
                                            @if($gc->trashed())
                                                @if (auth()->user()->has_access_to_route('gift-certificate.create'))
                                                    <nav class="nav table-options">
                                                        <a class="nav-link" href="{{route('gift-certificate.restore',$gc->id)}}" title="Restore this Gift Certificate"><i data-feather="rotate-ccw"></i></a>
                                                    </nav>
                                                @endif
                                            @else
                                                @if (auth()->user()->has_access_to_route('gift-certificate.edit'))
                                                    <a class="nav-link" href="{{ route('gift-certificate.edit',$gc->id) }}" title="Edit Gift Certificate"><i data-feather="edit"></i></a>
                                                @endif
                                                <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="settings"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if($gc->status=='Unused')
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="change_status({{$gc->id}},'Used')" title="Change Status"> Used</a>
                                                    @endif
                                                    @if (auth()->user()->has_access_to_route('gift-certificate.single.delete'))
                                                        <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="delete_one_category({{$gc->id}},'{{$gc->code}}')" title="Delete Gift Certificate">Delete</a>
                                                    @endif
                                                </div>
                                            @endif
                                        </nav>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="6" style="text-align: center;"> <p class="text-danger">No Gift Certificate found.</p></th>
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
                    @if ($giftcertificate->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $giftcertificate->firstItem() }} to {{ $giftcertificate->lastItem() }} of {{ $giftcertificate->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $giftcertificate->appends((array) $filter)->links() }}
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

    <div class="modal effect-scale" id="prompt-change-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('Insert Sales Transaction')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <input required type="text" class="form-control" name="sales_transaction" id="sales_transaction">
                        <p class="tx-10 text-danger" id="error">
                            <x-error-message inputName="code" />
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" id="btnChangeStatus">Update</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
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
        let listingUrl = "{{ route('gift-certificate.index') }}";
        let searchType = "{{ $searchType }}";
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
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
                    post_form("{{route('gift-certificate.multiple.delete')}}",'',selected_products);
                });
            }
        }

        function delete_one_category(id){
            $('#prompt-delete').modal('show');
            $('#btnDelete').on('click', function() {
                // console.log(id);
                post_form("{{route('gift-certificate.single.delete')}}",'',id);
            });
        }

        function change_status(id,status){
            $('#prompt-change-status').modal('show');
            $('#btnChangeStatus').on('click', function() {
                let sales = $('#sales_transaction').val();
                if($('#sales_transaction').val() == ''){
                    $('#error').text('Please enter sales transaction');
                    $('#sales_transaction').addClass('is-invalid');
                } else {
                    $('#error').text('');
                    $('#sales_transaction').removeClass('is-invalid');
                    post_form("{{route('gift-certificate.change.status')}}",sales,id)
                }
            });
        }

    </script>
@endsection
