@extends('layouts.guest', ['page' => $page])

@section('content')

<div
    x-data="{ 
        cancelOrderModal: false, 
        successPaymentModal: false, 
        trackOrderModal: false, 
        paymentMethodModal: false, 
        bankDepositProof: false, 
        paymentCenterProof: false,
        paymentMethod: '',
        choosePaymentMethod() {
            if (this.paymentMethod == '') {
                return;closeBankParentModal
            }

            if (this.paymentMethod == 'bank') {
                this.bankDepositProof = true;
            } else if (this.paymentMethod == 'paymentCenter') {
                this.paymentCenterProof = true;
            }

        },
        closeBankDepositProof() {
                console.log('xxxx')
            this.bankDepositProof = false;
        }
    }"
    @closeBankParentModal.window="closeBankDepositProof"
    x-init="
        const lockBody = () => {
            const anyOpen = cancelOrderModal || successPaymentModal || trackOrderModal || paymentMethodModal || bankDepositProof || paymentCenterProof;
            if (anyOpen) {
                document.body.classList.add('overflow-hidden');
            } else {
                document.body.classList.remove('overflow-hidden');
            }
        };
        $watch('trackOrderModal', lockBody);
        $watch('cancelOrderModal', lockBody);
        $watch('successPaymentModal', lockBody);
        $watch('paymentMethodModal', lockBody);
        $watch('bankDepositProof', lockBody);
        $watch('paymentCenterProof', lockBody);
    ">
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
            
                    <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md">
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
            
                            <div class="w-full flex flex-col gap-2 px-4 mt-4 lg:flex-row justify-between">
                                <div class="flex flex-col lg:flex-row gap-2 order-1 lg:order-2">
                                    <button @click="paymentMethodModal = true" type="button"
                                        class="order-1 lg:order-2 text-white bg-primary custom-btn btn-primary-dark font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                        Pay Now
                                    </button>
                                    <button @click="trackOrderModal = true" type="button"
                                        class="order-2 lg:order-1 custom-btn btn-primary-dark text-primary border hover:text-white border-primary bg-white hover:bg-primary-dark font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                        Track Order
                                    </button>
                                </div>
                                <div class="order-2 lg:order-1">
                                    <button @click="cancelOrderModal = true" type="button"
                                        class="text-white custom-btn btn-tertiary-dark bg-tertiary hover:bg-secondary font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                        Cancel Order
                                    </button>
                                </div>
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
            
                            <div class="w-full flex flex-col gap-2 px-4 mt-4 lg:flex-row justify-between">
                                <div class="flex flex-col lg:flex-row gap-2 order-1 lg:order-2">
                                    <button @click="trackOrderModal = true" type="button"
                                        class="order-2 lg:order-1 text-primary custom-btn btn-primary-dark border hover:text-white border-primary bg-white hover:bg-primary-dark font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                        Track Order
                                    </button>
                                </div>
                                <div class="order-2 lg:order-1">
                                    <button @click="cancelOrderModal = true" type="button"
                                        class="text-white custom-btn btn-tertiary-dark bg-tertiary hover:bg-secondary font-medium rounded-md w-full sm:w-auto px-5 py-3.5 text-center">
                                        Cancel Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <button  @click="cancelOrderModal = false" type="button"
                        class="text-primary border hover:text-white border-primary bg-white hover:bg-primary-dark font-medium rounded-lg w-full sm:w-auto px-5 py-3 text-center">
                        Yes
                    </button>
                    <button  @click="cancelOrderModal = false" type="button"
                        class="text-white bg-primary hover:bg-primary font-medium rounded-lg w-full sm:w-auto px-5 py-3 text-center">
                        No
                    </button>
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
                            <div class="text-slate-500 font-semibold mt-2">Order #12345678</div>
                            <div class="text-slate-500 font-semibold">Oct 20, 2024</div>
                            <div class="mt-5 px-4">
                                <ol class="relative border-s border-gray-200 dark:border-gray-700">                  
                                    <li class="mb-10 ms-8">            
                                        <span class="absolute flex items-center justify-center w-10 h-10 bg-[#ECECEC] border border-[#ACACAC] rounded-full -start-5 p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-[#ACACAC]">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                                            </svg>
                                        </span>
                                        <h3 class="mb-1 font-semibold text-[#ACACAC]">Order Placed</h3>
                                        <time class="block mb-2 text-xs font-normal leading-none text-[#ACACAC] float-right mt-1">10/20/24 5:43PM</time>
                                        <p class="text-sm font-normal text-[#ACACAC]">Your order has been received.</p>
                                    </li>
                                    <li class="mb-10 ms-8">
                                       <span class="absolute flex items-center justify-center w-10 h-10 bg-[#ECECEC] border border-[#ACACAC] rounded-full -start-5 p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-[#ACACAC]">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                            </svg>
                                        </span>
                                        <h3 class="mb-1 font-semibold text-[#ACACAC]">Payment Confirmed</h3>
                                        <time class="block mb-2 text-xs font-normal leading-none text-[#ACACAC] float-right mt-1">10/20/24 5:45PM</time>
                                        <p class="text-sm font-normal text-[#ACACAC]">Waiting for your payment confirmation.</p>
                                    </li>
                                    <li class="mb-10 ms-8">
                                       <span class="absolute flex items-center justify-center w-10 h-10 bg-[#ECECEC] border border-[#ACACAC] rounded-full -start-5 p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-[#ACACAC]">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                            </svg>
                                          
                                        </span>
                                        <h3 class="mb-1 font-semibold text-[#ACACAC]">Order Processed</h3>
                                        <time class="block mb-2 text-xs font-normal leading-none text-[#ACACAC] float-right mt-1">10/20/24 6:12PM</time>
                                        <p class="text-sm font-normal text-[#ACACAC]">We are currently preparing your order.</p>
                                    </li>
                                    <li class="mb-10 ms-8">
                                       <span class="absolute flex items-center justify-center w-10 h-10 bg-[#ECECEC] border border-[#ACACAC] rounded-full -start-5 p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-[#ACACAC]">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                            </svg>
                                        </span>
                                        <h3 class="mb-1 font-semibold text-[#ACACAC]">Ready to Pickup</h3>
                                        <time class="block mb-2 text-xs font-normal leading-none text-[#ACACAC] float-right mt-1">10/20/24 6:43PM</time>
                                        <p class="text-sm font-normal text-[#ACACAC]">Your order is ready for pickup.</p>
                                    </li>
                                    <li class="mb-10 ms-8">
                                       <span class="absolute flex items-center justify-center w-10 h-10 bg-[#CFEDD6] border border-primary rounded-full -start-5 p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-primary">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                        </span>
                                        <h3 class="mb-1 font-semibold text-primary">Completed</h3>
                                        <time class="block mb-2 text-xs font-normal leading-none text-[#717171] float-right mt-1">10/20/24 7:30PM</time>
                                        <p class="text-sm font-normal text-[#717171]">Your order has been picked up.</p>
                                    </li>
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

<x-bank-deposit-proof />
<x-payment-center-proof />
<x-footer-component />

@endsection