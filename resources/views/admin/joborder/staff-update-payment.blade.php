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
                    <li class="breadcrumb-item active" aria-current="page">Sales</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Update Job Order Payment</h4>
        </div>
    </div>
    <form autocomplete="off" action="{{ route('staff-update-payment') }}" method="post">
        @csrf
        <div class="row row-sm">
            <div class="col-lg-6">
                <div class="cs-search">
                    <label class="d-block tx-semibold">Customer Details</label>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $details->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th>E-mail</th>
                                    <td>{{ $details->customer_details->email }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Number</th>
                                    <td>{{ $details->customer_details->contact_mobile }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <label class="d-block tx-semibold mg-t-20">Delivery Details</label>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <th scope="row">Delivery Address</th>
                                    <td>{{$details->customer_delivery_adress }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Delivery Date & Time</th>
                                    <td>{{$details->delivery_date }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Order Type</th>
                                    <td>{{$details->order_type }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Delivery Type</th>
                                    <td>{{$details->delivery_type }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Outlet</th>
                                    <td>{{$details->outlet }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Remarks</th>
                                    <td>{{$details->instruction}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h6 class="mg-t-20 mg-b-10 tx-semibold">Payment Details</h6>
                <hr>
                <div class="form-group">
                    <div class="jo-products">
                        <div class="card bg-gray-100">
                            <div class="card-header"><i class="fa fa-calculator"></i> On-Screen Computation</div>
                            <div class="card-body">
                                <div>Ordered Products</div>
                                <table class="table table-borderless table-xs mg-b-20 bd-b">
                                    <thead>
                                        <th>Product Name</th>
                                        <th>Price/Item</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                    </thead>
                                    <tbody>
                                        @php
                                            $t_product_price = 0;
                                            $t_paella_price = 0;
                                            $product_qty = 0;
                                            $paella_qty = 0;
                                        @endphp
                                        
                                        @foreach($details->items as $item)
                                            @if($item->product->production_item == 1)
                                                @php
                                                    $product_qty += $item->qty;
                                                    $paella_qty += $item->paella_qty;

                                                    $t_product_price += $item->price*$item->qty;
                                                    $t_paella_price += $item->paella_price*$item->paella_qty;
                                                @endphp
                                                <tr>
                                                    <td>{{$item->product_name}}</td>
                                                    <td class="text-right">{{number_format($item->price,2)}}</td>
                                                    <td class="text-right">{{$item->qty}}</td>
                                                    <td class="text-right">PHP {{number_format(($item->price*$item->qty),2)}}</td>
                                                </tr>
                                                @if($item->paella_qty > 0)
                                                <tr>
                                                    <td>Add-On: Paella</td>
                                                    <td class="text-right">{{number_format($item->paella_price,2)}}</td>
                                                    <td class="text-right">{{$item->paella_qty}}</td>
                                                    <td class="text-right">PHP {{number_format(($item->paella_price*$item->paella_qty),2)}}</td>
                                                </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>

                                <div>Miscellaneous Products</div>
                                <table class="table table-borderless table-xs mg-b-20 bd-b">
                                    <thead>
                                        <th>Product Name</th>
                                        <th>Price/Item</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                    </thead>
                                    <tbody>
                                        @php
                                            $t_misc_price = 0;
                                            $misc_qty = 0;
                                        @endphp

                                        @foreach($details->items as $item)
                                            @if($item->product->is_misc == 1)
                                                @php
                                                    $misc_qty += $item->qty;
                                                    $t_misc_price += $item->price*$item->qty;
                                                @endphp
                                                <tr>
                                                    <td>{{$item->product_name}}</td>
                                                    <td class="text-right">{{number_format($item->price,2)}}</td>
                                                    <td class="text-right">{{$item->qty}}</td>
                                                    <td class="text-right">PHP {{number_format(($item->price*$item->qty),2)}}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="table-responsive pd-b-20 on-screen-computation">
                                    <table class="table table-borderless table-xs mg-b-0 bd-b">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><strong>Sub Total :</strong> </th>
                                                <td>PHP {{number_format(($t_product_price+$t_paella_price+$t_misc_price),2)}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Quantity :</strong></th>
                                                <td id="totalQty">{{$product_qty+$paella_qty+$misc_qty}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Delivery Charge :</strong></th>
                                                <td id="summary_delivery_charge">PHP {{number_format($details->delivery_fee_amount,2)}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Total Misc :</strong></th>
                                                <td id="summary_total_misc">PHP {{number_format($t_misc_price,2)}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-borderless table-xs mg-b-0 bd-b">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><strong>Total :</strong> </th>
                                                <td id="grand_total">PHP {{number_format($t_product_price+$t_paella_price+$t_misc_price+$details->delivery_fee_amount,2)}}</td>
                                                <td style="display: none;"><input type="text" id="summary_input_gross" value="{{number_format($t_product_price+$t_paella_price+$t_misc_price+$details->delivery_fee_amount,2,'.','')}}"></td>
                                            </tr>
                                            @if(auth()->user()->role_id == 4)
                                            <tr>
                                                <th scope="row"><strong>Deposit :</strong></th>
                                                <td id="summary_deposit">0.00</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Discount :</strong></th>
                                                <td id="summary_discount">0.00</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <table class="table table-borderless table-xs mg-b-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><strong>BALANCE :</strong> </th>
                                                <td id="balance">PHP {{number_format($t_product_price+$t_paella_price+$t_misc_price+$details->delivery_fee_amount,2)}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form>
                    <input type="hidden" name="header_id" value="{{$details->id}}">
                    <div class="form-group">
                        <label class="d-block">Payment Method <span class="tx-danger">*</span></label>
                        <select required class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select payment method" data-width="100%" name="payment_type">
                            <option value="Bank Deposit">Bank Deposit</option>
                            <option value="Debit/Credit Card">Debit/Credit Card</option>
                            <option value="M Lhuillier">M Lhuillier</option>
                            <option value="Gift Certificate">Gift Certificate</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                                        
                    <div class="form-group">
                        <label class="d-block">Deposit</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Php</div>
                            </div>
                            <input type="number" name="deposit" step="0.01" class="form-control text-right" id="deposit_amount" value="0.00">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="d-block">Discount</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Php</div>
                            </div>
                            <input type="number" step="0.01" name="discount" class="form-control text-right" id="discount_amount" value="0.00" onchange="discount();">
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-12 mg-t-20 mg-b-30">
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Sales Payment</button>
                <a  href="{{route('sales-transaction.index')}}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
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

        $(document).on('change','#deposit_amount',function(){
            var amount = $('#deposit_amount').val();
            $('#summary_deposit').html(FormatAmount(amount,2));

            calculate_balance(); 
        });

        $(document).on('change','#discount_amount',function(){
            var amount = $('#discount_amount').val();
            $('#summary_discount').html(FormatAmount(amount,2));

            calculate_balance();
        });

        function calculate_balance(){
            var gross  = $('#summary_input_gross').val();
            var deposit = $('#deposit_amount').val();
            var discount = $('#discount_amount').val();

            var total_deducted_amount = parseInt(deposit)+parseInt(discount);
            var balance = parseInt(gross)-total_deducted_amount;

            $('#balance').html('PHP '+FormatAmount(balance,2));

        }

        function FormatAmount(number, numberOfDigits) {

            var amount = parseFloat(number).toFixed(numberOfDigits);
            var num_parts = amount.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return num_parts.join(".");

        }
    /** On screen computation functions **/
    </script>
@endsection
