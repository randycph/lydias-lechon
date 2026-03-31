<div
    x-cloak
    x-show="couponModal"
    x-transition.opacity
    class="fixed inset-0 z-50"
>

    {{-- BACKDROP --}}
    <div
        class="fixed inset-0 bg-black/50"
        @click="closeCouponModal"
    ></div>


    {{-- MODAL CONTAINER --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">

        <div
            x-show="couponModal"
            x-transition
            class="w-full max-w-xl"
        >

            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">

                {{-- CLOSE BUTTON --}}
                <button
                    type="button"
                    @click="closeCouponModal"
                    class="absolute right-4 top-4 text-gray-500 hover:text-gray-700"
                >
                    ✕
                </button>


                {{-- HEADER --}}
                <div class="px-6 pt-6 pb-4 border-b">
                    <h2 class="text-xl font-semibold">
                        Available Coupons
                    </h2>
                </div>


                {{-- BODY --}}
                <div class="px-6 py-6 space-y-5 max-h-[500px] overflow-y-auto">

                    {{-- MANUAL CODE INPUT --}}

                    <div class="flex items-center border rounded-md overflow-hidden">

                        <input
                            type="text"
                            x-model="couponCode"
                            @input="couponCode = couponCode.toUpperCase()"
                            placeholder="Enter coupon code"
                            class="w-full p-3 border-none outline-none">

                        <button
                            type="button"
                            @click="applyCouponCode"
                            class="bg-tertiary text-white px-5 py-3"
                        >
                            Apply
                        </button>
                    </div>



                    {{-- MESSAGE --}}
                    <template x-if="couponMessage">
                        <div
                            class="text-sm"
                            :class="{
                                'text-green-600': couponMessageType === 'success',
                                'text-red-600': couponMessageType === 'error'
                            }"
                            x-text="couponMessage"
                        ></div>
                    </template>


                    {{-- COUPON LIST --}}
                    <div class="space-y-4">

                        <template x-for="coupon in eligibleCoupons" :key="coupon.id">
                        <div
                            class="border rounded-2xl p-5 cursor-pointer transition"
                            @click="selectCoupon(coupon)"
                            :class="{
                                'border-green-600 bg-green-50': selectedCoupon?.id === coupon.id,
                                'border-gray-200 bg-white': selectedCoupon?.id !== coupon.id
                            }"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="text-2xl font-semibold text-gray-900" x-text="coupon.name || coupon.code"></div>
                                    <div class="mt-2 text-gray-600" x-text="coupon.description || 'No description available'"></div>

                                    <div class="mt-3 text-sm text-gray-500">
                                        <span class="font-medium">Type:</span>
                                        <span x-text="couponTypeLabel(coupon)"></span>
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        <span class="font-medium">Worth:</span>
                                        <span x-text="couponWorthLabel(coupon)"></span>
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        <span class="font-medium">Expires:</span>
                                        <span x-text="couponExpiryLabel(coupon)"></span>
                                    </div>
                                </div>

                                <div class="shrink-0 mt-1">
                                    <div
                                        class="w-6 h-6 rounded-full border"
                                        :class="selectedCoupon?.id === coupon.id ? 'border-green-600 bg-green-600' : 'border-gray-300 bg-white'"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </template>

                        <template x-if="eligibleCoupons.length === 0">
                            <div class="text-sm text-gray-500 text-center py-4">
                                No available coupons
                            </div>
                        </template>

                    </div>

                </div>                


                {{-- FOOTER --}}
                <div class="px-6 py-4 border-t flex justify-end gap-3">

                    <button
                        type="button"
                        @click="clearCouponSelection"
                        class="px-4 py-2 text-sm border rounded-md"
                    >
                        Clear
                    </button>

                    <button
                        type="button"
                        @click="confirmCouponSelection"
                        class="px-6 py-2 text-sm bg-primary text-white rounded-md"
                    >
                        Apply Selected
                    </button>

                </div>

            </div>

        </div>
    </div>
</div>
