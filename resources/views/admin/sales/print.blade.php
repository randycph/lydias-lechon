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

    </style>
</head>
 <!-- onload="setTimeout(function() {window.print();}, 1000);" -->
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
                <div class="col-sm-6 col-lg-6 mg-t-20">
                    <label class="tx-sans tx-uppercase tx-16 tx-bold">Customer Details</label>
                    <h6 class="tx-15 mg-b-10">@if($sales->user_id == 9999) {{$sales->customer_name}} @else {{$sales->user->FullName}} @endif</h6>
                    <p class="mg-b-0 tx-15">Contact No: {{$sales->customer_contact_number}} @if(!empty($sales->user->contact_tel)) | Tel no: {{$sales->user->contact_tel}} @endif</p>
                    <p class="mg-b-0 tx-15">Email: {{$sales->email}}</p>
                </div>

                <div class="col-sm-6 col-lg-6 mg-t-20">
                    <label class="tx-sans tx-uppercase tx-16 tx-bold">Order Details</label>
                    <ul class="list-unstyled lh-7">
                        <li class="d-flex justify-content-between tx-15">
                            <span>Order Date</span>
                            <span>{{ date('F d, Y H:i A', strtotime($sales->created_at))}}</span>
                        </li>
                        <li class="d-flex justify-content-between tx-15">
                            <span>Payment Status</span>
                            @if($sales->PaymentStatus == 'UNPAID')
                                <span class="tx-danger tx-semibold tx-uppercase">NOT PAID</span>
                            @else
                                <span class="tx-success tx-semibold tx-uppercase">{{$sales->PaymentStatus}}</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between tx-15">
                            <span>Delivery Status</span>
                            <span>{{$sales->delivery_status}}</span>
                        </li>
                    </ul>
                </div>

                <div class="col-sm-12 col-lg-12 mg-t-10">
                    <p class="mg-b-0 tx-15">Delivery Type: 
                        {{$sales->delivery_type}}
                        <br>
                        @if ($sales->delivery_type == 'Door to door delivery')
                            @if ($sales?->deliveryAddress && count($sales?->deliveryAddress) > 0)
                            <ul>
                            @foreach ($sales->deliveryAddress as $k => $address)
                            <li class="tx-15">
                                Address {{ $k + 1 }}: {{ $address->address }}<br>
                                Contact person: {{ $address->contact_person }}<br>
                                Contact number: {{ $address->contact_tel }}<br>
                                Delivery fee: ₱{{ number_format($address->delivery_fee, 2) }}<br>
                                Location: {{ $address->location }}<br>
                                Delivery Date and time: {{ \Carbon\Carbon::parse(strtotime($address->delivery_date . ' ' . $address->delivery_time))->format('F d, Y g:i A') }}<br>
                                Order/s:
                                    @if ($address->products)
                                        @php
                                            $products = json_decode($address->products);
                                        @endphp

                                        @if(is_array($products) || is_object($products))
                                            <ul>
                                                @foreach ($products as $product)
                                                    <li>
                                                        {{ $product->product->name ?? 'Unknown Product' }} x {{ $product->qty }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    @endif
                                <br>
                                @if ( $address->note)
                                Instruction: {{ $address->note }}<br>
                                @endif
                            </li>
                            @endforeach
                            </ul>
                            @else
                                {{$sales->customer_delivery_adress}}
                            @endif
                        @else
                           Delivery/Pickup Address: {{$sales->customer_delivery_adress}}
                        @endif
                    </p>   

                    <p class="mg-b-0 tx-15">Contact Person: {{$sales->contact_person ?? $sales->customer_name}}</p>
                    @if ($sales->instruction)
                    <p class="mg-b-0 tx-15">Instruction: {{$sales->instruction}}</p>
                    @endif
                </div>
            </div>

            <!-- Order Details -->
            <div class="table-responsive mg-t-40">
                <table class="table table-bordered bd-b tx-15">
                    <thead>
                        <tr>
                            <th class="wd-28p">Product Name</th>        
                            <th class="wd-15p tx-center">Date Needed</th>
                            <th class="tx-center">Quantity</th>
                            <th class="tx-center">Price</th>
                            <th class="tx-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesDetails as $details)
                            <tr>
                                <td class="tx-nowrap">{{$details->product_name}} @if($details->paella_price > 0) with paella @endif</td>                
                                <td class="tx-nowrap tx-center">{{date('F d, Y H:i A',strtotime($details->delivery_date))}}</td>
                                <td class="tx-center">{{number_format($details->qty, 0)}}</td>
                                <td class="tx-center">{{number_format($details->price, 2)}}</td>
                                <td class="tx-right">{{number_format($details->gross_amount, 2)}}</td>                               
                            </tr>
                        @empty
                            <tr>
                                <td class="tx-center " colspan="5">No transaction found.</td>
                            </tr>
                        @endforelse

                        @if($sales->gross_amount > 0)
                            <tr>
                                <td class="tx-left " colspan="4">Subtotal</td>
                                <td class="tx-right ">{{number_format($sales->gross_amount, 2)}}</td>
                            </tr>
                        @endif

                        @if($sales->delivery_fee_amount > 0)
                            <tr>
                                <td class="tx-left " colspan="4">Delivery Fee</td>
                                <td class="tx-right ">{{number_format($sales->delivery_fee_amount, 2)}}</td>
                            </tr>
                        @endif

                        @if($sales->discount_amount > 0)

                                @if($sales->couponUsed && count($sales->couponUsed) > 0)
                                    <tr>
                                        <td class="tx-left" colspan="6" style="">Discount</td>
                                    </tr>
                                    @foreach($sales->couponUsed as $coupon)
                                    <tr>
                                            <td class="tx-left" colspan="4" style=" padding-left: 20px;">
                                                Coupon (<i>{{ $coupon->coupon_code }}</i>)
                                            </td>
                                            @if ($coupon->coupon->free_product_id)
                                                <span class="text-green-500">Free Products </span>
                                                @php $products = explode('|', $coupon->coupon->free_product_id); @endphp
                                                <td class="tx-right" style=" color: red;">
                                                    @foreach ($products as $productId)
                                                        @php $product = \App\Models\Product::find($productId); @endphp
                                                        @if ($product)
                                                            <li class="text-green-500">
                                                                {{ $product->name }}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @else
                                                <td class="tx-right" style=" color: red;">-{{ number_format($coupon->discount_used, 2) }}</td>
                                            @endif
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="tx-left" colspan="4" style="">Discount</td>
                                        <td class="tx-right" style=" color: red;">-{{ number_format($sales->discount_amount, 2) }}</td>
                                    </tr>
                                @endif
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
                                <td class="tx-right">Total: {{number_format($sales->net_amount < 0 ? 0 : $sales->net_amount, 2)}}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Payments -->
            @if($salesPayments->count())
            <div class="table-responsive mg-t-40">
                <h5>Payment Details</h5>
                <table class="table table-bordered bd-b tx-15">
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
                <table class="table table-bordered bd-b tx-15">
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

            <div class="mt-5">
                <div class="text-uppercase">Acknowledgment receipt</div>
                
                <div class="text-center mt-3">
                    <p class="text-uppercase font-weight-bold">Received the above in good order and condition</p>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <div class="flex-fill text-center">
                        <p class="font-weight-bold">Received by:</p>
                        <p class="mt-5" style="border-top: 1px solid black; width: 200px; margin: auto;"></p>
                    </div>
                    <div class="flex-fill text-center">
                        <p class="font-weight-bold">Received date and time:</p>
                        <p class="mt-5" style="border-top: 1px solid black; width: 200px; margin: auto;"></p>
                    </div>
                    <div class="flex-fill text-center">
                        <p class="font-weight-bold">Delivered by:</p>
                        <p class="mt-5" style="border-top: 1px solid black; width: 200px; margin: auto;"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
