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
                                <td style="width:25%">
                                    <input @if(isset($filter->dn_start_date)) type="date" value="{{$filter->dn_start_date}}" @else type="text" onfocus="(this.type='date')" @endif class="form-control" name="dn_start_date" placeholder="Start Date (Date Needed)">
                                </td>
                                <td style="width:25%">
                                    <input @if(isset($filter->dn_end_date)) type="date" value="{{$filter->dn_end_date}}" @else type="text" onfocus="(this.type='date')" @endif class="form-control" name="dn_end_date" placeholder="End Date (Date Needed)">
                                </td>
                                
                                <td style="width:40%"><input name="search" type="search" id="search" class="form-control"  placeholder="Order, Customer" value="{{ $filter->search }}">
                                </td>
                                <td><input type="submit" class="btn-xs btn btn-success" value="Search"></td>                                
                              
                                <td>
                                    
                                    <a href="{{ route('sales-transaction.driver_sales_transaction') }}" class="btn-xs btn btn-info">Reset</a>
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
                                <th style="width: 10%;">
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
                                <th>Order Status</th>
                                <th style="display:none;">Payment Type</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sales as $sale)
                                @php
                                    $date_needed = $sale['date_needed'];
                                    $dateneeded = '';
                                    $payment_types = '';
                                    $locationed = '';
                                    if(!empty($date_needed)){
                                        $dateneeded = date('Y-m-d H:i A',strtotime($date_needed));
                                    }
                                    if(!empty($sale->payments)){
                                        $ptype = $sale->payments->unique('payment_type');
                                        foreach($ptype as $p){
                                            $payment_types .= $p->payment_type.",";
                                        }
                                    }
                                    if($sale['delivery_type'] == 'Door to door delivery'){
                                        $locationed = $sale['customer_location'] ?? 'N/A';
                                    }
                                    if($sale['delivery_type'] == 'Store Pickup'){
                                        $locationed = $sale['outlet'] ?? 'N/A';
                                    }

                                    $is_allowed_delivered = 1;
                                    if($dateneeded > date('Y-m-d H:i:s')){
                                        $is_allowed_delivered = 1;
                                    }
                                    if ($dateneeded != '') {
                                        $dateneeded = \Carbon\Carbon::parse($date_needed)->format('Y-m-d H:i A');
                                    }
                                @endphp
                                @php
                                if ($sale['type'] == 'job') {
                                    $use = \App\EcommerceModel\JobOrder::find($sale['id']);
                                } else {
                                    $use = \App\EcommerceModel\SalesHeader::find($sale['id']);
                                }
                                @endphp
                                <tr style="height:30px; @if($sale['trashed']) background-color:#FFA07A; @endif {{ $sale['isConfirm'] == 1 ? 'disabled' : '' }}">
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" 
                                                data-id="{{ $sale['id'] }}" 
                                                data-type="{{ $sale['type'] }}" 
                                                id="cb{{ $sale['type'] }}_{{ $sale['id'] }}">
                                            <label class="custom-control-label" for="cb{{ $sale['type'] }}_{{ $sale['id'] }}"></label>
                                        </div>
                                    </td>
                                    <th class="{{ isUnreadTransaction($sale['id']) ? 'font-weight-bold' : '' }}">
                                        <div @if($sale['trashed']) style="text-decoration:line-through;" @endif> 
                                            @if ($sale['type'] == 'job')
                                                {{$sale['order_number'] }}
                                            @else
                                                <a title="View Sales Summary" target="_blank" href="{{ route('sales-transaction.view',$sale['id']) }}">{{$sale['order_number'] }}</a>
                                            @endif
                                            <br>
                                        </div>
                                    </th>
                                    <td class="{{ isUnreadTransaction($sale['id']) ? 'font-weight-bold' : '' }}">{{ $sale['customer_name'] }}</td>
                                    <td>{{ $sale['order_source'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sale['created_at'])->format('Y-m-d H:i A') }}</td>

                                    <td>
                                        @if($sale['delivery_status'] <> 'Open Date')
                                            @if (isset($sale['delivery_address']) && count($sale['delivery_address']) > 0)
                                                @php
                                                    $dateneeded = '';
                                                    foreach ($sale['delivery_address'] as $address) {
                                                        if ($dateneeded != '') {
                                                            $dateneeded .= ', ';
                                                        }
                                                        $dateneeded .= \Carbon\Carbon::parse($address['delivery_date'])->format('Y-m-d H:i A');
                                                    }
                                                @endphp
                                                    {{ $dateneeded }}
                                            @else
                                                {{ $dateneeded }}
                                            @endif
                                        @endif
                                    </td>

                                    <td>{{ $sale['delivery_type'] }}</td>
                                    {{-- <td>
                                        @if ($sale['type'] == 'job')
                                            {{ $sale['delivery_status'] }}
                                        @else
                                            <a href="{{route('admin.report.delivery_report',$sale['id'])}}" target="_blank">{{$sale['delivery_status']}}</a>
                                        @endif
                                    </td> --}}
                                    <td style="font-size:11px;">
                                        @php
                                            $addresses      = collect($sale['delivery_address'] ?? []);
                                            $firstAddressId = data_get($sale, 'delivery_address.0.id');

                                            $addrStatusLinks = $addresses
                                                ->map(function ($addr) use ($sale) {
                                                    $addressId = $addr['id'] ?? null;
                                                    if (!$addressId) return null;

                                                    $status = trim((string)($addr['delivery_status'] ?? $sale['delivery_status'] ?? ''));
                                                    $label  = $status !== '' ? $status : 'No status';

                                                    $href = url("admin/report/delivery_report/{$sale['id']}/multiple/{$addressId}");
                                                    return '<a href="'.$href.'" class="text-blue-600 hover:underline">'.e($label).'</a>';
                                                })
                                                ->filter();
                                        @endphp

                                        @if ($addrStatusLinks->isNotEmpty())
                                            {!! $addrStatusLinks->implode(',<br> ') !!}
                                        @else
                                            @if (!empty($sale['delivery_status']) && $firstAddressId)
                                                <a target="_blank" href="{{ url("admin/report/delivery_report/{$sale['id']}/multiple/{$firstAddressId}") }}"
                                                class="text-blue-600 hover:underline">
                                                    {{ $sale['delivery_status'] }}
                                                </a>
                                            @else
                                                <a target="_blank" href="{{ route('admin.report.delivery_report', ['id' => $sale['id']]) }}"
                                                class="text-blue-600 hover:underline">
                                                    {{ $sale['delivery_status'] ?? 'No status' }}
                                                </a>
                                            @endif
                                        @endif
                                    </td>

                                    <td style="display:none;">{{ rtrim($payment_types,",") }}</td>
                                    <td>
                                        {{ number_format($sale['gross_amount'],2) }}
                                    </td>
                                     <td>{{ $sale['type'] == 'job' ? 'Job Order' : 'Sales' }}</td>
                                    <td width="10%">
                                        <!-- 10102 -->
                                         @php $forecasters = [3,13]; $forecasters = [13]; @endphp
                                        @if(!in_array(auth()->user()->role_id, $forecasters) || auth()->user()->id == 10102)                                    
                                            <nav class="nav table-options">
                                                @if($sale['trashed'])
                                                    @if (auth()->user()->has_access_to_route('sales-transaction.restore'))
                                                        <nav class="nav table-options">
                                                            <a class="nav-link" href="{{route('sales-transaction.restore', $sale['id'])}}" title="Restore this Sales Transaction"><i data-feather="rotate-ccw"></i></a>
                                                        </nav>
                                                    @endif
                                                @else
                                                    <div class="nav-item dropdown">
                                                        <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i data-feather="truck"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                @if (!in_array($sale['delivery_status'], ['Delivered/Picked Up', 'Returned/Rejected']))
                                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="change_delivery_status({{$sale['id']}}, {{$is_allowed_delivered}}, '{{$sale['delivery_type']}}')" title="Update Order Status" data-id="{{$sale['id']}}">Update Order Status</a>
                                                                @endif
                                                                @if ($sale['delivery_type'] == 'Door to door delivery' && ($sale['delivery_address'] && count($sale['delivery_address']) > 0))
                                                                <div class="printReceipt" data-addresses="{{ json_encode($sale['delivery_address']) }}" data-saleid="{{ $sale['id'] }}">
                                                                    <button class="dropdown-item">Print Delivery Receipt</button>
                                                                </div>
                                                                @else
                                                                @if ($sale['type'] == 'sales')
                                                                <a class="dropdown-item" href="{{route('admin.report.delivery_report',$sale['id'])}}" target="_blank" >Print Delivery Receipt</a>
                                                                @endif
                                                                @endif
                                                                <a class="dropdown-item" href="javascript:void(0);" onclick="show_delivery_history({{$sale['id']}}, '{{$sale['type']}}')" title="Order History" data-id="{{$sale['id']}}">Show Order Status History</a>
                                                            
                                                            @if (substr(strtolower(isset($sale['user']) && $sale['user']['email']), 0, 8) == 'lydtemp_')
                                                                <a class="dropdown-item" href="{{route('confirmation', $sale['HashOrderNumber'] ?? null)}}" target="_blank" title="View Guest Sales Summary" >Guest Sales Summary</a>
                                                            @endif


                                                            @if (auth()->user()->has_access_to_route('sales-transaction.destroy'))
                                                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="delete_sales({{$sale['id']}},'{{$sale['order_number']}}')" title="Delete Transaction">Delete</a>
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

                                                                <a class="dropdown-item" title="View Sales Summary" target="_blank" href="{{ route('sales-transaction.view',$sale['id']) }}">View Sales Summary</a>
                                                                 <a class="dropdown-item" href="javascript:;" onclick="show_added_payments('{{$sale['id']}}')">View Payments</a>


                                                           
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

    <!-- Bulk delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
        <form id="bulkDeleteForm" action="{{ route('sales.bulk-delete-mixed') }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="records" id="selected_ids">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the selected records?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </form>
        </div>
    </div>
  

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
                                <x-error-message inputName="payment_type" />
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="d-block">Amount *</label>
                            <input type="text" class="form-control" name="amount" id="amount">
                            <p class="tx-10 text-danger" id="error">
                                <x-error-message inputName="amount" />
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="d-block">Payment date *</label>
                            <input type="date" class="form-control" name="payment_date" id="payment_date">
                            <p class="tx-10 text-danger" id="error">
                                <x-error-message inputName="payment_date" />
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="d-block">Receipt number *</label>
                            <input type="text" class="form-control" name="receipt_number" id="receipt_number">
                            <p class="tx-10 text-danger" id="error">
                                <x-error-message inputName="receipt_number" />
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
                    <h5 class="modal-title" id="exampleModalCenterTitle">Order Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="dd_form" method="POST" action="{{route('sales-transaction.delivery_status')}}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="form-group" id="deliveries_lists_div">
                            <label for="deliveries_lists">Deliveries</label>
                            <select id="deliveries_lists" class="form-control mg-b-5" name="deliveries_lists"  data-width="100%">
                            </select>
                            <p class="tx-10 text-danger" id="error">
                                <x-error-message inputName="deliveries_lists" />
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="delivery_status">Status</label>
                            <select id="delivery_status" class="form-control mg-b-5" name="delivery_status"  data-width="100%" required="required">
                                <option value="">- Select -</option>
                                {{-- <option value="In Traonsit">In Transit</option> --}}
                                <option value="Delivered/Picked Up">Delivered/Picked Up</option>
                                <option value="Returned/Rejected">Returned/Rejected</option>
                            </select>
                            <p class="tx-10 text-danger" id="error">
                                <x-error-message inputName="delivery_status" />
                            </p>
                        </div>
                        <div class="form-group" id="delivered_by_div" style="display: none">
                            <label for="delivered_by">Delivered by:</label>
                            <select name="delivered_by" id="delivered_by" class="form-control">
                                <option value="{{ auth()->user()->id }}" selected>{{ auth()->user()->name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="delivery_status">Remarks</label>
                            <textarea name="del_remarks" required="required" class="form-control" id="del_remarks" cols="30" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Attachment</label>
                            <input type="file" name="image" class="form-control" id="image">
                        </div>
                        <div class="form-group">
                            <a href="" target="_blank" id="view_image" style="display: none;">
                                <img id="del_image" src="" alt="Delivery Image" style="max-width: 200px; display: none;">
                            </a>
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

    <div class="modal effect-scale" id="prompt-print-receipt-delivery" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">View Receipt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="dd_form" method="POST" action="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="preint-receipt-delivert-select">Status</label>
                            <select id="preint-receipt-delivert-select" class="form-control mg-b-5" name="address"  data-width="100%" required="required">
                                <option value="">- Select -</option>
                            </select>

                            <p>Select the address to print the delivery receipt.</p>
                        </div>
                    </div>
                    <input type="hidden" id="sale_id">
                    <div class="modal-footer">
                        <button type="button" 
                                class="btn btn-sm btn-primary" 
                                id="printDeliveryBtn" 
                                data-url-template="{{ url('admin/report/delivery_report') }}/:id/multiple/:address">
                            View
                        </button>

                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
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
                                <x-error-message inputName="payment_dt" />
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
                                <th>Attachment</th>
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
        window.base_url = @json(config('app.url'));
    </script>
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

        function show_delivery_history(id,type){
            $.ajax({
                type: "GET",
                url: "{{ route('display.delivery-history') }}",
                data: { id : id, type : type },
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

        function toggleDeleteButton() {
            if ($('.cb:checked').length > 0) {
                $('#bulk-delete-btn').removeClass('d-none');
            } else {
                $('#bulk-delete-btn').addClass('d-none');
            }
        }

        // Show/hide delete button on individual checkbox click
        $(document).on('click', '.cb', function() {
            toggleDeleteButton();

            // Update header checkbox
            $('#checkbox_all').prop('checked', $('.cb:checked').length === $('.cb').length);
        });

        $(document).on('click', '.cb', function () {
            const totalEnabled = $('.cb:enabled').length;
            const totalChecked = $('.cb:enabled:checked').length;

            $('#checkbox_all').prop('checked', totalEnabled === totalChecked);
            toggleDeleteButton();
        });
        
        // When "select all" is clicked
        $('#checkbox_all').on('click', function() {
            $('.cb').each(function() {
                if (!$(this).is(':disabled')) {
                    $(this).prop('checked', $('#checkbox_all').is(':checked'));
                }
            });
            toggleDeleteButton();
        });

        // On modal open, collect selected IDs
        $('#bulk-delete-btn').on('click', function () {
            const selected = $('.cb:checked').map(function () {
                return {
                    id: $(this).data('id'),
                    type: $(this).data('type')
                };
            }).get();

            $('#selected_ids').val(JSON.stringify(selected));
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

        function change_delivery_status(id,is_allowed_delivered, delivery_type){

            $('#delivery_status').val('');
            $('#del_remarks').val('');
            $('#del_image').hide();
            $('#view_image').hide();

            if(is_allowed_delivered == 0){
                $("#delivery_status option[value='Delivered/Picked Up']").each(function() {
                    $(this).remove();
                });
          
            }
            else{
                var optionExists = ($("#delivery_status option[value='Delivered/Picked Up']").length > 0);
                if(!optionExists){
                    $("#delivery_status").append(new Option("Delivered/Picked Up", "Delivered/Picked Up"));
                }
            }
            $('#prompt-change-delivery-status').modal('show');
            $('#del_id').val(id);

            if (delivery_type == 'Store Pickup') {
                $("#delivery_status option[value='In Transit']").remove();
            }

            // $('#btnChangeDeliveryStatus').on('click', function() {
            //     let sales = $('#delivery_status').val();
            //     post_form("{{route('sales-transaction.delivery_status')}}",sales,id)
            // });

            $.ajax({
                type: "GET",
                url: "{{ route('show.delivery-status', [':id']) }}".replace(':id', id),
                success: function( response ) {
                    if (response.status && response.status.status !== 'In Transit') {
                        $('#delivery_status').val(response.status.status);
                        $('#del_remarks').val(response.status.remarks);

                        if (response.status.image) {
                            $('#del_image').attr('src', window.base_url + '/images/proof-of-delivery/' + response.status.image).show();
                            $('#view_image').attr('href', window.base_url + '/images/proof-of-delivery/' + response.status.image).show();
                        }
                    }

                    // Populate deliveries from top-level "deliveries"
                    if (response.deliveries && response.deliveries.length > 0) {
                        $('#deliveries_lists_div').show();
                        $('#deliveries_lists').empty().append('<option value="">- Select -</option>');
                        response.deliveries.forEach(function(delivery, index) {
                            if (delivery.delivery_status === 'In Transit') {
                                const label = `Address ${index + 1}: ${delivery.address} (${delivery.location})`;
                                $('#deliveries_lists').append(
                                    '<option value="' + delivery.id + '">' +
                                        label +
                                    '</option>'
                                );
                            }
                        });
                        $('#deliveries_lists').prop('required', true);
                    } else {
                        $('#deliveries_lists_div').hide();
                        $('#deliveries_lists').prop('required', false);
                    }
                }
            });


        }

        $('#delivery_status').change(function(){
            if($(this).val() == 'In Transit'){
                $('#delivered_by_div').show()
            }
            else{
                $('#delivered_by_div').hide();
            }
        })

        $('#mode_of_payment').change(function(){
            var req_attached = ['Bank Deposit','Check Payment','Gcash','Online Bank Transfer'];
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

            if($.inArray($(this).val(), req_attached) !== -1){
                //alert('required');
                $("#payment_attachment").prop('required',true);                
            }
            else{
                //alert('not required');
                $("#payment_attachment").prop('required',false);    
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


        $('.printReceipt').on('click', function () {
            const addresses = $(this).data('addresses');
            const $select = $('#preint-receipt-delivert-select');
            const sale_id = $(this).data('saleid');

            // Clear 
            $select.find('option:not(:first)').remove();

            // Populate new options
            if (Array.isArray(addresses)) {
                addresses.forEach((item, index) => {
                    const label = `Address ${index + 1}: ${item.address} (${item.location})`;
                    const option = new Option(label, item.id);
                    $select.append(option);
                });
            }

            $select.trigger('change.select2');

            $('#sale_id').val(sale_id);

            // Show modal
            $('#prompt-print-receipt-delivery').modal('show');
        });

        $('#printDeliveryBtn').on('click', function (e) {
            e.preventDefault();

            const $select = $('#preint-receipt-delivert-select');
            const selectedOption = $select.find('option:selected');

            const id = selectedOption.val();
            const address = selectedOption.text();
            const baseUrl = $(this).data('url-template');
            const saleid = $('#sale_id').val();

            if (!id || id === "") {
                alert("Please select an address.");
                return;
            }

            const encodedAddress = encodeURIComponent(id);

            // Construct the full URL
            const fullUrl = baseUrl
                .replace(':id', saleid)
                .replace(':address', encodedAddress);

            // Open in new tab
            window.open(fullUrl, '_blank');
        });


    </script>
@endsection
