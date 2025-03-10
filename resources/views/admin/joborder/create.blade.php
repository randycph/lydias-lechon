@extends('admin.layouts.app')

@section('pagetitle')
    Job Order
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/prismjs/themes/prism-vs.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datextime/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lydias-admin.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">

    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">

    <style>
        .bootstrap-tagsinput .tag {
            background-color : rgb(255, 255, 255, 0.5);
            color : black;
        }
    </style>
@endsection

@section('content')

<div class="container pd-x-0">
    <div class="d-sm-flex justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page">Portal</li>
                    <li class="breadcrumb-item active" aria-current="page">Job Orders</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Add Job Order</h4>
        </div>
    </div>
     @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
          Coupon code has been used already!
        </div>
     @endif
    <form autocomplete="off" action="{{ route('joborders.store') }}" method="post" id="joborder_form" enctype="multipart/form-data">
        @csrf
        <div class="row row-sm">
            <div class="col-lg-6">
                @if(Session::has('branch') && strlen(session('branch'))>0)

                    <input type="hidden" name="branch_source" value="{{ session('branch') }}|0">
                    
                @else
                    <div class="form-group">
                        <label class="d-block">Select Branch:</label>
                        <select name="branch_source" id="branch_source" required="required" class="form-control">
                            <option value="">- Select Branch -</option>
                            @foreach($branches_store as $b)
                                <option value="{{$b->name}}|{{$b->rate}}">{{$b->name}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="row row-sm">
            <div class="col-lg-6">
                <h6 class="mg-b-0 tx-semibold">Customer Details</h6>
                <hr>
                <div class="form-group" id="customer_type_div" style="display:none;">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="customer_type" id="cs-exist" value="cs-exist">
                        <label class="form-check-label" for="cs-exist">Existing Customer</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="customer_type" id="cs-new" value="cs-new">
                        <label class="form-check-label" for="cs-new">New Customer</label>
                    </div>
                </div>

                <!-- Existing Customer -->
                <div class="cs-exist box">
                    <div class="form-group">
                        <label class="d-block">Search Customer *</label>
                        <input id="input2" name="customer" type="text" onchange="view_customer_details($(this).val());" class="form-control" value="{{ old('customer') }}">
                    </div>
                    <div class="form-group">
                        <div id="customer_details"></div>
                    </div>
                </div>

                <!-- New Customer -->
                <div class="cs-new box cnew_details">
                    <div class="form-group">
                        <label>Last Name <span class="tx-danger">*</span></label>
                        <input type="text" name="lname" class="form-control" required="required">
                    </div>
                    <div class="form-group">
                        <label>First Name <span class="tx-danger">*</span></label>
                        <input type="text" name="fname" class="form-control" required="required">
                    </div>
                    <div class="form-group">
                        <label>E-mail Address </label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Birthdate </label>
                        <input type="date" name="birthdate" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="house_no" class="form-control">
                        <small class="tx-gray-400">Unit/House No., Floor</small>

                        <input type="text" name="street" class="form-control mg-t-15">
                        <small class="tx-gray-400">Street</small>

                        <input type="text" name="barangay" class="form-control mg-t-15">
                        <small class="tx-gray-400">Barangay/Subdivision</small>

                        <input type="text" name="city" class="form-control mg-t-15">
                        <small class="tx-gray-400">City/Province</small>
                    </div>
                    <div class="form-group">
                        <label>Contact Number <span class="tx-danger">*</span></label>
                        <input type="text" name="mobile" id="mobile" required="required" class="form-control" maxlength="13" onkeydown="isNumberKey(e);">
                    </div>
                </div>
                <input type="hidden" id="has_lechon" name="has_lechon" value="0">
                <h6 class="mg-b-0 tx-semibold">Order Details</h6>
                @include('admin.joborder.on-screen-computation')
                <form>
                    <div style="padding-bottom:0px;" class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="d-block">Select Products</label>
                                <select class="form-control select2" id="selected_product" onchange="check_if_product_already_selected();">
                                    <option></option>
                                    @foreach($products->where('is_misc',0) as $product)
                                        <option value="{{$product->name}}|{{$product->price}}|{{$product->paella_price}}|{{$product->id}}|{{$product->production_item}}|{{$product->is_misc}}">{{$product->name}} - {{number_format($product->price,2)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group d-flex justify-content-end mg-t-25">
                                <label class="d-block">&nbsp;</label>
                                <button type="button" class="btn btn-info btn-sm" onclick="add_more_product();">+</button>
                            </div>
                        </div>
                    </div>

                    <div id="products"></div>

                    <div class="justify-content-between mg-b-5 text-right">
                    <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addmiscellaneous">Add miscellaneous</a>
                    </div>
                    
                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="d-block">Delivery/Pickup Date & Time <i class="text-danger">*</i></label>
                                <input required type="text" name="delivery_date" class="form-control" placeholder="Choose date" id="date1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">&nbsp;</label>
                                <div class="input-group timepicker">
                                    <select required class="selectpicker" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select delivery time" data-width="100%" name="delivery_time" id="delivery_time" onchange="check_time($(this).val());">                                       
                                        <option value="05:00">05:00 AM</option>
                                        <option value="06:00">06:00 AM</option>
                                        <option value="07:00">07:00 AM</option>
                                        <option value="08:00">08:00 AM</option>
                                        <option value="09:00">09:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00">12:00 NN</option>
                                        <option value="13:00">01:00 PM</option>
                                        <option value="14:00">02:00 PM</option>
                                        <option value="15:00">03:00 PM</option>
                                        <option value="16:00">04:00 PM</option>
                                        <option value="17:00">05:00 PM</option>
                                        <option value="18:00">06:00 PM</option>
                                        <option value="19:00">07:00 PM</option>
                                        <option value="20:00">08:00 PM</option>
                                        <option value="21:00">09:00 PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Order Type</label>
                        <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select order type" data-width="100%" name="order_type">
                            <option value="Whole">Whole</option>
                            <option value="Reserved">Reserved</option>
                            <option value="Additional">Additional</option>
                        </select>
                    </div>

                    <div class="delivery-pickup-place">
                        <div class="form-group">
                            <label class="d-block">Delivery Type <span class="tx-danger">*</span></label>
                            <select required class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select delivery type" data-width="100%" id="delivery_type" name="delivery_type">
                                <option value=""> - Select -</option>
                                <option value="1">Door to door</option>
                                <option value="2">Pick-up at store</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="delivery_branch_div" style="display: none;">
                            <label class="d-block">Branch to Deliver <span class="tx-danger">*</span></label>
                            <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select branch to deliver" data-width="100%" id="delivery_branch" name="delivery_branch">
                                <option value="">- Select Branch -</option>
                                @php 
                                    $name='delivery';
                                    $brr = $branches_store->filter(function ($item) use($name){
                                                 return false !== stristr($item->name, $name);
                                            })
                                @endphp
                                @foreach($brr as $b)
                                    <option value="{{$b->name}}">{{$b->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group" id="outlet_div" style="display: none;">
                            <label class="d-block">Outlet <span class="tx-danger">*</span></label>
                            <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select delivery/pick-up details" data-width="100%" id="outlet_rate" name="outlet_pickup">
                                <option value="">- Select Branch -</option>
                                @php 
                                    $name='delivery';
                                    $prr = $branches_store->where('pickup_branch','1')->filter(function ($item) use($name){
                                                 return false === stristr($item->name, $name);
                                            })
                                @endphp
                                @foreach($prr as $b)
                                    <option value="{{$b->name}}|0">{{$b->name}}</option>
                                @endforeach
                               
                            </select>
                        </div>

                        <div class="form-group" id="d2d_div" style="display: none;">
                            <label class="d-block">Delivery Address <span class="tx-danger">*</span></label>
                           <textarea name="add_ress" class="form-control" id="add_ress" cols="30" rows="4" onchange="set_complete_address()"></textarea>
                        </div>
                        <div class="form-group" id="loc_div" style="display: none;">
                            <label class="d-block">Location/Area <span class="tx-danger">*</span></label>
                            <select class="selectpicker mg-b-5 outlet_location" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select delivery/pick-up details" data-width="100%" id="outlet_rate" name="outlet_d2d">
                                @foreach($branches as $b)
                                <option value="{{$b->name}}|{{$b->rate}}">{{$b->name}}</option>
                                @endforeach
                               <option value="Other|0">Other</option>
                            </select>
                        </div>
                        <div id="complete_address"></div>


                        
                        <div class="form-group" id="other_outlet_div" style="display: none;" onkeyup="set_complete_address()">
                            <input type="text" class="form-control" name="other_outlet" id="other_outlet" placeholder="Please enter location name">    
                        </div>

                        <div class="form-group">
                            <label class="d-block">Delivery Charge <span class="tx-danger">*</span></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Php</div>
                                </div>
                                <input type="number" name="delivery_charge" class="form-control text-right" id="set_delivery_charge" value="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Remarks </label>
                        <textarea name="instruction" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10">
                                <label class="d-block">Coupon Code:</label>
                                <input type="text" class="form-control" name="coupon" id="coupon" placeholder="Enter Code Here">
                            </div>
                            <div class="col-md-2">
                                <label class="d-block">&nbsp;</label>
                                <a class="btn btn-success btn-sm" style="color:white;" id="verify_btn" onclick="verify_coupon();">Apply</a>
                                <input type="hidden" name="totalcoupon" id="totalcoupon" value="0">
                            </div>
                        </div>                        
                    </div>
                    
                    <table class="table table-stripped">
                        <thead>
                            <th>Coupon</th>
                            <th>Discount</th>
                            <th></th>
                        </thead>
                        <tbody id="coupons_tbl">
                        </tbody>
                    </table>
                    <br>

                    <div class="form-group">
                        <label class="d-block">Agent Code</label>
                        <input type="text" class="form-control" name="agent" id="agent" readonly="readonly" value="{{Auth::user()->name}}">
                    </div>
                    
                    <div class="jo_payment">
                       {{--  <div class="form-group">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline1" name="payment" checked="checked" class="custom-control-input" value="Regular Payment">
                                <label class="custom-control-label" for="customRadioInline1">Regular Payment</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline2" name="payment" class="custom-control-input" value="No Payment Required">
                                <label class="custom-control-label" for="customRadioInline2">No Payment Required</label>
                            </div>
                        </div> --}}
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th style="width:25%;">Payment Method</th>
                                    <th style="width:25%;">Amount</th>
                                    <th style="width:40%;">Remarks & Attachment</th>
                                    <th style="width:10%;">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 10; $i++)
                                    <tr style="{{$i == 1 ? '' : 'display:none'}};" class="payment_tr" id="payment_tr{{$i}}">                                        
                                        <td valign="top">
                                            <select name="payment_method{{$i}}" id="payment_method{{$i}}" class="form-control payments_created" onchange="check_payment_type($(this).val(),{{$i}})">
                                                <option value="">-Select -</option> 
                                                <option value="Bank Deposit">Bank Deposit</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Check Payment">Check Payment</option>
                                                <option value="COD">COD</option>
                                                <option value="Credit/Debit Card">Credit/Debit Card</option>
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
                                        </td>
                                        <td valign="top"><input type="number" class="form-control payments" min="0" name="payment_amount{{$i}}" id="payment_amount{{$i}}" value="0"></td>
                                        <td valign="top">
                                            <textarea name="payment_remarks{{$i}}" id="payment_remarks{{$i}}" class="form-control" rows="1" 
                                            placeholder="Enter payment details here"></textarea>
                                            <br>
                                            <input type="file" name="payment_file{{$i}}" id="payment_file{{$i}}">
                                        </td>
                                       
                                        <td valign="top"><a href="javascript:void(0);" title="remove payment" class="btn btn-xs btn-warning" onclick="remove_payment({{$i}});">x</a></td>
                                    </tr>

                                @endfor                              
                            </tbody>
                        </table>
                        <table width="100%">
                            <tr>
                                <td style="width:25%"><a href="javascript:void(0);" onclick="add_more_payment();" class="btn btn-sm btn-success">Add more</a></td>
                                <td style="width:75%"><span id="payment_total"></span></td>
                            </tr>
                        </table>
                        

                        {{-- 
                        <div class="form-group"> 
                            <label class="d-block">Payment Method</label>
                            <select name="payment_method" id="payment_method" required="required" class="form-control" onchange="check_payment_type($(this).val())">
                                <option value="">-Select -</option>
                                <option value="Deposit">Deposit</option>
                                <option value="Order Online">Order Online</option>
                                <option value="COD">COD</option>
                                <option value="Ok Order">Ok Order</option>
                                <option value="Gift Check">Gift Check</option>
                                <option value="Ex-deal">Ex-deal</option>
                                <option value="Bank Deposit">Bank Deposit</option>
                                <option value="Oth">Oth</option>
                                <option value="Sign-Chit">Sign-Chit</option>
                                <option value="Check Payment">Check Payment</option>
                                <option value="Fully Paid">Fully Paid</option>
                            </select>
                        </div>
                        <div class="form-group" id="payment_div">
                            <label class="d-block">Amount</label>
                            <input type="number" class="form-control" name="payment_amount" id="payment_amount" value="0">
                        </div>
                        <div class="form-group">
                            <label class="d-block">Payment Remarks</label>
                            <textarea name="payment_remarks" class="form-control" rows="1" placeholder="Enter payment details here"></textarea>
                        </div> --}}
                    </div>



                    
                    
              
                </form>
            </div>

            <div class="col-lg-12 mg-t-20 mg-b-30">
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save Job Order</button>
                <a  href="{{route('joborders.index')}}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
            </div>
        </div>
    </form>
</div>

<div class="modal effect-scale" id="prompt-product-validation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="prompt_msg"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('admin.joborder.modal')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('lib/prismjs/prism.js') }}"></script>
    <script src="{{ asset('lib/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('lib/typeahead.js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

    <script src="{{ asset('lib/datextime/moment.min.js') }}"></script>
    <script src="{{ asset('lib/datextime/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
       
        function set_complete_address(){
            if($('#delivery_type').val() == 1){
                $('#complete_address').html('Complete Address: '+ $('#add_ress').val() +', '+ $('.outlet_location option:selected').text()+' '+$('#other_outlet').val());
            }
            else{
                $('#complete_address').html('');
            }
        }
        function remove_payment(i){
            $('#payment_remarks'+i).val('');
            $('#payment_amount'+i).val(0);
            $('#payment_method'+i).prop('selectedIndex',0);
            $('#payment_tr'+i).hide();
        }
        function add_more_payment(){
            var totalRows = $('.payment_tr').filter(function() {
                  return $(this).css('display') !== 'none';
                }).length;
            totalRows++;
            $('#payment_tr'+totalRows).show();
     
        }
        $(".payments").keyup(function(){
          $('#payment_total').html('Total: '+FormatAmount(get_payment_total(),2));
        });
        function check_payment_type(x,i){
            if(x == 'COD'){

                $('#payment_amount'+i).val(get_payment_balance());
                $('#payment_amount'+i).prop('readonly', true);

            }
            else{
                $('#payment_amount'+i).prop('readonly', false);
            }
            $('#payment_total').html('Total: '+FormatAmount(get_payment_total(),2));
        }
        function get_payment_balance(){
            var amt = 0;
            for(i=1;i<=10;i++){
                amt+=parseFloat($('#payment_amount'+i).val());
            }
            
            return (parseFloat($('#summary_input_gross').val()) - parseFloat(amt));
        }
        function get_payment_total(){
            var amt = 0;
            for(i=1;i<=10;i++){
                amt+=parseFloat($('#payment_amount'+i).val());
            }            
            return amt;
        }
        
        var x = 0;
        var arr_selected_coupons = [];
        function verify_coupon(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            var cpn = $('#coupon').val();
            if(jQuery.inArray(parseInt(cpn), arr_selected_coupons) !== -1){
                alert('Coupon code already applied!');
                return false;
            }

            $.ajax({
                data: {coupon: $('#coupon').val()},
                type: "post",
                url: "{{route('joborder.check_coupon')}}",            
                success: function(returnData) {   
                    $('#totalcoupon').val(1); 
                    x++;
                    
                    if(returnData['success'] == 1) {
                        arr_selected_coupons.push(parseInt(returnData['code']));
                    
                        $('#tbod').append('<tr id="on_screen_cpn'+x+'">'+
                                '<th scope="row"><strong>Coupon ('+returnData['code']+') :</strong></th>'+
                                '<td>'+returnData['rate']+'</td>'+
                                '<input type="hidden" class="coupon_discount" id="coupon'+x+'" name="coupondiscount[]" value="'+returnData['rate2']+'"><input type="hidden" id="couponcode" name="couponcode[]" value="'+returnData['code']+'">'+
                            '</tr>');
                        $('#coupon').val('');
                       calculate_sub_total();

                       $('#coupons_tbl').append('<tr id="cpn'+x+'">'+
                            '<td>Coupon #'+returnData['code']+'</td>'+
                            '<td>'+returnData['rate']+'</td>'+
                            '<td class="text-right">'+
                                '<button type="button" id="'+x+'" class="btn btn-danger btn-sm cremove">X</button>'+
                            '</td>'+
                        '</tr>');
                        
                    }
                    else{
                        alert('Invalid Coupon code!');
                    }
                }
                
            });
        }
        
        $(document).on('click', '.cremove', function(){
            var id = $(this).attr("id");
            $('#cpn'+id).remove();
            $('#on_screen_cpn'+id).remove();
            
            calculate_grand_total();
        });
    </script>
    <script>
    /** page level plugins **/
        $('.select2').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search options'
        });

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });
        
        $(function() {
            $('.selectpicker').selectpicker();
        });

        $(document).ready(function() {
            $('#customer_type_div').show();
            $('input[type="radio"]').click(function() {
                var inputValue = $(this).attr("value");
                var targetBox = $("." + inputValue);
                $(".box").not(targetBox).hide();
                $(targetBox).show();
            });
        });

        var dateToday = new Date(); 

        $(function(){
            'use strict'

            $('#date1').datepicker({
                minDate: dateToday,
                dateFormat: 'yy-mm-dd',
            });
        });
    /** page level plugins **/
    </script>

    <script>
    /** form validations **/
        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#mobile").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });   

        $(document).on('change','#cs-exist',function(){
            if($('#cs-exist').is(':checked')) {
                $('.cnew_details :input').prop('required',false);
            }
        });

        $(document).on('change','#cs-new',function(){
            if($('#cs-new').is(':checked')) {
                //$('.cnew_details :input').prop('required',true);
            }
        });

        function check_time(time){
            var arr_time = [ '23:00','01:00','02:00','03:00','04:00','05:00'];

            if(time == '23:00'){
                t = '11:00 PM';
            } else {
                t = time+' AM';
            }
            // if($.inArray(time,arr_time) != -1){
            //     $('#prompt-product-validation').modal('show');
            //     $('#exampleModalCenterTitle').html('REMINDER')
            //     $('#prompt_msg').html("The time you have selected is not within the delivery hours of our stores. Are you sure you want to deliver your order at "+t+" ?");

            //     $("#delivery_time").val('').trigger('change');
            // } 

            var v = $('#date1').val()+' - '+$('#delivery_time').val();  
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                data: {dateneeded: v, has_lechon: $('#has_lechon').val()},
                type: "post",
                url: "{{route('joborder.check_dateneeded')}}",            
                success: function(returnData) {   
                    if(returnData['remark'] > 0) {
                        $('#prompt-product-validation').modal('show');
                        $('#exampleModalCenterTitle').html('REMINDER')
                        $('#prompt_msg').html(returnData['err']);
                    }
                    else{
                        // $('#daten_warning').html('');
                        // $('#daten_warning_div').hide();
                        // $('#error_id').val('0');
                    }
                }
                
            });
        }
    /** form validations **/

    /** functions and validation of selecting products **/
        var n = 0;
        function add_more_product(){
            n++;
            var selected_product = $('#selected_product').val();
            var product = selected_product.split('|');
            if(product[5] == '0'){
                $('#has_lechon').val('1');
            }
            if(product[3] == '42'){
                $('#has_lechon').val('2');
            }
            if(selected_product.length > 0){
                if(product[2] > 0){

                    $('#products').append('<div style="padding-bottom:0px;" class="row" id="row'+n+'">'+
                        '<div class="col-md-8">'+
                            '<div class="form-group">'+
                                '<label class="d-block">Product <span class="tx-danger">*</span></label>'+
                                '<input type="hidden" class="form-control hproducts" name="product_id[]" value="'+product[3]+'" readonly>'+
                                '<input type="text" class="form-control" value="'+product[0]+'" readonly>'+
                                '<div class="custom-control custom-checkbox mg-t-15 d-flex justify-content-end">'+
                                    '<input type="checkbox" onclick="add_paella('+n+','+product[1]+','+product[2]+');" class="custom-control-input cb" id="cb'+n+'">'+
                                    '<label class="custom-control-label" for="cb'+n+'">Include Paella</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2 mg-t-10">'+
                            '<div class="form-group">'+
                                '<label class="d-block">Quantity <span class="tx-danger">*</span></label>'+
                                '<input type="number" name="order_qty[]" min="1" value="1" id="qty_'+n+'" onchange="qty_change('+n+','+product[1]+','+product[2]+');" class="form-control" required>'+
                                '<input type="number" name="paella_qty[]" min="1" value="0" id="paella_qty'+n+'" onchange="paella_qty_change('+n+','+product[1]+','+product[2]+');" class="form-control mg-t-10" readonly>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<div class="form-group d-flex justify-content-end">'+
                                '<label class="d-block">&nbsp;</label>'+
                                '<button type="button" id="'+n+'" class="btn btn-danger btn-sm remove">X</button>'+
                                '<span>&nbsp;</span>'+
                            '</div>'+
                        '</div>'+
                    '</div>');
                } else {
                    $('#products').append('<div style="padding-bottom:0px;" class="row" id="row'+n+'">'+
                        '<div class="col-md-8">'+
                            '<div class="form-group">'+
                                '<label class="d-block">Product <span class="tx-danger">*</span></label>'+
                                '<input type="hidden" class="form-control hproducts" name="product_id[]" value="'+product[3]+'" readonly>'+
                                '<input type="text" class="form-control" value="'+product[0]+'" readonly>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<div class="form-group">'+
                                '<label class="d-block">Quantity <span class="tx-danger">*</span></label>'+
                                '<input type="number" name="order_qty[]" min="1" value="1" id="qty_'+n+'" onchange="qty_change('+n+','+product[1]+','+product[2]+');" class="form-control" required>'+
                                '<input type="hidden" name="paella_qty[]" value="0" class="form-control mg-t-10">'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<div class="form-group d-flex justify-content-end mg-t-25">'+
                                '<label class="d-block">&nbsp;</label>'+
                                '<button type="button" id="'+n+'" class="btn btn-danger btn-sm remove">X</button>'+
                            '</div>'+
                        '</div>'+
                    '</div>');
                }
                
                $('#order_summary').append('<tr id="order_row'+n+'">'+
                    '<td>'+product[0]+'</td>'+
                    '<td class="text-right">'+FormatAmount(product[1],2)+'</td>'+
                    '<td class="order_qty'+n+' text-center">1</td>'+
                    '<td class="order_total_price'+n+' text-right">PHP '+FormatAmount(product[1],2)+'</td>'+
                    '<td style="display:none;"><input type="text" class="input-total-qty-per-product" id="product_total_qty_per_product'+n+'" value="1"/></td>'+
                    '<td style="display:none;"><input type="text" class="input-total-price-per-product" id="product_total_price'+n+'" value="'+product[1]+'"/></td>'+
                '</tr>'+
                '<tr>'+
                    '<td colspan="4">'+
                        '<table class="table table-borderless table-xs">'+
                            '<tbody id="paella_row'+n+'"></tbody>'+
                        '</table>'+
                    '</td>'+
                '</tr>');

                $("#selected_product").val('').trigger('change');
                calculate_sub_total();

            } else {
                $('#prompt-already-selected').modal('show');
            }
        }

        $(document).on('click', '.remove', function(){
            var btn_id = $(this).attr("id");
            $('#row'+btn_id+'').remove();
            $('#order_row'+btn_id+'').remove();
            $('#paella'+btn_id+'').remove();

            calculate_sub_total();
            calculate_grand_total();
        });

        function check_if_product_already_selected() {
            var a = $('#selected_product').val();
            var x = a.split('|');

            var arr = [];
            $("input[name='product[]']").each(function(){
                var value = $(this).val();
                arr.push(value);

                if(arr.indexOf(x[0]) < 0){
                    arr.push(value);
                } else {
                    $('#prompt-product-validation').modal('show');
                    $('#prompt_msg').html('Selected product is already in the list.');

                    $("#selected_product").val('').trigger('change');
                }
            });
        }
    /** functions and validation of selecting products **/

    /** miscellaneous functions **/

        function add_misc_product(){
            var mics_product = $('#misc_products').val();

            var product = mics_product.split('|');

            $('#product_id').val(product[2]);
            $('#product_name').val(product[0]);
            $('#product_price').val(product[1]);
            $('#total_misc_price').html(FormatAmount(product[1],2));

            var arr = [];
            $("input[name='misc[]']").each(function(){
                var value = $(this).val();
                arr.push(value);

                if(arr.indexOf(product[0]) < 0){
                    arr.push(value);
                    $('#misc_msg').hide();
                    $('#misc_btn_add').prop('disabled',false);
                } else {
                    $('#misc_msg').show();
                    $('#misc_btn_add').prop('disabled',true);
                }
            });

        }

        function misc_qty(){
            var qty = $('#misc_qty').val();
            var price = $('#product_price').val();

            var total_misc_price = parseInt(price)*qty;
            $('#total_misc_price').html(FormatAmount(total_misc_price,2));
        }

        var m = 0;
        function  add_to_screen_computation() {
            m++;

            var old_total_misc = $('#total_misc').val();
            var total_added_misc = parseInt(old_total_misc)+1;

            $('#total_misc').val(total_added_misc);

            var id   = $('#product_id').val();
            var name = $('#product_name').val();
            var price= $('#product_price').val();
            var qty  = $('#misc_qty').val();
            var total = parseInt(price)*qty;

            $('#misc_summary').append('<tr id="misc_row'+m+'">'+
                '<td style="display:none;"><input type="text" name="misc[]" value="'+name+'"/></td>'+
                '<td>'+name+'</td>'+
                '<td>'+FormatAmount(price,2)+'</td>'+
                '<td><input type="number" name="mq" id="mq'+m+'" min="1" value="'+qty+'" onchange="change_qty('+m+')" style="width:50px;" maxlength="4" size="4"/></td>'+
                '<td class="text-right" id="itemtotal'+m+'">PHP '+FormatAmount(total,2)+'</td>'+
                '<td style="display:none;" class="text-right"><input type="text" id="miscqty'+m+'" name="misc_qty[]" value="'+qty+'" /></td>'+
                '<td style="display:none;" class="text-right"><input type="text" name="misc_id[]" value="'+id+'" /><input type="text" id="pryc'+m+'" value="'+price+'" /></td>'+
                '<td class="text-right"><a href="javascript:;" id="'+m+'" class="remove_misc">x</a></td>'+
                '<td style="display:none;"><input type="text" class="input-total-qty-per-misc-product miscqty'+m+'" id="product_total_qty_per_misc_product'+m+'" value="'+qty+'"/></td>'+
                '<td style="display:none;"><input type="text" class="input-total-price-per-misc-product" id="product_total_misc_price'+m+'" value="'+total.toFixed(2)+'"/></td>'+
            '</tr>');

            $('#addmiscellaneous').modal('hide');
            $('#product_price').val(0);
            $('#misc_products').val('');
            $('#misc_qty').val(1);

            total_price_misc();
            calculate_sub_total();

            $("#misc_products").val('').trigger('change');
            $('#total_misc_price').html('0.00');

        } 

        function change_qty(m){
            var p = parseFloat($('#pryc'+m).val()) * parseInt($('#mq'+m).val());

            $('#product_total_qty_per_misc_product'+m).val($('#mq'+m).val());
            $('#product_total_misc_price'+m).val(p);
            $('#itemtotal'+m).html('PHP '+FormatAmount(p,2));
            $('.miscqty'+m).val($('#mq'+m).val());

            total_price_misc();
            calculate_sub_total();
        }


        $(document).on('click', '.remove_misc', function(){
            var btn_id = $(this).attr("id");
            var old_total_misc = $('#total_misc').val();
            var total_misc = parseInt(old_total_misc)-1;

            $('#total_misc').val(total_misc);
            $('#misc_row'+btn_id+'').remove();

            total_price_misc();
            calculate_sub_total();
        });

        function total_price_misc(){
            var total_misc = 0;
            
            $(".input-total-price-per-misc-product").each(function() {
                //add only if the value is number
                if(!isNaN(this.value) && this.value.length!=0) {
                    total_misc += parseFloat(this.value);
                }
            });

            $('#summary_total_misc').html('PHP '+FormatAmount(total_misc,2));
            $('#input_total_misc').val(total_misc.toFixed(2));

            calculate_grand_total();
        }
    /** miscellaneous functions **/

    /** On screen computation functions **/

        function qty_change(id,price){

            $('#paella'+id).remove();

            $('#cb'+id).prop('checked',false);
            $('#paella_qty'+id).val(0);
            $('#paella_qty'+id).prop('readonly',true);
            $('#paella_qty'+id).prop('required',false);

            var qty = $('#qty_'+id).val();
        
            update_total_price_per_product(id,price,qty);
            calculate_sub_total();

        }

        function add_paella(n,product_price,paella_price){
            var qty = $('#qty_'+n).val();
            var pprice = paella_price*qty;

            if($('#cb'+n).prop("checked") == true){

                $('#paella_row'+n).append('<tr id="paella'+n+'">'+
                        '<td width="38%">Add On: Paella</td>'+
                        '<td width="20%" class="text-right">'+FormatAmount(paella_price,2)+'</td>'+
                        '<td width="17%" class="text-center" id="paellaqty'+n+'">'+qty+'</td>'+
                        '<td width="25%" class="text-right" id="paellaprice'+n+'">PHP '+FormatAmount(pprice,2)+'</td>'+
                        '<td style="display:none;"><input type="text" class="input_paella_qty" id="paellaqty_'+n+'" value="'+qty+'" /></td>'+
                        '<td style="display:none;"><input type="text" class="input_paella_price" id="paella_'+n+'" value="'+pprice+'" /></td>'+
                    '</tr>'
                );

                $('#paella_qty'+n).val(qty);
                $("#paella_qty"+n).attr({"max" : qty});
                $('#paella_qty'+n).prop('readonly',false);
                $('#paella_qty'+n).prop('required',true);


            }
            else if($('#cb'+n).prop("checked") == false){

                $('#paella'+n).remove();

                $('#paella_qty'+n).val(0);
                $('#paella_qty'+n).prop('readonly',true);
                $('#paella_qty'+n).prop('required',false);

            }

            calculate_sub_total();
        }

        function paella_qty_change(n,product_price,paella_price){
            var qty = $('#paella_qty'+n).val();
            var qty1 = $('#qty_'+n).val();

            var product_total_price = parseInt(product_price)*qty1;
            var total_paella_price  = parseInt(paella_price)*qty;


            var total = parseInt(product_total_price)+total_paella_price;

            $('#paella_'+n).val(total_paella_price);
            $('#paellaqty'+n).html(qty);
            $('#paellaqty_'+n).val(qty);
            $('#paellaprice'+n).html('PHP '+FormatAmount(total_paella_price,2));
            //total_price_per_product_qty_and_paella(n,total);

            calculate_sub_total();
        }

        function total_price_per_product_qty_and_paella(n,total){

            $('.order_total_price'+n).html('PHP '+FormatAmount(total,2));
            $('#product_total_price'+n).val(total.toFixed(2));

            calculate_sub_total();

        }

        function update_total_price_per_product(n,price,qty){
            var total_price = parseInt(price)*qty;

            $('.order_qty'+n).html(qty);
            $('.order_total_price'+n).html('PHP '+FormatAmount(total_price,2));
            $('#product_total_price'+n).val(total_price.toFixed(2));

            $('#product_total_qty_per_product'+n).val(qty);
        }

        function calculate_sub_total()
        {
            var price_per_paella = 0;
            $(".input_paella_price").each(function() {
                //add only if the value is number
                if(!isNaN(this.value) && this.value.length!=0) {
                    price_per_paella += parseFloat(this.value);
                }
            });

            var price_per_product = 0;
            $(".input-total-price-per-product").each(function() {
                //add only if the value is number
                if(!isNaN(this.value) && this.value.length!=0) {
                    price_per_product += parseFloat(this.value);
                }
            });

            var total_prod_qty = 0;
            $(".input-total-qty-per-product").each(function() {
                //add only if the value is number
                if(!isNaN(this.value) && this.value.length!=0) {
                    total_prod_qty += parseFloat(this.value);
                }
            });

            total_misc_qty = 0;
            $(".input-total-qty-per-misc-product").each(function() {
                //add only if the value is number
                if(!isNaN(this.value) && this.value.length!=0) {
                    total_misc_qty += parseFloat(this.value);
                }
            });

            total_paella_qty = 0;
            $(".input_paella_qty").each(function() {
                //add only if the value is number
                if(!isNaN(this.value) && this.value.length!=0) {
                    total_paella_qty += parseFloat(this.value);
                }
            });
            
          
               
           
            


            var subtotal = price_per_product+price_per_paella;

            $("#subtotal").html('PHP '+FormatAmount(subtotal,2));
            $("#totalQty").html(total_prod_qty+total_misc_qty+total_paella_qty);
            $('#input_subtotal').val(subtotal.toFixed(2));

            calculate_grand_total();
        }

        function calculate_grand_total(){
            var total_coupon = 0;
            $(".coupon_discount").each(function() {
                if(!isNaN(this.value) && this.value.length!=0) {
                    total_coupon += parseFloat(this.value);
                }
            });
            
            // if($("#coupon1").val()) {
            
            //     total_coupon = parseFloat($("#coupon1").val());
            // }
            var subtotal = $('#input_subtotal').val();
            var delivery_charge = $('#input_delivery_charge').val();
            var total_misc = $('#input_total_misc').val();

            var grand_total = (parseInt(subtotal)+parseInt(delivery_charge)+parseInt(total_misc)) - parseFloat(total_coupon);

            $('#summary_input_gross').val(grand_total.toFixed(2));
            $('#grand_total').html('PHP '+FormatAmount(grand_total,2));
        }

        function FormatAmount(number, numberOfDigits) {

            var amount = parseFloat(number).toFixed(numberOfDigits);
            var num_parts = amount.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return num_parts.join(".");

        }
    /** On screen computation functions **/
    </script>

    <script>
    /** search customer and display customer details once selected **/
        $(document).ready(function(){
            var customers = {!! \App\Models\User::customer_lookup() !!}

            var customers = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local : customers,
                sufficient: 20
            });


            $('#input2').tagsinput({
                maxTags: 1,
                typeaheadjs: ({
                    sufficient: 20,
                    hint: true,
                    highlight: true,
                    minLength: 1
                },
                {
                    name : 'customers',
                    source: customers,
                    limit: 20
                })
            });
        });

        function view_customer_details(name)
        {
            if(name.length > 0){
                $.ajax({
                    type: "GET",
                    url: "{{ route('display-customer-details') }}",
                    data: { name : name },
                    success: function( response ) {
                        $('#customer_details').show();
                        $('#customer_details').html(response); 
                        $('#add_ress').val($('#customer_address').val());
                    }
                });
            } else {
                $('#customer_details').hide();
            }
        }
    /** search customer and display customer details once selected **/
    </script>

    <script>
    /** delivery functions **/

        $(document).on('keyup','#set_delivery_charge', function(){
            var rate = $(this).val();

            $('#summary_delivery_charge').html(FormatAmount(rate,2));
            $('#input_delivery_charge').val(rate);

            calculate_grand_total();
        });

        $(document).on('change', '#delivery_type', function(){
            var type = $(this).val();

            if(type == 1){
                $('#set_delivery_charge').val(0);
                $('#set_delivery_charge').prop('readonly',true);
                $('#delivery_branch').prop('required',true);
                $('#delivery_branch_div').show();
                $('#outlet_div').hide();
                $('#d2d_div').show();
                $('#loc_div').show();
            }

            if(type == 2){
                $('#delivery_branch_div').hide();
                $('#outlet_div').show();
                $('#d2d_div').hide();
                $('#loc_div').hide();
                
                $('#other_outlet_div').hide();
                $('#other_outlet').prop('required',false);

                $('#set_delivery_charge').val(0);
                $('#outlet_rate').prop('required',false);
                $('#set_delivery_charge').prop('readonly',true);

                $('#summary_delivery_charge').html('0.00');
                $('#input_delivery_charge').val(0);
            }

            calculate_grand_total();

        });

        $(document).on('change', '#outlet_rate', function(){
            var v = $(this).val();
            var x = v.split('|');

            if(x[0] == 'Other'){
                $('#set_delivery_charge').val(0);
                //$('#prompt-product-validation').modal('show');
                //$('#exampleModalCenterTitle').html('Info');
                //$('#prompt_msg').html('Selecting this option means you need to manually input the location name and the delivery charge.');
                $('#set_delivery_charge').prop('readonly',false);

                $('#other_outlet_div').show();
                $('#other_outlet').prop('required',true);
            } else {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    data: { location: x[0], has_lechon: $('#has_lechon').val() },
                    type: "post",
                    url: "{{route('cart.joborder.get_shipping_fee')}}",                
                    success: function(returnData) {
                            $('#set_delivery_charge').val(parseFloat(returnData['fee']).toFixed(2));
                            $('#input_delivery_charge').val(parseFloat(returnData['fee']).toFixed(2));
                            $('#set_delivery_charge').prop('readonly',true);         
                            $('#other_outlet_div').hide();
                            $('#other_outlet').prop('required',false);
                            $('#summary_delivery_charge').html(parseFloat(returnData['fee']).toFixed(2));
                            calculate_grand_total();
                    },
                    failed: function() {
                            $('#set_delivery_charge').val(0);
                            $('#input_delivery_charge').val(0);
                            $('#set_delivery_charge').prop('readonly',true);         
                            $('#other_outlet_div').hide();
                            $('#other_outlet').prop('required',false);
                            $('#summary_delivery_charge').html(0.00);  
                            calculate_grand_total();
                    }
                   
                });

                // $('#set_delivery_charge').val(x[1]);
                // $('#set_delivery_charge').prop('readonly',true);

                // $('#other_outlet_div').hide();
                // $('#other_outlet').prop('required',false);

                // $('#summary_delivery_charge').html(FormatAmount(x[1],2));
                // $('#input_delivery_charge').val(x[1]);
                 set_complete_address();

            }

            calculate_grand_total();
        });
    /** delivery functions **/
    </script>

    <script>
        $("#joborder_form").submit(function(e){
            
            var count = $('.hproducts').length;
            var misce = parseInt($('#total_misc').val());
            var tt = parseInt(count) + parseInt(misce);
      
            if(tt <= 0){
                alert('Please add atleast 1 item');
                 e.preventDefault();
            }

            if(parseFloat(get_payment_balance()) < 0){
                alert('Your payment/s exceeded the total billing amount!');
                 e.preventDefault();
            }

            if(parseFloat(get_payment_total())  < 1){
                alert('Please add payment details');
                 e.preventDefault();
            }

            if($('#delivery_type').val() == 2){
                if(!$("select[name=outlet_pickup]").val()){
                    alert('Please select Pickup Outlet');
                    e.preventDefault();
                }
            }


            var req_attached = ['Bank Deposit','Check Payment','Gcash','Online Bank Transfer', 'Cash', 'Credit/Debit Card', 'Gift Certificate'];
            var no_attached = 0;
            $('.payments_created').each(function(i, obj) {
                if($.inArray($(this).val(), req_attached) !== -1){
                    var idd = ($(this).attr('id')).substring(14);
                    console.log("idd: "+idd);
                    if ($('#payment_file'+idd).get(0).files.length === 0) {
                        no_attached++;
                    }
                }
            });
            if(no_attached > 0){
                alert('Attachment is required for payment types: Bank Deposit, Check Payment , Gcash, Cash, Credit/Debit Card, Gift Certificate, or Online Bank Transfer');
                e.preventDefault();
            }            
            
          
        });
     
    </script>

@endsection
