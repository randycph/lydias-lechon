@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/legande/plugins/vanilla-zoom/vanilla-zoom.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/legande/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
@endsection

@section('content')
    <main>
        <section id="checkout-wrapper">
            <div class="container">
                <h3>Checkout</h3>
                <div class="checkout-info">
                    <div id="wizard1" class="wizard">
                        <ul class="wizard-steps" role="tablist">
                            <li class="active" role="tab">
                                <a href="">
                                    <span class="number">1</span>
                                    <span class="title">Billing Information</span>
                                </a>
                            </li>
                            <li role="tab">
                                <a href="">
                                    <span class="number">2</span>
                                    <span class="title">Shipping Options</span>
                                </a>
                            </li>
                            <li role="tab">
                                <a href="">
                                    <span class="number">3</span>
                                    <span class="title">Review and Place Order</span>
                                </a>
                            </li>
                        </ul>
                        <div class="wizard-content">
                            <div class="sponsor-wrapper wizard-pane active" role="tabpanel">
                                <h4>Billing Information</h4>
                                <hr>
                                <div class="gap-10"></div>
                                <div class="checkout-card selected" style="border: initial; padding: 0px;">
                                    <div class="edit-item">
                                        <a href="#" class="edit" onclick="$('#update_billing_form').show();">Edit</a>
                                    </div>
                                    <div class="unit flex-row unit-spacing-s">
                                        <div class="unit__body">
                                            <div class="checkout-content">
                                                <table class="customer-info">
                                                    <tr>
                                                        <td>Name:</td>
                                                        <td>{{Auth::user()->fullName}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>E-mail Address:</td>
                                                        <td>{{Auth::user()->email}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Contact Number:</td>
                                                        <td>{{$profile->mobile}} <br> {{$profile->phone}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Address:</td>
                                                        <td id="currentAddress">
                                                            {{$profile->memberAddress}}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout-card selected" id="update_billing_form" style="display:none;">
                                    <div class="unit flex-row unit-spacing-s">
                                        <div class="unit__body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="updateBillingAddressForm">
                                                        @csrf
                                                        <div id="updateAddressSuccess" class="alert alert-success" role="alert" style="display: none;">
                                                            <span class="fa fa-info-circle"></span> Delivery Address has been updated
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address Street *</label>
                                                            <input type="text" class="input-sm form-control" name="address_street" required value="{{Auth::user()->profile->address_street}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address City/Municipal *</label>
                                                            <input type="text" class="input-sm form-control" name="address_city" required value="{{Auth::user()->profile->address_city}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address Province *</label>
                                                            <input type="text" class="input-sm form-control" name="address_province" required value="{{Auth::user()->profile->address_province}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address Zip *</label>
                                                            <input type="text" class="input-sm form-control" name="address_zip" required value="{{Auth::user()->profile->address_zip}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address Country *</label>
                                                            <input type="text" class="input-sm form-control" name="address_country" required value="{{Auth::user()->profile->address_country}}">
                                                        </div>
                                                        <div class="form-group form-inline">
                                                            <button class="btn btn-success btn-sm" id="btnUpdateAddress">Update</button>
                                                            <a href="#" class="btn btn-default btn-sm" onclick="$('#update_billing_form').hide();">Cancel</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="shipping-content wizard-pane" role="tabpanel">
                                <div class="sponsor-wrapper">
                                    <h4>Shipping Options</h4>
                                    <hr>
                                    <div class="gap-10"></div>
                                    <div class="form-group sponsor-option">
                                        <label class="radio">
                                            <input type="radio" class="no-sponsor" name="deliveryOption" value="0" required="" checked>
                                            One Time Delivery
                                            <span class="radio-button"></span>
                                        </label>
                                        <label class="radio">
                                            <input type="radio" class="with-sponsor" name="deliveryOption" value="1" required="">
                                            Scheduled (Auto Ship Monthly)
                                            <span class="radio-button"></span>
                                        </label>
                                        <label class="radio">
                                            <input type="radio" class="with-sponsor" name="deliveryOption" value="2" required="">
                                            Scheduled (Auto Ship Yearly)
                                            <span class="radio-button"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="checkout-card selected">
                                    <div class="edit-item">
                                        <a href="#" class="edit" onclick="$('#update_delivery_form').show();$('#new_delivery_form').hide();">Edit</a>
                                    </div>
                                    <div class="unit flex-row unit-spacing-s">
                                        <div class="unit__left">
                                            <span class="fa fa-check-circle fa-icon"></span>
                                        </div>
                                        <div class="unit__body">
                                            <h3 class="customer-name"></h3>
                                            <p class="customer-address" id="currentDeliveryAddress">{{$profile->complete_delivery_address()}}</p>
                                            <div class="row" id="update_delivery_form" style="display:none;">
                                                <div class="col-md-12">
                                                    <br>
                                                    <form id="updateDeliveryAddressForm">
                                                        @csrf
                                                        <div id="updateDeliverySuccess" class="alert alert-success" role="alert" style="display: none;">
                                                            <span class="fa fa-info-circle"></span> Delivery Address has been updated
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Street *</label>
                                                            <input type="text" class="input-sm form-control" value="{{$profile->address_delivery_street}}" name="address_delivery_street" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>City *</label>
                                                            <input type="text" class="input-sm form-control" value="{{$profile->address_delivery_city}}" name="address_delivery_city" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Province *</label>
                                                            <input type="text" class="input-sm form-control" value="{{$profile->address_delivery_province}}" name="address_delivery_province" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Zip *</label>
                                                            <input type="text" class="input-sm form-control" value="{{$profile->address_delivery_zip}}" name="address_delivery_zip" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Country *</label>
                                                            <input type="text" class="input-sm form-control" value="{{$profile->address_delivery_country}}" name="address_delivery_country" required>
                                                        </div>
                                                        <div class="form-group form-inline">
                                                            <button class="btn btn-success btn-sm" id="btnUpdateDeliveryAddress">Update</button>
                                                            <a href="#" class="btn btn-default btn-sm" onclick="$('#update_delivery_form').hide();">Cancel</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wizard-pane" role="tabpanel">
                                <h4>Review and Place Order</h4>
                                <hr>
                                <div class="gap-10"></div>
                                <div class="checkout-content">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="subtitle">Billed To</label>
                                            <h4 class="customer-name"></h4>
                                            <p class="customer-address">{{$profile->memberAddress}}</p>
                                            <p class="customer-phone">Tel No: {{$profile->phone}}</p>
                                            <p class="customer-email">Email: {{Auth::user()->email}}</p>
                                        </div>
                                    </div>
                                    <div class="gap-40"></div>
                                    <div class="table-responsive mg-t-40">
                                        <table class="table table-invoice bd-b order-table">
                                            <thead>
                                                <tr>
                                                    <th class="w-25">Name</th>
                                                    <th class="w-15 text-center">Qty</th>
                                                    <th class="w-15 text-right">Price</th>
                                                    @if($products->sum('installation_fee') > 0)
                                                        <th class="w-15 text-right">Intallation Fee</th>
                                                    @endif
                                                    <th class="w-15 text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $sub_total = 0;

                                                    /** Save on Sales Header **/
                                                    $total_kilos = 0;
                                                    $above_3kls_fee = 0;
                                                    $distance_fee = 0;
                                                    $shipping_fee = 0;
                                                    $convenience_fee = 0;
                                                    $grand_total = 0;
                                                @endphp
                                                @forelse($products as $product)
                                                    @php
                                                        $sub_total += $product->itemTotalPrice + $product->itemTotalInstallationFee;
                                                        $total_kilos += $product->product->weight * $product->qty;
                                                    @endphp
                                                    <tr>
                                                        <td class="tx-nowrap"><a href="">{{$product->product->name}} </a></td>
                                                        <td class="text-center">{{$product->qty}}</td>
                                                        <td class="text-right">₱ {{number_format($product->price,2)}}</td>
                                                        @if($products->sum('installation_fee') > 0)
                                                            <td class="text-right">₱ {{number_format($product->itemTotalInstallationFee,2)}}</td>
                                                        @endif
                                                        <td class="text-right">₱ {{number_format($product->grandPrice,2)}}</td>
                                                    </tr>
                                                    <input type="hidden" name="weight{{$product->id}}" value="{{$product->product->weight}}">

                                                @empty

                                                @endforelse
                                                @php
                                                    $above_3kls_fee = Setting::kilo_fee($total_kilos,$profile->address_delivery_zip);
                                                    $convenience_fee = Setting::convenience_fee();
                                                    $distance_fee = Setting::distance_fee($profile->address_delivery_zip);
                                                    $shipping_fee = $above_3kls_fee + $distance_fee;
                                                    $grand_total = $sub_total + $shipping_fee + $convenience_fee;
                                                @endphp
                                                <tr>
                                                    <td colspan="5">&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="checkout-bt row justify-content-between">
                                        <div class="col-sm-12 col-lg-6 order-2 order-sm-0 mg-t-40 mg-sm-t-0">
                                            <div class="gap-30"></div>
                                            <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Notes</label>

                                        </div>
                                        <!-- col -->
                                        <div class="col-sm-12 col-lg-4 order-1 order-sm-0">
                                            <div class="gap-30"></div>
                                            <ul class="list-unstyled lh-7 pd-r-10">
                                                <li class="d-flex justify-content-between">
                                                    <span>Sub-Total</span>
                                                    <span>₱ {{number_format($sub_total,2)}}</span>
                                                </li>
                                                <li class="d-flex justify-content-between">
                                                    <span>Shipping Fee</span>
                                                    <span>₱ {{number_format($shipping_fee,2)}}</span>
                                                </li>
                                                <li class="d-flex justify-content-between">
                                                    <span>Convenience Fee</span>
                                                    <span>₱ {{number_format($convenience_fee,2)}}</span>
                                                </li>
                                                <li class="d-flex justify-content-between">
                                                    <strong>Grand Total</strong>
                                                    <strong>₱ {{number_format($grand_total,2)}}</strong>
                                                </li>
                                            </ul>

                                            <!-- <button class="btn btn-block primary-btn mt-2"><span class="fa fa-check-circle fa-icon mr-2"></span> Place Order</button> -->
                                        </div>
                                        <!-- col -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('jsscript')
    <script src="{{ asset('theme/legande/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('theme/legande/plugins/responsive-tabs/js/jquery.responsiveTabs.min.js') }}"></script>
    <script src="{{ asset('theme/legande/plugins/gijgo/js/gijgo.min.js') }}"></script>
    <script src="{{ asset('theme/legande/plugins/jquery-wizard/jquery-wizard.js') }}"></script>
    <script src="{{ asset('theme/legande/plugins/jquery-wizard/formvalidation/formValidation.min.js') }}"></script>
    <script src="{{ asset('theme/legande/plugins/jquery-wizard/formvalidation/bootstrap.min.js') }}"></script>

    <script>
        {{--$('#autoship_frequency').change(function(){--}}
        {{--    $("#autoship_div").html('');--}}
        {{--    for(x=1;x<=parseInt($(this).val());x++){--}}
        {{--        $("#autoship_div").append('<div class="form-group"><label>Schedule '+x+':</label><input type="date" name="autoship_frequency" id="autoship_frequency" class="form-control"></div>');--}}
        {{--    }--}}
        {{--});--}}

        {{--function save_autoship(){--}}
        {{--    var i = $('#autoship_frequency').val();--}}
        {{--    for(x=1;x<=parseInt(i);x++){--}}
        {{--        $.ajaxSetup({--}}
        {{--            headers:{--}}
        {{--                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--            }--}}
        {{--        })--}}
        {{--        $.ajax({--}}
        {{--            method: "POST",--}}
        {{--            url: "{{route('product.review.store')}}",--}}
        {{--            data: {--}}
        {{--                product_id: {{$product->id}},--}}
        {{--                rating: $('#rating-count').val(),--}}
        {{--                review: $('#review-text').val()--}}
        {{--            }--}}
        {{--        })--}}
        {{--        .done(function( html ) {--}}
        {{--            //$( "#records" ).html( html );--}}
        {{--            $('#review-form').html('<div class="alert alert-primary" role="alert"><h4 class="alert-heading">Success!</h4> Thank you for submitting your review.</div>');--}}
        {{--        });--}}
        {{--    }--}}
        {{--}--}}
    </script>
    <script>
        (function () {
            $('#wizard1').wizard({
                enableWhenVisited: true,
                buttonLabels: {
                    next: 'Next',
                    back: 'Previous',
                    finish: 'Finish'
                },
                // onInit: function () {
                //     $('#validation').formValidation({
                //         framework: 'bootstrap',
                //     });
                // },
                // validator: function () {
                //     var fv = $('#validation').data('formValidation');
                //     var $this = $(this);
                //     // Validate the container
                //     fv.validateContainer($this);
                //     var isValidStep = fv.isValidContainer($this);
                //     if (isValidStep === false || isValidStep === null) {
                //     return false;
                //     }
                //     return true;
                // },
                onFinish: function () {
                    let deliveryOption = $('input[name="deliveryOption"]:checked').val();
                    window.location.href = "{{ url('/') }}/payment-process?option="+deliveryOption;
                }
            });
        })();



        $(function() {
            $('#updateBillingAddressForm').on('submit', function(e) {
                e.preventDefault();

                $('#btnUpdateAddress').prop('disabled', true);
                let data = $(this).serialize();

                $.ajax({
                    data: data,
                    type: "post",
                    url: "{{route('profile.ajax_update_billing_address')}}",
                    success: function(returnData) {
                        $('#btnUpdateAddress').prop('disabled', false);
                        if (returnData['success']) {
                            $('#currentAddress').html(returnData['address']);
                            $('#updateAddressSuccess').fadeIn();
                        }
                    },
                    failed: function() {
                        $('#btnUpdateAddress').prop('disabled', false);
                    }
                });

                return false;
            });

            $('#useOtherAddressForm').on('submit', function(e) {

                e.preventDefault();
                return false;
            });


            $('#updateDeliveryAddressForm').on('submit', function(e) {
                e.preventDefault()

                $('#btnUpdateDeliveryAddress').prop('disabled', true);
                let data = $(this).serialize();

                $.ajax({
                    data: data,
                    type: "post",
                    url: "{{route('profile.ajax-update_delivery_address')}}",
                    success: function(returnData) {
                        $('#btnUpdateDeliveryAddress').prop('disabled', false);
                        if (returnData['success']) {
                            $('#currentDeliveryAddress').html(returnData['delivery_address']);
                            $('#updateDeliverySuccess').fadeIn();
                        }
                    },
                    failed: function() {
                        $('#btnUpdateDeliveryAddress').prop('disabled', false);
                    }
                });

                return false;
            });
        });
    </script>
@endsection
