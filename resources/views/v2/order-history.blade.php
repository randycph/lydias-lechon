@extends('layouts.guest', ['page' => $page])

@section('content')

<div x-data="{ checkoutModal: false }" class="bg-cream">
    <div class="py-20 px-4">
        <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mt-10">
            <div class="px-6 py-4 border-b border-[#DFDFDF]">
                <h2 class="text-lg font-bold text-primary text-left uppercase">my account</h2>
            </div>
            <div class="flex items-start font-bold gap-4 flex-col px-6 py-5 border-b border-[#DFDFDF]">
                <div class="">Hi, Juan Dela Cruz!</div>
                <div class="text-tertiary  underline">Sign out</div>
            </div>
            <div class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">

                <div class="group relative items-center flex pl-6 hover:text-tertiary  cursor-pointer">
                    <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 opacity-0 group-hover:opacity-100 transition duration-200"></div>
                    <div class="py-2">Manage Account</div>
                </div>
                
                <div class="group relative items-center flex pl-6 hover:text-tertiary  cursor-pointer">
                    <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 opacity-0 group-hover:opacity-100 transition duration-200"></div>
                    <div class="py-2">Change Password</div>
                </div>
                
                <div class="group relative items-center flex pl-6 hover:text-tertiary text-tertiary cursor-pointer">
                    <div class="absolute left-0 top-0 h-10 w-1 bg-orange-500 opacity-100 group-hover:opacity-100 transition duration-200"></div>
                    <div class="py-2">Order History</div>
                </div>
            </div>
        </div>

        <div class="font-bold text-lg mt-10 mb-5">
            Order History
        </div>

        <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mt-5">
            <div class="px-6 py-4 border-b border-[#DFDFDF] flex items-center justify-between">
                <h2 class="font-semibold">Order #12345678</h2>
                <div class="font-semibold text-tertiary uppercase">Unpaid</div>
            </div>
            <div class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">
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

                    <div class="flex items-center justify-between w-full">
                        <div class="text-sm text-black font-bold">{{ count($carts) }} items</div>
                        <div class="text-sm text-black font-bold">₱13,075.00</div>
                    </div>
                </div>

                <div class="w-full flex flex-col gap-2 px-4 mt-4">
                    <button type="button"
                        class="text-white bg-primary hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center">
                        Pay Now
                    </button>
                    <button type="button"
                        class="text-primary border hover:text-white border-primary bg-white hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center">
                        Track Order
                    </button>
                    <button type="button"
                        class="text-white bg-tertiary hover:bg-secondary font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center">
                        Cancel Order
                    </button>
                </div>
                
            </div>
        </div>

        <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mt-5">
            <div class="px-6 py-4 border-b border-[#DFDFDF] flex items-center justify-between">
                <h2 class="font-semibold">Order #12345678</h2>
                <div class="font-semibold text-primary uppercase">Completed</div>
            </div>
            <div class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">
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

                    <div class="flex items-center justify-between w-full">
                        <div class="text-sm text-black font-bold">{{ count($carts) }} items</div>
                        <div class="text-sm text-black font-bold">₱13,075.00</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md mt-5">
            <div class="px-6 py-4 border-b border-[#DFDFDF] flex items-center justify-between">
                <h2 class="font-semibold">Order #12345678</h2>
                <div class="font-semibold text-blue uppercase">Payment Verified</div>
            </div>
            <div class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">
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

                    <div class="flex items-center justify-between w-full">
                        <div class="text-sm text-black font-bold">{{ count($carts) }} items</div>
                        <div class="text-sm text-black font-bold">₱13,075.00</div>
                    </div>
                </div>

                <div class="w-full flex flex-col gap-2 px-4 mt-4">
                    <button type="button"
                        class="text-primary border hover:text-white border-primary bg-white hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center">
                        Track Order
                    </button>
                    <button @click="checkoutModal = true" type="button"
                        class="text-white bg-tertiary hover:bg-secondary font-medium rounded-lg w-full sm:w-auto px-5 py-3.5 text-center">
                        Cancel Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="checkoutModal"
        x-transition
        class="relative z-10"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal content -->
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <!-- Modal body -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-end">
                        <div class="flex justify-end">
                            <button @click="checkoutModal = false" class="self-end text-2xl text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-base font-semibold text-gray-900" id="modal-title">Are you sure you want to cancel your order?</h3>
                            <div class="mt-2">
                            <p class="text-sm text-gray-500">This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full flex flex-col gap-2 px-4 pt-4 pb-6">
                    <button  @click="checkoutModal = false" type="button"
                        class="text-primary border hover:text-white border-primary bg-white hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3 text-center">
                        Yes
                    </button>
                    <button  @click="checkoutModal = false" type="button"
                        class="text-white bg-primary hover:bg-primary font-medium rounded-lg w-full sm:w-auto px-5 py-3 text-center">
                        No
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


  
    
<x-footer-component />

@endsection