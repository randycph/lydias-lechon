<div
    x-cloak
    x-show="couponModal"
    x-transition.opacity
    @keydown.escape.window="closeCouponModal()"
    class="fixed inset-0 z-50"
>
    {{-- BACKDROP --}}
    <div
        class="fixed inset-0 bg-black/50"
        @click="closeCouponModal()"
    ></div>

    {{-- MODAL CONTAINER --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div
            x-show="couponModal"
            x-transition
            class="w-full max-w-xl"
        >
            <div
                class="relative bg-white rounded-2xl shadow-2xl overflow-hidden"
                @click.stop
            >

                {{-- CLOSE BUTTON --}}
                <button
                    type="button"
                    @click="closeCouponModal()"
                    class="absolute right-4 top-4 text-gray-500 hover:text-gray-700"
                >
                    ✕
                </button>

                {{-- HEADER --}}
                <div class="px-6 pt-6 pb-4 border-b">
                    <h2 class="text-xl font-semibold">
                        Available Coupons
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Please choose one available coupon to apply to your order.
                    </p>
                </div>

                {{-- BODY --}}
                <div class="px-6 py-6 space-y-4 max-h-[500px] overflow-y-auto">

                    <template x-for="coupon in selectableCoupons" :key="coupon.id">
                        <label
                            class="block border rounded-2xl p-5 transition"
                            :class="{
                                'border-green-600 bg-green-50 cursor-pointer': selectedCoupon?.id === coupon.id,
                                'border-gray-200 bg-white cursor-pointer hover:border-orange-300': selectedCoupon?.id !== coupon.id
                            }"
                        >
                            <div class="flex items-start gap-4">
                                <input
                                    type="radio"
                                    name="coupon_choice"
                                    :value="coupon.id"
                                    :checked="selectedCoupon?.id === coupon.id"
                                    @change="selectCoupon(coupon)"
                                    class="mt-1"
                                >

                                <div class="flex-1 min-w-0">
                                    <div
                                        class="text-lg font-semibold text-gray-900"
                                        x-text="coupon.name || coupon.code"
                                    ></div>

                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <span class="inline-block text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded">
                                            Coupon
                                        </span>

                                        <span class="inline-block text-xs bg-green-100 text-green-700 px-2 py-1 rounded">
                                            Available
                                        </span>

                                        <template x-if="coupon.activation_type === 'auto' || coupon.auto_applied">
                                            <span class="inline-block text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                                Auto
                                            </span>
                                        </template>
                                    </div>

                                    <div
                                        class="mt-2 text-sm text-gray-600"
                                        x-text="coupon.description || 'No description available'"
                                    ></div>

                                    <div class="mt-3 text-sm text-orange-600 font-medium">
                                        <template x-if="isFreeShippingCoupon(normalizeCoupon(coupon))">
                                            <span x-text="couponWorthLabel(coupon)"></span>
                                        </template>

                                        <template x-if="normalizeCoupon(coupon).reward === 'discount-amount-optn' || normalizeCoupon(coupon).discount_type === 'amount'">
                                            <span x-text="couponWorthLabel(coupon)"></span>
                                        </template>

                                        <template x-if="normalizeCoupon(coupon).reward === 'discount-percentage-optn' || normalizeCoupon(coupon).discount_type === 'percent'">
                                            <span x-text="couponWorthLabel(coupon)"></span>
                                        </template>

                                        <template x-if="Array.isArray(normalizeCoupon(coupon).free_products) && normalizeCoupon(coupon).free_products.length > 0">
                                            <span>Free Product Coupon</span>
                                        </template>

                                        <template x-if="shouldShowLocationDiscount(coupon)">
                                            <div class="mt-2 text-xs font-bold text-green-700">
                                                <span x-text="locationDiscountLabel(coupon)"></span>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="mt-3 text-xs text-gray-500">
                                        <span class="font-medium">Type:</span>
                                        <span x-text="couponTypeLabel(coupon)"></span>
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        <span class="font-medium">Expires:</span>
                                        <span x-text="couponExpiryLabel(coupon)"></span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </template>

                    <template x-if="selectableCoupons.length === 0">
                        <div class="border border-gray-200 bg-gray-100 opacity-70 rounded-2xl p-5 text-center">
                            <div class="text-sm text-gray-500">
                                No available coupons
                            </div>
                        </div>
                    </template>

                </div>

                {{-- FOOTER --}}
                <div class="px-6 py-4 border-t flex justify-end gap-3">
                    <button
                        type="button"
                        @click="clearCouponSelection()"
                        class="px-4 py-2 text-sm border rounded-md"
                    >
                        Clear
                    </button>

                    <button
                        type="button"
                        @click="confirmCouponSelection()"
                        class="px-6 py-2 text-sm bg-primary text-white rounded-md"
                    >
                        Apply Selected Coupon
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>