<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @font-face {
            font-family: 'Cubao';
            src: url('{{ asset("fonts/Cubao_Free_Narrow.otf") }}') format('opentype');
            font-weight: 300;
            font-style: normal;
        }
    
        @font-face {
            font-family: 'Cubao';
            src: url('{{ asset("fonts/Cubao_Free_Regular.otf") }}') format('opentype');
            font-weight: 500;
            font-style: normal;
        }
    
        @font-face {
            font-family: 'Cubao';
            src: url('{{ asset("fonts/Cubao_Free_Wide.otf") }}') format('opentype');
            font-weight: 700;
            font-style: normal;
        }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body x-cloak class="bg-gray-100 text-gray-900" x-data="{ open: false, openCart: false, marketingPopup: false, openHotline: true }">
    <nav  class="fixed top-5 left-1/2 transform -translate-x-1/2 w-[90%] bg-green-700 px-4 py-3 flex justify-between items-center z-50 rounded-full">
        <button @click="open = true">
            <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-white cursor-pointer">
                <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
            </svg>
        </button>
        <div class="text-white font-bold text-lg">
            <img src="{{ asset('images/lydia-logo.png') }}" alt="Lydia's Logo" class="w-[60px]">
        </div>
        <button @click="openCart = true">
            <div class="relative">
                <span class="absolute -top-1 -right-1 bg-secondary text-black text-xs px-2 py-2 w-4 h-4 rounded-full flex items-center justify-center">3</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-white cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </div>
        </button>
    </nav>
    <!-- Fullscreen Drawer -->
    <div >
        <!-- Drawer Overlay -->
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="open = false"></div>

        <!-- Drawer Content -->
        <div x-show="open" x-transition:enter="transition-transform transform duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform transform duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
            class="fixed inset-0 bg-white text-black w-full h-full z-50 py-6 flex flex-col">

            <!-- Close Button -->
            <button @click="open = false" class="self-start text-2xl text-gray-800 px-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Drawer Content -->
            <div class="mt-10 space-y-4 text-left">

                <a href="#" class="block text-2xl font-medium font-cubao uppercase border-[#DFDFDF] text-primary pl-10">Hi, juan!</a>
                <ul class="flex flex-col gap-4 pl-10">
                    <li class="uppercase font-light">Manage account</li>
                    <li class="uppercase font-light">Change password</li>
                    <li class="uppercase font-light">Order History</li>
                    <li class="uppercase font-light">Sign out</li>
                </ul>

                <a href="#" class="block text-2xl font-medium font-cubao uppercase py-5 border-y border-[#DFDFDF] pl-10">Our Story</a>
                <a href="#" class="block text-2xl font-medium font-cubao uppercase py-5 border-b border-[#DFDFDF] pl-10">Our Stores</a>
                <a href="#" class="block text-2xl font-medium font-cubao uppercase py-5 border-b border-[#DFDFDF] pl-10">Menu</a>
                <a href="#" class="block text-2xl font-medium font-cubao uppercase py-5 border-b border-[#DFDFDF] pl-10">lechon Pricelist</a>
                <a href="#" class="block text-2xl font-medium font-cubao uppercase py-5 border-b border-[#DFDFDF] pl-10">Hotline</a>
                <a href="#" class="block text-2xl font-medium font-cubao uppercase py-5 border-b border-[#DFDFDF] pl-10">Careers</a>
            </div>
        </div>
    </div>

    <div >
        <!-- Drawer Overlay -->
        <div x-show="openCart" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="openCart = false"></div>

        <!-- Cart Drawer Content -->
        <div x-show="openCart" x-transition:enter="transition-transform transform duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform transform duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="fixed top-[2%] right-[5%] w-[90%] h-[90%] bg-white text-black z-50 py-3 flex flex-col shadow-lg rounded-lg overflow-x-scroll scrollbar-hide scroll-smooth snap-y">

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
                    You need <strong>₱1,050.00</strong> more to checkout
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
                    <button class="bg-primary text-white px-6 py-3 rounded-md mt-10 w-full">Checkout</button>

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
    
    <div >
        <!-- Drawer Overlay -->
        <div x-show="openHotline" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="openHotline = false"></div>

        <!-- Hotline Content -->
        <div x-show="openHotline" x-transition:enter="transition-transform transform duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform transform duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="fixed top-[2%] right-[5%] w-[90%] h-[90%] bg-white text-black z-50 py-3 flex flex-col shadow-lg rounded-lg overflow-x-scroll scrollbar-hide scroll-smooth snap-y">

            <!-- Cart Content -->
            <div class="">
                <div class="flex justify-between items-center px-3">
                    <div class="flex gap-2 items-center">
                        <div class="text-2xl font-bold">Call Our Hotlines</div>
                    </div>
                    <button @click="openHotline = false" class="self-end text-2xl text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="text-gray-600 font-medium px-4 mt-4">
                    Reach out to us through our hotlines for quick and reliable assistance.
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">Roces Branch</div>
                    <div>
                        <a href="tel:+6383761818" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8376-1818</div>
                        </a>
                        <a href="tel:+63287369664" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8736-9664</div>
                        </a>
                        <a href="tel:+637091-0395" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">Globe - 7091-0395</div>
                        </a>
                    </div>
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">Commonwealth Branch</div>
                    <div>
                        <a href="tel:+6389355095" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8935 5095</div>
                        </a>
                        <a href="tel:+63284019867" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8401 9867</div>
                        </a>
                    </div>
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">Marcos Highway Branch</div>
                    <div>
                        <a href="tel:+63286460871" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8646-0871</div>
                        </a>
                        <a href="tel:+63284019791" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8401 9791</div>
                        </a>
                    </div>
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">Pasig Branch</div>
                    <div>
                        <a href="tel:+63286719053" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8671-9053</div>
                        </a>
                        <a href="tel:+63279332961" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">Globe - 7933-2961</div>
                        </a>
                    </div>
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">BF Aguirre, Paranaque Branch</div>
                    <div>
                        <a href="tel:+63288610608" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8861-0608</div>
                        </a>
                        <a href="tel:+63285566741" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8556-6741</div>
                        </a>
                        <a href="tel:+639171685485" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                                <path fill-rule="evenodd" d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">Globe - 09171685485</div>
                        </a>
                    </div>
                </div>

                <div class="px-4 mt-5">
                    <div class="font-bold text-primary">Cash and Carry, Makati Branch</div>
                    <div>
                        <a href="tel:+63284747558" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="m3.855 7.286 1.067-.534a1 1 0 0 0 .542-1.046l-.44-2.858A1 1 0 0 0 4.036 2H3a1 1 0 0 0-1 1v2c0 .709.082 1.4.238 2.062a9.012 9.012 0 0 0 6.7 6.7A9.024 9.024 0 0 0 11 14h2a1 1 0 0 0 1-1v-1.036a1 1 0 0 0-.848-.988l-2.858-.44a1 1 0 0 0-1.046.542l-.534 1.067a7.52 7.52 0 0 1-4.86-4.859Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">(+632) 8474-7558</div>
                        </a>
                        <a href="tel:+639171685485" class="flex items-center gap-2 px-4 py-2 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path d="M7.25 11.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" />
                                <path fill-rule="evenodd" d="M6 1a2.5 2.5 0 0 0-2.5 2.5v9A2.5 2.5 0 0 0 6 15h4a2.5 2.5 0 0 0 2.5-2.5v-9A2.5 2.5 0 0 0 10 1H6Zm4 1.5h-.5V3a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5v-.5H6a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1Z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-base">Globe - 0917-1685485</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container">
        @yield('content')
    </main>
    
    <!-- Fixed Bottom Navigation -->
    <footer class="fixed bottom-0 left-0 w-full bg-orange-500 text-white flex justify-around p-3 text-sm font-bold z-50">
        <a href="#" class="text-center">MENU</a>
        <a href="#" class="text-center">LECHON PRICELIST</a>
        <button @click="openHotline = true" class="text-center">HOTLINE</button>
    </footer>
</body>
</html>
