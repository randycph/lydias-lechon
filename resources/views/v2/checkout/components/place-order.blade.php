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
                        <span x-text="item.product.name"></span>
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
                <p><span class="font-semibold">Date:</span> <span x-text="pickup_date"></span></p>
                <p><span class="font-semibold">Time:</span> <span x-text="pickup_time"></span></p>
            </div>
        </template>

        {{-- ============================= --}}
        {{-- SINGLE DELIVERY --}}
        {{-- ============================= --}}
        <template x-if="method === 'delivery' && !allowMultiple">
            <div class="text-sm space-y-1">
                <p x-show="delivery_address"><span class="font-semibold">Address:</span> <span x-text="delivery_address"></span></p>
                <p x-show="need_date"><span class="font-semibold">Date:</span> <span x-text="need_date"></span></p>
                <p x-show="need_time"><span class="font-semibold">Time:</span> <span x-text="need_time"></span></p>
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
                            <span x-text="delivery.location"></span>,
                            <span x-text="delivery.city"></span>,
                            <span x-text="delivery.province"></span>
                        </p>

                        <p>
                            <span class="font-semibold">Date:</span>
                            <span x-text="delivery.need_date"></span>
                        </p>

                        <p>
                            <span class="font-semibold">Time:</span>
                            <span x-text="delivery.need_time"></span>
                        </p>

                        <div class="mt-2">
                            <p class="font-semibold">Assigned Orders:</p>

                            <template x-for="order in delivery.orders" :key="order.product_id">
                                <p>
                                    Product ID:
                                    <span x-text="getProductName(order.product_id)"></span>
                                    (x<span x-text="order.qty"></span>)
                                </p>
                            </template>
                        </div>

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
            <template x-if="allowMultiple && deliveryFees.length">
                <div class="space-y-1">
                    <template x-for="(fee, i) in deliveryFees" :key="i">
                        <div class="flex justify-between text-gray-600 text-sm">
                            <span x-text="`Delivery Fee (${fee.location})`"></span>

                            <div class="flex items-center gap-1">
                                <template x-if="fee.discount > 0">
                                    <span class="line-through text-red-600 italic"
                                        x-text="formatMoney(fee.fee)">
                                    </span>
                                </template>
                                <span x-text="formatMoney(fee.fee - (fee.discount || 0))"></span>
                            </div>
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
                class="bg-primary text-white w-full py-3 rounded-md hover:bg-primary-dark transition"
            >
                Place Order
            </button>
        </div>

    </div>
</div>
