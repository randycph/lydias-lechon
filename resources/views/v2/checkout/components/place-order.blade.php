<div class="w-full rounded-lg border bg-white border-[#DFDFDF] shadow-md">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-[#DFDFDF]">
        <h2 class="text-lg lg:text-2xl font-semibold">
            Review & Place Order
        </h2>
    </div>

    <div class="px-4 py-5 space-y-6">

        {{-- ============================= --}}
        {{-- ORDER ITEMS --}}
        {{-- ============================= --}}
        <div>
            <h3 class="font-bold mb-3">Order Items</h3>

            <template x-for="item in carts" :key="item.id">
                <div class="flex justify-between border-b py-2 text-sm">
                    <div>
                        <span x-text="item.product?.name"></span>
                        <span
                            x-show="parseFloat(item.paella_price) > 0"
                            class="italic text-sm"
                        >
                            (Boneless with Paella)
                        </span>

                        <span
                            x-show="item.is_free_product"
                            class="ml-2 px-2 py-0.5 text-xs rounded bg-green-100 text-green-700"
                        >
                            FREE
                        </span>
                        <span class="text-gray-500 text-xs">
                            (x<span x-text="item.qty"></span>)
                        </span>
                    </div>

                    <div class="font-semibold">
                        <span x-text="item.is_free_product
                                ? '₱0.00'
                                : formatMoney(item.price)"></span>
                    </div>
                </div>
            </template>
        </div>

        {{-- ============================= --}}
        {{-- METHOD --}}
        {{-- ============================= --}}
        <div>
            <h3 class="font-bold mb-2">Fulfillment Method</h3>

            <p class="text-sm">
                <span class="font-semibold">Type:</span>
                <span x-text="method === 'pickup' ? 'Store Pickup' : 'Delivery'"></span>
            </p>
        </div>

        {{-- ============================= --}}
        {{-- PICKUP DETAILS --}}
        {{-- ============================= --}}
        <template x-if="method === 'pickup'">
            <div class="text-sm space-y-1">
                <p><span class="font-semibold">Date:</span> <span x-text="need_date"></span></p>
                <p><span class="font-semibold">Time:</span> <span x-text="formatTime(need_time)"></span></p>
            </div>
        </template>

        {{-- ============================= --}}
        {{-- SINGLE DELIVERY --}}
        {{-- ============================= --}}
        <template x-if="method === 'delivery' && !allowMultiple">
            <div class="text-sm space-y-1">
                <p x-show="delivery_address"><span class="font-semibold">Address:</span> <span x-text="delivery_address"></span></p>
                <p x-show="need_date"><span class="font-semibold">Date:</span> <span x-text="need_date"></span></p>
                <p x-show="need_time"><span class="font-semibold">Time:</span> <span x-text="formatTime(need_time)"></span></p>
                <p x-show="instruction"><span class="font-semibold">Instruction:</span> <span x-text="instruction"></span></p>
            </div>
        </template>

        {{-- ============================= --}}
        {{-- MULTI DELIVERY --}}
        {{-- ============================= --}}
        <template x-if="method === 'delivery' && allowMultiple">
            <div class="space-y-4">

                <template x-for="(delivery, index) in deliveries" :key="index">
                    <div class="border rounded p-3 text-sm space-y-1 bg-gray-50">

                        <p class="font-semibold">
                            Delivery <span x-text="index + 1"></span>
                        </p>

                        <p x-text="delivery.address"></p>

                        <p>
                            <span class="font-semibold">Date:</span>
                            <span x-text="delivery.need_date"></span>
                        </p>

                        <p>
                            <span class="font-semibold">Time:</span>
                            <span x-text="formatTime(delivery.need_time)"></span>
                        </p>

                        <div class="mt-2">
                            <p class="font-semibold">Assigned Orders:</p>

                            <ul class="list-disc pl-10">
                                <template x-for="(order, index) in delivery.orders" :key="index">
                                    <li>
                                        <span x-text="order.product_name"></span>

                                        <span
                                            x-show="order.is_free_product"
                                            class="text-green-600 text-xs font-semibold ml-1"
                                        >
                                            (Free)
                                        </span>
                                        (x<span x-text="order.qty"></span>)
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <p>
                            <span class="font-semibold">Delivery Fee:</span>
                            <span x-text="formatMoney(delivery.delivery_fee)"></span>
                        </p>

                        <p x-show="delivery.sms">
                            <span class="font-semibold">Send Notification:</span>
                            <span x-text="delivery.sms ? 'Yes' : 'No'"></span>
                        </p>

                    </div>
                </template>

            </div>
        </template>

        {{-- ============================= --}}
        {{-- CONTACT INFO --}}
        {{-- ============================= --}}
        <div x-show="contact.name || contact.mobile || contact.email || contact.agent" class="border-t pt-4">
            <h3 class="font-bold mb-2">Contact Information</h3>

            <p x-show="contact.name" class="text-sm"><span class="font-semibold">Name:</span> <span x-text="contact.name"></span></p>
            <p x-show="contact.mobile" class="text-sm"><span class="font-semibold">Mobile:</span> <span x-text="contact.mobile"></span></p>
            <p x-show="contact.email" class="text-sm"><span class="font-semibold">Email:</span> <span x-text="contact.email"></span></p>
            <p x-show="contact.agent" class="text-sm"><span class="font-semibold">Agent Code:</span> <span x-text="contact.agent"></span></p>
        </div>

        <div class="flex flex-col">
            {{-- SUBTOTAL --}}
            <div class="flex justify-between border-t py-3 relative">
                <span class="font-medium text-gray-800">Subtotal</span>
                <span class="font-medium" x-text="formattedSubtotal"></span>
            </div>

            {{-- SINGLE DELIVERY --}}
            <template x-if="method === 'delivery' && !allowMultiple && deliveryFee > 0">
                <div class="flex justify-between pb-3 relative">
                    <span class="font-medium text-gray-800">Delivery Fee</span>
                    <span x-text="formatMoney(deliveryFee)"></span>
                </div>
            </template>

            {{-- MULTIPLE DELIVERY --}}
            <template x-if="method === 'delivery' && allowMultiple">
                <div class="space-y-1 text-sm pb-4">

                    <template x-for="(delivery, index) in deliveries" :key="index">
                        <div class="flex justify-between" x-show="delivery.delivery_fee > 0">

                            <span class="text-slate-600">
                                Delivery Fee
                                (<span x-text="delivery.city + ', ' + delivery.province"></span>)
                            </span>

                            <span>
                                ₱<span x-text="(delivery.delivery_fee || 0).toLocaleString(undefined,{minimumFractionDigits:2})"></span>
                            </span>

                        </div>
                    </template>
                </div>
            </template>

            {{-- COUPONS --}}
            <template x-if="coupons.length">
                <div class="space-y-1 mt-2">
                    <template x-for="(coupon, i) in coupons" :key="i">
                        <div class="flex justify-between text-red-700 italic">
                            <span>
                                Coupon (<span x-text="coupon.code"></span>)
                                <span
                                    class="text-xs underline cursor-pointer ml-1"
                                    @click="removeCoupon(i)"
                                >
                                    Remove
                                </span>
                            </span>

                            <span x-text="couponDiscountLabel(coupon)"></span>
                        </div>
                    </template>
                </div>
            </template>

            {{-- ============================= --}}
            {{-- TOTAL --}}
            {{-- ============================= --}}
            <div class="border-t pt-4">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span x-text="computeTotal()"></span>
                </div>
            </div>

        </div>

        {{-- ============================= --}}
        {{-- PLACE ORDER BUTTON --}}
        {{-- ============================= --}}
        <div>
            <button
                type="submit"
                :disabled="isSubmitting"
                class="w-full bg-primary text-white font-bold py-3 rounded-lg transition flex items-center justify-center gap-2 disabled:bg-gray-400 disabled:cursor-not-allowed"
                :class="{
                    'opacity-50 cursor-not-allowed': isSubmitting
                }"
            >
                <template x-if="!isSubmitting">
                    <span>Place Order</span>
                </template>

                <template x-if="isSubmitting">
                    <span class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        Processing...
                    </span>
                </template>
            </button>

        </div>

    </div>
</div>
