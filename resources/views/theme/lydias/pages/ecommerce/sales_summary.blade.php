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

    <!-- vendor css -->
    <link href="{{ asset('lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashforge.dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skin.deepblue.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-admin.css') }}">

    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        .row-selected {
            background-color: #92b7da !important;
        }

    </style>

</head>

<body> 

        <div class="content ht-100v pd-0">
            <div class="container pd-x-0">
                 <div class="text-center mg-t-20"><img height="100px" src="https://www.exist.com/wp-content/uploads/2018/04/lydiaslechon2.png" alt=""></div>
                <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                    <div>
                        
                        
                        <h4> <br>Sales Transaction Summary</h4>
                        <h5>Order #: {{$sales->order_number}}</h5>
                    </div>
                    
                </div>

                <div class="row row-sm">
                    <div class="col-sm-6 col-lg-8">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Customer Details</label>
                        <p class="mg-b-3 tx-semibold">{{$sales->customer_name}}</p>                  
                        <p class="mg-b-3">Tel No: {{$sales->customer_contact_number}}</p>
                        <p class="mg-b-3">Email: {{$sales->email}}</p>
                        <p class="mg-b-3">{{$sales->delivery_type}}: {{$sales->customer_delivery_adress}}</p>                        
                        <p class="mg-b-3">Instruction: {{$sales->instruction}}</p>
                    </div>
                    <!-- col -->
                    <div class="col-sm-6 col-lg-4">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Order Details</label>
                        <ul class="list-unstyled lh-7">
                            <li class="d-flex justify-content-between">
                                <span>Order Date</span>
                                <span>{{ date('F d, Y H:i A', strtotime($sales->created_at))}}</span>
                            </li>                                                   
                            <li class="d-flex justify-content-between">
                                <span>Payment Status</span>
                                <span class="tx-success tx-semibold tx-uppercase">{{$sales->PaymentStatus}}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Delivery Status</span>
                                <span class="tx-success tx-semibold tx-uppercase">{{$sales->delivery_status}}</span>
                            </li>
                            <hr>
                        </ul>
                    </div>
                    <!-- col -->

                    <div class="table-responsive mg-t-20">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Order Details</label>
                        <table class="table table-invoice bd-b">
                            
                            <thead>
                            <tr>
                                <th class="wd-10p">Product Code</th>
                                <th class="wd-30p">Product Name</th>                                
                                <th class="tx-center">No. of Pax</th>
                                <th class="tx-center">Date Needed</th>
                                <th class="tx-center">Quantity</th>
                                <th class="tx-center">Paella Price</th>
                                <th class="tx-right">Price</th>
                                <th class="tx-right">Total</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($salesDetails as $details)
                            <tr>
                                <td class="tx-nowrap">{{$details->product->code}}</td>
                                <td class="tx-nowrap">{{$details->product_name}} @if($details->paella_price > 0) with paella @endif</td>
                                <th class="tx-center">{{$details->no_of_pax}}</th>                                
                                <td class="tx-nowrap">{{date('F d, Y H:i A',strtotime($details->delivery_date))}}</td>
                                <td class="tx-center">{{number_format($details->qty, 0)}}</td>
                                <td class="tx-right">{{number_format(($details->paella_price),2)}}</td>
                                <td class="tx-right">{{number_format($details->price, 2)}}</td>
                                <td class="tx-right">{{number_format($details->gross_amount, 2)}}</td>                               
                            </tr>
                            @empty
                                <tr>
                                    <td class="tx-center " colspan="5">No transaction found.</td>
                                </tr>
                            @endforelse
                            @if($sales->delivery_fee_amount > 0)
                                <tr>
                                    <td class="tx-left " colspan="7">Delivery Fee</td>
                                    <td class="tx-right ">{{number_format($sales->delivery_fee_amount, 2)}}</td>
                                </tr>
                            @endif
                            @forelse($gc as $g)
                                <tr style="font-weight:bold;">
                                    <td class="tx-left" colspan="7">Gift Certificate: {{$g->code}}</td>
                                    <td class="tx-right">({{number_format($g->amount, 2)}})</td> 
                                </tr>
                            @empty
                            @endforelse
                            @if($salesDetails->sum('gross_amount') > 0)
                                <tr style="font-weight:bold;">
                                    <td class="tx-left" colspan="7">Total</td>
                                    <td class="tx-right">{{number_format($sales->gross_amount, 2)}}</td> 
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mg-t-20">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Payments</label>
                        <table class="table table-invoice bd-b">
                            
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
                                    <td class="tx-left" colspan="4">Total</td>
                                    <td class="tx-right">{{number_format($salesPayments->sum('amount'), 2)}}</td> 
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="table-responsive mg-t-20">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Delivery History</label>
                        <table class="table table-invoice bd-b">
                            
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
                                    <td class="tx-center " colspan="6">No delivery transaction found</td>
                                </tr>
                            @endforelse
                           
                            </tbody>
                        </table>
                    </div>

                   
                   
                    <!-- col -->
                  

                </div>
                <!-- row -->
            </div>
            <!-- container -->
        </div>

</body>
</html>
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
