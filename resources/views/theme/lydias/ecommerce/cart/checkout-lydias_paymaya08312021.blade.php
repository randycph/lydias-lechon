@extends('theme.'.config('app.frontend_template').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('lib/datetime/css/bootstrap-datetimepicker.min.css') }}" />
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
    <link href="{{ asset('lib/datetime/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
    <style>
        #loading-overlay {
            position: absolute;
            width: 100%;
            height:180%;
            left: 0;
            top: 0;
            display: none;
            align-items: center;
            background-color: #000;
            z-index: 999;
            opacity: 0.5;
        }
        .loading-icon{ position:absolute;border-top:2px solid #fff;border-right:2px solid #fff;border-bottom:2px solid #fff;border-left:2px solid #767676;border-radius:25px;width:25px;height:25px;margin:0 auto;position:absolute;left:50%;margin-left:-20px;top:50%;margin-top:-20px;z-index:4;-webkit-animation:spin 1s linear infinite;-moz-animation:spin 1s linear infinite;animation:spin 1s linear infinite;}
        @-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
        @-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
        @keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }

        .datetimepicker{
            font-family: sans-serif, Arial;
        }
    </style>
@endsection

@section('content')
    <section>
        <form action="{{route('cart.temp_sales')}}" method="post" id="chk_form">
            @csrf
        <div class="content-wrapper">
        <div class="gap-70"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6" style="font-family:sans-serif, arial;">                
                            <h2 id="header_label">@if (session('delivery_option') == 'delivery') Delivery @else Customer @endif Information</h2>                        
                            <div class="form-group">
                                <label for="dtp_input1" class="control-label">Date and Time Needed *:</label>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <input type="date" name="need_date" id="need_date" class="form-control" min="{{date('Y-m-d')}}" onchange="combine_datetime();">
                                    </div>
                                    <div class="col">
                                        <select name="need_time" id="need_time" class="form-control" onchange="combine_datetime();">
                                            <option value="">- Time -</option>
                                           
                                            <option value="05:00 AM">05:00 AM</option>
                                            <option value="06:00 AM">06:00 AM</option>
                                            <option value="07:00 AM">07:00 AM</option>
                                            <option value="08:00 AM">08:00 AM</option>
                                            <option value="09:00 AM">09:00 AM</option>
                                            <option value="10:00 AM">10:00 AM</option>
                                            <option value="11:00 AM">11:00 AM</option>
                                            <option value="12:00 PM">12:00 NOON</option>
                                            <option value="01:00 PM">01:00 PM</option>
                                            <option value="02:00 PM">02:00 PM</option>
                                            <option value="03:00 PM">03:00 PM</option>
                                            <option value="04:00 PM">04:00 PM</option>
                                            <option value="05:00 PM">05:00 PM</option>
                                            <option value="06:00 PM">06:00 PM</option>
                                            <option value="07:00 PM">07:00 PM</option>
                                            <option value="08:00 PM">08:00 PM</option>
                                            <option value="09:00 PM">09:00 PM</option>
                                           
                                        </select>
                                    </div>
                                </div>
                                <input size="16" id="dateneeded" name="dateneeded" type="hidden" value="" required="required">
                                {{-- <div class="input-group date form_datetime" data-date="{{date('Y-m-d')}}T08:00:07Z" data-date-format="MM dd, yyyy - HH:ii P" data-link-field="dtp_input1">
                                    <input class="form-control" size="16" id="dateneeded" name="dateneeded" type="text" value="" required="required">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>--}}
                                <input type="hidden" id="dtp_input1" value="" />
                                <input type="hidden" id="error_id" value="0" /> 

                                <div class="alert alert-warning" role="alert" id="daten_warning_div" style="display:none;font-size:12px;margin-top:12px;">
                                    <h4 class="alert-heading">Warning!</h4>
                                    <ul class="text-danger" id="daten_warning"></ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="shipping_type" class="control-label">Other Instruction:</label>
                                <textarea name="instruction" id="instruction" class="form-control" cols="30" rows="2"></textarea>
                            </div>
                            <div class="form-group" style="display:none;">
                                <label for="shipping_type" class="control-label">Agent/Referrer:</label>
                                <input type="text" class="form-control" name="agent" id="agent" placeholder="Agent Code" value="{{Auth::user()->agent_code}}">
                            </div>      
                            <div class="form-group">
                                <label for="shipping_type" class="control-label" id="shipping_type_label">{{ucwords(session('delivery_option'))}} *:</label>
                                <select name="shipping_type" id="shipping_type" class="form-control" required="required">
                                    <option value="d2d" @if (session('delivery_option') == 'delivery') selected="selected" @endif>Door to door</option>
                                    <option value="storepickup" @if (session('delivery_option') == 'pick-up') selected="selected" @endif>Pick up at nearest store</option>
                                </select>
                            </div>        
                                            
                            <div id="storepickup_div" @if (session('delivery_option') == 'delivery')  style="display:none;" @endif>
                                <div class="form-group">
                                    <label for="uname1" class="control-label">Select Branch:</label>
                                    <select name="delivery_branch" id="delivery_branch" class="form-control">
                                        <option selected="selected" value="">Select Branch</option>
                                        @forelse($branches as $branch)
                                            <option value="{{$branch->name}}">{{$branch->name}}</option>
                                        @empty
                                        @endforelse                                        
                                    </select>
                                </div>
                            </div>
                            <div id="d2d_div" @if (session('delivery_option') == 'pick-up')  style="display:none;" @endif>
                                <div class="form-group">
                                    <label class="custom-control fill-checkbox">
                                        <input type="checkbox" class="fill-control-input" onchange="$('.orig').val('');">
                                        <span class="fill-control-indicator"></span>
                                        <span class="fill-control-description">Use different shipping address</span>
                                    </label>
                                </div>
                                <div class="gap-20"></div>
                                <div class="form-group">
                                    <label for="uname1" class="control-label">Contact Person</label>
                                    <input type="text" class="orig form-control" value="{{$user->firstname}} {{$user->lastname}}" name="uname1" id="uname1" required="">
                                </div>
                                <div class="form-group">
                                    <label for="mobile" class="control-label">Contact Number</label>
                                    <input type="number" class="orig form-control" value="{{$user->contact_mobile}}" name="mobile" id="mobile" required>
                                </div>                            
                                <div class="form-group">
                                    <label for="city" class="control-label">Location</label>
                                    <select required="required" name="location" id="location" class="form-control">
                                        <option selected="selected" value="">Select</option>
                                        @forelse($locations as $loc)
                                            <option value="{{$loc->name}}">{{$loc->name}}</option>
                                        @empty
                                        @endforelse
                                        <option value="Other">Other</option>
                                    </select>
                                </div>   
                                <div class="form-group" style="display:none;" id="delivery_other">
                                    <span class="text-danger">Note: If you're address is not listed on the location above, Our representative will contact you for the delivery fee.</span>
                                </div>                           
                                <div class="form-group">
                                    <label for="gaddress1" class="control-label">Delivery Address</label>
                                    <textarea name="delivery_address" id="delivery_address" class="orig form-control" cols="30" rows="2">{{$user->address_street}},{{$user->address_municipality}} {{$user->address_city}}, {{$user->address_region}}</textarea>
                                </div>
                                <span id="complete_delivery_address" style="text-transform:capitalize;"></span>                            
                                
                            </div>
                  
                        
                       
                    </div>
                    <div class="col-md-6">
                        <h2>Payment Summary</h2>
                        <input type="hidden" id="minimum_order" value="{{Setting::info()->minimum_order}}">
                        <table class="table">
                            <tr>
                                <td>Order</td>
                                <td align="right">
                                    <input type="hidden" id="order_amount" name="order_amount" value="{{($products->sum('itemTotalPrice'))}}">
                                    <input type="hidden" id="delivery_fee" name="delivery_fee" value="0">
                                    <input type="hidden" id="coupon" name="coupon" value="{{$coupon}}">
                                    <input type="hidden" id="total_amount" name="total_amount" value="{{($products->sum('itemTotalPrice') - $coupon)}}">
                                    &#8369; {{number_format($products->sum('itemTotalPrice'),2)}}</td>
                            </tr>
                            @if($coupon>0)
                                <tr>
                                    <td>Coupon</td>
                                    <td align="right">&#8369; <span id="coupon_fee_div">({{ number_format($coupon,2) }})</span></td>
                                </tr>
                            @endif
                            <tr>
                                <td>Delivery Fee</td>
                                <td align="right">&#8369; <span id="delivery_fee_div">0.00</span></td>
                            </tr>
                            <tr style="font-size:20px;font-weight:bold;">
                                <td>Total:</td>
                                <td align="right">&#8369; <span id="total_amount_div">{{number_format(($products->sum('itemTotalPrice') - $coupon),2)}}</span></td>
                            </tr>
                        </table>                   
                        <div class="gap-20"></div>
                        <div class="form-group text-right">                            
                            <!-- <input type="submit" class="btn btn-success" value="Pay Now"> -->
                             <a class="btn btn-info" href="javscript:void(0);" onclick="paying_now();">Submit</a>

                        </div>
                        <div id="paying_div" style="display:none;">
                            <div class="form-group">
                                <label for="deposit" class="control-label">Amount to Pay</label>
                                <input type="number" step="0.01" class="form-control text-right" value="{{number_format($products->sum('itemTotalPrice'),2,'.','')}}" name="deposit" id="deposit">
                                <small id="depositHelp" class="form-text text-muted" style="display:none;">We require 50% downpayment 
                                    <span id="dep50" style="font-weight:bold;font-style:italic;">(&#8369; 
                                        {{number_format((($products->sum('itemTotalPrice') - $coupon)/2),2)}})
                                    </span> 
                                before processing your order.</small>
                            </div>
                            <a class="btn btn-success" href="javscript:void(0)" onclick="pay_now();">Submit</a>

                            
                        </div>
                    </div>
                    
                    
                    
                </div>
            </div>
        <div class="gap-70"></div>
        </div>
        </form>
    </section>
    <div id="loading-overlay">
        <div class="loading-icon"></div>
    </div>  
