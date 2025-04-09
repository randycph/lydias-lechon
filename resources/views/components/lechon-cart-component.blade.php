<div>
    <!-- Drawer Overlay -->
    <div x-show="lechonCart" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="lechonCart = false">
    </div>

    <!-- Cart Drawer Content -->
    <div x-show="lechonCart" x-transition:enter="transition-transform transform duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition-transform transform duration-300" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="fixed top-[2%] right-[5%] bg-cream w-[90%] h-[90%] text-black z-50 py-3 px-1 lg:px-10 flex flex-col shadow-lg rounded-lg overflow-x-scroll scrollbar-hide scroll-smooth snap-y">

        <!-- Cart Content -->
        <div class="">
            <div class="flex justify-end items-center px-3">
                <button @click="lechonCart = false" class="self-end text-2xl text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-4 px-4 flex flex-col lg:flex-row gap-4 lg:gap-10">

                <div
                    class="lg:w-1/2 w-full border rounded-lg px-5 py-2 border-primary overflow-hidden flex items-center">
                    <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')"
                        class="object-cover rounded-lg max-w-[240px] lg:max-w-[587px] max-h-[215px] lg:max-h-max mx-auto bg-center relative">
                        <img src="{{ asset('images/lechonbaka.png') }}" alt="Add Lechon Baka in Cart"
                            class="object-cover p-2">
                    </div>
                </div>

                <div class="lg:w-1/2 w-full ">
                    <div class="flex lg:justify-between flex-col lg:flex-row justify-start items-start mb-5 lg:mt-0">
                        <div class="font-bold text-xl lg:text-4xl text-left text-primary">
                            Lechon Baka
                        </div>
                        <div>
                            <div class="mt-2 flex">
                                <span class="text-gray-500 pr-3">Share us:</span> 
                                <button href="https://www.facebook.com/lydiaslechonrestaurant/" target="_blank" class="fill-gray-600 hover:fill-gray-500">
                                    <span class="sr-only">Facebook</span>
                                    <svg class="h-6 w-6 " viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 24C0 10.7452 10.7452 0 24 0C37.2548 0 48 10.7452 48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24ZM26.5016 38.1115V25.0542H30.1059L30.5836 20.5546H26.5016L26.5077 18.3025C26.5077 17.1289 26.6192 16.5001 28.3048 16.5001H30.5581V12H26.9532C22.6231 12 21.0991 14.1828 21.0991 17.8536V20.5551H18.4V25.0547H21.0991V38.1115H26.5016Z"/>
                                    </svg>
                                </button>
                                <button href="https://www.instagram.com/lydiaslechon" target="_blank" class="ml-3 fill-gray-600 hover:fill-gray-500">
                                    <span class="sr-only">Instagram</span>
                                    <svg class="h-6 w-6 " viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 24C0 10.7452 10.7452 0 24 0C37.2548 0 48 10.7452 48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24ZM24.0012 11.2C20.5249 11.2 20.0886 11.2152 18.7233 11.2773C17.3606 11.3397 16.4305 11.5555 15.6166 11.872C14.7747 12.1989 14.0606 12.6363 13.3491 13.348C12.6371 14.0595 12.1997 14.7736 11.8717 15.6152C11.5544 16.4294 11.3384 17.3598 11.2771 18.7219C11.216 20.0873 11.2 20.5238 11.2 24.0001C11.2 27.4764 11.2155 27.9114 11.2773 29.2767C11.34 30.6394 11.5557 31.5695 11.872 32.3834C12.1992 33.2253 12.6365 33.9394 13.3483 34.6509C14.0595 35.3629 14.7736 35.8013 15.615 36.1283C16.4294 36.4448 17.3598 36.6605 18.7222 36.7229C20.0876 36.7851 20.5236 36.8003 23.9996 36.8003C27.4762 36.8003 27.9111 36.7851 29.2765 36.7229C30.6391 36.6605 31.5703 36.4448 32.3848 36.1283C33.2264 35.8013 33.9394 35.3629 34.6506 34.6509C35.3626 33.9394 35.8 33.2253 36.128 32.3837C36.4427 31.5695 36.6587 30.6391 36.7227 29.277C36.784 27.9116 36.8 27.4764 36.8 24.0001C36.8 20.5238 36.784 20.0876 36.7227 18.7222C36.6587 17.3595 36.4427 16.4294 36.128 15.6155C35.8 14.7736 35.3626 14.0595 34.6506 13.348C33.9386 12.636 33.2266 12.1987 32.384 11.872C31.5679 11.5555 30.6373 11.3397 29.2746 11.2773C27.9092 11.2152 27.4746 11.2 23.9972 11.2H24.0012Z" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M22.8529 13.5067C23.1937 13.5062 23.574 13.5067 24.0012 13.5067C27.4188 13.5067 27.8239 13.519 29.1735 13.5803C30.4215 13.6374 31.0989 13.8459 31.5501 14.0211C32.1474 14.2531 32.5733 14.5304 33.021 14.9784C33.469 15.4264 33.7464 15.8531 33.9789 16.4505C34.1541 16.9011 34.3629 17.5785 34.4197 18.8265C34.481 20.1758 34.4944 20.5812 34.4944 23.9972C34.4944 27.4132 34.481 27.8186 34.4197 29.1679C34.3626 30.4159 34.1541 31.0933 33.9789 31.5439C33.7469 32.1413 33.469 32.5666 33.021 33.0144C32.573 33.4624 32.1477 33.7397 31.5501 33.9717C31.0994 34.1477 30.4215 34.3557 29.1735 34.4128C27.8242 34.4741 27.4188 34.4874 24.0012 34.4874C20.5833 34.4874 20.1782 34.4741 18.8289 34.4128C17.5809 34.3552 16.9035 34.1466 16.4521 33.9714C15.8547 33.7394 15.428 33.4621 14.98 33.0141C14.532 32.5661 14.2547 32.1405 14.0222 31.5429C13.847 31.0922 13.6382 30.4149 13.5814 29.1669C13.52 27.8175 13.5078 27.4122 13.5078 23.994C13.5078 20.5758 13.52 20.1726 13.5814 18.8233C13.6384 17.5753 13.847 16.8979 14.0222 16.4467C14.2542 15.8494 14.532 15.4227 14.98 14.9747C15.428 14.5267 15.8547 14.2494 16.4521 14.0168C16.9033 13.8408 17.5809 13.6328 18.8289 13.5755C20.0097 13.5222 20.4673 13.5062 22.8529 13.5035V13.5067ZM30.8338 15.632C29.9858 15.632 29.2978 16.3193 29.2978 17.1675C29.2978 18.0155 29.9858 18.7035 30.8338 18.7035C31.6818 18.7035 32.3698 18.0155 32.3698 17.1675C32.3698 16.3195 31.6818 15.632 30.8338 15.632ZM24.0012 17.4267C20.371 17.4267 17.4278 20.37 17.4278 24.0001C17.4278 27.6303 20.371 30.5722 24.0012 30.5722C27.6314 30.5722 30.5735 27.6303 30.5735 24.0001C30.5735 20.37 27.6314 17.4267 24.0012 17.4267Z" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24.0012 19.7334C26.3575 19.7334 28.2679 21.6436 28.2679 24.0001C28.2679 26.3564 26.3575 28.2668 24.0012 28.2668C21.6446 28.2668 19.7345 26.3564 19.7345 24.0001C19.7345 21.6436 21.6446 19.7334 24.0012 19.7334Z" />
                                    </svg>
                                </button>
                                <button href="#" class="ml-3 fill-gray-600 hover:fill-gray-500">
                                    <span class="sr-only">Twitter</span>
                                    <svg class="h-6 w-6 " viewBox="0 0 48 48"  xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 24C0 10.7452 10.7452 0 24 0C37.2548 0 48 10.7452 48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24ZM23.2812 19.5075L23.3316 20.338L22.4922 20.2363C19.4369 19.8465 16.7677 18.5245 14.5013 16.3043L13.3934 15.2027L13.108 16.0162C12.5036 17.8296 12.8897 19.7448 14.1488 21.0328C14.8203 21.7446 14.6692 21.8463 13.5109 21.4226C13.108 21.287 12.7554 21.1854 12.7219 21.2362C12.6044 21.3548 13.0073 22.8971 13.3262 23.5072C13.7627 24.3546 14.6524 25.1851 15.6261 25.6766L16.4487 26.0664L15.475 26.0833C14.5349 26.0833 14.5013 26.1003 14.6021 26.4562C14.9378 27.5578 16.264 28.7272 17.7413 29.2357L18.7822 29.5916L17.8756 30.1339C16.5326 30.9135 14.9546 31.3542 13.3766 31.3881C12.6211 31.405 12 31.4728 12 31.5237C12 31.6931 14.0481 32.6422 15.24 33.0151C18.8157 34.1167 23.063 33.6422 26.2526 31.7609C28.5189 30.422 30.7852 27.7612 31.8428 25.1851C32.4136 23.8123 32.9844 21.304 32.9844 20.1007C32.9844 19.3211 33.0347 19.2194 33.9748 18.2872C34.5288 17.7449 35.0492 17.1517 35.15 16.9822C35.3178 16.6602 35.3011 16.6602 34.4449 16.9483C33.018 17.4568 32.8165 17.389 33.5216 16.6263C34.042 16.084 34.6631 15.101 34.6631 14.8129C34.6631 14.762 34.4113 14.8468 34.1259 14.9993C33.8238 15.1688 33.1523 15.423 32.6486 15.5756L31.7421 15.8637L30.9195 15.3044C30.4663 14.9993 29.8283 14.6604 29.4926 14.5587C28.6364 14.3214 27.327 14.3553 26.5548 14.6265C24.4563 15.3891 23.1301 17.3551 23.2812 19.5075Z"/>
                                    </svg>
                                </button>
                                <button href="#" class="ml-3 fill-gray-600 hover:fill-gray-500">
                                    <span class="sr-only">Tiktok</span>
                                    <svg class="h-6 w-6 " viewBox="0 0 48 48"  xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.4706 37.3231C15.4211 37.8246 16.4797 38.0864 17.5543 38.086C21.1338 38.086 24.0559 35.2387 24.1878 31.69L24.201 0H32.1076C32.1083 0.673085 32.1709 1.34468 32.2945 2.00632H26.5053V2.00742H34.4129C34.4118 4.65963 35.3731 7.22216 37.1185 9.21918L37.1207 9.22173C38.9011 10.3848 40.9821 11.0032 43.1088 11.0012V12.7622C43.8531 12.9216 44.6227 13.0065 45.4142 13.0065V20.9152C41.4667 20.9198 37.6179 19.6821 34.413 17.3775V33.4468C34.413 41.4709 27.8839 48 19.8586 48C17.8601 48.0005 15.883 47.5882 14.0512 46.7889C12.2207 45.9903 10.5749 44.8224 9.21653 43.3584L9.21355 43.3563C5.46035 40.7212 3 36.3633 3 31.4404C3 23.4151 9.52906 16.885 17.5543 16.885C18.2106 16.8881 18.8658 16.9359 19.5156 17.0279V18.9001C19.5613 18.8992 19.6067 18.8976 19.6523 18.8961C19.7207 18.8937 19.7893 18.8913 19.8586 18.8913C20.5148 18.8944 21.1701 18.9422 21.8198 19.0342V27.1068C21.1998 26.9122 20.5435 26.7989 19.8586 26.7989C18.0962 26.801 16.4066 27.5021 15.1605 28.7484C13.9144 29.9947 13.2136 31.6844 13.2119 33.4467C13.212 34.8387 13.6519 36.1951 14.4689 37.3222L14.4706 37.3231ZM6.26428 38.6397C6.93975 40.3996 7.9478 41.9961 9.21034 43.3529C7.9221 41.9743 6.92735 40.371 6.26428 38.6397Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center gap-4 my-2 lg:my-5">
                        <div class="text-gray-600 text-2xl lg:text-4xl font-bold">
                            ₱65,800.00
                        </div>
                        <!-- Quantity Selector -->
                        <div x-data="{ quantity: 1 }" class="flex items-center space-x-1">
                            <!-- Minus Button -->
                            <button @click="if(quantity > 1) quantity--"
                                class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5">
                                    <path fill-rule="evenodd"
                                        d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Quantity Display (Fix: Use `x-text`) -->
                            <span class="w-8 text-center font-bold text-primary" x-text="quantity"></span>

                            <!-- Plus Button -->
                            <button @click="quantity++"
                                class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5">
                                    <path
                                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
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
                        <div class="mt-3 text-sm">Available for <strong>store pick-up</strong> and
                            <strong>delivery</strong> around Metro Manila.</div>
                        <div class="mt-2 text-sm">Accepted Payment Methods: <strong>GCash</strong>,
                            <strong>Maya</strong> and <strong>Bank Transfer</strong></div>
                    </div>

                    <div>
                        <div class="font-bold lg:mt-4">Add-Ons</div>
                        <div class="inline-flex items-center gap-2">
                            <label class="flex items-center cursor-pointer relative">
                                <input type="checkbox"
                                    class="peer lg:h-6 lg:mb-4 lg:w-6 h-6 w-6 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-primary checked:border-primary"
                                    id="check1" />
                                <span
                                    class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                               
                                </span>
                            </label>
                            <div class="flex flex-col mt-2">
                                <span class="text-sm lg:text-xl">Boneless Lechon stuffed with Seafood Paella</span>
                                <span class="text-sm lg:text-xl font-semibold">₱2,800.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <div class="flex justify-between">
                            <div>
                                <div class="font-bold">Often purchased together</div>
                                <div class="text-xs text-gray-500">Customers who bought this item also purchased these.
                                </div>
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
                                    <div class="swiper-slide !w-[120px]">
                                        <div class="rounded-xl border-gray-200 border w-[120px]">
                                            <div class="inline-flex items-center absolute top-0 right-0 p-1">
                                                <label class="flex items-center cursor-pointer relative">
                                                    <input type="checkbox"
                                                        class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded-full shadow hover:shadow-md border border-slate-300 checked:bg-green-600 checked:border-green-600"
                                                        id="check4" />
                                                    <span
                                                        class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                            viewBox="0 0 20 20" fill="currentColor"
                                                            stroke="currentColor" stroke-width="1">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </span>
                                                </label>
                                            </div>
                                            <img src="{{ asset('images/' . $addon['image']) }}"
                                                alt="{{ $addon['name'] }}" class="object-cover w-full h-full hover:scale-125 transition duration-300">
                                        </div>
                                        <div class="text-xs text-center mt-2">{{ $addon['name'] }}</div>
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

                    <div x-data="{ added: false }" class="mt-10">
                        <button @click="added = true; addToCart()"
                            class="bg-primary primary-btn text-white w-full px-6 py-3 rounded-md font-semibold flex items-center justify-center gap-2 transition-all duration-300 ease-in-out custom-btn btn-primary">
                            <template x-if="!added">
                                <span x-transition.opacity> Add to Cart </span>
                            </template>

                            <template x-if="added">
                                <span class="flex items-center gap-2" x-transition.opacity>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Added to Cart
                                </span>
                            </template>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>