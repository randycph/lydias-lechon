@extends('layouts.guest', ['page' => $page])

@section('title', 'Checkout')
@section('meta_description',
    'Complete your order at Lydia\'s Lechon. Review your cart, choose delivery or pickup, and
    finalize your purchase for a delicious meal.')


@section('alpine.plugins')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
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
                {{-- @include('v2.checkout.components.header', ['title' => "Delivery Information"]) --}}

                <div class="flex flex-col lg:flex-row gap-4 mt-10">
                    {{-- LEFT --}}
                    <div class="w-full lg:w-3/5 space-y-4">
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
                    <div class="w-full lg:w-2/5">
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

                init() {

                    this.loadPhilippineData()
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


                populateMultiDeliveryTimes(index) {

                    const delivery = this.deliveries[index]

                    // If no order selected → reset
                    if (!delivery.orders.length) {

                        delivery.need_date = ''
                        delivery.need_time = ''
                        delivery.availableHours = []

                        return
                    }

                    let hours = this.generateHours()

                    const earliest = this.getEarliestDateTimeForDelivery(delivery)
                    const parts = this.formatDateTimeParts(earliest)

                    delivery._datepicker?.setOptions({
                        minDate: earliest
                    })

                    delivery.need_date = parts.date

                    hours = hours.filter(h => h >= parts.hour)

                    delivery.availableHours = hours

                    this.$nextTick(() => {

                        if (!hours.length) {
                            delivery.need_time = ''
                            return
                        }

                        const firstHour = this.formatHourValue(hours[0])
                        delivery.need_time = firstHour
                    })
                },







                availableDeliveryHours: [],




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
                    const parts = this.formatDateTimeParts(earliest)

                    const picker = new Datepicker(el, {
                        autohide: true,
                        format: 'yyyy-mm-dd',
                        minDate: earliest,
                        todayHighlight: true,
                        placeholder: 'Select date'
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
                    const parts = this.formatDateTimeParts(earliest)

                    const picker = new Datepicker(el, {
                        autohide: true,
                        format: 'yyyy-mm-dd',
                        minDate: earliest,
                        todayHighlight: true,
                        placeholder: 'Select date'
                    })

                    el._datepicker = picker

                    this.pickup_date = parts.date
                    picker.setDate(parts.date)

                    // 🔥 auto populate time correctly
                    this.$nextTick(() => {
                        this.populatePickupTimes(parts.hour)
                    })

                    el.addEventListener('changeDate', (e) => {
                        this.pickup_date = this.formatDate(e.detail.date)
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
                        todayHighlight: true,
                        placeholder: 'Select date',
                    })

                    el._datepicker = picker

                    el.addEventListener('changeDate', (e) => {

                        if (!this.deliveries[index].orders.length) return

                        this.deliveries[index].need_date =
                            this.formatDate(e.detail.date)

                        this.populateMultiDeliveryTimes(index)
                    })
                },

                populatePickupTimes(minHour = null) {

                    if (!this.pickup_date) return

                    let hours = this.generateHours()

                    const earliest = this.getEarliestForPickupAndSingle()
                    const parts = this.formatDateTimeParts(earliest)

                    if (this.pickup_date === parts.date) {
                        const requiredHour = minHour ?? parts.hour
                        hours = hours.filter(h => h >= requiredHour)
                    }

                    this.availablePickupHours = hours

                    this.$nextTick(() => {

                        if (!hours.length) {
                            this.pickup_time = ''
                            return
                        }

                        const firstHour = this.formatHourValue(hours[0])
                        this.pickup_time = firstHour
                    })
                },






                populateDeliveryTimes(minHour = null) {

                    if (!this.need_date) return

                    let hours = this.generateHours()

                    const earliest = this.getEarliestForPickupAndSingle()
                    const parts = this.formatDateTimeParts(earliest)

                    if (this.need_date === parts.date) {
                        const requiredHour = minHour ?? parts.hour
                        hours = hours.filter(h => h >= requiredHour)
                    }

                    this.availableDeliveryHours = hours

                    this.$nextTick(() => {

                        if (!hours.length) {
                            this.need_time = ''
                            return
                        }

                        const firstHour = this.formatHourValue(hours[0])
                        this.need_time = firstHour
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


                /* ==========================
                 * FORMATTERS
                 * ========================== */
                formatMoney(value) {
                    return '₱' + (parseFloat(value) || 0)
                        .toLocaleString(undefined, {
                            minimumFractionDigits: 2
                        })
                },

                /* ==========================
                 * COMPUTED GETTERS
                 * ========================== */
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

                /* ==========================
                 * HELPERS
                 * ========================== */
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
                        const paella = item.paella_price > 0 ?
                            Number(item.product?.paella_price || 0) :
                            0
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

                    if (type === 'pickup') {
                        this.allowMultiple = false
                        this.deliveryFee = 0
                        this.deliveryFees = []
                        this.pickup_time = ''
                    }

                    if (type === 'delivery') {
                        this.allowMultiple = false

                        // 🔥 reset single delivery state cleanly
                        this.need_time = ''
                        this.availableDeliveryHours = []
                    }

                },




                /* ==========================
                PICKUP HOURS
                ========================== */

                availablePickupHours: [],

                openHour: 9,
                closeHour: 20,
                availableDeliveryHours: [],

                populatePickupTimes(minHour = null) {

                    if (!this.pickup_date) return

                    let hours = this.generateHours()

                    const earliest = this.getEarliestAllowedDateTime()
                    const parts = this.formatDateTimeParts(earliest)

                    if (this.pickup_date === parts.date) {

                        const requiredHour = minHour ?? parts.hour

                        hours = hours.filter(h => h >= requiredHour)
                    }

                    this.availablePickupHours = hours

                    this.$nextTick(() => {
                        this.pickup_time = hours.length ?
                            this.formatHourValue(hours[0]) :
                            ''
                    })
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
                },

                onCityChange() {
                    this.location = ''
                    this.getDeliveryFee?.()
                    this.validateSingleDeliveryField('city')

                    this.rebuildAddress()

                },

                onBarangayChange() {
                    this.rebuildAddress()
                },

                onMultiProvinceChange(index) {

                    const d = this.deliveries[index]

                    d.city = ''
                    d.location = ''

                    this.rebuildMultiAddress(index)
                },

                onMultiCityChange(index) {

                    const d = this.deliveries[index]

                    d.location = ''

                    this.rebuildMultiAddress(index)
                },

                onMultiBarangayChange(index) {
                    this.rebuildMultiAddress(index)
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

                couponModal: false,
                couponCode: '',
                couponMessage: '',
                couponMessageType: '',
                eligibleCoupons: [],
                selectedCoupon: null,
                coupons: [],

                closeCouponModal() {
                    this.couponModal = false
                },

                selectCoupon(coupon) {
                    this.selectedCoupon = coupon
                    this.couponCode = coupon.code ?? ''
                },

                clearCouponSelection() {
                    this.selectedCoupon = null
                    this.couponCode = ''
                },

                confirmCouponSelection() {
                    if (!this.selectedCoupon) return

                    this.coupons = [this.selectedCoupon]
                    this.closeCouponModal()
                },

                applyCouponCode() {

                    if (!this.couponCode) {
                        this.couponMessage = 'Please enter a coupon code.'
                        this.couponMessageType = 'error'
                        return
                    }

                    const found = this.eligibleCoupons.find(
                        c => c.code === this.couponCode
                    )

                    if (!found) {
                        this.couponMessage = 'Invalid coupon code.'
                        this.couponMessageType = 'error'
                        return
                    }

                    this.selectedCoupon = found
                    this.couponMessage = 'Coupon applied successfully.'
                    this.couponMessageType = 'success'
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

                    let cities = []

                    Object.values(this.phData).forEach(region => {

                        const provinceObj = region.province_list[this.province]

                        if (provinceObj) {
                            cities = Object.keys(provinceObj.municipality_list)
                        }

                    })

                    return cities.sort()
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

                    let cities = []

                    Object.values(this.phData).forEach(region => {

                        const provinceObj = region.province_list[delivery.province]

                        if (provinceObj) {
                            cities = Object.keys(provinceObj.municipality_list)
                        }

                    })

                    return cities.sort()
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
                },

                async getDeliveryFeeForMultiple(index) {

                    const delivery = this.deliveries[index]

                    if (!delivery.province || !delivery.city) return

                    const response = await fetch('{{ route('cart.front.get_shipping_fee') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            province: delivery.province,
                            city: delivery.city
                        })
                    })

                    const data = await response.json()

                    delivery.delivery_fee = parseFloat(data.fee || 0)

                    this.deliveryFee = this.deliveries.reduce((sum, d) => {
                        return sum + parseFloat(d.delivery_fee || 0)
                    }, 0)
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
                            orders: []
                        }]

                        this.deliveryFee = 0

                        if (this.method === 'delivery') {
                            this.allowMultiple = false

                            this.need_time = ''
                            this.availableDeliveryHours = []
                        }
                    }
                },

                orders: @json($carts),

                isOrderChecked(delivery, order) {
                    const isPaella = parseFloat(order.paella_price) > 0;
                    const isFree = !!order.is_free_product;

                    return delivery.orders?.some(o =>
                        o.product_id === order.product_id &&
                        !!o.paella === isPaella &&
                        !!o.is_free_product === isFree
                    );
                },

                onOrderCheckToggle(deliveryIndex, delivery, order, checked) {

                    if (!delivery.orders) delivery.orders = []

                    const remaining = this.getRemainingQty(order)

                    const hasPaella = parseFloat(order.paella_price) > 0;
                    const isPaella = parseFloat(order.paella_price) > 0;
                    const isFree = !!order.is_free_product;

                    if (checked && remaining <= 0) return

                    const orderIndex = delivery.orders.findIndex(o =>
                        o.product_id === order.product_id &&
                        !!o.paella === isPaella &&
                        !!o.is_free_product === isFree
                    )

                    if (checked && orderIndex === -1) {
                        delivery.orders.push({
                            paella: isPaella,
                            is_free_product: isFree,
                            product_id: order.product_id,
                            qty: 1,
                            product: order.product,
                            product_name: isPaella ? order.product.name + ' Boneless with Paella' : order.product
                                .name,
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

                    const found = delivery.orders?.find(o =>
                        o.product_id === order.product_id &&
                        !!o.paella === !!order.product.paella_price &&
                        !!o.is_free_product === !!order.is_free_product
                    )

                    return found ? found.qty : 1
                },

                updateSelectedQty(delivery, order, newQty) {

                    const found = delivery.orders?.find(o =>
                        o.product_id === order.product_id &&
                        !!o.paella === !!order.product.paella_price &&
                        !!o.is_free_product === !!order.is_free_product
                    )

                    if (found) {
                        found.qty = parseInt(newQty)
                    }

                    this.$nextTick(() => {
                        this.cleanupEmptyDeliveries()
                    })

                    this.populateMultiDeliveryTimes(index)
                },

                getAvailableQtyForDropdown(delivery, order) {

                    const totalQty = parseInt(order.qty)

                    const assignedQty = this.deliveries.reduce((sum, d) => {

                        const found = d.orders?.find(o =>
                            o.product_id === order.product_id
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

                    return this.deliveries.reduce((sum, delivery) => {

                        const found = delivery.orders?.find(o =>
                            o.product_id === order.product_id &&
                            !!o.paella === !!order.product.paella_price &&
                            !!o.is_free_product === !!order.is_free_product
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

                getEarliestAllowedDateTime() {

                    const now = new Date()
                    const processingHours = this.getRequiredProcessingHours()

                    let earliest = new Date(now.getTime() + processingHours * 60 * 60 * 1000)

                    // round up to next hour
                    earliest = this.roundUpToNextHour(earliest)

                    // then adjust to opening hours
                    earliest = this.adjustToOpeningHours(earliest)

                    return earliest
                },

                adjustToOpeningHours(dateObj) {

                    const openHour = 9
                    const closeHour = 20

                    let adjusted = new Date(dateObj)

                    const hour = adjusted.getHours()

                    // If before opening → move to 9AM
                    if (hour < openHour) {
                        adjusted.setHours(openHour, 0, 0, 0)
                    }

                    // If after closing → move to next day 9AM
                    if (hour >= closeHour) {
                        adjusted.setDate(adjusted.getDate() + 1)
                        adjusted.setHours(openHour, 0, 0, 0)
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

                    let earliest = new Date(now.getTime() + processingHours * 60 * 60 * 1000)

                    earliest = this.roundUpToNextHour(earliest)
                    earliest = this.adjustToOpeningHours(earliest)

                    return earliest
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

                    const now = new Date()

                    const processingHours = this.getCartProcessingHours()

                    let earliest = new Date(now.getTime() + processingHours * 60 * 60 * 1000)

                    earliest = this.roundUpToNextHour(earliest)
                    earliest = this.adjustToOpeningHours(earliest)

                    return earliest
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

@section('alpine.plugins')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
@endsection
