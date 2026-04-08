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
            $autoCoupons = isset($autoCoupons) ? $autoCoupons : collect([]);
             $allCoupons = $eligibleCoupons->merge($autoCoupons);
    @endphp

    <div class="bg-cream">
        <div x-data="checkoutForm()" x-init="init()" class="container">
            <form id="checkoutForm" method="POST" action="{{ route('cart.temp_sales') }}" @submit.prevent="submitForm"
                class="pb-20 px-4">
                @csrf
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
                            <div>
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
                        </div>

                        {{-- RIGHT --}}
                        <div class="w-full lg:w-2/5 order-1 lg:order-2">
                            @include('v2.checkout.components.order-summary')
                        </div>
                    </div>
                @endif
            </form>

            @include('v2.checkout.modals.coupon-modal')
            @include('v2.checkout.modals.privacy-modal')
            @include('v2.checkout.modals.payment-modal')
            @include('v2.checkout.modals.block-modal')
        </div>
    </div>


    <x-footer-component />

@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker.min.js"></script>

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
                totalDiscountAmount: 0,
                shippingDiscountAmount: 0,
                shippingDiscountLists: [],
                giftChequeCode: '',
                giftChequeMessage: '',
                giftChequeMessageType: '',
                appliedGiftCheque: null,
                giftChequeDiscountAmount: 0,
                
                couponTypeLabel(coupon) {
                    if (!coupon) return '';

                    if (coupon.free_shipping || coupon.reward === 'free-shipping-optn') {
                        return 'Free Shipping';
                    }

                    if (coupon.reward === 'discount-percentage-optn' || coupon.discount_type === 'percent') {
                        return 'Percentage Discount';
                    }

                    if (coupon.reward === 'discount-amount-optn' || coupon.discount_type === 'amount') {
                        return 'Fixed Amount Discount';
                    }

                    if (Array.isArray(coupon.free_products) && coupon.free_products.length > 0) {
                        return 'Free Product';
                    }

                    return 'Coupon';
                },

                couponWorthLabel(coupon) {
                    if (!coupon) return '';

                    if (coupon.free_shipping || coupon.reward === 'free-shipping-optn') {
                        return 'Free Shipping';
                    }

                    if (coupon.reward === 'discount-percentage-optn' || coupon.discount_type === 'percent') {
                        return `${Number(coupon.discount || 0)}% off`;
                    }

                    if (coupon.reward === 'discount-amount-optn' || coupon.discount_type === 'amount') {
                        return this.formatMoney(coupon.discount || 0);
                    }

                    if (Array.isArray(coupon.free_products) && coupon.free_products.length > 0) {
                        return `${coupon.free_products.length} free item(s)`;
                    }

                    return this.formatMoney(coupon.discount || 0);
                },

                couponExpiryLabel(coupon) {
                    if (!coupon?.end_date) return 'N/A';

                    return coupon.end_time
                        ? `${coupon.end_date} ${coupon.end_time}`
                        : coupon.end_date;
                },

                normalizeCoupon(coupon) {
                    const reward = String(coupon.reward ?? coupon.coupon_type ?? '').trim().toLowerCase();
                    const discountType = String(coupon.discount_type ?? '').trim().toLowerCase();
                    const activationType = String(coupon.activation_type ?? '').trim().toLowerCase();

                    return {
                        ...coupon,
                        id: coupon.id ?? null,
                        code: String(coupon.code ?? coupon.coupon_code ?? '').trim(),
                        name: coupon.name ?? coupon.coupon_name ?? coupon.coupon_code ?? 'Coupon',
                        reward,
                        activation_type: activationType,
                        location: coupon.location ?? coupon.locations ?? '',
                        free_shipping:
                            !!coupon.free_shipping ||
                            reward === 'free-shipping-optn' ||
                            reward === 'free_shipping' ||
                            reward === 'free-shipping' ||
                            discountType === 'free_shipping' ||
                            discountType === 'free-shipping',
                        end_date: coupon.end_date ?? '',
                        end_time: coupon.end_time ?? '',
                        description: coupon.description ?? '',
                        auto_applied:
                            coupon.auto_applied === true ||
                            coupon.auto_applied === 1 ||
                            coupon.auto_applied === '1' ||
                            activationType === 'auto',
                        combination_allowed:
                            coupon.combination_allowed === true ||
                            coupon.combination_allowed === 1 ||
                            coupon.combination_allowed === '1',
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
                        free_products: Array.isArray(coupon.free_products)
                            ? coupon.free_products
                            : Object.values(coupon.free_products || {})
                    };
                },
                addFreeProductsFromCoupon(coupon) {
                if (!Array.isArray(coupon?.free_products) || !coupon.free_products.length) return

                coupon.free_products.forEach(fp => {
                    const existsInCart = this.carts.find(item =>
                        item.is_free_product &&
                        String(item.product_id) === String(fp.id) &&
                        item.coupon_code === coupon.code
                    )

                    if (!existsInCart) {
                        this.carts.push({
                            id: `free_${coupon.code}_${fp.id}`,
                            product_id: fp.id,
                            qty: 1,
                            price: 0,
                            paella_price: 0,
                            is_free_product: true,
                            coupon_code: coupon.code,
                            product: {
                                id: fp.id,
                                name: fp.name,
                                slug: fp.slug ?? '',
                                category_id: fp.category_id ?? null,
                                is_misc: fp.is_misc ?? 0,
                                paella_price: fp.paella_price ?? 0,
                                photos: fp.photos ?? []
                            }
                        })
                    }

                    const existsInOrders = this.orders.find(o =>
                        String(o.product_id) === String(fp.id) &&
                        o.is_free_product &&
                        o.coupon_code === coupon.code
                    )

                    if (!existsInOrders) {
                        this.orders.push({
                            id: `free_${coupon.code}_${fp.id}`,
                            product_id: fp.id,
                            qty: 1,
                            price: 0,
                            paella_price: 0,
                            is_free_product: true,
                            coupon_code: coupon.code,
                            product: {
                                id: fp.id,
                                name: fp.name,
                                slug: fp.slug ?? '',
                                category_id: fp.category_id ?? null,
                                is_misc: fp.is_misc ?? 0,
                                paella_price: fp.paella_price ?? 0,
                                photos: fp.photos ?? []
                            }
                        })
                    }
                })
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

                selectCoupon(coupon) {
                    this.selectedCoupon = this.normalizeCoupon(coupon);
                    this.couponCode = this.selectedCoupon.code ?? '';
                },

                clearCouponSelection() {
                    this.selectedCoupon = null;
                    this.couponCode = '';
                },

                isFreeShippingCoupon(coupon) {
                    const reward = String(coupon?.reward ?? '').trim().toLowerCase();
                    const discountType = String(coupon?.discount_type ?? '').trim().toLowerCase();

                    return !!(
                        coupon?.free_shipping ||
                        reward === 'free-shipping-optn' ||
                        reward === 'free_shipping' ||
                        reward === 'free-shipping' ||
                        discountType === 'free_shipping' ||
                        discountType === 'free-shipping'
                    );
                },

                normalizeText(value) {
                    return String(value ?? '')
                        .replace(/[\[\]"']/g, '')
                        .replace(/\s+/g, ' ')
                        .trim()
                        .toLowerCase();
                },

                getCouponLocations(coupon) {
                    const raw = coupon?.location ?? coupon?.locations ?? '';

                    if (Array.isArray(raw)) {
                        return raw.map(v => this.normalizeText(v)).filter(Boolean);
                    }

                    const text = String(raw || '').trim();
                    if (!text) return [];

                    if (text.startsWith('[') && text.endsWith(']')) {
                        try {
                            const parsed = JSON.parse(text);
                            if (Array.isArray(parsed)) {
                                return parsed.map(v => this.normalizeText(v)).filter(Boolean);
                            }
                        } catch (e) {}
                    }

                    return text
                        .split(/\r?\n|[|,]/)
                        .map(v => this.normalizeText(v))
                        .filter(Boolean);
                },

                couponMatchesLocation(coupon, city, location) {
                    const allowed = this.getCouponLocations(coupon);

                    if (!allowed.length) return true;
                    if (allowed.includes('all')) return true;

                    const normalizedLocation = this.normalizeText(location);
                    const normalizedCity = this.normalizeText(city);

                    if (normalizedLocation && allowed.includes(normalizedLocation)) return true;
                    if (normalizedCity && allowed.includes(normalizedCity)) return true;

                    return false;
                },

                getSelectedCouponTargets() {
                    if (this.method !== 'delivery') return [];

                    if (this.allowMultiple) {
                        return (this.deliveries || [])
                            .map(d => ({
                                city: d?.city ?? '',
                                location: d?.location ?? ''
                            }))
                            .filter(t => this.normalizeText(t.city) || this.normalizeText(t.location));
                    }

                    return [{
                        city: this.city ?? '',
                        location: this.location ?? ''
                    }].filter(t => this.normalizeText(t.city) || this.normalizeText(t.location));
                },

                removeInvalidLocationCoupons() {
                const targets = this.getSelectedCouponTargets();
                const removedCodes = [];

                this.coupons = this.coupons.filter(coupon => {
                    const normalized = this.normalizeCoupon(coupon);
                    const allowed = this.getCouponLocations(normalized);

                    if (!allowed.length) return true;
                    if (!targets.length) {
                        removedCodes.push(normalized.code);
                        return false;
                    }

                    const keep = targets.some(t =>
                        this.couponMatchesLocation(normalized, t.city, t.location)
                    );

                    if (!keep) {
                        removedCodes.push(normalized.code);
                    }

                    return keep;
                });

                if (removedCodes.length) {
                    removedCodes.forEach(code => this.removeFreeProductsByCoupon(code));

                    this.autoAppliedCoupons = this.autoAppliedCoupons.filter(c =>
                        !removedCodes.includes(c.code)
                    );

                    this.couponMessage = 'Invalid coupon removed because the selected location changed.';
                    this.couponMessageType = 'error';
                    this.order_amount = this.cartSubtotal();
                    this.recomputeCouponTotals();
                }
            },

                shouldAutoApplyCoupon(coupon) {
                    const isAuto =
                        coupon.auto_applied === true ||
                        String(coupon.activation_type || '').toLowerCase() === 'auto';

                    if (!isAuto) return false;

                    if (!this.isFreeShippingCoupon(coupon)) return true;

                    if (this.method !== 'delivery') return false;

                    const targets = this.getSelectedCouponTargets();
                    if (!targets.length) return false;

                    return targets.some(t =>
                        this.couponMatchesLocation(coupon, t.city, t.location)
                    );
                },

                getCouponDiscount(coupon) {
                    const subtotal = this.cartSubtotal();

                    if (!coupon) return 0;

                    if (this.isFreeShippingCoupon(coupon)) {
                        if (this.method !== 'delivery') return 0;

                        if (this.allowMultiple) {
                            return (this.deliveries || []).reduce((sum, delivery) => {
                                if (this.couponMatchesLocation(coupon, delivery.city, delivery.location)) {
                                    return sum + Number(delivery.delivery_fee || 0);
                                }
                                return sum;
                            }, 0);
                        }

                        return this.couponMatchesLocation(coupon, this.city, this.location)
                            ? Number(this.deliveryFee || 0)
                            : 0;
                    }

                    if (coupon.reward === 'discount-amount-optn' || coupon.discount_type === 'amount') {
                        return Math.min(Number(coupon.discount || 0), subtotal);
                    }

                    if (coupon.reward === 'discount-percentage-optn' || coupon.discount_type === 'percent') {
                        return subtotal * (Number(coupon.discount || 0) / 100);
                    }

                    return 0;
                },

                couponDiscountLabel(coupon) {
                    const amount = this.getCouponDiscount(coupon);

                    if (this.isFreeShippingCoupon(coupon)) {
                        return `- ${this.formatMoney(amount)} (Free Shipping)`;
                    }

                    return `- ${this.formatMoney(amount)}`;
                },

                applyAutoCoupons() {
                const autos = (this.autoCouponsSource || [])
                    .map(c => this.normalizeCoupon(c))
                    .filter(c => this.shouldAutoApplyCoupon(c))

                this.coupons = this.coupons.filter(c => !c.auto_applied)
                this.autoAppliedCoupons = []

                const autoCodes = (this.autoCouponsSource || [])
                    .map(c => this.normalizeCoupon(c).code)

                this.carts = this.carts.filter(item =>
                    !(item.is_free_product && autoCodes.includes(item.coupon_code))
                )

                this.orders = this.orders.filter(item =>
                    !(item.is_free_product && autoCodes.includes(item.coupon_code))
                )

                autos.forEach(autoCoupon => {
                    if (this.coupons.some(c =>
                        String(c.code || '').trim().toUpperCase() === String(autoCoupon.code || '').trim().toUpperCase()
                    )) return

                    if (!autoCoupon.combination_allowed && this.coupons.length > 0) return
                    if (autoCoupon.combination_allowed && this.coupons.some(c => !c.combination_allowed)) return

                    autoCoupon.auto_applied = true

                    this.coupons.push(autoCoupon)
                    this.autoAppliedCoupons.push(autoCoupon)
                    this.addFreeProductsFromCoupon(autoCoupon)
                })

                this.order_amount = this.cartSubtotal()
                this.recomputeCouponTotals()
            },

                confirmCouponSelection() {
                if (!this.selectedCoupon) return;

                const normalized = this.normalizeCoupon(this.selectedCoupon);

                if (this.isFreeShippingCoupon(normalized)) {
                    const targets = this.getSelectedCouponTargets();

                    if (!targets.length) {
                        this.couponMessage = 'Please select a delivery location first.';
                        this.couponMessageType = 'error';
                        this.closeCouponModal();
                        return;
                    }

                    const isValidForAnyTarget = targets.some(t =>
                        this.couponMatchesLocation(normalized, t.city, t.location)
                    );

                    if (!isValidForAnyTarget) {
                        this.couponMessage = 'This free shipping coupon is not valid for the selected location.';
                        this.couponMessageType = 'error';
                        this.closeCouponModal();
                        return;
                    }
                }

                if (this.coupons.find(c => c.code === normalized.code)) {
                    this.couponMessage = 'Coupon already applied.';
                    this.couponMessageType = 'error';
                    this.closeCouponModal();
                    return;
                }

                if (!normalized.combination_allowed && this.coupons.length > 0) {
                    this.couponMessage = 'This coupon cannot be combined with other coupons.';
                    this.couponMessageType = 'error';
                    return;
                }

                if (normalized.combination_allowed && this.coupons.some(c => !c.combination_allowed)) {
                    this.couponMessage = 'A coupon that does not allow combination has already been applied.';
                    this.couponMessageType = 'error';
                    return;
                }

                this.coupons.push(normalized)
                this.addFreeProductsFromCoupon(normalized)
                this.order_amount = this.cartSubtotal()
                this.recomputeCouponTotals()
                this.closeCouponModal()
            },

                applyCouponCode() {
                this.couponMessage = '';
                this.couponMessageType = '';

                const code = String(this.couponCode || '').trim().toUpperCase();

                if (!code) {
                    this.couponMessage = 'Please enter a coupon code.';
                    this.couponMessageType = 'error';
                    return;
                }

                const found = (this.availableCoupons || []).find(c =>
                    String(c.code ?? c.coupon_code ?? '').trim().toUpperCase() === code
                );

                if (!found) {
                    this.couponMessage = 'Invalid coupon code.';
                    this.couponMessageType = 'error';
                    return;
                }

                const normalized = this.normalizeCoupon(found);

                if (this.isFreeShippingCoupon(normalized)) {
                    const targets = this.getSelectedCouponTargets();

                    if (!targets.length) {
                        this.couponMessage = 'Please select a delivery location first.';
                        this.couponMessageType = 'error';
                        return;
                    }

                    const isValidForAnyTarget = targets.some(t =>
                        this.couponMatchesLocation(normalized, t.city, t.location)
                    );

                    if (!isValidForAnyTarget) {
                        this.couponMessage = 'This free shipping coupon is not valid for the selected location.';
                        this.couponMessageType = 'error';
                        return;
                    }
                }

                const alreadyApplied = this.coupons.find(c =>
                    String(c.code || '').trim().toUpperCase() === code
                );

                if (alreadyApplied) {
                    if (alreadyApplied.auto_applied) {
                        this.couponMessage = 'This coupon is already auto-applied.';
                    } else {
                        this.couponMessage = 'Coupon already applied.';
                    }
                    this.couponMessageType = 'error';
                    return;
                }

                if (!normalized.combination_allowed && this.coupons.length > 0) {
                    this.couponMessage = 'This coupon cannot be combined with other coupons.';
                    this.couponMessageType = 'error';
                    return;
                }

                if (normalized.combination_allowed && this.coupons.some(c => !c.combination_allowed)) {
                    this.couponMessage = 'A coupon that does not allow combination has already been applied.';
                    this.couponMessageType = 'error';
                    return;
                }

                this.coupons.push(normalized)
                this.addFreeProductsFromCoupon(normalized)
                this.couponMessage = 'Coupon applied successfully.';
                this.couponMessageType = 'success';
                this.couponCode = '';
                this.order_amount = this.cartSubtotal()
                this.recomputeCouponTotals()
            },

                removeCoupon(index) {
                const removed = this.coupons[index];
                this.coupons.splice(index, 1);

                this.autoAppliedCoupons = this.autoAppliedCoupons.filter(c => c.code !== removed?.code);
                this.removeFreeProductsByCoupon(removed?.code)

                this.order_amount = this.cartSubtotal();
                this.recomputeCouponTotals();
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
                        if (this.isFreeShippingCoupon(coupon) && this.method !== 'pickup') {
                            if (isMulti) {
                                (this.deliveries || []).forEach((delivery, idx) => {
                                    const feeRow = this.deliveryFees[idx] || {
                                        location: [delivery.city, delivery.province].filter(Boolean).join(', '),
                                        fee: Number(delivery.delivery_fee || 0),
                                        discount: 0
                                    };

                                    if (!this.deliveryFees[idx]) {
                                        this.deliveryFees[idx] = feeRow;
                                    }

                                    const fee = parseFloat(feeRow.fee || delivery.delivery_fee || 0);
                                    if (fee <= 0) return;

                                    if (!this.couponMatchesLocation(coupon, delivery.city, delivery.location)) return;

                                    const existingDiscount = parseFloat(this.deliveryFees[idx].discount || 0);
                                    const remainingFee = Math.max(fee - existingDiscount, 0);

                                    if (remainingFee <= 0) return;

                                    this.deliveryFees[idx].discount = existingDiscount + remainingFee;
                                    this.shippingDiscountAmount += remainingFee;

                                    this.shippingDiscountLists.push({
                                        location: feeRow.location,
                                        index: idx,
                                        discount: remainingFee,
                                        coupon_code: coupon.code
                                    });
                                });
                            } else {
                                if (this.couponMatchesLocation(coupon, this.city, this.location)) {
                                    this.shippingDiscountAmount += parseFloat(this.deliveryFee || 0);
                                }
                            }
                        } else {
                            this.totalDiscountAmount += this.getCouponDiscount(coupon);
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

                this.order_amount = this.cartSubtotal()
                this.applyAutoCoupons()
                this.recomputeCouponTotals()

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
                    if (!this.allowMultiple && this.hasBaka && this.lechonBakaService > 0) {
                        console.log('1111')
                    }
                     this.$watch('province', () => {
                    this.removeInvalidLocationCoupons();
                    this.recomputeCouponTotals();
                });

                this.$watch('city', () => {
                    this.removeInvalidLocationCoupons();
                    this.recomputeCouponTotals();
                });

                this.$watch('location', () => {
                    this.removeInvalidLocationCoupons();
                    this.recomputeCouponTotals();
                });

                this.$watch(() => JSON.stringify(this.deliveries || []), () => {
                    this.removeInvalidLocationCoupons();
                    this.recomputeCouponTotals();
                });
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

                this.need_date = parts.date
                picker.setDate(parts.date)

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
                        this.need_time = hours.length
                            ? this.formatHourValue(hours[0])
                            : ''
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
                        this.need_time = hours.length
                            ? this.formatHourValue(hours[0])
                            : ''
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
                this.applyAutoCoupons()
                this.recomputeCouponTotals()
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
                this.applyAutoCoupons()
                this.recomputeCouponTotals()
                },

                onBarangayChange() {
                    this.rebuildAddress()
                this.applyAutoCoupons()
                this.recomputeCouponTotals()
                },

                onMultiProvinceChange(index) {

                    const d = this.deliveries[index]

                    d.city = ''
                    d.location = ''

                    this.rebuildMultiAddress(index)
                this.applyAutoCoupons()
                this.recomputeCouponTotals()
                },

                onMultiCityChange(index) {

                    const d = this.deliveries[index]

                    d.location = ''

                    this.rebuildMultiAddress(index)
                this.applyAutoCoupons()
                this.recomputeCouponTotals()
                },

                onMultiBarangayChange(index) {
                    this.rebuildMultiAddress(index)
                this.applyAutoCoupons()
                this.recomputeCouponTotals()
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

                        const couponPayload = this.coupons.map(coupon => ({
                        code: coupon.code,
                        name: coupon.name,
                        reward: coupon.reward,
                        free_shipping: !!coupon.free_shipping,
                        free_products: coupon.free_products || [],
                        discount_used: Number(this.getCouponDiscount(coupon) || 0)
                    }));

                        let payload = {
                            name: this.contact.name,
                            mobile: this.contact.mobile,
                            email: this.contact.email,
                            agent: this.contact.agent,
                            shipping_type: this.method,
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

                async getDeliveryFee() {

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
                        this.applyAutoCoupons()
                        this.recomputeCouponTotals()
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


