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
    </script>

    <script>
        function checkoutForm() {
            return {
                lechonBakaService: window.lechonBakaService,
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

                async init() {
                    const cookie = document.cookie.split('; ').find(row => row.startsWith('shipping_method='));
                    await this.changeMethod(cookie ? cookie.split('=')[1] : 'pickup')

                    await this.getBlockDates();
                    
                    await this.loadPhilippineData();

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

                    const parts = this.formatDateTimeParts(finalMinDate)

                    // Only force date if empty or invalid
                    if (!delivery.need_date || delivery.need_date < parts.date) {
                        delivery.need_date = parts.date
                        delivery._datepicker?.setDate(parts.date)
                    }

                    let hours = this.generateHours()

                    // REMOVE BLOCKED TIME SLOTS (per delivery)
                    const dateBlocks = this.blockedDetails.filter(b =>
                        b.date === delivery.need_date &&
                        this.blockAppliesToDelivery(b, delivery) &&
                        (b.block_type === 'both' || b.block_type === 'delivery') &&
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
                        placeholder: 'Select date',
                        beforeShowDay: (date) => {

                            const formatted = this.formatDate(date)

                            const blockedForThisDate = this.blockedDetails.filter(b =>
                                b.date === formatted &&
                                this.blockAppliesToCart(b) &&
                                this.blockAppliesToMethod(b)
                            )

                            // If any full-day block exists → disable entire date
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
                    const parts = this.formatDateTimeParts(earliest)

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
                                this.blockAppliesToMethod(b)
                            )

                            // If any full-day block exists then we disable entire date
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

                            // BLOCK (per delivery)
                            const blockedForThisDate = this.blockedDetails.filter(b =>
                                b.date === formatted &&
                                this.blockAppliesToDelivery(b, delivery) &&
                                (
                                    b.block_type === 'both' ||
                                    b.block_type === 'delivery'
                                )
                            )

                            const hasAllDayBlock = blockedForThisDate.some(b => b.is_all_day == 1)

                            if (hasAllDayBlock) {
                                return {
                                    enabled: false,
                                }
                            }

                            // MIN DATE LOGIC
                            const nowRounded = this.roundUpToNextHour(new Date())
                            const earliest = this.getEarliestDateTimeForDelivery(delivery)
                            const finalMinDate = earliest > nowRounded ? earliest : nowRounded

                            const compareDate = new Date(date)
                            compareDate.setHours(0,0,0,0)

                            const minCompare = new Date(finalMinDate)
                            minCompare.setHours(0,0,0,0)

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

                        this.deliveries[index].need_date =
                            this.formatDate(e.detail.date)

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
                    const parts = this.formatDateTimeParts(earliest)

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
                    const parts = this.formatDateTimeParts(earliest)

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

                    const itemsTotal = this.carts.reduce((sum, item) => {

                        const price = parseFloat(item.price || 0)
                        const paella = parseFloat(item.paella_price || 0)

                        return sum + ((price + paella) * item.qty)

                    }, 0)

                    const deliveryTotal = this.allowMultiple
                        ? this.deliveries.reduce((sum, d) => sum + (parseFloat(d.delivery_fee + d.lechon_baka_service) || 0), 0)
                        : (parseFloat(this.deliveryFee + this.lechonBakaService) || 0)

                    console.log(itemsTotal, deliveryTotal)

                    this.total_amount = itemsTotal + deliveryTotal;
                    this.deposit = this.total_amount.toFixed(2);

                    return '₱' + (itemsTotal + deliveryTotal)
                        .toLocaleString(undefined, { minimumFractionDigits: 2 })
                },

                async changeMethod(type) {
                    if (type == this.method) return

                    this.method = type

                    if (type === 'pickup') {
                        this.allowMultiple = false
                        this.deliveryFee = 0
                        this.deliveryFees = []
                        this.need_time = ''
                    }

                    if (type === 'delivery') {
                        this.allowMultiple = false

                        // reset single delivery state cleanly
                        this.need_time = ''
                        this.availableDeliveryHours = []
                    }

                    this.deliveryFee = 0;
                    this.lechonBakaService = window.lechonBakaService;

                    this.computeTotal()
                },

                availablePickupHours: [],

                openHour: 9,
                closeHour: 20,
                availableDeliveryHours: [],

                formatHourValue(hour) {
                    return (hour < 10 ? '0' + hour : hour) + ':00'
                },

                onPickupBranchChange() {
                    this.pickupErrors.branch = ''
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

                        let payload = {
                            name: this.contact.name,
                            mobile: this.contact.mobile,
                            email: this.contact.email,
                            agent: this.contact.agent,
                            shipping_type: this.method,
                            coupons: JSON.stringify(this.coupons.map(c => c.code)),
                            coupon_data: JSON.stringify(this.coupons),
                            discount_amount: this.discount_amount || 0,
                            order_amount: this.order_amount,
                            delivery_fee: this.deliveryFee || 0,
                            deposit: this.deposit,
                            total_amount: this.total_amount,
                            isBaka: this.hasBaka ? 1 : 0,
                            lechon_baka_service: this.lechonBakaService,
                        }

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
                        // this.location = location;
                    })
                },

                async getDeliveryFeeForMultiple(index) {
                    const delivery = this.deliveries[index];

                    const city = delivery.city;
                    const province = delivery.province;

                    // include qty in products
                    const products = delivery?.orders?.map(o => ({ product_id: o.product_id, qty: o.qty }));

                    if (!delivery?.orders && !delivery?.orders?.length) {
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

                        this.deliveryFee = this.deliveries.reduce((sum, d) => sum + (parseFloat(d.delivery_fee || 0)) + (parseFloat(d.lechon_baka_service || 0)), 0);
                        
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
                        this.lechonBakaService = window.lechonBakaService;
                    } else {
                        this.isBaka = false;
                        this.lechonBakaService = 0;
                    }
                
                    this.deliveryFees = [];
                    this.deliveryFee = 0;
                    
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

                async getBlockDates() {
                    const cartProductIds = this.carts.map(i => i.product_id);

                    const response = await fetch('{{ route('checkout.blocks') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({
                            product_ids: cartProductIds
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

                blockAppliesToCart(block) {

                    const cartProductIds = this.carts.map(i => i.product_id)
                    const cartCategoryIds = this.carts.map(i => i.product.category_id)

                    if (block.scope === 'all') {
                        return true
                    }

                    if (block.scope === 'product') {

                        if (!block.products || !block.products.length) return false

                        const blockProductIds = block.products.map(p => p.id)

                        return cartProductIds.some(id => blockProductIds.includes(id))
                    }

                    if (block.scope === 'category') {

                        if (!block.categories || !block.categories.length) return false

                        const blockCategoryIds = block.categories.map(c => c.id)

                        return cartCategoryIds.some(id => blockCategoryIds.includes(id))
                    }

                    return false
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
                        b.is_all_day == 0
                    )
                },

                normalizeTime(timeStr) {
                    if (!timeStr) return null
                    return timeStr.substring(0,5) // from "11:00:00" to "11:00"
                },

                blockAppliesToDelivery(block, delivery) {

                    if (!delivery || !delivery.orders?.length) return false

                    const deliveryProductIds = delivery.orders.map(o => o.product_id)

                    const deliveryCategoryIds = delivery.orders
                        .map(o => o.product?.category_id)
                        .filter(Boolean)

                    if (block.scope === 'all') {
                        return true
                    }

                    if (block.scope === 'product') {

                        if (!block.products?.length) return false

                        const blockProductIds = block.products.map(p => p.id)

                        return deliveryProductIds.some(id => blockProductIds.includes(id))
                    }

                    if (block.scope === 'category') {

                        if (!block.categories?.length) return false

                        const blockCategoryIds = block.categories.map(c => c.id)

                        return deliveryCategoryIds.some(id => blockCategoryIds.includes(id))
                    }

                    return false
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

@section('alpine.plugins')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
@endsection
