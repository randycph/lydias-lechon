@extends('layouts.guest', ['page' => $page])

@section('title', 'Order Confirmation')
@section('meta_description', 'Thank you for ordering with us! Your delicious Lydia\'s Lechon meal is on its way. We’ll send you an update once it’s ready for pickup or delivery. Your order details has also been sent to your email. Enjoy!')

@section('content')

    <div x-data="{ expanded: false }" class="bg-cream">
        <div class="pb-10 px-4 container">
            <div class="pt-20 pb-5 px-4 flex flex-col justify-start">
                <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary mt-10">order confirmation</h1>
                <h3 class="font-medium text-base lg:text-xl mt-2">Thank you for ordering with us! Your delicious Lydia's Lechon meal is on its way. We’ll send you an update once it’s ready for pickup or delivery. Your order details has also been sent to your email. Enjoy!</h3>
                <div class="font-medium text-base lg:text-xl mt-2 lg:mt-8">For any inquiries, you may call our <strong class="text-primary cursor-pointer underline" @click="openHotline = true">hotline</strong> or <strong class="text-primary cursor-pointer underline" @click="openContactUs = true">contact us</strong>.</div>
            </div>

            <div class="flex flex-col px-4  lg:flex-row gap-4 mt-5 w-full max-w-lg justify-start">
                <a href="{{ route('lechon-menu') }}" class="primary-btn bg-primary border-primary border text-white px-6 py-4 w-full rounded-md text-center">Go Shopping</a>
                <div class="border border-primary text-primary px-6 py-4 w-full text-center rounded-md">
                    <a href="{{ route('order-history') }}" class="text-center">View Order History</a>
                </div>
            </div>

            @if (count($salesDetails) > 0)
    
            <div class="flex flex-col lg:flex-row gap-4 w-full justify-start px-4">
                <div class="rounded-lg border bg-white border-gray-200 shadow-md mt-10 w-full lg:w-1/2">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h2 class="text-lg lg:text-3xl font-semibold text-left">Order Details</h2>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Order Number</div>
                        <div class="font-bold">{{ $sales->order_number }}</div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Payment Status</div>
                        <div class="font-bold {{ strtolower($sales->PaymentStatus) == 'paid' ? 'text-primary' : 'text-yellow-500' }}">{{ $sales->PaymentStatus }}</div>
                    </div>
                    @if ($sales->delivery_type == 'Door to door delivery')
                    <div class="flex items-center text-sm  justify-between px-4 py-3 border-b border-gray-200">
                        <div>Delivery Status</div>
                        <div class="font-bold text-primary">{{ $sales->delivery_status ?? 'NA' }}</div>
                    </div>
                    @endif
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Order Date</div>
                        <div class="text-right">
                            <div>{{ \Carbon\Carbon::parse($sales->created_at)->format('F d, Y') }}</div>
                            <div>{{ \Carbon\Carbon::parse($sales->created_at)->format('g:i A') }}</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Order Type</div>
                        <div class="text-right">
                            <div>{{ $sales->delivery_type }}</div>
                            @if ($sales->delivery_type != 'Door to door delivery')
                            <div>{{ $sales->customer_delivery_adress }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Instruction</div>
                        <div class="text-right">
                            <div>{{ $sales->instruction ?? 'NA' }}</div>
                        </div>
                    </div>
                    @if ($sales->delivery_type == 'Door to door delivery')
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Delivery Address</div>
                        <div class="text-right">
                            @if ($sales?->deliveryAddress && count($sales?->deliveryAddress) > 0)
                                <ul class="list-decimal pl-5 flex flex-col gap-6">
                                @foreach ($sales->deliveryAddress as $k => $address)
                                <li>
                                    <strong>Address</strong> {{ $k + 1 }}: {{ $address->address }}<br>
                                    <strong>Contact person</strong>: {{ $address->contact_person }}<br>
                                    <strong>Contact number</strong>: {{ $address->contact_tel }}<br>
                                    <strong>Delivery fee</strong>: ₱{{ number_format($address->delivery_fee, 2) }}<br>
                                    <strong>Location</strong>: {{ $address->location }}<br>
                                    <strong>Delivery Date and time</strong>: {{ date('F d, Y g:i A', strtotime($address->delivery_date . ' ' . $address->delivery_time)) }}<br>
                                    <strong>Order/s</strong>: 
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
                                    @if ($address->note)
                                    <strong>Note</strong>: {{ $address->note ?? 'NA' }}<br>
                                    @endif
                                </li>
                                @endforeach
                                </ul>
                            @else
                                {{ $sales->customer_delivery_adress ?? 'NA' }}
                            @endif
                        </div>
                    </div>
                    @endif
                    @if ($sales->delivery_type != 'Door to door delivery')
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Date and Time Needed</div>
                        <div class="text-right">
                            <div>{{ \Carbon\Carbon::parse($sales?->items?->first()?->delivery_date)->format('F d, Y g:i A') }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Customer Name</div>
                        <div class="text-right">
                            <div>{{ $sales->customer_name }}</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Mobile Number</div>
                        <div class="text-right">
                            <div>{{ $sales->customer_contact_number }}</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Email Address</div>
                        <div class="text-right">
                            <div>{{ $sales->email }}</div>
                        </div>
                    </div>
                </div>

                @php
                    $total = 0;
                    $deliveryFee = 0;
                    if (count($salesDetails) > 0) {
                        foreach ($salesDetails as $detail) {
                            $total += $detail['price'] * $detail['qty'];
                        }
                    }
                    $colspan = 6;
                @endphp
                    
                <div class="rounded-lg border bg-white border-gray-200 shadow-md mt-10 w-full lg:w-1/2">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h2 class="text-lg lg:text-3xl font-semibold text-left">Order Summary</h2>
                    </div>
                    
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>{{ count($salesDetails) }} items</div>
                        <div class="font-bold">₱{{ number_format($total, 2)}}</div>
                    </div>
                
                
                    <!-- Order Items -->
                    <div class="flex flex-col items-center gap-8 px-4 py-3 border-b border-gray-200 w-full">
                        @foreach ($salesDetails as $index => $details)
                            <div class="flex gap-4 items-start w-full relative" 
                                x-show="{{ $index === 0 ? 'true' : 'expanded' }}" 
                                x-transition.duration.300ms>
                                
                                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="w-20 h-20 min-w-20 min-h-20 object-cover overflow-hidden rounded-md bg-center">
                                    <img 
                                        onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}'" 
                                        src="{{ $details?->product?->photos->last()?->path ? asset('storage/products/' . $details?->product?->photos->last()->path) : asset('images/no-image.jpg') }}" 
                                        alt="{{ $details['name'] ?? $details?->product?->name }}"
                                        class="w-20 h-20 object-cover">
                                </div>
                                
                                <div class="flex flex-col">
                                    <div class="font-bold">{{ $details['product_name'] }}</div>
                                    <div class="text-sm text-gray-600 font-medium">QTY: {{ number_format($details['qty'], 0)}}</div>
                                </div>
                                
                                <div class="text-sm text-black font-bold text-right w-full absolute right-0 bottom-0">₱{{ number_format(($details->price * $details->qty), 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                
                    @if (count($salesDetails) > 1)
                    <!-- View All Button -->
                    <button 
                        @click="expanded = !expanded" 
                        class="w-full text-center py-4 font-medium text-gray-400 border-b border-gray-200  flex items-center justify-center gap-1">
                        <span class="text-gray-400" x-text="expanded ? 'HIDE' : 'VIEW ALL'"></span>
                        <svg x-bind:class="expanded ? 'rotate-180' : ''" class="w-6 h-6 transition-transform duration-300 ease-in-out" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    @endif
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Subtotal</div>
                        <div class="text-right">
                            <div>₱{{ number_format(($total), 2) }}</div>
                        </div>
                    </div>
                    @if ($sales->delivery_type == 'Door to door delivery')
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Delivery Fee</div>
                        <div class="text-right">
                            <div>₱{{ number_format($sales->delivery_fee_amount, 2) }}</div>
                        </div>
                    </div>
                    @endif
                    @if ($sales->discount_amount > 0)
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Discount</div>
                        <div class="text-right text-red-500 italic">
                            <div>-₱{{ number_format($sales->discount_amount, 2) }}</div>
                        </div>
                    </div>
                    @endif
                    {{-- <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-gray-200">
                        <div>Coupon (lydiaslechon25)</div>
                        <div class="text-right">
                            <div>-  ₱250</div>
                        </div>
                    </div> --}}
                    <div class="flex items-center text-sm justify-between px-4 py-4 border-b border-gray-200">
                        <div>Total</div>
                        <div class="text-right font-bold">
                            <div>₱{{ number_format($sales->net_amount, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative overflow-x-auto mt-10 px-4 ">
                <div class="mb-2 text-lg font-semibold text-slate-600">
                    Order Summary
                </div>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class=" text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Product Code
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Product Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                No. of Pax
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Paella Price
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesDetails as $details)
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $details->product->code }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $details->product_name }} @if($details->paella_price > 0) with paella @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $details->no_of_pax }}
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($details->qty, 0) }}
                            </td>
                            <td class="px-6 py-4">
                                ₱{{ number_format(($details->paella_price),2) }}
                            </td>
                            <td class="px-6 py-4">
                                ₱{{ number_format($details->price, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                ₱{{ number_format($details->gross_amount, 2) }}
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="text-center">No transaction found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-white ">
                            <td colspan="{{ $colspan }}" class="px-6 py-4 font-bold text-right">Sub total</td>
                            <td class="px-6 py-4 font-bold">₱{{ number_format($sales->gross_amount, 2) }}</td>
                        </tr>
                        @if($sales->delivery_fee_amount > 0)
                        <tr class="bg-white ">
                            <td colspan="{{ $colspan }}" class="px-6 py-4 font-bold text-right">Delivery Fee</td>
                            <td class="px-6 py-4 font-bold">₱{{ number_format($sales->delivery_fee_amount, 2) }}</td>
                        </tr>
                        @endif
                        @forelse($gc as $g)
                        <tr class="bg-white ">
                            <td colspan="{{ $colspan }}" class="px-6 py-4 font-bold text-right">Gift Certificate: {{$g->code}}</td>
                            <td class="px-6 py-4 font-bold">₱{{ number_format($g->amount, 2) }}</td>
                        </tr>
                        @empty
                        @endforelse
                        @if($salesDetails->sum('gross_amount') > 0)
                        <tr class="bg-white border-b border-gray-200">
                            <td colspan="{{ $colspan }}" class="px-6 py-4 font-bold text-right">Total</td>
                            <td class="px-6 py-4 font-bold">₱{{ number_format($sales->gross_amount + $sales->delivery_fee_amount, 2) }}</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>

            <div class="relative overflow-x-auto mt-10 px-4 ">
                <div class="mb-2 text-lg font-semibold text-slate-600">
                    Payments
                </div>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class=" text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Payment Type
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Receipt No
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesPayments as $payment)
                        <tr class="bg-white border-b border-gray-200">
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $payment->payment_type }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $payment->receipt_number }}
                            </td>
                            <td class="px-6 py-4">
                                {{ date('F d, Y', strtotime($payment->payment_date)) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->status=='PENDING' && ($payment->payment_type=='IPAY' || $payment->payment_type=='Paymaya' )) Subject for Confirmation @else {{$payment->status}} @endif
                            </td>
                            <td class="px-6 py-4">
                                ₱{{ number_format($payment->amount, 2) }}
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-4 text-center" colspan="5">No payment found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        @if($salesPayments->sum('amount') > 0)
                        <tr class="bg-white border-b border-gray-200">
                            <td colspan="4" class="px-6 py-4 font-bold text-right">Total</td>
                            <td class="px-6 py-4 font-bold">₱{{ number_format($salesPayments->sum('amount'), 2) }}</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>

            @if ($sales->delivery_type == 'Door to door delivery')
            <div class="relative overflow-x-auto mt-10 px-4 ">
                <div class="mb-2 text-lg font-semibold text-slate-600">
                    Delivery History
                </div>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class=" text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Remarks
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Delivered By
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $delivery)  
                        <tr class="bg-white border-b border-gray-200">
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $delivery->created_at }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $delivery->status }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $delivery->remarks }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $delivery->delivered_by }}
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-4 text-center" colspan="4">No delivery transaction found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
            @else
            <div class="flex items-center justify-center mt-10">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-700">No Order Details Found</h2>
                    <p class="text-gray-500">It seems like there are no order details available for this transaction.</p>
                </div>
            </div>
            @endif
        </div>
        <x-footer-component />
    </div>
    
@endsection
