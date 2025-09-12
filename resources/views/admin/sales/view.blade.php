@extends('admin.layouts.app')

@section('pagetitle')
    Order Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
        html { overflow: hidden; }



    </style>
@endsection

@section('content')

    <div class="content ht-100v pd-0">
            <div class="container pd-x-0">
                 <div class="text-center mg-t-20"><img height="100px" src="{{ asset('images/lydias1965.png') }}" alt=""></div>
                <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                    <div>       
                        <h4> <br>Sales Transaction Summary</h4>
                        <h5>Order #: {{$sales->order_number}}</h5>
                        <a href="{{ route('sales.print',$sales->HashOrderNumber) }}" target="_blank" class="btn btn-xs btn-success">Print</a>
                    </div>

                    
                </div>

                <div class="row row-sm align-items-start">
                    <div class="col-sm-6 col-lg-8">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Customer Details</label>
                        <p class="mg-b-3 tx-semibold">{{$sales->customer_name}}</p>                  
                        <p class="mg-b-3">Mobile No: {{$sales->customer_contact_number ?? $sales->user->contact_mobile }} @if(!empty($sales->user->contact_tel)) | Tel no: {{$sales->user->contact_tel}} @endif</p>
                        <p class="mg-b-3">Email: {{$sales->email ?? $sales->user->email}}</p>
                        <p class="mg-b-3">{{$sales->delivery_type}}: 
                            @if ($sales->delivery_type == 'Door to door delivery')
                                @if ($sales?->deliveryAddress && count($sales?->deliveryAddress) > 0)
                                <ul>
                                @foreach ($sales->deliveryAddress->sortBy('delivery_date')->sortBy('delivery_time') as $k => $address)
                                <li>
                                    Contact person: {{ $address->contact_person }}<br>
                                    Contact number: {{ $address->contact_tel }}<br>
                                    Location: {{ $address->location }}<br>
                                    @if ($address->branch)
                                    Delivery Branch: {{ $address->branch ?? '' }}<br>
                                    @endif
                                    Address {{ $k + 1 }}: {{ $address->address }}<br>
                                    {{-- @php
                                        $salesPayments = $sales->payments;
                                    @endphp                              --}}
                                    Payment Method: {{ $salesPayments?->first()?->payment_type ?? 'None' }}<br>
                                    Order/s:
                                        @if ($address->products)
                                            @php
                                                $products = json_decode($address->products);
                                            @endphp

                                            @if(is_array($products) || is_object($products))
                                                <table class="table table-invoice bd-b">
                                                    <thead>
                                                    <tr>
                                                        <th class="wd-30p">Product Name</th>                                
                                                        <th class="tx-center">No. of Pax</th>
                                                        <th class="tx-center">Date Needed</th>
                                                        <th class="tx-center">Job Order#</th>
                                                        <th class="tx-center">Quantity</th>
                                                        <th class="tx-center">Paella</th>
                                                        <th class="tx-right">Price</th>
                                                        <th class="tx-right">Total</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @php
                                                        $grandTotal = 0;
                                                    @endphp

                                                    @forelse($products as $product)
                                                        @php
                                                            $lineTotal = ($product->product->price * ($product->qty ?? 1))
                                                                    + (!empty($product->paella) ? ($product->product->paella_price ?? 0) * ($product->qty ?? 1) : 0);

                                                            $grandTotal += $lineTotal;
                                                        @endphp
                                                        <tr>
                                                            <td class="tx-nowrap">
                                                                {!! highlightPaella($product?->product_name ?? '') !!}
                                                            </td>
                                                            <th class="tx-center">{{ $product->product->no_of_pax }}</th>                                
                                                            <td class="tx-nowrap">
                                                                {{ \Carbon\Carbon::parse(($address->delivery_date . ' ' . $address->delivery_time))->format('F d, Y g:i A') }}
                                                            </td>
                                                            <td></td>
                                                            <td class="tx-center">{{ number_format((int) ($product->qty ?? 0), 0) }}</td>
                                                            <td class="tx-right">
                                                                ₱{{ number_format(!empty($product->paella) ? ($product->product->paella_price ?? 0) : 0, 2) }}
                                                            </td>
                                                            <td class="tx-right">
                                                                ₱{{ number_format($product->product->price ?? 0, 2) }}
                                                            </td>
                                                            <td class="tx-right">
                                                                ₱{{ number_format($lineTotal, 2) }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="8" class="tx-center">No products</td></tr>
                                                    @endforelse

                                                    @if(isset($address->delivery_fee) && $address->delivery_fee > 0)
                                                        @php
                                                            $grandTotal += $address->delivery_fee;
                                                        @endphp
                                                        <tr>
                                                            <td class="tx-left" colspan="7">Delivery Fee</td>
                                                            <td class="tx-right">₱{{ number_format($address->delivery_fee, 2) }}</td>
                                                        </tr>
                                                    @endif

                                                    <tr>
                                                        <td class="tx-left" colspan="7"><strong>Total</strong></td>
                                                        <td class="tx-right"><strong>₱{{ number_format($grandTotal, 2) }}</strong></td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            @endif
                                        @endif
                                    <br>
                                </li>
                                @endforeach
                                </ul>
                                @else
                                    {{ $sales->customer_delivery_adress }}
                                @endif
                            @else
                                {{$sales->customer_delivery_adress}}
                            @endif
                        </p>
                        @if ($sales?->deliveryAddress && count($sales?->deliveryAddress) == 0)
                            @php 
                                $saleDetail = $sales->items ? $sales->items->first() : null;
                                $deliveryDate = $saleDetail ? \Carbon\Carbon::parse($saleDetail?->delivery_date)->format('F d, Y g:i A') : 'N/A';
                            @endphp
                            <p class="mg-b-3">Date needed: {{$deliveryDate}}</p>
                        @endif

                        <p class="mg-b-3">Contact Person: {{$sales->contact_person ?? $sales->customer_name}}</p>
                        @if ($sales->instruction)
                        <p class="mg-b-3">Note: {{$sales->instruction}}</p>
                        @endif
                    </div>
                    <!-- col -->
                    <div class="col-sm-6 col-lg-4">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Order Details</label>
                        <ul class="list-unstyled lh-7">
                            <li class="d-flex justify-content-between">
                                <span>Order Date</span>
                                <span>{{ \Carbon\Carbon::parse($sales->created_at)->format('F d, Y g:i A') }}</span>
                            </li>                                                   
                            <li class="d-flex justify-content-between">
                                <span>Payment Status</span>
                                <span class="tx-success tx-semibold tx-uppercase">{{$sales->PaymentStatus}} {!! $sales->status == 'CANCELLED' ? '<span class="badge badge-danger">CANCELLED</span>' : '' !!}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Delivery Status</span>
                                <span class="tx-success tx-semibold tx-uppercase">{{$sales->delivery_status}}</span>
                            </li>
                            <hr>
                        </ul>
                    </div>
                    <!-- col -->

                    @if (isset($salesDetails) && count($salesDetails) > 0)
                    <div class="table-responsive mg-t-20">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Order Details</label>
                        <table class="table table-invoice bd-b">
                            
                            <thead>
                            <tr>
                                <th class="wd-10p">Product Code</th>
                                <th class="wd-30p">Product Name</th>                                
                                <th class="tx-center">No. of Pax</th>
                                <th class="tx-center">Date Needed</th>
                                <th class="tx-center">Job Order#</th>
                                <th class="tx-center">Quantity</th>
                                <th class="tx-center">Paella</th>
                                <th class="tx-right">Price</th>
                                <th class="tx-right">Total</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($salesDetails as $details)
                            <tr>
                                <td class="tx-nowrap">{{$details->product->code}}</td>
                                <td class="tx-nowrap">{!! highlightPaella($details?->product_name) !!}</td>
                                <th class="tx-center">{{$details->no_of_pax}}</th>                                
                                <td class="tx-nowrap">
                                    @if(date('H:i A',strtotime($details->delivery_date)) == '12:00 PM')
                                        {{ \Carbon\Carbon::parse($details->delivery_date)->format('F d, Y g:i A') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($details->delivery_date)->format('F d, Y g:i A') }}
                                    @endif
                                    
                                </td>
                                <td>
                                    @forelse($details->joborders as $jo)
                                        @if($jo->status=='Active')
                                            {{$jo->jo_number}} 
                                            @if($jo->prodOrder->joborder_id > 0)
                                                ({{$jo->prodOrder->prodBranch->name}})
                                            @endif
                                        @endif
                                    @empty
                                    @endforelse
                                </td>
                                <td class="tx-center">{{number_format($details->qty, 0)}}</td>
                                <td class="tx-right">₱{{number_format(($details->paella_price),2)}}</td>
                                <td class="tx-right">₱{{number_format($details->price, 2)}}</td>
                                <td class="tx-right">₱{{number_format($details->gross_amount, 2)}}</td>                               
                            </tr>
                            @empty
                                <tr>
                                    <td class="tx-center " colspan="8">No transaction found.</td>
                                </tr>
                            @endforelse
                            @if($sales->delivery_fee_amount > 0)
                                <tr>
                                    <td class="tx-left " colspan="8">Delivery Fee</td>
                                    <td class="tx-right ">₱{{number_format($sales->delivery_fee_amount, 2)}}</td>
                                </tr>
                            @endif
                            @if($salesDetails->sum('gross_amount') > 0)
                                <tr>
                                    <td class="tx-left" colspan="8">Subtotal</td>
                                    <td class="tx-right">₱{{number_format($salesDetails->sum('gross_amount') + $sales->delivery_fee_amount, 2)}}</td>
                                </tr>
                            @endif
                            @if($sales->discount_amount > 0)

                                    @if($sales->couponUsed && count($sales->couponUsed) > 0)
                                        <tr>
                                            <td class="tx-left" colspan="6" style="">Discount</td>
                                        </tr>
                                        @foreach($sales->couponUsed as $coupon)
                                        <tr>
                                                <td class="tx-left" colspan="8" style=" padding-left: 20px;">
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
                                            <td class="tx-left" colspan="8" style="">Discount</td>
                                            <td class="tx-right text-danger">-{{number_format($sales->discount_amount, 2)}}</td>
                                        </tr>
                                    @endif
                            @endif

                            @forelse($gc as $g)
                                <tr style="font-weight:bold;">
                                    <td class="tx-left" colspan="8">Gift Certificate: {{$g->code}}</td>
                                    <td class="tx-right">₱({{number_format($g->amount, 2)}})</td> 
                                </tr>
                            @empty
                            @endforelse
                            @if($salesDetails->sum('net_amount') > 0)
                                <tr style="font-weight:bold;">
                                    <td class="tx-left" colspan="8">Total</td>
                                    <td class="tx-right">₱{{number_format($salesDetails->sum('net_amount') + $sales->delivery_fee_amount - $sales->discount_amount, 2)}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    @endif

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
                                @php $paidTotal=0; @endphp
                            @forelse($salesPayments as $payment)   
                                
                                @php 
                                    if($payment->status <> 'CANCELLED'){
                                        $paidTotal+=$payment->amount; 
                                    }
                                @endphp 
                            <tr>
                                <td class="tx-left">{{$payment->payment_type}}</td>
                                <td class="tx-center">{{$payment->receipt_number}}</td>
                                <td class="tx-center">{{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') }}</td>
                                <td class="tx-center">
                                    @if($payment->payment_type == 'Ok Order' || $payment->payment_type == 'COD')
                                        @if($payment->status == 'PAID')
                                            CONFIRMED
                                        @else
                                            NOT PAID
                                        @endif
                                    @else
                                        {{$payment->status}}
                                    @endif
                                </td>
                                <td class="tx-right">{{number_format($payment->amount, 2)}}</td>
                               
                            </tr>
                            @empty
                                <tr>
                                    <td class="tx-center " colspan="6">No payment found.</td>
                                </tr>
                            @endforelse
                            
                            @if($paidTotal > 0)
                                <tr style="font-weight:bold;">
                                    <td class="tx-left" colspan="4">Total</td>
                                    <td class="tx-right">{{number_format($paidTotal, 2)}}</td> 
                                </tr>
                            @endif
                            @php
                                $total_balance = $sales->net_amount - $paidTotal;
                            @endphp
                            @if($total_balance > 0)
                                <tr style="font-style:italic;">
                                    <td class="tx-left" colspan="4"><br>Balance</td>
                                    <td class="tx-right"><br>{{number_format($total_balance, 2)}}</td> 
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
                                    <td class="tx-center " colspan="6">No delivery transaction found.</td>
                                </tr>
                            @endforelse
                           
                            </tbody>
                        </table>
                    </div>
                    <p>Encoded by: {{$sales->agent ?? ''}}</p>

                   
                   
                    <!-- col -->
                  

                </div>
                <!-- row -->
            </div>
            <!-- container -->
        </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        {{--let searchType = "{{ $searchType }}";--}}
    </script>

{{--    <script src="{{ asset('js/listing.js') }}"></script>--}}
@endsection

@section('customjs')
<script>
    function post_form(id,status,pages){

        $('#posting_form').attr('action',id);
        $('#pages').val(pages);
        $('#status').val(status);
        $('#posting_form').submit();
    }

    {{--function cancel_product(id,status){--}}
    {{--    $('#prompt-cancel-product').modal('show');--}}
    {{--    $('#btnCancelProduct').on('click', function() {--}}
    {{--        //let sales = $('#delivery_status').val();--}}
    {{--        post_form("{{route('sales-transaction.cancel_product')}}",status,id)--}}
    {{--        //console.log(status);--}}
    {{--    });--}}
    {{--}--}}

    $('#prompt-cancel-product').on('show.bs.modal', function (e) {
        //get data-id attribute of the clicked element
        let sales = e.relatedTarget;
        let salesId = $(sales).data('id');
        let salesStatus = $(sales).data('status');
        let formAction = "{{ route('sales-transaction.cancel_product', 0) }}".split('/');
        formAction.pop();
        let editFormAction = formAction.join('/') + "/" + salesId;
        $('#editForm').attr('action', editFormAction);
        $('#id').val(salesId);
        $('#editStatus').val(salesStatus);

    });
</script>
@endsection
