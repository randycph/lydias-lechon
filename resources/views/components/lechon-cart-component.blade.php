<div>
    <!-- Drawer Overlay -->
    <div x-show="lechonCart" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="lechonCart = false"></div>

    <!-- Cart Drawer Content -->
    <div x-show="lechonCart" x-transition:enter="transition-transform transform duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition-transform transform duration-300"
        x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
        class="fixed top-[2%] right-[5%] bg-cream w-[90%] h-[90%] text-black z-50 py-3 flex flex-col shadow-lg rounded-lg overflow-x-scroll scrollbar-hide scroll-smooth snap-y">

        <!-- Cart Content -->
        <div class="">
            <div class="flex justify-end items-center px-3">
                <button @click="lechonCart = false" class="self-end text-2xl text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-4 px-4 flex flex-col gap-4">

                <div class="border rounded-lg px-5 py-2 border-primary-dark overflow-hidden">
                    <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="object-cover rounded-lg mx-auto max-w-[240px] max-h-[215px] bg-center relative">
                        <img src="{{ asset('images/lechonbaka.png') }}" alt="Add Lechon Baka in Cart" class="object-cover p-2">
                    </div>
                </div>

                <div class="font-bold text-xl text-left text-primary">
                    Lechon Baka
                </div>

                <div class="flex justify-between items-center gap-4">
                    <div class="text-gray-600 text-2xl font-bold">
                        ₱65,800.00
                    </div>
                    <!-- Quantity Selector -->
                    <div x-data="{ quantity: 1 }" class="flex items-center space-x-1">
                        <!-- Minus Button -->
                        <button @click="if(quantity > 1) quantity--" class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Quantity Display (Fix: Use `x-text`) -->
                        <span class="w-8 text-center font-bold text-primary" x-text="quantity"></span>

                        <!-- Plus Button -->
                        <button @click="quantity++" class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <div class="font-bold">Description</div>
                    <div class="flex flex-col text-gray-600 mt-2">
                        <div class="flex gap-4">
                            <div class="text-gray-500">Weight</div>
                            <div class="font-bold">5-6 kgs</div>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-gray-500">Serves:</div>
                            <div class="font-bold">12-15 pax</div>
                        </div>
                    </div>
                    <div class="mt-3 text-sm">Available for <strong>store pick-up</strong> and <strong>delivery</strong> around Metro Manila.</div>
                    <div class="mt-2 text-sm">Accepted Payment Methods: <strong>GCash</strong>, <strong>Maya</strong> and <strong>Bank Transfer</strong></div>
                </div>

                <div>
                    <div class="font-bold">Add-Ons</div>
                    <div class="inline-flex items-center gap-2 mt-2">
                        <label class="flex items-center cursor-pointer relative">
                          <input type="checkbox" checked class="peer h-8 w-8 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-primary checked:border-primary" id="check1" />
                          <span class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                          </span>
                        </label>
                        <div class="flex flex-col">
                            <span class="text-sm">Boneless Lechon stuffed with Seafood Paella</span>
                            <span class="text-sm font-semibold">₱2,800.00</span>
                        </div>
                      </div>
                </div>

                <div>
                    <div class="flex justify-between">
                        <div>
                            <div class="font-bold">Often purchased together</div>
                            <div class="text-xs text-gray-500">Customers who bought this item also purchased these.</div>
                        </div>
                        <div>
                            <div class="rounded-full px-4 py-1 text-xs bg-gray-200 text-gray-400 inline-block">
                                Optional
                            </div>
                        </div>
                    </div>

                    @php
                        $addons = [
                            [
                                'image' => 'pinakbet.png',
                                'name' => 'Pinakbet',
                            ],
                            [
                                'image' => 'party-tray.png',
                                'name' => 'Party Tray',
                            ],
                            [
                                'image' => 'pancit.png',
                                'name' => 'Pancit',
                            ],
                            [
                                'image' => 'dinuguan.png',
                                'name' => 'Dinuguan',
                            ],
                            [
                                'image' => 'sisig.png',
                                'name' => 'Sisig',
                            ],
                            [
                                'image' => 'coke.png',
                                'name' => 'Coke',
                            ],
                        ];
                    @endphp

                    <div>
                        <div class="swiper-addons relative mt-5 overflow-hidden">
                            <div class="swiper-wrapper">
                              @foreach ($addons as $addon)
                              <div class="swiper-slide !w-[120px] !h-[120px]">
                                    <div class="rounded-xl border-gray-200 border w-[120px] h-[120px] relative">
                                        <div class="inline-flex items-center absolute top-0 right-0 p-1">
                                            <label class="flex items-center cursor-pointer relative">
                                                <input type="checkbox" class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded-full shadow hover:shadow-md border border-slate-300 checked:bg-green-600 checked:border-green-600" id="check4" />
                                                <span class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                </span>
                                            </label>
                                        </div>
                                        <img src="{{ asset('images/' . $addon['image']) }}" alt="{{ $addon['name'] }}" class="object-cover w-full h-full">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div>
                            <div class="font-bold">Order Summary</div>
                        </div>
                        <div class="mt-2">
                            <table class="w-full border-collapse text-left">
                                <thead>
                                    <tr class="border-b">
                                        <th class="pb-2 text-sm font-bold">Product</th>
                                        <th class="pb-2 text-sm font-bold">Price</th>
                                        <th class="pb-2 text-sm font-bold">Quantity</th>
                                        <th class="pb-2 text-sm font-bold">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b">
                                        <td class="py-2">Lechon Baka</td>
                                        <td class="py-2">65,800.00</td>
                                        <td class="py-2">1</td>
                                        <td class="py-2">65,800.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        
                            <div class="mt-4 flex justify-end">
                                <span class="text-tertiary font-bold text-xl">₱65,800.00</span>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <button class="bg-primary text-white px-6 py-3 rounded-md mt-4 w-full">Add To Cart</button>
            </div>
        </div>
    </div>
</div>