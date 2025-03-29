<div >
    <!-- Drawer Overlay -->
    <div x-show="openCart" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="openCart = false"></div>

    <!-- Cart Drawer Content -->
    <div x-show="openCart" x-transition:enter="transition-transform transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform transform duration-300"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="fixed top-[2%] right-[5%] w-[90%] max-w-lg h-[90%] bg-white text-black z-50 py-3 flex flex-col shadow-lg rounded-lg overflow-x-scroll scrollbar-hide scroll-smooth snap-y">

        <!-- Cart Content -->
        <div class="">
            <div class="flex justify-between items-center px-3">
                <div class="flex gap-2 items-center">
                    <div class="text-2xl font-bold">Cart</div><span class="bg-primary text-white size-3 flex justify-center items-center p-3 rounded-full text-xs">1</span>
                </div>
                <button @click="openCart = false" class="self-end text-2xl text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="bg-tertiary text-white py-2 text-center w-full mt-2">
                You need <strong>₱1,050.00</strong> more to 
            </div>

            <div class="mt-4 px-6 flex flex-col gap-4">
                <div class="flex justify-between items-center gap-4">
                    <div class="flex gap-4 items-center">
                        <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="w-20 h-20 object-cover rounded-md scale-110 bg-center">
                            <img src="{{ asset('images/checkout1.png') }}" alt="Checkout" class="w-20 h-20 object-cover rounded-md scale-110">
                        </div>
                        <div class="flex flex-col gap-1">
                            <div class="font-bold">Pork BBQ Tray</div>
                            <div class="text-sm text-gray-600">₱950.00 PHP</div>

                            <!-- Quantity Selector -->
                            <div x-data="{ quantity: 1 }" class="flex items-center space-x-1">
                                <!-- Minus Button -->
                                <button @click="if(quantity > 1) quantity--" class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Quantity Display (Fix: Use `x-text`) -->
                                <span class="w-8 text-center font-bold text-green-600" x-text="quantity"></span>

                                <!-- Plus Button -->
                                <button @click="quantity++" class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex justify-between items-center gap-4">
                    <div class="flex gap-4 items-center">
                        <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="w-20 h-20 object-cover rounded-md scale-110 bg-center">
                            <img src="{{ asset('images/checkout2.png') }}" alt="Checkout" class="w-20 h-20 object-cover rounded-md scale-110">
                        </div>
                        <div class="flex flex-col gap-1">
                            <div class="font-bold">Petite (Lechon Cebu)</div>
                            <div class="text-sm text-gray-600">₱9,800.00</div>

                            <!-- Quantity Selector -->
                            <div x-data="{ quantity: 1 }" class="flex items-center space-x-1">
                                <!-- Minus Button -->
                                <button @click="if(quantity > 1) quantity--" class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Quantity Display (Fix: Use `x-text`) -->
                                <span class="w-8 text-center font-bold text-green-600" x-text="quantity"></span>

                                <!-- Plus Button -->
                                <button @click="quantity++" class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mt-10" x-data="{ method: 'pickup' }">
                    <div class="font-bold">Choose a shipping method</div>
                    <div class="flex items-center gap-4 mt-2">
                        <button 
                            class="px-6 py-3 rounded-md w-full transition"
                            :class="method === 'pickup' ? 'bg-green-700 text-white' : 'bg-gray-100 text-gray-700'"
                            @click="method = 'pickup'">
                            Pickup
                        </button>
                
                        <button 
                            class="px-6 py-3 rounded-md w-full transition"
                            :class="method === 'delivery' ? 'bg-green-700 text-white' : 'bg-gray-100 text-gray-700'"
                            @click="method = 'delivery'">
                            Delivery
                        </button>
                    </div>
                </div>

                <!-- Coupon Code Section -->
                <div class="bg-cream rounded-md mt-10">
                    <div class="flex items-center border border-gray-200 rounded-md overflow-hidden">
                        <input type="text" placeholder="Have a coupon code?" 
                            class="w-full p-3 outline-none border-none text-gray-700">
                        <button class="bg-green-700 text-white px-6 py-3">Apply</button>
                    </div>

                    <!-- Subtotal Section -->
                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-800">Subtotal</span>
                            <span class="font-bold text-lg">₱950.00</span>
                        </div>
                        <p class="text-gray-600 text-sm">Delivery fee is calculated upon checkout</p>
                    </div>
                </div>

                <!-- Checkout Button -->
                <a href="{{ route('checkout') }}" class="bg-primary text-white text-center px-6 py-3 rounded-md mt-10 w-full">Checkout</a>

            </div>
        </div>
        <div class="mt-6 px-6 flex items-center justify-center flex-col">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <div class="font-bold text-lg">Your cart is empty</div>

            <button class="bg-primary text-white px-6 py-3 rounded-md mt-4 w-full">Continue Shopping</button>
        </div>
    </div>
</div>