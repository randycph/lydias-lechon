@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="d-sm-flex justify-content-between mg-lg-b-25 mg-xl-b-30">
            <div>
                <h4 class="mg-b-0 tx-spacing--1">Manage Job Orders</h4>
            </div>
            <div class="d-none d-md-block">

            </div>
        </div>

        <div class="row row-sm">
            <div class="col-md-12">
                <div class="filter-buttons">
                    <div class="d-md-flex bd-highlight mg-b-10">

                        <form id="filterForm">
                            <table width="100%">
                                <tr>
                                    
                                    <td style="width:14%">
                                        <select name="order_source_filter" id="order_source_filter" class="form-control">
                                            <option value="">Source</option>
                                            @foreach(\App\EcommerceModel\Branch::orderBy('name','asc')->get() as $b)
                                                <option value="{{$b->name}}">{{$b->name}}</option>
                                            @endforeach
                                            <option value="Web">Web</option>
                                            @if(isset($_GET['order_source_filter']) && strlen($_GET['order_source_filter']) > 1)
                                                <option value="{{$_GET['order_source_filter']}}" selected="selected">{{$_GET['order_source_filter']}}</option>
                                            @endif
                                        </select>
                                    </td>
                                                                
                                    <td style="width:14%">
                                        <input @if(isset($filter->start_date)) type="date" value="{{$filter->start_date}}" @else type="text" onfocus="(this.type='date')" @endif class="form-control" name="start_date" placeholder="Start Date (Order)">
                                    </td>
                                    <td style="width:14%">
                                        <input @if(isset($filter->end_date)) type="date" value="{{$filter->end_date}}" @else type="text" onfocus="(this.type='date')" @endif class="form-control" name="end_date" placeholder="End Date (Order)">
                                    </td>
                                    
                                    <td style="width:20%"><input name="search" type="search" id="search" class="form-control"  placeholder="Order, Customer" value="{{ $filter->search }}">
                                       
                                       
                                    </td>                                
                                    <td align="left" style="width:10%">
                                        <div class="bd-highlight">
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{__('common.filters')}}
                                                </button>
                                                <div class="dropdown-menu">
                                                    
                                                        <div class="form-group">
                                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_by')}}</label>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="order_number" @if ($filter->orderBy == 'jo_number') checked @endif>
                                                                <label class="custom-control-label" for="orderBy1">JO Number</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="customer_name" @if ($filter->orderBy == 'customer_name') checked @endif>
                                                                <label class="custom-control-label" for="orderBy2">Customer Name</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="orderBy3" name="orderBy" class="custom-control-input" value="date_needed" @if ($filter->orderBy == 'date_needed') checked @endif>
                                                                <label class="custom-control-label" for="orderBy3">Date Needed</label>
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
                                                        <button style="display:none;" id="filter" type="button" class="btn btn-sm btn-primary">{{__('common.apply_filters')}}</button>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </td>
                                    <td>
                                        <input type="submit" class="btn-xs btn btn-success" value="Search">
                                        <a href="{{ route('joborders.index') }}" class="btn-xs btn btn-info">Reset</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:11%">
                                        <select class="form-control" name="delivery_type">
                                            <option value="">Delivery Type</option>
                                            <option value="Store Pickup" @if(isset($filter->delivery_type) && $filter->delivery_type == 'Store Pickup') selected="selected" @endif>Store Pickup</option>
                                            <option value="Door to door delivery" @if(isset($filter->delivery_type) && $filter->delivery_type == 'Door to door delivery') selected="selected" @endif>Door to door delivery</option>
                                        </select>
                                    </td>   
                                    <td style="width:16%">
                                        <input @if(isset($filter->dn_start_date)) type="date" value="{{$filter->dn_start_date}}" @else type="text" onfocus="(this.type='date')" @endif class="form-control" name="dn_start_date" placeholder="Start Date (Date Needed)">
                                    </td>
                                    <td style="width:16%">
                                        <input @if(isset($filter->dn_end_date)) type="date" value="{{$filter->dn_end_date}}" @else type="text" onfocus="(this.type='date')" @endif class="form-control" name="dn_end_date" placeholder="End Date (Date Needed)">
                                    </td>
                                    <td>
                                         @if (auth()->user()->has_access_to_route('joborders.create'))
                                            <a class="btn btn-primary btn-sm" style="width: 100%" href="{{route('joborders.create')}}">Create a Job Order</a>
                                        @endif
                                    </td>
                                    <td colspan="3">
                                         @if (auth()->user()->has_access_to_route('joborders.create-pantaga-or-display'))
                                            <a class="btn btn-primary btn-sm" style="width: 100%" href="{{route('joborders.create-pantaga-or-display')}}">Create a Pantaga/Display</a>
                                        @endif
                                    </td>
                                    
                                </tr>
                            </table>
                        </form>


                        <div class="bd-highlight mg-r-10" style="display:none;">
                            <div class="dropdown d-inline mg-r-5">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{__('common.filters')}}
                                </button>
                                <div class="dropdown-menu">
                                    <form id="filterForm">
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_by')}}</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="delivery_date" @if ($filter->orderBy == 'delivery_date') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">Delivery Date</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="schedule_type" @if ($filter->orderBy == 'schedule_type') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">Schedule Type</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy3" name="orderBy" class="custom-control-input" value="customer_name" @if ($filter->orderBy == 'customer_name') checked @endif>
                                                <label class="custom-control-label" for="orderBy3">Customer Name</label>
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
                                        <div class="form-group mg-b-40">
                                            <label class="d-block">{{__('common.item_displayed')}}</label>
                                            <input id="displaySize" type="text" class="js-range-slider" name="perPage" value="{{ $filter->perPage }}"/>
                                        </div>
                                        <button id="filter" type="button" class="btn btn-sm btn-primary">{{__('common.apply_filters')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="ml-auto bd-highlight mg-t-3 mg-r-10" style="display:none;">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Order # or Customer name" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div style="display:none;">
                            @if (auth()->user()->has_access_to_route('joborders.create'))
                                <a class="btn btn-primary btn-sm" href="{{route('joborders.create')}}">Create a Job Order</a>
                            @endif

                            @if (auth()->user()->has_access_to_route('joborders.create-pantaga-or-display'))
                                <a class="btn btn-primary btn-sm" href="{{route('joborders.create-pantaga-or-display')}}">Create a Pantaga/Display</a>
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
                                    <th style="width:10%;">JO No.</th>
                                    <th style="width:15%;">Customer/Branch</th>
                                    <th style="width:15%;">Product</th>
                                    <th style="width:10%;">Qty</th>
                                    <th style="width:10%;">Price</th>
                                    <th style="width:10%;">Source</th> 
                                    <th style="width:10%;">Category</th>
                                    <th style="width:10%;">Date Needed</th>
                                    <th style="width:10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($joborders as $jo)
                                <tr id="row{{$jo->id}}">
                                    <td>
                                        <strong>{{ $jo->jo_number }}</strong>
                                    </td>
                                    <td style="width:10%;"> @if($jo->sales_detail_id == 0){{ $jo->customer_address ?? '' }} @else {{ $jo->customer_name ?? '' }} @endif</td>
                                    <td style="width:15%;">{{ $jo->product->name }} {{ $jo->product->weight }}</td>
                                    <td style="width:15%;">{{ number_format($jo->qty,1) }}</td>
                                    <td style="width:10%;"> @if($jo->sales_detail_id > 0){{ number_format(($jo->product->price * $jo->qty),2) ?? '' }} @endif</td>
                                    <td style="width:10%;">{{ $jo->order_source }}</td>
                                   
                                    
                                    <td style="width:10%;">{{ $jo->jo_category }}</td>
                                    <td>{{ date('F d, Y h:i A',strtotime($jo->date_needed)) }}</td>
                                    <td style="width:10%;" class="text-right">
                                        <div class="btn-group pd-10" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-secondary btn-xs" data-toggle="collapse" data-target="#sales-details_{{$jo->id}}" class="accordion-toggle">Details</button>
                                            @if($jo->date_needed > date('Y-m-d H:i:s') && $jo->sales_detail_id == 0)
                                                <a href="{{route('joborders.edit-pantaga-or-display',$jo->id)}}" class="btn btn-success btn-xs">Edit</a>
                                            @endif

                                            @if (auth()->user()->has_access_to_route('joborders.destroy') || auth()->user()->has_access_to_route('jo.delete'))
                                                <a href="javascript:;" data-toggle="modal" data-id="{{$jo->id}}" data-target="#prompt-delete-joborder" class="btn btn-danger btn-xs delete-joborder">Delete</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="hiddenRow">
                                        <div class="accordian-body collapse" id="sales-details_{{$jo->id}}">
                                            <div class="autoship-table">
                                                <div class="row" style="margin-left:20px;margin-top:20px;">
                                                    <div class="col-md-4">
                                                        @php
                                                            $paella_price = 0.00;
                                                            $delivery_fee = 0.00;
                                                            $total_price = number_format(($jo->qty*$jo->price)+$paella_price,2);
                                                            if($jo->paella_qty > 0){
                                                                $paella_price = $jo->paella_qty * $jo->paella_price;
                                                            }

                                                            if($jo->sales_detail_id > 0){
                                                                $delivery_fee = number_format($jo->sales_detail->header->delivery_fee_amount,2);
                                                                $total_price = number_format(($jo->sales_detail->qty*$jo->sales_detail->price)+$paella_price+$jo->sales_detail->header->delivery_fee_amount,2);
                                                            }

                                                        @endphp
                                                        @if($jo->sales_detail_id > 0)
                                                            <p>Qty: {{ number_format($jo->qty,2) }}</p>
                                                            <p>Price: &#8369; {{ number_format($jo->price,2) }}</p>
                                                            <p>Delivery Fee: &#8369; {{ $delivery_fee }}</p>
                                                            <p>Total Price: &#8369; {{ $total_price }}</p>
                                                        @endif


                                                    </div>
                                                    <div class="col-md-8">
                                                        @if($jo->sales_detail_id <= 0)
                                                            <p>Qty: {{ number_format($jo->qty,2) }}</p>
                                                        @endif
                                                        <p>Delivery Address: {{ $jo->customer_delivery_adress }}</p>
                                                        <p>Instruction: {{ $jo->remarks }}</p>
                                                        @if($jo->sales_detail_id > 0)
                                                        <p>Sales #: {{ $jo->sales_number }}</p>
                                                        <p><a target="_blank" class="btn btn-xs btn-success" href="{{ route('sales-transaction.view',$jo->sales_detail->sales_header_id) }}">View Order Summary</a></p>
                                                        @endif
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr><th colspan="6"><center>No job order found.</center></th></tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                    <!-- table-responsive -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="mg-t-5">
                    @if ($joborders->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $joborders->firstItem() }} to {{ $joborders->lastItem() }} of {{ $joborders->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $joborders->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal effect-scale" id="prompt-delete-joborder" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Delete Job Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('jo.delete')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p>You are about to delete this Job Order. Do you want to continue?</p>
                        <input type="hidden" name="jo_id" id="jo_id">
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
        let listingUrl = "{{ route('joborders.index') }}";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')

<script>
    $(document).on('click','.cancel-schedule',function(){
        $('#sales_id').val($(this).data('sales_id'));
        $('#schedule_id').val($(this).data('schedule_id'));
    });

    $(".js-range-slider").ionRangeSlider({
        grid: true,
        from: selected,
        values: perPage
    });

    $(document).on('click','.delete-joborder',function(){
        $('#jo_id').val($(this).data('id'));
    });
</script>
@endsection
