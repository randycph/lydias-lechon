@extends('layouts.guest', ['page' => $page])

@section('title', 'Checkout')
@section('meta_description', 'Complete your order at Lydia\'s Lechon. Review your cart, choose delivery or pickup, and
finalize your purchase for a delicious meal.')



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

<div class="bg-cream">
    <div x-data="checkoutForm()" x-init="init()" class="container">
        <form id="checkoutForm" method="POST" action="{{ route('cart.temp_sales') }}" @submit.prevent="submitForm"
            class="pb-20 px-4">
            @csrf

            {{-- @include('v2.checkout.components.header', ['title' => "Delivery Information"]) --}}

            <div class="flex flex-col lg:flex-row gap-4 mt-10">
                {{-- LEFT --}}
                <div class="w-full lg:w-2/3 space-y-4">
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
                    @include('v2.checkout.components.place-order')
                </div>

                {{-- RIGHT --}}
                <div class="w-full lg:w-1/3">
                    @include('v2.checkout.components.order-summary')
                </div>
            </div>
        </form>

        @include('v2.checkout.modals.coupon-modal')
        @include('v2.checkout.modals.privacy-modal')
        @include('v2.checkout.modals.payment-modal')
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
</script>

<script>
    function checkoutForm() {
        return {

                /* ==========================
                * STATE
                * ========================== */
                carts: window.initialCarts || [],
                coupons: [],
                method: 'pickup',
                allowMultiple: false,
                deliveryFee: 0,
                deliveryFees: [],

                /* ==========================
                PICKUP STATE
                ========================== */

                pickup_branch: '',
                pickup_date: '',
                pickup_time: '',
                pickup_note: '',
                pickupErrors: {},
                pickupWarning: '',


                /* ==========================
                * FORMATTERS
                * ========================== */
                formatMoney(value) {
                    return '₱' + (parseFloat(value) || 0)
                        .toLocaleString(undefined, { minimumFractionDigits: 2 })
                },

                /* ==========================
                * COMPUTED GETTERS
                * ========================== */
                get formattedSubtotal() {
                    const total = this.carts.reduce((sum, item) => {
                        const qty = Number(item.qty) || 1
                        const base = item.is_free_product ? 0 : Number(item.price) || 0
                        const paella = item.paella_price > 0
                            ? Number(item.product?.paella_price || 0)
                            : 0

                        return sum + ((base + paella) * qty)
                    }, 0)

                    return this.formatMoney(total)
                },

                /* ==========================
                * HELPERS
                * ========================== */
                itemLineTotal(item) {
                    if (item.is_free_product) return '₱0.00'

                    const qty = Number(item.qty) || 1
                    const base = Number(item.price) || 0
                    const paella = item.paella_price > 0
                        ? Number(item.product?.paella_price || 0)
                        : 0

                    return this.formatMoney((base + paella) * qty)
                },

                itemImage(item) {
                    return item?.product?.photos?.length
                        ? item.product.photos[item.product.photos.length - 1].url
                        : '/images/no-image.jpg'
                },

                couponDiscountLabel(coupon) {
                    if (coupon.free_shipping) return 'Shipping Discount'
                    if (coupon.discount_type === 'amount') {
                        return '- ' + this.formatMoney(coupon.discount)
                    }
                    if (coupon.discount_type === 'percent') {
                        return `- ${coupon.discount}%`
                    }
                    return ''
                },

                /* ==========================
                * TOTAL
                * ========================== */
                computeTotal() {
                    let total = this.carts.reduce((sum, item) => {
                        const qty = Number(item.qty) || 1
                        const base = item.is_free_product ? 0 : Number(item.price) || 0
                        const paella = item.paella_price > 0
                            ? Number(item.product?.paella_price || 0)
                            : 0
                        return sum + ((base + paella) * qty)
                    }, 0)

                    if (this.method === 'delivery' && !this.allowMultiple) {
                        total += this.deliveryFee || 0
                    }

                    if (this.allowMultiple) {
                        total += this.deliveryFees.reduce((s, d) =>
                            s + (d.fee - (d.discount || 0)), 0)
                    }

                    // coupon effects already reflected in deliveryFees / discounts
                    return this.formatMoney(total)
                },

                changeMethod(type) {
                    this.method = type

                    // Reset delivery-specific state safely
                    if (type === 'pickup') {
                        this.allowMultiple = false
                        this.deliveryFee = 0
                        this.deliveryFees = []
                    }

                    if (type === 'delivery') {
                        // Optional defaults
                        if (!this.need_date) this.autoSelectEarliestDate?.()
                    }
                },


                /* ==========================
                PICKUP HOURS
                ========================== */

                get availablePickupHours() {
                    if (!this.pickup_date) return []

                    return this.allHours.filter(hour =>
                        !this.isTimeDisabled(hour)
                    )
                },

                formatHourValue(hour) {
                    return (hour < 10 ? '0' + hour : hour) + ':00'
                },

                /* ==========================
                EVENTS
                ========================== */

                onPickupBranchChange() {
                    this.pickupErrors.branch = ''
                },

                validatePickupDateTime() {
                    this.pickupErrors.date = ''
                    this.pickupErrors.time = ''

                    if (!this.pickup_date) {
                        this.pickupErrors.date = 'Please select a date.'
                    }

                    if (!this.pickup_time) {
                        this.pickupErrors.time = 'Please select a time.'
                    }
                },

                /* ==========================
                SINGLE DELIVERY STATE
                ========================== */

                delivery_address: '',
                province: '',
                city: '',
                location: '',
                need_date: '',
                need_time: '',
                instruction: '',

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
                },

                onCityChange() {
                    this.location = ''
                    this.getDeliveryFee?.()
                    this.validateSingleDeliveryField('city')
                },

                deliveries: [
                    {
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
                        delivery_fee: 0
                    }
                ],

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
                        delivery_fee: 0
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
                    name: '',
                    mobile: '',
                    email: '',
                    agent: ''
                },

                note: '',
                privacy: false,

                isSubmitting: false,
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

                validateBeforeSubmit() {

                    let valid = true
                    this.errors = {}

                    /* =========================
                    CONTACT VALIDATION
                    ========================== */

                    if (!this.contact?.name) {
                        this.errors.name = 'Name is required.'
                        valid = false
                    }

                    if (!this.contact?.mobile) {
                        this.errors.mobile = 'Mobile number is required.'
                        valid = false
                    }

                    if (!this.contact?.email) {
                        this.errors.email = 'Email is required.'
                        valid = false
                    }

                    /* =========================
                    PICKUP VALIDATION
                    ========================== */

                    if (this.method === 'pickup') {

                        if (!this.pickup_branch) {
                            this.errors.pickup_branch = 'Please select a branch.'
                            valid = false
                        }

                        if (!this.pickup_date) {
                            this.errors.pickup_date = 'Please select a date.'
                            valid = false
                        }

                        if (!this.pickup_time) {
                            this.errors.pickup_time = 'Please select a time.'
                            valid = false
                        }
                    }

                    /* =========================
                    SINGLE DELIVERY VALIDATION
                    ========================== */

                    if (this.method === 'delivery' && !this.allowMultiple) {

                        if (!this.delivery_address) {
                            this.errors.delivery_address = 'Address is required.'
                            valid = false
                        }

                        if (!this.province) {
                            this.errors.province = 'Province is required.'
                            valid = false
                        }

                        if (!this.city) {
                            this.errors.city = 'City is required.'
                            valid = false
                        }

                        if (!this.need_date) {
                            this.errors.need_date = 'Please select a date.'
                            valid = false
                        }

                        if (!this.need_time) {
                            this.errors.need_time = 'Please select a time.'
                            valid = false
                        }
                    }

                    /* =========================
                    MULTI DELIVERY VALIDATION
                    ========================== */

                    if (this.method === 'delivery' && this.allowMultiple) {

                        this.deliveries.forEach((delivery, index) => {

                            if (!delivery.address) {
                                if (!this.errors[index]) this.errors[index] = {}
                                this.errors[index].address = 'Address required'
                                valid = false
                            }

                            if (!delivery.need_date) {
                                if (!this.errors[index]) this.errors[index] = {}
                                this.errors[index].need_date = 'Date required'
                                valid = false
                            }

                            if (!delivery.need_time) {
                                if (!this.errors[index]) this.errors[index] = {}
                                this.errors[index].need_time = 'Time required'
                                valid = false
                            }

                            if (!delivery.name) {
                                if (!this.errors[index]) this.errors[index] = {}
                                this.errors[index].name = 'Name required'
                                valid = false
                            }

                            if (!delivery.phone) {
                                if (!this.errors[index]) this.errors[index] = {}
                                this.errors[index].phone = 'Phone required'
                                valid = false
                            }

                            if (!delivery.orders || delivery.orders.length === 0) {
                                if (!this.errors[index]) this.errors[index] = {}
                                this.errors[index].orders = 'Assign at least one order.'
                                valid = false
                            }

                        })
                    }

                    /* =========================
                    PRIVACY VALIDATION (guest)
                    ========================== */

                    if (this.isGuest && !this.privacy) {
                        this.errors.privacy = 'You must agree to the privacy policy.'
                        valid = false
                    }

                    return valid
                },


                submitForm() {

                    if (this.isSubmitting) return

                    this.isSubmitting = true

                    const isValid = this.validateBeforeSubmit()

                    if (!isValid) {
                        this.isSubmitting = false
                        return
                    }

                    // Submit normal form
                    this.$nextTick(() => {
                        document.getElementById('checkoutForm').submit()
                    })
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
                    order.value = this.paymentDetails.order_number

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




            }
    }
</script>

<script>
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || performance.getEntriesByType('navigation')[0].type === 'back_forward') {
            location.reload();
        }
    });
</script>

@endsection

@section('alpine.plugins')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
@endsection

