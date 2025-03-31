@extends('layouts.guest', ['page' => $page])

@section('content')

<div class="bg-cream">
    <div x-data="{ bankDepositProof: false, paymentCenterProof: false }" class="container">
        <div class="pb-20 px-4">
            <div class="pt-20 pb-5 px-4">
                <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center mt-10">Checkout</h1>
                <h3 class="font-medium lg:text-2xl text-center">You're almost there! Review your order details, choose your payment
                    method, and finalize your purchase to enjoy your Lydia's Lechon meal.</h3>
            </div>

            <div class="flex flex-col lg:flex-row gap-4 w-full mt-10">
                <div class="w-full order-1 lg:order-2 rounded-lg border bg-white border-[#DFDFDF] shadow-md ">
                    <div class="px-4 py-3 border-b border-[#DFDFDF]">
                        <h2 class="text-lg lg:text-3xl font-semibold text-left">Order Summary</h2>
                    </div>
                    <div class="flex items-center text-sm lg:text-base justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>3 items</div>
                        <div class="font-bold">₱13,175.00</div>
                    </div>
    
                    @php
                    $carts = [
                    [
                    'image' => 'checkout1.png',
                    'name' => 'Lechon-In-A-Box (2Kg)',
                    'price' => '₱2,800.00',
                    'qty' => 1
                    ],
                    [
                    'image' => 'checkout2.png',
                    'name' => 'Petite (Lechon Cebu)',
                    'price' => '₱9,800.00',
                    'qty' => 1
                    ],
                    [
                    'image' => 'checkout3.png',
                    'name' => 'Pancit con Lechon Medium (225G)',
                    'price' => '₱475.00',
                    'qty' => 1
                    ]
                    ];
                    @endphp
    
                    <div class="flex flex-col items-center gap-8 px-4 py-3 border-b border-[#DFDFDF] w-full">
                        @foreach ($carts as $cart)
                        <div class="flex gap-4 items-start w-full relative">
                            <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')"
                                class="w-20 h-20 min-w-20 min-h-20 object-cover overflow-hidden rounded-md bg-center">
                                <img src="{{ asset('images/'.$cart['image']) }}" alt="Checkout"
                                    class="w-20 h-20 object-cover">
                            </div>
                            <div class="flex flex-col">
                                <div class="font-bold">{{ $cart['name'] }}</div>
                                <div class="text-sm  text-gray-600 font-medium">QTY: {{ $cart['qty'] }}</div>
                            </div>
                            <div class="text-sm lg:text-base text-black font-bold text-right w-full absolute right-0 bottom-0">{{
                                $cart['price'] }}</div>
                        </div>
                        @endforeach
                    </div>
    
                    <!-- Coupon Code Section -->
                    <div class="bg-white rounded-md mt-2 text-sm">
                        <div class="flex items-center border mx-3 border-gray-200 rounded-md overflow-hidden">
                            <input type="text" placeholder="Have a coupon code?"
                                class="w-full p-3 outline-none border-none text-gray-700">
                            <button class="bg-green-700 text-white px-6 py-3 text-sm">Apply</button>
                        </div>
    
                        <!-- Subtotal Section -->
                        <div class="border-t border-gray-200 mt-2 pt-3 pb-1 gap-1 flex flex-col text-sm lg:text-base px-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-800">Subtotal</span>
                                <span class="font-medium">₱13,075.00</span>
                            </div>
                            <div class="flex justify-between lg:mt-2">
                                <span class="font-medium text-gray-800">Delivery Fee</span>
                                <span class="font-medium">₱100.00</span>
                            </div>
                        </div>
    
                        <div class="border-t border-gray-200 mt-2 py-4 gap-1 flex flex-col text-sm lg:text-base px-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-800">Total</span>
                                <span class="font-bold">₱13,075.00</span>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="w-full  order-2 lg:order-1 rounded-lg border bg-white border-[#DFDFDF] shadow-md">
                    <div>
                        <div class="px-4 py-3 border-b border-[#DFDFDF]">
                            <h2 class="text-lg lg:text-3xl font-semibold text-left">Delivery Information</h2>
                        </div>
    
                        <div class="my-3 px-4 " x-data="{ method: 'pickup' }">
                            <div class="font-bold">Choose Pickup or Delivery</div>
                            <div class="flex items-center gap-4 mt-2">
                                <button class="px-6 py-3 rounded-md w-full transition"
                                    :class="method === 'pickup' ? 'bg-green-700 text-white' : 'bg-gray-100 text-gray-700'"
                                    @click="method = 'pickup'">
                                    Pickup
                                </button>
    
                                <button class="px-6 py-3 rounded-md w-full transition"
                                    :class="method === 'delivery' ? 'bg-green-700 text-white' : 'bg-gray-100 text-gray-700'"
                                    @click="method = 'delivery'">
                                    Delivery
                                </button>
                            </div>
                            <div class="mt-4">
                                <label for="countries" class="font-bold">Select Branch <span
                                        class="text-red-700">*</span></label>
                                <select id="countries"
                                    class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected>Choose a branch</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="FR">France</option>
                                    <option value="DE">Germany</option>
                                </select>
                            </div>
                        </div>
                    </div>
    
                    <div>
                        <div class="px-4 py-3 border-b border-[#DFDFDF]">
                            <div class="font-bold">Contact Information</div>
    
                            <div class="mt-3">
                                <div class="my-2">
                                    <label for="name"
                                        class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Full Name <span
                                            class="text-red-700">*</span></label>
                                    <input type="text" id="name"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Randy ..." required />
                                </div>
                                <div class="my-2">
                                    <label for="phone"
                                        class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Mobile Number
                                        <span class="text-red-700">*</span></label>
                                    <input type="tel" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" id="phone"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="+63" required />
                                </div>
                                <div class="my-2">
                                    <label for="email"
                                        class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Email <span
                                            class="text-red-700">*</span></label>
                                    <input type="email" id="email"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="email@email.com" required />
                                </div>
                                <div class="my-2">
                                    <label for="date"
                                        class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Select Date <span
                                            class="text-red-700">*</span></label>
                                    <div class="relative max-w-sm">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input datepicker datepicker-autohide id="default-datepicker" type="text"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select date">
                                    </div>
                                </div>
                                <div class="my-2">
                                    <label for="time"
                                        class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Select Time <span
                                            class="text-red-700">*</span></label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="time"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                                <div class="my-2">
                                    <label for="time"
                                        class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Instruction</label>
                                    <div class="relative">
                                        <textarea
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            name="" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
    
                            </div>
    
                            <div class="mt-5">
                                <div class="font-bold">Choose Payment Method</div>
    
                                <div class="mt-3">
                                    <div x-data="{ selected: '' }" class="space-y-4">
                                        @foreach([
                                        ['id' => 'bdo', 'logo' => '/images/bdo.png', 'name' => 'Bank Transfer or Deposit ',
                                        'description' => 'Bank Transfer or Deposit'],
                                        ['id' => 'gcash', 'logo' => '/images/gcash.png', 'name' => 'GCash', 'description' =>
                                        'GCash'],
                                        ['id' => 'maya', 'logo' => '/images/maya.png', 'name' => 'Maya', 'description' =>
                                        'Maya'],
                                        ['id' => 'card', 'logo' => '/images/visa.png', 'name' => 'Credit/Debit Card',
                                        'description' => 'Credit/Debit Card', 'click' => 'bankDepositProof = true'],
                                        ['id' => 'mlhuillier', 'logo' => '/images/ml.png', 'name' => 'Payment Center',
                                        'description' => 'Payment Center', 'click' => 'paymentCenterProof = true'],
                                        ] as $option)
                                        <button @click="selected = '{{ $option['id'] }}'; {{ $option['click'] ?? '' }}"
                                            :class="{ 'border-blue-500 bg-blue-100': selected === '{{ $option['id'] }}' }"
                                            class="flex items-start flex-col p-4 border rounded-lg w-full transition duration-300 ease-in-out hover:bg-gray-100">
                                            <img src="{{ $option['logo'] }}" alt="{{ $option['name'] }}" class="h-10 mr-4">
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $option['name'] }}</p>
                                            </div>
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
    
                                <div class="bg-primary text-center text-white px-6 py-4 mt-4 w-full rounded-md">
                                    <a href="{{ route('confirmation') }}" class="text-center">Place Order</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <x-bank-deposit-proof />
        <x-payment-center-proof />
    </div>
</div>


<x-footer-component />

@endsection