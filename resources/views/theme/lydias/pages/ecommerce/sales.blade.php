@extends('theme.'.config('app.frontend_template').'.main')
@section('pagecss')
{{--     <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/vanilla-zoom/vanilla-zoom.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/datatables/datatables.css') }}" /> --}}
@endsection
@section('content')
@php
    $modals='';
@endphp
<section>
    <div class="content-wrapper">
        <div class="gap-70"></div>
        <div class="container" style="max-width:1350px;">
            <div class="row">
                <div class="col-lg-3">
                    <h3>My Account</h3>
                    <div class="list-group">
                        
                        <a href="{{ route('my-account.manage-account')}}" class="list-group-item list-group-item-action" 
                        style="font-size: 1.2rem !important !important !important;">Manage Account</a>
                        <a href="{{ route('my-account.update-password') }}" class="list-group-item list-group-item-action" 
                        style="font-size: 1.2rem !important !important !important;">Change Password</a>
                        <a href="{{ route('profile.sales') }}" class="list-group-item list-group-item-action active" 
                        style="font-size: 1.2rem !important !important !important;">Sales Transaction</a>
                    </div>
                </div>
               

                <div class="col-lg-9" style="padding-left:20px;">
                    @if(Session::has('success_cancelled'))
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Order Cancelled</h4>
                            <p>Your order has been successfully cancelled.</p>                            
                        </div>
                    @endif
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
                    <h3>Previous Transactions</h3>
                     
                    <div class="table-history" style="overflow-x:auto;">
                        <table class="table table-hover small text-center overflow-auto">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="align-middle">Order#</th>
                                    <th scope="col" class="align-middle">Date</th>
                                    <th scope="col" class="align-middle">Amount</th>
                                    <th scope="col" class="align-middle">Paid</th>
                                    <th scope="col" class="align-middle">Balance</th>
                                    <th scope="col" class="align-middle">Delivery Status</th>

                                    <th scope="col" class="align-middle" colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($sales as $sale)
                                @php
                                $paid = \App\EcommerceModel\SalesHeader::paid($sale->id);
                                $balance = \App\EcommerceModel\SalesHeader::balance($sale->id);
                                $is_still_allowed_to_pay = \App\EcommerceModel\SalesPayment::check_if_has_remaining_unpaid($sale->gross_amount,$sale->id);
                                $remaining_payment_balance = \App\EcommerceModel\SalesPayment::get_remaining_unpaid($sale->gross_amount,$sale->id);
                                @endphp
                                <tr>
                                    <td>{{$sale->order_number}}</td>
                                    <td>{{$sale->created_at}}</td>
                                    <td>{{number_format($sale->gross_amount,2)}}</td>
                                    <td>{{number_format($paid,2)}}</td>
                                    <td>{{number_format($balance,2)}}</td>
                                    <td>{{$sale->delivery_status}}</td>
                                    
                                    <td class="px-1 paynow">
                                        @if ($is_still_allowed_to_pay == 1)
                                        <a href="javascript:void(0)" title="Pay now" class="btn btn-success btn-sm mb-1" onclick="pay_now('{{$sale->order_number}}','{{number_format($remaining_payment_balance,2,'.','')}}');">Pay now</a>&nbsp;
                                        @endif
                                    </td>                                     
                                    <td class="px-1">
                                        <a  target="_blank" href="{{route('profile.show_sales_summary',$sale->id)}}" title="view summary" class="btn btn-success btn-sm mb-1" style="width: 2.4em;"><i class="fa fa-eye pb-1"></i></a>
                                        <a href="#" title="view delivery history" class="btn btn-success btn-sm mb-1" data-toggle="modal" data-target="#delivery{{$sale->id}}"  style="width: 2.4em;"><i class="fa fa-truck pb-1"></i></a>
                                        @if ($paid <= 0)
                                            <a href="#" title="cancel this order" class="btn btn-success btn-sm mb-1" data-toggle="modal" data-target="#cancelmodal{{$sale->id}}"  style="width: 2.4em;"><i class="fa fa-times pb-1"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $modals .='
                                        <div class="modal fade" id="delivery'.$sale->id.'" tabindex="-1" role="dialog" aria-labelledby="trackModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="trackModalLabel">'.$sale->order_number.'</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="transaction-status">
                                                        </div>
                                                        <div class="gap-20"></div>
                                                        <div class="table-modal-wrap">
                                                            <table class="table table-md table-modal">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Date and Time</th>
                                                                        <th>Status</th>
                                                                        <th>Remarks</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                                                                    if($sale->deliveries){
                                                                        foreach($sale->deliveries as $delivery){
                                                                         $modals.='
                                                                            <tr>
                                                                                <td>'.$delivery->created_at.'</td>
                                                                                <td>'.$delivery->status.'</td>
                                                                                <td>'.$delivery->remarks.'</td>
                                                                            </tr>
                                                                        ';
                                                                        }
                                                                    }
                                                                $modals .='
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="gap-20"></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="detail'.$sale->id.'" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewModalLabel">'.$sale->order_number.'</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="transaction-status">
                                                            <p>Date: '.$sale->created_at.'</p>
                                                            <p>Payment Status: '.$sale->payment_status.'</p>
                                                        </div>
                                                        <div class="gap-20"></div>
                                                        <div class="table-modal-wrap">
                                                            <table class="table table-md table-modal">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Product Code</th>
                                                                        <th>Description</th>
                                                                        <th>Qty</th>
                                                                        <th>Price</th>
                                                                        <th>Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';

                                                                        $total_qty = 0;
                                                                        $total_sales = 0;
                                                                    foreach($sale->items as $item){
                                                                        $total_qty += $item->qty;
                                                                        $total_sales += $item->qty * $item->price;
                                                                        $modals.='
                                                                        <tr>
                                                                            <td>'.$item->product_id.'</td>
                                                                            <td>'.$item->product_name.'</td>
                                                                            <td>'.$item->qty.' '.$item->uom.'</td>
                                                                            <td>'.number_format($item->price,2).'</td>
                                                                            <td>'.number_format(($item->price * $item->qty),2).'</td>
                                                                        </tr>';
                                                                    }
                                                                    $modals.='
                                                                    <tr style="font-weight:bold;">
                                                                        <td colspan="2">Total</td>
                                                                        <td>'.number_format($total_qty,2).'</td>
                                                                        <td>&nbsp;</td>
                                                                        <td>'.number_format($total_sales,2).'</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="gap-20"></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="cancelmodal'.$sale->id.'" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewModalLabel">'.$sale->order_number.'</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="transaction-status">
                                                            <p>Payment Status: '.$sale->payment_status.'</p>
                                                        </div>
                                                        <div class="gap-20"></div>
                                                        <div class="table-modal-wrap">
                                                            <p>Are you sure you want to cancel this order?</p>
                                                        </div>
                                                        <div class="gap-20"></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="'.route('my-account.cancel_order').'" method="post">
                                                            <input type="hidden" name="_token" id="csrf-token" value="'.Session::token().'" />
                                                            <input type="hidden" name="sales_id" value="'.$sale->id.'">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <input type="submit" class="btn btn-danger" value="Yes Confirm">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                @endphp
                            @empty
                                <td>
                                    <div class="alert alert-warning" role="alert">
                                        No Record found
                                    </div>
                                </td>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="gap-70"></div>
    </div>

</section>

<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModal" aria-hidden="true">">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Chooose Payment Option</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <nav>
            <div class="row nav nav-tabs payment border-0" id="nav-tab" role="tablist">
                <a class="col-lg-3 nav-item nav-link border-1 text-center" id="nav-bank-tab" data-toggle="tab" href="#nav-bank" role="tab" aria-controls="nav-bank" aria-selected="true">
                    <h6><strong>Bank Deposit</strong></h6>
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
                <a class="col-lg-3 nav-item nav-link border-1 text-center" id="nav-transfer-tab" data-toggle="tab" href="#nav-transfer" role="tab" aria-controls="nav-transfer" aria-selected="false">
                    <h6><strong>Money Transfer</strong></h6>
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
                <a class="col-lg-3 nav-item nav-link border-1 text-center" id="nav-center-tab" data-toggle="tab" href="#nav-center" role="tab" aria-controls="nav-center" aria-selected="false">
                    <h6><strong>Payment Center</strong></h6>
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
                <a class="col-lg-3 nav-item nav-link border-1 text-center" id="nav-card-tab" data-toggle="tab" href="#nav-card" role="tab" aria-controls="nav-card" aria-selected="false">
                    <h6><strong>Credit / Debit Card</strong></h6>
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
            </div>
            <div class="col-lg-12 tab-content mt-4" style="width:100%" id="nav-tabContent">
                <div class="tab-pane fade" id="nav-card" role="tabpanel" aria-labelledby="nav-home-tab">
                    <form style="display:none;" name="ePayment" id="ePayment" action="https://payment.ipay88.com.ph/epayment/entry.asp" method="post">
                         <input type="hidden" name="merchantcode" id="merchantcode" value="PH00125">
                                <input type="hidden" name="paymentid" id="paymentid" value="1">
                                <input type="hidden" name="RefNo" id="RefNo">
                                <input type="hidden" name="Amount" id="Amount">
                                <input type="hidden" name="Currency" id="Currency" value="PHP">
                                <input type="hidden" name="Remark" id="Remark" value="Lydias Lechon Payment">
                                <input type="hidden" name="ProdDesc" id="ProdDesc" value="Lydias Lechon Payment">
                                <input type="hidden" name="UserName" id="UserName" value="{{Auth::user()->firstname}} {{Auth::user()->lastname}}">
                                <input type="hidden" name="UserEmail" id="UserEmail" value="{{Auth::user()->email}}">
                                <input type="hidden" name="UserContact" id="UserContact" value="">
                                <input type="hidden" name="ResponseURL" value="https://lydias-lechon.com/ipay_processor.php">
                                <input type="hidden" name="BackendURL" value="https://lydias-lechon.com/ipay_backend.php">
                                <input type="hidden" name="signature" id="signature" value="">                 
                        <table class="table table-borderless mb-3 border small" id="tb_paynamics">
                          <tbody>
                            <tr class="border">
                              <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>
                            </tr>
                            <tr>
                              <td>
                                
                                    <div class="form-group">
                                        <label for="Amount" class="col-form-label">Amount to Pay:</label>
                                        <input type="number" min="0.00" step="0.01" class="form-control max_to_pay" id="Amount2" name="Amount2">
                                    </div>
                                                     
                               
                              </td>
                            </tr>
                            <tr class="border">
                              
                              <td colspan="2" class="text-right"><a href="javascript:void(0)" onclick="paying();" class="btn btn-primary">Pay Now</a></td>
                            </tr>
                          </tbody>
                        </table>
                        </form>

                          <form action="{{route('paymaya.pay')}}" id="form_pamaya" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="sales_header_id" class="sales_header_id" value="">      

                            <div class="form-group">
                                <label for="" class="col-form-label">Amount to Pay:</label>
                            
                                <input type="number" required="required" min="0.00" readonly="readonly" value="0.00" class="form-control" id="payamount" name="amount">
                            </div>
                            <input type="submit" value="Submit" class="btn btn-primary">      
                        </form>
                    </div>
                     
                <div class="tab-pane fade" id="nav-bank" role="tabpanel" aria-labelledby="nav-home-tab">
                    <form action="{{route('payment.add.store_customer')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="sales_header_id" class="sales_header_id" value=""> 
                    <table class="table table-borderless mb-3 border small" id="tb_bankdeposit">
                      <tbody>
                        <tr class="border">
                          <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>                   
                        </tr>
                        <tr>
                          <td>
                             
                                <div class="form-group">
                                    <label for="" class="col-form-label">BDO or Metrobank:</label>
                                    <select class="form-control" required="required" name="pamenty_mode" id="pamenty_mode" onchange="bank_change($(this).val());">
                                        <option value="">Select</option>
                                    <!--     <option value="Metrobank">Metrobank</option> -->
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
                            </td>
                        </tr>
                        <tr>
                            <td>                    
                                <div class="form-group">
                                    <label for="" class="col-form-label">Amount to Pay:</label>
                                    <input type="number" required="required" min="0.00" step="0.01" class="form-control max_to_pay" id="oamount" name="amount">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Paid Date:</label>
                                    <input type="date" required="required" class="form-control" id="payment_dt" name="payment_dt" max="{{date('Y-m-d')}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Receipt/Reference Number:</label>
                                    <input type="text" required="required"  class="form-control" id="ref_no" name="ref_no">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Attachment:</label>
                                    <input type="file" required="required" class="form-control" id="file" name="file">
                                </div>  
                            </td>
                        </tr>
                         <tr class="border">
                                              <td colspan="2" class="text-right"> <input type="submit" value="Submit" class="btn btn-primary">    </td>
                                            </tr>
                       
                      </tbody>
                    </table>
                        </form>
                </div>
                 <div class="tab-pane fade" id="nav-transfer" role="tabpanel" aria-labelledby="nav-home-tab">
                     <form action="{{route('payment.add.store_customer')}}" id="form_gpay" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="sales_header_id" class="sales_header_id" value="">   
                    <table class="table table-borderless mb-3 border small" id="tb_moneytransfer">
                      <tbody>
                        <tr class="border">
                          <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>
                       
                        </tr>
                        <tr>
                          <td>
                           
                                <div class="form-group" id="money_transfer_div">
                                    <label for="" class="col-form-label">GCash or PayMaya:</label>
                                    <select class="form-control" required="required" name="pamenty_mode" id="pamenty_mode_gpay" onchange="gcash_paymaya_change()">
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
                            </td>
                        </tr>
                        <tr>
                            <td>  
                                                    
                                <div class="form-group">
                                    <label for="" class="col-form-label">Amount to Pay:</label>
                                    <input type="number" required="required" min="0.00" step="0.01" class="form-control max_to_pay" id="oamount_gpay" name="amount">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group not_paymaya">
                                    <label for="" class="col-form-label">Paid Date:</label>
                                    <input type="date" required="required" class="form-control not_paymayaf" id="payment_dt" name="payment_dt" max="{{date('Y-m-d')}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group not_paymaya">
                                    <label for="" class="col-form-label">Receipt/Reference Number:</label>
                                    <input type="text" required="required"  class="form-control not_paymayaf" id="ref_no" name="ref_no">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group not_paymaya">
                                    <label for="" class="col-form-label">Attachment:</label>
                                    <input type="file" required="required" class="form-control not_paymayaf" id="file" name="file">
                                </div> 
                            </td>
                        </tr>
                        
                         <tr class="border">
                                              <td colspan="2" class="text-right"> <input type="submit" value="Submit" class="btn btn-primary">    </td>
                                            </tr>
                       
                       
                      </tbody>
                    </table>
                    </form>
                </div>
                <div class="tab-pane fade" id="nav-center" role="tabpanel" aria-labelledby="nav-profile-tab">
                     <form action="{{route('payment.add.store_customer')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="pamenty_mode" id="pamenty_mode" value="M lhuillier">
                                <input type="hidden" name="sales_header_id" class="sales_header_id" value=""> 
                    <table class="table table-borderless mb-3 border small" id="tb_paymentcenter">
                      <tbody>
                        <tr class="border">
                          <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>
                       
                        </tr>
                        <tr><td> <h4>Receiver: <b>Lydias Lechon</b></h4></td></tr>
                        <tr>
                          <td>
                             
                                                      
                                <div class="form-group">
                                    <label for="" class="col-form-label">Amount to Pay:</label>
                                    <input type="number" required="required" min="0.00" step="0.01" class="form-control max_to_pay" id="oamount" name="amount">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Paid Date:</label>
                                    <input type="date" required="required" class="form-control" id="payment_dt" name="payment_dt" max="{{date('Y-m-d')}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Receipt/Reference Number:</label>
                                    <input type="text" required="required"  class="form-control" id="ref_no" name="ref_no">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Attachment:</label>
                                    <input type="file" required="required" class="form-control" id="file" name="file">
                                </div> 
                                   
                                 
                            
                          </td>
                        </tr>
                        <tr class="border">
                                              <td colspan="2" class="text-right"> <input type="submit" value="Submit" class="btn btn-primary">    </td>
                                            </tr>
                      </tbody>
                    </table>
                    </form>
                </div>
                
                

                
                
            </div>
          
        </nav>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>


{!!$modals!!}

@endsection
@section('jsscript')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
{{--     <script src="{{ asset('theme/lydias/plugins/datatables/datatables.js') }}"></script> --}}
   
    <script>
        
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
        function paying(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                data: { amount: $('#Amount2').val(), order: $('#RefNo').val() },
                type: "post",
                url: "{{route('cart.generate_payment')}}",
               
                success: function(returnData) {
                  
                    if (returnData['success']) {
                        $('#Amount').val(returnData['amount']);
                        $('#UserContact').val(returnData['customer_contact_number']);
                        $('#signature').val(returnData['signature']);
                        $('#ProdDesc').val(returnData['saved_items']);  
                      
                        $('#ePayment').submit();
                    }
                }
            });
        }

        function pay_now(order,amt) {   
        
            $('#RefNo').val(order);
            $('#Amount2').val(amt);
            $('#payamount').val(amt);
            $('#oamount_gpay').val(amt);
            $('.max_to_pay').attr({"max":parseFloat(amt)});
            $('.sales_header_id').val(order);
           // $('#payment_modal').modal('show');
    
           $('#paymentModal').modal('show');
        }

        function bank_change(id){
            $('.banks').hide();
            if(id == 'Metrobank'){
                $('#metrobank_details').show();
            }
            if(id == 'BDO'){
                $('#bdo_details').show();
            }

            
        }   

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
    </script>
@endsection
