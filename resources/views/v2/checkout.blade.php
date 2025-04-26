@extends('layouts.guest', ['page' => $page])

@section('content')

<div class="bg-cream">
    <div x-data="{ bankDepositProof: false, paymentCenterProof: false }" class="container">
        <form
            action="{{ route('cart.temp_sales') }}" 
            method="POST" 
            enctype="multipart/form-data"
            x-data="checkoutForm"
            @submit.prevent="submitForm" class="pb-20 px-4">
            <div class="pt-20 pb-5 px-4">
                <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center mt-10">Checkout</h1>
                <h3 class="font-medium lg:text-2xl text-center">You're almost there! Review your order details, choose your payment
                    method, and finalize your purchase to enjoy your Lydia's Lechon meal.</h3>
            </div>

            @if ($carts->isEmpty())
                <div class="flex flex-col items-center justify-center h-96">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <h2 class="text-xl font-semibold mt-4">Your cart is empty</h2>
                    <p class="text-gray-500">Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ route('home') }}" class="mt-4 bg-primary text-white px-4 py-2 rounded-md">Start Shopping</a>
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
                            @php
                                $total = 0;
                                $deliveryFee = 100;
                                foreach ($carts as $cart) {
                                    $total += $cart['price'];
                                }
                            @endphp
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
                                <span class="font-medium">₱{{ number_format($total, 2) }}</span>
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
    
                        <div class="my-3 px-4 " x-data="{ 
                            method: document.cookie.split('; ').find(row => row.startsWith('shipping_method=')).split('=')[1] || 'pickup', 
                        }">
                            <div class="font-bold my-2">Choose Pickup or Delivery</div>
                            <div class="flex items-center gap-4 mt-2">
                                <button class="px-6 py-3 rounded-md w-full transition border-2"
                                    :class="method === 'pickup' ? 'bg-green-700 text-white' : 'bg-gray-100 text-gray-700'"
                                    @click="method = 'pickup'">
                                    Pickup
                                </button>
    
                                <button class="px-6 py-3 rounded-md w-full transition border-2"
                                    :class="method === 'delivery' ? 'bg-green-700 text-white' : 'bg-gray-100 text-gray-700'"
                                    @click="method = 'delivery'">
                                    Delivery
                                </button>
                            </div>
                            <div class="mt-4">	
                                <label for="branches" class="font-bold">Select Branch <span
                                        class="text-red-700">*</span></label>
                                <select id="branches" name="delivery_branch" @change="getDeliveryFee" x-ref="branch" required
                                    class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                    <option selected value="">Choose a branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
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

                            
                            <div x-data="{
                                totalQty: 3,
                                orders: [
                                    {
                                        id: 1,
                                        name: 'Lechon-In-A-Box (2Kg)',
                                        price: '₱2,800.00',
                                        qty: 1
                                    },
                                    {
                                        id: 2,
                                        name: 'Petite (Lechon Cebu)',
                                        price: '₱9,800.00',
                                        qty: 1
                                    },
                                    {
                                        id: 3,
                                        name: 'Pancit con Lechon Medium (225G)',
                                        price: '₱475.00',
                                        qty: 1
                                    }
                                ]
                            }" x-show="method === 'delivery'" class="space-y-4">
                        
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
                                                            class="w-full border border-gray-300 p-2 rounded-md" placeholder="Full Name" />
                                                    </div>
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Contact Number</label>
                                                        <input type="text" x-model="delivery.phone"
                                                            class="w-full border border-gray-300 p-2 rounded-md" placeholder="+63..." />
                                                    </div>
                                                </div>
                                                <div class="w-full flex gap-4">
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Orders</label>
                                                        <select x-model="delivery.order" class="w-full border border-gray-300 p-2 rounded-md">
                                                            <template x-for="order in orders">
                                                                <option :value="order.id" x-text="order.name"></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Quantity</label>
                                                        <select x-model="delivery.qty" class="w-full border border-gray-300 p-2 rounded-md">
                                                            <template x-for="i in totalQty">
                                                                <option :value="i" x-text="i"></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="w-full flex gap-4">
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Select Date</label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                                            </svg>
                                                            </div>
                                                            <input id="default-datepicker" name="need_date" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-3" placeholder="Select date">
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
                                                            <input type="time" id="time" name="need_time" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " value="00:00" required />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="w-full flex gap-4">
                                                    <div class="w-full">
                                                        <label :for="'locations' + index" class="font-bold">Select Location <span
                                                                class="text-red-700">*</span></label>
                                                        <select x-model="delivery.location" :id="'locations' + index" name="location" @change="getDeliveryFeeForMultipleDelivery" required
                                                            class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                            <option selected value="">Choose a location</option>
                                                            @foreach ($locations as $location)
                                                                <option value="{{ $location->name }}">{{ $location->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="w-full">
                                                    <label class="font-bold block text-sm mb-1">Note</label>
                                                    <textarea x-model="delivery.address"
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
                        
                                    <div>
                                        <button type="button" @click="deliveries.push({ address: '', name: '', phone: '', qty: 1 })"
                                            class="bg-green-700 text-white px-4 py-2 rounded-md text-sm">Add Another Delivery</button>
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
                                    placeholder="" required />
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
                                        placeholder="Randy ..." required />

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
                                    <input type="tel" id="mobile" name="mobile" value="{{ auth()->check() ? auth()->user()->contact_mobile : '' }}"
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
                                        placeholder="email@email.com" required />

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
                                            <input id="default-datepicker" type="date" name="need_date" value="{{ old('need_date') }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 "
                                                placeholder="Select date">
                                        </div>
                                    </div>
                                    <div class="my-2 w-full lg:w-1/2">
                                        <label for="time"
                                            class="block mb-2 text-sm font-bold text-gray-900">Select Time <span
                                                class="text-red-700">*</span></label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd"
                                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <input type="time" id="time" name="need_time" value="{{ old('need_time') }}"
                                                class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                             value="00:00" required />
                                        </div>
                                    </div>
                                </div>
                                </template>
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
                            <button type="submit" class="bg-primary custom-btn btn-primary-dark text-center text-white px-6 py-4 mt-4 w-full rounded-md">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif


        </form>

        <x-bank-deposit-proof />
        <x-payment-center-proof />
    </div>
</div>


<x-footer-component />

<script>
    function checkoutForm() {
        return {
            method: 'pickup',
            deliveries: [{ address: '', name: '', phone: '', qty: 1, location: '', order: '' }],
            allowMultiple: false,
            couponCode: '',
            formEl: null,
            deliveryFee: 0,
            orderAmount: {{ $total }},
            totalAmount: 0,
            deposit: 0,
            deliveryFees: [],
            couponCode: '',
            showMessage: false,
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
            init() {
                this.method = document.cookie
                    .split('; ')
                    .find(row => row.startsWith('shipping_method='))
                    ?.split('=')[1] || 'pickup';
            },

            submitForm() {
                // Build a FormData object
                this.formEl = this.$root;
                const formData = new FormData(this.formEl);

                // Add dynamic fields
                formData.append('shipping_type', this.method);
                formData.append('coupon', this.couponCode);
                formData.append('delivery_fee', this.deliveryFee);
                formData.append('order_amount', this.orderAmount);
                formData.append('deposit', this.deposit);
                formData.append('total_amount', this.totalAmount);

                // If multiple addresses allowed, send as JSON
                if (this.allowMultiple) {
                    formData.append('deliveries', JSON.stringify(this.deliveries));
                }

                // Now do the POST
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
                    // Show success message or redirect
                    console.log('Order submitted:', data);
                    window.location.href = data.redirect || '/thank-you';
                })
                .catch(async error => {
                    let errText = await error.text();
                    console.error('Error:', errText);
                });
            },

            async getDeliveryFee() {
                const location = this.$refs?.location?.value;
                const branch = this.$refs?.branch?.value;

                if (location && branch) {

                    try {
                        let response = await fetch('{{route('cart.front.get_shipping_fee')}}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                location: location,
                                branch: branch,
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
                return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(total);
            },

            async getDeliveryFeeForMultipleDelivery() {
                const branch = this.$refs.branch?.value;
                const locations = this.deliveries.map(d => d.location).filter(Boolean);

                if (!branch || locations.length === 0) return;

                try {
                    let response = await fetch('{{ route('cart.front.get_shipping_fee_for_multiple_address') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ branch, locations }),
                    });

                    if (!response.ok) throw new Error('Network error');

                    const data = await response.json();

                    // Optional: update deliveryFees if backend returns breakdown
                    if (data.fees) {
                        this.deliveryFees = data.fees; // [{ location: 'Imus Cavite', fee: 100 }]
                    } else {
                        // fallback single total
                        this.deliveryFees = locations.map(l => ({ location: l, fee: data.fee / locations.length }));
                    }
                    
                    console.log('Delivery Fees:', this.deliveryFees);

                    // Update total fee
                    this.deliveryFee = this.deliveryFees.reduce((acc, item) => acc + item.fee, 0);

                } catch (e) {
                    console.error(e);
                }
            }

        }
    }
</script>

@endsection