<form name="ePayment" id="ePayment" action="https://payment.ipay88.com.ph/epayment/entry.asp" method="post" style="display:none;">
    <input type="text" name="merchantcode" id="merchantcode" value="PH00125">
    <input type="text" name="paymentid" id="paymentid" value="1">
    <input type="text" name="RefNo" id="RefNo">
    <input type="text" name="Amount" id="Amount">
    <input type="text" name="Currency" id="Currency" value="PHP">
    <input type="text" name="Remark" id="Remark" value="Lydias Lechon Payment">
    <input type="text" name="ProdDesc" id="ProdDesc" value="Lydias Lechon Payment">
    <input type="text" name="UserName" id="UserName" value="{{Auth::user()->firstname}} {{Auth::user()->lastname}}">
    <input type="text" name="UserEmail" id="UserEmail" value="{{Auth::user()->email}}">
    <input type="text" name="UserContact" id="UserContact" value="">
    <input type="text" name="ResponseURL" value="https://lydias-lechon.com/ipay_processor.php">
    <input type="text" name="BackendURL" value="https://lydias-lechon.com/ipay_backend.php">
    <input type="text" name="signature" id="signature" value="">
</form>

<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Chooose Payment Option</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <nav class="px-4">
            <div class="row nav nav-tabs payment border-0" id="nav-tab" role="tablist">
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
                
                    {{--
                    <div class="col-lg-6 mb-6">
                        <div class="payment-options">
                            <div class="row no-gutters">
                                
                                <div class="col-md-6 col-6 text-center payment-options-opt">
                                    <div class="form-check border p-4">
                                      <input class="form-check-input" type="radio" name="payment_type" onchange="aa('bank deposit')" id="exampleRadios1" value="bank deposit">
                                      <label class="form-check-label" for="exampleRadios1">
                                        <h6><strong>Bank Deposit</strong></h6>
                                        <div class="payment-options-img d-flex align-items-center justify-content-center">
                                            <table class="table table-borderless">
                                              <tbody>
                                                <tr>
                                                  <td><img src="{{ asset('images/payment/metrobank.jpg') }}" /></td>
                                                </tr>
                                                <tr>
                                                  <td><img src="{{ asset('images/payment/bdo.jpg') }}" /></td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </div>
                                       
                                      </label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6 text-center payment-options-opt">
                                    <div class="form-check border p-4">
                                      <input class="form-check-input" type="radio" name="payment_type" onchange="aa('money transfer')" id="exampleRadios2" value="money transfer">
                                      <label class="form-check-label" for="exampleRadios2">
                                        <h6><strong>Money Transfer</strong></h6>
                                        <div class="payment-options-img d-flex align-items-center justify-content-center">
                                            <table class="table table-borderless">
                                              <tbody>
                                                <tr>
                                                  <td><img src="{{ asset('images/payment/g-cash.jpg') }}" /></td>
                                                </tr>
                                                <tr>
                                                  <td><img src="{{ asset('images/payment/pay-maya.jpg') }}" /></td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </div>
                                       
                                      </label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6 text-center payment-options-opt">
                                    <div class="form-check border p-4">
                                      <input class="form-check-input" type="radio" name="payment_type" onchange="aa('payment center')" id="exampleRadios3" value="payment center">
                                      <label class="form-check-label" for="exampleRadios3">
                                        <h6><strong>Payment Center</strong></h6>
                                        <div class="payment-options-img d-flex align-items-center justify-content-center">
                                            <table class="table table-borderless">
                                              <tbody>
                                                <tr>
                                                  <td><img src="{{ asset('images/payment/m-lhuillier.jpg') }}" /></td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </div>
                                       
                                      </label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6 text-center payment-options-opt">
                                    <div class="form-check border p-4">
                                      <input class="form-check-input" type="radio" name="payment_type" onchange="aa('paynamics')" checked="checked" id="exampleRadios4" value="paynamics">
                                      <label class="form-check-label" for="exampleRadios4">
                                        <h6><strong>Credit / Debit Card</strong></h6>
                                        <div class="payment-options-img d-flex align-items-center justify-content-center">
                                            <table class="table table-borderless">
                                              <tbody>
                                                <tr>
                                                  <td><img src="{{ asset('images/payment/credit-debit.jpg') }}" /></td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </div>
                                        
                                      </label>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    --}}
                
                <div class="col-lg-12 tab-content mt-4" style="width:100%" id="nav-tabContent">
                    <div class="tab-pane fade" id="nav-bank" role="tabpanel" aria-labelledby="nav-home-tab">
                        <form action="{{route('payment.add.store_customer')}}" method="post" enctype="multipart/form-data">
                        <table width="100%" class="table table-borderless mb-3 border small" id="tb_bankdeposit">
                          <tbody>
                            @csrf
                                    <input type="hidden" name="sales_header_id" class="sales_header_id" value="">  
                                    
                            <tr class="border">
                              <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>                           
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="" class="col-form-label">BDO or Metrobank:</label>
                                        <select class="form-control" name="pamenty_mode" id="pamenty_mode" onchange="bank_change($(this).val());">
                                            <option value="">Select</option>
                                            <option value="Metrobank">Metrobank</option>
                                            <option value="BDO">BDO</option>
                                        </select>    
                                        <div id="metrobank_details" style="display:none;" class="banks">
                                            <br>
                                            <p><b>Metrobank Account Details</b><br>  
                                            Account Name: <b>SAN GREGORIO ALL VENTURES CORP.</b><br> 
                                            Current Account #: <b>481-7-48191145-8</b></p>
                                        </div>         
                                        <div id="bdo_details" style="display:none;" class="banks">
                                            <br>
                                            <p><b>BDO Account Details<b><br>
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
                                        <input type="number" required="required" min="0.00" step="0.01" class="form-control oamount" id="oamount" name="amount">
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
                              <td colspan="2" class="text-right"><input type="submit" value="Submit" class="btn btn-primary">    </td>
                            </tr>
                          </tbody>
                        </table>

                        </form>
                    </div>
                    <div class="tab-pane fade" id="nav-transfer" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <form action="{{route('payment.add.store_customer')}}" id="form_gpay" method="post" enctype="multipart/form-data">
                        <table width="100%" class="table table-borderless mb-3 border small" id="tb_moneytransfer">
                          <tbody>
                            <tr class="border">
                              <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>                           
                            </tr>
                            <tr>
                                <td>
                                    @csrf
                                    <input type="hidden" name="sales_header_id" class="sales_header_id" value="">   
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
                                </td>
                             </tr>
                            <tr>
                                <td>
                                     <div class="form-group">
                                            <label for="" class="col-form-label">Amount to Pay:</label>
                                            <input type="number" required="required" min="0.00" step="0.01" class="form-control oamount" id="oamount" name="amount">
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
                                              <td colspan="2" class="text-right"><input type="submit" value="Submit" class="btn btn-primary">  </td>
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
                        <table width="100%" class="table table-borderless mb-3 border small" id="tb_paymentcenter">
                          <tbody>
                            <tr class="border">
                              <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>
                           
                            </tr>
                            <tr><td> <h4>Receiver: <b>Lydias Lechon</b></h4></td></tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                            <label for="" class="col-form-label">Amount to Pay:</label>
                                            <input type="number" required="required" min="0.00" step="0.01" class="form-control oamount" id="oamount" name="amount">
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
                                              <td colspan="2" class="text-right"><input type="submit" value="Submit" class="btn btn-primary">      </td>
                                            </tr>
                          </tbody>
                        </table>
                         </form>
                    </div>
                    <div class="tab-pane fade" id="nav-card" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <table width="100%" class="table table-borderless mb-3 border small" id="tb_paynamics">
                          <tbody>
                            <tr class="border">
                              <td colspan="2"><span class="m-0 h6"  style="color:#f26522;"><strong>Payment</strong></span></td>
                            </tr>
                            <tr>
                              <td colspan="2">
                                <form style="display:none;" name="ePayment" id="ePayment" action="https://payment.ipay88.com.ph/epayment/entry.asp" method="post">
                                    <div class="form-group">                                
                                        <label for="Amount" class="col-form-label">Amount to Pay:</label>
                                        <input type="number" min="0.00" step="0.01" class="form-control" id="Amount2" name="Amount2">
                                    </div>
                                                               
                                </form>
                                <form name="ePayment" id="ePayment" style="display:none;" action="https://payment.ipay88.com.ph/epayment/entry.asp" method="post">
                                            <div class="form-group">                                
                                                <label for="Amount" class="col-form-label">Amount to Pay:</label>
                                                <input type="number" min="0.00" readonly="readonly" class="form-control" id="Amount2" name="Amount2">
                                            </div>

                                        </form>
                                        <form action="{{route('paymaya.pay')}}" id="form_pamaya" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="sales_header_id" class="sales_header_id" value="">   
                                            <table width="80%" class="table table-borderless mb-3 border small">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="form-group">
                                                                <label for="" class="col-form-label">Amount to Pay:</label>
                                                                <input type="number" required="required" min="0.00" step="0.01" class="form-control oamount" readonly="readonly" name="amount">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="border">
                                                        <td colspan="2" class="text-right"> <input type="submit" value="Pay Now" class="btn btn-primary">       </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                   
                </div>
               
                  
           
            </div>
        </nav>
      </div>
      <div class="modal-footer">
        <a class="btn btn-dark" href="#" onclick="$('#paymentModal').modal('hide'); $('#successModal').modal('show');" style="display: none;">Pay Later</a>
       
      </div>
      <br><br><br>
    </div>
  </div>
