@extends('layouts.guest', ['page' => $page])

@section('title', 'Checkout')
@section('meta_description', 'Complete your order at Lydia\'s Lechon. Review your cart, choose delivery or pickup, and finalize your purchase for a delicious meal.')

@section('content')

@php
    $total = 0;
    $deliveryFee = 0;
    if (count($carts) > 0) {
        foreach ($carts as $cart) {
            $total += $cart['price'] * $cart['qty'];
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
                            x-text="'₱' + carts.reduce((sum, item) => sum + (item.is_free_product ? 0 : item.price * item.qty), 0).toLocaleString(undefined, { minimumFractionDigits: 2 })">
                        </div>
                    </div>
    
                    <div class="flex flex-col items-center gap-4 px-4 py-3 border-b border-[#DFDFDF] w-full">
                        <template x-for="(item, index) in carts" :key="index">
                            <div class="flex gap-4 items-start w-full relative  border-gray-200 py-3">
                                <!-- Image -->
                                <div class="w-20 h-20 min-w-20 min-h-20 bg-center rounded-md overflow-hidden">
                                    <img 
                                        onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}'"
                                        :src="item.product?.photos?.length 
                                            ? '/storage/products/' + item.product.photos[item.product.photos.length - 1].path 
                                            : @js(asset('images/no-image.jpg'))" 
                                        
                                        alt=""
                                        class="w-20 h-20 object-cover"
                                    >
                                </div>

                                <!-- Info -->
                                <div class="flex flex-col flex-grow">
                                    <div class="font-bold">
                                        <span x-text="item?.product?.name"></span>
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
                                    </div>
                                    <div class="text-sm text-gray-600 font-medium">
                                        QTY: <span x-text="item.qty"></span>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="absolute right-0 bottom-2 text-sm lg:text-base font-bold text-black text-right">
                                    <span x-text="item.is_free_product 
                                        ? '₱0.00' 
                                        : '₱' + (item.price * item.qty).toLocaleString(undefined, { minimumFractionDigits: 2 })">
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
                                    x-text="'₱' + carts.reduce((sum, item) => sum + (item.is_free_product ? 0 : item.price * item.qty), 0).toLocaleString(undefined, { minimumFractionDigits: 2 })">
                                </span>
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

                                    <span class="font-medium italic text-red-700">
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
                                                        class="w-full border border-gray-300 p-2 rounded-md" placeholder="Enter address"></textarea>
                                                </div>
                                                <div class="w-full flex gap-4">
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Contact Person</label>
                                                        <input type="text" x-model="delivery.name"
                                                            class="w-full border border-gray-300 p-2 rounded-md" placeholder="" />
                                                    </div>
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Contact Number</label>
                                                        <input type="tel" x-model="delivery.phone"
                                                            class="w-full border border-gray-300 p-2 rounded-md" placeholder="" />
                                                    </div>
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
                                                                        <span x-text="order.product.name + (getRemainingQty(order.product_id) <= 0 && !isOrderChecked(delivery, order) ? ' (Fully Assigned)' : '')"></span>
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
                                                                onkeydown="return false"
                                                                :min="minDate"
                                                                @change="validateDeliveryDateTime(delivery)"
                                                                x-model="delivery.need_date" name="need_date" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-3" placeholder="Select date">
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
                                                                name="need_time" 
                                                                id="need_time"
                                                                x-model="delivery.need_time" 
                                                                @change="validateDeliveryDateTime(delivery)"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                            >
                                                                <option value="">Select Hour</option>
                                                                <template x-for="hour in allHours" :key="hour">
                                                                    <template x-if="!isTimeDisabledForDelivery(hour)(delivery)">
                                                                        <option 
                                                                            :value="(hour < 10 ? '0' + hour : hour) + ':00'" 
                                                                            x-text="formatAMPM(hour)">
                                                                        </option>
                                                                    </template>
                                                                </template>
                                                            </select>
                                                        </div>
                                                        <div x-show="noNeededTime" class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                                            Please select a time.
                                                        </div>
                                                    </div>
                                                </div>

                                                <template x-if="delivery.warningMessage">
                                                    <div class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
                                                        <div x-html="delivery.warningMessage"></div>
                                                    </div>
                                                </template>

                                                <div class="w-full flex gap-4">
                                                    <div class="w-full">
                                                        <label :for="'locations' + index" class="font-bold">Select Location <span
                                                                class="text-red-700">*</span></label>
                                                        <select x-model="delivery.location" :id="'locations' + index" name="location" @change="getDeliveryFeeForMultipleDelivery" required
                                                            class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                            <option selected value="">Choose a location</option>
                                                            @foreach ($locations as $location)
                                                                <option value="{{ $location->name }}">{{ $location->name }}</option>
                                                            @endforeach
                                                        </select>
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
                                <div x-show="warningMessage">
                                    <div class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
                                        <div x-html="warningMessage"></div>
                                    </div>
                                </div>
                                <div class="my-2">
                                    <label for="time"
                                        class="block mb-2 text-sm font-bold text-gray-900">Instruction</label>
                                    <div class="relative">
                                        <textarea
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                            name="instruction" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
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
</script>

<script>
    function checkoutForm() {
        return {
            today: new Date(),
            hasbaka: {{ $hasbaka ? 'true' : 'false' }},
            haslechon: {{ $haslechon ? 'true' : 'false' }},
            minDate() {
                if ({{ $hasbaka ? 'true' : 'false' }}) {
                    const day = new Date(this.today);
                    day.setDate(day.getDate() + 3);
                    this.hasbaka = true;
                    return day.toISOString().split('T')[0];
                } else if ({{ $haslechon ? 'true' : 'false' }}) {
                    const tomorrow = new Date(this.today);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    this.haslechon = true;
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
                    qty: 1, 
                    location: '', 
                    order: '', 
                    need_date: this.minDate,
                    need_time: '',
                    note: '', 
                    delivery_fee: 0 
                }
            ],
            allowMultiple: false,
            onChangeMultipleAddress() {
                this.deliveries = this.orders.map(order => ({
                    address: '',
                    name: '',
                    phone: '',
                    qty: order.qty,
                    location: '',
                    order: order.id,
                    need_date: this.minDate(),
                    need_time: '',
                    note: '',
                    delivery_fee: 0
                }));
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
            allHours: Array.from({ length: 24 }, (_, i) => i),
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

            recomputeCouponTotals() {
                this.totalDiscountAmount = 0;
                this.shippingDiscountAmount = 0;

                this.coupons.forEach(coupon => {
                    if (coupon.free_shipping) {
                        const allowedLocations = coupon.location.split('|').map(l => l.trim()).filter(l => l !== '');
                        console.log(this.location)
                        if (coupon.location) {
                            if (allowedLocations.includes(this.location) || allowedLocations.includes('all')) {
                                if (coupon.free_shipping_discount_amount === 100) {
                                    this.shippingDiscountAmount += this.deliveryFee;
                                } else {
                                    this.shippingDiscountAmount += this.deliveryFee * (coupon.free_shipping_discount_amount / 100);
                                }
                            } else {
                                this.shippingDiscountAmount = 0;
                            }
                        } else {
                            if (coupon.free_shipping_discount_amount === 100) {
                                this.shippingDiscountAmount += this.deliveryFee;
                            } else {
                                this.shippingDiscountAmount += this.deliveryFee * (coupon.free_shipping_discount_amount / 100);
                            }
                        }
                    } else {
                        if (coupon.discount_type === 'amount') {
                            this.totalDiscountAmount += parseFloat(coupon.discount ?? 0);
                        } else if (coupon.discount_type === 'percent') {
                            this.totalDiscountAmount += (this.orderAmount * parseFloat(coupon.discount ?? 0)) / 100;
                        }
                    }
                });

                console.log('this.this.shippingDiscountAmount', this.shippingDiscountAmount);

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
                    deliveryFeeFinal = deliveryFeeFinal - shippingDiscount;
                    deliveryFeeFinal = Math.max(deliveryFeeFinal, 0); // no negative
                }

                if (shippingDiscount > 0) {
                    this.totalDiscountAmount += shippingDiscount;
                }

                // Compute total
                let total = orderAmount + deliveryFeeFinal - couponDiscount;

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

                if (this.method == 'pickup') {
                    this.allowMultiple = false;
                }

                this.noNeededTime = false;
                this.noNeededDate = false;

                this.couponMessage = '';
                this.deliveryFees = [];
                this.removeCoupon();

                this.loadAutoCoupons();
            },

            mobileValidationMessage: '',
            nameValidationMessage: '',
            emailValidationMessage: '',
            noDeliveryAddress: false,
            delivery_address: '',

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
                
                if (this.hasErrorMessage) {
                    this.isSubmitting = false;
                    return;
                }

                if (this.method === 'delivery' && this.allowMultiple) {
                    if (!this.validateAllDeliveryFields()) {
                        this.noNeededTime = true;
                        this.isSubmitting = false;
                        return;
                    }

                    if (!this.validateAllQtyUsed()) {
                        this.isSubmitting = false;
                        return;
                    }
                }

                // Add dynamic fields
                formData.append('shipping_type', this.method);
                formData.append('coupons', JSON.stringify(this.coupons.map(c => c.code)));
                formData.append(
                    'coupons',
                    JSON.stringify(
                        this.coupons.map(c => {
                        let discountUsed = 0;

                        if (c.free_shipping) {
                            discountUsed =
                            c.free_shipping_discount_amount === 100
                                ? this.deliveryFee
                                : this.deliveryFee * (c.free_shipping_discount_amount / 100);
                        } else {
                            if (c.discount_type === 'amount') {
                            discountUsed = parseFloat(c.discount ?? 0);
                            } else if (c.discount_type === 'percent') {
                            discountUsed = (this.orderAmount * parseFloat(c.discount ?? 0)) / 100;
                            }
                        }

                        return {
                            ...c,
                            discount_used: parseFloat(discountUsed.toFixed(2))
                        };
                        })
                    )
                );


                formData.append('discount_amount', isNaN(this.totalDiscountAmount) ? 0 : this.totalDiscountAmount);
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

                        this.recomputeCouponTotals();
                        
                    } catch (error) {
                        console.error('There was a problem with the fetch operation:', error);
                    }
                }
            },

            async loadAutoCoupons() {
                const res = await fetch('{{ route('get-auto-coupons') }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });

                const result = await res.json();

                if (result.success && result.coupons.length > 0) {
                    this.coupons = result.coupons;
                    
                    result.coupons.forEach(autoCoupon => {
                        // Apply combination logic (same as submitCouponCode)
                        // Case 1: New coupon is non-combinable, and there are already applied coupons → skip
                        if (autoCoupon.combination_allowed === false && this.coupons.length > 0) {
                            return;
                        }

                        // Case 2: New coupon is combinable, but existing coupons include non-combinable → skip
                        if (autoCoupon.combination_allowed === true) {
                            const nonCombinableCoupon = this.coupons.find(c => c.combination_allowed === false);
                            if (nonCombinableCoupon) {
                                return;
                            }
                        }

                        // Prevent duplicate (if called multiple times)
                        if (!this.coupons.find(c => c.code === autoCoupon.code)) {
                            this.coupons.push(autoCoupon);
                        }
                    });

                    this.recomputeCouponTotals();
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

            async getDeliveryFeeForMultipleDelivery() {
                const branch = this.$refs.branch?.value;
                
                const locations = this.deliveries.map(d => d.location).filter(Boolean);

                if (locations.length === 0) return;

                try {
                    let response = await fetch('{{ route('cart.front.get_shipping_fee_for_multiple_address_new') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ locations }),
                    });

                    if (!response.ok) throw new Error('Network error');

                    const data = await response.json();

                    // Optional: update deliveryFees if backend returns breakdown

                    if (data.fees) {
                        this.deliveryFees = data.fees;

                        // 🛠 Assign the correct fee to each delivery based on location
                        this.deliveries.forEach(delivery => {
                            const feeObj = this.deliveryFees.find(f => f.location === delivery.location);
                            delivery.delivery_fee = feeObj ? feeObj.fee : 0;
                        });

                    } else {
                        // fallback if backend only returns a single total
                        const perDeliveryFee = data.fee / locations.length;
                        this.deliveries.forEach(delivery => {
                            delivery.delivery_fee = perDeliveryFee;
                        });

                        this.deliveryFees = locations.map(l => ({ location: l, fee: data.fee / locations.length }));
                    }


                    this.deliveryFees = data.fees;

                    this.deliveryFee = data.fee;

                    // Update total fee
                    // this.deliveryFee = this.deliveryFees.reduce((acc, item) => acc + item.fee, 0);

                    this.recomputeCouponTotals();

                } catch (e) {
                    console.error(e);
                }
            },

            init() {
                this.checkMultipleDeliveries();
                
                const cookie = document.cookie.split('; ').find(row => row.startsWith('shipping_method='));
                this.method = cookie ? cookie.split('=')[1] : 'pickup';

                this.need_date = this.minDate();

                this.loadAutoCoupons();
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

            // When checkbox is toggled
            toggleOrderSelection(delivery, order) {
                if (!delivery.orders) delivery.orders = [];

                const index = this.deliveries.indexOf(delivery);
                const existingIndex = delivery.orders.findIndex(o => o.product_id === order.product_id);

                if (existingIndex !== -1) {
                    delivery.orders.splice(existingIndex, 1); // Uncheck
                } else {
                    delivery.orders.push({
                        product_id: order.product_id,
                        qty: 1,
                        product: order.product
                    });
                }

                // Remove all deliveries after the current one
                this.deliveries.splice(index + 1);

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
                const { address, name, phone, location, need_date, need_time } = lastDelivery;
                if (!address || !name || !phone || !location || !need_date || !need_time) {
                    alert('Please fill in all required fields before adding another delivery address.');
                    return;
                }

                // If valid, add a new blank delivery
                this.deliveries.push({
                    address: '',
                    name: '',
                    phone: '',
                    orders: [],
                    location: '',
                    need_date: this.minDate,
                    need_time: '',
                    note: '',
                    delivery_fee: 0,
                });

                this.qtyValidationMessage = '';
            },

            validateDeliveryDateTime(delivery) {
                if (!delivery.need_date || !delivery.need_time) return;

                const selectedDateTime = new Date(`${delivery.need_date}T${delivery.need_time}`);
                const now = new Date();

                const diffInMs = selectedDateTime - now;
                const diffInHours = diffInMs / (1000 * 60 * 60);

                delivery.warningMessage = '';

                if (this.haslechon && diffInHours < 24) {
                    delivery.warningMessage = `⚠️ Warning! The date and time you've selected (${delivery.need_date} - ${this.formatTime(delivery.need_time)}) is less than 24 hours from now. Our standard processing time is at least 24 hours. However, you can still proceed by contacting our store directly at our <span class='underline text-blue-600 cursor-pointer' @click='openHotline = true'>Call Hotline</span> tab.`;
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

                if (!this.need_time) {
                    this.noNeededTime = true;
                    return;
                }

                if (!this.need_date) {
                    this.noNeededDate = true;
                    return;
                }

                if (this.noNeededTime) {
                    this.noNeededTime = false;
                }

                if (this.noNeededDate) {
                    this.noNeededDate = false;
                }

                const selectedDateTime = new Date(`${this.need_date}T${this.need_time}`);
                const now = new Date();

                const diffInMs = selectedDateTime - now;
                const diffInHours = diffInMs / (1000 * 60 * 60);

                this.warningMessage = '';

                if (this.haslechon && diffInHours <= 24) {
                    this.warningMessage = `⚠️ Warning! The date and time you've selected (${this.need_date} - ${this.formatTime(this.need_time)}) is less than 24 hours from now. Our standard processing time is at least 24 hours. However, you can still proceed by contacting our store directly at our <span class='underline text-blue-600 cursor-pointer' @click='openHotline = true'>Call Hotline</span> tab.`;
                    this.errorMessage = `⚠️ We are not able to accommodate your order base on your preferred date and time. Kindly refer to the warning message that appeared on your order screen or call our hotline at 89391221 / 89394665.  Thank you.`;
                } else {
                    this.errorMessage = '';
                    this.hasErrorMessage = false;
                }
            },

            async submit() {
                this.isSubmitting = true;
            },

            formatAMPM(hour) {
                const h = hour % 12 || 12;
                const suffix = hour < 12 ? 'AM' : 'PM';
                return `${h}:00 ${suffix}`;
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

                const timeStr = (hour < 10 ? '0' + hour : hour) + ':00';
                const fullStr = `${this.need_date} ${timeStr}`;

                return this.method === 'pickup'
                    ? this.disabledPickupDates.includes(fullStr)
                    : this.disabledDeliveryDates.includes(fullStr);
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

                    const timeStr = (hour < 10 ? '0' + hour : hour) + ':00';
                    return this.disabledDeliveryDates.includes(`${delivery.need_date} ${timeStr}`);
                };
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