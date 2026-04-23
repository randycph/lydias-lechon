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
      <div class="bg-white rounded-md mt-2 text-sm px-3 py-3 border-b border-[#DFDFDF]">

    <template x-if="autoAppliedCoupons.length > 0">
        <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-md">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 mr-2">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-blue-800">
                    <span x-text="autoAppliedCoupons.length"></span> coupon(s) auto-applied to your order!
                </span>
            </div>
        </div>
    </template>

    <div class="flex justify-between items-center mb-3">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 fill-[#ff8545]">
                <path fill-rule="evenodd"
                    d="M1.5 6.375c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v3.026a.75.75 0 0 1-.375.65 2.249 2.249 0 0 0 0 3.898.75.75 0 0 1 .375.65v3.026c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 17.625v-3.026a.75.75 0 0 1 .374-.65 2.249 2.249 0 0 0 0-3.898.75.75 0 0 1-.374-.65V6.375Zm15-1.125a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0V6a.75.75 0 0 1 .75-.75Zm.75 4.5a.75.75 0 0 0-1.5 0v.75a.75.75 0 0 0 1.5 0v-.75Zm-.75 3a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0v-.75a.75.75 0 0 1 .75-.75Zm.75 4.5a.75.75 0 0 0-1.5 0V18a.75.75 0 0 0 1.5 0v-.75ZM6 12a.75.75 0 0 1 .75-.75H12a.75.75 0 0 1 0 1.5H6.75A.75.75 0 0 1 6 12Zm.75 2.25a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z"
                    clip-rule="evenodd" />
            </svg>
            <span class="font-medium">Shop Coupon</span>
        </div>

        <div class="cursor-pointer flex items-center justify-between text-[#ff8545] font-bold"
            @click="couponModal = true">
            Select Coupon
        </div>
    </div>

    <template x-if="coupons.length > 0">
        <div class="mt-2 space-y-2">
            <template x-for="(item, i) in coupons" :key="i">
                <div class="flex justify-between">
                    <div class="flex flex-col">
                        <template x-if="item.description">
                            <div class="text-xs text-gray-500 mb-1" x-text="item.description"></div>
                        </template>

                        <div class="font-medium text-red-700 italic flex items-center flex-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                fill="currentColor" class="size-4 text-green-600 mr-1">
                                <path fill-rule="evenodd"
                                    d="M4.5 2A2.5 2.5 0 0 0 2 4.5v2.879a2.5 2.5 0 0 0 .732 1.767l4.5 4.5a2.5 2.5 0 0 0 3.536 0l2.878-2.878a2.5 2.5 0 0 0 0-3.536l-4.5-4.5A2.5 2.5 0 0 0 7.38 2H4.5ZM5 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                    clip-rule="evenodd" />
                            </svg>
                            Coupon (<span x-text="item.name || item.coupon_name || item.code || item.coupon_code || 'Coupon'"></span>)
                            <span class="text-xs ml-1 underline cursor-pointer" @click="removeCoupon(i)">Remove</span>
                        </div>
                    </div>

                    <span class="font-medium italic text-red-700">
                        <template x-if="item.free_shipping || item.reward === 'free-shipping-optn'">
                            <span>
                                - ₱<span x-text="Number(deliveryFee || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                                (Free Shipping)
                            </span>
                        </template>

                        <template x-if="!(item.free_shipping || item.reward === 'free-shipping-optn')">
                            <span>
                                - ₱<span x-text="getCouponDiscount(item).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                            </span>
                        </template>
                    </span>
                </div>
            </template>
        </div>
    </template>
    <div class="mt-4 border rounded-2xl p-4">
<div class="mt-4 border rounded-2xl p-4" x-data="{ gcOpen: false }">
    <div class="flex items-center justify-between cursor-pointer" @click="gcOpen = !gcOpen">
        <div>
            <h3 class="text-xl font-bold text-gray-900">Gift Certificate</h3>
            <p class="mt-1 text-sm text-gray-500">
                Enter your gift certificate code to deduct it from the grand total.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-orange-600 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79V7a2 2 0 0 0-2-2h-3.17a3 3 0 0 0-5.66 0H7a2 2 0 0 0-2 2v5.79m16 0A2 2 0 0 1 19 15H5a2 2 0 0 1-2-2.21m18 0V17a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4.21" />
                </svg>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 text-gray-500 transition-transform duration-200"
                :class="{ 'rotate-180': gcOpen }"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    <div x-show="gcOpen || appliedGiftCheque" x-collapse class="mt-4">
        <div class="mt-4 flex flex-col gap-3 sm:flex-row">
            <div class="flex-1">
                <input
                    type="text"
                    x-model="giftChequeCode"
                    placeholder="Enter gift certificate code"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-base text-gray-900 shadow-sm outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-100"
                >
            </div>

            <button
                type="button"
                @click="applyGiftCheque(); gcOpen = true"
                class="inline-flex items-center justify-center rounded-xl bg-orange-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-200"
            >
                Apply GC
            </button>
        </div>

        <template x-if="giftChequeMessage">
            <div
                class="mt-3 rounded-xl px-3 py-2 text-sm font-medium"
                :class="giftChequeMessageType === 'success'
                    ? 'border border-green-200 bg-green-50 text-green-700'
                    : 'border border-red-200 bg-red-50 text-red-700'"
                x-text="giftChequeMessage">
            </div>
        </template>

        <template x-if="appliedGiftCheque">
            <div class="mt-4 rounded-2xl border border-green-200 bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                Applied
                            </span>
                            <span class="text-lg font-bold text-gray-900" x-text="appliedGiftCheque.code"></span>
                        </div>

                        <div class="mt-2 text-sm text-gray-500">Gift Certificate Value</div>
                        <div class="text-2xl font-extrabold text-green-600" x-text="formatMoney(giftChequeDiscountAmount || 0)"></div>
                    </div>

                    <button
                        type="button"
                        @click="removeGiftCheque(); gcOpen = false"
                        class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100"
                    >
                        Remove
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
</div>
</div>

    {{-- GRAND TOTAL --}}
<template x-if="appliedGiftCheque && Number(giftChequeDiscountAmount || 0) > 0">
    <div class="flex justify-between px-4 py-2 text-sm lg:text-base border-t border-[#DFDFDF]">
        <div class="font-medium text-red-700 italic">
            Gift Certificate
            (<span x-text="appliedGiftCheque.code"></span>)
        </div>
        <div class="font-medium italic text-red-700">
            - <span x-text="formatMoney(giftChequeDiscountAmount || 0)"></span>
        </div>
    </div>
</template>
    <div class="px-4 py-4 text-sm lg:text-base">
        <div class="flex justify-between">
            <span class="font-medium text-gray-800">Total</span>
            <span class="font-bold" x-text="computeTotal()"></span>
        </div>
    </div>

</div>