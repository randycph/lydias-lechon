@extends('layouts.guest', ['page' => $page])

@section('content')



<div
    x-init="init"
    x-data="{
        cartCount: 0,
        carts: [],
        async getCarts() {
            try {
                this.carts = [];
                let response = await fetch('{{ route('cart.get') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                }).then((response) => {
                    return response;
                }).catch((error) => {
                    
                });

                if (!response.ok) throw new Error('Network response was not ok');

                let data = await response.json();

                this.carts = data.cart;

                console.log(data.cart);

                this.cartCount = this.carts?.length ?? 0;

            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        },
        init() {
            this.getCarts();
            const cookie = document.cookie.split('; ').find(row => row.startsWith('shipping_method='));
            this.shippingMethod = cookie ? cookie.split('=')[1] : 'pickup';
        },
        async removeCart(productid) {
            this.loading = true;
            try {
                let response = await fetch('{{ route('cart.remove') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        product_remove_id: productid
                    })
                }).then((response) => {
                    return response;
                }).catch((error) => {
                    
                });

                if (!response.ok) throw new Error('Network response was not ok');

                let data = await response.json();

                this.getCarts();

                this.loading = false;

            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        },
        shippingMethod: 'pickup',
        chooseShippingMethod(method) {
            this.shippingMethod = method;
            document.cookie = `shipping_method=${method}; path=/; max-age=31536000;`;
        },
    }"
    @fetch-cart.window="init()"

    class="bg-cream">
    <div class="py-20 px-4 container">
        <div class="flex gap-6 lg:flex-row flex-col mt-10">
            <div class="w-full lg:w-1/4">
                <x-account-menu-component />
            </div>

            <div class="w-full lg:w-3/4">
                <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md">
                    <div class="px-6 py-4 border-b border-[#DFDFDF]">
                        <h2 class="font-semibold text-tertiary text-left uppercase">My Cart</h2>
                    </div>
                    <div class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">
                        <template x-if="carts?.length > 0"> 
                            <div class="w-full px-4">
                                <div class="mt-4 flex flex-col gap-4">
                
                                    <template x-for="(cart, index) in carts" :key="index">
                                        <div class="flex justify-between items-center gap-4 hover:bg-gray-100 py-2" >
                                            <div class="flex gap-4 items-center px-6">
                                                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="w-20 h-20 object-cover rounded-md scale-110 bg-center">
                                                    <template x-if="cart?.product?.photos?.length > 0">
                                                        <img 
                                                            :src="cart.product.photos[0].url" 
                                                            alt="Checkout" 
                                                            class="w-20 h-20 object-cover rounded-md scale-110" 
                                                            onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}';">
                                                    </template>
                                                    
                                                </div>
                                                <div class="flex flex-col gap-1">
                                                    <div class="font-bold" x-text="cart?.product?.name"></div>
                                                    <div class="text-sm text-gray-600" x-text="new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(cart?.product?.price * (cart?.qty || 1))"></div>
                
                                                    <!-- Quantity Selector -->
                                                    <div class="flex items-center space-x-1">
                                                        <!-- Minus Button -->
                                                        <button @click="handleQtyChange(cart.product.id, cart.qty, -1)" class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                                                <path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                
                                                        <!-- Quantity Display (Fix: Use `x-text`) -->
                                                        <span class="w-8 text-center font-bold text-green-600" x-text="cart.qty"></span>
                
                                                        <!-- Plus Button -->
                                                        <button @click="handleQtyChange(cart.product.id, cart.qty, 1)" class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                                                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pr-2">
                                                <button @click="removeCart(cart?.product?.id)" class="text-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                
                                    <!-- Coupon Code Section -->
                                    <div class="rounded-md mt-10" x-data="{ 
                                        couponCode: '',
                                        showMessage: false,
                                        submitCouponCode() {
                                            if (this.couponCode) {
                                                this.showMessage = true;
                                            } else {
                                                this.showMessage = false;
                                            }
                                        }
                                    }">
                                        <div class="flex justify-end">
                                            <div class="flex items-center border border-gray-200 rounded-md overflow-hidden w-max">
                                                <input x-model="couponCode" type="text" placeholder="Have a coupon code?" 
                                                    class="w-full p-3 outline-none border-none text-gray-700">
                                                <button @click="submitCouponCode" class="bg-primary hover:bg-primary-dark text-white px-6 py-3">Apply</button>
                                            </div>
                                        </div>
                                        <div x-show="showMessage" class="text-[#28A745] mx-5 py-2 text-left w-full px-6 flex justify-end">Voucher code successfully applied.</div>
                
                                        <!-- Subtotal Section -->
                                        <div class="border-t border-gray-200 mt-4 pt-4">
                                            <div class="flex justify-between">
                                                <span class="font-bold text-gray-800">Subtotal</span>
                                                <span class="font-bold text-lg" x-text="new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(carts.reduce((total, cart) => total + (cart?.product?.price * (cart?.qty || 1)), 0))"></span>
                                            </div>
                                            <div class="flex justify-between lg:mt-2 mb-2" x-show="showMessage">
                                                <span class="font-medium text-red-700 italic">Coupon (<span x-text="couponCode"></span>)</span>
                                                <span class="font-medium italic text-red-700">- ₱250.00</span>
                                            </div>
                                            <p class="text-gray-600 text-sm">Delivery fee is calculated upon checkout</p>
                                        </div>
    
                                        <div class="border-t border-gray-200 mt-2 py-4 gap-1 flex flex-col text-lg">
                                            <div class="flex justify-between">
                                                <span class="font-medium text-gray-800 font-semibold">Total</span>
                                                <span class="font-bold">
                                                    <span x-text="new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(carts.reduce((total, cart) => total + (cart?.product?.price * (cart?.qty || 1)), 0))"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="w-full mx-auto text-center">
                                        <a href="{{ route('checkout') }}" class="bg-primary custom-btn btn-primary-dark text-white text-center  py-3 rounded-md mt-2 w-full lg:max-w-sm flex justify-center ml-auto">Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <template x-if="carts?.length == 0">
                            <div class="w-full flex justify-center mb-10">
                                <div class="mt-6 px-6 flex items-center justify-center flex-col h-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                    <div class="font-bold text-lg">Your cart is empty</div>
                    
                                    <a href="{{ route('lechon-menu') }}" class="bg-primary custom-btn btn-primary-dark text-white text-center px-6 py-3 rounded-md mt-4 w-full">Continue Shopping</a>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<x-footer-component />

@endsection