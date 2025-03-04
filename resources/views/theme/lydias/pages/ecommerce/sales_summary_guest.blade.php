<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="">
    <meta name="author">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CMS') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage').'/icons/'.Setting::getFaviconLogo()->website_favicon }}">


    <link href="{{ asset('theme/lydias/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('theme/lydias/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
    
    <link rel="stylesheet" href="{{ asset('css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashforge.profile.css') }}">



    <style>
        .payment-options {}
        .payment-options .payment-options-opt {position:relative;}
        .payment-options .payment-options-opt .form-check-input {left:55%;bottom:24px;}
        .payment-options .payment-options-opt .payment-options-img {height:8rem;}
        .payment-options .payment-options-opt p {padding-bottom:12px;}

        .nav-tabs .nav-link {border-right:1px solid gainsboro;}
        .nav-tabs .nav-link:last-child {border-right:none;}
        .nav-tabs .nav-link h6 {color:#333;}
        .nav-tabs .nav-link.active {border:thin solid #333;}

        @media screen and (max-width:900px) {
            .payment-options .payment-options-opt h6 {height:3em;}
        }

        @media print {
            #btnPayment { 
                display: none !important;
            }
        }

    </style>
</head>

<body> 
    <div class="content content-fixed">
        <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
            <div class="text-center">
                <img style="height:100px;" src="{{asset('images/lydias-lechon-logo-small.jpg')}}" alt="">
                <h4><strong>Sales Transaction Summary</strong></h4>
                <h5>Order #: {{$sales->order_number}}</h5>
            </div>
        </div>
    </div>


    <div class="content tx-13">
        <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    @if(isset($_GET['order_cancelled']))                        
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Important Notice:</h4>
                            <p>The payment transaction you processed was unsuccessful.</p>
                            <hr>
                            <p class="mb-0">If you wish to continue with your order, please click on the corresponding Pay icon <i class="fa fa-credit-card"></i> of Order#: <i style="font-weight:bold;">{{$_GET['order_no']}}</i></p>
                        </div>
                    @endif

                    @if(isset($_GET['payment_successful']))                        
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Payment Successful:</h4>
                            <p>Your payment was successfully processed. Thank you.</p>                                             
                        </div>
                    @endif
                </div>

                <div class="col-sm-6 col-lg-8 mg-t-40 mg-sm-t-0 mg-md-t-40">
                    <label class="tx-sans tx-uppercase tx-16 tx-bold">Customer Details</label>
                    <h6 class="tx-15 mg-b-10">{{$sales->customer_name}}</h6>
                    <p class="mg-b-0 tx-15">Contact No: {{$sales->customer_contact_number}}</p>
                    <p class="mg-b-0 tx-15">Email: {{$sales->email}}</p>
                    <p class="mg-b-0 tx-15">{{$sales->delivery_type}}: {{$sales->customer_delivery_adress}}</p>
                    <p class="mg-b-0 tx-15">Instruction: {{$sales->instruction}}</p>
                </div>

                <div class="col-sm-6 col-lg-4 mg-t-40">
                    <label class="tx-sans tx-uppercase tx-16 tx-bold">Order Details</label>
                    <ul class="list-unstyled lh-7">
                        <li class="d-flex justify-content-between tx-15">
                            <span>Order Date</span>
                            <span>{{ date('F d, Y H:i A', strtotime($sales->created_at))}}</span>
                        </li>
                        <li class="d-flex justify-content-between tx-15">
                            <span>Payment Status</span>
                            <span class="tx-success tx-semibold tx-uppercase">
                                @if($sales->PaymentStatus == 'UNPAID') NOT PAID @else {{$sales->PaymentStatus}} @endif
                                @if($sales->PaymentStatus<>'PAID')
                                    @if($sales->net_amount > $salesPayments->sum('amount'))
                                        <a href="#" id="btnPayment" class="btn btn-xs btn-success" onclick="$('#paymentModal').modal('show')">Add Payment</a>
                                    @endif
                                @endif
                            </span>
                        </li>
                        <li class="d-flex justify-content-between tx-15">
                            <span>Delivery Status</span>
                            <span>{{$sales->delivery_status}}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Order Details -->
            <div class="table-responsive mg-t-40">
                <h5>Order Details</h5>
                <table class="table table-invoice bd-b tx-15">
                    <thead>
                        <tr>
                            <th class="wd-28p">Product Name</th>        
                            <th class="wd-15p tx-center">Date Needed</th>
                            <th class="tx-center">Quantity</th>
                            <th class="">Price</th>
                            <th class="tx-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesDetails as $details)
                            <tr>
                                <td class="tx-nowrap">{{$details->product_name}} @if($details->paella_price > 0) with paella @endif</td>                
                                <td class="tx-nowrap tx-center">{{date('F d, Y H:i A',strtotime($details->delivery_date))}}</td>
                                <td class="tx-center">{{number_format($details->qty, 0)}}</td>
                                <td class="">{{number_format($details->price, 2)}}</td>
                                <td class="tx-right">{{number_format($details->gross_amount, 2)}}</td>                               
                            </tr>
                        @empty
                            <tr>
                                <td class="tx-center " colspan="5">No transaction found.</td>
                            </tr>
                        @endforelse

                        @if($sales->delivery_fee_amount > 0)
                            <tr>
                                <td class="tx-left " colspan="4">Delivery Fee</td>
                                <td class="tx-right ">{{number_format($sales->delivery_fee_amount, 2)}}</td>
                            </tr>
                        @endif

                        @forelse($gc as $g)
                            <tr style="font-weight:bold;">
                                <td class="tx-left" colspan="4">Gift Certificate: {{$g->code}}</td>
                                <td class="tx-right">({{number_format($g->amount, 2)}})</td> 
                            </tr>
                        @empty
                        @endforelse
                        @if($salesDetails->sum('gross_amount') > 0)
                            <tr style="font-weight:bold;">
                                <td colspan="4">&nbsp;</td>
                                <td class="tx-right">Total: {{number_format($sales->gross_amount, 2)}}</td> 
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Payments -->
            @if($salesPayments->count())
            <div class="table-responsive mg-t-40">
                <h5>Payment Details</h5>
                <table class="table table-invoice bd-b tx-15">
                    <thead>
                        <tr>
                            <th class="tx-left">Payment Type</th>
                            <th class="tx-center">Receipt No</th>
                            <th class="tx-center">Date</th>
                            <th class="tx-center">Status</th>
                            <th class="tx-right">Amount</th>                                
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesPayments as $payment)                            
                            <tr>
                                <td class="tx-left">{{$payment->payment_type}}</td>
                                <td class="tx-center">{{$payment->receipt_number}}</td>
                                <td class="tx-center">{{ date('F d, Y', strtotime($payment->payment_date))}}</td>
                                <td class="tx-center">@if($payment->status=='PENDING' && ($payment->payment_type=='IPAY' || $payment->payment_type=='Paymaya' )) Subject for Confirmation @else {{$payment->status}} @endif</td>
                                <td class="tx-right">{{number_format($payment->amount, 2)}}</td>
                               
                            </tr>
                        @empty
                            <tr>
                                <td class="tx-center " colspan="6">No payment found.</td>
                            </tr>
                        @endforelse

                        @if($salesPayments->sum('amount') > 0)
                            <tr style="font-weight:bold;">
                                <td colspan="4">&nbsp;</td>
                                <td align="right">Total : {{number_format($salesPayments->sum('amount'), 2)}}</td> 
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Delivery History -->
            @if($deliveries->count()))
            <div class="table-responsive mg-t-40">
                <h5>Delivery History</h5>
                <table class="table table-invoice bd-b tx-15">
                    <thead>
                        <tr>
                            <th class="tx-left">Date</th>
                            <th class="tx-center">Status</th>
                            <th class="tx-center">Remarks</th>
                            <th class="tx-center">Delivered By</th>                              
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $delivery)                            
                            <tr>
                                <td class="tx-left">{{$delivery->created_at}}</td>
                                <td class="tx-center">{{$delivery->status}}</td>
                                <td class="tx-center">{{$delivery->remarks}}</td>
                                <td class="tx-center">{{$delivery->delivered_by}}</td>
                               
                            </tr>
                        @empty
                            <tr>
                                <td class="tx-center " colspan="6">No deliveries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>


    <div class="modal fade " id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Chooose Payment Option</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav d-flex justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link text-center" href="#">Bank Deposit</a>
                            <a class="nav-item nav-link border-1 text-center" id="nav-bank-tab" data-toggle="tab" href="#nav-bank" role="tab" aria-controls="nav-bank" aria-selected="true">
                                <div class="payment-options-img d-flex align-items-center justify-content-center">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td><img src="{{ asset('images/payment/metrobank.jpg') }}"></td>
                                            </tr>
                                            <tr>
                                                <td><img src="{{ asset('images/payment/bdo.jpg') }}"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" href="#">Money Transfer</a>
                            <a class="nav-item nav-link border-1 text-center" id="nav-transfer-tab" data-toggle="tab" href="#nav-transfer" role="tab" aria-controls="nav-transfer" aria-selected="false">
                                <div class="payment-options-img d-flex align-items-center justify-content-center">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td><img src="{{ asset('images/payment/g-cash.jpg') }}"></td>
                                            </tr>
                                            <tr>
                                                <td><img src="{{ asset('images/payment/pay-maya.jpg') }}"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" href="#">Payment Center</a>
                            <a class="nav-item nav-link border-1 text-center" id="nav-center-tab" data-toggle="tab" href="#nav-center" role="tab" aria-controls="nav-center" aria-selected="false">
                                <div class="payment-options-img d-flex align-items-center justify-content-center">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td><img src="{{ asset('images/payment/m-lhuillier.jpg') }}"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Credit / Debit Card</a>
                            <a class="nav-item nav-link border-1 text-center" id="nav-card-tab" data-toggle="tab" href="#nav-card" role="tab" aria-controls="nav-card" aria-selected="false">
                                <div class="payment-options-img d-flex align-items-center justify-content-center">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td><img src="{{ asset('images/payment/credit-debit.jpg') }}"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <div class="col-lg-12 tab-content mt-4" id="nav-tabContent">
                        <div class="tab-pane fade" id="nav-card" role="tabpanel" aria-labelledby="nav-home-tab">
                            <table class="table table-borderless mb-3 border small" id="tb_paynamics">
                                <tbody>
                                    <tr class="border">
                                        <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <form style="display:none;" name="ePayment" id="ePayment" action="https://payment.ipay88.com.ph/epayment/entry.asp" method="post">
                                                <div class="form-group">
                                                    <label for="Amount" class="col-form-label">Amount to Pay:</label>
                                                    <input type="number" min="0.00" step="0.01" readonly="readonly" value="{{$sales->gross_amount}}" class="form-control" id="Amount2" name="Amount2">
                                                </div>
                                                <input type="hidden" name="merchantcode" id="merchantcode" value="PH00125">
                                                <input type="hidden" name="paymentid" id="paymentid" value="1">
                                                <input type="hidden" name="RefNo" id="RefNo" value="{{$sales->order_number}}">
                                                <input type="hidden" name="Amount" id="Amount" value="{{$sales->gross_amount}}">
                                                <input type="hidden" name="Currency" id="Currency" value="PHP">
                                                <input type="hidden" name="Remark" id="Remark" value="Lydias Lechon Payment">
                                                <input type="hidden" name="ProdDesc" id="ProdDesc" value="Lydias Lechon Payment">
                                                <input type="hidden" name="UserName" id="UserName" value="{{$sales->customer_name}}">
                                                <input type="hidden" name="UserEmail" id="UserEmail" value="{{$sales->email}}">
                                                <input type="hidden" name="UserContact" id="UserContact" value="">
                                                <input type="hidden" name="ResponseURL" value="https://lydias-lechon.com/ipay_processor.php">
                                                <input type="hidden" name="BackendURL" value="https://lydias-lechon.com/ipay_backend.php">
                                                <input type="hidden" name="signature" id="signature" value="">                                   
                                            </form>

                                            <form action="{{route('paymaya.pay')}}" id="form_pamaya" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="sales_header_id" class="sales_header_id" value="{{$sales->order_number}}">      

                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Amount to Pay:</label>
                                                    @php if(isset($_GET['jjjj'])){ $sales->gross_amount = 100.00; } @endphp
                                                    <input type="number" required="required" min="0.00" readonly="readonly" value="{{number_format($sales->gross_amount,2, '.', '')}}" class="form-control" id="oamount" name="amount">
                                                </div>
                                                <input type="submit" value="Submit" class="btn btn-primary">      
                                            </form>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-bank" role="tabpanel" aria-labelledby="nav-home-tab">
                            <table class="table table-borderless mb-3 border small" id="tb_bankdeposit">
                                <tbody>
                                    <tr class="border">
                                        <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>                   
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <form action="{{route('payment.add.store_customer')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="sales_header_id" class="sales_header_id" value="{{$sales->order_number}}">  
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">BDO or Metrobank:</label>
                                                    <select class="form-control" name="pamenty_mode" required="required" id="pamenty_mode" onchange="bank_change($(this).val());">
                                                        <option value="">Select</option>
                                                        <option value="Metrobank">Metrobank</option>
                                                        <option value="BDO">BDO Unibank, Inc.</option>
                                                    </select>           
                                                    <div id="metrobank_details" style="display:none;" class="banks">
                                                        <br>
                                                        <p><b>Metrobank Account Details</b><br>  
                                                        Account Name: <b>SAN GREGORIO ALL VENTURES CORP.</b><br> 
                                                        Current Account #: <b>481-7-48191145-8</b></p>
                                                    </div>         
                                                    <div id="bdo_details" style="display:none;" class="banks">
                                                        <br>
                                                        <p><b>BDO Unibank, Inc. Account Details<b><br>
                                                        Account Name: <b>SAN GREGORIO ALL VENTURES CORP.</b><br>
                                                        Savings account no: <b>001628026896</b></p>
                                                    </div>                     
                                                </div>                        
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Amount to Pay:</label>
                                                    <input type="number" required="required" min="0.00" readonly="readonly" value="{{number_format($sales->gross_amount,2, '.', '')}}" class="form-control" id="oamount" name="amount">
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Paid Date:</label>
                                                    <input type="date" required="required" class="form-control" id="payment_dt" name="payment_dt" max="{{date('Y-m-d')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Receipt/Reference Number:</label>
                                                    <input type="text" required="required"  class="form-control" id="ref_no" name="ref_no">
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Attachment:</label>
                                                    <input type="file" required="required" class="form-control" id="file" name="file">
                                                </div>    
                                                <input type="submit" value="Submit" class="btn btn-primary">      
                                            </form>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-transfer" role="tabpanel" aria-labelledby="nav-home-tab">
                            <table class="table table-borderless mb-3 border small" id="tb_moneytransfer">
                                <tbody>
                                    <tr class="border">
                                        <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>

                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <form action="{{route('payment.add.store_customer')}}" id="form_gpay" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="sales_header_id" class="sales_header_id" value="{{$sales->order_number}}">   
                                                <div class="form-group" id="money_transfer_div">
                                                    <label for="" class="col-form-label">GCash or PayMaya:</label>
                                                    <select class="form-control" name="pamenty_mode" id="pamenty_mode_gpay" onchange="gcash_paymaya_change()">
                                                        <option value="">Select</option>
                                                        <option value="PayMaya">PayMaya</option>
                                                        <option value="GCash">GCash</option>
                                                    </select>    
                                                    <div id="gcash_details" style="display:none;" class="moneytransfer">
                                                        <br>
                                                        <p><b>GCash<b><br>
                                                        Scan the QR Code below<br>
                                                        </p>
                                                        <img src="{{asset('images/gcash.png')}}" height="200" alt="">
                                                    </div>                              
                                                </div>   

                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Amount to Pay:</label>
                                                    @php if(isset($_GET['jjjj'])){ $sales->gross_amount = 100.00; } @endphp
                                                    <input type="number" required="required" min="0.00" readonly="readonly" value="{{number_format($sales->gross_amount,2, '.', '')}}" class="form-control" id="oamount" name="amount">
                                                </div>
                                                <div class="form-group not_paymaya">
                                                    <label for="" class="col-form-label">Paid Date:</label>
                                                    <input type="date" required="required" class="form-control not_paymayaf" id="payment_dt" name="payment_dt" max="{{date('Y-m-d')}}">
                                                </div>
                                                <div class="form-group not_paymaya">
                                                    <label for="" class="col-form-label">Receipt/Reference Number:</label>
                                                    <input type="text" required="required"  class="form-control not_paymayaf" id="ref_no" name="ref_no">
                                                </div>
                                                <div class="form-group not_paymaya">
                                                    <label for="" class="col-form-label">Attachment:</label>
                                                    <input type="file" required="required" class="form-control not_paymayaf" id="file" name="file">
                                                </div>    
                                                <input type="submit" value="Submit" class="btn btn-primary">      
                                            </form>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-center" role="tabpanel" aria-labelledby="nav-home-tab">
                            <table class="table table-borderless mb-3 border small" id="tb_paymentcenter">
                                <tbody>
                                    <tr class="border">
                                        <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>

                                    </tr>
                                    <tr><td> <h4>Receiver: <b>Lydias Lechon</b></h4></td></tr>
                                    <tr>
                                        <td colspan="2">
                                            <form action="{{route('payment.add.store_customer')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="pamenty_mode" id="pamenty_mode" value="M lhuillier">
                                                <input type="hidden" name="sales_header_id" class="sales_header_id" value="{{$sales->order_number}}">   

                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Amount to Pay:</label>
                                                    <input type="number" required="required" min="0.00" readonly="readonly" value="{{number_format($sales->gross_amount,2, '.', '')}}" class="form-control" id="oamount" name="amount">
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Paid Date:</label>
                                                    <input type="date" required="required" class="form-control" id="payment_dt" name="payment_dt" max="{{date('Y-m-d')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Receipt/Reference Number:</label>
                                                    <input type="text" required="required"  class="form-control" id="ref_no" name="ref_no">
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Attachment:</label>
                                                    <input type="file" required="required" class="form-control" id="file" name="file">
                                                </div>    
                                                <input type="submit" value="Submit" class="btn btn-primary">      
                                            </form>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script>
    function gcash_paymaya_change(){

        if($('#pamenty_mode_gpay').val()=='GCash'){
            $('.not_paymaya').show();
            $('#gcash_details').show(); 
            $('#paymaya_btn').hide(); 
            $('.not_paymayaf').prop('required',true);
            $('#form_gpay').attr('action','{{route('payment.add.store_customer')}}');
            
            
        } 
        else if($('#pamenty_mode_gpay').val()=='PayMaya'){
            $('.not_paymaya').hide();
            $('#gcash_details').hide(); 
            $('#paymaya_btn').show(); 
            $('.not_paymayaf').prop('required',false);
            $('#form_gpay').attr('action','{{route('paymaya.pay')}}');

           // $('#paymaya_details').show(); 
        }
        else{
            $('.not_paymaya').show();
            $('#paymaya_btn').hide(); 
        }
    }
    function aa(x){
   
        if (x == 'paynamics') {
            $('#tb_paynamics').show();
            $('#tb_moneytransfer').hide();
            $('#tb_bankdeposit').hide();
            $('#tb_paymentcenter').hide();
        }   
        else if (x == 'money transfer') {
            $('#tb_paynamics').hide();
            $('#tb_moneytransfer').show();
            $('#tb_bankdeposit').hide();
            $('#tb_paymentcenter').hide();
        }  
        else if (x == 'bank deposit') {
            $('#tb_paynamics').hide();
            $('#tb_moneytransfer').hide();
            $('#tb_bankdeposit').show();
            $('#tb_paymentcenter').hide();
        }     
        else if (x == 'payment center') {
            $('#tb_paynamics').hide();
            $('#tb_moneytransfer').hide();
            $('#tb_bankdeposit').hide();
            $('#tb_paymentcenter').show();
        }
    };

    function bank_change(id){
        $('.banks').hide();
        if(id == 'Metrobank'){
            $('#metrobank_details').show();
        }
        if(id == 'BDO'){
            $('#bdo_details').show();
        }

        
    }    
    function paying(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: { _token: "{{ csrf_token() }}", amount: $('#Amount2').val(), order: $('#RefNo').val() },
            type: "post",
            url: "{{route('cart.generate_payment_guest')}}",
           
            success: function(returnData) {
                // alert();
                if (returnData['success']) {
                    $('#Amount').val(returnData['amount']);
                    $('#UserContact').val(returnData['customer_contact_number']);
                    $('#signature').val(returnData['signature']);
                    $('#ePayment').submit();
                }
            }
        });
    }
</script>
