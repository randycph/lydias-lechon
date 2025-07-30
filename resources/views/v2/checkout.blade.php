@extends('layouts.guest', ['page' => $page])

@section('title', 'Checkout')
@section('meta_description', 'Complete your order at Lydia\'s Lechon. Review your cart, choose delivery or pickup, and finalize your purchase for a delicious meal.')

@section('content')

@php
    $total = 0;
    $deliveryFee = 0;
    if (count($carts) > 0) {
        foreach ($carts as $cart) {
            $paella_price = $cart['paella_price'] > 0 ? $cart['product']['paella_price'] : 0;
            $total += ($cart['price'] + $paella_price) * $cart['qty'];
        }
    }
@endphp

<div class="bg-cream">
    <div 
        x-data="checkoutForm" 
        init="init()" 
        class="container">
        <form
            action="{{ route('cart.temp_sales') }}" 
            method="POST" 
            id="checkoutForm"
            enctype="multipart/form-data"
            @submit.prevent="submitForm" class="pb-20 px-4">
            <div class="pt-20 pb-5 px-4">
                <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center mt-10">Checkout</h1>
                @if ($carts && count($carts) > 0)
                <h3 class="font-medium lg:text-2xl text-center">You're almost there! Review your order details, choose your payment
                    method, and finalize your purchase to enjoy your Lydia's Lechon meal.</h3>
                @endif
            </div>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded-md mb-4">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            @endif

            @if ($carts->isEmpty())
                <div class="flex flex-col items-center justify-center h-96">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <h2 class="text-xl font-semibold mt-4">Your cart is empty</h2>
                    <p class="text-gray-500">Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ route('lechon-menu') }}" class="mt-4 bg-primary text-white px-4 py-2 rounded-md">Start Shopping</a>
                </div>
            @else
            <div class="flex flex-col lg:flex-row gap-4 w-full mt-10">
                
                @csrf
                <div class="w-full order-1 lg:order-2 rounded-lg border bg-white border-[#DFDFDF] shadow-md ">
                    <div class="px-4 py-3 border-b border-[#DFDFDF]">
                        <h2 class="text-lg lg:text-3xl font-semibold text-left">Order Summary</h2>
                    </div>
                    <div class="flex items-center text-sm lg:text-base justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div x-text="carts.length + ' items'"></div>
                        <div class="font-bold" 
                            x-text="'₱' + carts.reduce((sum, item) => 
                                sum + 
                                (Number(item.paella_price) || 0) + 
                                (item.is_free_product ? 0 : (Number(item.price) || 0) * (Number(item.qty) || 0))
                            , 0).toLocaleString(undefined, { minimumFractionDigits: 2 })"

                            {{-- x-text="'₱' + carts.reduce((sum, item) => sum + item.paella_price + (item.is_free_product ? 0 : item.price * item.qty), 0).toLocaleString(undefined, { minimumFractionDigits: 2 })" --}}
                        ></div>
                    </div>
    
                    <div class="flex flex-col items-center gap-4 px-4 py-3 border-b border-[#DFDFDF] w-full">
                        <template x-for="(item, index) in carts" :key="index">
                            <div class="flex gap-4 items-start w-full relative  border-gray-200 py-3">
                                <!-- Image -->
                                <div class="w-20 h-20 min-w-20 min-h-20 bg-center rounded-md overflow-hidden">
                                    <img
                                        x-ref="productImage"
                                        x-init="
                                            let img = $refs.productImage;
                                            img.onerror = () => {
                                                img.src = '{{ asset('images/no-image.jpg') }}';
                                            };
                                            img.src = item?.product?.photos?.length > 0
                                                ? item.product.photos[item.product.photos.length - 1]?.url
                                                : '{{ asset('images/no-image.jpg') }}';
                                        "
                                        :alt="item?.product?.name"
                                        class="w-20 h-20 object-cover rounded-md scale-110"
                                    />
                                </div>

                                <!-- Info -->
                                <div class="flex flex-col flex-grow">
                                    <div class="">
                                        <span class="font-bold" x-text="item?.product?.name"></span> <span class="italic" x-text="parseFloat(item.paella_price) > 0 ? 'with Seafood Paella' : ''"></span>
                                        <template x-if="item.is_free_product">
                                            <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded">FREE</span>
                                        </template>
                                    </div>
                                    <div class="text-sm text-gray-600 font-medium">
                                        Price:
                                        <span x-text="item.is_free_product 
                                            ? '₱0.00' 
                                            : '₱' + parseFloat(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })">
                                        </span>
                                        <span class="italic" x-text="item?.paella_price > 0 ? '+ ₱' + parseFloat(item.product.paella_price * item.qty).toLocaleString(undefined, { minimumFractionDigits: 2 }) : ''"></span>
                                    </div>
                                    <div class="text-sm text-gray-600 font-medium">
                                        QTY: <span x-text="item.qty"></span>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="absolute right-0 bottom-2 text-sm lg:text-base font-bold text-black text-right">
                                    <span x-text="item.is_free_product 
                                        ? '₱0.00' 
                                        : '₱' + ((parseFloat(item.price) + parseFloat(item?.paella_price > 0 ? item?.product?.paella_price || 0 : 0)) * item.qty).toLocaleString(undefined, { minimumFractionDigits: 2 })">
                                    </span>
                                </div>
                            </div>
                        </template>

                    </div>
    
                    <!-- Coupon Code Section -->
                    <div class="bg-white rounded-md mt-2 text-sm">
                        <div class="flex items-center border mx-3 border-gray-200 rounded-md overflow-hidden">
                            <input @input="couponCode = $event.target.value.toUpperCase()" x-model="couponCode" type="text" placeholder="Have a coupon code?"
                                class="w-full p-3 outline-none border-none text-gray-700">
                            <button @click="submitCouponCode" type="button" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 text-sm">Apply</button>
                        </div>
                        <div x-show="couponMessage" class="mx-5 py-2 text-sm"
                            :class="{
                                'text-green-600': couponMessageType === 'success',
                                'text-red-600': couponMessageType === 'error'
                            }"
                            x-text="couponMessage">
                        </div>
    
                        <!-- Subtotal Section -->
                        <div class="border-t border-gray-200 mt-2 pt-3 pb-1 gap-1 flex flex-col text-sm lg:text-base px-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-800">Subtotal</span>
                                <span class="font-medium" 
                                        x-text="'₱' + carts.reduce((sum, item) => 
                                            sum + 
                                            (Number(item.paella_price) || 0) + 
                                            (item.is_free_product ? 0 : (Number(item.price) || 0) * (Number(item.qty) || 0))
                                        , 0).toLocaleString(undefined, { minimumFractionDigits: 2 })"



                                    {{-- x-text="'₱' + carts.reduce((sum, item) => sum + item.paella_price + (item.is_free_product ? 0 : item.price * item.qty), 0).toLocaleString(undefined, { minimumFractionDigits: 2 })" --}}
                                 ></span>
                            </div>
                            <template x-if="deliveryFees.length == 0 && !allowMultiple && method == 'delivery'">
                                <div>
                                    <div class="flex justify-between lg:mt-2">
                                        <span class="font-medium text-gray-800">Delivery Fee</span>
                                        <span class="font-medium" x-text="deliveryFee > 0 ? '₱' + deliveryFee : 'Free'"></span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="deliveryFees.length > 0">
                                <div class="flex flex-col gap-1 mt-2">
                                    <template x-for="(item, i) in deliveryFees" :key="i">
                                        <div class="flex justify-between text-gray-500 text-sm">
                                            <span x-text="'Delivery Fee (' + item.location + ')'"></span>
                                            <span x-text="'₱' + item.fee.toLocaleString(undefined, { minimumFractionDigits: 2 })"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                        <template x-if="coupons.length > 0">
                            <template x-for="(item, i) in coupons" :key="i">
                                <div class="flex justify-between lg:mt-2">
                                    <span class="font-medium text-red-700 italic flex items-center flex-wrap" x-show="item.free_shipping && shippingDiscountAmount > 0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 text-green-600 mr-1">
                                            <path fill-rule="evenodd" d="M4.5 2A2.5 2.5 0 0 0 2 4.5v2.879a2.5 2.5 0 0 0 .732 1.767l4.5 4.5a2.5 2.5 0 0 0 3.536 0l2.878-2.878a2.5 2.5 0 0 0 0-3.536l-4.5-4.5A2.5 2.5 0 0 0 7.38 2H4.5ZM5 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                        </svg>
                                        Coupon (<span x-text="item.code"></span>) 
                                        <span class="text-xs ml-1 underline cursor-pointer" @click="removeCoupon(i)">Remove Coupon</span>
                                    </span>
                                    
                                    <span class="font-medium text-red-700 italic flex items-center flex-wrap" x-show="!item.free_shipping">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 text-green-600 mr-1">
                                            <path fill-rule="evenodd" d="M4.5 2A2.5 2.5 0 0 0 2 4.5v2.879a2.5 2.5 0 0 0 .732 1.767l4.5 4.5a2.5 2.5 0 0 0 3.536 0l2.878-2.878a2.5 2.5 0 0 0 0-3.536l-4.5-4.5A2.5 2.5 0 0 0 7.38 2H4.5ZM5 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                        </svg>
                                        Coupon (<span x-text="item.code"></span>) 
                                        <span class="text-xs ml-1 underline cursor-pointer" @click="removeCoupon(i)">Remove Coupon</span>
                                    </span>

                                    <span class="font-medium italic text-red-700 text-right">
                                        <template x-if="item.free_shipping && shippingDiscountAmount > 0">
                                            <span x-text="'- ₱' + (item.free_shipping_discount_amount == 100 ? deliveryFee : (deliveryFee * item.free_shipping_discount_amount / 100)).toLocaleString(undefined, { minimumFractionDigits: 2 }) + ' (Shipping Discount)'"></span>
                                        </template>
                                        <template x-if="!item.free_shipping && (item.free_products == null || item.free_products.length == 0)">
                                            <span x-text="'- ₱' + (
                                                item.discount_type === 'amount' 
                                                    ? parseFloat(item.discount) 
                                                    : (orderAmount * parseFloat(item.discount) / 100)
                                            ).toLocaleString(undefined, { minimumFractionDigits: 2 }) + ' (Order Discount)'"></span>
                                        </template>
                                        <template x-if="!item.free_shipping && (item.free_products && item.free_products.length > 0)">
                                            <span>Free Products</span>
                                        </template>
                                    </span>
                                </div>
                            </template>
                        </template>

                        </div>
    
                        <div class="border-t border-gray-200 mt-2 py-4 gap-1 flex flex-col text-sm lg:text-base px-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-800">Total</span>
                                <span class="font-bold" x-text="computeTotal()"></span>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="w-full  order-2 lg:order-1 rounded-lg border bg-white border-[#DFDFDF] shadow-md">
                    <div>
                        <div class="px-4 py-3 border-b border-[#DFDFDF]">
                            <h2 class="text-lg lg:text-3xl font-semibold text-left">Delivery Information</h2>
                        </div>
    
                        <div class="my-3 px-4 ">
                            <div class="font-bold my-2">Choose Pickup or Delivery</div>
                            <div class="flex items-center gap-4 mt-2">
                                <button type="button" class="px-6 py-3 rounded-md w-full transition border-2"
                                    :class="method === 'pickup' ? 'bg-green-700 text-white' : 'bg-gray-100 text-gray-700'"
                                    @click="changeMethod('pickup')">
                                    Pickup
                                </button>
    
                                <button type="button" class="px-6 py-3 rounded-md w-full transition border-2"
                                    :class="method === 'delivery' ? 'bg-green-700 text-white' : 'bg-gray-100 text-gray-700'"
                                    @click="changeMethod('delivery')">
                                    Delivery
                                </button>
                            </div>
                            
                            <template x-if="method === 'pickup'">
                            <div class="mt-4">	
                                <label for="branches" class="font-bold">Select Branch <span
                                        class="text-red-700">*</span></label>
                                <select id="branches" name="delivery_branch" @change="getDeliveryFee" x-ref="branch" required
                                    class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                    <option selected value="">Choose a branch</option>
                                    @foreach ($pickupBranches as $branch)
                                        <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </template>
                            
                            <div x-show="method === 'delivery'" class="space-y-4">
                        
                            <div class="flex items-center me-4 my-4">
                                <input @change="onChangeMultipleAddress()" x-model="allowMultiple" checked id="multiple-address" type="checkbox" value="" class="w-5 h-5 text-primary bg-gray-100 border-gray-300 rounded-sm focus:ring-primary-dark focus:ring-2">
                                <label for="multiple-address" class="ms-2 text-base font-medium text-gray-900">Allow multiple delivery address</label>
                            </div>

                            <template x-if="allowMultiple">
                                <div class="space-y-6">
                                    <template x-for="(delivery, index) in deliveries" :key="index">
                                        <div class="p-4 bg-gray-100 rounded-md border">
                                            <h4 class="font-bold mb-2">Delivery Address <span x-text="index + 1"></span></h4>
                        
                                            <div class="flex flex-col gap-4">
                                                <div class="w-full">
                                                    <label class="font-bold block text-sm mb-1">Address</label>
                                                    <textarea x-model="delivery.address"
                                                        class="w-full border border-gray-300 p-2 rounded-md" placeholder="Enter address" :class="{'border-red-500': errors.address}"></textarea>
                                                    <template x-if="errors.address">
                                                        <div class="text-red-500 text-xs mt-1" x-text="errors.address"></div>
                                                    </template>
                                                </div>
                                                <div class="w-full flex gap-4">
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Contact Person</label>
                                                        <input type="text" x-model="delivery.name"
                                                            class="w-full border border-gray-300 p-2 rounded-md" placeholder="" :class="{'border-red-500': errors.name}" />
                                                        <template x-if="errors.name">
                                                            <div class="text-red-500 text-xs mt-1" x-text="errors.name"></div>
                                                        </template>
                                                    </div>
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Contact Number</label>
                                                        <input type="tel" x-model="delivery.phone"
                                                            class="w-full border border-gray-300 p-2 rounded-md" placeholder="" :class="{'border-red-500': errors.phone}" />
                                                        <template x-if="errors.phone">
                                                            <div class="text-red-500 text-xs mt-1" x-text="errors.phone"></div>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="w-full flex gap-2">
                                                    <input
                                                        :id="'sms-' + index"
                                                        type="checkbox"
                                                        x-model="delivery.sms"
                                                        class="border border-gray-300 p-2"
                                                    />
                                                    <label class="block text-sm mb-1" :for="'sms-' + index">Notify recipient through SMS?</label>
                                                </div>
                                                
                                                <div class="w-full">
                                                    <label class="font-bold block text-sm mb-1">Orders</label>
                                                    <div class="flex flex-col gap-2">
                                                    <template x-for="(order, index2) in getAvailableOrders()" :key="index2">
                                                        <template x-if="getRemainingQty(order.product_id) > 0 || isOrderChecked(delivery, order)">
                                                            <div class="flex items-center gap-2 mb-2 justify-between">
                                                                <!-- Product checkbox -->
                                                                <div class="flex items-center gap-2">
                                                                    <input
                                                                        type="checkbox"
                                                                        :id="'order-' + order.id + '-' + index + '-' + index2"
                                                                        :value="order"
                                                                        @change="toggleOrderSelection(delivery, order)"
                                                                        :checked="isOrderChecked(delivery, order)"
                                                                        :disabled="getRemainingQty(order.product_id) <= 0 && !isOrderChecked(delivery, order)"
                                                                    />
                                                                    <label :for="'order-' + order.id + '-' + index + '-' + index2" class="flex-1">
                                                                        <span x-text="order.product.name + (order.paella_price > 0 ? ' with Paella' : '') + (getRemainingQty(order.product_id) <= 0 && !isOrderChecked(delivery, order) ? ' (Fully Assigned)' : '')"></span>
                                                                    </label>
                                                                </div>

                                                                <!-- Quantity dropdown -->
                                                                <select
                                                                    class="border rounded px-2 py-1"
                                                                    :disabled="!isOrderChecked(delivery, order)"
                                                                    :value="getSelectedQty(delivery, order)"
                                                                    @change="updateSelectedQty(delivery, order, $event.target.value)">
                                                                    <template x-for="i in getRemainingQty(order.product_id) + getPreviouslySelectedQty(delivery, order)">
                                                                        <option :value="i" x-text="i"></option>
                                                                    </template>
                                                                </select>
                                                            </div>
                                                        </template>
                                                    </template>

                                                    </div>
                                                </div>

                                                <div x-show="qtyValidationMessage" class="text-red-600 bg-red-100 border border-red-300 rounded p-3 mt-3">
                                                    <p x-text="qtyValidationMessage"></p>
                                                </div>

                                                <input type="hidden" x-model="delivery.delivery_fee" />
                                                
                                                <div class="w-full flex gap-4">
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Select Date</label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                                            </svg>
                                                            </div>
                                                            <input 
                                                                :class="{'border-red-500': errors.need_date}"
                                                                onkeydown="return false"
                                                                :min="minimumDate"
                                                                @change="validateDeliveryDateTime(delivery)"
                                                                x-model="delivery.need_date" name="need_date" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-3" placeholder="Select date">
                                                        
                                                            <template x-if="errors.need_date">
                                                                <div class="text-red-500 text-xs mt-1" x-text="errors.need_date"></div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Select Time</label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                            <select 
                                                                :class="{'border-red-500': errors.need_time}"
                                                                name="need_time" 
                                                                id="need_time"
                                                                x-model="delivery.need_time" 
                                                                {{-- @click="validateDeliveryDateTime(delivery)" --}}
                                                                @change="validateDeliveryDateTime(delivery)"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                            >
                                                                <option value="">Select Hour</option>
                                                                <template x-for="hour in getAvailableHours(delivery)" :key="hour">
                                                                    <template x-if="!isTimeDisabledForDelivery(hour)(delivery)">
                                                                        <option 
                                                                            :value="(hour < 10 ? '0' + hour : hour) + ':00'" 
                                                                            x-text="formatAMPM(hour)">
                                                                        </option>
                                                                    </template>
                                                                </template>
                                                            </select>
                                                            <template x-if="errors.need_time">
                                                                <div class="text-red-500 text-xs mt-1" x-text="errors.need_time"></div>
                                                            </template>
                                                        </div>
                                                        <div x-show="noNeededTime" class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                                            Please select a time.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
                                                    <div>We've pre-selected the earliest available time for your order. You’re welcome to adjust the date and time to your preference.</div>
                                                </div>

                                                <div class="w-full flex gap-4">
                                                    <div class="w-full">
                                                        <label :for="'locations' + index" class="font-bold">Select Location <span
                                                                class="text-red-700">*</span></label>
                                                        <select x-model="delivery.location" :id="'locations' + index" name="location" @change="getDeliveryFeeForMultipleDelivery(index)" required
                                                            class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                            <option selected value="">Choose a location</option>
                                                            @foreach ($locations as $location)
                                                                <option value="{{ $location->name }}">{{ $location->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p
                                                            x-show="(!delivery.orders || delivery.orders.length === 0) && delivery.location"
                                                            class="mt-1 text-red-600"
                                                        >
                                                            Order is required to get delivery fee. Please select at least one order for this delivery address.
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="w-full">
                                                    <label class="font-bold block text-sm mb-1">Note</label>
                                                    <textarea x-model="delivery.note"
                                                        class="w-full border border-gray-300 p-2 rounded-md" placeholder="Add instructions or notes about your delivery."></textarea>
                                                </div>
                                                
                                            </div>
                        
                                            <div class="text-right mt-3">
                                                <button type="button" @click="removeDelivery(index)"
                                                    x-show="deliveries.length > 1"
                                                    class="text-red-600 text-sm underline">Remove</button>
                                            </div>
                                        </div>
                                    </template>
                        
                                    <div x-show="canAddMoreDeliveries()">
                                        <button type="button" 
                                            @click="validateBeforeAddDelivery"
                                            class="bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                                            Add Another Delivery
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!allowMultiple && method === 'delivery'">
                                <div>
                                    <div class="w-full">
                                        <label for="delivery_address"
                                        class="block mb-2 font-bold text-gray-900">Delivery Address <span
                                            class="text-red-700">*</span></label>
                                        <input type="text" id="delivery_address" name="delivery_address" x-model="delivery_address" value="{{ auth()->check() ? auth()->user()->address_street : '' }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                            placeholder="" />
                                        <div x-show="noDeliveryAddress" class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                            Please add delivery address
                                        </div>
                                    </div>

                                    <div class="mt-4">	
                                        <label for="locations" class="font-bold">Select Location <span
                                                class="text-red-700">*</span></label>
                                        <select id="locations" name="location" @change="getDeliveryFee" x-ref="location" required
                                            class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                            <option selected value="">Choose a location</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->name }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        </div>
                    </div>
    
                    <div>
                        <div class="px-4 py-3 border-b border-[#DFDFDF]">
                            <div class="font-bold">Contact Information</div>
    
                            <div class="mt-3">
                                <div class="my-2">
                                    <label for="name"
                                        class="block mb-2 text-sm font-bold text-gray-900">Name<span
                                            class="text-red-700">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ auth()->check() ? (auth()->user()->is_org == 1 ? auth()->user()->contact_person : auth()->user()->name) : '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        placeholder="" required />

                                    <template x-if="nameValidationMessage">
                                        <p class="text-red-500 text-xs italic mt-2" x-text="nameValidationMessage"></p>
                                    </template>
                                </div>
                                <div class="my-2">
                                    <label for="mobile"
                                        class="block mb-2 text-sm font-bold text-gray-900">Mobile Number
                                        <span class="text-red-700">*</span></label>
                                    <input type="tel" id="mobile" name="mobile" value="{{ auth()->check() ? auth()->user()->contact_mobile : '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        required />

                                    <template x-if="mobileValidationMessage">
                                        <p class="text-red-500 text-xs italic mt-2" x-text="mobileValidationMessage"></p>
                                    </template>
                                </div>
                                <div class="my-2">
                                    <label for="email"
                                        class="block mb-2 text-sm font-bold text-gray-900">Email <span
                                            class="text-red-700">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        placeholder="" required />

                                    <template x-if="emailValidationMessage">
                                        <p class="text-red-500 text-xs italic mt-2" x-text="emailValidationMessage"></p>
                                    </template>
                                </div>
                                <div class="my-2">
                                    <label for="agent"
                                        class="block mb-2 text-sm font-bold text-gray-900">Agent Code</label>
                                    <input type="text" id="agent" name="agent" value=""
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        placeholder="" />

                                    @error('agent')
                                        <div class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <template x-if="!allowMultiple">
                                <div class="w-full flex gap-4">
                                    <div class="my-2 w-full lg:w-1/2">
                                        <label for="date"
                                            class="block mb-2 text-sm font-bold text-gray-900">Select Date <span
                                                class="text-red-700">*</span></label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </div>
                                            <input 
                                                onkeydown="return false"
                                                :min="minDate" @change="validateDateTime" 
                                                x-model="need_date" type="date" name="need_date"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 "
                                                placeholder="Select date">
                                        </div>
                                        <div x-show="noNeededDate" class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                            Please select a date.
                                        </div>
                                    </div>
                                    <div class="my-2 w-full lg:w-1/2">
                                        <div class="relative">
                                            <label for="need_time" class="block mb-2 text-sm font-bold text-gray-900">Select Time <span class="text-red-700">*</span></label>
                                            <select 
                                                id="need_time" 
                                                name="need_time" 
                                                x-model="need_time" 
                                                @change="validateDateTime"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            >
                                                <option value="">Select Hour</option>
                                                <template x-for="hour in allHours" :key="hour">
                                                    <template x-if="!isTimeDisabled(hour)">
                                                        <option 
                                                            :value="(hour < 10 ? '0' + hour : hour) + ':00'" 
                                                            x-text="formatAMPM(hour)"
                                                        ></option>
                                                    </template>
                                                </template>
                                            </select>
                                        </div>
                                        <div x-show="noNeededTime" class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                            Please select a time.
                                        </div>
                                    </div>
                                </div>
                                </template>
                                <div class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
                                    <div>We've pre-selected the earliest available time for your order. You’re welcome to adjust the date and time to your preference.</div>
                                </div>
                                {{-- <div x-show="warningMessage">
                                    <div class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
                                        <div x-html="warningMessage"></div>
                                    </div>
                                </div> --}}
                                <div class="my-2">
                                    <label for="time"
                                        class="block mb-2 text-sm font-bold text-gray-900">Instruction</label>
                                    <div class="relative">
                                        <textarea
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                            name="instruction" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                
                                @if (auth()->guest())
                                <div class="flex items-start">
                                    <label @click="onCheckboxChange" class="flex items-center space-x-2">
                                        <input
                                            x-model="privacy" 
                                            name="privacy" 
                                            type="checkbox"
                                            disabled="true"
                                        >
                                        <span>I agree Lydia’s Lechon’s Privacy Protection Policy</span>
                                    </label>
                                </div>
                                <template x-if="errors.privacy">
                                    <p class="text-red-500 text-xs mt-1" x-text="errors.privacy[0]"></p>
                                </template>
                                @endif
                            </div>
                            <div x-show="hasErrorMessage" class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                We are not able to accommodate your order base on your preferred date and time. Kindly refer to the warning message that appeared on your order screen or call our hotline at 89391221 / 89394665.  Thank you.
                            </div>
                            <button :disable="isSubmitting" type="submit" class="bg-primary custom-btn btn-primary-dark text-center text-white px-6 py-4 mt-4 w-full rounded-md">
                                <span x-show="!isSubmitting">Place Order</span>
                                <span x-show="isSubmitting" class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </form>

    <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 overflow-y-auto py-10 px-4"
        @click.self="showModal = false">
        <div x-show="showModal" 
            class="relative m-auto bg-white text-black z-50 w-full max-w-2xl rounded-md">
            <div id="data-privacy-render">
                {!! $dataPrivacyRender !!}
            </div>

            <div class="flex justify-end p-4">
                <button type="button" @click="agreePrivacy" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                    Agree
                </button>
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
                            <button @click="closeDepositModal()" class="self-end text-2xl text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
            
                        <div class="text-gray-600 font-medium px-4 mt-4">
                            To complete your order, please enter the amount you wish to pay.
                        </div>
            
                        <div class="px-4 mt-5">
                            <div>
                                <form
                                    x-data="{ isFormSubmitting: false }"
                                    @submit="isFormSubmitting = true; setTimeout(() => { this.depositModal = true}, 3000)"
                                    action="{{ route('paymaya.paytest') }}" method="POST" enctype="multipart/form-data" class="flex flex-col">
                                    
                                    {{-- action="{{ route('paymaya.pay') }}" method="POST" enctype="multipart/form-data" class="flex flex-col"> --}}
                                    @csrf
                                    <input type="hidden" name="sales_header_id" :value="paymentDetails.order_number">
                        
                                    <div class="pb-4">
                                        <img src="{{ asset('images/payment/pay-maya.jpg') }}">
                                    </div>

                                    <!-- GCash / PayMaya -->
                                    <div>
                                        <label class="font-semibold block mb-1">PayMaya:</label>
                                        <select name="pamenty_mode" id="pamenty_mode_gpay" x-model="paymentMode" @change="gcash_paymaya_change" required class="border-gray-300 rounded-md w-full p-2">
                                            <option value="PayMaya">PayMaya</option>
                                        </select>
                                    </div>
                        
                                    <!-- GCash QR Code -->
                                    <div x-show="paymentMode === 'GCash'" class="text-center">
                                        <p class="font-semibold">GCash</p>
                                        <p class="text-sm">Scan the QR Code below</p>
                                        <img src="{{ asset('images/gcash.png') }}" alt="GCash QR" class="mx-auto mt-2 w-40 h-40 object-contain">
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
                                            <input readonly required name="amount" :value="paymentDetails.amount" type="text" id="money" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full border-gray-300 p-2.5  " placeholder="">
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
    </div>
</div>

<x-footer-component />

<script>
    window.disabledPickupDates = @json($disabledPickupDates);
    window.disabledDeliveryDates = @json($disabledDeliveryDates);
    window.fullUrl = @json(config('app.url'));
    window.hasBaka = @json($hasbaka);
    window.hasMisc = @json($hasMisc);
    window.hasLechon = @json($haslechon);
    window.privacy = @json(auth()->check());
</script>

<script>
    function checkoutForm() {
        return {
            today: new Date(),
            hasbaka: window.hasBaka || false,
            haslechon: window.hasLechon || false,
            hasMisc: window.hasMisc || false,
            minDate() {
                if (this.hasbaka == true) {
                    const day = new Date(this.today);
                    day.setDate(day.getDate() + 3);
                    return day.toISOString().split('T')[0];
                } else if (this.haslechon == true) {
                    const tomorrow = new Date(this.today);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    return tomorrow.toISOString().split('T')[0];
                } else {
                    return this.today.toISOString().split('T')[0];
                }
            },
            paymentDetails: {
                sales_header_id: '',
                order_number: '',
                customer_contact_number: '',
                customer_name: '',
                amount: '',
                signature: '',
                saved_items: ''
            },
            disabledDeliveryDates: window.disabledDeliveryDates,
            disabledPickupDates: window.disabledPickupDates,
            paymentMode: '',
            currentDate: new Date()?.toISOString()?.split('T')[0],
            method: 'pickup',
            depositModal: false,
            closeDepositModal() {
                this.depositModal = false;
                setTimeout(() => {
                    window.location.href = window.fullUrl + '/sales-summary/' + this.paymentDetails.sales_header_id;
                }, 300);
            },
            orders: @json($carts) || [],
            carts: @json($carts) || [],
            totalQty: 1,
            deliveries: [
                { 
                    address: '', 
                    name: '',
                    phone: '',
                    sms: false, 
                    qty: 1, 
                    location: '', 
                    order: '', 
                    need_date: this.minDate,
                    need_time: '',
                    note: '', 
                    delivery_fee: 0,
                    paella: false,
                }
            ],
            allowMultiple: false,
            onChangeMultipleAddress() {
                if (!this.allowMultiple) {
                    this.init()
                }
                this.deliveries = [{
                    address: '',
                    name: '',
                    phone: '',
                    sms: false,
                    qty: 1,
                    location: '',
                    order: '',
                    need_date: this.minDate(),
                    need_time: '',
                    note: '',
                    paella: false,
                    delivery_fee: 0,
                }];
                this.deliveryFees = [];
                this.deliveryFee = 0;

            },
            formEl: null,
            deliveryFee: 0,
            orderAmount: {{ $total }},
            totalAmount: 0,
            deposit: '',
            rawDeposit: '',
            deliveryFees: [],
            showMessage: false,
            need_date: '',
            need_time: '',
            allHours: Array.from({ length: 21 }, (_, i) => i),
            warningMessage: '',
            errorMessage: '',
            hasErrorMessage: false,
            isSubmitting: false,
            isPaymentLoading: false,
            noNeededTime: false,
            noNeededDate: false,
            coupon: null,
            coupons: [],
            couponCode: '',
            couponMessage: '',
            couponMessageType: '',
            totalDiscountAmount: 0,
            shippingDiscountAmount: 0,
            location: '',
            async submitCouponCode() {
                this.couponMessage = '';
                this.couponMessageType = '';

                const res = await fetch('{{ route('add-manual-coupon') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        couponcode: this.couponCode,
                    }),
                });

                const result = await res.json();

                if (!result.success) {
                    if (result.status !== 'valid') {
                        this.couponMessage = result.message;
                    }

                    this.couponMessageType = 'error';
                    return;
                }

                // Prevent duplicate coupon code
                if (this.coupons.find(c => c.code === result.coupon.code)) {
                    this.couponMessage = 'This coupon is already applied.';
                    this.couponMessageType = 'error';
                    return;
                }

                // Combination logic check:

                // Case 1: New coupon is non-combinable, and there are already applied coupons → reject
                if (result.coupon.combination_allowed === false && this.coupons.length > 0) {
                    this.couponMessage = 'This coupon cannot be combined with other coupons.';
                    this.couponMessageType = 'error';
                    return;
                }

                // Case 2: New coupon is combinable, but an existing coupon is non-combinable → reject
                if (result.coupon.combination_allowed === true) {
                    const nonCombinableCoupon = this.coupons.find(c => c.combination_allowed === false);
                    if (nonCombinableCoupon) {
                        this.couponMessage = 'A coupon that does not allow combination has already been applied.';
                        this.couponMessageType = 'error';
                        return;
                    }
                }

                if (result.coupon.free_products && result.coupon.free_products.length > 0) {
                    result.coupon.free_products.forEach(fp => {
                        if (!this.carts.find(item => item.is_free_product && item.id === fp.id)) {
                            this.carts.push({
                                id: fp.id,
                                product: {
                                    name: fp.name,
                                    photos: fp.photos,
                                },
                                qty: 1,
                                price: 0,
                                is_free_product: true,
                                coupon_code: result.coupon.code,
                            });
                        }
                    });

                    this.hasFreeProducts = true;
                }

                // Add coupon to coupons array
                this.coupons.push(result.coupon);

                // Recompute totals
                this.recomputeCouponTotals();

                this.couponMessage = 'Voucher code successfully applied.';
                this.couponMessageType = 'success';

                // Clear input after success
                this.couponCode = '';
            },

            hasFreeProducts: false,

            clearToProceed: true,

            recomputeCouponTotals(delivery = null) {
                this.totalDiscountAmount = 0;
                this.shippingDiscountAmount = 0;

                let location = delivery ? delivery?.location : this.location;

                this.coupons = this.coupons.filter(coupon => {
                    if (coupon.free_shipping && coupon.location) {
                        const allowedLocations = coupon.location
                            .split('|')
                            .map(l => l.trim())
                            .filter(l => l !== '');

                        return allowedLocations.includes(location) || allowedLocations.includes('all');
                    }
                    return true; // keep all non-shipping coupons
                });

                // Now compute totals using cleaned coupon list
                this.coupons.forEach(coupon => {
                    if (coupon.free_shipping) {
                        if (parseFloat(coupon.free_shipping_discount_amount) === 100) {
                            this.shippingDiscountAmount += parseFloat(this.deliveryFee);
                        } else {
                            this.shippingDiscountAmount += parseFloat(this.deliveryFee) * (parseFloat(coupon.free_shipping_discount_amount) / 100);
                        }
                    } else {
                        if (coupon.discount_type === 'amount') {
                            this.totalDiscountAmount += parseFloat(coupon.discount ?? 0);
                        } else if (coupon.discount_type === 'percent') {
                            this.totalDiscountAmount += (this.orderAmount * parseFloat(coupon.discount ?? 0)) / 100;
                        }
                    }
                });

                // Safety cap
                this.totalDiscountAmount = Math.min(this.totalDiscountAmount, this.orderAmount);
            },


            computeTotal() {
                // Fallbacks
                const orderAmount = parseFloat(this.orderAmount) || 0;
                const shippingDiscount = parseFloat(this.shippingDiscountAmount) || 0;
                const couponDiscount = parseFloat(this.totalDiscountAmount) || 0;

                // Handle delivery fee
                let deliveryFeeFinal = parseFloat(this.deliveryFee) || 0;
                if (this.method === 'pickup') {
                    deliveryFeeFinal = 0;
                } else {
                    deliveryFeeFinal = deliveryFeeFinal;
                    deliveryFeeFinal = Math.max(deliveryFeeFinal, 0); // no negative
                }

                if (shippingDiscount > 0) {
                    this.totalDiscountAmount += shippingDiscount;
                }

                // Compute total
                let total = orderAmount + deliveryFeeFinal - (couponDiscount + this.shippingDiscountAmount);

                total = total <= 0 ? 0 : total;

                this.totalAmount = total;
                this.deposit = total.toFixed(2);

                // Update hidden input if needed
                this.$nextTick(() => {
                    let input = this.$root.querySelector('input[name="deposit"]');
                    if (input) {
                        input.dispatchEvent(new Event('input'));
                    }
                });

                return new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                }).format(total);
            },

            freeShipping: false,
            freeShippingDiscountAmount: 0,

            removeCoupon(index) {
                if (typeof index === 'number') {
                    const removedCoupon = this.coupons[index];
                    this.coupons.splice(index, 1);

                    this.carts = this.carts.filter(item =>
                        !(item.is_free_product && item.coupon_code === removedCoupon.code)
                    );
                } else {
                    this.coupons = [];

                    this.carts = this.carts.filter(item => !item.is_free_product);
                }

                this.recomputeCouponTotals();
            },

            changeMethod(method) {
                this.method = method;
                document.cookie = `shipping_method=${method}; path=/;`;

                this.allowMultiple = false;

                this.noNeededTime = false;
                this.noNeededDate = false;

                this.couponMessage = '';
                this.deliveryFees = [];
                this.removeCoupon();

                this.loadAutoCoupons();

                if (!this.allowMultiple) {
                    this.validateDateTime();
                }
            },

            mobileValidationMessage: '',
            nameValidationMessage: '',
            emailValidationMessage: '',
            noDeliveryAddress: false,
            delivery_address: '',
            privacy: window.privacy || false,
            errors: {
                privacy: null,
            },

            submitForm() {
                this.formEl = document.getElementById('checkoutForm');

                const formData = new FormData(this.formEl);

                this.isSubmitting = true;
                this.noNeededDate = false;
                this.noNeededTime = false;
                this.noDeliveryAddress = false;
                
                if ((!this.need_time && this.method === 'pickup') || (!this.need_time && this.method === 'delivery' && !this.allowMultiple)) {
                    this.noNeededTime = true;
                    this.isSubmitting = false;
                    return;
                }

                if (!this.delivery_address && this.method === 'delivery' && !this.allowMultiple) {
                    this.noDeliveryAddress = true;
                    this.isSubmitting = false;
                    return;
                }

                if (!this.need_date && this.method === 'pickup') {
                    this.noNeededDate = true;
                    this.isSubmitting = false;
                    return;
                }

                if (this.errorMessage) {
                    this.hasErrorMessage = true;
                    this.isSubmitting = false;
                    return;
                }

                if (!this.clearToProceed) {
                    this.hasErrorMessage = true;
                    this.isSubmitting = false;
                    return;
                }
                
                if (this.hasErrorMessage) {
                    this.isSubmitting = false;
                    return;
                }
                
                if (!this.privacy) {
                    this.onCheckboxChange();
                    this.errors.privacy = ['You must agree to the privacy policy.'];
                    this.isSubmitting = false;
                    return;
                } else {
                    this.errors.privacy = null;
                }

                if (this.method === 'delivery' && this.allowMultiple) {
                    if (!this.validateAllDeliveryFields()) {
                        this.qtyValidationMessage = 'Please fill all quantity fields.';
                        this.isSubmitting = false;
                        return;
                    }

                    if (!this.validateAllQtyUsed()) {
                        this.isSubmitting = false;
                        return;
                    }
                }

                const couponsWithDiscountUsed = this.coupons.map(c => {
                    let discountUsed = 0;

                    if (c.free_shipping) {
                        discountUsed =
                            parseFloat(c.free_shipping_discount_amount) === 100
                                ? parseFloat(this.deliveryFee)
                                : parseFloat(this.deliveryFee) * (parseFloat(c.free_shipping_discount_amount) / 100);
                    } else {
                        if (c.discount_type === 'amount') {
                            discountUsed = parseFloat(c.discount ?? 0);
                        } else if (c.discount_type === 'percent') {
                            discountUsed = (parseFloat(this.orderAmount) * parseFloat(c.discount ?? 0)) / 100;
                        }
                    }

                    return {
                        ...c,
                        discount_used: parseFloat(discountUsed.toFixed(2)),
                    };
                });


                // Add dynamic fields
                formData.append('shipping_type', this.method);
                formData.append('coupons', JSON.stringify(this.coupons.map(c => c.code)));
                formData.append('coupons', JSON.stringify(couponsWithDiscountUsed));

                // Get total discount_used
                const discounted_amount = couponsWithDiscountUsed.reduce((sum, c) => {
                    return sum + parseFloat(c.discount_used || 0);
                }, 0);

                console.log('Total discount used:', discounted_amount);


                formData.append('discount_amount', isNaN(discounted_amount) ? 0 : discounted_amount);
                formData.append('coupon_data', JSON.stringify(this.coupons));
                formData.append('order_amount', this.orderAmount);
                formData.append('delivery_fee', this.deliveryFee);
                formData.append('deposit', this.deposit);
                formData.append('total_amount', this.totalAmount);

                if (this.allowMultiple) {
                    formData.append('deliveries', JSON.stringify(this.deliveries));
                }

                fetch(this.formEl.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) throw response;
                    return response.json();
                })
                .then(data => {
                    console.log('Order submitted:', data);
                    if (data.success) {
                        this.paymentDetails = {
                            sales_header_id: data.sales_header_id,
                            order_number: data.order_number,
                            customer_contact_number: data.customer_contact_number,
                            customer_name: data.customer_name,
                            amount: data.amount,
                            signature: data.signature,
                            saved_items: data.saved_items
                        };

                        if (data.amount <= 0) {
                            window.location.href = '/sales-summary/' + data.sales_header_id;
                            return;
                        }

                        this.depositModal = true;

                        this.isSubmitting = false;
                    } else {
                        this.isSubmitting = false
                        alert('Error: ' + data.message);
                    };
                })
                .catch(async error => {
                    this.isSubmitting = false;
                    let errText = await error.text();
                    console.error('Error:', errText);

                    let errorMessage = JSON.parse(errText);

                    if (errorMessage.errors && errorMessage.errors.mobile) {
                        this.mobileValidationMessage = errorMessage.errors.mobile[0];
                    }

                    if (errorMessage.errors && errorMessage.errors.name) {
                        this.nameValidationMessage = errorMessage.errors.name[0];
                    }

                    if (errorMessage.errors && errorMessage.errors.email) {
                        this.emailValidationMessage = errorMessage.errors.email[0];
                    }
                });
            },

            validateAllDeliveryFields() {
                return this.deliveries.every(delivery => {
                    const hasValidProducts = Array.isArray(delivery.orders) &&
                        delivery.orders.length > 0 &&
                        delivery.orders.every(o => o.product_id && o.qty && o.qty > 0);

                    return (
                        delivery.need_time &&
                        delivery.need_date &&
                        delivery.location &&
                        hasValidProducts
                    );
                });
            },

            async getDeliveryFee() {
                const location = this.$refs?.location?.value;
                const branch = this.$refs?.branch?.value;

                if (location) {
                    try {
                        let response = await fetch('{{route('cart.front.get_shipping_fee')}}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                location: location,
                            }),
                        }).then((response) => {
                            return response;
                        }).catch((error) => {
                            
                        });

                        if (!response.ok) throw new Error('Network response was not ok');

                        let data = await response.json();

                        this.deliveryFee = data.fee;

                        this.location = location;

                        this.coupons = this.coupons.filter(coupon => !coupon.free_shipping);
                        
                        this.recomputeCouponTotals();
                        
                        this.loadAutoCoupons();
                        
                    } catch (error) {
                        console.error('There was a problem with the fetch operation:', error);
                    }
                }
            },

            async loadAutoCoupons(refetch = false) {
                const res = await fetch('{{ route('get-auto-coupons') }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });

                const result = await res.json();

                if (result.success && result.coupons.length > 0) {
                    for (const autoCoupon of result.coupons) {
                        // Skip if already exists
                        if (this.coupons.find(c => c.code === autoCoupon.code)) continue;

                        // Case 1: Auto coupon is non-combinable and we already have applied coupons
                        if (!autoCoupon.combination_allowed && this.coupons.length > 0) {
                            console.log(`Skipping non-combinable auto coupon: ${autoCoupon.code}`);
                            continue;
                        }

                        // Case 2: Auto coupon is combinable, but an existing coupon is non-combinable
                        if (autoCoupon.combination_allowed && this.coupons.some(c => !c.combination_allowed)) {
                            console.log(`Skipping auto coupon ${autoCoupon.code} due to non-combinable existing coupon`);
                            continue;
                        }

                        // Passed logic check → push to coupons
                        this.coupons.push(autoCoupon);

                        // Handle free products if any
                        if (autoCoupon.free_products?.length > 0) {
                            autoCoupon.free_products.forEach(fp => {
                                if (parseInt(fp.category_id) === 1) {
                                    this.haslechon = true;

                                    this.need_date = this.minDate();
                                }
                                if (fp.slug == 'lechon-baka') {
                                    this.hasbaka = true;

                                    this.need_date = this.minDate();
                                }


                                if (!this.carts.find(item => item.is_free_product && item.id === fp.id)) {
                                    this.carts.push({
                                        id: fp.id,
                                        product: {
                                            name: fp.name,
                                            photos: fp.photos,
                                        },
                                        qty: 1,
                                        price: 0,
                                        is_free_product: true,
                                        coupon_code: autoCoupon.code,
                                    });
                                }

                                // Push to orders so they appear in delivery assignment
                                if (!this.orders.find(o => o.product_id === fp.id && o.is_free_product)) {
                                    this.orders.push({
                                        product_id: fp.id,
                                        qty: 1,
                                        product: fp,
                                        is_free_product: true,
                                        price: 0,
                                        delivery_address: '',
                                        need_date: '',
                                        need_time: '',
                                    });
                                }
                            });

                            this.hasFreeProducts = true;
                        }
                    }

        
                    this.$nextTick(() => {
                        this.minDate();
                    });

                    if (!refetch) {
                        // Recompute totals after loading auto coupons
                        this.recomputeCouponTotals();
                    }
                }
            },

            discountAmount: 0,

            // computeTotal() {
            //     if (this.method == 'pickup') {
            //         this.deliveryFee = 0;
            //     }

            //     let deliveryFeeFinal = this.deliveryFee;

            //     // If free shipping applies
            //     if (this.coupon && this.freeShipping) {
            //         if (this.freeShippingDiscountAmount === 100) {
            //             deliveryFeeFinal = 0;
            //         } else {
            //             deliveryFeeFinal = this.deliveryFee * (1 - this.freeShippingDiscountAmount / 100);
            //         }
            //     }

            //     let total = parseFloat(this.orderAmount) + parseFloat(deliveryFeeFinal);

            //     // Apply coupon discount (if not free shipping type)
            //     if (this.coupon && this.discountAmount > 0) {
            //         total -= this.discountAmount;
            //     }

            //     // Update your component state
            //     this.totalAmount = total;
            //     this.deposit = this.totalAmount.toFixed(2);

            //     // Trigger any input update (if needed)
            //     this.$nextTick(() => {
            //         let input = this.$root.querySelector('input[name="deposit"]');
            //         if (input) {
            //             input.dispatchEvent(new Event('input'));
            //         }
            //     });

            //     return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(total);
            // },

            async getDeliveryFeeForMultipleDelivery(index) {
                const delivery = this.deliveries[index];
                const location = delivery.location;
                const products = delivery?.orders?.map(o => o.product_id);

                if (!location || products?.length === 0 || products == undefined) return;

                try {
                    const response = await fetch('{{ route('cart.front.get_shipping_fee_for_multiple_address_new') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ locations: [location], products }),
                    });

                    if (!response.ok) throw new Error('Network error');

                    const data = await response.json();
                    const fee = parseFloat(data.fee || 0);

                    delivery.delivery_fee = fee;

                    // Always store by index — 1 entry per row
                    this.deliveryFees[index] = { location, fee };

                    // this.deliveryFee += fee;

                    // Update total delivery fee
                    this.deliveryFee = this.deliveries.reduce((sum, d) => sum + parseFloat(d.delivery_fee || 0), 0);

                    await this.loadAutoCoupons(true);

                    this.recomputeCouponTotals(delivery);

                } catch (e) {
                    console.error(`Failed to fetch delivery fee for ${location}`, e);
                    delivery.delivery_fee = 0;
                }
            },

            init() {
                this.checkMultipleDeliveries();
                
                const cookie = document.cookie.split('; ').find(row => row.startsWith('shipping_method='));
                this.method = cookie ? cookie.split('=')[1] : 'pickup';

                this.need_date = this.minDate();

                this.loadAutoCoupons();

                this.$watch('need_date', value => {
                    this.checkAndAdvanceDateIfNoHours();
                });

                this.checkAndAdvanceDateIfNoHours();

                if (!this.allowMultiple) {
                    this.validateDateTime();
                }
            },

            checkMultipleDeliveries() {
                let multipleItems = this.orders.length > 1;
                let multipleQty = this.orders.some(order => order.qty > 1);
                
                // this.allowMultiple = multipleItems || multipleQty;
            },

            // Check if a product is selected for this delivery
            isOrderChecked(delivery, order) {
                return delivery.orders?.some(o => o.product_id === order.product_id);
            },

            // Get selected quantity for dropdown binding
            getSelectedQty(delivery, order) {
                const found = delivery.orders?.find(o => o.product_id === order.product_id);
                return found ? found.qty : '';
            },

            getProductType(order) {
                const slug = order.product?.slug;
                const categoryId = order.product?.category_id;

                if (slug === 'lechon-baka') {
                    return 'baka';
                }

                if (categoryId === 1 && slug !== 'lechon-baka') {
                    return 'lechon';
                }

                return 'misc';
            },

            // When checkbox is toggled
            toggleOrderSelection(delivery, order) {
                this.autoAdvanceDateIfNoHours(delivery)
                delivery.location = '';

                if (!delivery.orders) delivery.orders = [];

                const index = this.deliveries.indexOf(delivery);
                const existingIndex = delivery.orders.findIndex(o => o.product_id === order.product_id);

                if (existingIndex !== -1) {
                    delivery.orders.splice(existingIndex, 1); // Uncheck
                } else {
                    delivery.orders.push({
                        paella: order.paella_price > 0 ? true : false,
                        product_id: order.product_id,
                        qty: 1,
                        product: order.product
                    });
                }
                
                const now = new Date(); // current time
                const selectedDate = new Date(delivery.need_date ?? ''); // delivery date

                let originalAllHours = Array.from({ length: 21 }, (_, i) => i);
                const type = this.getProductType(order);

                // Check if selected date is tomorrow
                const tomorrow = new Date();
                tomorrow.setDate(now.getDate() + 1);

                const isTomorrow =
                    selectedDate.getDate() === tomorrow.getDate() &&
                    selectedDate.getMonth() === tomorrow.getMonth() &&
                    selectedDate.getFullYear() === tomorrow.getFullYear();

                if (type === 'lechon') {
                    this.$nextTick(() => {
                        let d = new Date();
                        d.setDate(d.getDate() + 1);
                        delivery.need_date = d.toISOString().split('T')[0];
                        this.minimumDate(d)
                    });
                } else if (type === 'baka') {
                    this.$nextTick(() => {
                        let d = new Date();
                        d.setDate(d.getDate() + 3);
                        delivery.need_date = d.toISOString().split('T')[0];
                        this.minimumDate(d)
                    });
                } else {
                    this.$nextTick(() => {
                        delivery.need_date = now.toISOString().split('T')[0];
                        this.minimumDate(now)
                        this.autoAdvanceDateIfNoHours(delivery)
                    });
                }

                if (isTomorrow && type === 'lechon') {
                    // 1. Get the current hour and round up
                    let selectedHour = now.getHours();
                    if (now.getMinutes() > 0) {
                        selectedHour += 1; // Round up if there's any minute
                    }

                    // 2. Set the need_time as HH:00 format
                    delivery.need_time = (selectedHour < 10 ? '0' + selectedHour : selectedHour) + ':00';

                    // 3. Get available hours and remove hours before selectedHour
                    let hours = this.getAvailableHours(delivery);
                    hours = hours.filter(hour => hour >= selectedHour);

                    // If you want to use these filtered hours for a dropdown
                    this.allHours = hours;
                } else {
                    this.allHours = originalAllHours;
                }

                // Remove all deliveries after the current one
                this.deliveries.splice(index + 1);

                this.qtyValidationMessage = '';

                this.refreshAllAvailableQty();
            },

            // Get remaining qty for a product globally (used across all deliveries)
            getRemainingQty(productId) {
                const order = this.orders.find(o => o.product_id === productId);
                const total = order ? parseInt(order.qty) : 0;

                const used = this.deliveries.reduce((sum, d) => {
                    return sum + (d.orders?.reduce((inner, o) => {
                    return o.product_id === productId ? inner + (parseInt(o.qty) || 0) : inner;
                    }, 0) || 0);
                }, 0);

                return Math.max(total - used, 0);
            },

            // Get previously selected qty in *this delivery* to allow it again in dropdown
            getPreviouslySelectedQty(delivery, order) {
                const match = delivery.orders?.find(o => o.product_id === order.product_id);
                return match ? parseInt(match.qty) || 0 : 0;
            },

            getOrderQtyBinding(delivery, order) {
                const selected = delivery.orders?.find(o => o.product_id === order.product_id);
                return selected ? selected.qty : '';
            },

            updateSelectedQty(delivery, order, newQty) {
                if (!delivery.orders) delivery.orders = [];

                const index = this.deliveries.indexOf(delivery);

                const orderIndex = delivery.orders.findIndex(o => o.product_id === order.product_id);
                if (orderIndex !== -1) {
                    delivery.orders[orderIndex].qty = parseInt(newQty) || 0;
                } else {
                    delivery.orders.push({
                        paella: order.paella_price > 0 ? true : false,
                        product_id: order.product_id,
                        qty: parseInt(newQty) || 0,
                        product: order.product
                    });
                }

                // Remove deliveries after this one
                this.deliveries.splice(index + 1);

                this.refreshAllAvailableQty();
            },

            refreshAllAvailableQty() {
                // re-trigger a render
                this.orders = this.orders.map(o => ({ ...o }));
            },

            updateAvailableQty(delivery) {
                if (!delivery.order) {
                    delivery.availableQty = [];
                    return;
                }

                const productId = delivery.order.product_id;
                const matchingOrder = this.orders.find(o => o.product_id === productId);
                const totalProductQty = matchingOrder ? parseInt(matchingOrder.qty) : 0;

                // Total assigned to same product, excluding current delivery
                const alreadyAssignedQty = this.deliveries
                    .filter(d => d !== delivery && d.order && d.order.product_id === productId)
                    .reduce((sum, d) => sum + (parseInt(d.qty) || 0), 0);

                const remainingQty = totalProductQty - alreadyAssignedQty;

                // Show dropdown from 1 to remainingQty only (don't add +1)
                const maxAvailable = Math.max(0, remainingQty);

                delivery.availableQty = Array.from({ length: maxAvailable }, (_, i) => i + 1);

                const currentQty = parseInt(delivery.qty) || 0;
                if (currentQty > maxAvailable) {
                    delivery.qty = '';
                }

                console.log({
                    totalProductQty,
                    alreadyAssignedQty,
                    currentQty,
                    remainingQty,
                    maxAvailable
                });
            },

            validateAllQtyUsed() {
                const expectedTotals = {};
                const assignedTotals = {};

                // Build the expected total quantity for each product
                this.orders.forEach(order => {
                    expectedTotals[order.product_id] = parseInt(order.qty) || 0;
                });

                // Sum up assigned quantities from all deliveries
                this.deliveries.forEach(delivery => {
                    if (Array.isArray(delivery.orders)) {
                        delivery.orders.forEach(o => {
                            if (!o.product_id || !o.qty) return;

                            const productId = o.product_id;
                            assignedTotals[productId] = (assignedTotals[productId] || 0) + parseInt(o.qty);
                        });
                    }
                });

                // Compare expected vs assigned
                for (const productId in expectedTotals) {
                    const expected = expectedTotals[productId];
                    const assigned = assignedTotals[productId] || 0;

                    if (assigned !== expected) {
                        this.qtyValidationMessage = '⚠️ Please assign all available quantities before proceeding.';
                        return false;
                    }
                }

                // All quantities match
                this.qtyValidationMessage = '';
                return true;
            },

            qtyValidationMessage: '',

            getAvailableOrders() {
                const availableOrders = this.orders.map(o => ({ ...o })); // Clone to avoid mutating

                for (const delivery of this.deliveries) {
                    if (Array.isArray(delivery.orders)) {
                        for (const selected of delivery.orders) {
                            const match = availableOrders.find(o => o.product_id === selected.product_id);
                            if (match) {
                                match.qty -= parseInt(selected.qty) || 0;
                                if (match.qty < 0) match.qty = 0;
                            }
                        }
                    }
                }

                return availableOrders;
            },

            canAddMoreDeliveries() {
                // Loop through each product
                for (const order of this.orders) {
                    const totalQty = parseInt(order.qty) || 0;

                    // Calculate how much has been used already for this product
                    const usedQty = this.deliveries.reduce((sum, delivery) => {
                        const match = delivery.orders?.find(o => o.product_id === order.product_id);
                        return sum + (match ? parseInt(match.qty) || 0 : 0);
                    }, 0);

                    // If there's still remaining quantity, allow adding delivery
                    if (usedQty < totalQty) {
                        return true;
                    }
                }

                // All products are fully assigned
                return false;
            },

            validateBeforeAddDelivery() {
                const lastDelivery = this.deliveries[this.deliveries.length - 1];

                this.errors = {}; // Clear previous errors

                if (!lastDelivery) return;

                // Check if at least one product with quantity is selected
                const hasValidProduct = Array.isArray(lastDelivery.orders) &&
                    lastDelivery.orders.length > 0 &&
                    lastDelivery.orders.every(o => o.product_id && o.qty && o.qty > 0);

                if (!hasValidProduct) {
                    alert('Please select at least one product and quantity before adding another delivery address.');
                    return;
                }

                // Check required address fields
                const { address, name, phone, location, need_date, need_time, sms } = lastDelivery;
                
                if (!address) this.errors.address = 'Address is required.';
                if (!name) this.errors.name = 'Contact person is required.';
                if (!location) this.errors.location = 'Location is required.';
                if (!need_date) this.errors.need_date = 'Date is required.';
                if (!need_time) this.errors.need_time = 'Time is required.';

                // Phone validation for SMS
                if (sms && phone) {
                    const phonePattern = /^(09|(\+63)|639)\d{9}$/;
                    if (!phonePattern.test(phone)) {
                        this.errors.phone = 'Please provide a valid phone number for SMS notifications.';
                        return;
                    }
                }

                if (!phone && sms) {
                    this.errors.phone = 'Please provide a phone number if you want the recipient to receive SMS notifications.';
                    return;
                }

                // check if need_date and need_time are less than 24 hours from now
                const selectedDateTime = new Date(`${need_date}T${need_time}`);
                const now = new Date();
                const diffInMs = selectedDateTime - now;
                const diffInHours = diffInMs / (1000 * 60 * 60);

                // if (this.haslechon && diffInHours < 24) {
                //     alert(`⚠️ Warning! The date and time you've selected (${need_date} - ${this.formatTime(need_time)}) is less than 24 hours from now. Our standard processing time is at least 24 hours. However, you can still proceed by contacting our store directly at our Call Hotline tab.`);
                //     return;
                // }

                // If valid, add a new blank delivery
                this.deliveries.push({
                    address: '',
                    name: '',
                    phone: '',
                    orders: [],
                    location: '',
                    paella: false,
                    need_date: this.minDate(),
                    need_time: '',
                    note: '',
                    delivery_fee: 0,
                    sms: false
                });

                this.qtyValidationMessage = '';
            },

            getAvailableHours(delivery) {
                return this.allHours.filter(hour => !this.isTimeDisabledForDelivery(hour)(delivery));
            },
            autoAdvanceDateIfNoHours(delivery, tries = 0) {
                if (tries > 31) return; // Don't go more than a month ahead

                const available = this.getAvailableHours(delivery);
                if (available.length === 0) {
                    // Add 1 day
                    let d = new Date(delivery.need_date);
                    d.setDate(d.getDate() + 1);
                    
                    this.$nextTick(() => {
                        delivery.need_date = d.toISOString().split('T')[0];
                    });

                    // Recursively check again for next date
                    this.autoAdvanceDateIfNoHours(delivery, tries + 1);
                } else {
                    const now = new Date();
                    if (!delivery.need_time) {

                        // Round up to next hour if minutes > 0
                        let roundedHour = now.getHours();
                        if (now.getMinutes() > 0) roundedHour += 1;

                        this.$nextTick(() => {
                            delivery.need_time = (roundedHour < 10 ? '0' + roundedHour : roundedHour) + ':00';
                            delivery.noNeededTime = false;
                        });
                    }
                }
            },
            isTimeDisabledForDelivery(hour) {
                return (delivery) => {
                    if (!delivery.need_date) return false;

                    const selectedDate = new Date(delivery.need_date);
                    const now = new Date();

                    const isToday =
                        selectedDate.getDate() === now.getDate() &&
                        selectedDate.getMonth() === now.getMonth() &&
                        selectedDate.getFullYear() === now.getFullYear();
                    if (isToday && hour <= now.getHours()) {
                        return true;
                    }

                    if (isToday && this.hasMisc && hour < (now.getHours() + 6)) {
                        return true;
                    }

                    const timeStr = (hour < 10 ? '0' + hour : hour) + ':00';
                    return this.disabledDeliveryDates.includes(`${delivery.need_date} ${timeStr}`);
                };
            },
            validateDeliveryDateTime(delivery) {
                this.autoAdvanceDateIfNoHours(delivery);

                // Optionally, always clear time when date changes (user can't select invalid time)
                const available = this.getAvailableHours(delivery);
                if (!available.includes(parseInt(delivery.need_time))) {
                    delivery.need_time = "";
                }

                if (!delivery.need_date || !delivery.need_time) return;

                const selectedDateTime = new Date(`${delivery.need_date}T${delivery.need_time}`);
                const now = new Date();

                const diffInMs = selectedDateTime - now;
                const diffInHours = diffInMs / (1000 * 60 * 60);

                delivery.warningMessage = '';

                if (this.haslechon) {
                    // pick the time that are 1 day or 24hours. example if today date is 7/29/2025 9:35.. then pick 7/30/2025 11:00 since it only display hour. and dont make it static and i want dynamic
                    if (diffInHours < 24) {
                       delivery.need_time = new Date(now.getTime() + 24 * 60 * 60 * 1000).toISOString().split('T')[1].substring(0, 5);
                    }

                    this.clearToProceed = true;
                    delivery.warningMessage = `⚠️ Warning! The date and time you've selected (${delivery.need_date} - ${this.formatTime(delivery.need_time)}) is less than 24 hours from now. Our standard processing time is at least 24 hours. However, you can still proceed by contacting our store directly at our <span class='underline text-blue-600 cursor-pointer' @click='openHotline = true'>Call Hotline</span> tab.`;
                } else {
                    this.clearToProceed = true;
                    this.hasErrorMessage = false;
                    delivery.warningMessage = '';
                }
            },
            formatTime(timeStr) {
                const [hours, minutes] = timeStr?.split(':');
                const hoursNum = parseInt(hours, 10);
                const isPM = hoursNum >= 12;
                const adjustedHours = hoursNum % 12 || 12;
                return `${adjustedHours}:${minutes} ${isPM ? 'PM' : 'AM'}`;
            },

            validateDateTime() {
                const now = new Date();

                if (!this.need_date) {
                    this.noNeededDate = true;
                    return;
                }

                const selectedDate = new Date(this.need_date);
                const isToday =
                    selectedDate.getDate() === now.getDate() &&
                    selectedDate.getMonth() === now.getMonth() &&
                    selectedDate.getFullYear() === now.getFullYear();

                if (!this.need_time) {
                    // Round up to next hour if minutes > 0
                    let roundedHour = now.getHours();
                    if (now.getMinutes() > 0) roundedHour += 1;

                    // Ensure the hour exists in your dropdown list
                    if (this.allHours.includes(roundedHour)) {
                        this.$nextTick(() => {
                            this.need_time = (roundedHour < 10 ? '0' + roundedHour : roundedHour) + ':00';
                            this.noNeededTime = false;
                        });
                    }
                }

                if (!this.need_time) {
                    this.noNeededTime = true;
                    return;
                }

                if (this.noNeededTime) this.noNeededTime = false;
                if (this.noNeededDate) this.noNeededDate = false;

                const selectedDateTime = new Date(`${this.need_date}T${this.need_time}`);
                const diffInMs = selectedDateTime - now;
                const diffInHours = diffInMs / (1000 * 60 * 60);

                this.warningMessage = '';

                // if (this.haslechon) {
                //     this.warningMessage = `⚠️ Warning! The date and time you've selected (${this.need_date} - ${this.formatTime(this.need_time)}) is less than 24 hours from now. Our standard processing time is at least 24 hours. However, you can still proceed by contacting our store directly at our <span class='underline text-blue-600 cursor-pointer' @click='openHotline = true'>Call Hotline</span> tab.`;
                //     this.errorMessage = `⚠️ We are not able to accommodate your order based on your preferred date and time. Kindly refer to the warning message that appeared on your order screen or call our hotline at 89391221 / 89394665. Thank you.`;
                // } else {
                //     this.errorMessage = '';
                //     this.hasErrorMessage = false;
                // }
            },

            async submit() {
                this.isSubmitting = true;
            },

            formatAMPM(hour) {
                const h = hour % 12 || 12;
                const suffix = hour < 12 ? 'AM' : 'PM';
                return `${h}:00 ${suffix}`;
            },

            checkAndAdvanceDateIfNoHours() {
                const available = this.allHours.filter(hour => !this.isTimeDisabled(hour));
                if (available.length === 0) {
                    // Add 1 day to need_date
                    const current = new Date(this.need_date);
                    current.setDate(current.getDate() + 1);
                    this.need_date = current.toISOString().split('T')[0];
                }
            },

            isTimeDisabled(hour) {
                if (!this.need_date) return false;

                const selectedDate = new Date(this.need_date);
                const now = new Date();

                const isToday =
                    selectedDate.getDate() === now.getDate() &&
                    selectedDate.getMonth() === now.getMonth() &&
                    selectedDate.getFullYear() === now.getFullYear();

                if (isToday && hour <= now.getHours()) {
                    return true;
                }

                if (isToday && this.hasMisc && hour < (now.getHours() + 6)) {
                    return true;
                }

                if (this.need_time && this.haslechon) {
                    const selectedHour = parseInt(this.need_time.split(':')[0]);
                    if (hour < selectedHour) {
                        return true;
                    }
                }

                const timeStr = (hour < 10 ? '0' + hour : hour) + ':00';
                const fullStr = `${this.need_date} ${timeStr}`;

                return this.method === 'pickup'
                    ? this.disabledPickupDates.includes(fullStr)
                    : this.disabledDeliveryDates.includes(fullStr);
            },
            removeDelivery(index) {
                const removed = this.deliveries.splice(index, 1)[0];

                if (removed?.location) {
                    this.deliveryFees = this.deliveryFees.filter(f => f.location !== removed.location);

                    // update delivery fee and total amount
                    this.deliveryFee = this.deliveryFees.reduce((acc, item) => acc + item.fee, 0);
                }

                this.computeTotal();
            },

            async checkDateTimeNeeded() {
                let response = await fetch('{{ route('cart.check_dateneeded') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        need_date: this.need_date,
                        need_time: this.need_time,
                        method: this.method,
                        allow_multiple: this.allowMultiple,
                    }),
                });

                if (!response.ok) throw new Error('Network error');

                const data = await response.json();
            },

            agreed: false,
            showModal: false,
            onCheckboxChange() {
                this.showModal = true;
            },
            agreePrivacy() {
                this.privacy = true;
                this.showModal = false;
            },

            minimumDate(date) {
                if (date) {
                    return new Date(date).toISOString().split('T')[0];
                } 

                return new Date().toISOString().split('T')[0]; // Default to today
            }
        }
    }
</script>

<script>
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || performance.getEntriesByType('navigation')[0].type === 'back_forward') {
            location.reload();
        }
    });
</script>

@section('alpine.plugins')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
@endsection

@endsection