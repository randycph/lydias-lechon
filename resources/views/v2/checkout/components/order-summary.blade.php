<div class="w-full rounded-lg border bg-white border-[#DFDFDF] shadow-md">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-[#DFDFDF]">
        <h2 class="text-lg lg:text-3xl font-semibold text-left">
            Order Summary
        </h2>
    </div>

    {{-- ITEM COUNT + SUBTOTAL --}}
    <div class="flex items-center justify-between px-4 py-3 border-b border-[#DFDFDF] text-sm lg:text-base">
        <span x-text="`${carts.length} items`"></span>
        <span class="font-bold" x-text="formattedSubtotal"></span>
    </div>

    {{-- CART ITEMS --}}
    <div class="flex flex-col gap-4 px-4 py-3 border-b border-[#DFDFDF]">

        <template x-for="(item, index) in carts" :key="index">
            <div class="flex gap-4 items-start relative">

                {{-- IMAGE --}}
                <div class="w-20 h-20 min-w-20 min-h-20 rounded-md overflow-hidden bg-gray-100">
                    <img
                        :src="itemImage(item)"
                        onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}'"
                        class="w-full h-full object-cover"
                        :alt="item?.product?.name"
                    />
                </div>

                {{-- INFO --}}
                <div class="flex-1">
                    <div class="font-bold leading-tight">
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
                    </div>

                    <div class="text-sm text-gray-600 mt-1">
                        Price:
                        <span
                            x-text="item.is_free_product
                                ? '₱0.00'
                                : formatMoney(item.price)"
                        ></span>

                        <span
                            x-show="parseFloat(item.paella_price) > 0"
                            class="italic"
                            x-text="' + ' + formatMoney(item.product?.paella_price)"
                        ></span>
                    </div>

                    <div class="text-sm text-gray-600">
                        QTY: <span x-text="item.qty"></span>
                    </div>
                </div>

                {{-- LINE TOTAL --}}
                <div class="absolute right-0 bottom-1 font-bold text-sm lg:text-base">
                    <span x-text="itemLineTotal(item)"></span>
                </div>
            </div>
        </template>

    </div>

    {{-- TOTALS --}}
    <div class="px-4 py-3 text-sm lg:text-base space-y-2 border-b border-[#DFDFDF]">

        {{-- SUBTOTAL --}}
        <div class="flex justify-between">
            <span class="font-medium text-gray-800">Subtotal</span>
            <span class="font-medium" x-text="formattedSubtotal"></span>
        </div>

        {{-- SINGLE DELIVERY --}}
        <template x-if="method === 'delivery' && !allowMultiple && deliveryFee > 0">
            <div class="flex justify-between">
                <span class="font-medium text-gray-800">Delivery Fee</span>
                <span x-text="formatMoney(deliveryFee)"></span>
            </div>
        </template>

        <template
            x-if="!allowMultiple && hasBaka && lechonBakaService > 0">
            <div>
                <div class="flex justify-between lg:mt-2">
                    <span class="font-medium text-gray-800 italic">Lechon Baka Service</span>
                    <span class="font-medium"
                        x-text="lechonBakaService > 0 ? '₱' + lechonBakaService.toLocaleString(undefined, { minimumFractionDigits: 2 }) : 'Free'"></span>
                </div>
            </div>
        </template>

        {{-- MULTIPLE DELIVERY --}}
        <template x-if="method === 'delivery' && allowMultiple">
            <div class="mt-3 space-y-1 text-sm">

                {{-- <template x-for="(delivery, index) in deliveries" :key="index">
                    <div class="flex justify-between" x-show="delivery.delivery_fee > 0">

                        <span class="text-slate-600">
                            Delivery Fee
                            (<span x-text="delivery.city + ', ' + delivery.province"></span>)
                        </span>

                        <span>
                            ₱<span x-text="(delivery.delivery_fee || 0).toLocaleString(undefined,{minimumFractionDigits:2})"></span>
                        </span>

                    </div>
                </template> --}}

                <template x-if="deliveryFees.length > 0">
                    <div class="flex flex-col gap-1 mt-2">
                        <template x-for="(item, i) in deliveryFees" :key="i">
                            <div>
                                <div class="flex justify-between text-gray-500 text-sm">
                                    <span x-text="'Delivery Fee (' + item.location + ')'"></span>
                                    <div class="flex items-center gap-1">
                                        <template x-if="item.discount && item.discount > 0">
                                            <span class="line-through text-red-700 italic"
                                                x-text="'₱' + item.fee.toLocaleString(undefined, { minimumFractionDigits: 2 })"></span>
                                        </template>
                                        <span
                                            x-text="'₱' + (item.fee - (item.discount || 0)).toLocaleString(undefined, { minimumFractionDigits: 2 })"></span>
                                    </div>
                                </div>

                                <template x-if="item.isBaka && item.lechon_baka_service > 0 && allowMultiple && method == 'delivery'">
                                    <ul class="pl-6 list-disc">
                                        <li class="flex justify-between text-gray-500 text-sm">
                                            <span class="italic ">Lechon Baka Service</span>
                                            <span
                                                x-text="'₱' + item.lechon_baka_service.toLocaleString(undefined, { minimumFractionDigits: 2 })"></span>
                                        </li>
                                    </ul>
                                </template>
                            </div>
                        </template>
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

    </div>

    {{-- GRAND TOTAL --}}
    <div class="px-4 py-4 text-sm lg:text-base">
        <div class="flex justify-between">
            <span class="font-medium text-gray-800">Total</span>
            <span class="font-bold" x-text="computeTotal()"></span>
        </div>
    </div>

</div>
