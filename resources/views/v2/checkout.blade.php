@extends('layouts.guest', ['page' => $page])

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
            <div
    
            
                class="flex flex-col lg:flex-row gap-4 w-full mt-10">
                
                @csrf
                <div class="w-full order-1 lg:order-2 rounded-lg border bg-white border-[#DFDFDF] shadow-md ">
                    <div class="px-4 py-3 border-b border-[#DFDFDF]">
                        <h2 class="text-lg lg:text-3xl font-semibold text-left">Order Summary</h2>
                    </div>
                    <div class="flex items-center text-sm lg:text-base justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>{{ count($carts) }} items</div>
                        <div class="font-bold">
                            ₱{{ number_format($total, 2) }}
                        </div>
                    </div>
    
    
                    <div class="flex flex-col items-center gap-8 px-4 py-3 border-b border-[#DFDFDF] w-full">
                        @foreach ($carts as $cart)
                        <div class="flex gap-4 items-start w-full relative">
                            <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')"
                                class="w-20 h-20 min-w-20 min-h-20 object-cover overflow-hidden rounded-md bg-center">
                                <img onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}'" src="{{ $cart['photo'] ??  asset('storage/products/' . $cart?->product?->photo_primary) }}" alt="Checkout"
                                    class="w-20 h-20 object-cover">
                            </div>
                            <div class="flex flex-col">
                                <div class="font-bold">{{ $cart['name'] ?? $cart?->product?->name }}</div>
                                <div class="text-sm  text-gray-600 font-medium">QTY: {{ $cart['qty'] }}</div>
                            </div>
                            <div class="text-sm lg:text-base text-black font-bold text-right w-full absolute right-0 bottom-0">
                                ₱{{ number_format($cart['price'], 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
    
                    <!-- Coupon Code Section -->
                    <div class="bg-white rounded-md mt-2 text-sm">
                        <div class="flex items-center border mx-3 border-gray-200 rounded-md overflow-hidden">
                            <input x-model="couponCode" type="text" placeholder="Have a coupon code?"
                                class="w-full p-3 outline-none border-none text-gray-700">
                            <button @click="submitCouponCode" type="button" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 text-sm">Apply</button>
                        </div>
                        <div x-show="showMessage" class="text-[#28A745] mx-5 py-2">Voucher code successfully applied.</div>
    
                        <!-- Subtotal Section -->
                        <div class="border-t border-gray-200 mt-2 pt-3 pb-1 gap-1 flex flex-col text-sm lg:text-base px-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-800">Subtotal</span>
                                <span class="font-medium" >₱{{ number_format($total, 2) }}</span>
                            </div>
                            <template x-if="deliveryFees.length == 0 && !allowMultiple">
                            <div class="flex justify-between lg:mt-2">
                                <span class="font-medium text-gray-800">Delivery Fee</span>
                                <span class="font-medium" x-text="deliveryFee > 0 ? '₱' + deliveryFee : 'Free'"></span>
                            </div>
                            </template>
                            <template x-if="deliveryFees.length > 0">
                                <div class="flex flex-col gap-1 mt-2">
                                    <template x-for="(item, i) in deliveryFees" :key="i">
                                        <div class="flex justify-between text-gray-500 text-sm">
                                            <span x-text="'• ' + item.location"></span>
                                            <span x-text="'₱' + item.fee.toLocaleString()"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <div class="flex justify-between lg:mt-2" x-show="showMessage">
                                <span class="font-medium text-red-700 italic">Coupon (<span x-text="couponCode"></span>) <span class="text-xs underline cursor-pointer" @click="removeCoupon">Remove Coupon</span></span>
                                <span class="font-medium italic text-red-700">- ₱250.00</span>
                            </div>
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
                            
                            <template x-if="!allowMultiple">
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
                            </template>

                            
                            <div x-show="method === 'delivery'" class="space-y-4">
                        
                            <div class="flex items-center me-4 my-4">
                                <input x-model="allowMultiple" checked id="multiple-address" type="checkbox" value="" class="w-5 h-5 text-primary bg-gray-100 border-gray-300 rounded-sm focus:ring-primary-dark focus:ring-2">
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
                                                        <input type="tel" x-mask="+99 999 999 9999" x-model="delivery.phone"
                                                            class="w-full border border-gray-300 p-2 rounded-md" placeholder="+63..." />
                                                    </div>
                                                </div>
                                                
                                                <!-- Order Dropdown -->
                                                <div class="w-full flex gap-4">
                                                    <div class="w-full">
                                                        <label class="font-bold block text-sm mb-1">Order</label>
                                                        <select @change="delivery.order = JSON.parse($event.target.value); updateAvailableQty(delivery)" class="w-full border border-gray-300 p-2 rounded-md">
                                                            <option selected value="">Select Order</option>
                                                            <template x-for="(order, index) in getAvailableOrders()" :key="index">
                                                                <option 
                                                                    :value="JSON.stringify(order)" 
                                                                    :disabled="order.qty === 0"
                                                                    x-text="order.product.name + (order.qty === 0 ? ' (Unavailable)' : '')"
                                                                ></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Quantity Dropdown -->
                                                <div class="w-full flex gap-4">
                                                    <div class="w-full">
                                                        <label class="font-bold block text-sm mb-1">Quantity</label>
                                                        <select x-model="delivery.qty" class="w-full border border-gray-300 p-2 rounded-md">
                                                            <option selected value="">Select Quantity</option>
                                                            <template x-if="delivery.order">
                                                                <template x-for="i in delivery.availableQty">
                                                                    <option :value="i" x-text="i"></option>
                                                                </template>
                                                            </template>
                                                        </select>
                                                    </div>
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
                                                                type="time" id="time" x-model="delivery.need_time" name="need_time"
                                                                @change="validateDeliveryDateTime(delivery)"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                            >
                                                                <option value="">Select Hour</option>
                                                                <template x-for="hour in 24" :key="hour">
                                                                    <option :value="(hour < 10 ? '0' + hour : hour) + ':00'" 
                                                                            x-text="(hour < 10 ? '0' + hour : hour) + ':00'">
                                                                    </option>
                                                                </template>
                                                            </select>
                                                        
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
                                                <button type="button" @click="deliveries.splice(index, 1)"
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
                            <template x-if="!allowMultiple">
                                <div class="">
                                    <label for="delivery_address"
                                    class="block mb-2 font-bold text-gray-900">Delivery Address <span
                                        class="text-red-700">*</span></label>
                                <input type="text" id="delivery_address" name="delivery_address" value="{{ auth()->check() ? auth()->user()->address_street : '' }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                    placeholder="" />
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
                                        class="block mb-2 text-sm font-bold text-gray-900">Full Name <span
                                            class="text-red-700">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        placeholder="" required />

                                    @error('name')
                                        <div class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="my-2">
                                    <label for="mobile"
                                        class="block mb-2 text-sm font-bold text-gray-900">Mobile Number
                                        <span class="text-red-700">*</span></label>
                                    <input type="tel" x-mask="+99 999 999 9999" id="mobile" name="mobile" value="{{ auth()->check() ? auth()->user()->contact_mobile : '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        placeholder="+63" required />

                                    @error('mobile')
                                        <div class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="my-2">
                                    <label for="email"
                                        class="block mb-2 text-sm font-bold text-gray-900">Email <span
                                            class="text-red-700">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        placeholder="" required />

                                    @error('email')
                                        <div class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
                                            <input @change="validateDateTime" x-model="need_date" type="date" name="need_date" value="{{ old('need_date') }}"
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
                                                <template x-for="hour in 24" :key="hour">
                                                    <option :value="(hour < 10 ? '0' + hour : hour) + ':00'" 
                                                            x-text="(hour < 10 ? '0' + hour : hour) + ':00'">
                                                    </option>
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
                                    action="{{ route('paymaya.pay') }}" method="POST" enctype="multipart/form-data" class="flex flex-col">
                                    @csrf
                                    <input type="hidden" name="sales_header_id" :value="paymentDetails.order_number">
                        
                                    <div class="pb-4">
                                        <img src="http://172.16.11.50/images/payment/pay-maya.jpg">
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
                                        <img src="http://172.16.11.50/images/gcash.png" alt="GCash QR" class="mx-auto mt-2 w-40 h-40 object-contain">
                                    </div>

                                    <input type="hidden" id="payment_dt" name="payment_dt">
                                    <input type="hidden" id="ref_no" name="ref_no">
                        
                                    <!-- Amount -->
                                    <div class="mt-4">
                                        <label class="font-semibold block mb-1">Amount to Pay:</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                                ₱
                                            </span>
                                            <input required name="amount" :value="paymentDetails.amount"  x-mask:dynamic="$money($input)" type="text" id="money" class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full border-gray-300 p-2.5  " placeholder="">
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
    function checkoutForm() {
        return {
            paymentDetails: {
                sales_header_id: '',
                order_number: '',
                customer_contact_number: '',
                customer_name: '',
                amount: '',
                signature: '',
                saved_items: ''
            },
            paymentMode: '',
            currentDate: new Date()?.toISOString()?.split('T')[0],
            method: 'pickup',
            depositModal: false,
            orders: @json($carts) || [],
            totalQty: 1,
            deliveries: [
                { 
                    address: '', 
                    name: '',
                    phone: '', 
                    qty: 1, 
                    location: '', 
                    order: '', 
                    need_date: new Date()?.toISOString()?.split('T')[0], 
                    need_time: new Date()?.toTimeString()?.slice(0,5), 
                    note: '', 
                    delivery_fee: 0 
                }
            ],
            allowMultiple: false,
            couponCode: '',
            formEl: null,
            deliveryFee: 0,
            orderAmount: {{ $total }},
            totalAmount: 0,
            deposit: '',
            rawDeposit: '',
            deliveryFees: [],
            couponCode: '',
            showMessage: false,
            need_date: '',
            need_time: '',
            warningMessage: '',
            errorMessage: '',
            hasErrorMessage: false,
            isSubmitting: false,
            isPaymentLoading: false,
            noNeededTime: false,
            noNeededDate: false,
            submitCouponCode() {
                if (this.couponCode != '') {
                    this.showMessage = true;
                } else {
                    this.showMessage = false;
                }
            },
            removeCoupon() {
                this.couponCode = '';
                this.showMessage = false;
            },

            changeMethod(method) {
                this.method = method;
                document.cookie = `shipping_method=${method}; path=/;`;
            },

            submitForm() {
                this.formEl = document.getElementById('checkoutForm');

                const formData = new FormData(this.formEl);

                this.isSubmitting = true;
                this.noNeededDate = false;
                this.noNeededTime = false;

                if (!this.need_time) {
                    this.noNeededTime = true;
                    this.isSubmitting = false;
                    return;
                }

                if (!this.need_date) {
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

                // Add dynamic fields
                formData.append('shipping_type', this.method);
                formData.append('coupon', this.couponCode);
                formData.append('delivery_fee', this.deliveryFee);
                formData.append('order_amount', this.orderAmount);
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
                        
                    } catch (error) {
                        console.error('There was a problem with the fetch operation:', error);
                    }
                }
            },

            computeTotal() {
                let total = parseFloat(this.orderAmount) + parseFloat(this.deliveryFee);
                this.totalAmount = total;
                this.deposit = this.totalAmount.toFixed(2);

                this.$nextTick(() => {
                    let input = this.$root.querySelector('input[name="deposit"]');
                    if (input) {
                        input.dispatchEvent(new Event('input'));
                    }
                });
                
                return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(total);
            },

            async getDeliveryFeeForMultipleDelivery() {
                const branch = this.$refs.branch?.value;
                const locations = this.deliveries.map(d => d.location).filter(Boolean);

                if (locations.length === 0) return;

                try {
                    let response = await fetch('{{ route('cart.front.get_shipping_fee_for_multiple_address') }}', {
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



                    // Update total fee
                    this.deliveryFee = this.deliveryFees.reduce((acc, item) => acc + item.fee, 0);

                } catch (e) {
                    console.error(e);
                }
            },

            init() {
                this.checkMultipleDeliveries();
                
                const cookie = document.cookie.split('; ').find(row => row.startsWith('shipping_method='));
                this.method = cookie ? cookie.split('=')[1] : 'pickup';
            },

            checkMultipleDeliveries() {
                let multipleItems = this.orders.length > 1;
                let multipleQty = this.orders.some(order => order.qty > 1);
                
                // this.allowMultiple = multipleItems || multipleQty;
            },

            updateAvailableQty(delivery) {
                console.log(deliver)
                if (!delivery.order) {
                    delivery.availableQty = [];
                    return;
                }

                const totalProductQty = delivery.order.qty;
                const currentQty = parseInt(delivery.qty) || 0;

                // Subtract current delivery's qty from total assigned to avoid double-counting
                const alreadyAssignedQty = this.deliveries
                    .filter(d => d.order && d.order.id === delivery.order.id && d !== delivery)
                    .reduce((sum, d) => sum + (parseInt(d.qty) || 0), 0);

                const remainingQty = totalProductQty - alreadyAssignedQty;
                const maxAvailable = Math.min(totalProductQty, remainingQty); // no +currentQty here

                delivery.availableQty = Array.from({ length: maxAvailable }, (_, i) => i + 1);

                // Reset qty if it's now over max
                if (currentQty > maxAvailable) {
                    delivery.qty = '';
                }

                console.log({ totalProductQty, alreadyAssignedQty, currentQty, remainingQty, maxAvailable });
            },

            getAvailableOrders() {
                let availableOrders = JSON.parse(JSON.stringify(this.orders));

                for (let delivery of this.deliveries) {
                    if (delivery.order) {
                        let matchingOrder = availableOrders.find(o => o.id === delivery.order.id);
                        if (matchingOrder) {
                            matchingOrder.qty -= (parseInt(delivery.qty) || 0);
                        }
                    }
                }

                // ✅ Return all orders (even if qty is 0)
                return availableOrders;
            },

            canAddMoreDeliveries() {
                // Get total qty across all products
                let totalAvailableQty = this.orders.reduce((sum, order) => sum + order.qty, 0);

                // Get total qty already assigned to deliveries
                let assignedQty = this.deliveries.reduce((sum, delivery) => sum + (parseInt(delivery.qty) || 0), 0);

                return assignedQty < totalAvailableQty;
            },

            validateBeforeAddDelivery() {
                const lastDelivery = this.deliveries[this.deliveries.length - 1];

                if (!lastDelivery || !lastDelivery.order || !lastDelivery.qty) {
                    alert('Please select a product and quantity before adding another new delivery address.');
                    return;
                }

                if (!lastDelivery || !lastDelivery.address || !lastDelivery.name || !lastDelivery.phone || !lastDelivery.location || !lastDelivery.need_date || !lastDelivery.need_time) {
                    alert('Please fill in all required fields before adding another new delivery address.');
                    return;
                }

                // If valid, add a new blank delivery
                this.deliveries.push({
                    address: '',
                    name: '',
                    phone: '',
                    qty: 1,
                    location: '',
                    order: '',
                    need_date: new Date().toISOString()?.split('T')[0],
                    need_time: new Date().toTimeString()?.slice(0,5),
                    note: '',
                    delivery_fee: 0,
                });
            },

            validateDeliveryDateTime(delivery) {
                if (!delivery.need_date || !delivery.need_time) return;

                const selectedDateTime = new Date(`${delivery.need_date}T${delivery.need_time}`);
                const now = new Date();

                const diffInMs = selectedDateTime - now;
                const diffInHours = diffInMs / (1000 * 60 * 60);

                delivery.warningMessage = '';

                if (diffInHours < 24) {
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

                if (diffInHours <= 24) {
                    this.warningMessage = `⚠️ Warning! The date and time you've selected (${this.need_date} - ${this.formatTime(this.need_time)}) is less than 24 hours from now. Our standard processing time is at least 24 hours. However, you can still proceed by contacting our store directly at our <span class='underline text-blue-600 cursor-pointer' @click='openHotline = true'>Call Hotline</span> tab.`;
                    this.errorMessage = `⚠️ We are not able to accommodate your order base on your preferred date and time. Kindly refer to the warning message that appeared on your order screen or call our hotline at 89391221 / 89394665.  Thank you.`;
                } else {
                    this.errorMessage = '';
                    this.hasErrorMessage = false;
                }
            },

            async submit() {
                this.isSubmitting = true;
            }
        }
    }
</script>

@section('alpine.plugins')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
@endsection


@endsection