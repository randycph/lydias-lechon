@extends('layouts.guest', ['page' => $page])

@section('content')

@php
$lists = [
    [
        'image' => 'petite.png',
        'name' => 'Petite',
        'price' => '₱9,800',
        'description' => 'Good for 8-10 persons',
        'weight' => 'Cooked Weight Approx. 3-4Kgs',
        'serving' => 'Approximate Serving 1.5ft',
        'add' => 'Add ₱2,800 for Boneless Lechon Stuffed with Seafood Paella'
    ],
    [
        'image' => 'chochinillo.png',
        'name' => 'COCHINILLO',
        'price' => '₱10,800',
        'description' => 'Good for 8-10 persons',
        'weight' => 'Cooked Weight Approx. 3-4Kgs',
        'serving' => 'Approximate Serving 2ft',
        'free' => 'FREE Mexican Flavored Rice'
    ],
    [
        'image' => 'deleche.png',
        'name' => 'De leche',
        'price' => '₱12,800',
        'description' => 'Good for 12-15 persons',
        'weight' => 'Cooked Weight Approx. 5-6Kgs',
        'serving' => 'Approximate Serving 2.5ft',
        'add' => 'Add ₱2,800 for Boneless Lechon Stuffed with Seafood Paella'
    ],
    [
        'image' => 'small.png',
        'name' => 'Small',
        'price' => '₱9,800',
        'description' => 'Good for 20-25 persons',
        'weight' => 'Cooked Weight Approx. 8-11Kgs',
        'serving' => 'Approximate Serving 3ft',
        'add' => 'Add ₱4,800 for Boneless Lechon Stuffed with Seafood Paella'
    ],
    [
        'image' => 'medium.png',
        'name' => 'Medium',
        'price' => '₱17,800',
        'description' => 'Good for 30-45 persons',
        'weight' => 'Cooked Weight Approx. 12-15Kgs',
        'serving' => 'Approximate Serving 3.5ft',
        'add' => 'Add ₱5,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'large.png',
        'name' => 'Large',
        'price' => '₱21,800',
        'description' => 'Good for 40-50 persons',
        'weight' => 'Cooked Weight Approx. 16-20Kgs',
        'serving' => 'Approximate Serving 3.5-4ft',
        'add' => 'Add ₱6,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'xlarge.png',
        'name' => 'X-Large',
        'price' => '₱24,800',
        'description' => 'Good for 60-70 persons',
        'weight' => 'Cooked Weight Approx. 21-25Kgs',
        'serving' => 'Approximate Serving 4ft',
        'add' => 'Add ₱7,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'jumbo.png',
        'name' => 'Jumbo',
        'price' => '₱30,800',
        'description' => 'Good for 100-120 persons',
        'weight' => 'Cooked Weight Approx. 26-30Kgs',
        'serving' => 'Approximate Serving 4ft-4.5ft',
        'add' => 'Add ₱8,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'lechonbaka.png',
        'name' => 'Lechon Baka',
        'price' => '₱65,800',
        'description' => 'Good for 150-200 persons',
        'weight' => 'Cooked Weight Approx. 26-30Kgs',
        'serving' => 'Live Weight 100-120Kgs',
        'add' => 'Add ₱3,500 for Service Fee Of Delivery and Reheating'
    ]
];
@endphp

    <div x-data="{ lechonCart: false }" class="px-4 bg-cream">
        <div class="pb-20">
            <div class="pt-20 pb-5 px-4">
                <h1 class="text-4xl font-cubao font-medium text-primary text-center mt-10">Checkout</h1>
                <h3 class="font-medium text-center">You're almost there! Review your order details, choose your payment method, and finalize your purchase to enjoy your Lydia's Lechon meal.</h3>
            </div>
    
            <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md">
                <div class="px-4 py-3 border-b border-[#DFDFDF]">
                    <h2 class="text-lg font-semibold text-left">Order Summary</h2>
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

                <div class="flex flex-col items-center gap-8 px-4 py-3 border-b border-[#DFDFDF] w-full">
                    @foreach ($carts as $cart)
                        <div class="flex gap-4 items-start w-full relative">
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

                <!-- Coupon Code Section -->
                <div class="bg-white rounded-md mt-2 text-sm">
                    <div class="flex items-center border mx-3 border-gray-200 rounded-md overflow-hidden">
                        <input type="text" placeholder="Have a coupon code?" 
                            class="w-full p-3 outline-none border-none text-gray-700">
                        <button class="bg-green-700 text-white px-6 py-3 text-sm">Apply</button>
                    </div>

                    <!-- Subtotal Section -->
                    <div class="border-t border-gray-200 mt-2 pt-3 pb-1 gap-1 flex flex-col text-sm px-3">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-800">Subtotal</span>
                            <span class="font-medium">₱13,075.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-800">Delivery Fee</span>
                            <span class="font-medium">₱100.00</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 mt-2 py-4 gap-1 flex flex-col text-sm px-3">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-800">Total</span>
                            <span class="font-bold">₱13,075.00</span>
                        </div>
                    </div>
                </div>
            </div>


        </div>


    
        <x-footer-component />
    
        <x-lechon-cart-component />
    </div>
    
@endsection
