@extends('layouts.guest', ['page' => $page])

@section('title', 'Checkout')
@section('meta_description', 'Complete your order at Lydia\'s Lechon. Review your cart, choose delivery or pickup, and finalize your purchase for a delicious meal.')

@section('content')

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
</style>

<div class="bg-cream">
    <div x-data="checkoutForm" init="init()" class="container">
        <form action="{{ route('cart.temp_sales') }}" method="POST" id="checkoutForm" enctype="multipart/form-data"
            @submit.prevent="submitForm" class="pb-20 px-4">
            <div class="pt-20 pb-5 px-4">
                <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center mt-10">Checkout</h1>
                @if ($carts && count($carts) > 0)
                <h3 class="font-medium lg:text-2xl text-center">You're almost there! Review your order details, choose
                    your payment
                    method, and finalize your purchase to enjoy your Lydia's Lechon meal.</h3>
                @endif
            </div>

            @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-md mb-4">
                <strong>Success!</strong> {{ session('success') }}
            </div>
            @endif

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
            <div class="flex flex-col lg:flex-row gap-4 w-full mt-10">

                @csrf
                <div class="w-full order-1 lg:order-2 rounded-lg border bg-white border-[#DFDFDF] shadow-md ">
                    <div class="px-4 py-3 border-b border-[#DFDFDF]">
                        <h2 class="text-lg lg:text-3xl font-semibold text-left">Order Summary</h2>
                    </div>
                    <div
                        class="flex items-center text-sm lg:text-base justify-between px-4 py-3 border-b border-[#DFDFDF]">
                        <div x-text="carts.length + ' items'"></div>
                        <div class="font-bold" x-text="'₱' + carts.reduce((sum, item) => 
                                sum + 
                                ((Number((item?.paella_price > 0 ? item?.product?.paella_price : 0)) || 0) * (Number(item.qty) || 1)) + 
                                (item.is_free_product ? 0 : (Number(item.price) || 0) * (Number(item.qty) || 1))
                            , 0).toLocaleString(undefined, { minimumFractionDigits: 2 })" {{--
                            x-text="'₱' + carts.reduce((sum, item) => sum + item.paella_price + (item.is_free_product ? 0 : item.price * item.qty), 0).toLocaleString(undefined, { minimumFractionDigits: 2 })"
                            --}}></div>
                    </div>

                    <div class="flex flex-col items-center gap-4 px-4 py-3 border-b border-[#DFDFDF] w-full">
                        <template x-for="(item, index) in carts" :key="index">
                            <div class="flex gap-4 items-start w-full relative  border-gray-200 py-3">
                                <!-- Image -->
                                <div class="w-20 h-20 min-w-20 min-h-20 bg-center rounded-md overflow-hidden">
                                    <img x-ref="productImage" x-init="
                                            let img = $refs.productImage;
                                            img.onerror = () => {
                                                img.src = '{{ asset('images/no-image.jpg') }}';
                                            };
                                            img.src = item?.product?.photos?.length > 0
                                                ? item.product.photos[item.product.photos.length - 1]?.url
                                                : '{{ asset('images/no-image.jpg') }}';
                                        " :alt="item?.product?.name"
                                        class="w-20 h-20 object-cover rounded-md scale-110" />
                                </div>

                                <!-- Info -->
                                <div class="flex flex-col flex-grow">
                                    <div class="">
                                        <span class="font-bold" x-text="item?.product?.name"></span> <span
                                            class="italic"
                                            x-text="parseFloat(item.paella_price) > 0 ? 'Boneless with Paella' : ''"></span>
                                        <template x-if="item.is_free_product">
                                            <span
                                                class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded">FREE</span>
                                        </template>
                                    </div>
                                    <div class="text-sm text-gray-600 font-medium">
                                        Price:
                                        <span
                                            x-text="item.is_free_product 
                                            ? '₱0.00' 
                                            : '₱' + parseFloat(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })">
                                        </span>
                                        <span class="italic"
                                            x-text="item?.paella_price > 0 ? '+ ₱' + parseFloat(item?.product?.paella_price).toLocaleString(undefined, { minimumFractionDigits: 2 }) : ''"></span>
                                    </div>
                                    <div class="text-sm text-gray-600 font-medium">
                                        QTY: <span x-text="item.qty"></span>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div
                                    class="absolute right-0 bottom-2 text-sm lg:text-base font-bold text-black text-right">
                                    <span
                                        x-text="item.is_free_product 
                                        ? '₱0.00' 
                                        : '₱' + ((parseFloat(item.price) + parseFloat(item?.paella_price > 0 ? item?.product?.paella_price || 0 : 0)) * item.qty).toLocaleString(undefined, { minimumFractionDigits: 2 })">
                                    </span>
                                </div>
                            </div>
                        </template>

                    </div>

                    <!-- Coupon Code Section -->
                    <div class="bg-white rounded-md mt-2 text-sm">
                        {{-- <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2 p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-6 fill-[#ff8545]">
                                    <path fill-rule="evenodd"
                                        d="M1.5 6.375c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v3.026a.75.75 0 0 1-.375.65 2.249 2.249 0 0 0 0 3.898.75.75 0 0 1 .375.65v3.026c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 17.625v-3.026a.75.75 0 0 1 .374-.65 2.249 2.249 0 0 0 0-3.898.75.75 0 0 1-.374-.65V6.375Zm15-1.125a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0V6a.75.75 0 0 1 .75-.75Zm.75 4.5a.75.75 0 0 0-1.5 0v.75a.75.75 0 0 0 1.5 0v-.75Zm-.75 3a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0v-.75a.75.75 0 0 1 .75-.75Zm.75 4.5a.75.75 0 0 0-1.5 0V18a.75.75 0 0 0 1.5 0v-.75ZM6 12a.75.75 0 0 1 .75-.75H12a.75.75 0 0 1 0 1.5H6.75A.75.75 0 0 1 6 12Zm.75 2.25a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Shop Coupon</span>
                            </div>
                            <div class="cursor-pointer flex items-center justify-between p-3 text-[#ff8545] font-bold"
                                @click="couponModal = true">
                                Select Coupon
                            </div>
                        </div> --}}

                        {{-- <div class="flex items-center border mx-3 border-gray-200 rounded-md overflow-hidden">
                            <input @input="couponCode = $event.target.value.toUpperCase()" x-model="couponCode"
                                type="text" placeholder="Have a coupon code?"
                                class="w-full p-3 outline-none border-none text-gray-700">
                            <button @click="submitCouponCode" type="button"
                                class="bg-primary hover:bg-primary-dark text-white px-6 py-3 text-sm">Apply</button>
                        </div> --}}

                        <!-- Subtotal Section -->
                        <div
                            class="border-t border-gray-200 mt-2 pt-3 pb-1 gap-1 flex flex-col text-sm lg:text-base px-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-800">Subtotal</span>
                                <span class="font-medium" x-text="'₱' + carts.reduce((sum, item) => 
                                            sum + 
                                            ((Number((item?.paella_price > 0 ? item?.product?.paella_price : 0)) || 0) * (Number(item.qty) || 1)) + 
                                            (item.is_free_product ? 0 : (Number(item.price) || 0) * (Number(item.qty) || 1))
                                        , 0).toLocaleString(undefined, { minimumFractionDigits: 2 })" {{--
                                    x-text="'₱' + carts.reduce((sum, item) => sum + item.paella_price + (item.is_free_product ? 0 : item.price * item.qty), 0).toLocaleString(undefined, { minimumFractionDigits: 2 })"
                                    --}}></span>
                            </div>
                            <template
                                x-if="deliveryFees.length == 0 && !allowMultiple && method == 'delivery' && shippingDiscountLists.length == 0">
                                <div>
                                    <div class="flex justify-between lg:mt-2">
                                        <span class="font-medium text-gray-800">Delivery Fee</span>
                                        <span class="font-medium"
                                            x-text="deliveryFee > 0 ? '₱' + deliveryFee : 'Free'"></span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="deliveryFees.length > 0">
                                <div class="flex flex-col gap-1 mt-2">
                                    <template x-for="(item, i) in deliveryFees" :key="i">
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
                                    </template>
                                </div>
                            </template>
                            <template
                                x-if="coupons.length > 0 && !allowMultiple && method == 'delivery' && deliveryFee > 0">
                                <div class="flex flex-col gap-1 mt-2">
                                    <div class="flex justify-between text-gray-500">
                                        <span class="font-medium text-gray-800">Delivery Fee</span>
                                        <div class="flex items-center gap-1">
                                            <template x-if="deliveryFee > 0">
                                                <span class="line-through text-red-700 italic"
                                                    x-text="'₱' + deliveryFee.toLocaleString(undefined, { minimumFractionDigits: 2 })"></span>
                                            </template>
                                            <span
                                                x-text="'- ₱' + shippingDiscountLists.reduce((acc, curr) => acc + curr.discount, 0).toLocaleString(undefined, { minimumFractionDigits: 2 })"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="coupons.length > 0 ">
                                <template x-for="(item, i) in coupons" :key="i">
                                    <div class="flex justify-between lg:mt-2">
                                        <span class="font-medium text-red-700 italic flex items-center flex-wrap"
                                            x-show="item.free_shipping && shippingDiscountAmount > 0">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                                fill="currentColor" class="size-4 text-green-600 mr-1">
                                                <path fill-rule="evenodd"
                                                    d="M4.5 2A2.5 2.5 0 0 0 2 4.5v2.879a2.5 2.5 0 0 0 .732 1.767l4.5 4.5a2.5 2.5 0 0 0 3.536 0l2.878-2.878a2.5 2.5 0 0 0 0-3.536l-4.5-4.5A2.5 2.5 0 0 0 7.38 2H4.5ZM5 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Coupon (<span x-text="item.code"></span>)
                                            <span class="text-xs ml-1 underline cursor-pointer"
                                                @click="removeCoupon(i)">Remove Coupon</span>
                                        </span>

                                        <span class="font-medium text-red-700 italic flex items-center flex-wrap"
                                            x-show="!item.free_shipping">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                                fill="currentColor" class="size-4 text-green-600 mr-1">
                                                <path fill-rule="evenodd"
                                                    d="M4.5 2A2.5 2.5 0 0 0 2 4.5v2.879a2.5 2.5 0 0 0 .732 1.767l4.5 4.5a2.5 2.5 0 0 0 3.536 0l2.878-2.878a2.5 2.5 0 0 0 0-3.536l-4.5-4.5A2.5 2.5 0 0 0 7.38 2H4.5ZM5 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Coupon (<span x-text="item.code"></span>)
                                            <span class="text-xs ml-1 underline cursor-pointer"
                                                @click="removeCoupon(i)">Remove Coupon</span>
                                        </span>

                                        <span class="font-medium italic text-red-700 text-right">
                                            <template x-if="item.free_shipping && shippingDiscountLists.length > 0">
                                                <div class="mt-2 space-y-1">
                                                    <template x-for="(shippingList, key) in shippingDiscountLists"
                                                        :key="key">
                                                        <template
                                                            x-if="shippingList.discount && shippingList.discount > 0">
                                                            <div>
                                                                <span
                                                                    x-text="'- ₱' + shippingList.discount.toLocaleString(undefined, { minimumFractionDigits: 2 }) + ' Shipping Discount (' + shippingList.location + ')'"></span>
                                                            </div>
                                                        </template>
                                                    </template>
                                                    <template
                                                        x-if="shippingDiscountLists.reduce((acc, curr) => acc + curr.discount, 0) > 0">
                                                        <div class="">Total discount: <span
                                                                x-text="'- ₱' + shippingDiscountLists.reduce((acc, curr) => acc + curr.discount, 0).toLocaleString(undefined, { minimumFractionDigits: 2 })"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>

                                            <template
                                                x-if="!item.free_shipping && (item.free_products == null || item.free_products.length == 0)">
                                                <span
                                                    x-text="'- ₱' + (
                                                item.discount_type === 'amount' 
                                                    ? parseFloat(item.discount) 
                                                    : (orderAmount * parseFloat(item.discount) / 100)
                                            ).toLocaleString(undefined, { minimumFractionDigits: 2 }) + ' (Order Discount)'"></span>
                                            </template>
                                            <template
                                                x-if="!item.free_shipping && (item.free_products && item.free_products.length > 0)">
                                                <span>Free Products</span>
                                            </template>
                                        </span>
                                    </div>
                                </template>
                            </template>

                        </div>

                        <div class="border-t border-gray-200 mt-2 py-4 gap-1 flex flex-col text-sm lg:text-base px-3">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-800">Total</span>
                                <span class="font-bold" x-text="computeTotal()"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full  order-2 lg:order-1 rounded-lg border bg-white border-[#DFDFDF] shadow-md">
                    <div>
                        <div class="px-4 py-3 border-b border-[#DFDFDF]">
                            <h2 class="text-lg lg:text-3xl font-semibold text-left">Delivery Information</h2>
                        </div>

                        <div class="my-3 px-4 ">
                            <div class="font-bold my-2">Choose Pickup or Delivery</div>
                            <div class="flex items-center gap-4 mt-2">
                                <button type="button" class="px-6 py-3 rounded-md w-full transition border-2"
                                    :class="method === 'pickup' ? 'bg-green-700 text-white' : 'bg-gray-100 text-gray-700'"
                                    @click="changeMethod('pickup')">
                                    Pickup
                                </button>

                                <button type="button" class="px-6 py-3 rounded-md w-full transition border-2"
                                    :class="method === 'delivery' ? 'bg-green-700 text-white' : 'bg-gray-100 text-gray-700'"
                                    @click="changeMethod('delivery')">
                                    Delivery
                                </button>
                            </div>

                            <template x-if="method === 'pickup'">
                                <div class="mt-4">
                                    <label for="branches" class="font-bold">Select Branch <span
                                            class="text-red-700">*</span></label>
                                    <select id="branches" name="delivery_branch" @change="getDeliveryFee" x-ref="branch"
                                        required
                                        class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                        <option selected value="">Choose a branch</option>
                                        @foreach ($pickupBranches as $branch)
                                        <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </template>

                            <div x-show="method === 'delivery'" class="space-y-4">

                                <div class="flex items-center me-4 my-4">
                                    <input @change="onChangeMultipleAddress()" x-model="allowMultiple" checked
                                        id="multiple-address" type="checkbox" value=""
                                        class="w-5 h-5 text-primary bg-gray-100 border-gray-300 rounded-sm focus:ring-primary-dark focus:ring-2">
                                    <label for="multiple-address" class="ms-2 text-base font-medium text-gray-900">Allow
                                        multiple delivery address</label>
                                </div>

                                <template x-if="allowMultiple">
                                    <div class="space-y-6">
                                        <template x-for="(delivery, index) in deliveries" :key="index">
                                            <div class="p-4 bg-gray-100 rounded-md border">
                                                <div class="w-full">
                                                    <label class="font-bold block text-sm mb-1">Orders</label>
                                                    <div class="flex flex-col gap-2">
                                                        <template x-for="(order, index2) in getAvailableOrders()"
                                                            :key="index2">
                                                            <template
                                                                x-if="getRemainingQty(order) > 0 || isOrderChecked(delivery, order)">

                                                                <div
                                                                    class="flex items-center gap-2 mb-2 justify-between">
                                                                    <!-- Product checkbox -->
                                                                    <div class="flex items-center gap-2">
                                                                        <input type="checkbox"
                                                                            :id="'order-' + order.id + '-' + index + '-' + index2 + '-' + (order.paella_price > 0 ? 'paella' : 'nopaella')"
                                                                            {{-- x-model="order.checked" --}}
                                                                            :checked="isOrderChecked(delivery, order)"
                                                                            @change="onOrderCheckToggle(delivery, order, $event.target.checked)"
                                                                            :disabled="getRemainingQty(order) <= 0 && !isOrderChecked(delivery, order)" />
                                                                        <label
                                                                            :for="'order-' + order.id + '-' + index + '-' + index2 + '-' + (order.paella_price > 0 ? 'paella' : 'nopaella')"
                                                                            class="flex-1">
                                                                            <span
                                                                                x-text="order.product.name + (order.paella_price > 0 ? ' Boneless with Paella' : '') + (getRemainingQty(order) <= 0 && !isOrderChecked(delivery, order) ? ' (Fully Assigned)' : '')"></span>
                                                                            <span x-show="order.is_free_product"
                                                                                class="text-green-600 font-semibold text-sm">(Free)</span>
                                                                        </label>
                                                                    </div>

                                                                    <!-- Quantity dropdown -->
                                                                    <select class="border rounded px-2 py-1"
                                                                        :disabled="!isOrderChecked(delivery, order)"
                                                                        :value="getSelectedQty(delivery, order)"
                                                                        @change="updateSelectedQty(delivery, order, $event.target.value)">
                                                                        <template
                                                                            x-for="i in getAvailableQtyForDropdown(delivery, order)">

                                                                            <option :value="i" x-text="i"></option>
                                                                        </template>
                                                                    </select>
                                                                </div>
                                                            </template>
                                                        </template>

                                                    </div>
                                                </div>

                                                <div x-show="qtyValidationMessage"
                                                    class="text-red-600 bg-red-100 border border-red-300 rounded p-3 mt-3">
                                                    <p x-text="qtyValidationMessage"></p>
                                                </div>

                                                <input type="hidden" x-model="delivery.delivery_fee" />

                                                <div class="w-full flex gap-4">
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Select Date</label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                                </svg>
                                                            </div>
                                                            <input :class="{'border-red-500': errors[index]?.need_date}"
                                                                onkeydown="return false" :min="minimumDate"
                                                                @change="validateDeliveryDateTime(delivery)"
                                                                x-model="delivery.need_date" name="need_date"
                                                                type="date"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-3"
                                                                placeholder="Select date">

                                                            <template x-if="errors[index]?.need_date">
                                                                <div class="text-red-500 text-xs mt-1"
                                                                    x-text="errors[index]?.need_date"></div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <div class="w-full lg:w-1/2">
                                                        <label class="font-bold block text-sm mb-1">Select Time</label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    fill="currentColor" viewBox="0 0 24 24">
                                                                    <path fill-rule="evenodd"
                                                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            <select
                                                                :class="{'border-red-500': errors[index]?.need_time}"
                                                                name="need_time" id="need_time"
                                                                x-model="delivery.need_time"
                                                                @change="validateDeliveryDateTime(delivery);"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                                <option value="">Select Hour</option>
                                                                <template x-for="hour in getAvailableHours(delivery)"
                                                                    :key="hour">
                                                                    <template
                                                                        x-if="!isTimeDisabledForDelivery(hour)(delivery)">
                                                                        <option
                                                                            :value="(hour < 10 ? '0' + hour : hour) + ':00'"
                                                                            x-text="formatAMPM(hour)">
                                                                        </option>
                                                                    </template>
                                                                </template>
                                                            </select>

                                                            <template x-if="errors[index]?.need_time">
                                                                <div class="text-red-500 text-xs mt-1"
                                                                    x-text="errors[index]?.need_time"></div>
                                                            </template>
                                                        </div>
                                                        <div x-show="noNeededTime"
                                                            class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                                            Please select a time.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
                                                    <div>We've pre-selected the earliest available time for your order. You may adjust the date and time to your preference. For bookings earlier that our pre-selected schedule, please contact our Hotline directly.</div>
                                                </div>

                                                <h4 class="font-bold mb-2 mt-2">Delivery Address <span
                                                        x-text="index + 1"></span></h4>

                                                <div class="flex flex-col gap-4">
                                                    <div class="w-full">
                                                        <label class="font-bold block text-sm mb-1">Address
                                                            <small>Street Name, Building, House No.,</small></label>
                                                        <textarea name="delivery_address"
                                                            @focus="onMultiAddressFocus(index)"
                                                            @blur="applyMultipleCityProvince(index)"
                                                            x-model="delivery.address"
                                                            @change="validateDeliveryAddress(delivery, 'address', index)"
                                                            class="w-full border border-gray-300 p-2 rounded-md"
                                                            placeholder="Enter address"
                                                            :class="{'border-red-500': errors[index]?.address}"></textarea>
                                                        <template x-if="errors[index]?.address">
                                                            <div class="text-red-500 text-xs mt-1"
                                                                x-text="errors[index]?.address"></div>
                                                        </template>
                                                    </div>

                                                    <div class="w-full flex gap-4 md:flex-row flex-col">

                                                        <div class="w-full md:w-1/2">
                                                            <label for="delivery_address"
                                                                class="block mb-2 font-bold text-gray-900">Province
                                                                <span class="text-red-700">*</span></label>
                                                            <select :disabled="!delivery.address"
                                                                @change="applyMultipleCityProvince(index); getDeliveryFeeForMultipleDelivery(index); validateDeliveryAddress(delivery, 'province', index)"
                                                                :id="'province'+index" x-model="delivery.province"
                                                                name="province" required
                                                                class="bg-gray-50 disabled:bg-gray-100 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                                <option selected value="">Choose a province</option>
                                                                @foreach ($provinces as $province)
                                                                <option value="{{ $province }}">{{ $province }}</option>
                                                                @endforeach
                                                            </select>

                                                            <template x-if="errors[index] && errors[index].province">
                                                                <div class="text-red-500 text-xs mt-1"
                                                                    x-text="errors[index].province"></div>
                                                            </template>
                                                        </div>

                                                        <div class="w-full md:w-1/2">
                                                            <label for="delivery_address"
                                                                class="block mb-2 font-bold text-gray-900">City /
                                                                Municipalities <span
                                                                    class="text-red-700">*</span></label>
                                                            <select :disabled="!delivery.address"
                                                                @change="applyMultipleCityProvince(index); getDeliveryFeeForMultipleDelivery(index); validateDeliveryAddress(delivery, 'city', index)"
                                                                :id="'city'+index" x-model="delivery.city" name="city"
                                                                required
                                                                class="bg-gray-50 disabled:bg-gray-100 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                                <option selected value="">Choose a City</option>
                                                                <template
                                                                    x-for="(c, i) in multipleFilteredCities(index)"
                                                                    :key="i">
                                                                    <option :value="c.city" x-text="c.city"></option>
                                                                </template>
                                                            </select>

                                                            <template x-if="errors[index] && errors[index].city">
                                                                <div class="text-red-500 text-xs mt-1"
                                                                    x-text="errors[index].city"></div>
                                                            </template>
                                                        </div>
                                                    </div>

                                                    <div class="mt-4">
                                                        <label :for="'locations' + index"
                                                            class="font-bold text-gray-900">Barangay</label>
                                                        <select
                                                            @change="validateDeliveryAddress(delivery, 'location', index); applyMultipleCityProvince(index)"
                                                            :disabled="!delivery.city || !delivery.province"
                                                            x-model="delivery.location" :id="'locations' + index"
                                                            name="location"
                                                            class="bg-gray-50 mt-2 border disabled:bg-gray-100 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                            <option selected value="">Choose a Barangay</option>
                                                            <template x-for="(c, i) in filteredMultipleBarangay(index)"
                                                                :key="i">
                                                                <option :value="c.barangay" x-text="c.barangay">
                                                                </option>
                                                            </template>
                                                        </select>
                                                        <p x-show="(!delivery.orders || delivery.orders.length === 0) && delivery.location"
                                                            class="mt-1 text-red-600">
                                                            Order is required to get delivery fee. Please select at
                                                            least one order for this delivery address.
                                                        </p>
                                                    </div>


                                                    {{-- <div class="w-full flex gap-4">
                                                        <div class="w-full">
                                                            <label :for="'locations' + index"
                                                                class="font-bold text-gray-900">Barangay</label>
                                                            <textarea :disabled="!delivery.city || !delivery.province"
                                                                x-model="delivery.location" :id="'locations' + index"
                                                                name="location"
                                                                @change="validateDeliveryAddress(delivery, 'location', index); applyMultipleCityProvince(index)"
                                                                required
                                                                class="disabled:bg-gray-100 bg-gray-50 mt-2 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                        </textarea>

                                                            <p x-show="(!delivery.orders || delivery.orders.length === 0) && delivery.location"
                                                                class="mt-1 text-red-600">
                                                                Order is required to get delivery fee. Please select at
                                                                least one order for this delivery address.
                                                            </p>
                                                        </div>
                                                    </div> --}}

                                                    <div class="w-full flex gap-4">
                                                        <div class="w-full lg:w-1/2">
                                                            <label class="font-bold block mb-1">Contact Person</label>
                                                            <input type="text" x-model="delivery.name"
                                                                @change="validateDeliveryAddress(delivery, 'name', index)"
                                                                class="w-full border border-gray-300 p-2 rounded-md"
                                                                placeholder=""
                                                                :class="{'border-red-500': errors[index]?.name}" />
                                                            <template x-if="errors[index]?.name">
                                                                <div class="text-red-500 text-xs mt-1"
                                                                    x-text="errors[index]?.name"></div>
                                                            </template>
                                                        </div>
                                                        <div class="w-full lg:w-1/2">
                                                            <label class="font-bold block mb-1">Contact Number</label>
                                                            <input type="tel" x-model="delivery.phone"
                                                                @change="validateDeliveryAddress(delivery, 'phone', index)"
                                                                placeholder="e.g. 09171234567"
                                                                x-mask:dynamic="
                                                                    $input.startsWith('34') || $input.startsWith('37')
                                                                        ? '99999999999' : '99999999999'
                                                                "
                                                                class="w-full border border-gray-300 p-2 rounded-md"
                                                                placeholder=""
                                                                :class="{'border-red-500': errors[index]?.phone}" />
                                                            <template x-if="errors[index]?.phone">
                                                                <div class="text-red-500 text-xs mt-1"
                                                                    x-text="errors[index]?.phone"></div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <div class="w-full flex gap-2">
                                                        <input :id="'sms-' + index" type="checkbox"
                                                            x-model="delivery.sms" class="border border-gray-300 p-2" />
                                                        <label class="block text-sm mb-1" :for="'sms-' + index">Notify
                                                            recipient through SMS?</label>
                                                    </div>

                                                    <div class="w-full">
                                                        <label class="font-bold block mb-1">Note</label>
                                                        <textarea x-model="delivery.note"
                                                            class="w-full border border-gray-300 p-2 rounded-md"
                                                            placeholder="Add instructions or notes about your delivery."></textarea>
                                                    </div>

                                                </div>

                                                <div class="text-right mt-3">
                                                    <button type="button" @click="removeDelivery(index)"
                                                        x-show="deliveries.length > 1"
                                                        class="text-red-600 text-sm underline">Remove</button>
                                                </div>
                                            </div>
                                        </template>

                                        <div x-show="canAddMoreDeliveries()">
                                            <button type="button" @click="validateBeforeAddDelivery"
                                                class="bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                                                Add Another Delivery
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!allowMultiple && method === 'delivery'">
                                    <div>

                                        <div class="font-bold mb-4">Delivery Information</div>

                                        <div class="w-full mb-4">
                                            <label for="delivery_address"
                                                class="block mb-2 font-bold text-gray-900">Address <small>Street Name,
                                                    Building, House No.,</small> <span
                                                    class="text-red-700">*</span></label>
                                            <textarea
                                                @focus="delivery_address = _stripCurrentPlaces(delivery_address); _addressCore = delivery_address"
                                                @input="onSingleAddressInput($event.target.value)"
                                                @blur="_rebuildAddress" id="delivery_address" name="delivery_address"
                                                x-model="delivery_address"
                                                value="{{ auth()->check() ? auth()->user()->address_street : '' }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                                placeholder=""></textarea>
                                            <div x-show="noDeliveryAddress"
                                                class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                                Please add delivery address
                                            </div>

                                            <template x-if="addressValidationMessage">
                                                <p class="text-red-500 text-xs italic mt-2"
                                                    x-text="addressValidationMessage"></p>
                                            </template>
                                        </div>

                                        <div class="w-full flex gap-4 mb-4 md:flex-row flex-col">

                                            <div class="w-full md:w-1/2">
                                                <label for="locations"
                                                    class="block mb-2 font-bold text-gray-900">Province <span
                                                        class="text-red-700">*</span></label>
                                                <select :disabled="!delivery_address"
                                                    @change="applyCityProvince; getDeliveryFee()" id="locations"
                                                    name="province" x-model="province" required
                                                    class="bg-gray-50 mt-2 border disabled:bg-gray-100 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                    <option selected value="">Choose a province</option>
                                                    @foreach ($provinces as $province)
                                                    <option value="{{ $province }}">{{ $province }}</option>
                                                    @endforeach
                                                </select>

                                                <template x-if="provinceValidationMessage">
                                                    <p class="text-red-500 text-xs italic mt-2"
                                                        x-text="provinceValidationMessage"></p>
                                                </template>
                                            </div>

                                            <div class="w-full md:w-1/2">
                                                <label for="cities" class="block mb-2 font-bold text-gray-900">City /
                                                    Municipalities<span class="text-red-700">*</span></label>
                                                <select :disabled="!delivery_address"
                                                    @change="applyCityProvince; getDeliveryFee()" id="cities"
                                                    name="city" x-model="city" required
                                                    class="bg-gray-50 mt-2 border disabled:bg-gray-100 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                                    <option selected value="">Choose a City</option>
                                                    <template x-for="(c, i) in filteredCities" :key="i">
                                                        <option :value="c.city" x-text="c.city"></option>
                                                    </template>
                                                </select>

                                                <template x-if="cityValidationMessage">
                                                    <p class="text-red-500 text-xs italic mt-2"
                                                        x-text="cityValidationMessage"></p>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <label for="locations"
                                                class="block mb-2 font-bold text-gray-900">Barangay</label>
                                            <select :disabled="!city || !province" id="locations" name="location"
                                                x-ref="location" x-model="location"
                                                class="bg-gray-50 mt-2 border disabled:bg-gray-100 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <option selected value="">Choose a Barangay</option>
                                                <template x-for="(c, i) in filteredBarangay()" :key="i">
                                                    <option :value="c.barangay" x-text="c.barangay"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </div>

                    <div>
                        <div class="px-4 py-3 border-b border-[#DFDFDF]">
                            <div class="font-bold">Contact Information</div>

                            <div class="mt-3">
                                <div class="my-2">
                                    <label for="name" class="block mb-2 text-sm font-bold text-gray-900">Name<span
                                            class="text-red-700">*</span></label>
                                    <input type="text" id="name" name="name"
                                        value="{{ auth()->check() ? (auth()->user()->is_org == 1 ? auth()->user()->contact_person : auth()->user()->name) : '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        placeholder="" required />

                                    <template x-if="nameValidationMessage">
                                        <p class="text-red-500 text-xs italic mt-2" x-text="nameValidationMessage"></p>
                                    </template>
                                </div>
                                <div class="my-2">
                                    <label for="mobile" class="block mb-2 text-sm font-bold text-gray-900">Mobile Number
                                        <span class="text-red-700">*</span></label>
                                        <input type="tel" id="mobile" name="mobile" placeholder="e.g. 09171234567"
                                                x-mask:dynamic="
                                                    $input.startsWith('34') || $input.startsWith('37')
                                                        ? '99999999999' : '99999999999'
                                                "
                                            value="{{ auth()->check() ? auth()->user()->contact_mobile : '' }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            required>

                                    <template x-if="mobileValidationMessage">
                                        <p class="text-red-500 text-xs italic mt-2" x-text="mobileValidationMessage">
                                        </p>
                                    </template>
                                </div>
                                <div class="my-2">
                                    <label for="email" class="block mb-2 text-sm font-bold text-gray-900">Email <span
                                            class="text-red-700">*</span></label>
                                    <input type="email" id="email" name="email"
                                        value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        placeholder="" required />

                                    <template x-if="emailValidationMessage">
                                        <p class="text-red-500 text-xs italic mt-2" x-text="emailValidationMessage"></p>
                                    </template>
                                </div>
                                <div class="my-2">
                                    <label for="agent" class="block mb-2 text-sm font-bold text-gray-900">Agent
                                        Code</label>
                                    <input type="text" id="agent" name="agent" value=""
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        placeholder="" />

                                    @error('agent')
                                    <div class="text-red-500 text-sm mt-1">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <template x-if="!allowMultiple">
                                    <div class="w-full flex gap-4">
                                        <div class="my-2 w-full lg:w-1/2">
                                            <label for="date" class="block mb-2 text-sm font-bold text-gray-900">Select
                                                Date <span class="text-red-700">*</span></label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                    </svg>
                                                </div>
                                                <input onkeydown="return false" :min="minDate"
                                                    @change="validateDateTime" x-model="need_date" type="date"
                                                    name="need_date"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 "
                                                    placeholder="Select date">
                                            </div>
                                            <div x-show="noNeededDate"
                                                class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                                Please select a date.
                                            </div>
                                        </div>
                                        <div class="my-2 w-full lg:w-1/2">
                                            <div class="relative">
                                                <label for="need_time"
                                                    class="block mb-2 text-sm font-bold text-gray-900">Select Time <span
                                                        class="text-red-700">*</span></label>
                                                <select id="need_time" name="need_time" x-model="need_time"
                                                    @change="validateDateTime"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                    <option value="">Select Hour</option>
                                                    <template x-for="hour in allHours" :key="hour">
                                                        <template x-if="!isTimeDisabled(hour)">
                                                            <option :value="(hour < 10 ? '0' + hour : hour) + ':00'"
                                                                x-text="formatAMPM(hour)"></option>
                                                        </template>
                                                    </template>
                                                </select>
                                            </div>
                                            <div x-show="noNeededTime"
                                                class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                                Please select a time.
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!allowMultiple">
                                    <div
                                        class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
                                        <div>We've pre-selected the earliest available time for your order. You may adjust the date and time to your preference. For bookings earlier that our pre-selected schedule, please contact our Hotline directly.</div>
                                    </div>
                                </template>
                                {{-- <div x-show="warningMessage">
                                    <div
                                        class="text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500 p-3 mt-3 rounded">
                                        <div x-html="warningMessage"></div>
                                    </div>
                                </div> --}}
                                <template x-if="!allowMultiple">
                                    <div class="my-2">
                                        <label for="time"
                                            class="block mb-2 text-sm font-bold text-gray-900">Note</label>
                                        <div class="relative">
                                            <textarea
                                                class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                                name="instruction" id="" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                </template>

                                @if (auth()->guest())
                                <div class="flex items-center space-x-2">
                                    <label  class="flex items-center space-x-2">
                                        <input x-model="privacy" name="privacy" type="checkbox">
                                    </label>
                                    <span class="cursor-pointer" @click="onCheckboxChange">I agree Lydia’s Lechon’s Privacy Protection Policy</span>
                                </div>
                                <template x-if="errors.privacy">
                                    <p class="text-red-500 text-xs mt-1" x-text="errors.privacy[0]"></p>
                                </template>
                                @endif
                            </div>
                            <div x-show="hasErrorMessage"
                                class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded">
                                We are not able to accommodate your order base on your preferred date and time. Kindly
                                refer to the warning message that appeared on your order screen or call our hotline at
                                89391221 / 89394665. Thank you.
                            </div>
                            <div x-show="warningMessage"
                                class="text-red-700 bg-red-100 border-l-4 border-red-500 p-3 mt-3 rounded" x-html="warningMessage">
                            </div>
                            <button :disable="isSubmitting" type="submit"
                                class="bg-primary custom-btn btn-primary-dark text-center text-white px-6 py-4 mt-4 w-full rounded-md">
                                <span x-show="!isSubmitting">Place Order</span>
                                <span x-show="isSubmitting" class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </form>

        <div x-cloak x-show="couponModal" class="fixed inset-0 z-50">
            <!-- backdrop -->
            <div class="fixed inset-0 bg-black/50" @click="close()"></div>

            <!-- panel -->
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div x-show="couponModal" x-transition class="w-full max-w-xl">
                    <div class="relative rounded-2xl bg-white shadow-2xl overflow-hidden">
                        <!-- close -->
                        <button @click="couponModal = false" type="button"
                            class="absolute right-3 top-3 z-10 rounded-full bg-black/10 p-1 text-white hover:bg-black/20"
                            aria-label="Close">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293 10 10l5.707-5.707 1.414 1.414L11.414 11.414l5.707 5.707-1.414 1.414L10 12.828l-5.707 5.707-1.414-1.414 5.707-5.707-5.707-5.707 1.414-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Wrap the whole modal content with selectedCoupon state -->
                        <div class="relative px-8 pt-12 pb-20 text-black">
                            <!-- Coupon code input -->
                            <div class="flex items-center border border-gray-150 rounded-md overflow-hidden mt-5 mb-3">
                                <input @input="couponCode = $event.target.value.toUpperCase()" x-model="couponCode"
                                    type="text" placeholder="Have a coupon code?"
                                    class="w-full p-3 outline-none border-none text-gray-700">
                                <button @click="submitCouponCode" type="button"
                                    class="bg-tertiary hover:bg-tertiary-light text-white px-6 py-3 text-sm">
                                    Apply
                                </button>
                            </div>

                            <div x-show="couponMessage" class="pb-2 text-sm" :class="{
        'text-green-600': couponMessageType === 'success',
        'text-red-600': couponMessageType === 'error'
    }" x-text="couponMessage">
                            </div>

                            <!-- List -->
                            <div class="grid grid-cols-1 gap-4">
                                @foreach ($eligibleCoupons as $c)
                                @php
                                $expires = \Carbon\Carbon::parse(($c->end_date ?? '') . ' ' . ($c->end_time ??
                                '00:00'))->format('d M Y');
                                $reward = match (trim((string)($c->reward ?? ''))) {
                                'free-shipping-optn' => 'Free Shipping',
                                'discount-amount-optn' => 'Discount Amount',
                                'discount-percentage-optn' => 'Discount Percent',
                                'free-product-optn' => 'Free Product',
                                '' => 'Special Offer',
                                default => $c->reward,
                                };
                                @endphp

                                <!-- CARD -->
                                <div class="relative w-full text-left">
                                    <div
                                        class="relative overflow-visible rounded-2xl border border-gray-200 bg-white shadow-sm cursor-pointer">
                                        <!-- left strip -->
                                        <div class="absolute inset-y-0 left-0 w-12 rounded-l-2xl overflow-hidden">
                                            <div class="h-full w-full bg-gradient-to-b from-indigo-600 to-violet-600">
                                            </div>
                                            <div
                                                class="absolute inset-y-0 left-0 flex w-12 items-center justify-center">
                                                <span
                                                    class="vertical-rl rotate-180 text-white/90 tracking-widest text-[11px] font-bold uppercase ml-2">
                                                    {{ $reward }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- left hole -->
                                        <div class="pointer-events-none absolute top-1/2 -left-3 -translate-y-1/2 h-6 w-6 rounded-full border border-white"
                                            style="background:var(--page-bg);"></div>

                                        <!-- content -->
                                        <div class="pl-16 pr-28 py-4">
                                            <div class="flex items-start gap-3">
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm leading-5 text-gray-800 flex flex-col gap-1">
                                                        <span class="font-semibold">{{ $c->name }}</span>
                                                        {!! $c->description
                                                        ?
                                                        \Illuminate\Support\Str::of($c->description)->replace('%','<span
                                                            class="font-extrabold">%</span>')
                                                        : ($c->name ?? 'Special offer') !!}
                                                    </p>
                                                    @php
                                                    $combi = explode('|', $c->purchase_combination ?? '');
                                                    $purchaseAmount = in_array('amount', $combi) ?
                                                    number_format($c->purchase_amount, 2) : null;
                                                    @endphp
                                                    @if ($purchaseAmount)
                                                    <p class="text-[11px] uppercase tracking-wide text-gray-400">Min.
                                                        spend</p>
                                                    <div class="mt-1">₱{{ $purchaseAmount }}</div>
                                                    @endif
                                                    <div class="mt-1">
                                                        <p class="text-[11px] uppercase tracking-wide text-gray-400">
                                                            Expires</p>
                                                        <p class="text-sm font-semibold text-gray-900">{{ $expires }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- single-select control -->
                                        <!-- single-select control (checkbox UI, radio behavior) -->
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                            <div class="flex items-center">
                                                <input type="checkbox" name="coupon_pick" :id="'coupon-{{ $c->id }}'"
                                                    :checked="selectedCoupon === {{ $c->id }}" @change.stop="
        if ($event.target.checked) {
          // selecting this one: becomes the only checked
          selectedCoupon = {{ $c->id }};
          couponCode = @js($c->coupon_code ?? '').toUpperCase();
        } else if (selectedCoupon === {{ $c->id }}) {
          // unchecking the currently selected one: clear
          selectedCoupon = null;
          couponCode = '';
        }
      " class="h-5 w-5 rounded-full text-tertiary focus:ring-tertiary border-gray-300">
                                                <label :for="'coupon-{{ $c->id }}'" class="sr-only">Select coupon {{
                                                    $c->id }}</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 overflow-y-auto py-10 px-4"
            @click.self="showModal = false">
            <div x-show="showModal" class="relative m-auto bg-white text-black z-50 w-full max-w-2xl rounded-md">
                <div id="data-privacy-render">
                    {!! $dataPrivacyRender !!}
                </div>

                <div class="flex justify-end p-4">
                    <button type="button" @click="agreePrivacy"
                        class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                        Agree
                    </button>
                </div>
            </div>
        </div>

        <div x-show="depositModal" x-transition class="relative z-50" aria-labelledby="modal-title" role="dialog"
            aria-modal="true" style="display: none;">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal content -->
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg pb-5">
                        <!-- Modal body -->
                        <div class="">

                            <div class="flex justify-between items-center px-3 pt-3">
                                <div class="flex gap-2 items-center">
                                    <div class="text-2xl font-bold">Amount to pay</div>
                                </div>
                                <button @click="closeDepositModal()" class="self-end text-2xl text-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="text-gray-600 font-medium px-4 mt-4">
                                To complete your order, please enter the amount you wish to pay.
                            </div>

                            <div class="px-4 mt-5">
                                <div>
                                    <form x-data="{ isFormSubmitting: false }"
                                        @submit="isFormSubmitting = true; setTimeout(() => { this.depositModal = true}, 3000)"
                                        action="{{ route('paymaya.paytest') }}" method="POST"
                                        enctype="multipart/form-data" class="flex flex-col">

                                        {{-- action="{{ route('paymaya.pay') }}" method="POST"
                                        enctype="multipart/form-data" class="flex flex-col"> --}}
                                        @csrf
                                        <input type="hidden" name="sales_header_id"
                                            :value="paymentDetails.order_number">

                                        <div class="pb-4">
                                            <img src="{{ asset('images/payment/pay-maya.jpg') }}">
                                        </div>

                                        <!-- GCash / PayMaya -->
                                        <div>
                                            <label class="font-semibold block mb-1">PayMaya:</label>
                                            <select name="pamenty_mode" id="pamenty_mode_gpay" x-model="paymentMode"
                                                @change="gcash_paymaya_change" required
                                                class="border-gray-300 rounded-md w-full p-2">
                                                <option value="PayMaya">PayMaya</option>
                                            </select>
                                        </div>

                                        <!-- GCash QR Code -->
                                        <div x-show="paymentMode === 'GCash'" class="text-center">
                                            <p class="font-semibold">GCash</p>
                                            <p class="text-sm">Scan the QR Code below</p>
                                            <img src="{{ asset('images/gcash.png') }}" alt="GCash QR"
                                                class="mx-auto mt-2 w-40 h-40 object-contain">
                                        </div>

                                        <input type="hidden" id="payment_dt" name="payment_dt">
                                        <input type="hidden" id="ref_no" name="ref_no">

                                        <!-- Amount -->
                                        <div class="mt-4">
                                            <label class="font-semibold block mb-1">Amount to Pay:</label>
                                            <div class="flex">
                                                <span
                                                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md">
                                                    ₱
                                                </span>
                                                <input readonly required name="amount" :value="paymentDetails.amount"
                                                    type="text" id="money"
                                                    class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full border-gray-300 p-2.5  "
                                                    placeholder="">
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="text-right mt-4">
                                            <button :disabled="isFormSubmitting" type="submit"
                                                class="bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-2 rounded-md">
                                                <span x-show="!isFormSubmitting">Submit</span>
                                                <span x-show="isFormSubmitting"
                                                    class="flex items-center justify-center gap-2">
                                                    <svg class="animate-spin h-5 w-5 text-white"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8v8z"></path>
                                                    </svg>
                                                    Submitting...
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-footer-component />

<script>
    window.disabledPickupDates = @json($disabledPickupDates);
    window.disabledDeliveryDates = @json($disabledDeliveryDates);
    window.fullUrl = @json(config('app.url'));
    window.hasBaka = @json($hasbaka);
    window.hasMisc = @json($hasMisc);
    window.hasLechon = @json($haslechon);
    window.privacy = @json(auth()->check());
    window.minimum_order_amount_door_to_door = @json($minimum_order_amount_door_to_door);
    window.minimum_order_amount_pickup = @json($minimum_order_amount_pickup);
</script>

<script>
    function checkoutForm() {
        return {
            today: new Date(),
            hasbaka: window.hasBaka || false,
            haslechon: window.hasLechon || false,
            hasMisc: window.hasMisc || false,
            minimum_order_amount_door_to_door: window.minimum_order_amount_door_to_door || 0,
            minimum_order_amount_pickup: window.minimum_order_amount_pickup || 0,
            minDate() {
                if (this.hasbaka == true) {
                    const day = new Date(this.today);
                    day.setDate(day.getDate() + 3);
                    return day.toISOString().split('T')[0];
                } else if (this.haslechon == true) {
                    const tomorrow = new Date(this.today);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    return tomorrow.toISOString().split('T')[0];
                } else {
                    return this.today.toISOString().split('T')[0];
                }
            },
            paymentDetails: {
                sales_header_id: '',
                order_number: '',
                customer_contact_number: '',
                customer_name: '',
                amount: '',
                signature: '',
                saved_items: ''
            },
            couponModal: false,
            disabledDeliveryDates: window.disabledDeliveryDates,
            disabledPickupDates: window.disabledPickupDates,
            paymentMode: '',
            currentDate: new Date()?.toISOString()?.split('T')[0],
            method: 'pickup',
            depositModal: false,
            closeDepositModal() {
                this.depositModal = false;
                setTimeout(() => {
                    window.location.href = window.fullUrl + '/sales-summary/' + this.paymentDetails.sales_header_id;
                }, 300);
            },
            orders: @json($carts) || [],
            carts: @json($carts) || [],
            totalQty: 1,
            deliveries: [
                { 
                    address: '', 
                    name: '',
                    phone: '',
                    sms: false, 
                    qty: 1, 
                    location: '', 
                    city: '',
                    province: '',
                    order: '', 
                    need_date: this.minDate,
                    need_time: '',
                    note: '', 
                    delivery_fee: 0,
                    paella: false,
                }
            ],
            allowMultiple: false,
            onChangeMultipleAddress() {
                if (!this.allowMultiple) {
                    this.init()
                }
                this.deliveries = [{
                    address: '',
                    name: '',
                    phone: '',
                    sms: false,
                    qty: 1,
                    location: '',
                    city: '',
                    province: '',
                    order: '',
                    need_date: this.minDate(),
                    need_time: '',
                    note: '',
                    paella: false,
                    delivery_fee: 0,
                }];
                this.deliveryFees = [];
                this.deliveryFee = 0;

            },
            formEl: null,
            deliveryFee: 0,
            orderAmount: {{ $total }},
            totalAmount: 0,
            deposit: '',
            rawDeposit: '',
            deliveryFees: [],
            showMessage: false,
            need_date: '',
            need_time: '',
            allHours: Array.from({ length: 14 }, (_, i) => i + 7),
            warningMessage: '',
            errorMessage: '',
            hasErrorMessage: false,
            isSubmitting: false,
            isPaymentLoading: false,
            noNeededTime: false,
            noNeededDate: false,
            coupon: null,
            coupons: [],
            couponCode: '',
            selectedCoupon: null,
            couponMessage: '',
            couponMessageType: '',
            totalDiscountAmount: 0,
            shippingDiscountAmount: 0,
            location: '',
            async submitCouponCode() {
                this.couponMessage = '';
                this.couponMessageType = '';

                const res = await fetch('{{ route('add-manual-coupon') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        couponcode: this.couponCode,
                    }),
                });

                const result = await res.json();

                if (!result.success) {
                    if (result.status !== 'valid') {
                        this.couponMessage = result.message;
                    }

                    this.couponMessageType = 'error';
                    return;
                }

                // Prevent duplicate coupon code
                if (this.coupons.find(c => c.code === result.coupon.code)) {
                    this.couponMessage = 'This coupon is already applied.';
                    this.couponMessageType = 'error';
                    return;
                }

                // Combination logic check:

                // Case 1: New coupon is non-combinable, and there are already applied coupons → reject
                if (result.coupon.combination_allowed === false && this.coupons.length > 0) {
                    this.couponMessage = 'This coupon cannot be combined with other coupons.';
                    this.couponMessageType = 'error';
                    return;
                }

                // Case 2: New coupon is combinable, but an existing coupon is non-combinable → reject
                if (result.coupon.combination_allowed === true) {
                    const nonCombinableCoupon = this.coupons.find(c => c.combination_allowed === false);
                    if (nonCombinableCoupon) {
                        this.couponMessage = 'A coupon that does not allow combination has already been applied.';
                        this.couponMessageType = 'error';
                        return;
                    }
                }

                if (result.coupon.free_products && result.coupon.free_products.length > 0) {
                    result.coupon.free_products.forEach(fp => {
                        if (!this.carts.find(item => item.is_free_product && item.id === fp.id)) {
                            this.carts.push({
                                id: fp.id,
                                product: {
                                    name: fp.name,
                                    photos: fp.photos,
                                },
                                qty: 1,
                                price: 0,
                                is_free_product: true,
                                coupon_code: result.coupon.code,
                            });
                        }
                    });

                    this.hasFreeProducts = true;
                }

                // Add coupon to coupons array
                this.coupons.push(result.coupon);

                // Recompute totals
                this.recomputeCouponTotals();

                this.couponMessage = 'Voucher code successfully applied.';
                this.couponMessageType = 'success';

                // Clear input after success
                this.couponCode = '';
            },

            hasFreeProducts: false,

            clearToProceed: true,

            recomputeCouponTotals(delivery = null) {
                this.totalDiscountAmount = 0;
                this.shippingDiscountAmount = 0;
                this.shippingDiscountLists = [];

                const isMulti = this.method === 'delivery' && this.allowMultiple;

                if (isMulti) {
                    // Reset per-row delivery fee discounts
                    this.deliveryFees = this.deliveryFees.map(row => ({
                        ...row,
                        discount: 0
                    }));
                }

                // Re-filter coupons (only needed for multi)
                this.coupons = this.coupons.filter(coupon => {
                    if (coupon.free_shipping && coupon.location) {
                        const allowedLocations = coupon.location
                            .split('|')
                            .map(l => l.trim())
                            .filter(l => l !== '');

                        if (isMulti) {
                            return this.deliveries.some(d =>
                                allowedLocations.includes(d.location) || allowedLocations.includes('all')
                            );
                        } else {
                            return (
                                allowedLocations.includes(this.location) || allowedLocations.includes('all')
                            );
                        }
                    }
                    return true;
                });

                // Apply per-coupon logic
                this.coupons.forEach(coupon => {
                    if (coupon.free_shipping && this.method !== 'pickup') {
                        const allowedLocations = coupon.location
                            ?.split('|')
                            .map(l => l.trim())
                            .filter(l => l !== '') || [];

                        if (isMulti) {
                            this.deliveryFees.forEach((feeRow, idx) => {
                                if (
                                    allowedLocations.includes(feeRow.location) ||
                                    allowedLocations.includes('all')
                                ) {
                                    const fee = feeRow.fee || 0;
                                    const discount = parseFloat(coupon.free_shipping_discount_amount || 0);

                                    const discountAmount =
                                        discount === 100
                                            ? fee
                                            : (fee * discount) / 100;

                                    this.deliveryFees[idx].discount = discountAmount;
                                    this.shippingDiscountAmount += discountAmount;

                                    this.shippingDiscountLists.push({
                                        location: feeRow.location,
                                        index: idx,
                                        discount: parseFloat(discountAmount.toFixed(2))
                                    });
                                }
                            });
                        } else {
                            // Single delivery flow
                            const fee = this.deliveryFee || 0;

                            if (
                                allowedLocations.includes(this.location) ||
                                allowedLocations.includes('all')
                            ) {
                                const discount = parseFloat(coupon.free_shipping_discount_amount || 0);
                                const discountAmount =
                                    discount === 100 ? fee : (fee * discount) / 100;

                                this.shippingDiscountAmount += discountAmount;

                                this.shippingDiscountLists.push({
                                    location: this.province + '' + this.city,
                                    index: 0,
                                    discount: parseFloat(discountAmount.toFixed(2))
                                });
                            }
                        }
                    } else {
                        // Handle order discount
                        if (coupon.discount_type === 'amount') {
                            this.totalDiscountAmount += parseFloat(coupon.discount ?? 0);
                        } else if (coupon.discount_type === 'percent') {
                            this.totalDiscountAmount += (this.orderAmount * parseFloat(coupon.discount ?? 0)) / 100;
                        }
                    }
                });

                // Cap total discount to orderAmount
                this.totalDiscountAmount = Math.min(this.totalDiscountAmount, this.orderAmount);
            },

            computeTotal() {
                // Fallbacks
                const orderAmount = parseFloat(this.orderAmount) || 0;
                const shippingDiscount = parseFloat(this.shippingDiscountAmount) || 0;
                const couponDiscount = parseFloat(this.totalDiscountAmount) || 0;

                // Handle delivery fee
                let deliveryFeeFinal = parseFloat(this.deliveryFee) || 0;
                if (this.method === 'pickup') {
                    deliveryFeeFinal = 0;
                } else {
                    deliveryFeeFinal = deliveryFeeFinal;
                    deliveryFeeFinal = Math.max(deliveryFeeFinal, 0); // no negative
                }

                if (shippingDiscount > 0) {
                    this.totalDiscountAmount += shippingDiscount;
                }

                // Compute total
                let total = orderAmount + deliveryFeeFinal - (couponDiscount + this.shippingDiscountAmount);

                total = total <= 0 ? 0 : total;

                this.totalAmount = total;
                this.deposit = total.toFixed(2);

                // Update hidden input if needed
                this.$nextTick(() => {
                    let input = this.$root.querySelector('input[name="deposit"]');
                    if (input) {
                        input.dispatchEvent(new Event('input'));
                    }
                });

                return new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                }).format(total);
            },

            freeShipping: false,
            freeShippingDiscountAmount: 0,

            removeCoupon(index) {
                if (typeof index === 'number') {
                    const removedCoupon = this.coupons[index];
                    this.coupons.splice(index, 1);

                    this.carts = this.carts.filter(item =>
                        !(item.is_free_product && item.coupon_code === removedCoupon.code)
                    );
                } else {
                    this.coupons = [];

                    this.carts = this.carts.filter(item => !item.is_free_product);
                }

                this.recomputeCouponTotals();
            },

            changeMethod(method) {
                this.method = method;
                document.cookie = `shipping_method=${method}; path=/;`;

                this.allowMultiple = false;

                this.noNeededTime = false;
                this.noNeededDate = false;

                this.couponMessage = '';
                this.deliveryFees = [];
                this.removeCoupon();

                // this.loadAutoCoupons();

                if (!this.allowMultiple) {
                    this.validateDateTime();
                }

                this.recomputeCouponTotals()
            },

            mobileValidationMessage: '',
            cityValidationMessage: '',
            provinceValidationMessage: '',
            addressValidationMessage: '',
            nameValidationMessage: '',
            emailValidationMessage: '',
            noDeliveryAddress: false,
            delivery_address: '',
            city: '',
            province: '',
            privacy: window.privacy || false,
            errors: {
                privacy: null,
            },

            submitForm() {
                this.formEl = document.getElementById('checkoutForm');

                const formData = new FormData(this.formEl);

                this.isSubmitting = true;
                this.noNeededDate = false;
                this.noNeededTime = false;
                this.noDeliveryAddress = false;

                console.log(this.orderAmount ,  'this.orderAmount')

                this.minimum_order_amount_door_to_door = parseFloat(this.minimum_order_amount_door_to_door) || 0;
                this.minimum_order_amount_pickup = parseFloat(this.minimum_order_amount_pickup) || 0;

                if (this.method === 'delivery' && parseFloat(this.orderAmount) < this.minimum_order_amount_door_to_door) {
                    // add a link going to /menu
                    this.warningMessage = `The minimum order amount for door-to-door delivery is ₱${this.minimum_order_amount_door_to_door.toFixed(2)}. Please add more items to your cart to proceed. <a href="/menu" class="underline font-bold">Browse Menu</a>`;
                    this.isSubmitting = false;
                    return;
                } else if(this.method === 'pickup' && parseFloat(this.orderAmount) < this.minimum_order_amount_pickup) {
                    this.warningMessage = `The minimum order amount for pickup is ₱${this.minimum_order_amount_pickup.toFixed(2)}. Please add more items to your cart to proceed. <a href="/menu" class="underline font-bold">Browse Menu</a>`;
                    this.isSubmitting = false;
                    return;
                } else {
                    this.warningMessage = '';
                }
                
                if ((!this.need_time && this.method === 'pickup') || (!this.need_time && this.method === 'delivery' && !this.allowMultiple)) {
                    this.noNeededTime = true;
                    this.isSubmitting = false;
                    return;
                }

                if (!this.delivery_address && this.method === 'delivery' && !this.allowMultiple) {
                    this.noDeliveryAddress = true;
                    this.isSubmitting = false;
                    return;
                }

                if (!this.need_date && this.method === 'pickup') {
                    this.noNeededDate = true;
                    this.isSubmitting = false;
                    return;
                }

                if (this.errorMessage) {
                    this.hasErrorMessage = true;
                    this.isSubmitting = false;
                    return;
                }

                if (!this.clearToProceed) {
                    this.hasErrorMessage = true;
                    this.isSubmitting = false;
                    return;
                }
                
                if (this.hasErrorMessage) {
                    this.isSubmitting = false;
                    return;
                }
                
                if (!this.privacy) {
                    this.onCheckboxChange();
                    this.errors.privacy = ['You must agree to the privacy policy.'];
                    this.isSubmitting = false;
                    return;
                } else {
                    this.errors.privacy = null;
                }

                if (this.method === 'delivery' && this.allowMultiple) {
                    if (!this.validateAllDeliveryFields()) {
                        this.qtyValidationMessage = 'Please fill all quantity fields.';
                        this.isSubmitting = false;
                        return;
                    }

                    if (!this.validateAllQtyUsed()) {
                        this.isSubmitting = false;
                        return;
                    }
                }

                const couponsWithDiscountUsed = this.coupons.map(c => {
                    let discountUsed = 0;

                    if (c.free_shipping) {
                        const allowedLocations = (c.location || '')
                            .split('|')
                            .map(l => l.trim())
                            .filter(l => l !== '');

                        // Loop through each delivery fee entry and apply if location matches
                        this.deliveryFees.forEach(row => {
                            const isAllowed =
                                allowedLocations.includes(row.location) ||
                                allowedLocations.includes('all');

                            if (isAllowed) {
                                const fee = parseFloat(row.fee || 0);
                                const rate = parseFloat(c.free_shipping_discount_amount || 0);

                                discountUsed += rate === 100
                                    ? fee
                                    : (fee * rate / 100);
                            }
                        });
                    } else {
                        if (c.discount_type === 'amount') {
                            discountUsed = parseFloat(c.discount ?? 0);
                        } else if (c.discount_type === 'percent') {
                            discountUsed = (parseFloat(this.orderAmount) * parseFloat(c.discount ?? 0)) / 100;
                        }
                    }

                    return {
                        ...c,
                        discount_used: parseFloat(discountUsed.toFixed(2)),
                    };
                });


                // Add dynamic fields
                formData.append('shipping_type', this.method);
                formData.append('coupons', JSON.stringify(this.coupons.map(c => c.code)));
                formData.append('coupons', JSON.stringify(couponsWithDiscountUsed));

                // Get total discount_used
                const discounted_amount = couponsWithDiscountUsed.reduce((sum, c) => {
                    return sum + parseFloat(c.discount_used || 0);
                }, 0);

                console.log('Total discount used:', discounted_amount);


                formData.append('discount_amount', couponsWithDiscountUsed.reduce((sum, c) => sum + (c.discount_used || 0), 0));
                formData.append('coupon_data', JSON.stringify(this.coupons));
                formData.append('order_amount', this.orderAmount);
                formData.append('delivery_fee', this.deliveryFee);
                formData.append('deposit', this.deposit);
                formData.append('total_amount', this.totalAmount);

                if (this.allowMultiple) {
                    formData.append('deliveries', JSON.stringify(this.deliveries));
                }

                fetch(this.formEl.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) throw response;
                    return response.json();
                })
                .then(data => {
                    console.log('Order submitted:', data);
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

                        this.depositModal = true;

                        this.isSubmitting = false;
                    } else {
                        this.isSubmitting = false
                        alert('Error: ' + data.message);
                    };
                })
                .catch(async error => {
                    this.isSubmitting = false;
                    let errText = await error.text();
                    console.error('Error:', errText);

                    let errorMessage = JSON.parse(errText);

                    if (errorMessage.errors && errorMessage.errors.mobile) {
                        this.mobileValidationMessage = errorMessage.errors.mobile[0];
                    }

                    if (errorMessage.errors && errorMessage.errors.name) {
                        this.nameValidationMessage = errorMessage.errors.name[0];
                    }

                    if (errorMessage.errors && errorMessage.errors.email) {
                        this.emailValidationMessage = errorMessage.errors.email[0];
                    }

                    if (errorMessage.errors && errorMessage.errors.city) {
                        this.cityValidationMessage = errorMessage.errors.city[0];
                    }

                    if (errorMessage.errors && errorMessage.errors.province) {
                        this.provinceValidationMessage = errorMessage.errors.province[0];
                    }

                    if (errorMessage.errors && errorMessage.errors.delivery_address) {
                        this.addressValidationMessage = errorMessage.errors.delivery_address[0];
                    }
                });
            },

            validateAllDeliveryFields() {
                return this.deliveries.every(delivery => {
                    const hasValidProducts = Array.isArray(delivery.orders) &&
                        delivery.orders.length > 0 &&
                        delivery.orders.every(o => o.product_id && o.qty && o.qty > 0);

                    return (
                        delivery.need_time &&
                        delivery.need_date &&
                        delivery.city &&
                        delivery.province &&
                        hasValidProducts
                    );
                });
            },

            async getDeliveryFee() {
                const location = this.$refs?.location?.value;
                const branch = this.$refs?.branch?.value;

                if (this.province && this.city) {
                    try {
                        let response = await fetch('{{route('cart.front.get_shipping_fee')}}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                province: this.province,
                                city: this.city,
                            }),
                        }).then((response) => {
                            return response;
                        }).catch((error) => {
                            
                        });

                        if (!response.ok) throw new Error('Network response was not ok');

                        let data = await response.json();

                        this.deliveryFee = data.fee;

                        this.location = location;

                        this.coupons = this.coupons.filter(coupon => !coupon.free_shipping);
                        
                        this.recomputeCouponTotals();
                        
                        // this.loadAutoCoupons();
                        
                    } catch (error) {
                        console.error('There was a problem with the fetch operation:', error);
                    }
                }
            },

            async loadAutoCoupons(refetch = false) {
                const res = await fetch('{{ route('get-auto-coupons') }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });

                const result = await res.json();

                if (result.success && result.coupons.length > 0) {
                    for (const autoCoupon of result.coupons) {
                        // Skip if already exists
                        if (this.coupons.find(c => c.code === autoCoupon.code)) continue;

                        // Case 1: Auto coupon is non-combinable and we already have applied coupons
                        if (!autoCoupon.combination_allowed && this.coupons.length > 0) {
                            console.log(`Skipping non-combinable auto coupon: ${autoCoupon.code}`);
                            continue;
                        }

                        // Case 2: Auto coupon is combinable, but an existing coupon is non-combinable
                        if (autoCoupon.combination_allowed && this.coupons.some(c => !c.combination_allowed)) {
                            console.log(`Skipping auto coupon ${autoCoupon.code} due to non-combinable existing coupon`);
                            continue;
                        }

                        // Passed logic check → push to coupons
                        this.coupons.push(autoCoupon);

                        // Handle free products if any
                        if (autoCoupon.free_products?.length > 0) {
                            autoCoupon.free_products.forEach(fp => {
                                if (parseInt(fp.category_id) === 1) {
                                    this.haslechon = true;

                                    this.need_date = this.minDate();
                                }
                                if (fp.slug == 'lechon-baka') {
                                    this.hasbaka = true;

                                    this.need_date = this.minDate();
                                }


                                if (!this.carts.find(item => item.is_free_product && item.id === fp.id)) {
                                    this.carts.push({
                                        id: fp.id,
                                        product: {
                                            name: fp.name,
                                            photos: fp.photos,
                                        },
                                        qty: 1,
                                        price: 0,
                                        is_free_product: true,
                                        coupon_code: autoCoupon.code,
                                    });
                                }

                                // Push to orders so they appear in delivery assignment
                                if (!this.orders.find(o => o.product_id === fp.id && o.is_free_product)) {
                                    this.orders.push({
                                        product_id: fp.id,
                                        qty: 1,
                                        product: fp,
                                        is_free_product: true,
                                        price: 0,
                                        delivery_address: '',
                                        need_date: '',
                                        need_time: '',
                                    });
                                }
                            });

                            this.hasFreeProducts = true;
                        }
                    }

        
                    this.$nextTick(() => {
                        this.minDate();
                    });

                    if (!refetch) {
                        // Recompute totals after loading auto coupons
                        this.recomputeCouponTotals();
                    }
                }
            },

            discountAmount: 0,

            // computeTotal() {
            //     if (this.method == 'pickup') {
            //         this.deliveryFee = 0;
            //     }

            //     let deliveryFeeFinal = this.deliveryFee;

            //     // If free shipping applies
            //     if (this.coupon && this.freeShipping) {
            //         if (this.freeShippingDiscountAmount === 100) {
            //             deliveryFeeFinal = 0;
            //         } else {
            //             deliveryFeeFinal = this.deliveryFee * (1 - this.freeShippingDiscountAmount / 100);
            //         }
            //     }

            //     let total = parseFloat(this.orderAmount) + parseFloat(deliveryFeeFinal);

            //     // Apply coupon discount (if not free shipping type)
            //     if (this.coupon && this.discountAmount > 0) {
            //         total -= this.discountAmount;
            //     }

            //     // Update your component state
            //     this.totalAmount = total;
            //     this.deposit = this.totalAmount.toFixed(2);

            //     // Trigger any input update (if needed)
            //     this.$nextTick(() => {
            //         let input = this.$root.querySelector('input[name="deposit"]');
            //         if (input) {
            //             input.dispatchEvent(new Event('input'));
            //         }
            //     });

            //     return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(total);
            // },

            shippingDiscountLists: [],

            async getDeliveryFeeForMultipleDelivery(index) {
                const delivery = this.deliveries[index];
                const city = delivery.city;
                const province = delivery.province;
                const products = delivery?.orders?.map(o => o.product_id);

                if (!delivery?.orders && !delivery?.orders?.length) {
                    delivery.city = '';
                    delivery.province = '';

                    if (this.errors[index]) {
                        this.errors[index].location = 'Please select at least one product for this delivery.';
                    } else {
                        this.errors[index] = { location: 'Please select at least one product for this delivery.' };
                    }

                    // this.errors[index].location = 'Please select at least one product for this delivery.';
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

                    const data = await response.json();
                    const fee = parseFloat(data.fee || 0);

                    delivery.delivery_fee = fee;

                    // Always store by index — 1 entry per row
                    this.deliveryFees[index] = { location: city + ', ' + province, fee };

                    // this.deliveryFee += fee;

                    // Update total delivery fee
                    this.deliveryFee = this.deliveries.reduce((sum, d) => sum + parseFloat(d.delivery_fee || 0), 0);

                    // await this.loadAutoCoupons(true);

                    this.recomputeCouponTotals(delivery);

                } catch (e) {
                    console.error(`Failed to fetch delivery fee for ${city + ', ' + province}`, e);
                    delivery.delivery_fee = 0;
                }
            },

            async init() {
                this.checkMultipleDeliveries();
                
                const cookie = document.cookie.split('; ').find(row => row.startsWith('shipping_method='));
                this.method = cookie ? cookie.split('=')[1] : 'pickup';

                this.need_date = this.minDate();

                // this.loadAutoCoupons();

                this.$watch('need_date', value => {
                    this.checkAndAdvanceDateIfNoHours();
                });

                this.checkAndAdvanceDateIfNoHours();

                if (!this.allowMultiple) {
                    this.validateDateTime();
                }

                this._citySet = new Set(
                    (this._cities || [])
                        .map(row => (row && typeof row === 'object') ? (row.city ?? '') : (row ?? ''))
                        .map(s => String(s).toLowerCase().trim())
                        .filter(Boolean)
                );

                this._provinceSet = new Set(
                    (this._provinces || [])
                        // if ever you pass objects later, this keeps it robust
                        .map(row => (row && typeof row === 'object') ? (row.province ?? row.name ?? '') : (row ?? ''))
                        .map(s => String(s).toLowerCase().trim())
                        .filter(Boolean)
                );

                this._locationSet = new Set(
                    (this.locationsAll || [])
                        .map(l => String(l?.name ?? '').toLowerCase().trim())
                        .filter(Boolean)
                );

                const hasCore = () => (this._addressCore || '').trim().length > 0;

                // Keep core synced with what the user sees (without current tokens)
                this.$watch('delivery_address', (val) => {
                    if (this._syncing) return;
                    this._addressCore = this._stripCurrentPlaces(val);
                });

                // CITY
                this.$watch('city', (val, old) => {
                    // 1) remove old city (if any) and current location from the field
                    if (old)            this.delivery_address = this._removePlace(this.delivery_address, old);
                    if (this.location)  this.delivery_address = this._removePlace(this.delivery_address, this.location);

                    // 2) clear location because city changed
                    this.location = '';

                    // 3) recompute core from the cleaned field and rebuild
                    this._addressCore = this._stripCurrentPlaces(this.delivery_address);
                    if (hasCore()) this._rebuildAddress();
                });

                // PROVINCE
                this.$watch('province', (val, old) => {
                      this._rebuildAllowedCitySetForProvince(val);

                    // 1) remove old province and current location from the field
                    if (old)            this.delivery_address = this._removePlace(this.delivery_address, old);
                    if (this.location)  this.delivery_address = this._removePlace(this.delivery_address, this.location);

                    // 2) clear location because province changed
                    this.location = '';

                    // 3) recompute core from the cleaned field and rebuild
                    this._addressCore = this._stripCurrentPlaces(this.delivery_address);
                    if (hasCore()) this._rebuildAddress();

                    this.city = '';
                    this.deliveryFee = 0;
                });

                // LOCATION (barangay)
                this.$watch('location', (val, old) => {
                    if (this._syncing) return;

                    // 1) remove the OLD location from the field (if present)
                    if (old) this.delivery_address = this._removePlace(this.delivery_address, old);

                    // 2) recompute core from what’s visible now (no current tokens)
                    this._addressCore = this._stripCurrentPlaces(this.delivery_address);

                    // 3) append the NEW location (and city/province) only if there’s user-typed core
                    if (hasCore()) this._rebuildAddress();
                });

                // Keep core synced if something else edits the field
                this.$watch('delivery_address', (val) => {
                    if (this._syncing) return;
                    this._addressCore = this._stripCurrentPlaces(val);
                });

                // for multi delivery
                this.deliveries.forEach((_, i) => this._wireDelivery(i));

                const res = await fetch('{{ asset("addresses/philippine_provinces_cities_municipalities_and_barangays_2019v2.json") }}');
                this.phData = await res.json();


                // this.allowedCitySet = new Set(
                //     (this._cities || []).map(n => this._normalizeCityKey(n))
                // );
                
                this._rebuildAllowedCitySetForProvince(this.province);

                if (!this.privacy && this.carts.length > 0) {
                    this.showModal = true;
                }

            },

            _rebuildAllowedCitySetForProvince(provinceLabel) {
                const P = this._norm(provinceLabel || '');
                this.allowedCitySet = new Set(
                    (this._cities || [])
                    .map(row =>
                        (row && typeof row === 'object')
                        ? (this._norm(row.province) === P ? row.city : null)
                        : row
                    )
                    .filter(Boolean)
                    .map(name => this._normalizeCityKey(name))
                );
            },

            allowedCitySet: new Set(),
            _addressCore: '',
            _syncing: false,
            _cities: @json($cities),
            _provinces: @json($provinces),

            
            phData: null,

            onSingleAddressFocus() {
                if (this._syncing) return;
                this.delivery_address = this._stripCurrentPlaces(this.delivery_address);
                this._addressCore = this.delivery_address;
            },
            onSingleAddressInput(val) {
                if (this._syncing) return;
                this._addressCore = this._stripCurrentPlaces(val);
                this.delivery_address = this._addressCore; // keep tail out while typing
            },

            _stripAllPlaces(text) {
                const toks = (text || '').split(',').map(t => t.trim()).filter(Boolean);
                return toks.filter(t => {
                    const tl = t.toLowerCase();
                    return !this._locationSet.has(tl) && !this._citySet.has(tl) && !this._provinceSet.has(tl);
                }).join(', ');
            },

            _escapeRe(s){ return String(s ?? '').replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); },

            // remove exactly one place segment
            _removePlace(addr, place){
                if (!place) return String(addr || '');
                const p  = this._escapeRe(String(place).trim());
                const re = new RegExp(`(^|\\s*,\\s*)${p}(?=\\s*,|$)`, 'gi');
                let out  = String(addr || '').replace(re, (m, pre) => (pre && pre.trim() ? pre : ''));
                return out.replace(/\s*,\s*,/g, ', ').replace(/^\s*,\s*|\s*,\s*$/g, '').trim();
            },

            _stripRowTail(addr, tail){
                let out = String(addr || '');
                if (!tail) return out;
                if (tail.location) out = this._removePlace(out, tail.location);
                if (tail.city)     out = this._removePlace(out, tail.city);
                if (tail.province) out = this._removePlace(out, tail.province);
                return out;
            },
            
            _stripCurrentPlaces(text){
                let out = String(text || '');
                out = this._removePlace(out, this.location);
                out = this._removePlace(out, this.city);
                out = this._removePlace(out, this.province);
                return out;
            },

            _stripRowCurrentPlaces(text, d){
                let out = String(text || '');
                out = this._removePlace(out, d.location); // barangay
                out = this._removePlace(out, d.city);
                out = this._removePlace(out, d.province);
                return out;
            },

            _ic(s){ return String(s ?? '').toLowerCase(); },
            _includesIC(hay, needle){ return needle && this._ic(hay).includes(this._ic(needle)); },

            _wireDelivery(i){
                const hasCore = () => (this.deliveries[i]?._core || '').trim().length > 0;

                this.$watch(`deliveries[${i}].address`, (val) => {
                    if (this._syncing) return;
                    const d = this.deliveries[i]; if (!d) return;
                    d._core = this._stripRowTail(val, d._lastTail);
                });

                this.$watch(`deliveries[${i}].city`, (val, old) => {
                    const d = this.deliveries[i]; if (!d) return;
                    d.address   = this._stripRowTail(d.address, d._lastTail);
                    d._lastTail = null; 
                    d.location  = '';
                    d._core     = d.address;
                    if (hasCore()) this._rebuildMultipleAddress(i);
                });

                // Province changed
                this.$watch(`deliveries[${i}].province`, (val, old) => {
                    const d = this.deliveries[i]; if (!d) return;
                    d.address   = this._stripRowTail(d.address, d._lastTail);
                    d._lastTail = null;
                    d.location  = '';
                    d._core     = d.address;
                    d.city      = '';
                    if (hasCore()) this._rebuildMultipleAddress(i);
                });

                // Barangay/Location changed 
                this.$watch(`deliveries[${i}].location`, (val, old) => {
                    if (this._syncing) return;
                    const d = this.deliveries[i]; if (!d) return;
                    d.address   = this._stripRowTail(d.address, d._lastTail);
                    d._lastTail = null;
                    d._core     = d.address;
                    if (hasCore()) this._rebuildMultipleAddress(i);
                });
            },

            applyMultipleCityProvince(index){
                const d = this.deliveries[index]; if (!d) return;
                d.address   = this._stripRowTail(d.address, d._lastTail); // drop previous tail
                d._lastTail = null;
                d._core     = d.address;
                if ((d._core || '').trim()) this._rebuildMultipleAddress(index);
            },

            onMultiAddressFocus(i){
                const d = this.deliveries[i]; if (!d) return;
                d.address = this._stripRowTail(d.address, d._lastTail);
                d._core   = d.address;
            },

            applyCityProvince() {
                this._addressCore = this._stripCurrentPlaces(this.delivery_address);
                this._rebuildAddress();
                this.filteredBarangay();
            },

            _wireCPWatchers(i) {
                this.$watch(`deliveries[${i}].city`,     () => { const d = this.deliveries[i]; if (!d) return; d.location = ''; this._rebuildMultipleAddress(i); });
                this.$watch(`deliveries[${i}].province`, () => { const d = this.deliveries[i]; if (!d) return; d.location = ''; this._rebuildMultipleAddress(i); });
            },

            onAddressInput(i, val) {
                if (this._syncing) return;
                const d = this.deliveries[i]; if (!d) return;
                d._core = this._stripParts(val, d.city, d.province);   // keep only user-typed core
            },

            _citySet: new Set(),
            _provinceSet: new Set(),
            _locationSet: new Set(),

            _rebuildMultipleAddress(i){
                const d = this.deliveries[i]; if (!d) return;

                const start = this._stripRowTail(d.address, d._lastTail);
                const core  = String(d._core || start || '').trim();
                if (!core) return; 

                this._syncing = true;

                const parts = [core];
                const addOnce = (token) => {
                    if (!token) return;
                    const t = String(token).toLowerCase();
                    if (!parts.some(p => String(p||'').toLowerCase() === t)) parts.push(token);
                };

                addOnce(d.location);

                const includesIC = (h, n) => n && String(h||'').toLowerCase().includes(String(n||'').toLowerCase());
                if (d.city && !(d.location && includesIC(d.location, d.city))) addOnce(d.city);

                const provinceCovered =
                    (d.location && includesIC(d.location, d.province)) ||
                    (d.city     && includesIC(d.city,     d.province));
                if (d.province && !provinceCovered) addOnce(d.province);

                d.address   = parts.join(', ');
                d._lastTail = {
                    location: d.location || '',
                    city:     (d.city && !(d.location && includesIC(d.location, d.city))) ? d.city : '',
                    province: (d.province && !provinceCovered) ? d.province : ''
                };

                this._syncing = false;
            },

            _inferFromCore(i) {
                const d = this.deliveries[i]; if (!d) return;
                const core = (d._core || '').trim(); if (!core) return;

                const toks = core.split(',').map(t => t.trim()).filter(Boolean);

                if (!d.city && this._citySet.size) {
                    const hit = toks.find(t => this._citySet.has(t.toLowerCase()));
                    if (hit) {
                        d.city  = hit;
                        d._core = toks.filter(t => t.toLowerCase() !== hit.toLowerCase()).join(', ');
                    }
                }
                if (!d.province && this._provinceSet.size) {
                    const toks2 = (d._core || '').split(',').map(t => t.trim()).filter(Boolean);
                    const hit = toks2.find(t => this._provinceSet.has(t.toLowerCase()));
                    if (hit) {
                        d.province = hit;
                        d._core    = toks2.filter(t => t.toLowerCase() !== hit.toLowerCase()).join(', ');
                    }
                }
            },

            _stripCityProvince(text) {
                const toks = (text || '').split(',').map(t => t.trim()).filter(Boolean);
                return toks.filter(t => {
                    const tl = t.toLowerCase();
                    return !this._locationSet.has(tl)   // barangay 
                        && !this._citySet.has(tl)       // city 
                        && !this._provinceSet.has(tl);  // province 
                }).join(', ');
            },

            _rebuildAddress() {
                this._syncing = true;

                // Always start from a fresh, cleaned core (what the user actually typed)
                const core = this._stripCurrentPlaces(this.delivery_address || this._addressCore || '').trim();
                if (!core) { this._syncing = false; return; }

                const parts = [core];
                const addOnce = (token) => {
                    if (!token) return;
                    const t = this._ic(token);
                    if (!parts.some(p => this._ic(p) === t)) parts.push(token);
                };

                // Append in order; avoid doubling city/province if location already contains them
                addOnce(this.location); // whole token, even if it has commas
                if (this.city && !(this.location && this._includesIC(this.location, this.city))) addOnce(this.city);

                const provinceCovered =
                    (this.location && this._includesIC(this.location, this.province)) ||
                    (this.city &&     this._includesIC(this.city,     this.province));
                if (this.province && !provinceCovered) addOnce(this.province);

                this.delivery_address = parts.join(', ');
                this._syncing = false;
            },

            _inferFromText() {
                const toks = (this._addressCore || '').split(',').map(t => t.trim()).filter(Boolean);

                if (!this.city) {
                    const fCity = toks.find(t => this._cities.some(c => c.toLowerCase() === t.toLowerCase()));
                    if (fCity) { this.city = fCity; this._addressCore = toks.filter(t => t !== fCity).join(', '); }
                }

                if (!this.province) {
                    const fProv = toks.find(t => this._provinces.some(p => p.toLowerCase() === t.toLowerCase()));
                    if (fProv) { this.province = fProv; this._addressCore = toks.filter(t => t !== fProv).join(', '); }
                }

                if (!this.location) {
                    const fLoc = toks.find(t => this.filteredLocations.some(l => l.name.toLowerCase() === t.toLowerCase()));
                    if (fLoc) { this.location = fLoc; this._addressCore = toks.filter(t => t !== fLoc).join(', '); }
                }
            },
            
            locationsAll: @js($locations),

            multipleFilteredCities(index) {
                const d = this.deliveries[index] || {};
                if (!this.phData || !d.province) return [];

                const prov = this._findProvinceObj(d.province);
                if (!prov) return [];
                const muni = prov.municipality_list || {};

                const P = this._norm(d.province || '');
                const allow = new Set(
                    (this._cities || [])
                    .map(row => {
                        if (row && typeof row === 'object') {
                        return (this._norm(row.province) === P) ? (row.city ?? '') : '';
                        }
                        return row ?? '';
                    })
                    .filter(Boolean)
                    .map(name => this._normalizeCityKey(name))
                );

                return Object.keys(muni)
                    .filter(name => allow.has(this._normalizeCityKey(name)))
                    .sort((a, b) => a.localeCompare(b))
                    .map(name => ({ city: name }));
            },

            multipleFilteredProvinces(index) {
                let rows = this.locationsAll;
                const d = this.deliveries[index] || {};

                rows = rows.filter(r => r.province && r.province.trim().length > 0);

                if (d.city) {
                    const c = d.city.toLowerCase();
                    rows = rows.filter(r => (r.city ?? '').toLowerCase() === c);
                }

                const seen = new Set();
                return rows.filter(r => {
                    const k = (r.province ?? '').toLowerCase();
                    if (seen.has(k)) return false;
                    seen.add(k);
                    return true;
                });
            },


            _buildProvinces() {
                const set = new Set();
                for (const r in this.phData) {
                    const provs = this.phData[r]?.province_list || {};
                    for (const p of Object.keys(provs)) set.add(p);
                }
                this.provinces = Array.from(set).sort((a, b) => a.localeCompare(b));
            },

            _findProvinceObj(provinceName) {
                const P = this._norm(provinceName);
                for (const r in this.phData) {
                    const provs = this.phData[r]?.province_list || {};
                    if (provs[P]) return provs[P];
                    for (const key of Object.keys(provs)) {
                    if (this._norm(key) === P) return provs[key];
                    }
                }
                return null;
            },

            _normalizeCityKey(name) {
                let s = this._norm(name || '');
                s = s.replace(/^city\s+of\s+/, '').replace(/\s+city$/, '').replace(/\s+/g, ' ').trim();
                return s;
            },

            get filteredCities() {
                if (!this.phData || !this.province) return [];
                const prov = this._findProvinceObj(this.province);
                if (!prov) return [];
                const muni = prov.municipality_list || {};

                return Object.keys(muni)
                    .filter(name => this.allowedCitySet.has(this._normalizeCityKey(name)))
                    .sort((a, b) => a.localeCompare(b))
                    .map(name => ({ city: name }));
            },

            get filteredProvinces() {
                let rows = this.locationsAll;

                rows = rows.filter(r => r.province && r.province.trim().length > 0);

                if (this.city) {
                    const c = this.city.toLowerCase();
                    rows = rows.filter(r => (r.city ?? '').toLowerCase() === c);
                }

                const seen = new Set();
                return rows.filter(r => {
                    const k = (r.province ?? '').toLowerCase();
                    if (seen.has(k)) return false;
                    seen.add(k);
                    return true;
                });
            },

            filteredMultipleBarangay(index) {
                const d = this.deliveries[index] || {};
                if (!this.phData || !d.province || !d.city) return [];

                const P = this._norm(d.province);
                const C = this._norm(d.city);

                // Scan regions → province → municipality for a flexible match
                for (const regionCode in this.phData) {
                    const region = this.phData[regionCode];
                    const provs = region?.province_list || {};

                    // province keys in dataset are already UPPERCASE, so direct lookup first
                    let provinceObj = provs[P];

                    // if direct lookup failed (rare), try loose find
                    if (!provinceObj) {
                        for (const k of Object.keys(provs)) {
                            if (this._norm(k) === P) {
                                provinceObj = provs[k];
                                break;
                            }
                        }
                    }

                    if (!provinceObj) continue;

                    const muni = provinceObj.municipality_list || {};

                    // cities/munis can be like "CITY OF PASIG", "QUEZON CITY", etc. — compare loosely
                    for (const cityName of Object.keys(muni)) {
                        if (this._norm(cityName) === C) {
                            const list = muni[cityName]?.barangay_list || [];
                            // return as [{ barangay: '...' }, ...] because your template expects object.c.barangay
                            return list
                            .slice()               // copy
                            .sort((a, b) => a.localeCompare(b))
                            .map(b => ({ barangay: b }));
                        }
                    }
                }

                // Not found
                return [];
            },

            filteredBarangay() {
                if (!this.phData || !this.province || !this.city) return [];

                const P = this._norm(this.province);
                const C = this._norm(this.city);

                // Scan regions → province → municipality for a flexible match
                for (const regionCode in this.phData) {
                    const region = this.phData[regionCode];
                    const provs = region?.province_list || {};

                    // province keys in dataset are already UPPERCASE, so direct lookup first
                    let provinceObj = provs[P];

                    // if direct lookup failed (rare), try loose find
                    if (!provinceObj) {
                        for (const k of Object.keys(provs)) {
                            if (this._norm(k) === P) {
                                provinceObj = provs[k];
                                break;
                            }
                        }
                    }

                    if (!provinceObj) continue;

                    const muni = provinceObj.municipality_list || {};

                    // cities/munis can be like "CITY OF PASIG", "QUEZON CITY", etc. — compare loosely
                    for (const cityName of Object.keys(muni)) {
                        if (this._norm(cityName) === C) {
                            const list = muni[cityName]?.barangay_list || [];
                            // return as [{ barangay: '...' }, ...] because your template expects object.c.barangay
                            return list
                            .slice()               // copy
                            .sort((a, b) => a.localeCompare(b))
                            .map(b => ({ barangay: b }));
                        }
                    }
                }

                // Not found
                return [];
            },

            _norm(s){ return (s ?? '').trim().toLowerCase(); },
            _filterByCP(rows, city, province){
                const c = this._norm(city), p = this._norm(province), l = this._norm(rows.location);
                return rows.filter(r =>
                    (!c || this._norm(r.city)     === c) &&
                    (!p || this._norm(r.province) === p) &&
                    (!l || this._norm(r.location) === l)
                );
            },
            _uniqueByName(rows){
                const seen = new Set(), out = [];
                for (const r of rows) {
                    const k = this._norm(r.name);
                    if (!k || seen.has(k)) continue;
                    seen.add(k); out.push(r);
                }
                return out;
            },

            filteredMultipleLocations(i) {
                const location = @json($locations);
                const d = this.deliveries[i] || {};
                const rows = this._filterByCP(location, d.city, d.province);
                return this._uniqueByName(rows).sort((a,b)=>a.name.localeCompare(b.name));
            },

            checkMultipleDeliveries() {
                let multipleItems = this.orders.length > 1;
                let multipleQty = this.orders.some(order => order.qty > 1);
                
                // this.allowMultiple = multipleItems || multipleQty;
            },

            // Get selected quantity for dropdown binding
            getSelectedQty(delivery, order) {
                const isPaella = parseFloat(order.paella_price) > 0;
                const isFree = !!order.is_free_product;

                const found = delivery.orders?.find(o =>
                    o.product_id === order.product_id &&
                    !!o.paella === isPaella &&
                    !!o.is_free_product === isFree
                );

                return found ? found.qty : '';
            },

            getProductType(order) {
                const slug = order.product?.slug;
                const categoryId = order.product?.category_id;

                if (slug === 'lechon-baka') {
                    return 'baka';
                }

                if (categoryId === 1 && slug !== 'lechon-baka') {
                    return 'lechon';
                }

                return 'misc';
            },

            // When checkbox is toggled
            toggleOrderSelection(delivery, order) {
                this.autoAdvanceDateIfNoHours(delivery)
                delivery.location = '';

                if (!delivery.orders) delivery.orders = [];
                 const hasPaella = parseFloat(order.paella_price) > 0;
                const index = this.deliveries.indexOf(delivery);
                const existingIndex = delivery.orders.findIndex(o =>
                    o.product_id === order.product_id &&
                    !!o.paella === hasPaella
                );

                if (existingIndex !== -1) {
                    delivery.orders.splice(existingIndex, 1); // Uncheck
                } else {
                    delivery.orders.push({
                        paella: order.paella_price > 0 ? true : false,
                        product_id: order.product_id,
                        qty: 1,
                        product: order.product,
                        product_name: order.paella_price > 0 ? order.product.name + ' Boneless with Paella' : order.product.name,
                    });
                }
                
                const now = new Date(); // current time
                const selectedDate = new Date(delivery.need_date ?? ''); // delivery date

                let originalAllHours = Array.from({ length: 21 }, (_, i) => i);
                const type = this.getProductType(order);

                // Check if selected date is tomorrow
                const tomorrow = new Date();
                tomorrow.setDate(now.getDate() + 1);

                const isTomorrow =
                    selectedDate.getDate() === tomorrow.getDate() &&
                    selectedDate.getMonth() === tomorrow.getMonth() &&
                    selectedDate.getFullYear() === tomorrow.getFullYear();

                if (type === 'lechon') {
                    this.$nextTick(() => {
                        let d = new Date();
                        d.setDate(d.getDate() + 1);
                        delivery.need_date = d.toISOString().split('T')[0];
                        this.minimumDate(d)
                    });
                } else if (type === 'baka') {
                    this.$nextTick(() => {
                        let d = new Date();
                        d.setDate(d.getDate() + 3);
                        delivery.need_date = d.toISOString().split('T')[0];
                        this.minimumDate(d)
                    });
                } else {
                    this.$nextTick(() => {
                        delivery.need_date = now.toISOString().split('T')[0];
                        this.minimumDate(now)
                        this.autoAdvanceDateIfNoHours(delivery)
                    });
                }

                if (isTomorrow && type === 'lechon') {
                    // 1. Get the current hour and round up
                    let selectedHour = now.getHours();
                    if (now.getMinutes() > 0) {
                        selectedHour += 1; // Round up if there's any minute
                    }

                    // 2. Set the need_time as HH:00 format
                    delivery.need_time = (selectedHour < 10 ? '0' + selectedHour : selectedHour) + ':00';

                    // 3. Get available hours and remove hours before selectedHour
                    let hours = this.getAvailableHours(delivery);
                    hours = hours.filter(hour => hour >= selectedHour);

                    // If you want to use these filtered hours for a dropdown
                    this.allHours = hours;
                } else {
                    this.allHours = originalAllHours;
                }

                // Remove all deliveries after the current one
                this.deliveries.splice(index + 1);

                this.deliveryFees = this.deliveryFees.slice(0, index + 1);

                this.deliveryFee = this.deliveryFees.reduce((sum, item) =>
                                    sum + parseFloat(item.fee || 0) - parseFloat(item.discount || 0), 0);



                this.qtyValidationMessage = '';

                this.refreshAllAvailableQty();
            },

            // Get remaining qty for a product globally (used across all deliveries)
            getRemainingQty(order) {
                const isPaella = parseFloat(order.paella_price) > 0;
                const isFree = !!order.is_free_product;


                // Fetch from original clean source
                const baseOrder = this.orders.find(o =>
                    o.product_id === order.product_id &&
                    (parseFloat(o.paella_price) > 0) === isPaella &&
                    !!o.is_free_product === isFree
                );

                const total = baseOrder ? parseInt(baseOrder.qty) : 0;

                const used = this.deliveries.reduce((sum, d) => {
                    return sum + (d.orders?.reduce((inner, o) => {
                        return (
                            o.product_id === order.product_id &&
                            !!o.paella === isPaella &&
                            !!o.is_free_product === isFree
                        ) ? inner + (parseInt(o.qty) || 0) : inner;
                    }, 0) || 0);
                }, 0);


                return Math.max(total - used, 0);
            },

            // Check if a product is selected for this delivery
            isOrderChecked(delivery, order) {
                const isPaella = parseFloat(order.paella_price) > 0;
                const isFree = !!order.is_free_product;

                return delivery.orders?.some(o =>
                    o.product_id === order.product_id &&
                    !!o.paella === isPaella &&
                    !!o.is_free_product === isFree
                );
            },

            onOrderCheckToggle(delivery, order, isChecked) {
                if (!delivery.orders) delivery.orders = [];

                const hasPaella = parseFloat(order.paella_price) > 0;
                const isPaella = parseFloat(order.paella_price) > 0;
                const isFree = !!order.is_free_product;

                const existingIndex = delivery.orders.findIndex(o =>
                    o.product_id === order.product_id &&
                    !!o.paella === isPaella &&
                    !!o.is_free_product === isFree
                );

                if (isChecked && existingIndex === -1) {
                    delivery.orders.push({
                        paella: isPaella,
                        is_free_product: isFree,
                        product_id: order.product_id,
                        qty: 1,
                        product: order.product,
                        product_name: isPaella ? order.product.name + ' Boneless with Paella' : order.product.name,
                    });
                } else if (!isChecked && existingIndex !== -1) {
                    delivery.orders.splice(existingIndex, 1);
                }

                this.refreshAllAvailableQty();

                this.$nextTick(() => {
                    if (delivery.orders.length > 0) {
                        this.validateDeliveryDateTime(delivery, true);
                    } else {
                        delivery.need_time = '';
                        delivery.need_date = '';
                    }
                });

                this.validateDeliveryDateTime(delivery, true);

                this.qtyValidationMessage = '';
            },





            autoSetDateTimeBasedOnOrders(delivery) {
                const now = new Date();

                const productTypes = delivery.orders.map(o => this.getProductType(o));
                let offsetHours = 0;

                if (productTypes.includes('baka')) offsetHours = Math.max(offsetHours, 72);
                if (productTypes.includes('lechon')) offsetHours = Math.max(offsetHours, 24);
                if (productTypes.includes('misc')) offsetHours = Math.max(offsetHours, 6);

                const minAllowedTime = new Date(now.getTime() + offsetHours * 60 * 60 * 1000);

                // Update need_date to match the offset
                delivery.need_date = minAllowedTime.toISOString().split('T')[0];

                const availableHours = this.getAvailableHours(delivery);

                // Check if current selected time is valid
                let selectedDateTime = null;
                if (delivery.need_time) {
                    selectedDateTime = new Date(`${delivery.need_date}T${delivery.need_time}`);
                }

                // Find the first valid hour that satisfies the required offset
                const firstValidHour = availableHours.find(hour => {
                    const timeStr = (hour < 10 ? '0' + hour : hour) + ':00';
                    const dateTime = new Date(`${delivery.need_date}T${timeStr}`);
                    return dateTime >= minAllowedTime;
                });

                // Only assign time if:
                // - no time selected
                // - or selected time is before the allowed minimum
                if (
                    !delivery.need_time ||
                    (selectedDateTime && selectedDateTime < minAllowedTime)
                ) {
                    delivery.need_time = firstValidHour !== undefined
                        ? (firstValidHour < 10 ? '0' + firstValidHour : firstValidHour) + ':00'
                        : '';
                }

                // Optional: if still invalid (e.g., no valid hours at all), show warning
                if (!delivery.need_time) {
                    delivery.warningMessage = '⚠️ No valid time available for this date.';
                } else {
                    delivery.warningMessage = '';
                }
            },

            validateDeliveryDateTime(delivery, force = false) {
                const now = new Date();
                const productTypes = delivery.orders?.map(o => this.getProductType(o)) || [];

                // Get the maximum offset required
                let offsetHours = 0;
                if (productTypes.includes('baka')) offsetHours = Math.max(offsetHours, 72);
                if (productTypes.includes('lechon')) offsetHours = Math.max(offsetHours, 24);
                if (productTypes.includes('misc')) offsetHours = Math.max(offsetHours, 6);

                let minAllowedDateTime = new Date(now.getTime() + offsetHours * 60 * 60 * 1000);

                let selectedDate = delivery.need_date ? new Date(delivery.need_date) : null;

                // Update need_date if forced or empty/invalid
                if (force || !selectedDate || selectedDate < minAllowedDateTime) {
                    delivery.need_date = minAllowedDateTime.toISOString().split('T')[0];
                }

                // Filter hours from 7 AM to 8 PM only
                const availableHours = this.getAvailableHours(delivery).filter(h => h >= 7 && h <= 20);

                const selectedDateTime = delivery.need_time
                    ? new Date(`${delivery.need_date}T${delivery.need_time}`)
                    : null;

                const isSelectedTimeValid = selectedDateTime && selectedDateTime >= minAllowedDateTime;

                // Only update time if it's invalid or force is true
                if (!isSelectedTimeValid || force) {
                    const validHour = availableHours.find(hour => {
                        const timeStr = (hour < 10 ? '0' + hour : hour) + ':00';
                        const dt = new Date(`${delivery.need_date}T${timeStr}`);
                        return dt >= minAllowedDateTime;
                    });

                    if (validHour !== undefined) {
                        delivery.need_time = (validHour < 10 ? '0' + validHour : validHour) + ':00';
                    } else {
                        // ❗ No valid time today — add 1 day and pick earliest valid hour tomorrow
                        const nextDay = new Date(minAllowedDateTime);
                        nextDay.setDate(nextDay.getDate() + 1);
                        delivery.need_date = nextDay.toISOString().split('T')[0];

                        const hoursNextDay = this.getAvailableHours(delivery).filter(h => h >= 7 && h <= 20);
                        const firstHour = hoursNextDay[0];

                        delivery.need_time = firstHour !== undefined
                            ? (firstHour < 10 ? '0' + firstHour : firstHour) + ':00'
                            : '';
                    }
                }

                // Optional warning for lechon if under 24h
                const finalSelectedDateTime = delivery.need_time
                    ? new Date(`${delivery.need_date}T${delivery.need_time}`)
                    : null;

                delivery.warningMessage = '';
                if (
                    productTypes.includes('lechon') &&
                    finalSelectedDateTime &&
                    (finalSelectedDateTime - now) / 3600000 < 24
                ) {
                    delivery.warningMessage = `⚠️ Warning! The date and time you've selected (${delivery.need_date} - ${this.formatTime(delivery.need_time)}) is less than 24 hours from now. You can still proceed by contacting our <span class='underline text-blue-600 cursor-pointer' @click='openHotline = true'>Hotline</span>.`;
                }

                this.clearToProceed = true;
            },

            // Get previously selected qty in *this delivery* to allow it again in dropdown
            getPreviouslySelectedQty(delivery, order) {
                const isPaella = parseFloat(order.paella_price) > 0;
                const isFree = !!order.is_free_product;
                const found = delivery.orders?.find(o =>
                    o.product_id === order.product_id &&
                    !!o.paella === isPaella &&
                    !!o.is_free_product === isFree
                );
                
                return found ? parseInt(found.qty) || 0 : 0;
            },


            getAvailableQtyForDropdown(delivery, order) {
                return this.getRemainingQty(order) + this.getPreviouslySelectedQty(delivery, order);
            },


            getOrderQtyBinding(delivery, order) {
                const selected = delivery.orders?.find(o => o.product_id === order.product_id);
                return selected ? selected.qty : '';
            },

            updateSelectedQty(delivery, order, newQty) {
                if (!delivery.orders) delivery.orders = [];

                const isPaella = parseFloat(order.paella_price) > 0;
                const isFree = !!order.is_free_product;

                const orderIndex = delivery.orders.findIndex(o =>
                    o.product_id === order.product_id &&
                    !!o.paella === isPaella &&
                    !!o.is_free_product === isFree
                );

                if (orderIndex !== -1) {
                    delivery.orders[orderIndex].qty = parseInt(newQty) || 0;
                } else {
                    delivery.orders.push({
                        paella: isPaella,
                        product_id: order.product_id,
                        qty: parseInt(newQty) || 0,
                        product: order.product,
                        product_name: isPaella ? order.product.name + ' Boneless with Paella' : order.product.name,
                    });
                }

                this.refreshAllAvailableQty();
            },


            refreshAllAvailableQty() {
                // re-trigger a render
                this.orders = this.orders.map(o => ({ ...o }));
            },

            updateAvailableQty(delivery) {
                if (!delivery.order) {
                    delivery.availableQty = [];
                    return;
                }

                const productId = delivery.order.product_id;
                const matchingOrder = this.orders.find(o => o.product_id === productId);
                const totalProductQty = matchingOrder ? parseInt(matchingOrder.qty) : 0;

                // Total assigned to same product, excluding current delivery
                const alreadyAssignedQty = this.deliveries
                    .filter(d => d !== delivery && d.order && d.order.product_id === productId)
                    .reduce((sum, d) => sum + (parseInt(d.qty) || 0), 0);

                const remainingQty = totalProductQty - alreadyAssignedQty;

                // Show dropdown from 1 to remainingQty only (don't add +1)
                const maxAvailable = Math.max(0, remainingQty);

                delivery.availableQty = Array.from({ length: maxAvailable }, (_, i) => i + 1);

                const currentQty = parseInt(delivery.qty) || 0;
                if (currentQty > maxAvailable) {
                    delivery.qty = '';
                }

                console.log({
                    totalProductQty,
                    alreadyAssignedQty,
                    currentQty,
                    remainingQty,
                    maxAvailable
                });
            },

            validateAllQtyUsed() {
                const expectedTotals = {};
                const assignedTotals = {};

                // Build expected totals (use product_id + paella as key)
                this.orders.forEach(order => {
                    const isPaella = parseFloat(order.paella_price) > 0;
                    const isFree = !!order.is_free_product;
                    const key = `${order.product_id}-${isPaella ? 'paella' : 'nopaella'}-${isFree ? 'free' : 'paid'}`;
                    expectedTotals[key] = parseInt(order.qty) || 0;
                });

                // Build assigned totals across all deliveries
                this.deliveries.forEach(delivery => {
                    if (!Array.isArray(delivery.orders)) return;

                    delivery.orders.forEach(o => {
                        if (!o.product_id || !o.qty) return;

                        const key = `${o.product_id}-${!!o.paella ? 'paella' : 'nopaella'}-${!!o.is_free_product ? 'free' : 'paid'}`;
                        assignedTotals[key] = (assignedTotals[key] || 0) + parseInt(o.qty);
                    });
                });

                // Compare expected vs assigned
                for (const key in expectedTotals) {
                    const expected = expectedTotals[key];
                    const assigned = assignedTotals[key] || 0;

                    if (expected !== assigned) {
                        this.qtyValidationMessage = '⚠️ Please assign all available quantities before proceeding.';
                        return false;
                    }
                }

                // All quantities match
                this.qtyValidationMessage = '';
                return true;
            },


            qtyValidationMessage: '',

            getAvailableOrders() {
                return this.orders.map(o => ({ ...o }));
            },

            canAddMoreDeliveries() {
                // Loop through each unique order (product_id + paella)
                for (const order of this.orders) {
                    const totalQty = parseInt(order.qty) || 0;
                    const hasPaella = parseFloat(order.paella_price) > 0;

                    // Sum qty used across all deliveries for this exact product + paella combo
                    const usedQty = this.deliveries.reduce((sum, delivery) => {
                        const matches = delivery.orders?.filter(o =>
                            o.product_id === order.product_id &&
                            !!o.paella === hasPaella
                        ) || [];

                        return sum + matches.reduce((sub, o) => sub + (parseInt(o.qty) || 0), 0);
                    }, 0);

                    // If this specific version still has unassigned qty, allow adding delivery
                    if (usedQty < totalQty) {
                        return true;
                    }
                }

                // All order variants are fully assigned
                return false;
            },


            validateBeforeAddDelivery() {
                const index = this.deliveries.length - 1;
                const lastDelivery = this.deliveries[index];

                // Initialize errors for this delivery
                if (!this.errors) this.errors = {};
                this.errors[index] = {}; // Clear previous errors for this delivery

                if (!lastDelivery) return;

                // Check if at least one product with quantity is selected
                const hasValidProduct = Array.isArray(lastDelivery.orders) &&
                    lastDelivery.orders.length > 0 &&
                    lastDelivery.orders.every(o => o.product_id && o.qty && o.qty > 0);

                if (!hasValidProduct) {
                    alert('Please select at least one product and quantity before adding another delivery address.');
                    return;
                }

                // Check required address fields
                let { address, name, phone, location, need_date, need_time, sms, orders, city, province } = lastDelivery;
                
                if (!address) this.errors[index].address = 'Address is required.';
                if (!name) this.errors[index].name = 'Contact person is required.';
                if (!city) this.errors[index].city = 'City is required.';
                if (!province) this.errors[index].province = 'Province is required.';
                if (!need_date) this.errors[index].need_date = 'Date is required.';
                if (!need_time) this.errors[index].need_time = 'Time is required.';

                if (!city) {
                    this.errors[index].city = 'City is required.';
                    return;
                }

                if (!orders || !Array.isArray(orders) || orders.length === 0) {
                    this.errors[index].orders = 'Please select at least one product for this delivery.';
                    return;
                }

                // Phone validation for SMS
                if (sms && phone) {
                    const phonePattern = /^(09|(\+63)|639)\d{9}$/;
                    if (!phonePattern.test(phone)) {
                        this.errors[index].phone = 'Please provide a valid phone number for SMS notifications.';
                        return;
                    }
                }

                if (!phone && sms) {
                    this.errors[index].phone = 'Please provide a phone number if you want the recipient to receive SMS notifications.';
                    return;
                }

                // check if need_date and need_time are less than 24 hours from now
                const selectedDateTime = new Date(`${need_date}T${need_time}`);
                const now = new Date();
                const diffInMs = selectedDateTime - now;
                const diffInHours = diffInMs / (1000 * 60 * 60);

                // if (this.haslechon && diffInHours < 24) {
                //     alert(`⚠️ Warning! The date and time you've selected (${need_date} - ${this.formatTime(need_time)}) is less than 24 hours from now. Our standard processing time is at least 24 hours. However, you can still proceed by contacting our store directly at our Call Hotline tab.`);
                //     return;
                // }

                // If valid, add a new blank delivery
                this.deliveries.push({
                    address: '',
                    name: '',
                    phone: '',
                    orders: [],
                    city: '',
                    province: '',
                    location: '',
                    paella: false,
                    need_date: this.minDate(),
                    need_time: '',
                    note: '',
                    delivery_fee: 0,
                    sms: false
                });

                this.qtyValidationMessage = '';
            },

            getAvailableHours(delivery) {
                const now = new Date();
                const productTypes = delivery.orders?.map(o => this.getProductType(o)) || [];

                let offset = 0;
                if (productTypes.includes('baka')) offset = 72;
                else if (productTypes.includes('lechon')) offset = 24;
                else if (productTypes.includes('misc')) offset = 6;

                const minAllowedTime = new Date(now.getTime() + offset * 3600 * 1000);
                const deliveryDate = new Date(delivery.need_date + 'T00:00');

                return this.allHours.filter(hour => {
                    const testTime = new Date(`${delivery.need_date}T${hour < 10 ? '0' + hour : hour}:00`);
                    return testTime >= minAllowedTime && hour >= 7 && hour <= 20;
                });
            },

            autoAdvanceDateIfNoHours(delivery, tries = 0) {
                if (tries > 31) return; // Don't go more than a month ahead

                const available = this.getAvailableHours(delivery);
                if (available.length === 0) {
                    // Add 1 day
                    let d = new Date(delivery.need_date);
                    d.setDate(d.getDate() + 1);
                    
                    this.$nextTick(() => {
                        delivery.need_date = d.toISOString().split('T')[0];
                    });

                    // Recursively check again for next date
                    this.autoAdvanceDateIfNoHours(delivery, tries + 1);
                } else {
                    const now = new Date();
                    if (!delivery.need_time) {

                        // Round up to next hour if minutes > 0
                        let roundedHour = now.getHours();
                        if (now.getMinutes() > 0) roundedHour += 1;

                        this.$nextTick(() => {
                            delivery.need_time = (roundedHour < 10 ? '0' + roundedHour : roundedHour) + ':00';
                            delivery.noNeededTime = false;
                        });
                    }
                }
            },
            isTimeDisabledForDelivery(hour) {
                return (delivery) => {
                    if (!delivery.need_date) return false;

                    const selectedDate = new Date(delivery.need_date);
                    const now = new Date();

                    const isToday =
                        selectedDate.getDate() === now.getDate() &&
                        selectedDate.getMonth() === now.getMonth() &&
                        selectedDate.getFullYear() === now.getFullYear();
                    if (isToday && hour <= now.getHours()) {
                        return true;
                    }

                    if (isToday && this.hasMisc && hour < (now.getHours() + 6)) {
                        return true;
                    }

                    const timeStr = (hour < 10 ? '0' + hour : hour) + ':00';
                    return this.disabledDeliveryDates.includes(`${delivery.need_date} ${timeStr}`);
                };
            },

            formatTime(timeStr) {
                const [hours, minutes] = timeStr?.split(':');
                const hoursNum = parseInt(hours, 10);
                const isPM = hoursNum >= 12;
                const adjustedHours = hoursNum % 12 || 12;
                return `${adjustedHours}:${minutes} ${isPM ? 'PM' : 'AM'}`;
            },

            validateDateTime() {
                const now = new Date();

                if (!this.need_date) {
                    this.noNeededDate = true;
                    return;
                }

                const selectedDate = new Date(this.need_date);

                if (!this.need_time || this.isTimeDisabled(parseInt(this.need_time.split(':')[0]))) {
                    // Auto-select first valid hour
                    const validHour = this.allHours.find(hour => !this.isTimeDisabled(hour));
                    if (validHour !== undefined) {
                        this.$nextTick(() => {
                            this.need_time = (validHour < 10 ? '0' + validHour : validHour) + ':00';
                            this.noNeededTime = false;
                        });
                    } else {
                        this.noNeededTime = true;
                        return;
                    }
                }

                if (this.noNeededTime) this.noNeededTime = false;
                if (this.noNeededDate) this.noNeededDate = false;

                const selectedDateTime = new Date(`${this.need_date}T${this.need_time}`);
                const diffInMs = selectedDateTime - now;
                const diffInHours = diffInMs / (1000 * 60 * 60);

                this.warningMessage = '';
            },

            async submit() {
                this.isSubmitting = true;
            },

            formatAMPM(hour) {
                const h = hour % 12 || 12;
                const suffix = hour < 12 ? 'AM' : 'PM';
                return `${h}:00 ${suffix}`;
            },

            checkAndAdvanceDateIfNoHours() {
                const available = this.allHours.filter(hour => !this.isTimeDisabled(hour));
                if (available.length === 0) {
                    // Add 1 day to need_date
                    const current = new Date(this.need_date);
                    current.setDate(current.getDate() + 1);
                    this.need_date = current.toISOString().split('T')[0];
                }
            },

            isTimeDisabled(hour) {
                if (!this.need_date) return false;

                const now = new Date();

                // Determine offset by product type
                let requiredOffset = 0;
                if (this.hasbaka) requiredOffset = 72;
                else if (this.haslechon) requiredOffset = 24;
                else if (this.hasMisc) requiredOffset = 6;

                // Compose full datetime for that hour
                const timeStr = (hour < 10 ? '0' + hour : hour) + ':00';
                const testDateTime = new Date(`${this.need_date}T${timeStr}`);

                const minAllowedTime = new Date(now.getTime() + requiredOffset * 3600 * 1000);

                // Disallow hours before allowed time
                if (testDateTime < minAllowedTime) return true;

                // Check disabled slots
                const fullStr = `${this.need_date} ${timeStr}`;
                if (this.method === 'pickup') {
                    return this.disabledPickupDates.includes(fullStr);
                } else {
                    return this.disabledDeliveryDates.includes(fullStr);
                }
            },

            removeDelivery(index) {
                const removed = this.deliveries.splice(index, 1)[0];

                if (removed?.location) {
                    this.deliveryFees = this.deliveryFees.filter(f => f.location !== removed.location);

                    this.deliveryFee = this.deliveryFees.reduce((sum, item) => sum + parseFloat(item.fee || 0) - parseFloat(item.discount || 0), 0);

                }

                this.deliveries.forEach((_, i) => {
                    this.getDeliveryFeeForMultipleDelivery(i);
                });

                this.computeTotal();
            },

            async checkDateTimeNeeded() {
                let response = await fetch('{{ route('cart.check_dateneeded') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        need_date: this.need_date,
                        need_time: this.need_time,
                        method: this.method,
                        allow_multiple: this.allowMultiple,
                    }),
                });

                if (!response.ok) throw new Error('Network error');

                const data = await response.json();
            },

            agreed: false,
            showModal: false,
            onCheckboxChange() {
                this.showModal = true;
            },
            agreePrivacy() {
                this.privacy = true;
                this.showModal = false;
            },

            minimumDate(date) {
                if (date) {
                    return new Date(date).toISOString().split('T')[0];
                } 

                return new Date().toISOString().split('T')[0]; // Default to today
            },

            validateDeliveryAddress(delivery, type, index = null) {
                if (type === 'address' && delivery.address) {
                    if (!this.errors[index]) this.errors[index] = {};
                    this.errors[index].address = '';
                } else if (type === 'name' && delivery.name) {
                    if (!this.errors[index]) this.errors[index] = {};
                    this.errors[index].name = '';
                } else if (type === 'city' && delivery.city) {
                    if (!this.errors[index]) this.errors[index] = {};
                    this.errors[index].city = '';
                    this.errors[index].location = '';
                } else if (type === 'province' && delivery.province) {
                    if (!this.errors[index]) this.errors[index] = {};
                    this.errors[index].province = '';
                    this.errors[index].location = '';
                } else if (type === 'phone' && delivery.phone) {
                    if (!this.errors[index]) this.errors[index] = {};
                    this.errors[index].phone = '';
                } else if (type === 'location' && delivery.location) {
                if (!this.errors[index]) this.errors[index] = {};
                    this.errors[index].location = '';
                } else if (type === 'need_date' && delivery.need_date) {
                    if (!this.errors[index]) this.errors[index] = {};
                    this.errors[index].need_date = '';
                } else if (type === 'need_time' && delivery.need_time) {
                    if (!this.errors[index]) this.errors[index] = {};
                    this.errors[index].need_time = '';
                }
            },

            hasSelectedAddress: false
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

@section('alpine.plugins')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
@endsection

@endsection