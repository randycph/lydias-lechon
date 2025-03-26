@extends('theme.'.config('app.frontend_template').'.ecommerce.kiosk.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('lib/datetime/css/bootstrap-datetimepicker.min.css') }}" />
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
        <form action="{{route('kiosk.temp_sales')}}" method="post" id="chk_form" autocomplete="off">
            @csrf
            <div class="content-wrapper">
                <div class="gap-70"></div>
                <div class="container">
                    <div class="row">
                        <div class="gap-70"></div>
                        <div class="col-md-6" style="font-family:sans-serif, arial;">                
                            <h2 id="header_label">@if (session('delivery_option') == 'delivery') Delivery @else Customer @endif Information</h2>

                            <div class="form-group">
                                <label for="uname1" class="control-label">Customer Name *</label>
                                <input type="text" class="form-control" name="uname1" id="uname1" required>
                            </div>

                            <div class="form-group">
                                <label for="email" class="control-label">Email *</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>

                            <div class="form-group">
                                <label for="mobile" class="control-label">Contact Number *</label>
                                <input type="number" class="form-control" name="mobile" id="mobile" required>
                            </div>

                            <div class="form-group">
                                <label for="cperson" class="control-label">Contact Person *</label>
                                <input type="text" class="form-control" name="cperson" id="cperson" required>
                            </div>

                            <div class="form-group">
                                <label for="dtp_input1" class="control-label">Date and Time Needed *</label>
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
                                <input type="hidden" id="dtp_input1" value="" />
                                <input type="hidden" id="error_id" value="0" /> 

                                <div class="alert alert-warning" role="alert" id="daten_warning_div" style="display:none;font-size:12px;margin-top:12px;">
                                    <h4 class="alert-heading">Warning!</h4>
                                    <ul class="text-danger" id="daten_warning"></ul>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="shipping_type" class="control-label" id="shipping_type_label">Delivery Type *</label>
                                <select name="shipping_type" id="shipping_type" class="form-control" required="required">
                                    <option value="d2d" @if (session('delivery_option') == 'delivery') selected="selected" @endif>Door to door</option>
                                    <option value="storepickup" @if (session('delivery_option') == 'pick-up') selected="selected" @endif>Pick up at this branch</option>
                                </select>
                            </div>

                            <div id="storepickup_div" @if(session('delivery_option') == 'delivery') style="display:none;" @endif>
                                <div class="form-group">
                                    <label for="uname1" class="control-label">Store Branch *</label>
                                    <input type="text" class="form-control" name="delivery_branch" value="{{ Cookie::get('branch') }}" readonly>
                                </div>
                            </div>

                            <div id="d2d_div" @if(session('delivery_option') == 'pick-up') style="display:none;" @endif>
                                <div class="gap-20"></div>          
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
                                    <textarea name="delivery_address" id="delivery_address" class="form-control" cols="30" rows="2"></textarea>
                                </div>
                                <span id="complete_delivery_address" style="text-transform:capitalize;"></span>                            
                            </div> 

                            <div class="gap-10"></div> 
                            <div class="form-group">
                                <label for="shipping_type" class="control-label">Other Instruction:</label>
                                <textarea name="instruction" id="instruction" class="form-control" cols="30" rows="2"></textarea>
                            </div>

                            <div class="form-group" style="display:none;">
                                <label for="shipping_type" class="control-label">Agent/Referrer:</label>
                                <input type="text" class="form-control" name="agent" id="agent" placeholder="Agent Code" value="{{Auth::user()->agent_code}}">
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
                                <a class="btn btn-info" href="javascript:void(0);" onclick="paying_now();">Submit</a>
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
    <script type="text/javascript" src="{{ asset('lib/datetime/js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('lib/datetime/js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
    <script>

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

        $('#delivery_address').keyup(function() {       
            combine_address();
        });

        $('#need_date').change(function(){
            check_disable_dates();
        });

        function check_disable_dates(){
            var disable_dates = ["2020-12-24","2020-12-25","2020-12-31"];
            if($('#shipping_type').val() == 'd2d'){
                if($.inArray($('#need_date').val(), disable_dates) > -1){
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
                $('#dateneeded').val($('#need_date').val()+' - '+$('#need_time').val());
                check_time();
            }
            
        }

        function check_time(){
            return true;
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
            if(parseFloat($('#minimum_order').val()) > parseFloat($('#total_amount').val())){
                alert('A minimum of Php '+$('#minimum_order').val()+' is required in order to checkout your order.');
                return false;
            }

            if($('#uname1').val()==''){
                alert('Please enter customer name.');
                return false;
            }

            if($('#email').val()==''){
                alert('Please enter email.');
                return false;
            }

            if($('#mobile').val()==''){
                alert('Please enter contact number.');
                return false;
            }

            if($('#cperson').val()==''){
                alert('Please enter contact person.');
                return false;
            }

            if($('#dateneeded').val()==''){
                alert('Please enter the date and time you need this order.');
                return false;
            }

            if($('#shipping_type').val()==''){
                alert('Please select delivery type!');
                return false;
            } else if($('#shipping_type').val()=='d2d'){
                // if(parseFloat(deposit50) > parseFloat($('#deposit').val()) ){
                //     alert('The required downpayment for door to door delivery is 50% of your total amount. Please pay atleast Php '+addCommas(parseFloat(deposit50).toFixed(2)));
                //     return false;
                // }
                if($('#location').val()==''){
                    alert('Please select your location.');
                    return false;
                }  
            } else if($('#shipping_type').val()=='storepickup'){
                if(parseFloat($('#total_amount').val()) > parseFloat($('#deposit').val()) ){
                    alert('We require full payment for your order. Please pay the amount Php '+addCommas(parseFloat($('#total_amount').val()).toFixed(2)));
                    return false;
                }

                if($('#delivery_branch').val()==''){
                    alert('Please select your preferred branch to pickup your order!');
                    return false;
                }
            }

            if($('#error_id').val()!='0'){
                alert("We are not able to accommodate your order base on your preferred date and time. Kindly refer to the warning message that appeared on your order screen.");
                return false;
            }

            $('#chk_form').submit();
        }

        $('#shipping_type').change(function(){
            check_disable_dates();
            var typ = $(this).val();
            if(typ == 'd2d'){
                $('#d2d_div').show();
                $('#storepickup_div').hide();
                $('#header_label').html('Delivery Information');
            }
            if(typ == 'storepickup'){
                $('#d2d_div').hide();
                $('#storepickup_div').show();
                $('#delivery_fee_div').html('0.00');
                $('#delivery_fee').val(0);
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
                    },
                    failed: function() {
                        $('#delivery_fee_div').html('0.00');  
                        $('#delivery_fee').val(0);
                        compute_total();
                    }
                   
                });         
            }

            compute_total();
            combine_address();
        });

        function compute_total(){
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
    </script>
@endsection
