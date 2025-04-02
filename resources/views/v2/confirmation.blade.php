@extends('layouts.guest', ['page' => $page])

@section('content')

    <div x-data="{ expanded: false }" class="bg-cream">
        <div class="pb-10 px-4 container">
            <div class="pt-20 pb-5 px-4 flex flex-col justify-start">
                <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary mt-10">order confirmation</h1>
                <h3 class="font-medium text-base lg:text-xl mt-2">Thank you for ordering with us! Your delicious Lydia's Lechon meal is on its way. We’ll send you an update once it’s ready for pickup or delivery. Your order details has also been sent to your email. Enjoy!</h3>
                <div class="font-medium text-base lg:text-xl mt-2 lg:mt-8">For any inquiries, you may call our <strong class="text-primary">hotline</strong> or <strong class="text-primary">contact us</strong>.</div>
            </div>

            <div class="flex flex-col px-4  lg:flex-row gap-4 mt-5 w-full max-w-lg justify-start">
                <button class="bg-primary border-primary border text-white px-6 py-4 w-full rounded-md">Go Shopping</button>
                <div class="border border-primary text-primary px-6 py-4 w-full text-center rounded-md">
                    <a href="{{ route('order-history') }}" class="text-center">View Order History</a>
                </div>
            </div>
    
            <div class="flex flex-col lg:flex-row gap-4 w-full justify-start px-4">
                <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mt-10 w-full lg:w-1/2">
                    <div class="px-4 py-3 border-b border-[#DFDFDF]">
                        <h2 class="text-lg lg:text-3xl font-semibold text-left">Order Details</h2>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>Order Number</div>
                        <div class="font-bold">000012348</div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>Order Date</div>
                        <div class="text-right">
                            <div>10/10/2024</div>
                            <div>7:58 PM</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>Order Type</div>
                        <div class="text-right">
                            <div>Pickup</div>
                            <div>SM San Lazaro Branch</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>Date and Time Needed</div>
                        <div class="text-right">
                            <div>10/14/2024</div>
                            <div>3:00 PM</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>Customer Name</div>
                        <div class="text-right">
                            <div>Juan Dela Cruz</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>Mobile Number</div>
                        <div class="text-right">
                            <div>09123456789</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>Email Address</div>
                        <div class="text-right">
                            <div>juandc@test.com</div>
                        </div>
                    </div>
                </div>
                    
                <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mt-10 w-full lg:w-1/2">
                    <div class="px-4 py-3 border-b border-[#DFDFDF]">
                        <h2 class="text-lg lg:text-3xl font-semibold text-left">Order Summary</h2>
                    </div>
                    
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
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
                
                    <!-- Order Items -->
                    <div class="flex flex-col items-center gap-8 px-4 py-3 border-b border-[#DFDFDF] w-full">
                        @foreach ($carts as $index => $cart)
                            <div class="flex gap-4 items-start w-full relative" 
                                x-show="{{ $index === 0 ? 'true' : 'expanded' }}" 
                                x-transition.duration.300ms>
                                
                                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="w-20 h-20 min-w-20 min-h-20 object-cover overflow-hidden rounded-md bg-center">
                                    <img src="{{ asset('images/'.$cart['image']) }}" alt="Checkout" class="w-20 h-20 object-cover">
                                </div>
                                
                                <div class="flex flex-col">
                                    <div class="font-bold">{{ $cart['name'] }}</div>
                                    <div class="text-sm text-gray-600 font-medium">QTY: {{ $cart['qty'] }}</div>
                                </div>
                                
                                <div class="text-sm text-black font-bold text-right w-full absolute right-0 bottom-0">{{ $cart['price'] }}</div>
                            </div>
                        @endforeach
                    </div>
                
                    <!-- View All Button -->
                    <button 
                        @click="expanded = !expanded" 
                        class="w-full text-center py-4 font-medium text-gray-400 border-b border-[#DFDFDF]  flex items-center justify-center gap-1">
                        <span class="text-gray-400" x-text="expanded ? 'HIDE' : 'VIEW ALL'"></span>
                        <svg x-bind:class="expanded ? 'rotate-180' : ''" class="w-6 h-6 transition-transform duration-300 ease-in-out" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>Subtotal</div>
                        <div class="text-right">
                            <div>₱13,075.00</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>Delivery Fee</div>
                        <div class="text-right">
                            <div>N/A</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div>Coupon (lydiaslechon25)</div>
                        <div class="text-right">
                            <div>-  ₱250</div>
                        </div>
                    </div>
                    <div class="flex items-center text-sm justify-between px-4 py-4 border-b border-[#DFDFDF]">
                        <div>Total</div>
                        <div class="text-right font-bold">
                            <div>₱13,025.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <x-footer-component />
    
        <x-bank-deposit-proof />
        <x-payment-center-proof />
    </div>
    
@endsection
