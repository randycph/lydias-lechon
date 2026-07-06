@extends('layouts.guest', ['page' => $page])

@section('title', 'Checkout')
@section('meta_description',
    'Complete your order at Lydia\'s Lechon. Review your cart, choose delivery or pickup, and
    finalize your purchase for a delicious meal.')


@section('alpine.plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('content')

    <style>
        :root {
            --page-bg: #fff;
        }

        [x-cloak] {
            display: none;
        }

        .vertical-rl {
            writing-mode: vertical-rl;
        }

        .datepicker-dropdown {
            width: 100% !important;
        }

        .datepicker-view {
            width: 100% !important;
        }
    </style>

    @php
        $deliveryFee = 0;
        $total = 0;

        foreach ($carts as $cart) {
            $qty = $cart['qty'] ?? 1;
            $paella_price = $cart['paella_price'] > 0 ? $cart['product']['paella_price'] : 0;
            $price = $cart['price'] ?? 0;

            $isFree = $cart['is_free_product'] ?? false;

            $total += ($paella_price * $qty) + ($isFree ? 0 : ($price * $qty));
        }
            $eligibleAutoCoupons = isset($eligibleAutoCoupons) ? $eligibleAutoCoupons : collect([]);
            $allCoupons = isset($allCoupons) ? $allCoupons : $eligibleCoupons->merge($eligibleAutoCoupons);
    @endphp

    <div class="bg-cream">
        <div x-data="checkoutForm()" x-init="init()" class="container">
            <form id="checkoutForm" method="POST" action="{{ route('cart.temp_sales') }}" @submit.prevent="submitForm"
                class="pb-20 px-4">
                @csrf

                 <input 
                    type="hidden" 
                    name="selected_auto_coupon_id" 
                    :value="selectedAutoCoupon ? selectedAutoCoupon.id : ''"
                >

                <input 
                    type="hidden" 
                    name="selected_coupon_id" 
                    :value="selectedCoupon ? selectedCoupon.id : ''"
                >
                <div class="pt-20 pb-5 px-4">
                    <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center mt-10">Checkout</h1>
                    @if ($carts && count($carts) > 0)
                        <h3 class="font-medium lg:text-2xl text-center">You're almost there! Review your order details,
                            choose
                            your payment
                            method, and finalize your purchase to enjoy your Lydia's Lechon meal.</h3>
                    @endif
                </div>

                @if ($carts->isEmpty())
                    <div class="flex flex-col items-center justify-center h-96">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-20">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <h2 class="text-xl font-semibold mt-4">Your cart is empty</h2>
                        <p class="text-gray-500">Looks like you haven't added anything to your cart yet.</p>
                        <a href="menu" class="mt-4 bg-primary text-white px-4 py-2 rounded-md">Start
                            Shopping</a>
                    </div>
                @else
    <div class="flex flex-col lg:flex-row gap-4 mt-10">

        {{-- LEFT --}}
        <div class="w-full lg:w-3/5 space-y-4 order-2 lg:order-1">
            @include('v2.checkout.components.delivery-method')

            <template x-if="method === 'pickup'">
                @include('v2.checkout.components.pickup-form')
            </template>

            <template x-if="method === 'delivery' && !allowMultiple">
                @include('v2.checkout.components.single-delivery-form')
            </template>

            <template x-if="method === 'delivery' && allowMultiple">
                @include('v2.checkout.components.multi-delivery-form')
            </template>

            @include('v2.checkout.components.contact-info')
            {{-- @include('v2.checkout.components.place-order') --}}

            <button
                type="submit"
                :disabled="incompleteProgress || isSubmitting"
                class="w-full bg-primary text-white font-bold py-3 rounded-lg transition flex items-center justify-center gap-2 disabled:bg-gray-400 disabled:cursor-not-allowed"
                :class="{
                    'opacity-50 cursor-not-allowed pointer-events-none': incompleteProgress || isSubmitting
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

        {{-- RIGHT --}}
        <div class="w-full lg:w-2/5 order-1 lg:order-2">
            @include('v2.checkout.components.order-summary')
        </div>

    </div>
@endif
            </form>
                {{-- AUTO COUPON POPUP --}}
                <div
                    x-cloak
                    x-show="showAutoCouponChooser"
                    x-transition.opacity
                    class="fixed inset-0 z-50"
                >
                    {{-- BACKDROP --}}
                    <div class="fixed inset-0 bg-black/50"></div>

                    {{-- MODAL CONTAINER --}}
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <div
                            x-show="showAutoCouponChooser"
                            x-transition
                            class="w-full max-w-xl"
                        >
                            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">

                                {{-- CLOSE BUTTON --}}
                                <button
                                    type="button"
                                    @click="showAutoCouponChooser = false"
                                    class="absolute right-4 top-4 text-gray-500 hover:text-gray-700"
                                >
                                    ✕
                                </button>

                                {{-- HEADER --}}
                                <div class="px-6 pt-6 pb-4 border-b">
                                    <h2 class="text-xl font-semibold">
                                        Choose Your Auto Coupon
                                    </h2>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Please choose one available auto coupon to apply. Free shipping coupons will appear after selecting a valid delivery location.
                                    </p>
                                </div>

                                {{-- BODY --}}
                                <div class="px-6 py-6 space-y-4 max-h-[500px] overflow-y-auto">

                                    <template x-for="coupon in autoCouponChoices" :key="coupon.id">
                    <label
                        class="block border rounded-2xl p-5 transition"
                        :class="{
                            'border-green-600 bg-green-50 cursor-pointer': coupon.auto_available && String(selectedAutoCouponId) === String(coupon.id),
                            'border-gray-200 bg-white cursor-pointer hover:border-orange-300': coupon.auto_available && String(selectedAutoCouponId) !== String(coupon.id),
                            'border-gray-200 bg-gray-100 opacity-70 cursor-not-allowed': !coupon.auto_available
                        }"
                    >
                        <div class="flex items-start gap-4">
                            <input
                                type="radio"
                                name="auto_coupon_choice"
                                :value="coupon.id"
                                x-model="selectedAutoCouponId"
                                :disabled="!coupon.auto_available"
                                class="mt-1"
                            >

                            <div class="flex-1 min-w-0">
                                <div
                                    class="text-lg font-semibold text-gray-900"
                                    x-text="coupon.name || coupon.code"
                                ></div>

                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="inline-block text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded">
                                        Auto Coupon
                                    </span>

                                    <template x-if="coupon.auto_available">
                                        <span class="inline-block text-xs bg-green-100 text-green-700 px-2 py-1 rounded">
                                            Available
                                        </span>
                                    </template>
                                    <template x-if="!coupon.auto_available && coupon.unavailable_reason">
                                    <span
                                        class="inline-block text-xs bg-red-100 text-red-700 px-2 py-1 rounded"
                                        x-text="coupon.unavailable_reason"
                                    ></span>
                                </template>

                                    
                                </div>

                                <div
                                    class="mt-2 text-sm text-gray-600"
                                    x-text="coupon.description || 'No description available'"
                                ></div>

                                

                                <div class="mt-3 text-sm text-orange-600 font-medium">
                                    <template x-if="coupon.reward === 'free-shipping-optn'">
                                        <span>Free Shipping Coupon</span>
                                    </template>

                                    <template x-if="coupon.reward === 'discount-amount-optn'">
                                        <span x-text="'₱' + Number(coupon.discount || 0).toFixed(2) + ' OFF'"></span>
                                    </template>

                                    <template x-if="coupon.reward === 'discount-percentage-optn'">
                                        <span x-text="Number(coupon.discount || 0) + '% OFF'"></span>
                                    </template>

                                    <template x-if="coupon.reward === 'free-product-optn'">
                                        <span>Free Product Coupon</span>
                                    </template>

                                    <template x-if="shouldShowLocationDiscount(coupon)">
                                    <div class="mt-2 text-xs font-bold text-green-700">
                                        <span x-text="locationDiscountLabel(coupon)"></span>
                                    </div>
                                </template>
                                </div>
                            </div>
                        </div>
                    </label>
                </template>

                                

                                </div>

                                {{-- FOOTER --}}
                                <div class="px-6 py-4 border-t flex justify-end gap-3">
                                    <button
                                        type="button"
                                        @click="showAutoCouponChooser = false"
                                        class="px-4 py-2 text-sm border rounded-md"
                                    >
                                        Skip
                                    </button>

                                    <button
                                        type="button"
                                        @click="applySelectedAutoCoupon()"
                                        class="px-6 py-2 text-sm bg-primary text-white rounded-md"
                                    >
                                        Apply Selected Coupon
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
              
            @include('v2.checkout.modals.coupon-modal')
    {{-- COUPON ERROR / SUCCESS POPUP --}}
<div
    x-data="{
        open: false,
        type: 'error',
        title: '',
        message: '',

        show(e) {
            this.type = e.detail.type || 'error';
            this.title = this.type === 'success' ? 'Coupon Applied' : 'Coupon Error';
            this.message = e.detail.message || '';
            this.open = true;
        },

        close() {
            this.open = false;
        }
    }"
    @coupon-popup.window="show($event)"
    x-cloak
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 flex items-center justify-center p-4"
    style="z-index: 999999;"
>
    <div
        class="absolute inset-0 bg-black/40"
        @click="close()"
    ></div>

    <div
        x-show="open"
        x-transition
        class="relative w-full max-w-sm rounded-2xl bg-white shadow-2xl overflow-hidden"
        style="z-index: 1000000;"
    >
        <div
            class="px-6 py-5"
            :class="{
                'bg-red-50': type === 'error',
                'bg-green-50': type === 'success'
            }"
        >
            <div class="flex items-start gap-4">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-lg font-black"
                    :class="{
                        'bg-red-100 text-red-600': type === 'error',
                        'bg-green-100 text-green-600': type === 'success'
                    }"
                >
                    <span x-text="type === 'success' ? '✓' : '!'"></span>
                </div>

                <div class="flex-1">
                    <h3
                        class="text-lg font-black"
                        :class="{
                            'text-red-700': type === 'error',
                            'text-green-700': type === 'success'
                        }"
                        x-text="title"
                    ></h3>

                    <p
                        class="mt-1 text-sm leading-5"
                        :class="{
                            'text-red-600': type === 'error',
                            'text-green-600': type === 'success'
                        }"
                        x-text="message"
                    ></p>
                </div>

                <button
                    type="button"
                    @click="close()"
                    class="text-gray-400 hover:text-gray-700"
                >
                    ✕
                </button>
            </div>
        </div>

        <div class="px-6 py-4 bg-white flex justify-end">
            <button
                type="button"
                @click="close()"
                class="rounded-lg px-6 py-2 text-sm font-bold text-white shadow-md"
                style="background:#0f8f43; color:#ffffff;"
            >
                Okay
            </button>
        </div>
    </div>
</div>
            @include('v2.checkout.modals.privacy-modal')
            @include('v2.checkout.modals.payment-modal')
            @include('v2.checkout.modals.block-modal')
        </div>
    </div>


    <x-footer-component />

