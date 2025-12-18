@extends('layouts.guest', ['page' => $page])

@section('title', 'Order History')
@section('meta_description', 'View your order history, track your orders, and manage your account. Stay updated with your purchases and delivery status.')

@section('content')

<div
    x-data="orderHistory"
    @closeBankParentModal.window="closeBankDepositProof"
    x-init="init()">
    <div class="py-20 px-4 container">

        <div class="flex gap-6 lg:flex-row flex-col mt-10">
            <div class="w-full lg:w-1/4">
                <x-account-menu-component />
            </div>
            <div class="w-full lg:w-3/4">
                <div>
                    <div class="font-bold text-lg mb-5">
                        Order History
                    </div>

                    @if (request()->query('payment_successful'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                        <span class="font-medium">Success!</span> Your payment was successfully processed. Thank you.
                    </div>
                    @endif

                    @if (request()->query('order_cancelled'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                        <span class="font-medium">Important</span> The payment transaction you processed was unsuccessful.
                        <p class="mb-0">If you wish to continue with your order, please click on the corresponding Pay icon <i class="fa fa-credit-card"></i> of Order#: <i style="font-weight:bold;">{{$_GET['order_no']}}</i></p>
                    </div>
                    @endif

                    @if(Session::has('success_cancelled'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                        <span class="font-medium">Order Cancelled!</span> Your order has been successfully cancelled.
                    </div>
                    @endif
                    
                    @if (count($sales) > 0)
                    @foreach ($sales as $index => $sale)
                    <div x-data="{ viewMore{{ $index }}: false }" class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mb-5 {{ $sale->status == 'CANCELLED' ? 'opacity-50 bg-gray-200' : 'bg-white' }}">
                        <div class="px-6 py-4 border-b border-[#DFDFDF] flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <h2 class="font-semibold {{ $sale->status == 'CANCELLED' ? 'line-through' : '' }}">Order #{{ $sale->order_number }}</h2> 
                                <span class="{{ $sale->status == 'CANCELLED' ? 'text-red-700 uppercase' : 'hidden' }}">{{ $sale->status }}</span>
                            </div>
                            <div class="font-semibold text-tertiary uppercase {{ strtolower($sale->payment_status) == 'unpaid' ? 'text-red-500' : '' }}">
                                {{ $sale->payment_status }}
                            </div>
                        </div>
                        <div class="flex items-start flex-col gap-2  py-5 border-b border-[#DFDFDF]">
                            @if ($sale->items->count() > 0)                
                            <div class="flex flex-col items-center gap-8 px-4 py-3 border-b border-[#DFDFDF] w-full">
                                @php
                                    $total = 0;
                                    $fee = $sale->delivery_type == 'Door to door delivery' ? $sale->delivery_fee_amount : 0;
                                @endphp
                                @if ($sale->items->count() > 0)
                                @foreach ($sale->items as $cart)
                                    @php
                                        $itemTotal = $cart->net_amount;
                                        $total += $itemTotal;
                                    @endphp
                                    <div class="flex gap-4 items-start w-full relative">
                                        <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="w-20 h-20 min-w-20 min-h-20 object-cover overflow-hidden rounded-md bg-center">
                                            <img 
                                                onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}'" 
                                                src="{{ $cart?->product?->photos->last()?->path ? asset('storage/products/' . $cart?->product?->photos->last()->path) : asset('images/no-image.jpg') }}" 
                                                alt="{{ $cart['name'] ?? $cart?->product?->name }}"
                                                class="w-20 h-20 object-cover">
                                        </div>
                                        <div class="flex flex-col">
                                            <div class="font-bold">{!! highlightPaella($cart?->product_name ?? '') !!} @if ($cart->price == 0)<span class="text-green-600 font-semibold text-sm">(Free)</span>@endif</div>
                                            <div class="text-sm text-gray-600 font-medium">Price: ₱{{ number_format($cart->price, 2) }} <span class="italic">{{ isset($cart?->paella_price) && $cart?->paella_price > 0 ? '+ ₱' . number_format($cart?->paella_price, 2) : '' }}</span></div>
                                            <div class="text-sm text-gray-600 font-medium">QTY: {{ number_format($cart->qty, 0) }}</div>
                                        </div>
                                        <div class="text-sm text-black font-bold text-right w-full absolute right-0 bottom-0">₱{{ number_format($itemTotal, 2) }}</div>
                                    </div>
                                @endforeach
                                @else
                                    <div class="text-sm text-gray-600">No items in this order.</div>
                                @endif

                                @php
                                    $amountPaid = ($sale->payments && count($sale->payments) > 0) ? $sale->payments->sum('amount') : 0;
                                    $amountPaid = $amountPaid < 0 ? 0 : $amountPaid;
                                    $cartTotal = $total;
                                    $total += $fee;
                                    $discount = $sale->discount_amount ? $sale->discount_amount : 0;
                                    $balance = ($total - $amountPaid) - $discount;
                                    $balance = $balance < 0 ? 0 : $balance;
                                @endphp
            
                                <div class="flex items-center justify-between w-full mt-4">
                                    <div class="text-sm text-black font-bold">{{ count($sale->items) }} items</div>
                                    <div class="text-sm text-black font-bold">₱{{ number_format($cartTotal, 2) }}</div>
                                </div>
            
                                <div class="flex items-center justify-between w-full mt-4">
                                    <div class="text-sm text-slate-500 font-bold">{{ \Carbon\Carbon::parse($sale->created_at)->format('m/d/Y h:i A') }}</div> 
                                    <button class="text-sm text-primary uppercase font-bold flex gap-1 hover:underline" @click="viewMore{{ $index }} = !viewMore{{ $index }}; $event.stopPropagation()">
                                        VIEW ALL DETAILS
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <template x-if="viewMore{{ $index }}">
                                    <div class="flex flex-col gap-2 w-full">
                                        <div class="flex items-center justify-between w-full ">
                                            <div class="text-sm text-black font-bold">Sub total</div>
                                            <div class="text-sm text-black font-bold">₱{{ number_format($cartTotal, 2) }}</div>
                                        </div>
                                        @if ($sale->delivery_type == 'Door to door delivery')
                                        <div class="flex items-center justify-between w-full ">
                                            <div class="text-sm text-black font-bold">Total Delivery fee</div>
                                            <div class="text-sm text-black font-bold">₱{{ number_format($fee, 2) }}</div>
                                        </div>
                                        @endif
                                        @if ($sale->discount_amount && $sale->discount_amount > 0)
                                        <div class="flex items-center justify-between w-full">
                                            <div class="text-sm text-black font-bold">Discount</div>
                                        </div>
                                        @endif

                                        @if ($sale->couponUsed && count($sale->couponUsed) > 0 && $sale->discount_amount > 0)
                                        <ul class="italic">
                                            @foreach ($sale->couponUsed as $coupon)
                                                <li class="pl-4 flex items-center text-sm justify-between">
                                                    <div>{{ $coupon->coupon_code }}</div>
                                                    <div class="text-right text-red-500 italic">
                                                    @if ($coupon?->coupon?->free_product_id)
                                                        <span class="text-green-500">Free Products </span>
                                                        @php $products = explode('|', $coupon->coupon->free_product_id); @endphp
                                                        <ul class="mt-2">
                                                            @foreach ($products as $productId)
                                                                @php $product = \App\Models\Product::find($productId); @endphp
                                                                @if ($product)
                                                                    <li class="text-green-500">
                                                                        {{ $product->name }}
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div>-₱{{ number_format($coupon->discount_used ?? 0, 2) }}</div>
                                                    @endif

                                                </li>
                                            @endforeach
                                        </ul>
                                        @endif

                                        @if ($sale->net_amount && $sale->net_amount > 0)
                                        <div class="flex items-center justify-between w-full">
                                            <div class="text-sm text-black font-bold">Total</div>
                                            <div class="text-sm font-bold">₱{{ number_format($total <= 0 ? 0 : $total, 2) }}</div>
                                        </div>
                                        @endif
                                        @if ($sale->payments && count($sale->payments) > 0)
                                        <div class="flex items-center justify-between w-full">
                                            <div class="text-sm text-black font-bold">Amount Paid</div>
                                            <div class="text-sm text-red-600 font-bold italic">- ₱{{ number_format($amountPaid <= 0 ? 0 : $amountPaid, 2) }}</div>
                                        </div>
                                        @endif

                                        @if (strtolower($sale->payment_status) != 'paid')
                                        <div class="flex items-center justify-between w-full">
                                            <div class="text-sm text-black font-bold">Amount to pay</div>
                                            <div class="text-sm text-black font-bold">
                                                ₱{{ number_format($balance <= 0 ? 0 : $balance, 2) }}
                                            </div>
                                        </div>
                                        @endif

                                        <div class="mt-5">
                                            <div class="text-sm text-slate-500 font-bold mb-3">Order Details</div>
                                            <p class="mg-b-3 tx-semibold">@if($sale->user_id == 9999) {{$sale->customer_name}} @else {{$sale->user->FullName}} @endif</p>                  
                                            <p class="mg-b-3">Mobile No: {{$sale->customer_contact_number ?? $sale->user->contact_mobile }}</p>
                                            <p class="mg-b-3">Email: {{$sale->email ?? $sale->user->email}}</p>
                                            <p class="mg-b-3 mt-5">
                                                <div class="mt-1">
                                                @if ($sale->delivery_type == 'Door to door delivery' && $sale->has_sub == 1)
                                                    @php
                                                        $salesHeaders = \App\EcommerceModel\SalesHeader::where('parent_sales_header_id', $sale->id)->get();
                                                        $addresses = [];

                                                        foreach ($salesHeaders as $header) {
                                                            $address = json_decode($header->deliveryAddress);
                                                            if ($address) {
                                                                $addresses[] = $address;
                                                            }
                                                        }

                                                    @endphp
                                                    @if ($addresses && count($addresses) > 0)
                                                    <ul class="list-disc pl-10">
                                                        @foreach ($addresses as $k => $row)
                                                            @php
                                                                $address = is_array($row) ? ($row[0] ?? null) : $row;
                                                                if (!$address) continue;

                                                                $products = json_decode($address->products ?? '[]') ?: [];

                                                                $totalQty = collect($products)->sum('qty');
                                                            @endphp

                                                            <li>
                                                                Date: {{ \Carbon\Carbon::parse($address->delivery_date)->format('F d, Y') }}<br>
                                                                Time: {{ \Carbon\Carbon::parse($address->delivery_time)->format('h:i A') }}<br>
                                                                Name: {{ $address->contact_person ?? $sale->customer_name }}<br>
                                                                Contact #: {{ $address->contact_tel ?? $sale->customer_contact_number }}<br>
                                                                QTY/Size: {{ $totalQty }}<br>
                                                                Delivery/Pickup: {{ $sale->delivery_type }}<br>
                                                                Note: {{ $address->note ?? '' }}<br>
                                                                Address: {{ $address->address }}<br>
                                                                Location: {{ $address->location }}<br>
                                                                Delivery charge: ₱{{ number_format((float)$address->delivery_fee, 2) }}<br>

                                                                Order/s:
                                                                @if (!empty($products))
                                                                    <ul class="list-disc pl-10">
                                                                        @foreach ($products as $product)
                                                                            @php
                                                                                $base   = (float)($product->product->price ?? 0);
                                                                                $addOn  = !empty($product->paella) ? (float)($product->product->paella_price ?? 0) : 0;
                                                                                $price  = $base + $addOn;
                                                                            @endphp
                                                                            <li>
                                                                                {!! highlightPaella($product->product_name ?? '') !!} x {{ $product->qty }}
                                                                                - ₱{{ number_format($price, 2) }}
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif

                                                                @php
                                                                    $payment = \App\EcommerceModel\SalesPayment::where('sales_header_id', $sale->id)
                                                                        ->where('status', 'PAID')->latest()->first();
                                                                @endphp
                                                                @if ($payment)
                                                                    Payment type: {{ optional($sale->payments->first())->payment_type }}<br>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    @else
                                                        @php 
                                                            $saleDetail = $sale->items ? $sale->items->first() : null;
                                                            $deliveryDate = $saleDetail ? date('F d, Y h:i A', strtotime($saleDetail?->delivery_date)) : 'N/A';
                                                        @endphp
                                                            Date: {{ \Carbon\Carbon::parse($saleDetail?->delivery_date)->format('F d, Y') }}<br>
                                                            Time: {{ \Carbon\Carbon::parse($saleDetail?->delivery_date)->format('h:i A') }}<br>
                                                            Name: {{ $saleDetail?->contact_person ?? $sale->customer_name }}<br>
                                                            Contact #: {{ $saleDetail?->contact_tel ?? $sale->customer_contact_number }}<br>
                                                            QTY/Size: {{ count($sale->items) }} <br>
                                                            Delivery/Pickup: {{ $sale->delivery_type }}<br>
                                                            Note: {{ $sale?->instruction ?? 'N/A' }}<br>
                                                            Delivery charge: ₱{{ number_format($fee, 2) }}<br>
                                                    @endif
                                                @else 
                                                    @php 
                                                        $saleDetail = $sale->items ? $sale->items->first() : null;
                                                        $deliveryDate = $saleDetail ? date('F d, Y h:i A', strtotime($saleDetail?->delivery_date)) : 'N/A';
                                                    @endphp
                                                    Date: {{ \Carbon\Carbon::parse($saleDetail?->delivery_date)->format('F d, Y') }}<br>
                                                    Time: {{ \Carbon\Carbon::parse($saleDetail?->delivery_date)->format('h:i A') }}<br>
                                                    Name: {{ $saleDetail?->contact_person ?? $sale->customer_name }}<br>
                                                    Contact #: {{ $saleDetail?->contact_tel ?? $sale->customer_contact_number }}<br>
                                                    QTY/Size: {{ count($sale->items) }} <br>
                                                    Delivery/Pickup: {{ $sale->delivery_type }}<br>
                                                    Note: {{ $sale?->instruction ?? 'N/A' }}<br>
                                                    Delivery charge: ₱{{ number_format($fee, 2) }}<br>
                                                @endif
                                                </div>
                                            </p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            @else
                            <div class="flex items-center justify-center w-full py-4">
                                <div class="text-sm text-gray-600">No items in this order.</div>
                            </div>
                            @endif
                            @if ($sale->items->count() > 0)
                            <div class="w-full flex flex-col gap-2 px-4 mt-4 lg:flex-row justify-between">
    
                                {{-- Left side: Cancel Order --}}
                                <div class="lg:order-1 order-2 w-full lg:w-auto {{ $sale->status == 'CANCELLED' ? 'invisible' : '' }}">
                                    @if (strtolower($sale->payment_status) != 'paid')
                                        <button @click="cancelOrderModal = true; saleId = '{{ $sale->id }}'" type="button"
                                            class="text-white custom-btn btn-tertiary-dark bg-tertiary hover:bg-secondary font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                            Cancel Order
                                        </button>
                                        <button @click="editOrderModal = true; saleId = '{{ $sale->id }}'" type="button"
                                            class="text-white custom-btn btn-primary-dark bg-indigo-600 hover:bg-indigo-500 font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                            Edit Order
                                        </button>
                                    @endif
                                </div>
                            
                                {{-- Right side: Pay Now and Track Order --}}
                                <div class="flex flex-col lg:flex-row gap-2 order-1 lg:order-2 justify-end w-full lg:w-auto" x-data="{ dropdownOpen: false }">
                                    @if (strtolower($sale->payment_status) == 'paid')
                                        @if ($sale->subHeaders && count($sale->subHeaders) > 0)
                                        <div class="relative inline-block">
                                            <button
                                                type="button"
                                                @click="dropdownOpen = !dropdownOpen;"
                                                class="flex gap-2 custom-btn btn-primary-dark group text-primary border hover:text-white border-primary bg-white hover:bg-primary-dark font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center"
                                            >
                                            Track Order
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-primary group-hover:text-white">
                                                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                            </svg>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <div
                                                x-cloak
                                                x-show="dropdownOpen"
                                                @click.outside="dropdownOpen = false"
                                                x-transition
                                                class="absolute right-0 mt-2 z-10 bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-96"
                                            >
                                                <ul class="py-2 text-sm text-gray-700">
                                                    @foreach ($sale->subHeaders as $subHeader)
                                                    <li>
                                                        <button @click="trackOrder({{ $subHeader }})" class="block w-full text-left px-4 py-2 hover:bg-gray-100"><strong>#{{ $subHeader->order_number }}</strong> -  {{ $subHeader->customer_address }}</button>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        @else 
                                            <button
                                                type="button"
                                                @click="trackOrder({{ $sale }})"
                                                class="custom-btn btn-primary-dark text-primary border hover:text-white border-primary bg-white hover:bg-primary-dark font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center"
                                            >
                                            Track Order
                                            </button>
                                        @endif
                                    @endif
                                    <a href="{{ route('confirmation', ['id' => $sale->id ])}}"
                                        class="text-white bg-slate-500 custom-btn btn-primary-dark font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center self-start">
                                        View
                                    </a>
                                    @if (strtolower($sale->payment_status) != 'paid')
                                    <button @click="openPaymentModal({{$balance}}, '{{ $sale->order_number }}')" type="button"
                                        class="{{ $sale->status == 'CANCELLED' ? 'hidden' : '' }} text-white bg-primary custom-btn btn-primary-dark font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                        Pay Now
                                    </button>
                                    @endif
                                </div>
                            
                            </div>
                            @endif
                            
                        </div>
                    </div>
                    @endforeach
                    @else
                        <div class="w-full flex justify-center mb-10">
                            <div class="mt-6 px-6 flex items-center justify-center flex-col h-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                <div class="font-bold text-lg">Your cart is empty</div>
                
                                <a href="{{ route('lechon-menu') }}" class="bg-primary custom-btn btn-primary-dark text-white text-center px-6 py-3 rounded-md mt-4 w-full">Continue Shopping</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

<div x-show="successPaymentModal"
    x-transition
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

    <!-- Modal content -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg pb-5">
                <!-- Modal body -->
                <div class="bg-white px-4 pt-5 pb-4 p-6">
                    <div class="flex w-full flex-col">
                        <div class="flex justify-end ">
                            <button @click="successPaymentModal = false" class="self-end text-2xl text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mb-2 rounded-full size-10 bg-primary flex items-center justify-center mx-auto text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>

                            <h3 class="text-lg font-semibold text-center w-full" id="modal-title">Order successfully paid!</h3>
                            <div class="mt-2 text-center w-full">
                                <p class="text-sm text-gray-500">Thank you for your payment! We will verify it and notify you once the payment is confirmed and your order is being processed.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div x-show="cancelOrderModal"
    x-transition
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

    <!-- Modal content -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                <!-- Modal body -->
                <div class="bg-white px-4 pt-5 pb-4 p-6">
                    <div class="flex w-full flex-col">
                        <div class="flex justify-end ">
                            <button @click="cancelOrderModal = false" class="self-end text-2xl text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg lg:text-2xl font-semibold" id="modal-title">Are you sure you want to cancel your order?</h3>
                            <div class="mt-2">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full flex flex-col gap-2 px-10 pt-4 pb-6">

                    <form action="{{ route('my-account.cancel_order') }}" method="post">
                        @csrf
                        <input type="hidden" name="sales_id" x-model="saleId">
                        <div class="flex flex-col gap-2">
                            <button type="submit"
                                class="text-primary border hover:text-white border-primary bg-white hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3 text-center">
                                Yes
                            </button>
                            <button type="button" @click="cancelOrderModal = false"
                                class="text-white bg-primary hover:bg-primary font-medium rounded-lg w-full sm:w-auto px-5 py-3 text-center">
                                No
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div x-show="trackOrderModal"
    x-transition
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

    <!-- Modal content -->
    <div class="fixed inset-0 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
          <!-- Scrollable content container -->
          <div class="relative transform overflow-y-auto h-auto rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg pb-5">
            <!-- Modal body -->
                <div class="bg-white px-4 pt-5 pb-4 p-6">
                    <div class="flex w-full flex-col">
                        <div class="flex justify-end ">
                            <button @click="trackOrderModal = false" class="self-end text-2xl text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="text-left sm:mt-0 sm:ml-4 sm:text-left">
                            <div class="font-bold text-xl">Track Order</div>
                            <div class="text-slate-500 font-semibold mt-2" x-text="'Order #' + saleId"></div>
                            <div class="text-slate-500 font-semibold" x-text="saleDate"></div>
                            <div class="mt-5 px-4">
                                <ol class="relative border-s border-gray-200 dark:border-gray-700">
                                    <template x-for="(key, index) in Object.keys(statusMap)" :key="index">
                                        <li class="mb-10 ms-0 relative pl-8" x-data="{
                                            isCompleted() {
                                                return deliveryStatuses.some(s => s.status === key);
                                            },
                                            completedAt() {
                                                const entry = deliveryStatuses.find(s => s.status === key);
                                                return entry ? formatDate(entry.created_at) : '';
                                            },
                                            driverName() {
                                                const entry = deliveryStatuses.find(s => s.status === 'In Transit');
                                                return entry ? entry.delivered_by_name : 'No assigned driver';
                                            },
                                        }">
                                            <!-- ICON -->
                                            <span
                                                class="absolute flex items-center justify-center w-10 h-10 border rounded-full -start-5 p-1"
                                                :class="isCompleted() ? 'bg-[#CFEDD6] border-primary' : 'bg-[#ECECEC] border-[#ACACAC]'"
                                                x-html="getIcon(statusMap[key].icon, isCompleted() ? 'size-6 text-primary' : 'size-6 text-[#ACACAC]')"
                                            ></span>

                                            <!-- TITLE -->
                                            <h3 class="mb-1 font-semibold" :class="isCompleted() ? 'text-primary' : 'text-[#ACACAC]'" x-text="statusMap[key].title"></h3>

                                            <!-- TIME -->
                                            <template x-if="isCompleted()">
                                                <time class="block mb-2 text-xs text-[#717171] float-right mt-1" x-text="completedAt()"></time>
                                            </template>

                                            <!-- SUBTITLE -->
                                            <p class="text-sm" :class="isCompleted() ? 'text-[#717171]' : 'text-[#ACACAC]'" x-text="statusMap[key].subtitle"></p>
                                            <template x-if="statusMap[key].title == 'In Transit' && isCompleted()">
                                                <p class="text-sm text-[#ACACAC]">Driver: <span x-text="driverName()"></span></p>
                                            </template>
                                        </li>
                                    </template>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  
<div x-show="paymentMethodModal"
    x-transition
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

    <!-- Modal content -->
    <div class="fixed inset-0 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
          <!-- Scrollable content container -->
          <div class="relative transform overflow-y-auto h-auto rounded-lg bg-cream text-left shadow-xl transition-all w-full max-w-xl pb-5">
            <!-- Modal body -->
            <div class="bg-cream px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="w-full">
                        <div class="flex justify-end">
                            <button @click="paymentMethodModal = false" class="self-end text-2xl text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="text-left sm:mt-0 sm:ml-4 sm:text-left">
                            <div class="font-bold text-xl">Choose Payment Method</div>

                            <div class="flex flex-col gap-2 mt-5">
                                <button 
                                    :class="[
                                        'bg-white border rounded-md p-3',
                                        paymentMethod === 'bank' ? 'border-primary ring-2 ring-primary' : 'border-border'
                                    ]"
                                    @click="paymentMethod = 'bank'" class="bg-white border-border border rounded-md p-3">
                                    <div class="flex justify-between items-center"> 
                                        <div class="font-semibold">
                                            Bank Transfer or Deposit 
                                        </div>
                                        <div>
                                            <img src="{{ asset('images/bdo.png') }}" alt="bdo" class="h-4">
                                        </div>
                                    </div>
                                </button>
                                <button class="primary-btn bg-white border-border border rounded-md p-3">
                                    <div class="flex justify-between items-center"> 
                                        <div class="font-semibold">
                                            GCash
                                        </div>
                                        <div>
                                            <img src="{{ asset('images/gcash.png') }}" alt="gcash" class="w-14">
                                        </div>
                                    </div>
                                </button>
                                <button class="primary-btn bg-white border-border border rounded-md p-3">
                                    <div class="flex justify-between items-center"> 
                                        <div class="font-semibold">
                                            Maya
                                        </div>
                                        <div>
                                            <img src="{{ asset('images/maya.png') }}" alt="maya" class="w-14">
                                        </div>
                                    </div>
                                </button>
                                <button class="primary-btn bg-white border-border border rounded-md p-3">
                                    <div class="flex justify-between items-center"> 
                                        <div class="font-semibold">
                                            Credit/Debit Card
                                        </div>
                                        <div>
                                            <img src="{{ asset('images/cc.png') }}" alt="cc" class="w-24">
                                        </div>
                                    </div>
                                </button>
                                <button
                                    :class="[
                                        'bg-white border rounded-md p-3',
                                        paymentMethod === 'paymentCenter' ? 'border-primary ring-2 ring-primary' : 'border-border'
                                    ]"
                                    @click="paymentMethod = 'paymentCenter'" class="bg-white border-border border rounded-md p-3">
                                    <div class="flex justify-between items-center"> 
                                        <div class="font-semibold">
                                            Payment Center
                                        </div>
                                        <div>
                                            <img src="{{ asset('images/ml.png') }}" alt="ml" class="w-28">
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <button @click="choosePaymentMethod" type="button"
                                class="text-white bg-primary hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center mt-5">
                                Choose
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div x-show="depositModal"
    x-transition
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

    <!-- Modal content -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg pb-5">
                <!-- Modal body -->
                <div class="">

                    <div class="flex justify-between items-center px-3 pt-3">
                        <div class="flex gap-2 items-center">
                            <div class="text-2xl font-bold">Amount to pay</div>
                        </div>
                        <button @click="depositModal = false" class="self-end text-2xl text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
        
                    <div class="text-gray-600 font-medium px-4 mt-4">
                        To complete your order, please enter the amount you wish to pay. You can choose to pay the full amount or a partial amount.
                    </div>
        
                    <div class="px-4 mt-5">
                        <div>
                            <form
                                x-data="{ isFormSubmitting: false }"
                                @submit="isFormSubmitting = true; setTimeout(() => { this.depositModal = true}, 3000)"
                                action="{{ route('paymaya.paytest') }}" method="POST" enctype="multipart/form-data" class="flex flex-col">
                                
                                {{-- action="{{ route('paymaya.pay') }}" method="POST" enctype="multipart/form-data" class="flex flex-col"> --}}
                                @csrf
                                <input type="hidden" name="sales_header_id" x-model="sales_header_id">
                    
                                <div class="pb-4">
                                    <img src="{{ asset('images/payment/pay-maya.jpg') }}">
                                </div>

                                <!-- GCash / PayMaya -->
                                <div>
                                    <label class="font-semibold block mb-1">PayMaya:</label>
                                    <select name="pamenty_mode" id="pamenty_mode_gpay" required class="border-gray-300 rounded-md w-full p-2">
                                        <option value="PayMaya">PayMaya</option>
                                    </select>
                                </div>

                                <input type="hidden" id="payment_dt" name="payment_dt">
                                <input type="hidden" id="ref_no" name="ref_no">
                    
                                <!-- Amount -->
                                <div class="mt-4">
                                    <label class="font-semibold block mb-1">Amount to Pay:</label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md">
                                            ₱
                                        </span>
                                        <input required name="amount" x-model="amount" type="text" id="money" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full border-gray-300 p-2.5  " placeholder="">
                                    </div>
                                </div>
                    
                                <!-- Submit Button -->
                                <div class="text-right mt-4">
                                    <button :disabled="isFormSubmitting" type="submit" class="bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-2 rounded-md">
                                        <span x-show="!isFormSubmitting">Submit</span>
                                        <span x-show="isFormSubmitting" class="flex items-center justify-center gap-2">
                                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                            </svg>
                                            Submitting...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div x-show="editOrderModal"
    x-transition
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

    <!-- Modal content -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                <!-- Modal body -->
                <div class="bg-white px-4 pt-5 pb-4 p-6">
                    <div class="flex w-full flex-col">
                        <div class="flex justify-end ">
                            <button @click="editOrderModal = false" class="self-end text-2xl text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg lg:text-2xl font-semibold" id="modal-title">You will now redirect to Menu to Add more Product!</h3>
                            <div class="mt-2">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full flex flex-col gap-2 px-10 pt-4 pb-6">

                    <form action="{{ route('my-account.edit_order') }}" method="post">
                        @csrf
                        <input type="hidden" name="sales_id" x-model="saleId">
                        <div class="flex flex gap-2">
                            <button type="submit"
                                class="text-primary border hover:text-white border-primary bg-white hover:bg-primary-dark font-medium rounded-lg w-full px-5 py-3 text-center">
                                Yes
                            </button>
                            <button type="button" @click="editOrderModal = false"
                                class="text-white bg-primary hover:bg-primary font-medium rounded-lg w-full px-5 py-3 text-center">
                                No
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<x-bank-deposit-proof />
<x-payment-center-proof />
<x-footer-component />

<script>
    function orderHistory() {
        return {
            init() {
                const lockBody = () => {
                    const anyOpen = this.cancelOrderModal || this.successPaymentModal || this.trackOrderModal || this.paymentMethodModal || this.bankDepositProof || this.paymentCenterProof || this.editOrderModal || this.depositModal;
                    if (anyOpen) {
                        document.body.classList.add('overflow-hidden');
                    } else {
                        document.body.classList.remove('overflow-hidden');
                    }
                };

                this.$watch('trackOrderModal', lockBody);
                this.$watch('cancelOrderModal', lockBody);
                this.$watch('successPaymentModal', lockBody);
                this.$watch('paymentMethodModal', lockBody);
                this.$watch('bankDepositProof', lockBody);
                this.$watch('paymentCenterProof', lockBody);
                this.$watch('editOrderModal', lockBody);
            },
            depositModal: false,
            cancelOrderModal: false, 
            editOrderModal: false,
            successPaymentModal: false, 
            trackOrderModal: false, 
            paymentMethodModal: false, 
            bankDepositProof: false, 
            paymentCenterProof: false,
            paymentMethod: '',
            choosePaymentMethod() {
                if (this.paymentMethod == '') {
                    return;
                }

                if (this.paymentMethod == 'bank') {
                    this.bankDepositProof = true;
                } else if (this.paymentMethod == 'paymentCenter') {
                    this.paymentCenterProof = true;
                }

            },
            closeBankDepositProof() {
                this.bankDepositProof = false;
            },
            openPaymentModal(amount, sales_header_id) {
                this.depositModal = true;
                this.amount = amount;
                this.sales_header_id = sales_header_id;
            },
            trackOrder(sale) {
                console.log(sale)
                this.trackOrderModal = true;
                this.saleId = sale.order_number;
                this.saleDate = this.formatDate(sale.created_at);

                this.deliveryStatuses = sale.delivery_status;
            },
            deliveryStatuses: [],
            saleDate: '',
            sales_header_id: '',
            amount: '',
            saleId: '',
            formatDate(dateString) {
                const date = new Date(dateString);

                const month = String(date.getMonth() + 1).padStart(2, '0'); // months are 0-indexed
                const day = String(date.getDate()).padStart(2, '0');
                const year = date.getFullYear();

                let hours = date.getHours();
                const minutes = String(date.getMinutes()).padStart(2, '0');

                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // 0 should be 12
                const formattedHours = String(hours).padStart(2, '0');

                return `${month}/${day}/${year} ${formattedHours}:${minutes} ${ampm}`;
            },
            statusMap: {
                'Open Date': {
                    title: 'Open Date',
                    subtitle: 'Waiting to open order.',
                    icon: 'calendar-days',
                },
                'Scheduled for Processing': {
                    title: 'Scheduled for Processing',
                    subtitle: 'Waiting for your payment confirmation.',
                    icon: 'receipt-percent',
                },
                'Processing': {
                    title: 'Processing',
                    subtitle: 'We are currently preparing your order.',
                    icon: 'cog-6-tooth',
                },
                'In Transit': {
                    title: 'In Transit',
                    subtitle: 'Your order is on the way.',
                    icon: 'truck',
                },
                'Delivered/Picked Up': {
                    title: 'Delivered/Picked Up',
                    subtitle: 'Your order has been delivered or picked up.',
                    icon: 'check-circle',
                },
                'Returned/Rejected': {
                    title: 'Returned/Rejected',
                    subtitle: 'Your order was returned or rejected.',
                    icon: 'arrow-uturn-left',
                },
            },
            getIcon(iconName) {
                switch(iconName) {
                    case 'calendar-days':
                        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                </svg>`;
                    case 'receipt-percent':
                        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 14.25 6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185ZM9.75 9h.008v.008H9.75V9Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm4.125 4.5h.008v.008h-.008V13.5Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>`;
                    case 'cog-6-tooth' :
                        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>`;
                    case 'archive-box': 
                        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg>`;
                    case 'truck': 
                        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>`;
                    case 'check-circle': 
                        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>`;
                    case 'arrow-uturn-left': 
                        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                </svg>`;
                }
            }
        }
    }
</script>

@endsection