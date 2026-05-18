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
                            class="w-full p-3 border-none outline-none"
                        >

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
                                class="border rounded-xl p-4 cursor-pointer hover:shadow-sm transition relative"
                                @click="selectCoupon(coupon)"
                                :class="{
                                    'border-green-600 bg-green-50': selectedCoupon?.id === coupon.id
                                }"
                            >

                                <div class="flex justify-between items-start">

                                    <div class="space-y-1">

                                        <div class="font-semibold"
                                             x-text="coupon.name">
                                        </div>

                                        <div class="text-sm text-gray-600"
                                             x-html="coupon.description">
                                        </div>

                                        <div class="text-xs text-gray-400">
                                            Expires:
                                            <span x-text="coupon.expiry"></span>
                                        </div>

                                    </div>

                                    <input
                                        type="radio"
                                        name="coupon_pick"
                                        :checked="selectedCoupon?.id === coupon.id"
                                        class="mt-1"
                                    >

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