@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.disabledPickupDates = @json($disabledPickupDates);
        window.disabledDeliveryDates = @json($disabledDeliveryDates);
        window.disabledDeliveryMiscDates = @json($disabledDeliveryMiscDates);
        window.availableCities = @json($cities);
        window.fullUrl = @json(config('app.url'));
        window.hasBaka = @json($hasbaka);
        window.hasMisc = @json($hasMisc);
        window.hasLechon = @json($haslechon);
        window.privacy = @json(auth()->check());
        window.minimum_order_amount_door_to_door = @json($minimum_order_amount_door_to_door);
        window.minimum_order_amount_pickup = @json($minimum_order_amount_pickup);
        window.minimum_processing_hours = @json($minimum_processing_hours);
        window.minimum_processing_hours_misc = @json($minimum_processing_hours_misc);
        window.minimum_order_misc = @json($minimum_order_misc);
        window.hasCochinillo = @json($hasCochinillo);
        window.minimum_processing_hours_baka = @json($minimum_processing_hours_baka);
        window.initialCarts = @json($carts);
        window.lechonBakaService = @json($lechonBakaService);
        window.eligibleCoupons = @json($eligibleCoupons);
        window.eligibleAutoCoupons = @json($eligibleAutoCoupons);
        window.allCoupons = @json($allCoupons);
        window.eligibleGiftCheques = @json($eligibleGiftCheques ?? []);
        window.APP_DEBUG = @json(config('app.debug'));
        window.sale = @json($sale ?? null);
    </script>

    <script>
        function checkoutForm() {
            return {
                lechonBakaService: window.hasBaka ? window.lechonBakaService : 0,
                isBaka: false,
                hasBaka: window.hasBaka,
                carts: window.initialCarts || [],
                coupons: [],
                method: 'pickup',
                allowMultiple: false,
                deliveryFee: 0,
                deliveryFees: [],
                sale: window.sale,

                couponPopupOpen: false,
                couponPopupTitle: '',
                couponPopupMessage: '',
                couponPopupType: 'error',

                pickup_branch: '',
                pickup_date: '',
                pickup_time: '',
                pickup_note: '',
                pickupErrors: {},
                pickupWarning: '',
                couponModal: false,
                couponCode: '',
                couponMessage: '',
                couponMessageType: '',
                eligibleCoupons: window.eligibleCoupons || [],
                availableCoupons: window.eligibleCoupons || [],
                autoCouponsSource: window.eligibleAutoCoupons || [],
                giftCheques: window.eligibleGiftCheques || [],
                allCoupons: window.allCoupons || [],
                selectedCoupon: null,
                autoAppliedCoupons: [],
                selectedAutoCoupon: null,
                showAutoCouponChooser: false,
                autoCouponChooserShownOnce: false,
                autoCouponChoices: [],
                selectedAutoCouponId: '',
                totalDiscountAmount: 0,
                shippingDiscountAmount: 0,
                shippingDiscountLists: [],
                giftChequeCode: '',
                giftChequeMessage: '',
                giftChequeMessageType: '',
                appliedGiftCheque: null,
                giftChequeDiscountAmount: 0,
                deliveryCouponPopupShownCodes: [],
                deliveryCouponPopupLocationKey: '',
                
                couponTypeLabel(coupon) {
                    if (!coupon) return '';

                    const normalized = this.normalizeCoupon(coupon);

                    if (this.isFreeShippingCoupon(normalized)) {
                        return Number(normalized.shipping_discount_amount || 0) > 0
                            ? 'Shipping Fee Discount'
                            : 'Free Shipping';
                    }

                    if (normalized.reward === 'discount-percentage-optn' || normalized.discount_type === 'percent') {
                        return 'Percentage Discount';
                    }

                    if (normalized.reward === 'discount-amount-optn' || normalized.discount_type === 'amount') {
                        return 'Fixed Amount Discount';
                    }

                    if (Array.isArray(normalized.free_products) && normalized.free_products.length > 0) {
                        return 'Free Product';
                    }

                    return 'Coupon';
                },

            get selectableCoupons() {
                if (this.hasWholeLechonInCart()) {
                    return [];
                }

                const sources = [
                    ...(this.availableCoupons || []),
                    ...(this.eligibleCoupons || []),
                    ...(this.allCoupons || []),
                    ...(this.autoCouponsSource || []),
                    ...(this.autoCouponChoices || [])
                ];

                const unique = [];

                sources
                    .map(coupon => this.normalizeCoupon(coupon))
                    .filter(coupon => {
                        const code = this.couponCodeKey(coupon);
                        return code !== '';
                    })
                    .filter(coupon => this.couponIsActive(coupon))
                    .filter(coupon => this.shouldShowCouponInList(coupon))
                    .filter(coupon => !this.couponUsageConsumed(coupon))
                    .filter(coupon => !this.couponAlreadyApplied(coupon))
                    .filter(coupon => {
                        if (typeof this.couponProductRequirementStatus !== 'function') {
                            return true;
                        }

                        return this.couponProductRequirementStatus(coupon).valid;
                    })
                    .forEach(coupon => {
                        const code = this.couponCodeKey(coupon);

                        const exists = unique.some(item =>
                            this.couponCodeKey(item) === code
                        );

                        if (!exists) {
                            unique.push({
                                ...coupon,
                                auto_applied: false,
                                activation_type: coupon.activation_type || 'manual'
                            });
                        }
                    });

                return unique;
            },

            couponCodeKey(coupon) {
                return String(coupon?.code || coupon?.coupon_code || '')
                    .trim()
                    .toUpperCase();
            },

            couponAlreadyApplied(coupon) {
                const code = this.couponCodeKey(coupon);

                if (!code) return false;

                return (this.coupons || []).some(applied =>
                    this.couponCodeKey(applied) === code
                );
            },

            keepSelectedCouponStable(coupon) {
                if (!coupon) return;

                const normalized = this.normalizeCoupon(coupon);
                const code = this.couponCodeKey(normalized);

                if (!code) return;

                this.selectedCoupon = normalized;

                if (!this.deliveryCouponPopupShownCodes.includes(code)) {
                    this.deliveryCouponPopupShownCodes.push(code);
                }
            },

            couponChoiceSources() {
                return [
                    ...(this.availableCoupons || []),
                    ...(this.eligibleCoupons || []),
                    ...(this.autoCouponsSource || []),
                    ...(this.allCoupons || [])
                ];
            },

            deliveryCouponPopupReady() {
                if (this.method !== 'delivery') {
                    return false;
                }

                if (this.allowMultiple) {
                    const activeDeliveries = (this.deliveries || [])
                        .filter(delivery => (delivery?.orders || []).length > 0);

                    if (!activeDeliveries.length) {
                        return false;
                    }

                    return activeDeliveries.every(delivery =>
                        this.normalizeText(delivery?.province) &&
                        this.normalizeText(delivery?.city)
                    );
                }

                return !!(
                    this.normalizeText(this.province) &&
                    this.normalizeText(this.city)
                );
            },

            getDeliveryQualifiedCouponChoices() {
                if (this.hasWholeLechonInCart()) {
                    return [];
                }

                if (!this.deliveryCouponPopupReady()) {
                    return [];
                }

                const unique = [];

                this.couponChoiceSources()
                    .map(coupon => this.normalizeCoupon(coupon))
                    .filter(coupon => this.couponIsActive(coupon))
                    .filter(coupon => this.shouldShowCouponInList(coupon))
                    .filter(coupon => !this.couponUsageConsumed(coupon))
                    .filter(coupon => !this.couponAlreadyApplied(coupon))
                    .filter(coupon => this.couponProductRequirementStatus(coupon).valid)
                    .forEach(coupon => {
                        const code = this.couponCodeKey(coupon);

                        if (!code) return;

                        const exists = unique.some(item =>
                            this.couponCodeKey(item) === code
                        );

                        if (!exists) {
                            unique.push({
                                ...coupon,
                                auto_applied: false,
                                activation_type: 'manual-popup'
                            });
                        }
                    });

                return unique;
            },

            deliveryCouponPopupLocationSignature() {
                if (this.method !== 'delivery') {
                    return 'method:pickup';
                }

                const normalizeTarget = (target) => [
                    this.normalizeText(target?.province || ''),
                    this.normalizeText(target?.city || ''),
                    this.normalizeText(target?.location || target?.barangay || '')
                ].join('|');

                if (this.allowMultiple) {
                    return (this.deliveries || [])
                        .filter(delivery => (delivery?.orders || []).length > 0)
                        .map(delivery => normalizeTarget(delivery))
                        .sort()
                        .join('||');
                }

                return normalizeTarget({
                    province: this.province,
                    city: this.city,
                    location: this.location
                });
            },

            resetDeliveryCouponPopupForLocationChange(force = false) {
                const currentSignature = this.deliveryCouponPopupLocationSignature();

                if (!force && currentSignature === this.deliveryCouponPopupLocationKey) {
                    return;
                }

                this.deliveryCouponPopupLocationKey = currentSignature;

                /*
                |--------------------------------------------------------------------------
                | Re-show location coupon popup when location becomes valid again
                |--------------------------------------------------------------------------
                | Example flow:
                | 1. Customer selects a valid city and coupon popup appears.
                | 2. Customer changes to an invalid city, so the coupon is removed/hidden.
                | 3. Customer selects the valid city again.
                |
                | Clear the shown-code memory so the same coupon can pop up again.
                | Already-applied coupons still will not appear because couponAlreadyApplied()
                | filters them in getDeliveryQualifiedCouponChoices().
                |--------------------------------------------------------------------------
                */
                this.deliveryCouponPopupShownCodes = [];
                this.autoCouponChooserShownOnce = false;
            },

            checkDeliveryCouponPopup() {
                const qualifiedCoupons = this.getDeliveryQualifiedCouponChoices();
                const currentSelectedCoupon = this.selectedCoupon
                    ? this.normalizeCoupon(this.selectedCoupon)
                    : null;
                const currentSelectedIsApplied = currentSelectedCoupon
                    ? this.couponAlreadyApplied(currentSelectedCoupon)
                    : false;

                /*
                |--------------------------------------------------------------------------
                | Important
                |--------------------------------------------------------------------------
                | After a coupon is applied, getDeliveryQualifiedCouponChoices() removes it
                | from the chooser because couponAlreadyApplied() is true. Do not clear
                | selectedCoupon in that case, otherwise the applied coupon can appear to
                | disappear immediately after selecting it from the coupon selection popup.
                |--------------------------------------------------------------------------
                */
                if (!qualifiedCoupons.length) {
                    if (this.couponModal && !currentSelectedIsApplied) {
                        this.selectedCoupon = null;
                    }
                    return;
                }

                if (this.showAutoCouponChooser || this.couponModal) {
                    return;
                }

                const newCoupons = qualifiedCoupons.filter(coupon =>
                    !this.deliveryCouponPopupShownCodes.includes(this.couponCodeKey(coupon))
                );

                if (!newCoupons.length) {
                    return;
                }

                newCoupons.forEach(coupon => {
                    const code = this.couponCodeKey(coupon);

                    if (code && !this.deliveryCouponPopupShownCodes.includes(code)) {
                        this.deliveryCouponPopupShownCodes.push(code);
                    }
                });

                this.selectedCoupon = this.normalizeCoupon(newCoupons[0]);
                this.couponModal = true;
            },

            refreshDeliveryCouponPopup(forceReshow = false) {
                this.$nextTick(() => {
                    this.resetDeliveryCouponPopupForLocationChange(forceReshow);
                    this.checkDeliveryCouponPopup();
                });
            },

                couponWorthLabel(coupon) {
                    if (!coupon) return '';

                    const normalized = this.normalizeCoupon(coupon);

                    if (this.isFreeShippingCoupon(normalized)) {
                        const amount = Number(normalized.shipping_discount_amount || normalized.discount || 0);
                        const type = String(normalized.shipping_discount_type || '').trim().toLowerCase();

                        if (type === 'partial' || amount > 0) {
                            return `${this.formatMoney(amount)} shipping discount`;
                        }

                        return 'Free Shipping';
                    }

                    if (normalized.reward === 'discount-percentage-optn' || normalized.discount_type === 'percent') {
                        return `${Number(normalized.discount || 0)}% off`;
                    }

                    if (normalized.reward === 'discount-amount-optn' || normalized.discount_type === 'amount') {
                        return this.formatMoney(normalized.discount || 0);
                    }

                    if (Array.isArray(normalized.free_products) && normalized.free_products.length > 0) {
                        return `${normalized.free_products.length} free item(s)`;
                    }

                    return this.formatMoney(normalized.discount || 0);
                },

                couponExpiryLabel(coupon) {
                    if (!coupon?.end_date) return 'N/A';

                    return coupon.end_time
                        ? `${coupon.end_date} ${coupon.end_time}`
                        : coupon.end_date;
                },
                hasWholeLechonInCart() {
                    if (window.hasLechon === true || window.hasLechon === 1 || window.hasLechon === '1') {
                        return true;
                    }

                    const items = [
                        ...(this.carts || []),
                        ...((this.deliveries || []).flatMap(d => d.orders || []))
                    ];

                    return items.some(item => {
                        if (item?.is_free_product) return false;

                        const product = item?.product || {};

                        const categoryId = Number(
                            product.category_id ||
                            item.category_id ||
                            0
                        );

                        const name = String(
                            product.name ||
                            item.name ||
                            product.title ||
                            ''
                        ).toLowerCase();

                        const slug = String(
                            product.slug ||
                            item.slug ||
                            ''
                        ).toLowerCase();

                        return (
                            categoryId === 1 ||
                            name.includes('whole lechon') ||
                            slug.includes('whole-lechon') ||
                            slug.includes('whole_lechon')
                        );
                    });
                },

                blockCouponIfWholeLechon() {
                    if (!this.hasWholeLechonInCart()) {
                        return false;
                    }

                    this.showCouponError('Coupons are not allowed when there is Whole Lechon in the order list.');
                    return true;
                },

                toBoolean(value) {
                    if (value === true || value === 1) return true;
                    if (value === false || value === 0 || value === null || value === undefined) return false;

                    const normalized = String(value).trim().toLowerCase();

                    return ['1', 'true', 'yes', 'y', 'on'].includes(normalized);
                },

                couponInactiveValues() {
                    return [
                        '0',
                        'false',
                        'no',
                        'n',
                        'off',
                        'inactive',
                        'disabled',
                        'deactivated',
                        'draft',
                        'archived',
                        'deleted',
                        'expired',
                        'cancelled',
                        'canceled',
                        'blocked'
                    ];
                },

                couponActiveValues() {
                    return [
                        '1',
                        'true',
                        'yes',
                        'y',
                        'on',
                        'active',
                        'enabled',
                        'published',
                        'approved',
                        'live'
                    ];
                },

                getCouponStatusValue(coupon) {
                    const statusKeys = [
                        'status',
                        'coupon_status',
                        'state',
                        'coupon_state',
                        'availability_status'
                    ];

                    for (const key of statusKeys) {
                        if (coupon?.[key] !== undefined && coupon?.[key] !== null && String(coupon[key]).trim() !== '') {
                            return String(coupon[key]).trim().toLowerCase();
                        }
                    }

                    return '';
                },

                couponIsActive(coupon) {
                    const normalized = coupon || {};
                    const inactiveValues = this.couponInactiveValues();

                    const activeFlagKeys = [
                        'is_active',
                        'active',
                        'enabled',
                        'is_enabled',
                        'coupon_active',
                        'coupon_enabled'
                    ];

                    for (const key of activeFlagKeys) {
                        if (normalized?.[key] !== undefined && normalized?.[key] !== null && String(normalized[key]).trim() !== '') {
                            const value = String(normalized[key]).trim().toLowerCase();

                            if (inactiveValues.includes(value)) {
                                return false;
                            }
                        }
                    }

                    const status = this.getCouponStatusValue(normalized);

                    if (status && inactiveValues.includes(status)) {
                        return false;
                    }

                    // If no status/active field exists, keep backward compatibility and let other coupon rules decide.
                    return true;
                },

                couponInactiveMessage(coupon) {
                    const code = this.couponCodeKey(coupon);
                    return code
                        ? `Coupon ${code} is inactive and cannot be used.`
                        : 'This coupon is inactive and cannot be used.';
                },

                couponAllowsCombinationRaw(coupon) {
                    if (!coupon) return false;

                    const keys = [
                        'combination_allowed',
                        'combination',
                        'allow_combination',
                        'allow_combine',
                        'can_combine',
                        'is_combinable',
                        'combined_coupon',
                        'combine_coupon'
                    ];

                    for (const key of keys) {
                        if (coupon?.[key] !== undefined && coupon?.[key] !== null && String(coupon[key]).trim() !== '') {
                            return this.toBoolean(coupon[key]);
                        }
                    }

                    return false;
                },

                couponCombinationStatus(candidateCoupon, existingCoupons = null) {
                    const candidate = this.normalizeCoupon(candidateCoupon);
                    const candidateCode = this.couponCodeKey(candidate);
                    const appliedCoupons = (existingCoupons || this.coupons || [])
                        .map(coupon => this.normalizeCoupon(coupon))
                        .filter(coupon => this.couponIsActive(coupon));

                    const alreadyApplied = appliedCoupons.find(coupon =>
                        this.couponCodeKey(coupon) === candidateCode
                    );

                    if (alreadyApplied) {
                        return {
                            valid: false,
                            message: alreadyApplied.auto_applied
                                ? 'This coupon is already auto-applied.'
                                : 'Coupon already applied.'
                        };
                    }

                    if (!appliedCoupons.length) {
                        return { valid: true, message: '' };
                    }

                    if (!candidate.combination_allowed) {
                        return {
                            valid: false,
                            message: 'This coupon cannot be combined with other coupons.'
                        };
                    }

                    const blockingCoupon = appliedCoupons.find(coupon => !coupon.combination_allowed);

                    if (blockingCoupon) {
                        const code = this.couponCodeKey(blockingCoupon);
                        return {
                            valid: false,
                            message: code
                                ? `Coupon ${code} does not allow combination with other coupons.`
                                : 'A coupon that does not allow combination has already been applied.'
                        };
                    }

                    return { valid: true, message: '' };
                },

                appliedCouponIds() {
                    return (this.coupons || [])
                        .map(coupon => this.normalizeCoupon(coupon))
                        .filter(coupon => this.couponIsActive(coupon))
                        .map(coupon => coupon.id)
                        .filter(id => id !== null && id !== undefined && String(id).trim() !== '');
                },

                appliedManualCouponIds() {
                    return (this.coupons || [])
                        .map(coupon => this.normalizeCoupon(coupon))
                        .filter(coupon => this.couponIsActive(coupon))
                        .filter(coupon => !coupon.auto_applied)
                        .map(coupon => coupon.id)
                        .filter(id => id !== null && id !== undefined && String(id).trim() !== '');
                },

                appliedAutoCouponIds() {
                    return (this.coupons || [])
                        .map(coupon => this.normalizeCoupon(coupon))
                        .filter(coupon => this.couponIsActive(coupon))
                        .filter(coupon => coupon.auto_applied)
                        .map(coupon => coupon.id)
                        .filter(id => id !== null && id !== undefined && String(id).trim() !== '');
                },

                normalizeCoupon(coupon) {
                    
                    const reward = String(coupon.reward ?? coupon.coupon_type ?? '').trim().toLowerCase();
                    const discountType = String(coupon.discount_type ?? '').trim().toLowerCase();
                    const activationType = String(coupon.activation_type ??coupon.coupon_activation ??coupon.activation ??'').trim().toLowerCase();

                    const shippingDiscountType = String(
                        coupon.shipping_discount_type ??
                        coupon.shipping_fee_discount_type ??
                        coupon.delivery_discount_type ??
                        coupon.free_shipping_discount_type ??
                        coupon.free_shipping_type ??
                        coupon.shipping_discount_kind ??
                        (reward === 'free-shipping-optn' ? coupon.discount_type : '') ??
                        ''
                    ).trim().toLowerCase();

                    const shippingDiscountAmount = Number(
                        coupon.shipping_discount_amount ??
                        coupon.shipping_fee_discount_amount ??
                        coupon.delivery_discount_amount ??
                        coupon.free_shipping_discount_amount ??
                        coupon.shipping_fee_discount ??
                        coupon.delivery_fee_discount ??
                        coupon.shipping_amount ??
                        coupon.amount ??
                        coupon.discount_amount ??
                        coupon.discount_value ??
                        coupon.discount ??
                        0
                    );

                    const hasExplicitAutoApplied =
                        coupon.auto_applied !== undefined &&
                        coupon.auto_applied !== null &&
                        String(coupon.auto_applied).trim() !== '';

                    return {
                        ...coupon,
                        id: coupon.id ?? null,
                        code: String(coupon.code ?? coupon.coupon_code ?? '').trim(),
                        name: coupon.name ?? coupon.coupon_name ?? coupon.coupon_code ?? 'Coupon',
                        status: coupon.status ?? coupon.coupon_status ?? coupon.state ?? '',
                        is_active: coupon.is_active ?? coupon.active ?? coupon.enabled ?? coupon.is_enabled ?? null,
                        reward,
                        activation_type: activationType,
                        location: coupon.location ?? coupon.locations ?? '',
                        purchase_combination: coupon.purchase_combination ?? coupon.purchase_conditions ?? coupon.condition_combination ?? '',
                        // Important: do NOT fall back to coupon.product_id / coupon.product_ids here.
                        // In a Free Product coupon, generic product_id can be the reward item
                        // (example: Fresh Lumpia), not the purchase-condition item (example: Bopis).
                        // Purchase conditions must come only from purchase/condition/selected fields.
                        purchase_product_id:
                            coupon.purchase_product_id ??
                            coupon.purchase_product_ids ??
                            coupon.selected_product_id ??
                            coupon.selected_product_ids ??
                            coupon.condition_product_id ??
                            coupon.condition_product_ids ??
                            coupon.minimum_purchase_product_id ??
                            coupon.minimum_purchase_product_ids ??
                            coupon.total_quantity_product_id ??
                            coupon.total_quantity_product_ids ??
                            coupon.required_product_id ??
                            coupon.required_product_ids ??
                            '',
                        purchase_product_ids:
                            coupon.purchase_product_ids ??
                            coupon.purchase_product_id ??
                            coupon.selected_product_ids ??
                            coupon.selected_product_id ??
                            coupon.condition_product_ids ??
                            coupon.condition_product_id ??
                            coupon.minimum_purchase_product_ids ??
                            coupon.minimum_purchase_product_id ??
                            coupon.total_quantity_product_ids ??
                            coupon.total_quantity_product_id ??
                            coupon.required_product_ids ??
                            coupon.required_product_id ??
                            '',
                        purchase_category_id:
                            coupon.purchase_product_cat_id ??
                            coupon.purchase_product_cat_ids ??
                            coupon.purchase_category_id ??
                            coupon.purchase_category_ids ??
                            coupon.selected_category_id ??
                            coupon.selected_category_ids ??
                            coupon.condition_category_id ??
                            coupon.condition_category_ids ??
                            coupon.minimum_purchase_category_id ??
                            coupon.minimum_purchase_category_ids ??
                            coupon.total_quantity_category_id ??
                            coupon.total_quantity_category_ids ??
                            coupon.required_category_id ??
                            coupon.required_category_ids ??
                            '',
                        purchase_product_cat_id:
                            coupon.purchase_product_cat_id ??
                            coupon.purchase_product_cat_ids ??
                            coupon.purchase_category_id ??
                            coupon.purchase_category_ids ??
                            coupon.selected_category_id ??
                            coupon.selected_category_ids ??
                            coupon.condition_category_id ??
                            coupon.condition_category_ids ??
                            '',
                        purchase_category_ids:
                            coupon.purchase_product_cat_ids ??
                            coupon.purchase_product_cat_id ??
                            coupon.purchase_category_ids ??
                            coupon.purchase_category_id ??
                            coupon.selected_category_ids ??
                            coupon.selected_category_id ??
                            coupon.condition_category_ids ??
                            coupon.condition_category_id ??
                            coupon.minimum_purchase_category_ids ??
                            coupon.minimum_purchase_category_id ??
                            coupon.total_quantity_category_ids ??
                            coupon.total_quantity_category_id ??
                            coupon.required_category_ids ??
                            coupon.required_category_id ??
                            '',
                        purchase_product_cat_ids:
                            coupon.purchase_product_cat_ids ??
                            coupon.purchase_product_cat_id ??
                            coupon.purchase_category_ids ??
                            coupon.purchase_category_id ??
                            coupon.selected_category_ids ??
                            coupon.selected_category_id ??
                            coupon.condition_category_ids ??
                            coupon.condition_category_id ??
                            '',
                        purchase_category: coupon.purchase_category ?? coupon.condition_category ?? coupon.total_quantity_category ?? coupon.category ?? '',
                        purchase_categories: coupon.purchase_categories ?? coupon.selected_categories ?? coupon.applicable_categories ?? coupon.categories ?? [],
                        category_id: coupon.purchase_product_cat_id ?? coupon.category_id ?? coupon.purchase_category_id ?? coupon.condition_category_id ?? '',
                        category_ids: coupon.purchase_product_cat_ids ?? coupon.purchase_product_cat_id ?? coupon.category_ids ?? coupon.purchase_category_ids ?? coupon.condition_category_ids ?? '',
                        category: coupon.category ?? coupon.purchase_category ?? coupon.condition_category ?? '',
                        categories: coupon.categories ?? coupon.purchase_categories ?? coupon.selected_categories ?? coupon.applicable_categories ?? coupon.category ?? [],
                        has_category_condition: coupon.has_category_condition ?? coupon.category_required ?? coupon.requires_category ?? coupon.purchase_category_enabled ?? null,
                        free_shipping:
                            this.toBoolean(coupon.free_shipping) ||
                            reward === 'free-shipping-optn' ||
                            reward === 'free_shipping' ||
                            reward === 'free-shipping' ||
                            discountType === 'free_shipping' ||
                            discountType === 'free-shipping',
                        end_date: coupon.end_date ?? '',
                        end_time: coupon.end_time ?? '',
                        description: coupon.description ?? '',
                        auto_applied: hasExplicitAutoApplied
                            ? this.toBoolean(coupon.auto_applied)
                            : activationType === 'auto',
                        combination_allowed: this.couponAllowsCombinationRaw(coupon),
                        discount_type: discountType,
                        discount: Number(
                            coupon.discount ??
                            coupon.amount ??
                            coupon.discount_amount ??
                            coupon.discount_value ??
                            coupon.percentage ??
                            coupon.discount_percent ??
                            coupon.discount_percentage ??
                            0
                        ),
                        location_discount_type: String(coupon.location_discount_type ?? '').trim().toLowerCase(),
                        location_discount_amount: Number(coupon.location_discount_amount ?? 0),
                        shipping_discount_type: shippingDiscountType,
                        shipping_discount_amount: shippingDiscountAmount,
                        free_products: Array.isArray(coupon.free_products)
                            ? coupon.free_products
                            : Object.values(coupon.free_products || {}),

                        total_usage_limit: Number(coupon.total_usage_limit ?? coupon.usage_limit ?? 0),
                        total_usage_used: Number(coupon.total_usage_used ?? coupon.total_used ?? 0),

                        customer_usage_limit: Number(coupon.customer_usage_limit ?? coupon.customer_limit ?? 0),
                        customer_usage_used: Number(coupon.customer_usage_used ?? coupon.customer_used ?? 0)
                    };
                    
                },

            couponUsageStatus(coupon) {
                const normalized = this.normalizeCoupon(coupon);

                /*
                |--------------------------------------------------------------------------
                | Coupon usage rules
                |--------------------------------------------------------------------------
                | usage_limit    = total paid uses allowed across all customers
                | customer_limit = paid uses allowed per customer
                |--------------------------------------------------------------------------
                */

                const totalLimit = Number(
                    normalized.total_usage_limit ??
                    normalized.usage_limit ??
                    normalized.usageLimit ??
                    0
                );

                const totalUsed = Number(
                    normalized.total_usage_used ??
                    normalized.total_used ??
                    normalized.used_count ??
                    normalized.total_usage ??
                    normalized.usage_count ??
                    0
                );

                if (totalLimit > 0 && totalUsed >= totalLimit) {
                    return {
                        consumed: true,
                        type: 'total',
                        message: 'This coupon has reached its total usage limit.'
                    };
                }

                const customerLimit = Number(
                    normalized.customer_usage_limit ??
                    normalized.customer_limit ??
                    normalized.per_customer_limit ??
                    0
                );

                const customerUsed = Number(
                    normalized.customer_usage_used ??
                    normalized.customer_used ??
                    normalized.used_by_customer ??
                    normalized.my_usage_count ??
                    0
                );

                if (customerLimit > 0 && customerUsed >= customerLimit) {
                    return {
                        consumed: true,
                        type: 'customer',
                        message: 'You have already used this coupon.'
                    };
                }

                return {
                    consumed: false,
                    type: '',
                    message: ''
                };
            },

            couponUsageConsumed(coupon) {
                return this.couponUsageStatus(coupon).consumed;
            },


            parseCouponArray(value) {
                if (Array.isArray(value)) return value;
                if (!value) return [];

                if (typeof value === 'object') {
                    return Object.values(value);
                }

                const text = String(value || '').trim();

                if (!text || text === 'null' || text === 'undefined' || text === '[]' || text === '{}') {
                    return [];
                }

                if (text.startsWith('[') || text.startsWith('{')) {
                    try {
                        const parsed = JSON.parse(text);

                        if (Array.isArray(parsed)) return parsed;
                        if (parsed && typeof parsed === 'object') return Object.values(parsed);
                    } catch (e) {}
                }

                return text.split(/[|,]/).map(v => v.trim()).filter(Boolean);
            },

            normalizeCouponProductText(value) {
                return String(value ?? '')
                    .toLowerCase()
                    .replace(/&/g, ' and ')
                    .replace(/[^a-z0-9]+/g, ' ')
                    .replace(/\s+/g, ' ')
                    .trim();
            },

            couponRequiredProductQuantity(coupon) {
                const qtyKeys = [
                    // Admin Purchase Conditions: Total Quantity + Minimum
                    'total_quantity',
                    'total_qty',
                    'minimum_total_quantity',
                    'min_total_quantity',
                    'purchase_total_quantity',
                    'purchase_total_qty',
                    'purchase_condition_total_quantity',
                    'purchase_condition_total_qty',
                    'condition_total_quantity',
                    'condition_total_qty',
                    'minimum_purchase_total_quantity',
                    'minimum_purchase_total_qty',
                    'minimum_purchase_quantity',
                    'minimum_purchase_qty',
                    'min_purchase_quantity',
                    'min_purchase_qty',
                    'purchase_minimum_quantity',
                    'purchase_minimum_qty',

                    // Product quantity aliases
                    'total_product_quantity',
                    'total_products_quantity',
                    'total_product_qty',
                    'total_products_qty',
                    'product_total_quantity',
                    'product_total_qty',
                    'coupon_product_quantity',
                    'coupon_product_qty',
                    'coupon_total_product_quantity',
                    'coupon_total_product_qty',
                    'required_product_quantity',
                    'minimum_product_quantity',
                    'min_product_quantity',
                    'product_quantity_required',
                    'required_product_count',
                    'minimum_product_count',
                    'min_product_count',
                    'product_count',
                    'total_count',
                    'minimum_count',
                    'min_count',
                    'required_count',
                    'purchase_count',
                    'purchase_qty',

                    // Generic quantity aliases
                    'required_quantity',
                    'minimum_quantity',
                    'min_quantity',
                    'required_qty',
                    'minimum_qty',
                    'min_qty',
                    'quantity',
                    'qty',
                    'product_qty',
                    'qty_required',
                    'buy_quantity',
                    'buy_qty'
                ];

                const parseMaybeJson = (value) => {
                    if (!value) return [];
                    if (Array.isArray(value)) return value;

                    if (typeof value === 'object') {
                        return [value];
                    }

                    const text = String(value || '').trim();

                    if (!text || text === 'null' || text === 'undefined' || text === '[]' || text === '{}') {
                        return [];
                    }

                    if (text.startsWith('[') || text.startsWith('{')) {
                        try {
                            const parsed = JSON.parse(text);
                            return Array.isArray(parsed) ? parsed : [parsed];
                        } catch (e) {}
                    }

                    return [];
                };

                const sources = [];
                const visited = new Set();

                const addSource = (source) => {
                    parseMaybeJson(source).forEach(item => {
                        if (!item || typeof item !== 'object') return;

                        const visitKey = JSON.stringify(item);
                        if (visited.has(visitKey)) return;
                        visited.add(visitKey);

                        sources.push(item);

                        [
                            item.purchase_condition,
                            item.purchase_conditions,
                            item.minimum_purchase,
                            item.minimum_purchase_condition,
                            item.minimum_purchase_conditions,
                            item.purchase_rule,
                            item.purchase_rules,
                            item.coupon_condition,
                            item.coupon_conditions,
                            item.requirement,
                            item.requirements,
                            item.minimum_requirement,
                            item.minimum_requirements,
                            item.condition,
                            item.conditions,
                            item.product_condition,
                            item.product_conditions,
                            item.product_rule,
                            item.product_rules,
                            item.rule,
                            item.rules,
                            item.pivot
                        ].forEach(addSource);
                    });
                };

                addSource(coupon);

                for (const source of sources) {
                    for (const key of qtyKeys) {
                        const qty = Number(source?.[key] ?? 0);

                        if (qty > 0) {
                            return qty;
                        }
                    }
                }

                // Do not read quantity from coupon name/description/code.
                // Minimum count must come from the saved purchase-condition fields only.
                return 0;
            },

            normalizeCouponProductId(value) {
                if (value === null || value === undefined) return '';

                if (typeof value === 'object') {
                    return this.normalizeCouponProductId(
                        value.product_id ??
                        value.productId ??
                        value.required_product_id ??
                        value.buy_product_id ??
                        value.selected_product_id ??
                        value.id ??
                        value.value ??
                        value.product?.id ??
                        value.pivot?.product_id ??
                        ''
                    );
                }

                const text = String(value).trim();

                if (!text || text === 'null' || text === 'undefined') return '';

                // Keep this strict. Product requirements must match by ID, not by product name.
                return /^\d+$/.test(text) ? text : '';
            },

            parseCouponProductIds(value) {
                if (value === null || value === undefined || value === '') return [];

                const ids = [];
                const pushId = (id) => {
                    const normalizedId = this.normalizeCouponProductId(id);
                    if (normalizedId && !ids.includes(normalizedId)) {
                        ids.push(normalizedId);
                    }
                };

                const parseValue = (input) => {
                    if (input === null || input === undefined || input === '') return;

                    if (Array.isArray(input)) {
                        input.forEach(parseValue);
                        return;
                    }

                    if (typeof input === 'object') {
                        pushId(input);
                        return;
                    }

                    const text = String(input || '').trim();

                    if (!text || text === 'null' || text === 'undefined' || text === '[]' || text === '{}') {
                        return;
                    }

                    if (text.startsWith('[') || text.startsWith('{')) {
                        try {
                            const parsed = JSON.parse(text);
                            parseValue(parsed);
                            return;
                        } catch (e) {}
                    }

                    // Accept only numeric IDs from comma/pipe separated values.
                    // Product names like "Bopis" or "Lumpia" are intentionally ignored.
                    text.split(/[|,]/).map(v => v.trim()).forEach(pushId);
                };

                parseValue(value);

                return ids;
            },

            normalizeCouponCategoryId(value) {
                if (value === null || value === undefined) return '';

                if (typeof value === 'object') {
                    return this.normalizeCouponCategoryId(
                        value.category_id ??
                        value.categoryId ??
                        value.required_category_id ??
                        value.requiredCategoryId ??
                        value.purchase_product_cat_id ??
                        value.purchaseProductCatId ??
                        value.purchase_product_cat_ids ??
                        value.purchaseProductCatIds ??
                        value.purchase_category_id ??
                        value.purchaseCategoryId ??
                        value.selected_category_id ??
                        value.selectedCategoryId ??
                        value.condition_category_id ??
                        value.conditionCategoryId ??
                        value.total_quantity_category_id ??
                        value.totalQuantityCategoryId ??
                        value.category?.id ??
                        value.pivot?.category_id ??
                        value.id ??
                        value.value ??
                        ''
                    );
                }

                const text = String(value).trim();

                if (!text || text === 'null' || text === 'undefined') return '';

                // Keep category purchase conditions strict. Match by category ID only,
                // never by category name like "Party Trays".
                return /^\d+$/.test(text) ? text : '';
            },

            parseCouponCategoryIds(value) {
                if (value === null || value === undefined || value === '') return [];

                const ids = [];
                const pushId = (id) => {
                    const normalizedId = this.normalizeCouponCategoryId(id);
                    if (normalizedId && !ids.includes(normalizedId)) {
                        ids.push(normalizedId);
                    }
                };

                const parseValue = (input) => {
                    if (input === null || input === undefined || input === '') return;

                    if (Array.isArray(input)) {
                        input.forEach(parseValue);
                        return;
                    }

                    if (typeof input === 'object') {
                        pushId(input);
                        return;
                    }

                    const text = String(input || '').trim();

                    if (!text || text === 'null' || text === 'undefined' || text === '[]' || text === '{}') {
                        return;
                    }

                    if (text.startsWith('[') || text.startsWith('{')) {
                        try {
                            const parsed = JSON.parse(text);
                            parseValue(parsed);
                            return;
                        } catch (e) {}
                    }

                    // Accept only numeric IDs from comma/pipe separated values.
                    // Category names are intentionally ignored.
                    text.split(/[|,]/).map(v => v.trim()).forEach(pushId);
                };

                parseValue(value);

                return ids;
            },

            couponValueHasMeaningfulData(value) {
                if (value === null || value === undefined) return false;

                if (Array.isArray(value)) {
                    return value.some(item => this.couponValueHasMeaningfulData(item));
                }

                if (typeof value === 'object') {
                    return Object.values(value).some(item => this.couponValueHasMeaningfulData(item));
                }

                const text = String(value || '').trim();
                if (!text) return false;

                const lowered = this.normalizeText(text);
                return ![
                    'null',
                    'undefined',
                    '[]',
                    '{}',
                    'all',
                    'all area',
                    'all areas',
                    'all_area',
                    'all_areas',
                    'all location',
                    'all locations',
                    'all city',
                    'all cities',
                    'all barangay',
                    'all barangays',
                    'any',
                    'any area',
                    'any areas',
                    'none',
                    'n/a',
                    'na',
                    '-'
                ].includes(lowered);
            },

            couponHasCategoryConditionSource(coupon) {
                const normalized = coupon || {};

                if (this.getCouponSelectedCategoryIds(normalized).length > 0) {
                    return true;
                }

                const rawCategoryValues = [
                    normalized.purchase_product_cat_id,
                    normalized.purchase_product_cat_ids,
                    normalized.purchase_category_id,
                    normalized.purchase_category_ids,
                    normalized.purchase_category,
                    normalized.purchase_categories,
                    normalized.category_id,
                    normalized.category_ids,
                    normalized.category,
                    normalized.categories,
                    normalized.selected_category_id,
                    normalized.selected_category_ids,
                    normalized.selected_categories,
                    normalized.condition_category_id,
                    normalized.condition_category_ids,
                    normalized.condition_category,
                    normalized.condition_categories,
                    normalized.total_quantity_category_id,
                    normalized.total_quantity_category_ids,
                    normalized.total_quantity_category,
                    normalized.total_quantity_categories
                ];

                if (rawCategoryValues.some(value => this.couponValueHasMeaningfulData(value))) {
                    return true;
                }

                const categoryFlags = [
                    normalized.has_category_condition,
                    normalized.category_condition_enabled,
                    normalized.category_required,
                    normalized.requires_category,
                    normalized.is_category_condition,
                    normalized.purchase_category_enabled,
                    normalized.category_optn,
                    normalized.category_option
                ];

                if (categoryFlags.some(value => this.toBoolean(value))) {
                    return true;
                }

                return this.parseCouponArray(normalized.purchase_combination)
                    .map(value => this.normalizeText(value))
                    .some(value => value === 'category' || value.includes('category'));
            },

            getCouponSelectedCategoryIds(coupon) {
                const normalized = this.normalizeCoupon(coupon);
                const ids = [];
                const visited = new Set();

                const addIds = (value) => {
                    this.parseCouponCategoryIds(value).forEach(id => {
                        if (!ids.includes(id)) ids.push(id);
                    });
                };

                const scanSource = (source) => {
                    if (!source) return;

                    if (Array.isArray(source)) {
                        source.forEach(scanSource);
                        return;
                    }

                    if (typeof source === 'string') {
                        const text = source.trim();

                        if (!text || text === 'null' || text === 'undefined' || text === '[]' || text === '{}') {
                            return;
                        }

                        if (text.startsWith('[') || text.startsWith('{')) {
                            try {
                                scanSource(JSON.parse(text));
                            } catch (e) {}
                            return;
                        }

                        addIds(text);
                        return;
                    }

                    if (typeof source !== 'object') {
                        addIds(source);
                        return;
                    }

                    const visitKey = JSON.stringify(source);
                    if (visited.has(visitKey)) return;
                    visited.add(visitKey);

                    const isRootCoupon = source === normalized;

                    // Some controllers send the purchase category as plain
                    // category_id/category_ids. Keep this fallback for old payloads.
                    addIds(source.category_id);
                    addIds(source.categoryId);
                    addIds(source.category_ids);
                    addIds(source.categoryIds);
                    addIds(source.category);

                    // Nested category objects may only have {id, name}.
                    if (!isRootCoupon) {
                        addIds(source.id);
                        addIds(source.value);
                        addIds(source.pivot?.category_id);
                        addIds(source.pivot?.categoryId);
                    }

                    addIds(source.required_category_id);
                    addIds(source.required_category_ids);
                    addIds(source.purchase_product_cat_id);
                    addIds(source.purchase_product_cat_ids);
                    addIds(source.purchaseProductCatId);
                    addIds(source.purchaseProductCatIds);
                    addIds(source.purchase_category_id);
                    addIds(source.purchase_category_ids);
                    addIds(source.selected_category_id);
                    addIds(source.selected_category_ids);
                    addIds(source.minimum_purchase_category_id);
                    addIds(source.minimum_purchase_category_ids);
                    addIds(source.condition_category_id);
                    addIds(source.condition_category_ids);
                    addIds(source.total_quantity_category_id);
                    addIds(source.total_quantity_category_ids);
                    addIds(source.coupon_category_id);
                    addIds(source.coupon_category_ids);

                    [
                        source.required_categories,
                        source.coupon_categories,
                        source.categories,
                        source.applicable_categories,
                        source.eligible_categories,
                        source.selected_categories,
                        source.buy_categories,
                        source.purchase_product_cat_id,
                        source.purchase_product_cat_ids,
                        source.purchase_categories,
                        source.purchase_category,
                        source.minimum_purchase_categories,
                        source.condition_categories,
                        source.total_quantity_categories,
                        source.purchase_condition,
                        source.purchase_conditions,
                        source.coupon_condition,
                        source.coupon_conditions,
                        source.requirement,
                        source.requirements,
                        source.minimum_requirement,
                        source.minimum_requirements,
                        source.minimum_purchase,
                        source.minimum_purchase_condition,
                        source.minimum_purchase_conditions,
                        source.category_condition,
                        source.category_conditions,
                        source.condition,
                        source.conditions,
                        source.rule,
                        source.rules,
                        source.pivot
                    ].forEach(scanSource);
                };

                scanSource(normalized);

                return ids;
            },


            couponFreeProductIds(coupon) {
                const ids = [];
                const visited = new Set();

                const pushId = (value) => {
                    const normalizedId = this.normalizeCouponProductId(value);
                    if (normalizedId && !ids.includes(normalizedId)) {
                        ids.push(normalizedId);
                    }
                };

                const scan = (value) => {
                    if (value === null || value === undefined || value === '') return;

                    if (Array.isArray(value)) {
                        value.forEach(scan);
                        return;
                    }

                    if (typeof value === 'string') {
                        const text = value.trim();

                        if (!text || text === 'null' || text === 'undefined' || text === '[]' || text === '{}') {
                            return;
                        }

                        if (text.startsWith('[') || text.startsWith('{')) {
                            try {
                                scan(JSON.parse(text));
                                return;
                            } catch (e) {}
                        }

                        // Plain numeric string means free product ID.
                        pushId(text);
                        return;
                    }

                    if (typeof value !== 'object') {
                        pushId(value);
                        return;
                    }

                    const visitKey = JSON.stringify(value);
                    if (visited.has(visitKey)) return;
                    visited.add(visitKey);

                    // Free product reward payloads may come as {id}, {product_id},
                    // {free_product_id}, or nested {product: {id}} depending on the controller.
                    pushId(value.free_product_id);
                    pushId(value.freeProductId);
                    pushId(value.reward_product_id);
                    pushId(value.rewardProductId);
                    pushId(value.gift_product_id);
                    pushId(value.giftProductId);
                    pushId(value.product_id);
                    pushId(value.productId);
                    pushId(value.product?.id);
                    pushId(value.id);

                    [
                        value.product,
                        value.free_product,
                        value.freeProduct,
                        value.reward_product,
                        value.rewardProduct,
                        value.gift_product,
                        value.giftProduct,
                        value.free_products,
                        value.reward_products,
                        value.gift_products
                    ].forEach(scan);
                };

                const normalized = coupon || {};

                [
                    normalized.free_products,
                    normalized.free_product,
                    normalized.freeProduct,
                    normalized.free_product_id,
                    normalized.free_product_ids,
                    normalized.freeProductId,
                    normalized.freeProductIds,
                    normalized.reward_product,
                    normalized.reward_products,
                    normalized.reward_product_id,
                    normalized.reward_product_ids,
                    normalized.gift_product,
                    normalized.gift_products,
                    normalized.gift_product_id,
                    normalized.gift_product_ids
                ].forEach(scan);

                return ids;
            },

            getCouponSelectedProductIds(coupon) {
                const normalized = this.normalizeCoupon(coupon);
                const ids = [];
                const visited = new Set();
                const freeProductIds = this.couponFreeProductIds(normalized);

                const addIds = (value, options = {}) => {
                    this.parseCouponProductIds(value).forEach(id => {
                        // Generic root product_id/product_ids can be the Free Product reward.
                        // Use it as a purchase condition only when it is not the free reward item.
                        if (options.excludeFreeProductIds && freeProductIds.includes(id)) {
                            return;
                        }

                        if (id && !ids.includes(id)) ids.push(id);
                    });
                };

                const scanSource = (source) => {
                    if (!source) return;

                    if (Array.isArray(source)) {
                        source.forEach(scanSource);
                        return;
                    }

                    if (typeof source === 'string') {
                        const text = source.trim();

                        if (!text || text === 'null' || text === 'undefined' || text === '[]' || text === '{}') {
                            return;
                        }

                        if (text.startsWith('[') || text.startsWith('{')) {
                            try {
                                scanSource(JSON.parse(text));
                            } catch (e) {}
                            return;
                        }

                        addIds(text);
                        return;
                    }

                    if (typeof source !== 'object') {
                        addIds(source);
                        return;
                    }

                    const visitKey = JSON.stringify(source);
                    if (visited.has(visitKey)) return;
                    visited.add(visitKey);

                    const isRootCoupon = source === normalized;

                    if (isRootCoupon) {
                        // Some controllers still send the selected Product Name field as
                        // product_id/product_ids. Allow it, but never let the Free Product
                        // reward item (Fresh Lumpia) become the required purchase item.
                        addIds(source.product_id, { excludeFreeProductIds: true });
                        addIds(source.productId, { excludeFreeProductIds: true });
                        addIds(source.product_ids, { excludeFreeProductIds: true });
                        addIds(source.productIds, { excludeFreeProductIds: true });
                        addIds(source.product, { excludeFreeProductIds: true });
                    } else {
                        // Nested product objects usually come from coupon.products or
                        // purchase_products and may only have {id, name}. Include id/value.
                        addIds(source.product_id);
                        addIds(source.productId);
                        addIds(source.product_ids);
                        addIds(source.productIds);
                        addIds(source.product);
                        addIds(source.id);
                        addIds(source.value);
                        addIds(source.pivot?.product_id);
                        addIds(source.pivot?.productId);
                    }

                    addIds(source.required_product_id);
                    addIds(source.required_product_ids);
                    addIds(source.buy_product_id);
                    addIds(source.buy_product_ids);
                    addIds(source.selected_product_id);
                    addIds(source.selected_product_ids);
                    addIds(source.purchase_product_id);
                    addIds(source.purchase_product_ids);
                    addIds(source.minimum_purchase_product_id);
                    addIds(source.minimum_purchase_product_ids);
                    addIds(source.condition_product_id);
                    addIds(source.condition_product_ids);
                    addIds(source.total_quantity_product_id);
                    addIds(source.total_quantity_product_ids);

                    [
                        source.required_products,
                        source.coupon_products,
                        source.products,
                        source.applicable_products,
                        source.eligible_products,
                        source.selected_products,
                        source.buy_products,
                        source.purchase_products,
                        source.purchase_product,
                        source.minimum_purchase_products,
                        source.condition_products,
                        source.total_quantity_products,
                        source.purchase_condition,
                        source.purchase_conditions,
                        source.coupon_condition,
                        source.coupon_conditions,
                        source.requirement,
                        source.requirements,
                        source.minimum_requirement,
                        source.minimum_requirements,
                        source.minimum_purchase,
                        source.minimum_purchase_condition,
                        source.minimum_purchase_conditions,
                        source.product_condition,
                        source.product_conditions,
                        source.condition,
                        source.conditions,
                        source.rule,
                        source.rules,
                        source.pivot
                    ].forEach(scanSource);
                };

                scanSource(normalized);

                return ids;
            },

            selectedProductNameCandidates(coupon) {
                const normalized = coupon || {};
                const names = [];

                const pushName = (value) => {
                    if (value === null || value === undefined) return;

                    if (Array.isArray(value)) {
                        value.forEach(pushName);
                        return;
                    }

                    if (typeof value === 'object') {
                        pushName(value.product_name);
                        pushName(value.productName);
                        pushName(value.purchase_product_name);
                        pushName(value.selected_product_name);
                        pushName(value.required_product_name);
                        pushName(value.text);
                        pushName(value.label);
                        pushName(value.title);
                        pushName(value.name);
                        pushName(value.product?.name);
                        return;
                    }

                    const text = String(value || '').trim();
                    if (!text || text === 'null' || text === 'undefined' || text === '[]' || text === '{}') return;

                    if (text.startsWith('[') || text.startsWith('{')) {
                        try {
                            pushName(JSON.parse(text));
                            return;
                        } catch (e) {}
                    }

                    // Numeric values are IDs, not names.
                    if (/^\d+$/.test(text)) return;

                    const normalizedName = this.normalizeCouponProductText(text);
                    if (normalizedName && !names.includes(normalizedName)) {
                        names.push(normalizedName);
                    }
                };

                [
                    normalized.purchase_product_name,
                    normalized.purchase_product_names,
                    normalized.selected_product_name,
                    normalized.selected_product_names,
                    normalized.required_product_name,
                    normalized.required_product_names,
                    normalized.condition_product_name,
                    normalized.condition_product_names,
                    normalized.minimum_purchase_product_name,
                    normalized.minimum_purchase_product_names,
                    normalized.total_quantity_product_name,
                    normalized.total_quantity_product_names,
                    // Compatibility only: some old payloads put the selected purchase
                    // product name inside purchase_product_id. Exact-name resolution is
                    // used only to recover the cart product ID; matching still uses ID.
                    normalized.purchase_product_id,
                    normalized.purchase_product_ids,
                    normalized.purchase_product,
                    normalized.purchase_products,
                    normalized.selected_product,
                    normalized.selected_products,
                    normalized.required_products,
                    normalized.condition_products,
                    normalized.minimum_purchase_products,
                    normalized.total_quantity_products,
                    normalized.purchase_condition,
                    normalized.purchase_conditions,
                    normalized.minimum_purchase,
                    normalized.minimum_purchase_condition,
                    normalized.minimum_purchase_conditions
                ].forEach(pushName);

                return names;
            },

            selectedCategoryNameCandidates(coupon) {
                const normalized = coupon || {};
                const names = [];

                const pushName = (value) => {
                    if (value === null || value === undefined) return;

                    if (Array.isArray(value)) {
                        value.forEach(pushName);
                        return;
                    }

                    if (typeof value === 'object') {
                        pushName(value.category_name);
                        pushName(value.categoryName);
                        pushName(value.purchase_category_name);
                        pushName(value.purchase_product_cat_name);
                        pushName(value.selected_category_name);
                        pushName(value.required_category_name);
                        pushName(value.text);
                        pushName(value.label);
                        pushName(value.title);
                        pushName(value.name);
                        pushName(value.category?.name);
                        return;
                    }

                    const text = String(value || '').trim();
                    if (!text || text === 'null' || text === 'undefined' || text === '[]' || text === '{}') return;

                    if (text.startsWith('[') || text.startsWith('{')) {
                        try {
                            pushName(JSON.parse(text));
                            return;
                        } catch (e) {}
                    }

                    if (/^\d+$/.test(text)) return;

                    const normalizedName = this.normalizeCouponProductText(text);
                    if (normalizedName && !names.includes(normalizedName)) {
                        names.push(normalizedName);
                    }
                };

                [
                    normalized.purchase_category_name,
                    normalized.purchase_category_names,
                    normalized.purchase_product_cat_name,
                    normalized.purchase_product_cat_names,
                    normalized.selected_category_name,
                    normalized.selected_category_names,
                    normalized.required_category_name,
                    normalized.required_category_names,
                    normalized.condition_category_name,
                    normalized.condition_category_names,
                    normalized.minimum_purchase_category_name,
                    normalized.minimum_purchase_category_names,
                    normalized.total_quantity_category_name,
                    normalized.total_quantity_category_names,
                    normalized.purchase_product_cat_id,
                    normalized.purchase_product_cat_ids,
                    normalized.purchase_category_id,
                    normalized.purchase_category_ids,
                    normalized.purchase_category,
                    normalized.purchase_categories,
                    normalized.selected_category,
                    normalized.selected_categories,
                    normalized.required_categories,
                    normalized.condition_categories,
                    normalized.minimum_purchase_categories,
                    normalized.total_quantity_categories,
                    normalized.purchase_condition,
                    normalized.purchase_conditions,
                    normalized.minimum_purchase,
                    normalized.minimum_purchase_condition,
                    normalized.minimum_purchase_conditions
                ].forEach(pushName);

                return names;
            },

            resolveCouponProductIdsFromSelectedNames(coupon) {
                const selectedNames = this.selectedProductNameCandidates(coupon);
                if (!selectedNames.length) return [];

                const ids = [];

                (this.cartItemsForCouponQualification() || []).forEach(item => {
                    const product = item?.product || {};
                    const cartProductId = this.normalizeCouponProductId(item?.product_id ?? product?.id ?? '');

                    if (!cartProductId) return;

                    const cartNames = [
                        item?.product_name,
                        item?.name,
                        item?.title,
                        product?.name,
                        product?.title,
                        product?.slug,
                        item?.slug
                    ].map(value => this.normalizeCouponProductText(value)).filter(Boolean);

                    if (cartNames.some(name => selectedNames.includes(name)) && !ids.includes(cartProductId)) {
                        ids.push(cartProductId);
                    }
                });

                return ids;
            },

            resolveCouponCategoryIdsFromSelectedNames(coupon) {
                const selectedNames = this.selectedCategoryNameCandidates(coupon);
                if (!selectedNames.length) return [];

                const ids = [];

                (this.cartItemsForCouponQualification() || []).forEach(item => {
                    const product = item?.product || {};
                    const category = product?.category || item?.category || {};
                    const cartCategoryId = this.normalizeCouponCategoryId(
                        item?.category_id ??
                        item?.product_category_id ??
                        product?.category_id ??
                        category?.id ??
                        ''
                    );

                    if (!cartCategoryId) return;

                    const cartNames = [
                        item?.category_name,
                        item?.product_category_name,
                        product?.category_name,
                        category?.name,
                        category?.title,
                        category?.slug
                    ].map(value => this.normalizeCouponProductText(value)).filter(Boolean);

                    if (cartNames.some(name => selectedNames.includes(name)) && !ids.includes(cartCategoryId)) {
                        ids.push(cartCategoryId);
                    }
                });

                return ids;
            },

            couponHasProductConditionSource(coupon) {
                const normalized = coupon || {};

                if (this.getCouponSelectedProductIds(normalized).length > 0) {
                    return true;
                }

                if (this.selectedProductNameCandidates(normalized).length > 0) {
                    return true;
                }

                const rawProductValues = [
                    normalized.purchase_product_id,
                    normalized.purchase_product_ids,
                    normalized.purchase_product,
                    normalized.purchase_products,
                    normalized.selected_product_id,
                    normalized.selected_product_ids,
                    normalized.selected_product,
                    normalized.selected_products,
                    normalized.condition_product_id,
                    normalized.condition_product_ids,
                    normalized.condition_product,
                    normalized.condition_products,
                    normalized.minimum_purchase_product_id,
                    normalized.minimum_purchase_product_ids,
                    normalized.minimum_purchase_products,
                    normalized.total_quantity_product_id,
                    normalized.total_quantity_product_ids,
                    normalized.total_quantity_products
                ];

                if (rawProductValues.some(value => this.couponValueHasMeaningfulData(value))) {
                    return true;
                }

                return this.parseCouponArray(normalized.purchase_combination)
                    .map(value => this.normalizeText(value))
                    .some(value => value === 'product' || value.includes('product'));
            },

            getCouponProductRules(coupon) {
                const normalized = this.normalizeCoupon(coupon);
                const defaultRequiredQty = Number(this.couponRequiredProductQuantity(normalized) || 0);
                const productIds = [
                    ...this.getCouponSelectedProductIds(normalized),
                    ...this.resolveCouponProductIdsFromSelectedNames(normalized)
                ].filter((id, index, list) => id && list.indexOf(id) === index);
                const categoryIds = [
                    ...this.getCouponSelectedCategoryIds(normalized),
                    ...this.resolveCouponCategoryIdsFromSelectedNames(normalized)
                ].filter((id, index, list) => id && list.indexOf(id) === index);
                const rules = [];

                productIds.forEach(productId => {
                    rules.push({
                        product_id: String(productId),
                        category_id: '',
                        required_qty: defaultRequiredQty,
                        name: '',
                        slug: '',
                        source_type: 'product'
                    });
                });

                categoryIds.forEach(categoryId => {
                    rules.push({
                        product_id: '',
                        category_id: String(categoryId),
                        required_qty: defaultRequiredQty,
                        name: '',
                        slug: '',
                        source_type: 'category'
                    });
                });

                return rules;
            },

            cartItemsForCouponQualification() {
                const cartItems = (this.carts || []).filter(item => !item?.is_free_product);

                if (this.allowMultiple && Array.isArray(this.deliveries)) {
                    const deliveryItems = this.deliveries.flatMap(delivery => delivery?.orders || [])
                        .filter(item => !item?.is_free_product);

                    // In multiple-address checkout, delivery.orders are assigned from the same cart items.
                    // Do not add carts + delivery.orders together, or QTY 1 can be counted as QTY 2.
                    if (deliveryItems.length) {
                        return deliveryItems;
                    }
                }

                return cartItems;
            },

            cartQtyForCouponRule(rule) {
                return this.cartItemsForCouponQualification().reduce((sum, item) => {
                    return this.couponRuleMatchesCartItem(rule, item)
                        ? sum + Number(item?.qty || 1)
                        : sum;
                }, 0);
            },

            couponRuleRequirementLabel(rule) {
                const productId = this.normalizeCouponProductId(rule?.product_id ?? '');
                const categoryId = this.normalizeCouponCategoryId(rule?.category_id ?? '');

                if (productId && categoryId) {
                    return `product ID ${productId} under category ID ${categoryId}`;
                }

                if (productId) {
                    return `product ID ${productId}`;
                }

                if (categoryId) {
                    return `category ID ${categoryId}`;
                }

                if (rule?.source_name) {
                    return `selected ${rule.source_type || 'product/category'} ${rule.source_name}`;
                }

                return 'selected product/category';
            },

            couponRuleRequirementStatuses(coupon) {
                const normalized = this.normalizeCoupon(coupon);
                const rules = this.getCouponProductRules(normalized);
                const defaultRequiredQty = Math.max(
                    Number(this.couponEffectiveRequiredProductQty(normalized) || 0),
                    1
                );

                return rules.map(rule => {
                    const requiredQty = Math.max(Number(rule?.required_qty || defaultRequiredQty || 0), 1);
                    const matchedQty = Number(this.cartQtyForCouponRule(rule) || 0);

                    return {
                        ...rule,
                        required_qty: requiredQty,
                        matched_qty: matchedQty,
                        valid: matchedQty >= requiredQty,
                        label: this.couponRuleRequirementLabel(rule)
                    };
                });
            },

            couponRuleMatchesCartItem(rule, item) {
                const product = item?.product || {};
                const cartProductId = this.normalizeCouponProductId(item?.product_id ?? product?.id ?? '');
                const cartCategoryId = this.normalizeCouponCategoryId(
                    item?.category_id ??
                    item?.product_category_id ??
                    product?.category_id ??
                    product?.category?.id ??
                    ''
                );
                const ruleProductId = this.normalizeCouponProductId(rule?.product_id ?? '');
                const ruleCategoryId = this.normalizeCouponCategoryId(rule?.category_id ?? '');

                // Strict ID matching only. Never match by product name, slug, or category name.
                if (ruleProductId && ruleCategoryId) {
                    return cartProductId === ruleProductId && cartCategoryId === ruleCategoryId;
                }

                if (ruleProductId) {
                    return !!(cartProductId && cartProductId === ruleProductId);
                }

                if (ruleCategoryId) {
                    return !!(cartCategoryId && cartCategoryId === ruleCategoryId);
                }

                return false;
            },

            couponHasSelectedProductCondition(coupon) {
                const normalized = this.normalizeCoupon(coupon);

                if (
                    this.getCouponSelectedProductIds(normalized).length > 0 ||
                    this.resolveCouponProductIdsFromSelectedNames(normalized).length > 0 ||
                    this.getCouponSelectedCategoryIds(normalized).length > 0 ||
                    this.resolveCouponCategoryIdsFromSelectedNames(normalized).length > 0 ||
                    this.couponHasProductConditionSource(normalized) ||
                    this.couponHasCategoryConditionSource(normalized)
                ) {
                    return true;
                }

                const directFlags = [
                    normalized.product_condition_enabled,
                    normalized.product_required,
                    normalized.requires_product,
                    normalized.has_product_condition,
                    normalized.is_product_condition,
                    normalized.purchase_product_enabled,
                    normalized.product_optn,
                    normalized.product_option,
                    normalized.category_condition_enabled,
                    normalized.category_required,
                    normalized.requires_category,
                    normalized.has_category_condition,
                    normalized.is_category_condition,
                    normalized.purchase_category_enabled,
                    normalized.category_optn,
                    normalized.category_option
                ];

                if (directFlags.some(value => this.toBoolean(value))) {
                    return true;
                }

                const textValues = [
                    normalized.condition_type,
                    normalized.purchase_condition_type,
                    normalized.minimum_purchase_type,
                    normalized.purchase_requirement_type,
                    normalized.requirement_type
                ];

                return textValues.some(value => {
                    const text = this.normalizeText(value);
                    return text === 'product' ||
                        text === 'category' ||
                        text.includes('product-optn') ||
                        text.includes('product option') ||
                        text.includes('category-optn') ||
                        text.includes('category option');
                });
            },

            couponHasProductRequirement(coupon) {
                const normalized = this.normalizeCoupon(coupon);
                const requiredQty = this.couponRequiredProductQuantity(normalized);
                const rules = this.getCouponProductRules(normalized);

                return requiredQty > 0 || rules.length > 0 || this.couponHasSelectedProductCondition(normalized);
            },

            couponEffectiveRequiredProductQty(coupon) {
                const normalized = this.normalizeCoupon(coupon);
                const directRequiredQty = Number(this.couponRequiredProductQuantity(normalized) || 0);
                const rules = this.getCouponProductRules(normalized);
                const ruleRequiredQty = rules.reduce((max, rule) => {
                    return Math.max(max, Number(rule?.required_qty || 0));
                }, 0);

                return Math.max(directRequiredQty, ruleRequiredQty, 0);
            },

            couponMatchedProductQty(coupon) {
                const normalized = this.normalizeCoupon(coupon);
                const requiredQty = this.couponRequiredProductQuantity(normalized);
                const rules = this.getCouponProductRules(normalized);
                const items = this.cartItemsForCouponQualification();

                if (rules.length) {
                    return items.reduce((sum, item) => {
                        const matchesAnyRule = rules.some(rule => this.couponRuleMatchesCartItem(rule, item));
                        return matchesAnyRule ? sum + Number(item?.qty || 1) : sum;
                    }, 0);
                }

                // If Product is enabled but selected product IDs are missing,
                // do not count all cart items. This prevents false qualification.
                if (this.couponHasSelectedProductCondition(normalized)) {
                    return 0;
                }

                // Quantity-only condition without Product selected: count all paid cart items.
                if (requiredQty > 0) {
                    return items.reduce((sum, item) => sum + Number(item?.qty || 1), 0);
                }

                return 0;
            },

            couponMatchedCategoryQty(coupon) {
                const normalized = this.normalizeCoupon(coupon);
                const categoryIds = this.getCouponSelectedCategoryIds(normalized);

                if (!categoryIds.length) {
                    return 0;
                }

                const items = this.cartItemsForCouponQualification();

                return items.reduce((sum, item) => {
                    const product = item?.product || {};
                    const cartCategoryId = this.normalizeCouponCategoryId(
                        item?.category_id ??
                        item?.product_category_id ??
                        product?.category_id ??
                        product?.category?.id ??
                        ''
                    );

                    return cartCategoryId && categoryIds.includes(cartCategoryId)
                        ? sum + Number(item?.qty || 1)
                        : sum;
                }, 0);
            },

            couponDiscountMultiplier(coupon) {
                const normalized = this.normalizeCoupon(coupon);

                // Product count is only a qualification rule.
                // Do not multiply the discount by QTY.
                const productRequirement = this.couponProductRequirementStatus(normalized);

                return productRequirement.valid ? 1 : 0;
            },

            couponProductRequirementStatus(coupon) {
                const normalized = this.normalizeCoupon(coupon);

                if (!this.couponHasProductRequirement(normalized)) {
                    return { valid: true, message: '' };
                }

                const requiredQty = Math.max(Number(this.couponEffectiveRequiredProductQty(normalized) || 0), 1);
                const rules = this.getCouponProductRules(normalized);

                if (this.couponHasCategoryConditionSource(normalized) && !this.getCouponSelectedCategoryIds(normalized).length && !this.getCouponSelectedProductIds(normalized).length) {
                    return {
                        valid: false,
                        message: 'This coupon has a Category condition, but no category ID was received from the coupon setup. Please pass the selected category ID, not only the category name.'
                    };
                }

                if (this.couponHasSelectedProductCondition(normalized) && !rules.length) {
                    return {
                        valid: false,
                        message: 'This coupon has a Product/Category condition, but no selected purchase product/category ID was found. Please pass the selected purchase condition ID from the coupon setup. Do not pass the free reward product ID as the purchase condition.'
                    };
                }

                /*
                |--------------------------------------------------------------------------
                | Multiple selected products/categories + Total Quantity
                |--------------------------------------------------------------------------
                | Example: selected products = Pork BBQ + Pork Dinuguan, Total Quantity = 2.
                | This must mean minimum 2 EACH, not total combined quantity of 2.
                |--------------------------------------------------------------------------
                */
                if (rules.length) {
                    const ruleStatuses = this.couponRuleRequirementStatuses(normalized);
                    const failedRules = ruleStatuses.filter(rule => !rule.valid);

                    if (!failedRules.length) {
                        return { valid: true, message: '' };
                    }

                    return {
                        valid: false,
                        message: `This coupon requires at least ${requiredQty} each for every selected product/category. ${failedRules.map(rule => `${rule.label}: needs ${rule.required_qty}, current ${rule.matched_qty}`).join('; ')}.`
                    };
                }

                const matchedQty = Number(this.couponMatchedProductQty(normalized) || 0);

                if (matchedQty >= requiredQty) {
                    return { valid: true, message: '' };
                }

                const selectedProductIds = this.getCouponSelectedProductIds(normalized).join(', ');
                const selectedCategoryIds = this.getCouponSelectedCategoryIds(normalized).join(', ');
                const requirementLabel = [
                    selectedProductIds ? `product ID(s): ${selectedProductIds}` : '',
                    selectedCategoryIds ? `category ID(s): ${selectedCategoryIds}` : ''
                ].filter(Boolean).join(' / ');

                return {
                    valid: false,
                    message: `This coupon requires at least ${requiredQty} count of selected ${requirementLabel || 'product/category ID(s): missing'}. Current matching count is ${matchedQty}.`
                };
            },
normalizeFreeProductFromCoupon(fp) {
                if (!fp) return null;

                const product = fp.product || fp;
                const productId =
                    fp.id ??
                    fp.product_id ??
                    fp.productId ??
                    fp.free_product_id ??
                    product.id ??
                    product.product_id ??
                    null;

                if (!productId) return null;

                return {
                    id: productId,
                    product_id: productId,
                    name: product.name ?? fp.name ?? fp.product_name ?? 'Free Product',
                    slug: product.slug ?? fp.slug ?? '',
                    category_id: product.category_id ?? fp.category_id ?? null,
                    is_misc: product.is_misc ?? fp.is_misc ?? 0,
                    paella_price: product.paella_price ?? fp.paella_price ?? 0,
                    photos: product.photos ?? fp.photos ?? []
                };
            },

            buildFreeProductOrderItem(coupon, freeProduct) {
                const normalizedCoupon = this.normalizeCoupon(coupon);
                const couponCode = this.couponCodeKey(normalizedCoupon) || String(normalizedCoupon.code || '').trim();

                return {
                    id: `free_${couponCode}_${freeProduct.product_id}`,
                    product_id: freeProduct.product_id,
                    qty: 1,
                    price: 0,
                    paella_price: 0,
                    is_free_product: true,
                    coupon_id: normalizedCoupon.id,
                    coupon_code: couponCode,
                    product: {
                        id: freeProduct.product_id,
                        name: freeProduct.name,
                        slug: freeProduct.slug ?? '',
                        category_id: freeProduct.category_id ?? null,
                        is_misc: freeProduct.is_misc ?? 0,
                        paella_price: freeProduct.paella_price ?? 0,
                        photos: freeProduct.photos ?? []
                    },
                    product_name: freeProduct.name
                };
            },

            addFreeProductsFromCoupon(coupon) {
                const normalizedCoupon = this.normalizeCoupon(coupon);
                const couponCode = this.couponCodeKey(normalizedCoupon) || String(normalizedCoupon.code || '').trim();
                const freeProducts = Array.isArray(normalizedCoupon.free_products)
                    ? normalizedCoupon.free_products
                    : Object.values(normalizedCoupon.free_products || {});

                if (!couponCode || !freeProducts.length) return;

                if (!Array.isArray(this.carts)) this.carts = [];
                if (!Array.isArray(this.orders)) this.orders = [];

                freeProducts
                    .map(fp => this.normalizeFreeProductFromCoupon(fp))
                    .filter(Boolean)
                    .forEach(freeProduct => {
                        const existsInCart = this.carts.some(item =>
                            item.is_free_product &&
                            String(item.product_id) === String(freeProduct.product_id) &&
                            this.couponCodeKey(item) === couponCode
                        );

                        if (!existsInCart) {
                            this.carts.push(this.buildFreeProductOrderItem(normalizedCoupon, freeProduct));
                        }

                        const existsInOrders = this.orders.some(order =>
                            order.is_free_product &&
                            String(order.product_id) === String(freeProduct.product_id) &&
                            this.couponCodeKey(order) === couponCode
                        );

                        if (!existsInOrders) {
                            this.orders.push(this.buildFreeProductOrderItem(normalizedCoupon, freeProduct));
                        }
                    });
            },

            removeFreeProductsByCoupon(couponCode) {
                this.carts = this.carts.filter(item =>
                    !(item.is_free_product && item.coupon_code === couponCode)
                )

                this.orders = this.orders.filter(item =>
                    !(item.is_free_product && item.coupon_code === couponCode)
                )
            },


                closeCouponModal() {
                    this.couponModal = false;
                },

            showCouponPopup(message, type = 'error') {
            this.couponMessage = message;
            this.couponMessageType = type;

            window.dispatchEvent(new CustomEvent('coupon-popup', {
                detail: {
                    message: message,
                    type: type
                }
            }));
        },

            showCouponError(message) {
                this.showCouponPopup(message, 'error');
            },

            showCouponSuccess(message) {
                this.showCouponPopup(message, 'success');
            },

                selectCoupon(coupon) {
                    this.selectedCoupon = this.normalizeCoupon(coupon);

                    // Do not put selected coupon code into manual coupon textbox
                    this.couponCode = '';
                },

                clearCouponSelection() {
                    this.selectedCoupon = null;
                    this.couponCode = '';
                },

                isFreeShippingCoupon(coupon) {
                    const reward = String(coupon?.reward ?? coupon?.coupon_type ?? '').trim().toLowerCase();
                    const discountType = String(coupon?.discount_type ?? '').trim().toLowerCase();

                    return !!(
                        this.toBoolean(coupon?.free_shipping) ||
                        reward === 'free-shipping-optn' ||
                        reward === 'free_shipping' ||
                        reward === 'free-shipping' ||
                        discountType === 'free_shipping' ||
                        discountType === 'free-shipping'
                    );
                },

            couponNoLocationValues() {
                return [
                    'all',
                    'all area',
                    'all areas',
                    'all_area',
                    'all_areas',
                    'all location',
                    'all locations',
                    'all city',
                    'all cities',
                    'all barangay',
                    'all barangays',
                    'any',
                    'any area',
                    'any areas',
                    'none',
                    'null',
                    'n/a',
                    'na',
                    'undefined',
                    '-',
                    '[]',
                    '{}'
                ];
            },

            couponHasLocationLimit(coupon) {
                const locations = this.getCouponLocations(coupon);
                const noRestrictionValues = this.couponNoLocationValues();

                if (locations.length) {
                    return !locations.every(loc => noRestrictionValues.includes(loc));
                }

                // Some coupon payloads do not expose the actual location list, but still
                // expose a location/city/barangay flag/mechanic. Treat that as location-
                // restricted and hide it until the backend sends a matching location value.
                return this.couponHasLocationConditionSource(coupon);
            },

            couponHasLocationConditionSource(coupon) {
                if (!coupon) return false;

                const locationKeys = [
                    'location',
                    'locations',
                    'location_id',
                    'location_ids',
                    'coupon_location',
                    'coupon_locations',
                    'coupon_location_id',
                    'coupon_location_ids',
                    'delivery_location',
                    'delivery_locations',
                    'delivery_location_id',
                    'delivery_location_ids',
                    'valid_location',
                    'valid_locations',
                    'valid_location_id',
                    'valid_location_ids',
                    'allowed_location',
                    'allowed_locations',
                    'allowed_location_id',
                    'allowed_location_ids',
                    'applicable_location',
                    'applicable_locations',
                    'applicable_location_id',
                    'applicable_location_ids',
                    'selected_location',
                    'selected_locations',
                    'selected_location_id',
                    'selected_location_ids',
                    'delivery_area',
                    'delivery_areas',
                    'covered_area',
                    'covered_areas',
                    'area',
                    'areas',
                    'city',
                    'cities',
                    'city_id',
                    'city_ids',
                    'province',
                    'provinces',
                    'province_id',
                    'province_ids',
                    'barangay',
                    'barangays',
                    'barangay_id',
                    'barangay_ids'
                ];

                const locationValuesAreOnlyNoRestriction = (value) => {
                    if (value === null || value === undefined) return true;

                    if (Array.isArray(value)) {
                        return value.every(item => locationValuesAreOnlyNoRestriction(item));
                    }

                    if (typeof value === 'object') {
                        return Object.values(value).every(item => locationValuesAreOnlyNoRestriction(item));
                    }

                    const text = String(value || '').trim();

                    if (!text || ['null', 'undefined', '[]', '{}'].includes(text.toLowerCase())) {
                        return true;
                    }

                    if ((text.startsWith('[') && text.endsWith(']')) || (text.startsWith('{') && text.endsWith('}'))) {
                        try {
                            return locationValuesAreOnlyNoRestriction(JSON.parse(text));
                        } catch (e) {}
                    }

                    return text
                        .split(/\r?\n|[|,;]/)
                        .map(item => this.normalizeText(item))
                        .filter(Boolean)
                        .every(item => this.couponNoLocationValues().includes(item));
                };

                const rawLocationValues = locationKeys
                    .map(key => coupon?.[key])
                    .filter(value => value !== null && value !== undefined && String(value).trim() !== '');

                const hasMeaningfulLocationField = rawLocationValues.some(value =>
                    this.couponValueHasMeaningfulData(value) && !locationValuesAreOnlyNoRestriction(value)
                );

                if (hasMeaningfulLocationField || this.getCouponLocations(coupon).length > 0) {
                    return true;
                }

                const locationFlagKeys = [
                    'has_location_condition',
                    'location_condition_enabled',
                    'location_required',
                    'requires_location',
                    'is_location_condition',
                    'delivery_location_required',
                    'delivery_location_enabled',
                    'city_required',
                    'requires_city',
                    'has_city_condition',
                    'barangay_required',
                    'requires_barangay',
                    'has_barangay_condition',
                    'province_required',
                    'requires_province',
                    'has_province_condition'
                ];

                if (locationFlagKeys.some(key => this.toBoolean(coupon?.[key]))) {
                    return true;
                }

                const mechanicValues = [
                    coupon?.coupon_mechanics,
                    coupon?.mechanics,
                    coupon?.mechanic,
                    coupon?.conditions,
                    coupon?.condition,
                    coupon?.rules,
                    coupon?.rule,
                    coupon?.purchase_combination,
                    coupon?.purchase_conditions,
                    coupon?.condition_combination,
                    coupon?.delivery_condition,
                    coupon?.delivery_conditions,
                    coupon?.availability_condition,
                    coupon?.availability_conditions,
                    coupon?.applies_to,
                    coupon?.applicable_to
                ];

                return mechanicValues
                    .flatMap(value => this.parseCouponArray(value))
                    .map(value => this.normalizeText(value))
                    .some(value =>
                        value === 'location' ||
                        value === 'locations' ||
                        value === 'city' ||
                        value === 'cities' ||
                        value === 'province' ||
                        value === 'provinces' ||
                        value === 'barangay' ||
                        value === 'barangays' ||
                        value.includes('delivery location') ||
                        value.includes('delivery address') ||
                        value.includes('location only') ||
                        value.includes('city only') ||
                        value.includes('barangay only') ||
                        value.includes('province only')
                    );
            },

                normalizeText(value) {
                    return String(value ?? '')
                        .replace(/[\[\]"']/g, '')
                        .replace(/\s+/g, ' ')
                        .trim()
                        .toLowerCase();
                },

                couponLocationKey(city, location, delivery = null) {
                const normalizedCity = this.normalizeText(
                    city ||
                    delivery?.city ||
                    delivery?.customer_city ||
                    delivery?.delivery_city ||
                    ''
                );

                const normalizedBarangay = this.normalizeText(
                    location ||
                    delivery?.location ||
                    delivery?.barangay ||
                    delivery?.customer_barangay ||
                    delivery?.delivery_barangay ||
                    ''
                );

                const normalizedProvince = this.normalizeText(
                    delivery?.province ||
                    delivery?.customer_province ||
                    delivery?.delivery_province ||
                    ''
                );

                const normalizedAddress = this.normalizeText(
                    delivery?.address ||
                    delivery?.customer_delivery_address ||
                    delivery?.customer_delivery_adress ||
                    delivery?.delivery_address ||
                    ''
                );

                // Main rule: same city = one free shipping discount only.
                if (normalizedCity) {
                    return `city:${normalizedCity}`;
                }

                // Fallback: same province/barangay/address = one discount only.
                const fallbackKey = [
                    normalizedProvince,
                    normalizedBarangay,
                    normalizedAddress
                ].filter(Boolean).join('|');

                if (fallbackKey) {
                    return `address:${fallbackKey}`;
                }

                return '';
            },

                normalizeLocationText(value) {
                    return this.normalizeText(value)
                        .replace(/\bcity of\b/g, '')
                        .replace(/\bmunicipality of\b/g, '')
                        .replace(/\bprovince of\b/g, '')
                        .replace(/\s+/g, ' ')
                        .trim();
                },

                locationAliasValues(value) {
                    const normalized = this.normalizeText(value);
                    const simplified = this.normalizeLocationText(value);

                    return [normalized, simplified]
                        .filter(Boolean)
                        .filter((item, index, arr) => arr.indexOf(item) === index);
                },

                getCouponLocations(coupon) {
                    const normalized = coupon || {};
                    const values = [];
                    const visited = new Set();
                    const locationLikeKeys = [
                        'location',
                        'locations',
                        'location_id',
                        'location_ids',
                        'coupon_location',
                        'coupon_locations',
                        'coupon_location_id',
                        'coupon_location_ids',
                        'delivery_location',
                        'delivery_locations',
                        'delivery_location_id',
                        'delivery_location_ids',
                        'valid_location',
                        'valid_locations',
                        'valid_location_id',
                        'valid_location_ids',
                        'allowed_location',
                        'allowed_locations',
                        'allowed_location_id',
                        'allowed_location_ids',
                        'applicable_location',
                        'applicable_locations',
                        'applicable_location_id',
                        'applicable_location_ids',
                        'selected_location',
                        'selected_locations',
                        'selected_location_id',
                        'selected_location_ids',
                        'delivery_area',
                        'delivery_areas',
                        'covered_area',
                        'covered_areas',
                        'area',
                        'areas',
                        'city',
                        'cities',
                        'city_id',
                        'city_ids',
                        'province',
                        'provinces',
                        'province_id',
                        'province_ids',
                        'barangay',
                        'barangays',
                        'barangay_id',
                        'barangay_ids'
                    ];

                    const isLocationKey = (key) => {
                        const normalizedKey = String(key || '')
                            .replace(/[A-Z]/g, letter => `_${letter.toLowerCase()}`)
                            .replace(/[^a-z0-9]+/g, '_')
                            .replace(/^_+|_+$/g, '')
                            .toLowerCase();

                        return locationLikeKeys.includes(normalizedKey) ||
                            normalizedKey.includes('location') ||
                            normalizedKey.includes('barangay') ||
                            normalizedKey === 'city' ||
                            normalizedKey === 'cities' ||
                            normalizedKey === 'province' ||
                            normalizedKey === 'provinces' ||
                            normalizedKey === 'area' ||
                            normalizedKey === 'areas';
                    };

                    const pushValue = (value) => {
                        const cleaned = this.normalizeText(value);

                        if (!cleaned || this.couponNoLocationValues().includes(cleaned)) {
                            return;
                        }

                        if (!values.includes(cleaned)) {
                            values.push(cleaned);
                        }
                    };

                    const scan = (source, forceAsLocation = false, depth = 0) => {
                        if (source === null || source === undefined || depth > 8) return;

                        if (typeof source === 'string') {
                            const text = source.trim();

                            if (!text || text === 'null' || text === 'undefined' || text === '[]' || text === '{}') {
                                return;
                            }

                            if ((text.startsWith('[') && text.endsWith(']')) || (text.startsWith('{') && text.endsWith('}'))) {
                                try {
                                    scan(JSON.parse(text), forceAsLocation, depth + 1);
                                    return;
                                } catch (e) {}
                            }

                            if (forceAsLocation) {
                                text
                                    .split(/\r?\n|[|,;]/)
                                    .map(item => item.trim())
                                    .filter(Boolean)
                                    .forEach(pushValue);
                            }

                            return;
                        }

                        if (typeof source !== 'object') {
                            if (forceAsLocation) pushValue(source);
                            return;
                        }

                        const visitKey = JSON.stringify(source);
                        if (visited.has(visitKey)) return;
                        visited.add(visitKey);

                        if (Array.isArray(source)) {
                            source.forEach(item => scan(item, forceAsLocation, depth + 1));
                            return;
                        }

                        const objectMarksLocation = Object.entries(source).some(([key, value]) => {
                            const markerKey = String(key || '')
                                .replace(/[A-Z]/g, letter => `_${letter.toLowerCase()}`)
                                .replace(/[^a-z0-9]+/g, '_')
                                .replace(/^_+|_+$/g, '')
                                .toLowerCase();
                            const markerValue = this.normalizeText(value);

                            return [
                                'type',
                                'condition_type',
                                'field',
                                'field_name',
                                'key',
                                'name',
                                'mechanic',
                                'mechanics'
                            ].includes(markerKey) &&
                                (
                                    markerValue === 'location' ||
                                    markerValue === 'locations' ||
                                    markerValue === 'city' ||
                                    markerValue === 'cities' ||
                                    markerValue === 'province' ||
                                    markerValue === 'provinces' ||
                                    markerValue === 'barangay' ||
                                    markerValue === 'barangays' ||
                                    markerValue.includes('delivery location') ||
                                    markerValue.includes('delivery address')
                                );
                        });

                        if (objectMarksLocation && !forceAsLocation) {
                            Object.entries(source).forEach(([key, value]) => {
                                const markerKey = String(key || '')
                                    .replace(/[A-Z]/g, letter => `_${letter.toLowerCase()}`)
                                    .replace(/[^a-z0-9]+/g, '_')
                                    .replace(/^_+|_+$/g, '')
                                    .toLowerCase();

                                if (![
                                    'type',
                                    'condition_type',
                                    'field',
                                    'field_name',
                                    'key',
                                    'name',
                                    'mechanic',
                                    'mechanics'
                                ].includes(markerKey)) {
                                    scan(value, true, depth + 1);
                                }
                            });
                        }

                        if (forceAsLocation) {
                            [
                                source.name,
                                source.location,
                                source.location_name,
                                source.locationName,
                                source.city,
                                source.city_name,
                                source.cityName,
                                source.province,
                                source.province_name,
                                source.provinceName,
                                source.barangay,
                                source.barangay_name,
                                source.barangayName,
                                source.area,
                                source.area_name,
                                source.areaName,
                                source.label,
                                source.value,
                                source.text
                            ].forEach(pushValue);
                        }

                        Object.entries(source).forEach(([key, value]) => {
                            const keyIsLocation = isLocationKey(key);
                            if (keyIsLocation || forceAsLocation) {
                                scan(value, true, depth + 1);
                            }
                        });
                    };

                    locationLikeKeys.forEach(key => scan(normalized?.[key], true));

                    // Scan nested coupon payloads where the backend may store mechanics.
                    [
                        normalized?.coupon_mechanics,
                        normalized?.mechanics,
                        normalized?.mechanic,
                        normalized?.conditions,
                        normalized?.condition,
                        normalized?.rules,
                        normalized?.rule,
                        normalized?.delivery_condition,
                        normalized?.delivery_conditions,
                        normalized?.availability_condition,
                        normalized?.availability_conditions,
                        normalized?.applicable_to,
                        normalized?.applies_to,
                        normalized?.pivot
                    ].forEach(source => scan(source, false));

                    return values;
                },

                couponMatchesLocation(coupon, city, location, delivery = null) {
                    const allowed = this.getCouponLocations(coupon);

                    if (!allowed.length) return false;

                    const noRestrictionValues = this.couponNoLocationValues();

                    if (allowed.every(loc => noRestrictionValues.includes(loc))) {
                        return true;
                    }

                    const target = delivery || {};
                    const targetValues = [
                        city,
                        location,
                        target.city,
                        target.location,
                        target.barangay,
                        target.province,
                        target.address,
                        target.delivery_address,
                        target.customer_delivery_address,
                        [city || target.city, target.province].filter(Boolean).join(' '),
                        [location || target.location || target.barangay, city || target.city].filter(Boolean).join(' '),
                        [location || target.location || target.barangay, city || target.city, target.province].filter(Boolean).join(' ')
                    ]
                        .flatMap(value => this.locationAliasValues(value))
                        .filter(Boolean)
                        .filter((item, index, arr) => arr.indexOf(item) === index);

                    if (!targetValues.length) return false;

                    return allowed.some(allowedLocation => {
                        const allowedAliases = this.locationAliasValues(allowedLocation);

                        return allowedAliases.some(allowedAlias => {
                            if (!allowedAlias || noRestrictionValues.includes(allowedAlias)) {
                                return noRestrictionValues.includes(allowedAlias);
                            }

                            return targetValues.some(targetValue => {
                                if (!targetValue) return false;

                                if (allowedAlias === targetValue) return true;

                                // Match values like "CITY OF PASIG, NCR - SECOND DISTRICT"
                                // against selected city "CITY OF PASIG", without matching tiny fragments.
                                if (targetValue.length >= 4 && allowedAlias.includes(targetValue)) return true;
                                if (allowedAlias.length >= 4 && targetValue.includes(allowedAlias)) return true;

                                return false;
                            });
                        });
                    });
                },
                couponMatchesSelectedLocation(coupon) {
                    return this.couponLocationApplies(coupon);
                },

                couponLocationApplies(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    if (!this.couponHasLocationLimit(normalized)) {
                        return true;
                    }

                    if (this.method !== 'delivery') {
                        return false;
                    }

                    const allowed = this.getCouponLocations(normalized);

                    // If the coupon has a location mechanic but the backend did not pass
                    // its allowed city/barangay list, do not show/apply it as a global coupon.
                    if (!allowed.length) {
                        return false;
                    }

                    const targets = this.getSelectedCouponTargets();

                    if (!targets.length) {
                        return false;
                    }

                    return targets.some(t =>
                        this.couponMatchesLocation(normalized, t.city, t.location, t)
                    );
                },

                couponLocationRequirementStatus(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    // Location = All Area is not a delivery restriction.
                    // It must work for pickup and delivery.
                    if (!this.couponHasLocationLimit(normalized) && !this.isFreeShippingCoupon(normalized) && !this.hasLocationDiscount(normalized)) {
                        const shippingMethod = this.normalizeText(normalized.shipping_method ?? normalized.delivery_method ?? '');

                        if (!shippingMethod.includes('delivery') && !shippingMethod.includes('door')) {
                            return { valid: true, message: '' };
                        }
                    }

                    if (!this.couponHasDeliveryLocationRule(normalized)) {
                        return { valid: true, message: '' };
                    }

                    if (this.method !== 'delivery') {
                        return {
                            valid: false,
                            message: 'This coupon is available for delivery addresses only.'
                        };
                    }

                    const targets = this.getSelectedCouponTargets();

                    if (!targets.length) {
                        return {
                            valid: false,
                            message: 'Please select a delivery location first.'
                        };
                    }

                    if (this.couponHasLocationLimit(normalized) && !this.couponLocationApplies(normalized)) {
                        return {
                            valid: false,
                            message: 'This coupon is not valid for the selected delivery address.'
                        };
                    }

                    return { valid: true, message: '' };
                },

                hasLocationDiscount(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    return Number(normalized.location_discount_amount || 0) > 0 &&
                        String(normalized.location_discount_type || '').trim() !== '';
                },

                shouldShowLocationDiscount(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    if (!this.hasLocationDiscount(normalized)) {
                        return false;
                    }

                    // Location discount should only appear if coupon has location restriction
                    if (!this.couponHasLocationLimit(normalized)) {
                        return false;
                    }

                    return this.couponMatchesSelectedLocation(normalized);
                },

                locationDiscountLabel(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    if (!this.shouldShowLocationDiscount(normalized)) {
                        return '';
                    }

                    if (
                        normalized.location_discount_type === 'percentage' ||
                        normalized.location_discount_type === 'percent'
                    ) {
                        return `${Number(normalized.location_discount_amount || 0)}% location discount`;
                    }

                    return `${this.formatMoney(normalized.location_discount_amount || 0)} location discount`;
                },

                getSelectedCouponTargets() {
                    if (this.method !== 'delivery') return [];

                    if (this.allowMultiple) {
                        return (this.deliveries || [])
                            .filter(d => (d?.orders || []).length > 0 || this.normalizeText(d?.city) || this.normalizeText(d?.location))
                            .map(d => ({
                                province: d?.province ?? '',
                                city: d?.city ?? '',
                                location: d?.location ?? d?.barangay ?? '',
                                barangay: d?.barangay ?? d?.location ?? '',
                                address: d?.address ?? d?.delivery_address ?? ''
                            }))
                            .filter(t =>
                                this.normalizeText(t.province) ||
                                this.normalizeText(t.city) ||
                                this.normalizeText(t.location) ||
                                this.normalizeText(t.address)
                            );
                    }

                    return [{
                        province: this.province ?? '',
                        city: this.city ?? '',
                        location: this.location ?? this.barangay ?? '',
                        barangay: this.barangay ?? this.location ?? '',
                        address: this.delivery_address ?? this.delivery_address_street ?? ''
                    }].filter(t =>
                        this.normalizeText(t.province) ||
                        this.normalizeText(t.city) ||
                        this.normalizeText(t.location) ||
                        this.normalizeText(t.address)
                    );
                },

                couponLocationValidationIsPending(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    if (!this.couponHasDeliveryLocationRule(normalized)) {
                        return false;
                    }

                    if (this.method !== 'delivery') {
                        return false;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Preserve coupon while customer is still switching to/editing multiple address
                    |--------------------------------------------------------------------------
                    | When a coupon was already applied in single delivery and the customer ticks
                    | Multiple Address, the multi delivery rows can be empty/incomplete for a moment.
                    | Do not remove the coupon in that pending state. Remove it only after the
                    | delivery address mechanics are already available and the coupon still fails.
                    |--------------------------------------------------------------------------
                    */
                    if (this.allowMultiple) {
                        const deliveries = this.deliveries || [];
                        const activeDeliveries = deliveries.filter(delivery =>
                            (delivery?.orders || []).length > 0
                        );

                        const hasAnyAddressInput = deliveries.some(delivery =>
                            this.normalizeText(delivery?.province) ||
                            this.normalizeText(delivery?.city) ||
                            this.normalizeText(delivery?.location) ||
                            this.normalizeText(delivery?.barangay) ||
                            this.normalizeText(delivery?.address)
                        );

                        if (!activeDeliveries.length && !hasAnyAddressInput) {
                            return true;
                        }

                        const hasIncompleteActiveDelivery = activeDeliveries.some(delivery =>
                            !this.normalizeText(delivery?.province) ||
                            !this.normalizeText(delivery?.city)
                        );

                        if (hasIncompleteActiveDelivery) {
                            return true;
                        }
                    }

                    return !this.getSelectedCouponTargets().length;
                },

                couponShouldRemoveWhenMechanicsNotMet(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    if (!this.couponIsActive(normalized)) {
                        return true;
                    }

                    const productRequirement = this.couponProductRequirementStatus(normalized);

                    if (!productRequirement.valid) {
                        return true;
                    }

                    const locationRequirement = this.couponLocationRequirementStatus(normalized);

                    if (!locationRequirement.valid) {
                        return !this.couponLocationValidationIsPending(normalized);
                    }

                    return false;
                },

                couponShouldStayAppliedWhileEditingAddress(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    if (!this.couponIsActive(normalized)) {
                        return false;
                    }

                    const productRequirement = this.couponProductRequirementStatus(normalized);

                    if (!productRequirement.valid) {
                        return false;
                    }

                    return this.couponLocationValidationIsPending(normalized);
                },

                removeInvalidLocationCoupons(recompute = true) {
                    const removedCodes = [];

                    this.coupons = (this.coupons || [])
                        .map(coupon => this.normalizeCoupon(coupon))
                        .filter(coupon => {
                            const code = this.couponCodeKey(coupon);

                            if (this.couponShouldRemoveWhenMechanicsNotMet(coupon)) {
                                if (code) removedCodes.push(code);
                                return false;
                            }

                            return true;
                        });

                    removedCodes.forEach(code => this.removeFreeProductsByCoupon(code));

                    if (removedCodes.length) {
                        this.autoAppliedCoupons = (this.autoAppliedCoupons || []).filter(coupon =>
                            !removedCodes.includes(this.couponCodeKey(coupon))
                        );

                        this.deliveryCouponPopupShownCodes = (this.deliveryCouponPopupShownCodes || []).filter(code =>
                            !removedCodes.includes(String(code || '').trim().toUpperCase())
                        );

                        this.autoCouponChooserShownOnce = false;

                        if (this.selectedCoupon && removedCodes.includes(this.couponCodeKey(this.selectedCoupon))) {
                            this.selectedCoupon = null;
                        }

                        if (this.selectedAutoCoupon && removedCodes.includes(this.couponCodeKey(this.selectedAutoCoupon))) {
                            this.selectedAutoCoupon = null;
                            this.selectedAutoCouponId = '';
                        }
                    }

                    this.order_amount = this.cartSubtotal();

                    if (recompute) {
                        this.recomputeCouponTotals();
                    }
                },

                shouldAutoApplyCoupon(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    if (!this.couponIsActive(normalized)) {
                        return false;
                    }

                    if (this.couponUsageConsumed(coupon)) {
                        return false;
                    }

                    const isAuto =
                        normalized.auto_applied === true ||
                        normalized.auto_applied === 1 ||
                        normalized.auto_applied === '1' ||
                        String(normalized.activation_type || '').toLowerCase() === 'auto';

                    if (!isAuto) return false;

                    const productRequirement = this.couponProductRequirementStatus(normalized);

                    if (!productRequirement.valid) {
                        return false;
                    }

                    const locationRequirement = this.couponLocationRequirementStatus(normalized);

                    if (!locationRequirement.valid) {
                        return false;
                    }

                    const hasLocationLimit = this.couponHasLocationLimit(normalized);

                    if (!hasLocationLimit) {
                        return true;
                    }

                    return this.couponLocationApplies(normalized);
                },

                couponHasDeliveryLocationRule(coupon) {
                    const normalized = this.normalizeCoupon(coupon);
                    const shippingMethod = this.normalizeText(normalized.shipping_method ?? normalized.delivery_method ?? '');

                    return this.couponHasLocationLimit(normalized) ||
                        this.couponHasLocationConditionSource(normalized) ||
                        this.hasLocationDiscount(normalized) ||
                        this.isFreeShippingCoupon(normalized) ||
                        shippingMethod.includes('delivery') ||
                        shippingMethod.includes('door') ||
                        shippingMethod.includes('location');
                },

                couponSelectedLocationIsHit(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    if (!this.couponHasDeliveryLocationRule(normalized)) {
                        return true;
                    }

                    // Location-only coupons must not appear on pickup, in the popup,
                    // or in coupon selection until the selected delivery location matches.
                    if (this.method !== 'delivery') {
                        return false;
                    }

                    const targets = this.getSelectedCouponTargets();

                    if (!targets.length) {
                        return false;
                    }

                    // If a coupon has saved location mechanics, the selected city/barangay
                    // must hit that list. Do not show it just because delivery was chosen.
                    if (this.couponHasLocationLimit(normalized)) {
                        return this.couponLocationApplies(normalized);
                    }

                    // Delivery-only/free-shipping coupons without a location list can show
                    // once a delivery target exists.
                    return true;
                },

                shouldShowAutoCouponInList(coupon) {
                const normalized = this.normalizeCoupon(coupon);

                if (!this.couponIsActive(normalized)) {
                    return false;
                }

                return this.couponSelectedLocationIsHit(normalized);
            },
            shouldShowCouponInList(coupon) {
                const normalized = this.normalizeCoupon(coupon);

                if (!this.couponIsActive(normalized)) {
                    return false;
                }

                return this.couponSelectedLocationIsHit(normalized);
            },

                        
                    getAutoCouponUnavailableReason(coupon) {
                    const normalized = this.normalizeCoupon(coupon);

                    if (!this.couponIsActive(normalized)) {
                        return this.couponInactiveMessage(normalized);
                    }

                    const couponUsage = this.couponUsageStatus(coupon);

                    if (couponUsage.consumed) {
                        return couponUsage.message;
                    }

                    const isAuto =
                        normalized.auto_applied === true ||
                        String(normalized.activation_type || '').toLowerCase() === 'auto';

                    if (!isAuto) {
                        return 'This is not an auto coupon.';
                    }

                    const productRequirement = this.couponProductRequirementStatus(normalized);

                    if (!productRequirement.valid) {
                        return productRequirement.message;
                    }

                    const locationRequirement = this.couponLocationRequirementStatus(normalized);

                    if (!locationRequirement.valid) {
                        return locationRequirement.message;
                    }

                    return '';
                },

                debugCouponQualificationRows() {
                    return (this.autoCouponChoices || []).map(coupon => {
                        const normalized = this.normalizeCoupon(coupon);
                        const productStatus = this.couponProductRequirementStatus(normalized);
                        const locationStatus = this.couponLocationRequirementStatus(normalized);

                        return {
                            code: this.couponCodeKey(normalized),
                            auto_available: coupon.auto_available,
                            reason: coupon.unavailable_reason || '',
                            product_valid: productStatus.valid,
                            product_message: productStatus.message || '',
                            location_valid: locationStatus.valid,
                            location_message: locationStatus.message || '',
                            required_qty: Number(this.couponEffectiveRequiredProductQty(normalized) || 0),
                            matched_qty: Number(this.couponMatchedProductQty(normalized) || 0),
                            purchase_product_ids: this.getCouponSelectedProductIds(normalized).join(','),
                            free_product_ids: this.couponFreeProductIds(normalized).join(','),
                            purchase_category_ids: this.getCouponSelectedCategoryIds(normalized).join(',')
                        };
                    });
                },

                getShippingDiscountAmountFromCoupon(coupon, deliveryFee) {
                    const normalized = this.normalizeCoupon(coupon);
                    const fee = Number(deliveryFee || 0);

                    if (!this.isFreeShippingCoupon(normalized) || this.method !== 'delivery' || fee <= 0) {
                        return 0;
                    }

                    const type = String(normalized.shipping_discount_type || '').trim().toLowerCase();
                    const amount = Number(normalized.shipping_discount_amount || normalized.discount || 0);

                    // Admin Free Shipping coupon supports:
                    // Full    = discount full delivery fee
                    // Partial = discount only the configured shipping fee discount amount
                    if (
                        type === 'partial' ||
                        type === 'fixed' ||
                        type === 'amount' ||
                        type === 'discount-amount' ||
                        type === 'discount_amount' ||
                        (amount > 0 && type !== 'full')
                    ) {
                        return Math.min(amount, fee);
                    }

                    return fee;
                },

                getCouponDiscount(coupon) {
                const subtotal = this.cartSubtotal();

                if (!coupon) return 0;

                const normalized = this.normalizeCoupon(coupon);

                if (!this.couponIsActive(normalized)) {
                    return 0;
                }

                const productRequirement = this.couponProductRequirementStatus(normalized);

                // If product count drops after coupon was selected,
                // keep the coupon line but remove the discount until the rule is satisfied again.
                if (!productRequirement.valid) {
                    return 0;
                }

                // A location setting is an eligibility rule for ALL reward types.
                // It must not replace the coupon reward. Once the selected city/barangay
                // matches, amount, percentage, free-shipping, and free-product coupons continue normally.
                if (this.couponHasLocationLimit(normalized) && !this.couponLocationApplies(normalized)) {
                    return 0;
                }

                if (this.isFreeShippingCoupon(normalized)) {
                    if (this.method !== 'delivery') return 0;

                    if (this.allowMultiple) {
                    const discountedLocationKeys = new Set();

                    return (this.deliveries || []).reduce((sum, delivery) => {
                        if (!this.couponMatchesLocation(normalized, delivery.city, delivery.location, delivery)) {
                                return sum;
                            }

                        const locationKey = this.couponLocationKey(delivery.city, delivery.location, delivery);

                        // Same city/location can only be counted once.
                        if (locationKey && discountedLocationKeys.has(locationKey)) {
                            return sum;
                        }

                        if (locationKey) {
                            discountedLocationKeys.add(locationKey);
                        }

                        return sum + this.getShippingDiscountAmountFromCoupon(
                            normalized,
                            Number(delivery.delivery_fee || 0)
                        );
                    }, 0);
                }

                    return this.couponMatchesLocation(normalized, this.city, this.location, { province: this.province, city: this.city, location: this.location, address: this.delivery_address })
                        ? this.getShippingDiscountAmountFromCoupon(normalized, Number(this.deliveryFee || 0))
                        : 0;
                }

                if (normalized.reward === 'discount-amount-optn' || normalized.discount_type === 'amount') {
                    return Math.min(Number(normalized.discount || 0), subtotal);
                }

                if (normalized.reward === 'discount-percentage-optn' || normalized.discount_type === 'percent') {
                    return Math.min(
                        subtotal * (Number(normalized.discount || 0) / 100),
                        subtotal
                    );
                }

                // Backward compatibility: some old coupons store their reward in the
                // location-discount fields only. Use it only when no normal reward matched.
                if (this.hasLocationDiscount(normalized) && this.shouldShowLocationDiscount(normalized)) {
                    if (
                        normalized.location_discount_type === 'percentage' ||
                        normalized.location_discount_type === 'percent'
                    ) {
                        return Math.min(
                            subtotal * (Number(normalized.location_discount_amount || 0) / 100),
                            subtotal
                        );
                    }

                    return Math.min(Number(normalized.location_discount_amount || 0), subtotal);
                }

                return 0;
            },

                couponDiscountLabel(coupon) {
                    const amount = this.getCouponDiscount(coupon);

                    if (this.isFreeShippingCoupon(coupon)) {
                        const normalized = this.normalizeCoupon(coupon);
                        const type = String(normalized.shipping_discount_type || '').trim().toLowerCase();

                        return type === 'partial' || Number(normalized.shipping_discount_amount || 0) > 0
                            ? `- ${this.formatMoney(amount)} (Shipping Discount)`
                            : `- ${this.formatMoney(amount)} (Free Shipping)`;
                    }

                    return `- ${this.formatMoney(amount)}`;
                },
                

                applyAutoCoupons() {
                if (this.hasWholeLechonInCart()) {
                    const autoCodes = (this.autoCouponsSource || [])
                        .map(c => this.couponCodeKey(this.normalizeCoupon(c)))
                        .filter(Boolean);

                    this.coupons = (this.coupons || [])
                        .map(c => this.normalizeCoupon(c))
                        .filter(c => !c.auto_applied);

                    this.autoAppliedCoupons = [];
                    this.selectedAutoCoupon = null;
                    this.selectedAutoCouponId = '';
                    this.autoCouponChoices = [];
                    this.showAutoCouponChooser = false;

                    this.carts = (this.carts || []).filter(item => {
                        const itemCouponCode = this.couponCodeKey(item);
                        return !(item.is_free_product && autoCodes.includes(itemCouponCode));
                    });

                    this.orders = (this.orders || []).filter(item => {
                        const itemCouponCode = this.couponCodeKey(item);
                        return !(item.is_free_product && autoCodes.includes(itemCouponCode));
                    });

                    this.order_amount = this.cartSubtotal();
                    this.recomputeCouponTotals();
                    return;
                }

                const previousSelectedAutoCouponId = this.selectedAutoCouponId;
                const previousSelectedAutoCoupon = this.selectedAutoCoupon
                    ? this.normalizeCoupon(this.selectedAutoCoupon)
                    : null;

                const allAutoCoupons = (this.autoCouponsSource || [])
                    .map(c => ({
                        ...this.normalizeCoupon(c),
                        auto_applied: true
                    }));

                const autoCodes = allAutoCoupons
                    .map(c => this.couponCodeKey(c))
                    .filter(Boolean);

                const normalizedCurrentCoupons = (this.coupons || [])
                    .map(c => this.normalizeCoupon(c));

                const removedMechanicCodes = normalizedCurrentCoupons
                    .filter(c => this.couponShouldRemoveWhenMechanicsNotMet(c))
                    .map(c => this.couponCodeKey(c))
                    .filter(Boolean);

                const currentCoupons = normalizedCurrentCoupons
                    .filter(c => !this.couponShouldRemoveWhenMechanicsNotMet(c));

                const appliedAutoCouponsToKeep = currentCoupons.filter(c =>
                    c.auto_applied &&
                    (
                        this.shouldAutoApplyCoupon(c) ||
                        this.couponShouldStayAppliedWhileEditingAddress(c)
                    )
                );
                const appliedAutoCodesToKeep = appliedAutoCouponsToKeep
                    .map(c => this.couponCodeKey(c))
                    .filter(Boolean);

                const manuallySelectedAutoCodes = currentCoupons
                    .filter(c => autoCodes.includes(this.couponCodeKey(c)) && !c.auto_applied)
                    .map(c => this.couponCodeKey(c));

                const shouldRemoveAutoFreeProduct = (item) => {
                    if (!item?.is_free_product) return false;

                    const itemCouponCode = this.couponCodeKey(item);

                    // Do not remove free products from already-applied coupons while
                    // multiple-address selection is still being edited. Remove only when
                    // product/location mechanics are actually not met.
                    return removedMechanicCodes.includes(itemCouponCode) ||
                        (
                            autoCodes.includes(itemCouponCode) &&
                            !manuallySelectedAutoCodes.includes(itemCouponCode) &&
                            !appliedAutoCodesToKeep.includes(itemCouponCode)
                        );
                };

                // Keep already-applied coupons when switching to Multiple Address.
                // Remove them only when the saved coupon mechanics are actually not met.
                this.coupons = currentCoupons;
                this.autoAppliedCoupons = appliedAutoCouponsToKeep;

                this.carts = (this.carts || []).filter(item => !shouldRemoveAutoFreeProduct(item));
                this.orders = (this.orders || []).filter(item => !shouldRemoveAutoFreeProduct(item));

                if (
                    previousSelectedAutoCoupon &&
                    (
                        this.shouldAutoApplyCoupon(previousSelectedAutoCoupon) ||
                        this.couponShouldStayAppliedWhileEditingAddress(previousSelectedAutoCoupon)
                    )
                ) {
                    this.selectedAutoCoupon = previousSelectedAutoCoupon;
                    this.selectedAutoCouponId = previousSelectedAutoCoupon.id || previousSelectedAutoCouponId || '';
                } else if (this.autoAppliedCoupons.length) {
                    this.selectedAutoCoupon = this.autoAppliedCoupons[0];
                    this.selectedAutoCouponId = this.autoAppliedCoupons[0].id || '';
                } else {
                    this.selectedAutoCoupon = null;
                    this.selectedAutoCouponId = '';
                }

                const visibleAutos = allAutoCoupons.filter(c =>
                    this.couponIsActive(c) &&
                    !this.couponUsageConsumed(c) &&
                    this.shouldShowAutoCouponInList(c)
                );

                const mappedAutoChoices = visibleAutos.map(coupon => {
                    const available = this.shouldAutoApplyCoupon(coupon);

                    return {
                        ...coupon,
                        auto_available: available,
                        unavailable_reason: available
                            ? ''
                            : this.getAutoCouponUnavailableReason(coupon)
                    };
                });

                // Hide coupons that are not qualified yet and do not show already-applied
                // coupons again in the chooser.
                this.autoCouponChoices = mappedAutoChoices.filter(c =>
                    c.auto_available && !this.couponAlreadyApplied(c)
                );

                if (window.APP_DEBUG && typeof console !== 'undefined') {
                    console.table(this.debugCouponQualificationRows());
                }

                const availableAutos = this.autoCouponChoices;

                if (previousSelectedAutoCouponId) {
                    const alreadyAppliedSelected = this.coupons.some(c =>
                        String(c.id) === String(previousSelectedAutoCouponId) ||
                        this.couponCodeKey(c) === this.couponCodeKey(previousSelectedAutoCoupon)
                    );

                    if (alreadyAppliedSelected) {
                        this.showAutoCouponChooser = false;
                        this.order_amount = this.cartSubtotal();
                        this.recomputeCouponTotals();
                        return;
                    }

                    const chosen = availableAutos.find(c =>
                        String(c.id) === String(previousSelectedAutoCouponId)
                    );

                    if (chosen) {
                        this.applyChosenAutoCoupon(chosen);
                        this.showAutoCouponChooser = false;
                        return;
                    }

                    this.selectedAutoCoupon = null;
                    this.selectedAutoCouponId = '';
                }

                if (this.autoCouponChoices.length === 0) {
                    this.showAutoCouponChooser = false;
                    this.order_amount = this.cartSubtotal();
                    this.recomputeCouponTotals();
                    return;
                }

                if (!this.autoCouponChooserShownOnce) {
                    this.showAutoCouponChooser = true;
                    this.autoCouponChooserShownOnce = true;
                }

                this.order_amount = this.cartSubtotal();
                this.recomputeCouponTotals();
            },

            confirmCouponSelection() {
    if (this.blockCouponIfWholeLechon()) {
        return;
    }

    if (!this.selectedCoupon) {
        this.showCouponError('Please select a coupon first.');
        return;
    }

    const normalized = this.normalizeCoupon(this.selectedCoupon);

    if (!this.couponIsActive(normalized)) {
        this.showCouponError(this.couponInactiveMessage(normalized));
        this.selectedCoupon = null;
        return;
    }

    const couponUsage = this.couponUsageStatus(normalized);

    if (couponUsage.consumed) {
        this.showCouponError(couponUsage.message);
        return;
    }

    const productRequirement = this.couponProductRequirementStatus(normalized);

    if (!productRequirement.valid) {
        this.showCouponError(productRequirement.message);
        return;
    }

    const locationRequirement = this.couponLocationRequirementStatus(normalized);

    if (!locationRequirement.valid) {
        this.showCouponError(locationRequirement.message);
        return;
    }

    const alreadyApplied = this.coupons.find(c =>
        String(c.code || '').trim().toUpperCase() === String(normalized.code || '').trim().toUpperCase()
    );

    if (alreadyApplied) {
        this.showCouponError('Coupon already applied.');
        return;
    }

    const combinationStatus = this.couponCombinationStatus(normalized);

    if (!combinationStatus.valid) {
        this.showCouponError(combinationStatus.message);
        return;
    }

    this.coupons.push(normalized);
    this.addFreeProductsFromCoupon(normalized);
    this.keepSelectedCouponStable(normalized);
    this.couponCode = '';
    this.order_amount = this.cartSubtotal();
    this.recomputeCouponTotals();

    this.showCouponSuccess('Coupon applied successfully.');
    this.closeCouponModal();
},

        applyCouponCode() {
            
            this.couponMessage = '';
            this.couponMessageType = '';
            if (this.blockCouponIfWholeLechon()) {
                this.couponCode = '';
                return;
            }

            const code = String(this.couponCode || '').trim().toUpperCase();

            if (!code) {
                this.showCouponError('Please enter a coupon code.');
                return;
            }

            const found = this.couponChoiceSources().find(c =>
                String(c.code ?? c.coupon_code ?? '').trim().toUpperCase() === code
            );

            if (!found) {
                this.showCouponError('Invalid coupon code.');
                return;
            }

            const normalized = this.normalizeCoupon({
                ...found,
                auto_applied: false
            });

            if (!this.couponIsActive(normalized)) {
                this.showCouponError(this.couponInactiveMessage(normalized));
                this.couponCode = '';
                return;
            }

            const couponUsage = this.couponUsageStatus(normalized);

            if (couponUsage.consumed) {
                this.showCouponError(couponUsage.message);
                this.couponCode = '';
                return;
            }

            const productRequirement = this.couponProductRequirementStatus(normalized);

            if (!productRequirement.valid) {
                this.showCouponError(productRequirement.message);
                this.couponCode = '';
                return;
            }

            const locationRequirement = this.couponLocationRequirementStatus(normalized);

            if (!locationRequirement.valid) {
                this.showCouponError(locationRequirement.message);
                this.couponCode = '';
                return;
            }

            const alreadyApplied = this.coupons.find(c =>
                String(c.code || '').trim().toUpperCase() === code
            );

            if (alreadyApplied) {
                if (alreadyApplied.auto_applied) {
                    this.showCouponError('This coupon is already auto-applied.');
                } else {
                    this.showCouponError('Coupon already applied.');
                }
                return;
            }

            const combinationStatus = this.couponCombinationStatus(normalized);

            if (!combinationStatus.valid) {
                this.showCouponError(combinationStatus.message);
                return;
            }

            this.coupons.push(normalized);
            this.addFreeProductsFromCoupon(normalized);
            this.keepSelectedCouponStable(normalized);
            this.couponCode = '';
            this.order_amount = this.cartSubtotal();
            this.recomputeCouponTotals();

            this.showCouponSuccess('Coupon applied successfully.');
        },

        removeCoupon(index) {
            const removed = this.normalizeCoupon(this.coupons[index]);

            this.coupons.splice(index, 1);

            this.autoAppliedCoupons = this.autoAppliedCoupons.filter(c =>
                String(c.code || '').trim().toUpperCase() !== String(removed?.code || '').trim().toUpperCase()
            );

            this.removeFreeProductsByCoupon(removed?.code);

            // IMPORTANT:
            // If removed coupon is the selected auto coupon,
            // clear it so it can appear again in the coupon list.
            if (
                this.selectedAutoCoupon &&
                (
                    String(this.selectedAutoCoupon.id) === String(removed?.id) ||
                    String(this.selectedAutoCoupon.code || '').trim().toUpperCase() === String(removed?.code || '').trim().toUpperCase()
                )
            ) {
                this.selectedAutoCoupon = null;
                this.selectedAutoCouponId = '';
            }

            // Clear selected regular coupon too if same coupon was removed
            if (
                this.selectedCoupon &&
                (
                    String(this.selectedCoupon.id) === String(removed?.id) ||
                    String(this.selectedCoupon.code || '').trim().toUpperCase() === String(removed?.code || '').trim().toUpperCase()
                )
            ) {
                this.selectedCoupon = null;
            }

            this.order_amount = this.cartSubtotal();
            this.recomputeCouponTotals();
        },

            applyChosenAutoCoupon(autoCoupon) {

             if (this.blockCouponIfWholeLechon()) {
                    return;
                }

                if (!autoCoupon) return;

                const normalizedAutoCoupon = this.normalizeCoupon(autoCoupon);

                if (!this.couponIsActive(normalizedAutoCoupon)) {
                    this.showCouponError(this.couponInactiveMessage(normalizedAutoCoupon));
                    return;
                }

                const productRequirement = this.couponProductRequirementStatus(normalizedAutoCoupon);

                if (!productRequirement.valid) {
                    this.showCouponError(productRequirement.message);
                    return;
                }

                const locationRequirement = this.couponLocationRequirementStatus(normalizedAutoCoupon);

                if (!locationRequirement.valid) {
                    this.showCouponError(locationRequirement.message);
                    return;
                }

                if (!this.shouldAutoApplyCoupon(normalizedAutoCoupon)) {
                    this.showCouponError(this.getAutoCouponUnavailableReason(normalizedAutoCoupon) || 'This coupon is not available for the current delivery location.');
                    return;
                }

                const alreadyApplied = this.coupons.some(c =>
                    this.couponCodeKey(c) === this.couponCodeKey(normalizedAutoCoupon)
                );

                if (alreadyApplied) return;

                const combinationStatus = this.couponCombinationStatus(normalizedAutoCoupon);

                if (!combinationStatus.valid) {
                    this.showCouponError(combinationStatus.message);
                    return;
                }

                normalizedAutoCoupon.auto_applied = true;

                this.selectedAutoCoupon = normalizedAutoCoupon;
                this.selectedAutoCouponId = normalizedAutoCoupon.id;

                this.coupons.push(normalizedAutoCoupon);
                this.autoAppliedCoupons.push(normalizedAutoCoupon);
                this.addFreeProductsFromCoupon(normalizedAutoCoupon);

                this.order_amount = this.cartSubtotal();
                this.recomputeCouponTotals();
            },

                applySelectedAutoCoupon() {
                    if (this.blockCouponIfWholeLechon()) {
                        this.showAutoCouponChooser = false;
                        return;
                    }

                    if (!this.selectedAutoCouponId) {
                        this.showCouponError('Please select an auto coupon first.');
                        return;
                    }

                    const chosen = this.autoCouponChoices.find(c =>
                        String(c.id) === String(this.selectedAutoCouponId)
                    );

                    if (!chosen) {
                        this.showCouponError('Selected coupon is no longer available.');
                        return;
                    }

                    if (!this.couponIsActive(chosen)) {
                        this.showCouponError(this.couponInactiveMessage(chosen));
                        this.selectedAutoCoupon = null;
                        this.selectedAutoCouponId = '';
                        return;
                    }

                    const productRequirement = this.couponProductRequirementStatus(chosen);

                    if (!productRequirement.valid) {
                        this.showCouponError(productRequirement.message);
                        return;
                    }

                    if (!chosen.auto_available) {
                        this.showCouponError(chosen.unavailable_reason || 'This coupon is not available for the current order.');
                        return;
                    }

                    const alreadyApplied = this.coupons.some(c =>
                        String(c.code || '').trim().toUpperCase() === String(chosen.code || '').trim().toUpperCase()
                    );

                    if (alreadyApplied) {
                        const normalizedChosen = this.normalizeCoupon(chosen);
                        normalizedChosen.auto_applied = true;
                        this.selectedAutoCoupon = normalizedChosen;
                        this.selectedAutoCouponId = normalizedChosen.id;
                        this.showAutoCouponChooser = false;
                        this.recomputeCouponTotals();
                        this.showCouponSuccess('Coupon applied successfully.');
                        return;
                    }

                    this.applyChosenAutoCoupon(chosen);

                    this.showAutoCouponChooser = false;
                    this.showCouponSuccess('Coupon applied successfully.');
                },

                applyGiftCheque() {
                this.giftChequeMessage = '';
                this.giftChequeMessageType = '';

                const code = String(this.giftChequeCode || '').trim().toUpperCase();

                if (!code) {
                    this.giftChequeMessage = 'Please enter a gift cheque code.';
                    this.giftChequeMessageType = 'error';
                    return;
                }

                const found = (this.giftCheques || []).find(gc =>
                    String(gc.code || '').trim().toUpperCase() === code
                );

                if (!found) {
                    this.giftChequeMessage = 'Invalid gift cheque.';
                    this.giftChequeMessageType = 'error';
                    return;
                }

                this.appliedGiftCheque = {
                    id: found.id,
                    code: found.code,
                    amount: Number(found.amount || 0),
                    gc_type: found.gc_type || ''
                };

                this.giftChequeDiscountAmount = Number(found.amount || 0);
                this.giftChequeMessage = 'Gift cheque applied successfully.';
                this.giftChequeMessageType = 'success';

                this.computeTotal();
            },

            removeGiftCheque() {
                this.appliedGiftCheque = null;
                this.giftChequeDiscountAmount = 0;
                this.giftChequeCode = '';
                this.giftChequeMessage = '';
                this.giftChequeMessageType = '';
                this.computeTotal();
            },

                recomputeCouponTotals() {
                this.totalDiscountAmount = 0;
                this.shippingDiscountAmount = 0;
                this.shippingDiscountLists = [];

                const isMulti = this.method === 'delivery' && this.allowMultiple;

                if (isMulti) {
                    this.deliveryFees = this.deliveryFees.map(row => ({
                        ...row,
                        discount: 0
                    }));
                }

                this.coupons.forEach(coupon => {
                    const normalizedCoupon = this.normalizeCoupon(coupon);

                    if (!this.couponIsActive(normalizedCoupon)) {
                        return;
                    }

                    const productRequirement = this.couponProductRequirementStatus(normalizedCoupon);

                    if (!productRequirement.valid) {
                        return;
                    }

                    if (this.isFreeShippingCoupon(normalizedCoupon) && this.method !== 'pickup') {
                        if (isMulti) {
                            const discountedLocationKeys = new Set();

                            (this.deliveries || []).forEach((delivery, idx) => {
                                if (!this.couponMatchesLocation(normalizedCoupon, delivery.city, delivery.location, delivery)) {
                                    return;
                                }

                                const locationKey = this.couponLocationKey(delivery.city, delivery.location, delivery);

                                // IMPORTANT:
                                // Same city/location can only receive this coupon discount once.
                                if (locationKey && discountedLocationKeys.has(locationKey)) {
                                    return;
                                }

                                const feeRow = this.deliveryFees[idx] || {
                                    location: [delivery.city, delivery.province].filter(Boolean).join(', '),
                                    fee: Number(delivery.delivery_fee || 0),
                                    discount: 0
                                };

                                if (!this.deliveryFees[idx]) {
                                    this.deliveryFees[idx] = feeRow;
                                }

                                const fee = parseFloat(feeRow.fee || delivery.delivery_fee || 0);

                                if (fee <= 0) {
                                    return;
                                }

                                const existingDiscount = parseFloat(this.deliveryFees[idx].discount || 0);
                                const remainingFee = Math.max(fee - existingDiscount, 0);

                                if (remainingFee <= 0) {
                                    return;
                                }

                                const discountToApply = this.getShippingDiscountAmountFromCoupon(
                                    normalizedCoupon,
                                    remainingFee
                                );

                                if (discountToApply <= 0) {
                                    return;
                                }

                                this.deliveryFees[idx].discount = existingDiscount + discountToApply;
                                this.shippingDiscountAmount += discountToApply;

                                if (locationKey) {
                                    discountedLocationKeys.add(locationKey);
                                }

                                this.shippingDiscountLists.push({
                                    location: feeRow.location,
                                    index: idx,
                                    discount: discountToApply,
                                    coupon_code: normalizedCoupon.code
                                });
                            });
                        } else {
                            if (this.couponMatchesLocation(normalizedCoupon, this.city, this.location, { province: this.province, city: this.city, location: this.location, address: this.delivery_address })) {
                                const fee = parseFloat(this.deliveryFee || 0);
                                const remainingFee = Math.max(fee - this.shippingDiscountAmount, 0);

                                this.shippingDiscountAmount += this.getShippingDiscountAmountFromCoupon(
                                    normalizedCoupon,
                                    remainingFee
                                );
                            }
                        }
                    } else {
                        this.totalDiscountAmount += this.getCouponDiscount(normalizedCoupon);
                    }
                });

                this.totalDiscountAmount = Math.min(this.totalDiscountAmount, this.cartSubtotal());

                if (!isMulti) {
                    this.shippingDiscountAmount = Math.min(
                        this.shippingDiscountAmount,
                        parseFloat(this.deliveryFee || 0)
                    );
                }

                this.computeTotal();
            },

                async init() {
                    const cookie = document.cookie.split('; ').find(row => row.startsWith('shipping_method='));
                    await this.changeMethod(cookie ? cookie.split('=')[1] : 'pickup')

                    await this.getBlockDates();
                    await this.loadPhilippineData();

                    // Keep inactive coupons out of all checkout lists immediately.
                    this.coupons = (this.coupons || []).filter(coupon => this.couponIsActive(coupon));
                    this.selectedCoupon = this.selectedCoupon && this.couponIsActive(this.selectedCoupon)
                        ? this.selectedCoupon
                        : null;
                    this.selectedAutoCoupon = this.selectedAutoCoupon && this.couponIsActive(this.selectedAutoCoupon)
                        ? this.selectedAutoCoupon
                        : null;

                    this.order_amount = this.cartSubtotal()
                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    this.refreshDeliveryCouponPopup()

                    this.$nextTick(() => {
                        if (this.method === 'pickup' && this.$refs.pickupDate) {
                            this.initPickupDatepicker(this.$refs.pickupDate)
                        }

                        if (this.method === 'delivery' && this.$refs.deliveryDate) {
                            this.initSingleDeliveryDatepicker(this.$refs.deliveryDate)
                        }
                    })

                    if (this.isGuest) {
                        this.openPrivacyModal()
                    }

                    this.$nextTick(() => {
                        if (this.sale && this.sale.delivery_type == "Door to door delivery") {
                            this.province = this.sale?.province ?? ''
                            this.onProvinceChange()
                            this.city = this.sale?.city ?? ''
                            this.onCityChange()
                            this.location = this.sale?.barangay ?? ''
                            this.onBarangayChange()
                            this.instruction = this.sale?.instruction ?? ''

                            let delivery_date = '{{ $sale && $sale?->delivery_type == "Door to door delivery" ? $sale?->items()->first()->delivery_date : '' }}'

                            if (delivery_date) {
                                this.need_date = this.formatDate(new Date(delivery_date))
                                let time = delivery_date.split(' ')[1]
                                time = time.slice(0, -3)
                                this.need_time = this.formatHourValue(time)
                            }
                        } else if (this.sale && this.sale.delivery_type == "Store Pickup") {
                            this.pickup_branch = this.sale?.outlet ?? ''
                            this.pickup_note = this.sale?.instruction ?? ''

                            let delivery_date = '{{ $sale && $sale?->delivery_type == "Store Pickup" ? $sale?->items()->first()->delivery_date : '' }}'

                            if (delivery_date) {
                                this.need_date = this.formatDate(new Date(delivery_date))
                                let time = delivery_date.split(' ')[1]
                                time = time.slice(0, -3)
                                this.need_time = this.formatHourValue(time)
                            }
                        }
                    })

                const refreshCouponStateAfterCityChange = () => {
                    this.removeInvalidLocationCoupons()
                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    this.refreshDeliveryCouponPopup()
                }

                const refreshCouponStateAfterBarangayChange = () => {
                    this.removeInvalidLocationCoupons()
                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    this.refreshDeliveryCouponPopup()
                }

                this.$watch('province', refreshCouponStateAfterCityChange)
                this.$watch('city', refreshCouponStateAfterCityChange)
                this.$watch('location', () => {
                    // Barangay/location change: refresh auto coupon choices too,
                    // so location-only coupons stay hidden until the selected location is hit.
                    this.$nextTick(() => {
                        this.applyAutoCoupons();
                        refreshCouponStateAfterBarangayChange();
                    });
                });

                this.$watch(() => JSON.stringify(this.carts || []), () => {
                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    this.refreshDeliveryCouponPopup()
                })

                this.$watch(() => JSON.stringify(this.deliveries || []), () => {
                    this.removeInvalidLocationCoupons()
                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    this.refreshDeliveryCouponPopup()
                })

                this.$watch('showAutoCouponChooser', (isOpen) => {
                    if (!isOpen) {
                        this.refreshDeliveryCouponPopup()
                    }
                })
                },

                formatDate(date) {

                    const year = date.getFullYear()
                    const month = String(date.getMonth() + 1).padStart(2, '0')
                    const day = String(date.getDate()).padStart(2, '0')

                    return `${year}-${month}-${day}`
                },

                generateHours() {
                    const hours = []

                    for (let h = this.openHour; h < this.closeHour; h++) {
                        hours.push(h)
                    }

                    return hours
                },

                async populateMultiDeliveryTimes(index) {

                    const delivery = this.deliveries[index]
                    
                    await this.getBlockDatesForMulti(delivery.orders, index);

                    this.$nextTick(() => {
                        const el = this.deliveries[index]._el;

                        if (!el._datepicker) {
                            this.initMultiDeliveryDatepicker(el, index);
                        } else {
                            el._datepicker.destroy(); 
                            this.initMultiDeliveryDatepicker(el, index);
                        }
                    });

                    if (!delivery.orders.length) {
                        delivery.need_date = ''
                        delivery.need_time = ''
                        delivery.availableHours = []
                        return
                    }

                    const earliest = this.getEarliestDateTimeForDelivery(delivery)
                    const nowRounded = this.roundUpToNextHour(new Date())
                    const finalMinDate = earliest > nowRounded ? earliest : nowRounded

                    // Update datepicker minimum date dynamically
                    delivery._datepicker?.setOptions({
                        minDate: finalMinDate
                    })

                    const nextValidDate = this.getNextAvailableDate(
                        finalMinDate,
                        delivery.blockedDetails || [],
                        (b) =>
                            this.blockAppliesToDelivery(b, delivery) &&
                            (b.block_type === 'both' || b.block_type === 'delivery') &&
                            this.isBlockedWithCombo(b)
                    )
                    const parts = this.formatDateTimeParts(nextValidDate)

                    // Only force date if empty or invalid
                    if (!delivery.need_date || delivery.need_date < parts.date) {
                        delivery.need_date = parts.date
                        delivery._datepicker?.setDate(parts.date)
                    }

                    let hours = this.generateHours()

                    // REMOVE BLOCKED TIME SLOTS (per delivery)
                    const dateBlocks = (delivery.blockedDetails || []).filter(b =>
                        b.date === delivery.need_date &&
                        this.blockAppliesToDelivery(b, delivery) &&
                        (b.block_type === 'both' || b.block_type === 'delivery') &&
                        this.isBlockedWithCombo(b) &&
                        b.is_all_day == 0
                    )

                    hours = hours.filter(hour => {

                        const timeStr = this.formatHourValue(hour)

                        const blocked = dateBlocks.some(b => {

                            // normalize time format
                            const start = b.start_time?.substring(0,5)
                            const end   = b.end_time?.substring(0,5)

                            return timeStr >= start && timeStr < end
                        })

                        return !blocked
                    })

                    if (delivery.need_date === parts.date) {
                        hours = hours.filter(h => h >= parts.hour)
                    }

                    delivery.availableHours = hours

                    this.$nextTick(() => {
                        if (!hours.length) {
                            delivery.need_time = ''
                            return
                        }

                        const firstHour = this.formatHourValue(hours[0])

                        const valid = hours.some(h =>
                            this.formatHourValue(h) === delivery.need_time
                        )

                        delivery.need_time = valid ? delivery.need_time : firstHour
                    })
                },

                formatAMPM(hour) {
                    const suffix = hour >= 12 ? 'PM' : 'AM'
                    const formatted = hour % 12 === 0 ? 12 : hour % 12
                    return formatted + ':00 ' + suffix
                },

                formatHourValue(hour) {
                    return (hour < 10 ? '0' + hour : hour) + ':00'
                },

                getCurrentHour() {
                    return new Date().getHours()
                },

                isToday(dateStr) {
                    const today = new Date().toISOString().split('T')[0]
                    return dateStr === today
                },

                initSingleDeliveryDatepicker(el) {
                if (el._datepicker) {
                    el._datepicker.destroy()
                }

                const earliest = this.getEarliestAllowedDateTime()
                const nextValidDate = this.getNextAvailableDate(
                    earliest,
                    this.blockedDetails,
                    (b) =>
                        this.blockAppliesToCart(b) &&
                        this.blockAppliesToMethod(b) &&
                        this.isBlockedWithCombo(b)
                )
                const parts = this.formatDateTimeParts(nextValidDate)

                const picker = new Datepicker(el, {
                    autohide: true,
                    format: 'yyyy-mm-dd',
                    minDate: earliest,
                    placeholder: 'Select date',
                    beforeShowDay: (date) => {
                        const formatted = this.formatDate(date)

                        const blockedForThisDate = this.blockedDetails.filter(b =>
                            b.date === formatted &&
                            this.blockAppliesToCart(b) &&
                            this.blockAppliesToMethod(b) &&
                            this.isBlockedWithCombo(b)
                        )

                        const hasAllDayBlock = blockedForThisDate.some(b => b.is_all_day == 1)

                        if (hasAllDayBlock) {
                            return { enabled: false }
                        }

                        return { enabled: true }
                    }
                })

                el._datepicker = picker

                if (this.sale && this.sale.delivery_type == "Door to door delivery") {
                    // Keep existing sale date when editing.
                } else {
                    this.need_date = parts.date
                    picker.setDate(parts.date)
                }

                this.$nextTick(() => {
                    this.populateDeliveryTimes(parts.hour)
                })

                el.addEventListener('changeDate', (e) => {
                    this.need_date = this.formatDate(e.detail.date)
                    this.populateDeliveryTimes()
                })
            },



                initPickupDatepicker(el) {

                    if (el._datepicker) {
                        el._datepicker.destroy()
                    }

                    const earliest = this.getEarliestAllowedDateTime()
                    const nextValidDate = this.getNextAvailableDate(
                        earliest,
                        this.blockedDetails,
                        (b) =>
                            this.blockAppliesToCart(b) &&
                            this.blockAppliesToMethod(b) &&
                            this.isBlockedWithCombo(b)
                    )
                    const parts = this.formatDateTimeParts(nextValidDate)

                    const picker = new Datepicker(el, {
                        autohide: true,
                        format: 'yyyy-mm-dd',
                        minDate: earliest,
                        placeholder: 'Select date',
                        beforeShowDay: (date) => {
                        const formatted = this.formatDate(date)

                        const blockedForThisDate = this.blockedDetails.filter(b =>
                            b.date === formatted &&
                            this.blockAppliesToCart(b) &&
                            this.blockAppliesToMethod(b) &&
                            this.isBlockedWithCombo(b)
                        )

                        const hasAllDayBlock = blockedForThisDate.some(b => b.is_all_day == 1)

                        if (hasAllDayBlock) {
                            return { enabled: false }
                        }

                        return { enabled: true }
                    }

                    })

                    el._datepicker = picker

                    this.need_date = parts.date
                    picker.setDate(parts.date)

                    // auto populate time correctly
                    this.$nextTick(() => {
                        this.populatePickupTimes(parts.hour)
                    })

                    el.addEventListener('changeDate', (e) => {
                        this.need_date = this.formatDate(e.detail.date)
                        this.populatePickupTimes()
                    })
                },

                initMultiDeliveryDatepicker(el, index) {
                if (el._datepicker) {
                    el._datepicker.destroy()
                }

                const picker = new Datepicker(el, {
                    autohide: true,
                    format: 'yyyy-mm-dd',
                    placeholder: 'Select date',
                    beforeShowDay: (date) => {
                        const delivery = this.deliveries[index]

                        if (!delivery || !delivery.orders.length) {
                            return { enabled: false }
                        }

                        const formatted = this.formatDate(date)

                        const blockedForThisDate = (delivery.blockedDetails || []).filter(b =>
                            b.date === formatted &&
                            this.blockAppliesToDelivery(b, delivery) &&
                            (b.block_type === 'both' || b.block_type === 'delivery') &&
                            this.isBlockedWithCombo(b)
                        )

                        const hasAllDayBlock = blockedForThisDate.some(b => b.is_all_day == 1)

                        if (hasAllDayBlock) {
                            return { enabled: false }
                        }

                        const nowRounded = this.roundUpToNextHour(new Date())
                        const earliest = this.getEarliestDateTimeForDelivery(delivery)
                        const finalMinDate = earliest > nowRounded ? earliest : nowRounded

                        const compareDate = new Date(date)
                        compareDate.setHours(0, 0, 0, 0)

                        const minCompare = new Date(finalMinDate)
                        minCompare.setHours(0, 0, 0, 0)

                        if (compareDate < minCompare) {
                            return { enabled: false }
                        }

                        return { enabled: true }
                    }
                })

                el._datepicker = picker
                this.deliveries[index]._datepicker = picker

                el.addEventListener('changeDate', (e) => {
                    if (!this.deliveries[index].orders.length) return

                    this.deliveries[index].need_date = this.formatDate(e.detail.date)
                    this.populateMultiDeliveryTimes(index)
                })
            },

                        

                populatePickupTimes(minHour = null) {

                    if (!this.need_date) return

                    let hours = this.generateHours()

                    const dateBlocks = this.getBlockedTimeRangesForDate(this.need_date)

                    hours = hours.filter(hour => {

                        const timeStr = this.formatHourValue(hour) // "11:00"

                        const isBlocked = dateBlocks.some(b => {

                            const start = this.normalizeTime(b.start_time)
                            const end   = this.normalizeTime(b.end_time)

                            return timeStr >= start && timeStr < end
                        })

                        return !isBlocked
                    })

                    const earliest = this.getEarliestForPickupAndSingle()
                    const nextValidDate = this.getNextAvailableDate(
                        earliest,
                        this.blockedDetails,
                        (b) =>
                            this.blockAppliesToCart(b) &&
                            this.blockAppliesToMethod(b) &&
                            this.isBlockedWithCombo(b)
                    )
                    const parts = this.formatDateTimeParts(nextValidDate)

                    if (this.need_date === parts.date) {
                        const requiredHour = minHour ?? parts.hour
                        hours = hours.filter(h => h >= requiredHour)
                    }

                    this.availablePickupHours = hours

                    this.$nextTick(() => {
                        if (this.sale && this.sale.delivery_type == "Store Pickup" && this.method == 'pickup') {
                            let delivery_date = '{{ $sale && $sale?->delivery_type == "Store Pickup" ? $sale?->items()->first()->delivery_date : '' }}'

                            if (delivery_date) {
                                let time = delivery_date.split(' ')[1]
                                time = time.slice(0, -3)
                                setTimeout(() => {
                                    this.need_time = time
                                }, 300)
                            }
                        } else {
                            this.need_time = hours.length
                                ? this.formatHourValue(hours[0])
                                : ''
                        }
                    })
                },

                populateDeliveryTimes(minHour = null) {

                    if (!this.need_date) return

                    let hours = this.generateHours()

                    const dateBlocks = this.getBlockedTimeRangesForDate(this.need_date)

                    hours = hours.filter(hour => {

                        const timeStr = this.formatHourValue(hour)

                        const isBlocked = dateBlocks.some(b => {

                            const start = this.normalizeTime(b.start_time)
                            const end   = this.normalizeTime(b.end_time)

                            return timeStr >= start && timeStr < end
                        })

                        return !isBlocked
                    })

                    const earliest = this.getEarliestForPickupAndSingle()
                    const nextValidDate = this.getNextAvailableDate(
                        earliest,
                        this.blockedDetails,
                        (b) =>
                            this.blockAppliesToCart(b) &&
                            this.blockAppliesToMethod(b) &&
                            this.isBlockedWithCombo(b)
                    )
                    const parts = this.formatDateTimeParts(nextValidDate)

                    if (this.need_date === parts.date) {
                        const requiredHour = minHour ?? parts.hour
                        hours = hours.filter(h => h >= requiredHour)
                    }

                    this.availableDeliveryHours = hours

                    this.$nextTick(() => {
                        if (this.sale && this.sale.delivery_type == "Door to door delivery" && this.method == 'delivery') {
                            let delivery_date = '{{ $sale && $sale?->delivery_type == "Door to door delivery" ? $sale?->items()->first()->delivery_date : '' }}'

                            if (delivery_date) {
                                let time = delivery_date.split(' ')[1]
                                time = time.slice(0, -3)
                                setTimeout(() => {
                                    this.need_time = time
                                }, 300)
                            }
                        } else {
                            this.need_time = hours.length
                                ? this.formatHourValue(hours[0])
                                : ''
                        }
                    })
                },

                formatDateTimeParts(dateObj) {

                    const year = dateObj.getFullYear()
                    const month = String(dateObj.getMonth() + 1).padStart(2, '0')
                    const day = String(dateObj.getDate()).padStart(2, '0')

                    const hour = dateObj.getHours()

                    return {
                        date: `${year}-${month}-${day}`,
                        hour: hour
                    }
                },

                roundUpToNextHour(dateObj) {

                    const rounded = new Date(dateObj)

                    if (
                        rounded.getMinutes() > 0 ||
                        rounded.getSeconds() > 0 ||
                        rounded.getMilliseconds() > 0
                    ) {
                        rounded.setHours(rounded.getHours() + 1)
                        rounded.setMinutes(0, 0, 0)
                    }

                    return rounded
                },

                formatMoney(value) {
                    return '₱' + (parseFloat(value) || 0)
                        .toLocaleString(undefined, {
                            minimumFractionDigits: 2
                        })
                },
                cartSubtotal() {
                return this.carts.reduce((sum, item) => {
                    const qty = Number(item?.qty || 1)
                    const base = item?.is_free_product ? 0 : Number(item?.price || 0)
                    const paella = Number(item?.paella_price || 0) > 0
                        ? Number(item?.product?.paella_price || 0)
                        : 0

                    return sum + ((base + paella) * qty)
                }, 0)
                },

                get formattedSubtotal() {
                    const total = this.carts.reduce((sum, item) => {
                        const qty = Number(item.qty) || 1
                        const base = item.is_free_product ? 0 : Number(item.price) || 0
                        const paella = item.paella_price > 0 ?
                            Number(item.product?.paella_price || 0) :
                            0

                        return sum + ((base + paella) * qty)
                    }, 0)

                    return this.formatMoney(total)
                },

            get formattedTotalAmount() {
                return this.formatMoney(this.total_amount || 0);
            },

            giftChequeDiscountLabel() {
                return `- ${this.formatMoney(this.giftChequeDiscountAmount || 0)}`;
            },

                itemLineTotal(item) {
                    if (item.is_free_product) return '₱0.00'

                    const qty = Number(item.qty) || 1
                    const base = Number(item.price) || 0
                    const paella = item.paella_price > 0 ?
                        Number(item.product?.paella_price || 0) :
                        0

                    return this.formatMoney((base + paella) * qty)
                },

                itemImage(item) {
                    return item?.product?.photos?.length ?
                        item.product.photos[item.product.photos.length - 1].url :
                        '/images/no-image.jpg'
                },

                

                /* ==========================
                 * TOTAL
                 * ========================== */
                computeTotal() {
                const itemsTotal = this.cartSubtotal();

                let deliveryFeeFinal = 0;

                if (this.method !== 'pickup') {
                    if (this.allowMultiple) {
                        deliveryFeeFinal = this.deliveries.reduce((sum, d) => {
                            return sum + Number(d.delivery_fee || 0) + Number(d.lechon_baka_service || 0);
                        }, 0);
                    } else {
                        deliveryFeeFinal = Number(this.deliveryFee || 0) + Number(this.lechonBakaService || 0);
                    }
                }

                deliveryFeeFinal = Math.max(0, deliveryFeeFinal - Number(this.shippingDiscountAmount || 0));

                let total = itemsTotal
                    + deliveryFeeFinal
                    - Number(this.totalDiscountAmount || 0)
                    - Number(this.giftChequeDiscountAmount || 0);

                if (total < 0) total = 0;

                this.discount_amount =
                    Number(this.totalDiscountAmount || 0) +
                    Number(this.shippingDiscountAmount || 0) +
                    Number(this.giftChequeDiscountAmount || 0);

                this.total_amount = total;
                this.deposit = total.toFixed(2);

                return this.formatMoney(total);
                },

                async changeMethod(type) {
                    if (type == this.method) return

                    this.method = type

                    if (type === 'pickup') {
                        this.allowMultiple = false
                        this.deliveryFee = 0
                        this.deliveryFees = []
                        this.need_time = ''
                        this.pickup_note = ''
                        this.availableDeliveryHours = []
                    }

                    if (type === 'delivery') {
                        this.allowMultiple = false

                        // reset single delivery state cleanly
                        this.need_time = ''
                        this.availableDeliveryHours = []
                    }

                    this.province = ''
                    this.city = ''
                    this.pickup_branch = ''
                    this.blockedDetails = []

                    this.deliveryFee = 0;
                    this.lechonBakaService = this.hasBaka ? window.lechonBakaService : 0;

                    this.resetDeliveryCouponPopupForLocationChange(true)
                    this.computeTotal()
                    this.applyAutoCoupons()
                this.recomputeCouponTotals()
                },

                availablePickupHours: [],

                openHour: 9,
                closeHour: 20,
                availableDeliveryHours: [],

                

                async onPickupBranchChange() {
                    this.pickupErrors.branch = ''
                    this.getBlockDates().then(() => {
                        this.$nextTick(() => {
                            if (this.$refs.pickupDate) {
                                this.initPickupDatepicker(this.$refs.pickupDate)
                            }
                        })
                    })
                },

                validatePickupDateTime() {
                    this.pickupErrors.date = ''
                    this.pickupErrors.time = ''

                    if (!this.need_date) {
                        this.pickupErrors.date = 'Please select a date.'
                    }

                    if (!this.need_time) {
                        this.pickupErrors.time = 'Please select a time.'
                    }
                },

                /* ==========================
                SINGLE DELIVERY STATE
                ========================== */

                delivery_address_street: '',
                delivery_address: '',
                province: '',
                city: '',
                location: '',
                need_date: '',
                need_time: '',
                instruction: '',
                isEditingAddress: false,

                singleDeliveryErrors: {},

                /* ==========================
                EVENTS
                ========================== */

                validateSingleDeliveryField(field) {

                    if (!this.singleDeliveryErrors) {
                        this.singleDeliveryErrors = {}
                    }

                    switch (field) {
                        case 'address':
                            this.singleDeliveryErrors.address =
                                this.delivery_address ? '' : 'Address is required.'
                            break

                        case 'province':
                            this.singleDeliveryErrors.province =
                                this.province ? '' : 'Province is required.'
                            break

                        case 'city':
                            this.singleDeliveryErrors.city =
                                this.city ? '' : 'City is required.'
                            break

                        case 'date':
                            this.singleDeliveryErrors.date =
                                this.need_date ? '' : 'Please select a date.'
                            break

                        case 'time':
                            this.singleDeliveryErrors.time =
                                this.need_time ? '' : 'Please select a time.'
                            break
                    }
                },

                onProvinceChange() {
                    this.city = ''
                    this.location = ''
                    this.validateSingleDeliveryField('province')

                    this.rebuildAddress()
                    this.resetDeliveryCouponPopupForLocationChange(true)
                    this.removeInvalidLocationCoupons()
                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    this.refreshDeliveryCouponPopup()
                },

                onCityChange() {
                    this.location = ''
                    this.validateSingleDeliveryField('city')

                    this.getBlockDates(true).then(() => {
                        this.$nextTick(() => {
                            if (this.$refs.deliveryDate) {
                                this.initSingleDeliveryDatepicker(this.$refs.deliveryDate)
                            }
                        })
                    })

                    this.rebuildAddress()
                    this.getDeliveryFee?.()
                    this.resetDeliveryCouponPopupForLocationChange(true)
                    this.removeInvalidLocationCoupons()
                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    this.refreshDeliveryCouponPopup()
                },

                onBarangayChange() {
                    this.rebuildAddress();

                    this.$nextTick(() => {
                        this.resetDeliveryCouponPopupForLocationChange(true);
                        this.removeInvalidLocationCoupons();
                        this.applyAutoCoupons();
                        this.recomputeCouponTotals();
                        this.refreshDeliveryCouponPopup();
                    });
                },

                onMultiProvinceChange(index) {

                    const d = this.deliveries[index]

                    d.city = ''
                    d.location = ''

                    this.rebuildMultiAddress(index)
                    this.resetDeliveryCouponPopupForLocationChange(true)
                    this.removeInvalidLocationCoupons()
                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    this.refreshDeliveryCouponPopup()
                },

                onMultiCityChange(index) {

                    const d = this.deliveries[index]

                    d.location = ''

                    this.rebuildMultiAddress(index)
                    this.resetDeliveryCouponPopupForLocationChange(true)
                    this.removeInvalidLocationCoupons()
                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    this.refreshDeliveryCouponPopup()
                },

                onMultiBarangayChange(index) {
                    this.rebuildMultiAddress(index);

                    this.getDeliveryFeeForMulti?.(index);

                    this.$nextTick(() => {
                        this.resetDeliveryCouponPopupForLocationChange(true);
                        this.removeInvalidLocationCoupons();
                        this.applyAutoCoupons();
                        this.recomputeCouponTotals();
                        this.refreshDeliveryCouponPopup();
                    });
                },

                deliveries: [{
                    orders: [],
                    need_date: '',
                    need_time: '',
                    address: '',
                    province: '',
                    city: '',
                    location: '',
                    name: '',
                    phone: '',
                    note: '',
                    delivery_fee: 0,
                    errors: {},
                    isEditingAddress: false,
                    street: '',
                    sms: false,
                    cochinillo_warning: false,
                    paella: false,
                }],

                errors: {},

                addDelivery() {
                    this.deliveries.push({
                        orders: [],
                        need_date: '',
                        need_time: '',
                        address: '',
                        province: '',
                        city: '',
                        location: '',
                        name: '',
                        phone: '',
                        note: '',
                        delivery_fee: 0,
                        errors: {},
                        isEditingAddress: false,
                        street: '',
                        sms: false,
                        cochinillo_warning: false,
                        paella: false,
                    })
                },

                removeDelivery(index) {
                    this.deliveries.splice(index, 1)
                },

                validateDelivery(index, field) {
                    if (!this.errors[index]) this.errors[index] = {}

                    const delivery = this.deliveries[index]

                    switch (field) {
                        case 'date':
                            this.errors[index].need_date =
                                delivery.need_date ? '' : 'Date required'
                            break
                        case 'time':
                            this.errors[index].need_time =
                                delivery.need_time ? '' : 'Time required'
                            break
                        case 'address':
                            this.errors[index].address =
                                delivery.address ? '' : 'Address required'
                            break
                        case 'name':
                            this.errors[index].name =
                                delivery.name ? '' : 'Name required'
                            break
                        case 'phone':
                            this.errors[index].phone =
                                delivery.phone ? '' : 'Phone required'
                            break
                    }
                },

                contact: {
                    name: '{{ auth()->user()->name ?? '' }}',
                    mobile: '{{ auth()->user()->contact_mobile ?? '' }}',
                    email: '{{ auth()->user()->email ?? '' }}',
                    agent: ''
                },

                note: '',
                privacy: {{ auth()->check() ? 'true' : 'false' }},

                isSubmitting: false,
                formSubmitting: false,
                hasErrorMessage: false,
                warningMessage: '',

                privacyModal: false,

                openPrivacyModal() {
                    this.privacyModal = true
                },

                closePrivacyModal() {
                    this.privacyModal = false
                },

                agreePrivacy() {
                    this.privacy = true
                    this.privacyModal = false
                },

                order_amount: {{ $total }},
                total_amount: 0,
                discount_amount: 0,
                deposit: '',

                incompleteMessage: 'sdfsdfsdf',

                async submitForm() {

                    if (this.isSubmitting) return

                    this.isSubmitting = true

                    this.formSubmitting = true

                    if (this.isGuest && !this.privacy) {
                        this.errors.privacy = 'You must agree to the privacy policy.'
                        this.isSubmitting = false
                        this.formSubmitting = false

                        this.$nextTick(() => {
                            this.smoothScroll('.border-red-500')
                        })

                        return
                    }

                    try {

                        const inactiveCoupon = (this.coupons || [])
                            .map(coupon => this.normalizeCoupon(coupon))
                            .find(coupon => !this.couponIsActive(coupon));

                        if (inactiveCoupon) {
                            this.showCouponError(this.couponInactiveMessage(inactiveCoupon));
                            this.removeFreeProductsByCoupon(inactiveCoupon.code);
                            this.coupons = (this.coupons || []).filter(coupon =>
                                this.couponCodeKey(coupon) !== this.couponCodeKey(inactiveCoupon)
                            );
                            this.isSubmitting = false;
                            this.formSubmitting = false;
                            this.recomputeCouponTotals();
                            return;
                        }

                        // Re-check product count minimum purchase rules before submitting.
                        // Example: Product = Bopis, Total Quantity = 2, Minimum.
                        const invalidProductCoupon = (this.coupons || [])
                            .map(coupon => {
                                const normalized = this.normalizeCoupon(coupon);

                                return {
                                    coupon: normalized,
                                    status: this.couponProductRequirementStatus(normalized)
                                };
                            })
                            .find(row => !row.status.valid);

                        if (invalidProductCoupon) {
                            this.showCouponError(invalidProductCoupon.status.message);
                            this.isSubmitting = false;
                            this.formSubmitting = false;
                            return;
                        }

                        const invalidLocationCoupon = (this.coupons || [])
                            .map(coupon => {
                                const normalized = this.normalizeCoupon(coupon);

                                return {
                                    coupon: normalized,
                                    status: this.couponLocationRequirementStatus(normalized)
                                };
                            })
                            .find(row => !row.status.valid);

                        if (invalidLocationCoupon) {
                            this.showCouponError(invalidLocationCoupon.status.message);
                            this.removeFreeProductsByCoupon(invalidLocationCoupon.coupon.code);
                            this.coupons = (this.coupons || []).filter(coupon =>
                                this.couponCodeKey(coupon) !== this.couponCodeKey(invalidLocationCoupon.coupon)
                            );
                            this.isSubmitting = false;
                            this.formSubmitting = false;
                            this.recomputeCouponTotals();
                            return;
                        }

                        const couponPayload = this.coupons.map(coupon => {
                        const normalized = this.normalizeCoupon(coupon);

                        return {
                            id: normalized.id,
                            code: normalized.code,
                            name: normalized.name,
                            reward: normalized.reward,
                            activation_type: normalized.activation_type,
                            auto_applied: normalized.auto_applied === true,
                            combination_allowed: normalized.combination_allowed === true,
                            discount_type: normalized.discount_type,
                            discount: Number(normalized.discount || 0),
                            free_shipping: this.isFreeShippingCoupon(normalized),
                            shipping_discount_type: normalized.shipping_discount_type,
                            shipping_discount_amount: Number(normalized.shipping_discount_amount || 0),
                            free_products: normalized.free_products || [],
                            required_product_qty: Number(this.couponEffectiveRequiredProductQty(normalized) || 0),
                            required_product_ids: this.getCouponSelectedProductIds(normalized),
                            required_category_ids: this.getCouponSelectedCategoryIds(normalized),
                            matched_product_qty: Number(this.couponMatchedProductQty(normalized) || 0),
                            matched_category_qty: Number(this.couponMatchedCategoryQty(normalized) || 0),
                            product_requirement_passed: this.couponProductRequirementStatus(normalized).valid,
                            discount_multiplier: Number(this.couponDiscountMultiplier(normalized) || 0),
                            discount_used: Number(this.getCouponDiscount(normalized) || 0)
                        };
                    });

                        let payload = {
                            name: this.contact.name,
                            mobile: this.contact.mobile,
                            email: this.contact.email,
                            agent: this.contact.agent,
                            shipping_type: this.method,

                            selected_auto_coupon_id: this.appliedAutoCouponIds()[0] || (this.selectedAutoCoupon ? this.selectedAutoCoupon.id : null),
                            selected_coupon_id: this.appliedManualCouponIds()[0] || (this.selectedCoupon ? this.selectedCoupon.id : null),
                            selected_auto_coupon_ids: this.appliedAutoCouponIds(),
                            selected_coupon_ids: this.appliedManualCouponIds(),
                            coupon_ids: this.appliedCouponIds(),

                            coupons: JSON.stringify(couponPayload),
                            coupon_data: JSON.stringify(couponPayload),
                            discount_amount: this.discount_amount || 0,
                            order_amount: this.cartSubtotal(),
                            delivery_fee: this.deliveryFee || 0,
                            deposit: this.deposit,
                            total_amount: this.total_amount,
                            isBaka: this.hasBaka ? 1 : 0,
                            lechon_baka_service: this.lechonBakaService,
                            gift_cheque: this.appliedGiftCheque ? JSON.stringify(this.appliedGiftCheque) : null,
                            gift_cheque_amount: Number(this.giftChequeDiscountAmount || 0),
                        };

                        /* ==========================
                        PICKUP
                        ========================== */

                        if (this.method === 'pickup') {

                            payload.delivery_branch = this.pickup_branch
                            payload.need_date = this.need_date
                            payload.need_time = this.need_time
                            payload.instruction = this.pickup_note
                        }


                        /* ==========================
                        SINGLE DELIVERY
                        ========================== */

                        if (this.method === 'delivery' && !this.allowMultiple) {

                            payload.delivery_address = this.delivery_address
                            payload.province = this.province
                            payload.city = this.city
                            payload.location = this.location
                            payload.need_date = this.need_date
                            payload.need_time = this.need_time
                            payload.instruction = this.instruction
                        }

                        /* ==========================
                        MULTI DELIVERY
                        ========================== */

                        if (this.method === 'delivery' && this.allowMultiple) {

                            if (this.hasRemainingOrders()) {
                                this.errors.unused = 'Please assign all products to a delivery.'
                                this.isSubmitting = false
                                this.formSubmitting = false

                                this.$nextTick(() => {
                                    this.smoothScroll('.border-red-500')
                                })

                                return
                            } else {
                                this.errors.errors = ''
                            }

                            payload.delivery_address = this.deliveries[0]?.address ?? ''
                            payload.province = this.deliveries[0]?.province ?? ''
                            payload.city = this.deliveries[0]?.city ?? ''
                            payload.location = this.deliveries[0]?.location ?? ''
                            payload.need_time = this.deliveries[0]?.need_time ?? ''

                            payload.delivery_fee = this.deliveryFees?.reduce((a,b)=>a+b.fee,0) || 0

                            payload.deliveries = JSON.stringify(
                                this.deliveries.map(d => ({
                                    orders: d.orders.map(o => ({
                                        product_id: o.product_id,
                                        paella: o.paella,
                                        is_free_product: o.is_free_product,
                                        qty: o.qty,
                                        product: o.product,
                                        product_name: o.product_name,
                                        price: o.price
                                    })),
                                    need_date: d.need_date,
                                    need_time: d.need_time,
                                    address: d.address,
                                    province: d.province,
                                    city: d.city,
                                    location: d.location,
                                    name: d.name,
                                    phone: d.phone,
                                    note: d.note,
                                    delivery_fee: d.delivery_fee,
                                    errors: d.errors,
                                    isEditingAddress: d.isEditingAddress,
                                    street: d.street,
                                    sms: d.sms ?? false,
                                    cochinillo_warning: d.cochinillo_warning,
                                    paella: d.paella,
                                    availableHours: d.availableHours,
                                    isBaka: d.isBaka ?? false,
                                    lechon_baka_service: d.lechon_baka_service ?? 0,
                                }))
                            )
                        }

                        try {

                            payload._token = document.querySelector('meta[name="csrf-token"]').content

                            const response = await fetch("{{ route('cart.temp_sales') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify(payload)
                            })

                            const data = await response.json()

                            if (!response.ok) {
                                this.handleBackendErrors(data.errors)
                                this.isSubmitting = false
                                return
                            }

                            // redirect or open payment modal
                            this.onOrderSuccess(data)

                        } catch (e) {
                            this.isSubmitting = false
                            console.error(e)
                        }

                    } catch (error) {
                        console.error(error)
                        this.formSubmitting = false
                    }
                },

                handleBackendErrors(errors) {

                    if (!errors) return

                    // Reset existing errors
                    this.errors = {}
                    this.singleDeliveryErrors = {}

                    if (this.deliveries) {
                        this.deliveries.forEach(d => d.errors = {})
                    }

                    Object.keys(errors).forEach(key => {

                        const message = Array.isArray(errors[key])
                            ? errors[key][0]
                            : errors[key]

                        // Multi delivery errors
                        if (key.startsWith('deliveries.')) {

                            const parts = key.split('.')
                            const index = parseInt(parts[1])
                            const field = parts[2]

                            if (this.deliveries[index]) {
                                this.deliveries[index].errors[field] = message
                            }

                            return
                        }

                        // Single delivery specific fields
                        if ([
                            'delivery_address',
                            'province',
                            'city',
                            'location',
                        ].includes(key)) {

                            this.singleDeliveryErrors[key] = message
                            return
                        }

                        // Date/time — depends on shipping type
                        if (['need_date', 'need_time'].includes(key)) {

                            if (this.method === 'delivery' && !this.allowMultiple) {
                                this.singleDeliveryErrors[key] = message
                            } else {
                                this.errors[key] = message
                            }

                            return
                        }

                        // Contact info / global errors
                        this.errors[key] = message
                    })

                    // Scroll to first error
                    this.$nextTick(() => {
                        this.smoothScroll('.border-red-500')
                    })
                },

                smoothScroll(selector) {
                    if (!selector) return
                    
                    const el = document.querySelector(selector)
                    if (el) {
                        el.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        })
                    }
                },

                onOrderSuccess(data) {
                    if (data.success) {
                        this.paymentDetails = {
                            sales_header_id: data.sales_header_id,
                            order_number: data.order_number,
                            customer_contact_number: data.customer_contact_number,
                            customer_name: data.customer_name,
                            amount: data.amount,
                            signature: data.signature,
                            saved_items: data.saved_items
                        };

                        if (data.amount <= 0) {
                            window.location.href = '/sales-summary/' + data.sales_header_id;
                            return;
                        }

                        this.paymentModal = true;

                        // this.isSubmitting = false;
                    } else {
                        this.isSubmitting = false
                        this.warningMessage = data.message;
                    };
                },

                isGuest: {{ auth()->guest() ? 'true' : 'false' }},

                paymentModal: false,
                paymentMode: 'PayMaya',
                isProcessingPayment: false,

                paymentDetails: {
                    order_number: '',
                    amount: 0
                },

                openPaymentModal(orderNumber, amount) {
                    this.paymentDetails.order_number = orderNumber
                    this.paymentDetails.amount = amount
                    this.paymentModal = true
                },

                closePaymentModal() {
                    this.paymentModal = false
                },

                cancelPayment() {
                    this.paymentModal = false
                    window.location.href = '/sales-summary/' + this.paymentDetails.sales_header_id
                },

                submitPayment() {

                    if (this.isProcessingPayment) return

                    this.isProcessingPayment = true

                    // Example: redirect to backend payment route
                    const form = document.createElement('form')
                    form.method = 'POST'
                    form.action = "{{ route('paymaya.paytest') }}"

                    const csrf = document.createElement('input')
                    csrf.type = 'hidden'
                    csrf.name = '_token'
                    csrf.value = "{{ csrf_token() }}"

                    const order = document.createElement('input')
                    order.type = 'hidden'
                    order.name = 'sales_header_id'
                    order.value = this.paymentDetails.sales_header_id

                    const amount = document.createElement('input')
                    amount.type = 'hidden'
                    amount.name = 'amount'
                    amount.value = this.paymentDetails.amount

                    form.appendChild(csrf)
                    form.appendChild(order)
                    form.appendChild(amount)
                    
                    document.body.appendChild(form)
                    form.submit()
                    
                },


                phData: {},
                provincesList: [],

                async loadPhilippineData() {

                    const res = await fetch(
                        '{{ asset('addresses/philippine_provinces_cities_municipalities_and_barangays_2019v2.json') }}'
                    )

                    this.phData = await res.json()

                    this.extractProvinces()
                },

                extractProvinces() {

                    const provinces = []

                    Object.values(this.phData).forEach(region => {

                        Object.keys(region.province_list).forEach(province => {
                            provinces.push(province)
                        })

                    })

                    this.provincesList = provinces.sort()
                },

                get filteredCities() {
                    if (!this.province) return []

                    return window.availableCities
                        .filter(c => c.province === this.province)
                        .map(c => c.city)
                        .sort()
                },

                filteredBarangay() {

                    if (!this.province || !this.city) return []

                    let barangays = []

                    Object.values(this.phData).forEach(region => {

                        const provinceObj = region.province_list[this.province]

                        if (provinceObj) {

                            const cityObj = provinceObj.municipality_list[this.city]

                            if (cityObj) {
                                barangays = cityObj.barangay_list
                            }
                        }

                    })

                    return barangays.sort()
                },

                multipleFilteredCities(index) {
                    const delivery = this.deliveries[index]

                    if (!delivery.province) return []

                    return window.availableCities
                        .filter(c => c.province === delivery.province)
                        .map(c => c.city)
                        .sort()
                },

                filteredMultipleBarangay(index) {

                    const delivery = this.deliveries[index]

                    if (!delivery.province || !delivery.city) return []

                    let barangays = []

                    Object.values(this.phData).forEach(region => {

                        const provinceObj = region.province_list[delivery.province]

                        if (provinceObj) {

                            const cityObj = provinceObj.municipality_list[delivery.city]

                            if (cityObj) {
                                barangays = cityObj.barangay_list
                            }
                        }

                    })

                    return barangays.sort()
                },

                async getDeliveryFee(refreshCoupons = true) {

                    if (!this.province || !this.city) return

                    this.isBaka = false;

                const response = await fetch('{{ route('cart.front.get_shipping_fee') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            province: this.province,
                            city: this.city
                        })
                    })

                    const data = await response.json()

                    this.deliveryFee = parseFloat(data.fee || 0)

                    this.$nextTick(() => {
                        this.isBaka = data.is_baka;
                        this.lechonBakaService = data.lechon_baka_service;

                        if (refreshCoupons) {
                            this.applyAutoCoupons();
                        }

                        this.recomputeCouponTotals();
                        this.refreshDeliveryCouponPopup();
                    })
                },

                async getDeliveryFeeForMultiple(index) {
                    const delivery = this.deliveries[index];

                    const city = delivery?.city;
                    const province = delivery?.province;

                    if (city && province) {
                        await this.getBlockDatesForMulti(delivery.orders, index);
                    }

                    // include qty in products
                    const products = delivery?.orders?.map(o => ({ product_id: o.product_id, qty: o.qty }));

                    if (!delivery?.orders || !delivery?.orders?.length) {
                        delivery.city = '';
                        delivery.province = '';

                        if (this.errors[index]) {
                            this.errors[index].location = 'Please select at least one product for this delivery.';
                        } else {
                            this.errors[index] = { location: 'Please select at least one product for this delivery.' };
                        }
                        return;
                    }

                    if (!city || !province || products?.length === 0 || products == undefined) return;

                    try {
                        const response = await fetch('{{ route('cart.front.get_shipping_fee_for_multiple_address_new') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ locations: [{ city, province }], products }),
                        });

                        if (!response.ok) throw new Error('Network error');

                        const data = await response.json()
                        const fee = parseFloat(data.fee || 0);

                        this.deliveryFees[index] = { 
                            location: city + ', ' + province, 
                            fee, 
                            isBaka: data.has_baka, 
                            lechon_baka_service: 
                            data.lechon_baka_service_total
                        };

                        delivery.delivery_fee = fee;
                        delivery.isBaka = data.has_baka;
                        delivery.lechon_baka_service = data.lechon_baka_service_total;

                    this.deliveryFee = this.deliveries.reduce((sum, d) =>
                        sum + parseFloat(d.delivery_fee || 0) + parseFloat(d.lechon_baka_service || 0), 0);

                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    this.refreshDeliveryCouponPopup()
                        
                    } catch (e) {
                        console.error(`Failed to fetch delivery fee for ${city + ', ' + province}`, e);
                        delivery.delivery_fee = 0;
                        delivery.isBaka = false;
                        delivery.lechon_baka_service = 0;
                    }

                   
                },

                onChangeMultipleAddress() {

                    if (!this.allowMultiple) {
                        // Reset to single delivery structure
                        this.deliveries = [{
                            address: '',
                            city: '',
                            province: '',
                            location: '',
                            name: '',
                            phone: '',
                            need_date: '',
                            need_time: '',
                            note: '',
                            delivery_fee: 0,
                            orders: [],
                            errors: {},
                            isEditingAddress: false,
                            street: '',
                            sms: false,
                            cochinillo_warning: false,
                            paella: false,
                            isBaka: false,
                            lechon_baka_service: 0,
                        }]

                        this.deliveryFee = 0

                        if (this.method === 'delivery') {
                            this.allowMultiple = false

                            this.need_time = ''
                            this.availableDeliveryHours = []
                        }

                        this.isBaka = window.hasBaka;
                        this.lechonBakaService = this.isBaka ? window.lechonBakaService : 0;
                    } else {
                        this.isBaka = false;
                        this.lechonBakaService = 0;
                    }
                
                    this.deliveryFees = [];
                    this.deliveryFee = 0;

                    this.applyAutoCoupons()
                    this.recomputeCouponTotals()
                    
                },

                orders: @json($carts),

                isOrderChecked(delivery, order) {
                    const key = this.getOrderKey(order)

                    return delivery.orders?.some(o =>
                       this.getDeliveryOrderKey(o) === key
                    );
                },

                onOrderCheckToggle(deliveryIndex, delivery, order, checked) {

                    if (!delivery.orders) delivery.orders = []

                    const remaining = this.getRemainingQty(order)
                    const key = this.getOrderKey(order)

                    const hasPaella = parseFloat(order.paella_price) > 0;
                    const isPaella = parseFloat(order.paella_price) > 0;
                    const isFree = !!order.is_free_product;

                    if (checked && remaining <= 0) return

                    const orderIndex = delivery.orders.findIndex(o =>
                        this.getDeliveryOrderKey(o) === key
                    )

                    if (checked && orderIndex === -1) {
                        delivery.orders.push({
                            product_id: order.product_id,
                            paella: parseFloat(order.paella_price || 0) > 0,
                            isBaka: order.product_id === 178,
                            is_free_product: !!order.is_free_product,
                            qty: 1,
                            product: order.product,
                            product_name:
                                parseFloat(order.paella_price || 0) > 0
                                    ? order.product.name + ' Boneless with Paella'
                                    : order.product.name,
                            price: parseFloat(order.price || 0),
                        })
                    }

                    if (!checked && orderIndex !== -1) {
                        delivery.orders.splice(orderIndex, 1)
                    }

                    this.$nextTick(() => {
                        this.cleanupEmptyDeliveries()
                        this.populateMultiDeliveryTimes(deliveryIndex)
                    })
                },


                getSelectedQty(delivery, order) {
                    const key = this.getOrderKey(order)

                    const found = delivery.orders?.find(o =>
                        this.getDeliveryOrderKey(o) === key
                    )

                    return found ? found.qty : 1
                },

                updateSelectedQty(delivery, order, newQty) {
                    const key = this.getOrderKey(order)

                    const found = delivery.orders?.find(o =>
                        this.getDeliveryOrderKey(o) === key
                    )

                    if (found) {
                        found.qty = parseInt(newQty)
                    }

                    this.$nextTick(() => {
                        this.cleanupEmptyDeliveries()
                    })

                    // this.populateMultiDeliveryTimes(index)
                },

                getAvailableQtyForDropdown(delivery, order) {

                    const totalQty = parseInt(order.qty)
                    const key = this.getOrderKey(order)

                    const assignedQty = this.deliveries.reduce((sum, d) => {

                        const found = d.orders?.find(o =>
                            this.getDeliveryOrderKey(o) === key
                        )

                        return sum + (found ? parseInt(found.qty) : 0)

                    }, 0)

                    const currentQty = this.getSelectedQty(delivery, order)

                    const remaining = totalQty - assignedQty + currentQty

                    return Array.from({
                        length: remaining
                    }, (_, i) => i + 1)
                },

                getAssignedQty(order) {
                    const key = this.getOrderKey(order)

                    return this.deliveries.reduce((sum, delivery) => {

                        const found = delivery.orders?.find(o =>
                            this.getDeliveryOrderKey(o) === key
                        )

                        return sum + (found ? parseInt(found.qty) : 0)

                    }, 0)
                },


                getRemainingQty(order) {

                    const total = parseInt(order.qty)
                    const assigned = this.getAssignedQty(order)

                    return total - assigned
                },

                hasRemainingOrders() {

                    return this.orders.some(order =>
                        this.getRemainingQty(order) > 0
                    )
                },


                validateBeforeAddDelivery() {

                    const lastDelivery = this.deliveries[this.deliveries.length - 1]

                    const isValid = this.validateDeliveryFields(lastDelivery)

                    if (!isValid) {
                        this.$nextTick(() => {
                            const firstError = document.querySelector('.border-red-500')
                            if (firstError) {
                                firstError.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                })
                            }
                        })
                        return
                    }

                    if (!this.hasRemainingOrders()) {
                        return
                    }

                    this.errors.unused = ''

                    this.deliveries.push({
                        address: '',
                        province: '',
                        city: '',
                        location: '',
                        name: '',
                        phone: '',
                        need_date: '',
                        need_time: '',
                        note: '',
                        delivery_fee: 0,
                        orders: [],
                        errors: {},
                        isEditingAddress: false,
                        street: '',
                        sms: false,
                        cochinillo_warning: false,
                        paella: false,
                        isBaka: false,
                        lechon_baka_service: 0,
                    })
                },



                cleanupEmptyDeliveries() {

                    // If there are remaining orders, do nothing
                    if (this.hasRemainingOrders()) return

                    // Remove all deliveries that have no assigned orders
                    this.deliveries = this.deliveries.filter((delivery, index) => {

                        // Always keep first delivery
                        if (index === 0) return true

                        return delivery.orders && delivery.orders.length > 0
                    })
                },

                validateDeliveryFields(delivery) {

                    delivery.errors = {}

                    if (!delivery.address?.trim()) {
                        delivery.errors.address = 'Address is required.'
                    }

                    if (!delivery.province) {
                        delivery.errors.province = 'Province is required.'
                    }

                    if (!delivery.city) {
                        delivery.errors.city = 'City is required.'
                    }

                    if (!delivery.location) {
                        delivery.errors.location = 'Barangay is required.'
                    }

                    if (!delivery.name?.trim()) {
                        delivery.errors.name = 'Contact person is required.'
                    }

                    if (!delivery.phone?.trim()) {
                        delivery.errors.phone = 'Contact number is required.'
                    }

                    if (!delivery.orders || delivery.orders.length === 0) {
                        delivery.errors.orders = 'Please assign at least one order.'
                    }

                    if (!delivery.need_date) {
                        delivery.errors.need_date = 'Please select a date.'
                    }

                    if (!delivery.need_time) {
                        delivery.errors.need_time = 'Please select a time.'
                    }

                    return Object.keys(delivery.errors).length === 0
                },

                clearDeliveryFieldError(delivery, field) {

                    if (!delivery.errors) return

                    if (delivery.errors[field]) {
                        delete delivery.errors[field]
                    }
                },

                getTodayFormatted() {

                    const today = new Date()

                    const year = today.getFullYear()
                    const month = String(today.getMonth() + 1).padStart(2, '0')
                    const day = String(today.getDate()).padStart(2, '0')

                    return `${year}-${month}-${day}`
                },

                getRequiredProcessingHours() {

                    let hours = 0

                    if (window.hasLechon) {
                        hours = Math.max(hours, parseInt(window.minimum_processing_hours || 24))
                    }

                    if (window.hasMisc) {
                        hours = Math.max(hours, parseInt(window.minimum_processing_hours_misc || 12))
                    }

                    if (window.hasBaka) {
                        hours = Math.max(hours, parseInt(window.minimum_processing_hours_baka || 72))
                    }

                    return hours
                },

                

                calculateEarliestBusinessTime(processingHours) {

                    const now = new Date()
                    let current = new Date(now)

                    // move to opening if outside hours
                    if (current.getHours() >= this.closeHour) {
                        current.setDate(current.getDate() + 1)
                        current.setHours(this.openHour, 0, 0, 0)
                    } else if (current.getHours() < this.openHour) {
                        current.setHours(this.openHour, 0, 0, 0)
                    }

                    let remainingHours = processingHours

                    while (remainingHours > 0) {

                        const endOfDay = new Date(current)
                        endOfDay.setHours(this.closeHour, 0, 0, 0)

                        const availableToday =
                            (endOfDay - current) / (1000 * 60 * 60)

                        if (remainingHours <= availableToday) {
                            current.setHours(current.getHours() + remainingHours)
                            remainingHours = 0
                        } else {
                            remainingHours -= availableToday

                            current.setDate(current.getDate() + 1)
                            current.setHours(this.openHour, 0, 0, 0)
                        }
                    }

                    return current
                },

                getEarliestAllowedDateTime() {
                    const hours = this.getRequiredProcessingHours()
                    return this.calculateEarliestBusinessTime(hours)
                },

                adjustToOpeningHours(dateObj) {

                    let adjusted = new Date(dateObj)

                    const hour = adjusted.getHours()

                    // If before opening → move to 9AM
                    if (hour < this.openHour) {
                        adjusted.setHours(this.openHour, 0, 0, 0)
                    }

                    // If after closing move to next day 9AM
                    if (hour >= this.closeHour) {
                        adjusted.setDate(adjusted.getDate() + 1)
                        adjusted.setHours(this.openHour, 0, 0, 0)
                    }

                    return adjusted
                },

                getProcessingHoursForDelivery(delivery) {

                    let hours = 0

                    delivery.orders.forEach(order => {

                        if (order.product.id === 178) {
                            hours = Math.max(hours, parseInt(window.minimum_processing_hours_baka))
                        }

                        if (order.product.category_id === 1) {
                            hours = Math.max(hours, parseInt(window.minimum_processing_hours))
                        }

                        if (order.product.is_misc == 1) {
                            hours = Math.max(hours, parseInt(window.minimum_processing_hours_misc))
                        }

                    })

                    return hours
                },


                getEarliestDateTimeForDelivery(delivery) {

                    const now = new Date()

                    const processingHours = this.getProcessingHoursForDelivery(delivery)

                    let current = new Date(now)

                    // move to opening if outside hours
                    if (current.getHours() >= this.closeHour) {
                        current.setDate(current.getDate() + 1)
                        current.setHours(this.openHour, 0, 0, 0)
                    } else if (current.getHours() < this.openHour) {
                        current.setHours(this.openHour, 0, 0, 0)
                    }

                    let remainingHours = processingHours

                    while (remainingHours > 0) {

                        const endOfDay = new Date(current)
                        endOfDay.setHours(this.closeHour, 0, 0, 0)

                        const availableToday =
                            (endOfDay - current) / (1000 * 60 * 60)

                        if (remainingHours <= availableToday) {
                            current.setHours(current.getHours() + remainingHours)
                            remainingHours = 0
                        } else {
                            remainingHours -= availableToday

                            current.setDate(current.getDate() + 1)
                            current.setHours(this.openHour, 0, 0, 0)
                        }
                    }

                    return current
                },

                getProcessingHoursFromSelectedOrders() {

                    let hours = 0

                    this.orders.forEach(order => {

                        if (!order.checked) return

                        if (order.product.id === 178) {
                            hours = Math.max(hours, parseInt(window.minimum_processing_hours_baka))
                        }

                        if (order.product.category_id === 1) {
                            hours = Math.max(hours, parseInt(window.minimum_processing_hours))
                        }

                        if (order.product.is_misc == 1) {
                            hours = Math.max(hours, parseInt(window.minimum_processing_hours_misc))
                        }

                    })

                    return hours
                },


                getCartProcessingHours() {

                    let hours = 0

                    window.initialCarts.forEach(cart => {

                        if (cart.product.slug === 'lechon-baka') {
                            hours = Math.max(hours, parseInt(window.minimum_processing_hours_baka))
                        }

                        if (cart.product.category_id === 1) {
                            hours = Math.max(hours, parseInt(window.minimum_processing_hours))
                        }

                        if (cart.product.is_misc == 1) {
                            hours = Math.max(hours, parseInt(window.minimum_processing_hours_misc))
                        }

                    })

                    return hours
                },

                getEarliestForPickupAndSingle() {
                    const hours = this.getCartProcessingHours()
                    return this.calculateEarliestBusinessTime(hours)
                },

                getProductName(productId) {
                    const item = this.carts.find(c => c.product_id === productId)    

                    return item?.product?.name ?? ''
                },

                rebuildSingleDeliveryAddress() {

                    const street = this.delivery_address_street || ''

                    const parts = [
                        street,
                        this.location || '',
                        this.city || '',
                        this.province || ''
                    ].filter(Boolean)

                    this.delivery_address = parts.join(', ')
                },

                startEditingAddress() {
                    this.isEditingAddress = true

                    // Strip appended parts while editing
                    this.delivery_address = this.delivery_address_street
                },

                onAddressInput() {
                    this.delivery_address_street = this.delivery_address
                },

                finishEditingAddress() {
                    this.isEditingAddress = false
                    this.rebuildAddress()
                },

                rebuildAddress() {

                    if (this.isEditingAddress) return

                    const parts = []

                    if (this.delivery_address_street)
                        parts.push(this.delivery_address_street)

                    if (this.location)
                        parts.push(this.location)

                    if (this.city)
                        parts.push(this.city)

                    if (this.province)
                        parts.push(this.province)

                    this.delivery_address = parts.join(', ')
                },

                startMultiEditing(index) {

                    const d = this.deliveries[index]

                    d.isEditingAddress = true

                    // Remove appended parts while editing
                    d.address = d.street
                },

                onMultiAddressInput(index) {

                    const d = this.deliveries[index]

                    d.street = d.address
                },

                finishMultiEditing(index) {

                    const d = this.deliveries[index]

                    d.isEditingAddress = false

                    this.rebuildMultiAddress(index)
                },

                rebuildMultiAddress(index) {

                    const d = this.deliveries[index]

                    if (d.isEditingAddress) return

                    const parts = []

                    if (d.street)
                        parts.push(d.street)

                    if (d.location)
                        parts.push(d.location)

                    if (d.city)
                        parts.push(d.city)

                    if (d.province)
                        parts.push(d.province)

                    d.address = parts.join(', ')
                },

                getOrderKey(order) {
                    const isPaella = parseFloat(order.paella_price || 0) > 0
                    const isFree = !!order.is_free_product

                    return `${order.product_id}_${isPaella ? 1 : 0}_${isFree ? 1 : 0}`
                },

                getDeliveryOrderKey(o) {
                    return `${o.product_id}_${o.paella ? 1 : 0}_${o.is_free_product ? 1 : 0}`
                },

                formatTime(time) {
                    if (!time) return '';

                    const [h, m] = time.split(':');
                    const hour24 = parseInt(h, 10);

                    const hour12 = ((hour24 + 11) % 12) + 1;
                    const suffix = hour24 >= 12 ? 'PM' : 'AM';

                    return `${hour12}:${m} ${suffix}`;
                },

                canAgree: false,

                checkScroll() {
                    const el = this.$refs.policyContent;

                    const scrolledToBottom =
                        el.scrollTop + el.clientHeight >= el.scrollHeight - 5;

                    if (scrolledToBottom) {
                        this.canAgree = true;
                    }
                },

                async getBlockDatesForMulti(orders, index) {
                    this.blockedDetails = [];

                    let delivery = this.deliveries[index];
                    
                    const cartProductIds = orders.map(i => i.product_id);

                    let citiesProvince = {};

                    if (delivery.city && delivery.province) {
                        citiesProvince = {
                            province: delivery.province,
                            city: delivery.city
                        }
                    }

                    const response = await fetch('{{ route('checkout.blocks') }}',  {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({
                            product_ids: cartProductIds,
                            ...citiesProvince
                        })
                    });

                    const blocks = await response.json();

                    delivery.blockedDetails = Array.isArray(blocks) ? blocks : [];

                    this.$nextTick(() => {
                        this.$nextTick(() => {

                            const el = this.deliveries[index]?._el;

                            if (!el) return;

                            this.initMultiDeliveryDatepicker(el, index);

                        });
                    });
                },

                async getBlockDates(isDelivery = false) {
                    const cartProductIds = this.carts.map(i => i.product_id);

                    let citiesProvince = {};

                    if  (isDelivery) {
                        if (!this.province || !this.city) return

                        citiesProvince = {
                            province: this.province,
                            city: this.city
                        }
                    }

                    const response = await fetch('{{ route('checkout.blocks') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({
                            product_ids: cartProductIds,
                            location: this.method === 'pickup' ? this.pickup_branch : '',
                            ...citiesProvince
                        })
                    });

                    const blocks = await response.json();

                    this.blockedDetails = Array.isArray(blocks) ? blocks : [];

                    // FORCE DATEPICKER REFRESH
                    this.$nextTick(() => {

                        if (this.$refs.pickupDate?._datepicker) {
                            this.$refs.pickupDate._datepicker.update();
                        }

                        if (this.$refs.deliveryDate?._datepicker) {
                            this.$refs.deliveryDate._datepicker.update();
                        }

                    });
                },

                blockedDetails: [],
                blockModal: false,

                closeBlockModal() {
                    this.blockModal = false
                },

                blockAppliesToDelivery(block, delivery) {

                    const cartProductIds = delivery.orders.map(i => i.product_id)
                    const cartCategoryIds = delivery.orders.map(i => i.product.category_id)

                    const matchProduct =
                        !block.products?.length ||
                        block.products.some(p => cartProductIds.includes(p.id))

                    const matchCategory =
                        !block.categories?.length ||
                        block.categories.some(c => cartCategoryIds.includes(c.id))

                    const matchLocation =
                        !block.locations?.length ||
                        block.locations.some(loc => loc.name === this.pickup_branch)

                    const matchCity =
                        !block.cities?.length ||
                        block.cities.some(c =>
                            c.province === delivery.province &&
                            c.city === delivery.city
                        )

                    const matchGeo = matchLocation || matchCity

                    return matchProduct && matchCategory && matchGeo
                },
                
                isBlockedWithCombo(block) {

                    if (!block.combo_products?.length) return true

                    const cartProductIds = this.carts.map(i => i.product_id)
                    const cartCategoryIds = this.carts.map(i => i.product.category_id)

                    const comboProductMatch =
                        block.combo_products?.some(p => cartProductIds.includes(p.id))

                    const comboCategoryMatch =
                        block.combo_categories?.some(c => cartCategoryIds.includes(c.id))

                    return !(comboProductMatch || comboCategoryMatch)
                },

                blockAppliesToCart(block) {

                    const cartProductIds = this.carts.map(i => i.product_id)
                    const cartCategoryIds = this.carts.map(i => i.product.category_id)

                    // PRODUCT
                    const matchProduct =
                        !block.products?.length ||
                        block.products.some(p => cartProductIds.includes(p.id))

                    // CATEGORY
                    const matchCategory =
                        !block.categories?.length ||
                        block.categories.some(c => cartCategoryIds.includes(c.id))

                    // LOCATION
                    const matchLocation =
                        !block.locations?.length ||
                        block.locations.some(loc => loc.name === this.pickup_branch)

                    // CITY
                    const matchCity =
                        !block.cities?.length ||
                        block.cities.some(c =>
                            c.province === this.province &&
                            c.city === this.city
                        )

                    const matchGeo = matchLocation || matchCity

                    return matchProduct && matchCategory && matchGeo
                },

                blockAppliesToMethod(block) {

                    if (block.block_type === 'both') return true

                    return block.block_type === this.method
                },

                getBlockedTimeRangesForDate(date) {
                return this.blockedDetails.filter(b =>
                    b.date === date &&
                    this.blockAppliesToCart(b) &&
                    this.blockAppliesToMethod(b) &&
                    this.isBlockedWithCombo(b) &&
                    b.is_all_day == 0
                )
                },

                normalizeTime(timeStr) {
                    if (!timeStr) return null
                    return timeStr.substring(0,5) // from "11:00:00" to "11:00"
                },

                
                getNextAvailableDate(startDate, blocks = this.blockedDetails, applies = null) {
                let date = new Date(startDate)

                while (true) {
                    const formatted = this.formatDate(date)

                    const blockedForThisDate = (blocks || []).filter(b => {
                        if (b.date !== formatted) return false
                        if (b.is_all_day != 1) return false

                        return typeof applies === 'function' ? applies(b) : true
                    })

                    if (!blockedForThisDate.length) {
                        return date
                    }

                    date.setDate(date.getDate() + 1)
                }
            },

                get incompleteProgress() {
                    if (!this.contact.name || !this.contact.mobile || !this.contact.email) {
                        return true
                    }

                    if (this.isGuest && !this.privacy) {
                        return true
                    }
                        
                    // ==========================
                    // PICKUP
                    // ==========================
                    if (this.method === 'pickup') {

                        if (!this.pickup_branch) return true
                        if (!this.need_date) return true
                        if (!this.need_time) return true

                        return false
                    }

                    // ==========================
                    // SINGLE DELIVERY
                    // ==========================
                    if (this.method === 'delivery' && !this.allowMultiple) {

                        if (!this.delivery_address) return true
                        if (!this.province) return true
                        if (!this.city) return true
                        if (!this.location) return true

                        if (!this.need_date) return true
                        if (!this.need_time) return true

                        return false
                    }

                    // ==========================
                    // MULTI DELIVERY
                    // ==========================
                    if (this.method === 'delivery' && this.allowMultiple) {

                        // must assign all orders
                        if (this.hasRemainingOrders()) return true

                        for (let d of this.deliveries) {

                            if (!d.orders || d.orders.length === 0) return true

                            if (!d.address) return true
                            if (!d.province) return true
                            if (!d.city) return true
                            if (!d.location) return true

                            if (!d.name) return true
                            if (!d.phone) return true

                            if (!d.need_date) return true
                            if (!d.need_time) return true
                        }

                        return false
                    }

                    return true
                }

            }
        }
    </script>

    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || performance.getEntriesByType('navigation')[0].type === 'back_forward') {
                location.reload();
            }
        });
    </script>
@endsection
