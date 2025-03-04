@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <style>
        .quantity {
            display: block;
            margin: 0 0 10px;
            position: relative;
        }

        .quantity .product-pcs {
            height: 60px;
            border: solid 1px #dfe3e9;
            display: inline-block;
            font-size: 1.05em;
            font-weight: 500;
            padding: 12px 25px;
            margin-left: -1px;
        }

        .quantity input {
            color: #443017;
            background-color: #ffffff;
            border: solid 1px #ced4da;
            font-family: "Roboto", sans-serif;
            font-size: .85em;
            font-weight: 300;
            max-width: 150px;
            height: 38px;
            float: left;
            display: block;
            padding: 0 30px 0 2px;
            margin: 0;
            text-align: center;
            width: 100%;
            -webkit-transition: all 0.15s ease-in-out;
            transition: all 0.15s ease-in-out;
        }

        .quantity input:focus {
            outline: 0;
            border-color: #981c1e;
        }

        .quantity .quantity-nav {
            float: left;
            position: relative;
            height: 38px;
        }

        .quantity .quantity-nav .quantity-button {
            position: relative;
            cursor: pointer;
            border-left: 1px solid #ced4da;
            width: 25px;
            text-align: center;
            color: #333;
            font-size: 16px;
            font-family: "Trebuchet MS", Helvetica, sans-serif !important;
            line-height: 1;
            -webkit-transform: translateX(-100%);
            transform: translateX(-100%);
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .quantity .quantity-nav .quantity-button:active {
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
            -webkit-box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .quantity .quantity-nav .quantity-button.quantity-up {
            position: absolute;
            height: 50%;
            top: 0;
            border-bottom: 1px solid #ced4da;
        }

        .quantity .quantity-nav .quantity-button.quantity-down {
            position: absolute;
            bottom: 0px;
            height: 50%;
        }


.shopping-cart-wrapper .border-left, .shopping-cart-wrapper .border-bottom, .shopping-cart-wrapper .border-right{border:none !important;}
.shopping-cart-wrapper .py-4, .shopping-cart-wrapper .py-5 {padding:0 !important;}
    </style>
@endsection

@section('content')
    <section>
        <div class="content-wrapper">
            <div class="gap-70"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        @if (\Session::has('coupon-success'))
                            <div class="alert alert-success">
                                Successfully applied coupon
                            </div>
                        @endif
                        @if (\Session::has('coupon-remove-success'))
                            <div class="alert alert-success">
                                Successfully removed coupon
                            </div>
                        @endif
                        <div class="shopping-cart-wrapper">
                            <div class="shopping-cart-label mb-5 text-center d-none d-lg-block">
                                <div class="row bg-secondary text-light rounded" style="font-family: sans-serif;">
                                    <div class="col-lg-4 border-right py-2"><span>Item(s)</span></div>
                                    <div class="col-lg-2 border-right py-2"><span>Quantity</span></div>
                                    <div class="col-lg-1 border-right py-2"><span>Size</span></div>
                                    <div class="col-lg-2 border-right py-2"><span>Additional</span></div>
                                    <div class="col-lg-2 border-right py-2"><span>Price</span></div>
                                    <div class="col-lg-1">&nbsp;</div>
                                </div>
                            </div>
                            <form id="checkOutForm" action="{{route('cart.front.batch_update')}}" method="post">
                                @csrf
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="total_products" value="{{ count((array)$cart) }}">
                                @php
                                    $coupon_amount = 0;
                                    $coupon_codes = '';
                                @endphp
                                @forelse($cart as $key => $order)
                                    @php
                                        if (auth()->check()) {
                                            $orderId = $order->id;
                                            $product = $order->product;
                                        } else {
                                            $orderId = $key;
                                            $order = (object) $order;
                                            $product = \App\Product::find($order->product_id);
                                        }

                                        if (empty($product)) {
                                            continue;
                                        }
                                        if(!empty($order->coupon_code)){

                                            $coupons = explode("|", $order->coupon_code);
                                            foreach($coupons as $coupon){
                                                if(strlen($coupon)>1){
                                                    $c = explode(":", $coupon);
                                                    $coupon_codes.='<li>
                                                        <table width="100%">
                                                            <tr>
                                                                <td><a href="#" onclick=\'deapply_coupon("'.$c[0].'");\'><i class="fa fa-minus-circle"></i></a></td>
                                                                <td style="font-weight:bold;">&nbsp;&nbsp;'.strtoupper($c[0]).'</td>
                                                                <td>&nbsp;&nbsp;&nbsp;&nbsp; (&#8369; '.number_format($c[1],2).')</td>
                                                            </tr>
                                                        </table>
                                                   
                                                    </li>';
                                                }
                                            }
                                            $coupon_amount = $order->coupon_amount;
                                        }
                                    @endphp
                                    <input type="hidden" name="record_id[{{$loop->iteration}}]" value="{{$order->product_id}}">
                                    <div class="shopping-cart" style="font-size:14px;">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-4">
                                                <div class="shopping-cart-detials">
                                                    <img src="{{ asset('storage/products/'.$product->photoPrimary) }}" />
                                                    <div class="shopping-cart-title">{{$product->name}}</div>
                                                    <div class="shopping-cart-details">&#8369; {{ number_format($product->price,2) }} / order</div>
                                                    <div class="clearfix"></div>
                                                    <div class="gap-10"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2">
                                                <div class="row">
                                                    <div class="shopping-cart-quantity">
                                                        <div class="input-group">
                                                         <span class="input-group-btn">
                                                           <button type="button" class="btn btn-default btn-number minus-btn" @if($order->qty==1) disabled="disabled" @endif data-type="minus" data-field="quantity[{{$loop->iteration}}]">
                                                             <span class="fa fa-minus"></span>
                                                           </button>
                                                         </span>
                                                            <input type="text" name="quantity[{{$loop->iteration}}]" id="quantity{{$loop->iteration}}" onchange="compute({{$loop->iteration}});" class="form-control input-number quantity-fld text-center" value="{{$order->qty}}" min="1" max="100">
                                                            <span class="input-group-btn">
                                                           <button type="button" class="btn btn-default btn-number plus-btn" data-type="plus" data-field="quantity[{{$loop->iteration}}]">
                                                             <span class="fa fa-plus"></span>
                                                           </button>
                                                         </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-1">
                                                {{$product->weight}}
                                            </div>
                                            <div class="col-md-12 col-lg-2 text-center">
                                                @if($order->paella_price > 0)
                                                    With Paella<br> &#8369; {{ number_format($order->paella_price,2) }}
                                                @endif
                                            </div>
                                            <div class="col-md-12 col-lg-2">
                                                <div class="shopping-cart-total">
                                                    <strong>&#8369; <span id="price_div{{$loop->iteration}}">{{ number_format($order->itemTotalPrice,2) }}</span></strong>
                                                </div>
                                            </div>
                                            {{-- Item Price --}}
                                            <input type="hidden" name="price{{$loop->iteration}}" id="price{{$loop->iteration}}" value="{{$product->price}}">

                                            {{-- Paella Price --}}
                                            <input type="hidden" name="paella_price{{$loop->iteration}}" id="paella_price{{$loop->iteration}}" value="@if($order->paella_price > 0){{$product->paella_price}}@else{{0.00}}@endif">

                                            {{-- Installation Fee per Item --}}
                                            <input type="hidden" id="installation_fee{{$loop->iteration}}" name="installation_fee{{$loop->iteration}}" value="{{$product->installation_fee}}">

                                            {{-- Sub Total Price --}}
                                            <input type="hidden" id="sum_sub_price{{$loop->iteration}}" name="sum_sub_price{{$loop->iteration}}" value="{{$order->itemTotalPrice}}">

                                            {{-- Grand Total Price --}}
                                            <input type="hidden" id="sum_total_price{{$loop->iteration}}" name="sum_total_price{{$loop->iteration}}" value="{{$order->grand_price}}">

                                            {{-- Total Installation Fee --}}
                                            {{-- <input type="hidden" id="sum_installation_fee{{$loop->iteration}}" name="sum_installation_fee{{$loop->iteration}}" value="{{$order->itemTotalInstallationFee}}">--}}
                                            <div class="col-md-12 col-lg-1 py-5 border-bottom border-right">
                                                <a  href="javascript:void(0)" onclick="remove_item({{$orderId}})" class="btn btn-light remove-btn" data-dismiss="modal">Remove</a>
                                            </div>

                                        </div>
                                    </div>
                                @empty
                                    <div class="no-order-wrapper">
                                        <div class="alert alert-warning" role="alert">
                                            Your shopping cart is empty. Click <a href="#" data-toggle="modal" data-target="#terms-modal">here</a> to start shopping
                                        </div>
                                    </div>
                                @endforelse
                                <input type="hidden" name="customer_type" id="customer_type" value="0">
                            </form>
                        </div>

                    </div>
                </div>
                @if(count($cart) > 0)
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            @auth
                                <div class="coupon-code">
                                    <strong>Have a coupon code?</strong>
                                    <form class="form-inline" method="post" action="{{route('cart.front.apply_coupon')}}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" required="required" class="form-control coupon-fld" name="coupon" placeholder="Enter Code Here">
                                        </div>
                                        <button type="submit" class="btn btn-light add-btn">Apply Coupon Code</button>
                                        @if($coupon_amount > 0)
                                        <div class="gap-10"></div>
                                        <div class="coupon-list">
                                            <ul>
                                                {!!$coupon_codes!!}
                                            </ul>
                                        </div>
                                        @endif
                                    </form>
                                </div>
                            @endauth
                        </div>
                        <input type="hidden" id="coupon_amount" value="{{$coupon_amount}}">
                        <div class="col-md-6 col-lg-5 offset-lg-1">
                            <div class="cart-summary">
                                <strong>Cart Summary</strong>
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td>Subtotal</td>
                                        <th id="grandSubTotal">&#8369; 0.00</th>
                                    </tr>
                                    <tr>
                                        <td>Coupon Discount</td>
                                        <td id="grandcoupon">&#8369; 0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Total Cart Amount</th>
                                        <th id="grandtotal">&#8369; 0.00</th>
                                    </tr>
                                    </tbody>
                                </table>
                                @guest
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="" id="checkoutAsGuest">
                                    <label class="form-check-label" for="defaultCheck1">
                                        Check out as a Guest
                                    </label>
                                </div>
                                @endguest
                                <a href="javascript:;" class="btn btn-secondary checkout-btn">Checkout</a>
                                <a class="btn btn-primary add-btn" href="{{route('product.front.show_forsale')}}">Add more products</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="gap-70"></div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal effect-scale" id="prompt-remove" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('Remove Item')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('Are you sure you want to remove this item?')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnDelete">Yes</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <div style="display:none;">
        <form id="remove_form" method="post" action="{{route('cart.remove_product')}}">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="product_remove_id" id="product_remove_id" value="">
            <button type="submit" href="#" class="removed" style="font-size:.7em;text-transform:uppercase;">
                Remove <span class="lnr lnr-cross"></span>
            </button>
        </form>
    </div>

    <div style="display:none;">
        <form id="deapply_form" method="post" action="{{route('cart.front.deapply_coupon')}}">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="coupon_delete" id="coupon_delete" value="">
            <button type="submit" href="#" class="removed_code" style="font-size:.7em;text-transform:uppercase;">
                Remove <span class="lnr lnr-cross"></span>
            </button>
        </form>
    </div>

@endsection

@section('jsscript')

    <script>
        // $('#checkoutAsGuest').on('click', function () {
        //     if ($(this).is(':checked')) {
        //         $('#customer_type').val(1);
        //         //$('#checkout').attr('href', "{{ route('cart.front.checkout-as-guest') }}");
        //     } else {
        //         $('#customer_type').val(0);
        //         //$('#checkout').attr('href', "{{ route('cart.front.checkout') }}");
        //     }
        // });

        function deapply_coupon(code){
            $('#coupon_delete').val(code);
            $('#deapply_form').submit();
        }

        $('.qty').change(function() {
            if ($(this).val().trim() == '') {
                $(this).val(1);
            }

            let id = $(this).attr('id').replace('quantity','');

            update_installation_fee_per_item(id);

            update_sub_total_price_per_item(id);

            update_total_price_per_item(id);

            compute_grand_total();
        });

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

        function compute_grand_total(){

            let summary_sub_price = 0;
            let summary_installation_price = 0;
            let grand_price = 0;
            let grand_coupon = 0;

            for(x=1;x<={{ $totalProducts }};x++){
                grand_price+=parseFloat($('#sum_total_price'+x).val());
                summary_sub_price+=parseFloat($('#sum_sub_price'+x).val());
                summary_installation_price+=parseFloat($('#sum_installation_fee'+x).val());
            }
            grand_price = grand_price - parseFloat($('#coupon_amount').val());
            grand_coupon = parseFloat($('#coupon_amount').val());


            $('#grandtotal').html('&#8369; '+addCommas(grand_price.toFixed(2)));
            $('#grandSubTotal').html('&#8369; '+addCommas(summary_sub_price.toFixed(2)));
            $('#grandInstallationTotal').html('&#8369; '+addCommas(summary_installation_price.toFixed(2)));

            $('#summary_subtotal').val(summary_sub_price);
            $('#summary_installation_fee').val(summary_installation_price);
            $('#summary_grandtotal').val(grand_price);

            $('#grandcoupon').html('&#8369; '+addCommas(grand_coupon.toFixed(2)));
        }

        function compute(x){

            let summary_sub_price = 0;
            let summary_installation_price = 0;
            let grand_price = 0;

            var a = $('#price'+x).val();
            var price_total = parseFloat(a) * parseFloat($('#quantity'+x).val());
            var price_item_total = price_total + (parseFloat($('#paella_price'+x).val()) * parseFloat($('#quantity'+x).val()));

            $('#price_div'+x).html(addCommas(price_item_total+'.00'));
            $('#sum_sub_price'+x).val(price_item_total);

            for(x=1;x<={{ $totalProducts }};x++){
                grand_price+=parseFloat($('#sum_total_price'+x).val());
                summary_sub_price+=parseFloat($('#sum_sub_price'+x).val());
            }


            $('#grandSubTotal').html('&#8369; '+addCommas(summary_sub_price+'.00'));
            $('#grandtotal').html('&#8369; '+addCommas(summary_sub_price+'.00'));

            //console.log(parseFloat($('#paella_price'+x).val()));
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


        $(document).ready(function(){
            compute_grand_total();

            $('.checkout-btn').on('click', function () {
                if ($('#checkoutAsGuest').is(':checked')) {
                    $('#customer_type').val(1);
                } else {
                    $('#customer_type').val(0);
                }
                
                $('#checkOutForm').submit();
            });
        });


        function remove_item(i){

            $('#prompt-remove').modal('show');
            $('#btnDelete').on('click', function() {
                $('#product_remove_id').val(i);
                $('#remove_form').submit();
            });
        }

        $('.with_installation_cb').change(function(){

            let id = $(this).attr('id').replace('with_installation','');

            update_installation_fee_per_item(id);

            update_total_price_per_item(id);

            compute_grand_total();
        });

        function update_installation_fee_per_item(id){

            if($('#with_installation'+id).is(':checked')) {

                var ins_fee = parseFloat($('#quantity'+id).val()) * parseFloat($('#installation_fee'+id).val());
                $('#total_installation_fee'+id).html('&#8369; '+ addCommas(ins_fee.toFixed(2)));
                $('#sum_installation_fee'+id).val(ins_fee);
            }
            else{
                $('#total_installation_fee'+id).html('&#8369; 0.00');
                $('#sum_installation_fee'+id).val(0);
            }

        }

        function update_sub_total_price_per_item(id){

            var pr = parseFloat($('#quantity'+id).val()) * parseFloat($('#price'+id).val());

            $('#total_price'+id).html('&#8369; '+ addCommas(pr.toFixed(2)));
            $('#sum_sub_price'+id).val(pr);

        }

        function update_total_price_per_item(id){
            alert();
            var pr = $('#sum_sub_price'+id).val();
            var totalprice =  parseFloat(pr);

            $('#sum_total_price'+id).val(totalprice);
            $('#item_total_price'+id).html('&#8369; '+ addCommas(totalprice.toFixed(2)));

        }


    </script>
@endsection
