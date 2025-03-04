@extends('admin.layouts.app')

@section('pagetitle')
    Sales Transaction Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
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
                        <li class="breadcrumb-item active" aria-current="page">Sales Transaction</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Sales Transaction Manager</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12">
                <div class="filter-buttons">
                    <div class="d-md-flex bd-highlight">
                        <form id="filterForm">
                        <table width="100%">
                            <tr>
                                
                                <td style="width:10%">
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
                                <td style="width:11%">
                                    <select class="form-control" name="order_status">
                                        <option value="">Order Status</option>
                                        <option value="0" @if(isset($filter->order_status) && $filter->order_status == '0') selected="selected" @endif>Unconfirm</option>
                                        <option value="1" @if(isset($filter->order_status) && $filter->order_status == '1') selected="selected" @endif>Confirmed</option>
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
                                                            <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="order_number" @if ($filter->orderBy == 'order_number') checked @endif>
                                                            <label class="custom-control-label" for="orderBy1">Order Number</label>
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
                                    <a href="{{ route('sales-transaction.index') }}" class="btn-xs btn btn-info">Reset</a>
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
                                
                            </tr>
                        </table>
                         </form>
                        

                        <div class="ml-auto bd-highlight mg-t-10" style="display:none;">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-b-5">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Order # or Customer" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
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
                        <table class="table mg-b-0 table-light table-hover table-striped">
                            <thead>
                            <tr>
                                <th style="width: 10%;display:none;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                        <label class="custom-control-label" for="checkbox_all"></label>
                                    </div>
                                </th>
                                <th>Order Number</th>
                                <th>Customer</th>
                                <th>Source</th>
                                <th>Order Date</th>
                                <th>Date Needed</th>
                                <th>Delivery Type</th>
                                <th>Delivery Status</th>
                                <th style="display:none;">Payment Type</th>
                                <th>Payment Status</th>
                                <th>is Confirmed</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sales as $sale)
                                @php
                                    $date_needed = $sale->items->first();
                                    $dateneeded = '';
                                    $payment_types = '';
                                    $locationed = '';
                                    if(!empty($date_needed)){
                                        $dateneeded = date('Y-m-d H:i A',strtotime($date_needed->delivery_date));
                                    }
                                    if(!empty($sale->payments)){
                                        $ptype = $sale->payments->unique('payment_type');
                                        foreach($ptype as $p){
                                            $payment_types .= $p->payment_type.",";
                                        }
                                    }
                                    if($sale->delivery_type == 'Door to door delivery'){
                                        $locationed = $sale->customer_location;
                                    }
                                    if($sale->delivery_type == 'Store Pickup'){
                                        $locationed = $sale->outlet;
                                    }

                                    $is_allowed_delivered = 1;
                                    if($dateneeded > date('Y-m-d H:i:s')){
                                        $is_allowed_delivered = 0;
                                    }
                                @endphp
                                <tr style="height:30px; @if($sale->trashed()) background-color:#FFA07A; @endif">
                                    <td style="display:none;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{ $sale->id }}">
                                            <label class="custom-control-label" for="cb{{ $sale->id }}"></label>
                                        </div>
                                    </td>
                                    <th> <strong @if($sale->trashed()) style="text-decoration:line-through;" @endif> <a title="View Sales Summary" target="_blank" href="{{ route('sales-transaction.view',$sale->id) }}">{{$sale->order_number }}</a><br></strong></th>
                                    <td>{{ $sale->customer_name }}</td>
                                    <td>{{ $sale->order_source }}</td>
                                    <td>{{ date('Y-m-d H:i A',strtotime($sale->created_at)) }}</td>
                                    <td>{{ $dateneeded }}</td>
                                    <td>{{ $sale->delivery_type }}</td>
                                    <td><a href="{{route('admin.report.delivery_report',$sale->id)}}" target="_blank">{{$sale->delivery_status}}</a></td>
                                    <td style="display:none;">{{ rtrim($payment_types,",") }}</td>
                                    <td>{{ $sale->Paymentadminstatus }} <a href="#" title="Pending payments" onclick="show_added_payments('{{$sale->id}}');"><span class="badge badge-info">{{$sale->Paymentspendingtotal}}</span></a></td>
                                    <td align="center">
                                        @if($sale->isConfirm==1)
                                            <i data-feather="check-square"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if(\App\EcommerceModel\SalesPayment::check_if_has_added_payments($sale->id) == 1)
                                            <a href="javascript:;" onclick="show_added_payments('{{$sale->id}}');">{{ number_format($sale->gross_amount,2) }}</a>
                                        @else
                                            {{ number_format($sale->gross_amount,2) }}
                                        @endif
                                    </td>
                                     <td>{{ number_format((\App\EcommerceModel\SalesHeader::balance($sale->id)),2) }}</td>
                                    <td width="10%">
                                         @php $forecasters = [3,13]; $forecasters = [13]; @endphp
                                        @if(!in_array(auth()->user()->role_id, $forecasters))                                    
                                            <nav class="nav table-options">
                                                @if($sale->trashed())
                                                    @if (auth()->user()->has_access_to_route('sales-transaction.restore'))
                                                        <nav class="nav table-options">
                                                            <a class="nav-link" href="{{route('sales-transaction.restore', $sale->id)}}" title="Restore this Sales Transaction"><i data-feather="rotate-ccw"></i></a>
                                                        </nav>
                                                    @endif
                                                @else


                                                    <div class="nav-item dropdown">
                                                        <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i data-feather="eye"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">

                                                                <a class="dropdown-item" title="View Sales Summary" target="_blank" href="{{ route('sales-transaction.view',$sale->id) }}">View Sales Summary</a>
                                                            @if($sale->isConfirm != 1)
                                                                @if(auth()->user()->role_id == 2 || auth()->user()->role_id == 1)
                                                                <a class="dropdown-item"  href="javascript:void(0);" onclick="confirm_order({{$sale->id}},'{{ number_format((\App\EcommerceModel\SalesHeader::balance($sale->id)),2) }}');" title="Confirm Order" >Confirm Order</a>
                                                                @endif
                                                            @endif
                                                            
                                                            <a class="dropdown-item"  href="{{ route('sales.update_details',$sale->id) }}" title="Update Sales Details & Items" >Update Sales Details</a>
                                                            
                                                            @if($dateneeded > date('Y-m-d H:i:s'))
                                                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="delete_sales({{$sale->id}},'{{$sale->order_number}}')" title="Delete Transaction">Delete</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="nav-item dropdown">
                                                        <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i data-feather="credit-card"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            @if($sale->status == 'UNPAID')
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#prompt-change-status" title="Update Sales Transaction" data-id="{{$sale->id}}" data-status="PAID">Paid</a>

                                                            @endif

                                                                <a class="dropdown-item" style="display: none;" target="_blank" href="{{ route('sales-transaction.view_payment',$sale->id) }}" title="Show payment" data-id="{{$sale->id}}">Sales Payment</a>

                                                                @if(\App\EcommerceModel\SalesPayment::check_if_has_remaining_unpaid($sale->gross_amount,$sale->id) == 1)
                                                                    <a class="dropdown-item" href="javascript:;" onclick="addPayment('{{$sale->id}}','{{\App\EcommerceModel\SalesPayment::get_remaining_unpaid($sale->gross_amount,$sale->id)}}');">Add Payment</a>
                                                                @endif

                                                                @if($dateneeded > date('Y-m-d H:i:s'))
                                                                    @if(auth()->user()->role_id == 2 || auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                                                        <a class="dropdown-item" href="javascript:;" onclick="addDelFee({{$sale->id}},'{{$sale->order_number}}',{{$sale->delivery_fee_amount}});">Update Delivery Fee</a>
                                                                    @endif
                                                                @endif

                                                                <a class="dropdown-item" href="javascript:;" onclick="show_added_payments('{{$sale->id}}')">View Payments</a>


                                                                @if($sale->payment_type == 'xxxxxx')
                                                                <a class="dropdown-item" href="{{route('staff-edit-payment',$sale->id)}}">Update Sales Payment</a>
                                                                @endif



                                                        </div>
                                                    </div>
                                                    <div class="nav-item dropdown">
                                                        <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i data-feather="truck"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            @if (auth()->user()->has_access_to_route('sales-transaction.quick_update'))
                                                                <a class="dropdown-item" href="javascript:void(0);" onclick="change_delivery_status({{$sale->id}}, {{$is_allowed_delivered}})" title="Update Delivery Status" data-id="{{$sale->id}}">Update Delivery Status</a>
                                                            @endif
                                                                <a class="dropdown-item" href="{{route('admin.report.delivery_report',$sale->id)}}" target="_blank" >Print Delivery Receipt</a>
                                                                <a class="dropdown-item" href="javascript:void(0);" onclick="show_delivery_history({{$sale->id}})" title="Update Delivery Status" data-id="{{$sale->id}}">Show Delivery History</a>


                                                            @if (auth()->user()->has_access_to_route('sales-transaction.destroy'))
                                                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="delete_sales({{$sale->id}},'{{$sale->order_number}}')" title="Delete Transaction">Delete</a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                @endif
                                            </nav>
                                        @else
                                            <nav class="nav table-options">

                                                    <div class="nav-item dropdown">
                                                        <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i data-feather="eye"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">

                                                                <a class="dropdown-item" title="View Sales Summary" target="_blank" href="{{ route('sales-transaction.view',$sale->id) }}">View Sales Summary</a>
                                                                 <a class="dropdown-item" href="javascript:;" onclick="show_added_payments('{{$sale->id}}')">View Payments</a>


                                                           
                                                        </div>
                                                    </div>
                                                   

                                            </nav>
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="10" style="text-align: center;"> <p class="text-danger">No Sales Transaction found.</p></th>
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
                    @if ($sales->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $sales->appends((array) $filter)->links() }}
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
            <form action="" id="frm_delete" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.delete_confirmation_title')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                            @csrf
                            @method('DELETE ')
                        <input type="hidden" name="id_delete" id="id_delete">
                        <p>Are you sure you want to delete this transaction no: <span id="delete_order_div"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger" id="btnDelete">Yes, Delete</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-change-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" class="form-control" name="id" id="id">
                        <input type="hidden" class="form-control" name="status" id="editStatus">
                        <div class="form-group">
                            <label class="d-block">Payment source *</label>
                            <select id="payment_type" class="selectpicker mg-b-5" name="payment_type" data-style="btn btn-outline-light btn-md btn-block tx-left" title="- None -" data-width="100%">
                                <option value="Gift Certificate">Gift Certificate</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Cash">Cash</option>
                            </select>
                            <p class="tx-10 text-danger" id="error">
                                @hasError(['inputName' => 'payment_type'])
                                @endhasError
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="d-block">Amount *</label>
                            <input type="text" class="form-control" name="amount" id="amount">
                            <p class="tx-10 text-danger" id="error">
                                @hasError(['inputName' => 'amount'])
                                @endhasError
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="d-block">Payment date *</label>
                            <input type="date" class="form-control" name="payment_date" id="payment_date">
                            <p class="tx-10 text-danger" id="error">
                                @hasError(['inputName' => 'payment_date'])
                                @endhasError
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="d-block">Receipt number *</label>
                            <input type="text" class="form-control" name="receipt_number" id="receipt_number">
                            <p class="tx-10 text-danger" id="error">
                                @hasError(['inputName' => 'receipt_number'])
                                @endhasError
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary">Update</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-change-delivery-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('Delivery Status')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="dd_form" method="POST" action="{{route('sales-transaction.delivery_status')}}">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="delivery_status">Status</label>
                            <select id="delivery_status" class="form-control mg-b-5" name="delivery_status"  data-width="100%" required="required">
                                <option value="">- Select -</option>
                                <option value="Open Date">Open Date</option>
                                <option value="Scheduled for Processing">Scheduled for Processing</option>
                                <option value="Processing">Processing</option>
                                <option value="Ready For delivery">Ready For delivery</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Returned">Returned</option>
                            </select>
                            <p class="tx-10 text-danger" id="error">
                                @hasError(['inputName' => 'delivery_status'])
                                @endhasError
                            </p>
                        </div>
                        <div class="form-group" style="display:none;" id="delivered_by_div">
                            <label for="delivered_by">Delivered by:</label>
                            <input type="text" class="form-control" name="delivered_by" id="delivered_by">
                        </div>
                        <div class="form-group">
                            <label for="delivery_status">Remarks</label>
                            <textarea name="del_remarks" required="required" class="form-control" id="del_remarks" cols="30" rows="4"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="del_id" name="del_id" value="">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
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

    <div class="modal effect-scale" id="prompt-confirm-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" action="{{route('sales.confirm.order')}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Confirm Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <p>
                            This order still has balance of &#8369; <span id="confirm_order_amount"></span>.
                            Please enter the details of this confirmation:
                        </p>
                        <input type="hidden" name="confirm_order_id" id="confirm_order_id">
                        <textarea name="confirm_order_remarks" id="confirm_order_remarks" class="form-control" cols="60" rows="4"></textarea>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger">Yes, Confirm</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
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

    <div class="modal effect-scale" id="prompt-add-delfee" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{route('admin.sales.update_deliveryfee')}}" method="post">
                @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Delivery Fee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="delfee_sales_id" id="delfee_sales_id">
                    <p>Enter Delivery Fee for order#: <span id="delfee_order"></span></p>
                    <input type="number" name="delfee" id="delfee" min="0" step="0.01" value="0.00">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">Submit Delivery Fee</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-add-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form autocomplete="off" action="{{ route('payment.add.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                    <div class="modal-body">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="d-block">Mode of Payment *</label>
                                <select required class="custom-select" name="pamenty_mode" id="mode_of_payment">
                                    <option value="">Select</option>
                                    <option value="Bank Deposit">Bank Deposit</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Check Payment">Check Payment</option>
                                    <option value="COD">COD</option>
                                    <option value="Debit/Credit Card">Debit/Credit Card</option>
                                    <option value="Discount (Promo)">Discount (Promo)</option>
                                    <option value="Discount (VAT)">Discount (VAT)</option>
                                    <option value="Discount (Senior Citizen)">Discount (Senior Citizen)</option>
                                    <option value="Ex-deal">Ex-deal</option>
                                    <option value="Gcash">Gcash</option>
                                    <option value="Gift Certificate">Gift Certificate</option>   
                                    <option value="M Lhuillier">M Lhuillier</option>
                                    <option value="Ok Order">Ok Order</option>
                                    <option value="Online Bank Transfer">Online Bank Transfer</option>
                                    <option value="Open Date Order">Open Date Order</option>
                                    <option value="Oth">Oth</option>
                                    <option value="Paymaya">Paymaya</option>
                                    <option value="Sign-Chit">Sign-Chit</option>      
                                </select>
                                <input type="hidden" id="sales_header_id" name="sales_header_id">
                            </div>
                            <div class="form-group" id="cashdiv">
                                <label class="d-block">Reference # </label>
                                <input type="text" class="form-control" name="ref_no" id="ref_no">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Payment Date *</label>
                                <input required type="text" name="payment_dt" class="form-control" id="payment_dt" placeholder="Choose date" value="{{ old('date') }}">
                                @hasError(['inputName' => 'payment_dt'])@endhasError
                            </div>
                            <div class="form-group">
                                <label class="d-block">Amount *</label>
                                <input required type="number" step="0.01" value="0.00" class="form-control text-right" name="amount" id="payment_amount">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Remarks</label>
                                <textarea name="payment_remark" class="form-control" id="payment_remark" cols="30" rows="4"></textarea>                              
                            </div>
                            <div class="form-group">
                                <label class="d-block">Attachment</label>
                                <input  type="file" class="form-control text-right" name="payment_attachment" id="payment_attachment">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-show-added-payments" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Added Payments</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th>Reference #</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Attachment</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody id="added_payments_tbl">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal effect-scale" id="prompt-show-delivery-history" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Delivery History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Delivered By</th>
                            </thead>
                            <tbody id="delivery_history_tbl">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-confirm-payment-approval" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" action="{{route('approve_payment')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Confirm Payment Approval</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group ccard" style="display:none;">
                            <label class="d-block">Reference#</label>
                            <input type="text" class="form-control" name="confirm_payment_ref" id="confirm_payment_ref">
                        </div>
                        <div class="form-group ccard" style="display:none;">
                            <label class="d-block">Attachment</label>
                            <input type="file" class="form-control" name="confirm_payment_file" id="confirm_payment_file">
                        </div>
                        <p>
                            Please enter payment confirmation remarks:
                        </p>
                        <input type="hidden" name="confirm_payment_id" id="confirm_payment_id">

                        <textarea name="confirm_payment_remarks" id="confirm_payment_remarks" class="form-control" cols="60" rows="4"></textarea>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger">Yes, Confirm</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    

@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script>
        //var dateToday = new Date();
        $(function(){
            'use strict'

            $('#payment_dt').datepicker({
                //minDate: dateToday,
                dateFormat: 'yy-mm-dd',
            });
        });
    </script>

    <script>
        let listingUrl = "{{ route('sales-transaction.index') }}";
        let searchType = "{{ $searchType }}";
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>

        function ui_add_product(x){    
            $('#ui_total_new').val(parseInt($('#ui_total_new').val())+1);
            var y = parseInt($('.uia_tr').length)+1;
         
            var i = x.split("|");
            var pael = '&nbsp;<input type="hidden" value="0" name="uia_paella'+y+'" id="uia_paella'+y+'">';
            if(parseFloat(i[2]) > 0){
                var pael = '<input type="checkbox" onchange="ui_change_qty(\'uia\','+y+');" value="'+i[2]+'" name="uia_paella'+y+'" id="uia_paella'+y+'"> '+addCommas(parseFloat(i[2]).toFixed(2));
            }
            var s = '<tr id="ui_tr'+y+'" class="uia_tr">'+
                '<td><a href="#" class="btn btn-xs btn-danger" onclick="ui_removeitem(\'ui_tr'+y+'\');">x</a></td>'+
                '<td>'+i[0]+'<input name="uia_product'+y+'" value="'+i[3]+'" type="hidden"></td>'+
                '<td>'+
                    '<input type="number" onchange="ui_change_qty(\'uia\','+y+');" class="form-control" title="'+y+'" name="uia_qty'+y+'" min="0" id="uia_qty'+y+'" value="1">'+
                '</td>'+
                '<td>'+
                    pael+
                '</td>'+   
                '<td>'+
                    ''+addCommas(parseFloat(i[1]).toFixed(2))+''+
                    '<input type="hidden" name="uia_price'+y+'" id="uia_price'+y+'" value="'+i[1]+'">'+
                '</td> '+
                '<td>'+
                    '<span id="uia_total'+y+'">'+addCommas(parseFloat(i[1]).toFixed(2))+'</span>'+
                    '<input type="hidden" name="uia_subtotal'+y+'" id="uia_subtotal'+y+'" value="'+i[1]+'">'+
                '</td>'+ 
            '</tr>';
            $('#ui_body').append(s);
        }

        function ui_removeitem(tr){
            var txt;
            var r = confirm("Are you sure you want to remove this item?");
            if (r == true) {
              $('#uia_product'+tr).remove();
              $('#'+tr).remove();
            }
            
        }

        function ui_change_qty(i,x){
            var qty = $('#'+i+'_qty'+x).val();
            var price = $('#'+i+'_price'+x).val();
            var paella = 0;
            if($('#'+i+'_paella'+x).is(':checked')){
                paella = $('#'+i+'_paella'+x).val();
            }
            var subtotal = parseFloat(parseFloat(qty) * parseFloat(price)) + parseFloat(parseFloat(paella) * parseFloat(qty));
            $('#'+i+'_total'+x).html(addCommas(parseFloat(subtotal).toFixed(2)));
        }
        
        function confirm_order(x,y){
            $('#confirm_order_amount').html(y);
            $('#confirm_order_id').val(x);
            $('#prompt-confirm-order').modal('show');
        }

        function confirm_sales_payment(x,paytype,payref){
            $('#confirm_payment_id').val(x);
            if(paytype=='IPAY'){
                $('.ccard').show();
            }else{
                $('.ccard').hide();
            }
            $('#prompt-confirm-payment-approval').modal('show');
        }


        function delete_sales(x,order_number){
            $('#frm_delete').attr('action',"{{route('sales-transaction.destroy',"x")}}");
            $('#id_delete').val(x);
            $('#delete_order_div').html(order_number);
            $('#prompt-delete').modal('show');
        }
        function addPayment(id,balance){
            $('#prompt-add-payment').modal('show');
            $('#sales_header_id').val(id);

            $("#payment_amount").attr({
                "max" : balance
            });
        }
        function addDelFee(id,order,delf){
            // alert(delf);
            $('#delfee_order').html(order);
            $('#delfee_sales_id').val(id);
            $('#delfee').val(delf);
            $('#prompt-add-delfee').modal('show');
        }

        function show_added_payments(id){
            $.ajax({
                type: "GET",
                url: "{{ route('display.added-payments') }}",
                data: { id : id },
                success: function( response ) {
                    $('#added_payments_tbl').html(response);
                    $('#prompt-show-added-payments').modal('show');
                }
            });
        }

        function show_delivery_history(id){
            $.ajax({
                type: "GET",
                url: "{{ route('display.delivery-history') }}",
                data: { id : id },
                success: function( response ) {
                    $('#delivery_history_tbl').html(response);
                    $('#prompt-show-delivery-history').modal('show');
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

        $('#prompt-change-status').on('show.bs.modal', function (e) {
            //get data-id attribute of the clicked element
            let sales = e.relatedTarget;
            let salesId = $(sales).data('id');
            let salesStatus = $(sales).data('status');
            let formAction = "{{ route('sales-transaction.quick_update', 0) }}".split('/');
            formAction.pop();
            let editFormAction = formAction.join('/') + "/" + salesId;
            $('#editForm').attr('action', editFormAction);
            $('#id').val(salesId);
            $('#editStatus').val(salesStatus);

        });

        function change_delivery_status(id,is_allowed_delivered){

            if(is_allowed_delivered == 0){
                $("#delivery_status option[value='Delivered']").each(function() {
                    $(this).remove();
                });
          
            }
            else{
                var optionExists = ($("#delivery_status option[value='Delivered']").length > 0);
                if(!optionExists){
                    $("#delivery_status").append(new Option("Delivered", "Delivered"));
                }
            }
            $('#prompt-change-delivery-status').modal('show');
            $('#del_id').val(id);

            // $('#btnChangeDeliveryStatus').on('click', function() {
            //     let sales = $('#delivery_status').val();
            //     post_form("{{route('sales-transaction.delivery_status')}}",sales,id)
            // });
        }

        $('#delivery_status').change(function(){
            if($(this).val() == 'In Transit'){
                $('#delivered_by_div').modal('show');
            }
            else{
                $('#delivered_by_div').modal('hide');
            }
        })

        $('#mode_of_payment').change(function(){
            /*
            if($(this).val() == 'Cash'){
                $('#cashdiv').hide();
                $("#ref_no").prop('required',false);
            }
            else{
                $('#cashdiv').show();
                $("#ref_no").prop('required',true);
            }
            */

            if($(this).val() == 'COD'){
                $("#ref_no").prop('required',false);
                $("#payment_dt").prop('required',false);                
                $("#payment_amount").attr({
                    "readonly" : "readonly",
                    "value" : $("#payment_amount").attr('max')
                });
            }
            else{
                
                //$("#ref_no").prop('required',true);
                $("#payment_dt").prop('required',true);
                $("#payment_amount").attr({
                    "readonly" : false,
                    "value" : 0.00
                });
            }
        })

        function addCommas(nStr){
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }






    </script>
@endsection
