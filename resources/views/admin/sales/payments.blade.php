@extends('admin.layouts.app')

@section('pagetitle')
    Sales Payments
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
    @php
        if(!isset($_GET['search'])){
            $_GET['search']='';
        }
    @endphp
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Payments</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Sales Payments Manager</h4>
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
                                
                              
                                <td style="width:20%">
                                    <select class="form-control" name="status">
                                        <option value="">Payment Status</option>
                                        <option value="PENDING" @if(isset($_GET['status']) && $_GET['status'] == 'PENDING') selected="selected" @endif>PENDING</option>
                                        <option value="PAID" @if(isset($_GET['status']) && $_GET['status'] == 'PAID') selected="selected" @endif>CONFIRMED</option>
                                        <option value="CANCELLED" @if(isset($_GET['status']) && $_GET['status'] == 'CANCELLED') selected="selected" @endif>CANCELLED</option>
                                    </select>
                                </td>                                
                                <td style="width:20%">
                                    <input @if(isset($_GET['start_date'])) type="date" value="{{$_GET['start_date']}}" @else type="text" onfocus="(this.type='date')" @endif class="form-control" name="start_date" placeholder="Start Date (Payment)">
                                </td>
                                <td style="width:20%">
                                    <input @if(isset($_GET['end_date'])) type="date" value="{{$_GET['end_date']}}" @else type="text" onfocus="(this.type='date')" @endif class="form-control" name="end_date" placeholder="End Date (Payment)">
                                </td>
                                
                                <td style="width:20%"><input name="search" type="search" id="search" class="form-control"  placeholder="Order Number" value="{{ $_GET['search'] }}">
                                </td>                                
                            
                                <td>
                                    <input type="submit" class="btn-xs btn btn-success" value="Search">
                                    <a href="{{ route('sales-transaction.payments') }}" class="btn-xs btn btn-info">Reset</a>
                                </td>
                            </tr>
                            
                        </table>
                         </form>
                        

                        <div class="ml-auto bd-highlight mg-t-10" style="display:none;">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-b-5">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Order # or Customer" value="{{ $_GET['search'] }}">
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
                                
                                <th>Order Number</th>
                                <th>Customer Name</th>
                                <th>Payment Type</th>
                                <th>Reference #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Attachment/Approval Code</th>
                                <th>Approve By</th>
                                <th>Approve Date</th>
                                                              
                                <th style="width:10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php                            
                                $alls = explode(",",auth()->user()->allowed_payments);
                                if(in_array("Credit/Debit Card", $alls)){
                                    array_push($alls,"Debit/Credit Card");
                                }
                            @endphp
                            @forelse($payments as $p)
                                @php
                                    $approval_code = '';
                                    $approved_by = '';
                                    $approved_date = '';
                                    if($p->status == 'PAID'){
                                        $payment_approval = \App\Approvals::where('reference_id',$p->sales_header_id)->where('approval_type','Payment')->first();
                                        if($payment_approval){
                                            $approval_code = '<br>'.$payment_approval->approval_code;
                                            $approved_by = $payment_approval->user->name;
                                            $approved_date = date('Y-m-d h:i A',strtotime($payment_approval->created_at));
                                        }
                                    }
                                @endphp
                               
                                <tr>
                                    
                                    <th> 
                                        @if($p->sales->deleted_at)
                                            <strong style="text-decoration:line-through;"> <a title="Deleted Sales" href="#" onclick="alert('This sales transaction is already cancelled.');">{{$p->sales->order_number }}</a><br></strong>
                                        @else
                                            <strong> <a title="View Sales Summary" target="_blank" href="{{ route('sales-transaction.view',$p->sales->id) }}">{{$p->sales->order_number }}</a><br></strong>
                                        @endif
                                    </th>
                                    <td>{{ $p->sales->customer_name }}</td>
                                    <td>{{ $p->payment_type }}</td>
                                    <td>{{ $p->receipt_number }}</td>
                                    <td>{{ date('Y-m-d',strtotime($p->payment_date)) }}</td>
                                    <td>{{ $p->status }}</td>
                                    <td>{{ number_format($p->amount,2) }}</td>
                                    <td>@if(!empty($p->file_url))<a href="{{$p->file_url}}" target="_blank">View</a>@endif {!!$approval_code!!}</td>
                                    <td>{{ $approved_by }}</td>
                                    <td>{{ $approved_date }}</td>
                                    <td>
                                        @if($p->status == 'PENDING')
                                            @if(in_array(strtolower($p->payment_type),array_map('strtolower',$alls)) || auth()->user()->role_id == 1)
                                                @if (auth()->user()->has_access_to_route('approve_payment'))
                                                    <a href="#" title="Approve this payment" onclick="confirm_sales_payment({{$p->id}},'{{$p->payment_type}}','{{preg_replace( "/\r|\n/", "", $p->receipt_number )}}')" class="btn btn-primary btn-xs"><i class="fa fa-check"></i></a>
                                                @endif

                                                @if(auth()->user()->has_access_to_route('disapprove_payment'))
                                                    <a href="#" title="Disapprove/Cancel this payment" class="btn btn-danger btn-xs" onclick='
                                                        var txt;
                                                        var r = confirm("Are you sure you want cancel this payment?");
                                                        if (r == true) {
                                                          window.location.href = "{{route('disapprove_payment',$p->id)}}";
                                                        }
                                                        else {
                                                            return false;
                                                        }
                                                    '><i class="fa fa-times"></i></a>
                                                @endif
                                            @endif
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
                    @if ($payments->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $payments->appends((array) $_GET)->links() }}
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



    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>

        function confirm_sales_payment(x,paytype,payref){
            $('#confirm_payment_id').val(x);
            if(paytype=='IPAY'){
                $('.ccard').show();
            }else{
                $('.ccard').hide();
            }
            $('#prompt-confirm-payment-approval').modal('show');
        }




    </script>
@endsection