</div>
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Order Successfully Submitted</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        We've sent you an email for your order summary.
      </div>
      <div class="modal-footer">
       <a class="btn btn-success" href="{{route('product.front.show_forsale')}}">OK</a>
       
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="disabledatemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">To our valued customers!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    

        <p>Our delivery service has already reached it's maximum, please be informed that all orders for December 24, 25 & 31 is on a store pick up basis only.</p>

        <p>We can no longer accomodate deliveries during the said dates. </p>

        <p>We apologize for the inconvenience.</p>

        <p>Happy Holidays!</p>
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>       
      </div>
    </div>
  </div>
</div>
@endsection

@section('jsscript')
    <script src="{{ asset('lib/datetime/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src=""{{ asset('lib/datetime/js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src=""{{ asset('lib/datetime/js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
    <script>

    $('#paymentModal').on('hidden.bs.modal', function () {
      $('#paymentModal').modal('hide'); 
      $('#successModal').modal('show');
    });


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
    $('#successModal').on('hidden.bs.modal', function () {
      window.location.href = "{{route('product.front.show_forsale')}}";
    });

    $('#delivery_address').keyup(function() {       
        combine_address();
    });

    $('#need_date').change(function(){
         check_disable_dates();
    });

    function check_disable_dates(){
        var disable_dates = ["2020-12-24","2020-12-25","2020-12-31"];
        if($('#shipping_type').val() == 'd2d'){
            //console.log($('#need_date').val());
            if($.inArray($('#need_date').val(), disable_dates) > -1){
                // alert("Our apology, We only accept STORE PICKUP as delivery type on Dec 24, 25 and 31.");
                $('#disabledatemodal').modal('show');
                $('#need_date').val("");
            }
        }   
    }

    function combine_address(){
        $('#complete_delivery_address').html('<b style="font-size:12px;">Complete Delivery Address:</b> '+$('#delivery_address').val()+', '+$('#location').val());
    }
    function combine_datetime(){
        if($('#need_date').val() && $('#need_time').val()){
           //alert($('#need_date').val()+' - '+$('#need_time').val());
            $('#dateneeded').val($('#need_date').val()+' - '+$('#need_time').val());
            check_time();
        }
        
    }
    function check_time(){
        
        var v = $('#dateneeded').val();  
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: {dateneeded: v},
            type: "post",
            url: "{{route('cart.check_dateneeded')}}",            
            success: function(returnData) {   
                if(returnData['remark'] > 0) {
                    $('#daten_warning').html(returnData['err']);
                    $('#daten_warning_div').show();
                    $('#error_id').val(returnData['remark']);
                }
                else{
                    $('#daten_warning').html('');
                    $('#daten_warning_div').hide();
                    $('#error_id').val('0');
                }
            }
            
        });
        
    };

    function paying_now(){

        $('#paying_div').show();
        //return false;
        //pay_now();

    }
    function pay_now() {      
        var deposit50 = $('#total_amount').val()/2; 
        
        if(parseFloat($('#minimum_order').val()) > parseFloat($('#total_amount').val())){
            alert('A minimum of Php '+$('#minimum_order').val()+' is required in order to checkout your order.');
            return false;
        } 
        if($('#dateneeded').val()==''){
            alert('Please enter the date and time you need this order!');
            return false;
        }
        if($('#mobile').val()==''){
            alert('Please enter contact number!');
            return false;
        }
        if($('#shipping_type').val()==''){
            alert('Please select delivery type!');
            return false;
        }
        else if($('#shipping_type').val()=='d2d'){
            if(parseFloat(deposit50) > parseFloat($('#deposit').val()) ){
                alert('The required downpayment for door to door delivery is 50% of your total amount. Please pay atleast Php '+addCommas(parseFloat(deposit50).toFixed(2)));
                return false;
            }
            if($('#location').val()==''){
                alert('Please select your location.');
                return false;
            }
            
        }
        else if($('#shipping_type').val()=='storepickup'){
            if(parseFloat($('#total_amount').val()) > parseFloat($('#deposit').val()) ){
                alert('We require full payment for your order. Please pay the amount Php '+addCommas(parseFloat($('#total_amount').val()).toFixed(2)));
                return false;
            }

            if($('#delivery_branch').val()==''){
                alert('Please select your preferred branch to pickup your order!');
                return false;
            }
        }


        // var deposit50 = $('#total_amount').val()/2;
        // if(parseFloat(deposit50) > parseFloat($('#deposit').val()) ){
        //     alert('The required downpayment is 50% of your total amount. Please pay atleast Php '+addCommas(parseFloat(deposit50).toFixed(2)));
        //     return false;
        // }

        if($('#error_id').val()!='0'){
            alert("We are not able to accommodate your order base on your preferred date and time. Kindly refer to the warning message that appeared on your order screen or call our hotline at 89391221 / 89394665.  Thank you.");
            return false;
        }
        
        //$('#paymentModal').modal('show');
        //$('#Amount').val($('#deposit').val())

        let data = $('#chk_form').serialize();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: data,
            type: "post",
            url: "{{route('cart.temp_sales')}}",
            beforeSend: function(){
                $("#loading-overlay").show();
            },
            success: function(returnData) {
                $("#loading-overlay").hide();
                // alert($('#Amount').val());
                // return false;
                if (returnData['success']) {
                    
                    $('#RefNo').val(returnData['order_number']);
                    $('#ProdDesc').val(returnData['saved_items']);  
                    $('#Amount').val(returnData['amount']);
                    $('#UserContact').val(returnData['customer_contact_number']);
                    $('#signature').val(returnData['signature']);
                    $('#Amount2').val($('#deposit').val());
                    $('.oamount').val(parseFloat($('#deposit').val()).toFixed(2));
                    $('.sales_header_id').val(returnData['order_number']);
                    $('#paymentModal').modal('show');
                    //$('#ePayment').submit();
                }
            },
            failed: function() {
                $("#loading-overlay").hide(); 
            }
        });
    }
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('.form_datetime').datetimepicker('setStartDate', '{{date("Y-m-d", strtotime("+0 days"))}}');

    $('#shipping_type').change(function(){
        check_disable_dates();
        var typ = $(this).val();
        if(typ == 'd2d'){
            $('#d2d_div').show();
            $('#storepickup_div').hide();
            $('#shipping_type_label').html('Delivery *:');
            $('#header_label').html('Delivery Information');
        }
        if(typ == 'storepickup'){
            $('#d2d_div').hide();
            $('#storepickup_div').show();
            $('#delivery_fee_div').html('0.00');
            $('#delivery_fee').val(0);
            $('#shipping_type_label').html('Pick-up *:');
            $('#header_label').html('Customer Information');
        }
        compute_total();      
    });

    $('#location').change(function(){
        if($(this).val() == 'Other'){
            $('#delivery_other').show();
        }
        else if($(this).val() == ''){
            $('#delivery_fee_div').html('0.00');  
            $('#delivery_fee').val(0);
        }
        else{            

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                data: { location: $('#location').val() },
                type: "post",
                url: "{{route('cart.front.get_shipping_fee')}}",                
                success: function(returnData) {                      
                        $('#delivery_fee_div').html(parseFloat(returnData['fee']).toFixed(2));
                        $('#delivery_fee').val(parseFloat(returnData['fee']).toFixed(2));      
                         compute_total(); 
                        //alert(returnData['fee']);    
                },
                failed: function() {
                    $('#delivery_fee_div').html('0.00');  
                    $('#delivery_fee').val(0);
                    compute_total();
                }
               
            });
            // var a = ($(this).val()).split('|');
            // $('#delivery_fee_div').html(addCommas(parseFloat(a[1]).toFixed(2)));  
            // $('#delivery_fee').val(a[1]);          
        }
        compute_total();
        combine_address();
        //alert('compute');
        
    });
    function compute_total(){
        //alert(document.getElementById('delivery_fee').value);
        var total_a = (parseFloat($('#order_amount').val()) + parseFloat($('#delivery_fee').val())) - parseFloat($('#coupon').val());   
       
        $('#total_amount_div').html(addCommas(parseFloat(total_a).toFixed(2)));
        $('#total_amount').val(total_a);
        $('#deposit').val(total_a);
        $('#dep50').html('(&#8369; '+parseFloat(total_a)/2+')');
        
    }


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
    </script>
    
@endsection
