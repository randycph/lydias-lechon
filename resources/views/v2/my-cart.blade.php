<style>
   
.coupon-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 8px 10px; /* compact card */
    background: #fff;
    transition: 0.2s;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.coupon-card:hover {
    border-color: #28a745;
    transform: scale(1.01);
}

.coupon-header {
    display: flex;
    justify-content: space-between;
    width: 100%;
    font-size: 0.9rem;
}

.coupon-title {
    font-weight: bold;
    font-size: 0.95rem;
}

.coupon-tag {
    background: #f0f0f0;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
}

.coupon-desc {
    margin-top: 2px;
    font-size: 0.8rem;
    color: #555;
}

.coupon-validity {
    font-size: 0.7rem;
    color: #888;
}

.discount-value {
    font-weight: bold;
    font-size: 0.85rem;
    color: #28a745;
}
 .big-modal {
    width: 60vw !important;
    max-width: 1400px !important;
    height: 60vh !important;
    max-height: 60vh !important;
     position: fixed !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;

    /* Optional */
    overflow-y: auto;
}
</style>
@extends('layouts.guest', ['page' => $page])

@section('title', 'My Cart')
@section('meta_description', 'View and manage your cart items. Adjust quantities, remove items, and proceed to checkout for a seamless shopping experience.')

@section('content')
<div
x-init="init"
x-data="{
    cartCount: 0,
    carts: [],
    coupons: [],
    loading: false,

    autoCoupon: null,
    manualCoupon: null,
    deliveryFee: 100,
    originalDeliveryFee: 100,
    couponDiscount: 0,

    /* ---------------- CART ---------------- */
    async getCarts() {
        try {
            let response = await fetch('{{ route('cart.get') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            });

            let data = await response.json();
            this.carts = data.cart ?? [];
            this.cartCount = this.carts.length;

            if (!this.originalDeliveryFee) {
                this.originalDeliveryFee = data.delivery_fee ?? 0;
                this.deliveryFee = this.originalDeliveryFee;
            }

            this.$nextTick(() => {
                this.autoApplyFreeDelivery();
                this.applyCoupons();
            });

        } catch (error) {
            console.error('Fetch carts error:', error);
        }
    },

    async getUserCoupons() {
        try {
            let response = await fetch('{{ route('user.coupons') }}');
            let data = await response.json();
            this.coupons = data.coupons ?? [];
        } catch (error) {
            console.error('Fetch coupons error:', error);
        }
    },

    /* ---------------- SUBTOTAL ---------------- */
    getSubtotal() {
        return this.carts.reduce((total, cart) => {
            let price = this.getItemPrice(cart);
            return total + price * Number(cart.qty || 1);
        }, 0);
    },
    // Raw subtotal (no item coupons)
    getRawSubtotal() {
        return this.carts.reduce(
            (total, cart) =>
                total + Number(cart.product.price) * Number(cart.qty || 1),
            0
        );
    },

    // Item-level discount
    getItemPrice(cart) {
        let price = Number(cart.product.price);

        if (
            this.manualCoupon &&
            this.manualCoupon.discount_type === 'item_discount' &&
            this.manualCoupon.product_id === cart.product.id &&
            this.getRawSubtotal() >= this.manualCoupon.min_cart_total
        ) {
            price = Math.max(
                price - Number(this.manualCoupon.discount_value),
                0
            );
        }

        return price;
    },

    /* ---------------- TOTAL ---------------- */
    getTotal() {
        return this.getSubtotal() + this.deliveryFee - this.couponDiscount;
    },

    /* ---------------- AUTO COUPON ---------------- */
    async autoApplyFreeDelivery() {
        try {
            const response = await fetch('{{ route('cart.autoFreeDelivery') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    subtotal: this.getSubtotal()
                })
            });

            const data = await response.json();
            this.autoCoupon = data.applied ? data.coupon : null;
            this.applyCoupons();

        } catch (error) {
            console.error('Auto coupon error:', error);
        }
    },

    /* ---------------- COUPON ENGINE ---------------- */
    applyCoupons() {

    if (
        this.manualCoupon &&
        this.manualCoupon.product_id &&
        !this.hasCouponItem(this.manualCoupon)
    ) {
        this.manualCoupon = null;
    }

    let deliveryFee = this.originalDeliveryFee ?? 0;
    let discount = 0;

    // AUTO COUPON
    if (this.autoCoupon?.discount_type === 'free delivery') {
        deliveryFee = 0;
    }

    // MANUAL COUPON
    if (this.manualCoupon) {
        if (this.manualCoupon.discount_type === 'fixed') {
            discount += Number(this.manualCoupon.discount_value);
        }

        if (this.manualCoupon.discount_type === 'percentage') {
            discount += this.getRawSubtotal() *
                (this.manualCoupon.discount_value / 100);
        }
    }

    this.deliveryFee = deliveryFee;

    this.couponDiscount = Math.min(
        discount,
        this.getRawSubtotal()
    );
},
displayCouponValue(coupon) {
    if (!coupon) return '₱0.00';

    if (coupon.discount_type === 'fixed') {
        return '₱' + Number(coupon.discount_value).toFixed(2);
    }

    if (coupon.discount_type === 'percentage') {
        return coupon.discount_value + '%';
    }

    if (coupon.discount_type === 'free delivery') {
        // discount_value exists, but delivery fee is the REAL saved value
        return '₱' + Number(this.originalDeliveryFee).toFixed(2);
    }

    return '₱0.00';
},

    hasCouponItem(coupon) {
        if (!coupon?.product_id) return true;

        return this.carts.some(cart =>
            Number(cart.product.id) === Number(coupon.product_id)
        );
    },

    /* ---------------- INIT ---------------- */
    init() {
        this.getCarts();
        this.getUserCoupons();
    }
}"
class="bg-cream"
>




    <div class="py-20 px-4 container">
        <div class="flex gap-6 lg:flex-row flex-col mt-10">
            <div class="w-full lg:w-1/4">
                <x-account-menu-component />
            </div>

            <div class="w-full lg:w-3/4">
                <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md">
                    <div class="px-6 py-4 border-b border-[#DFDFDF]">
                        <h2 class="font-semibold text-tertiary text-left uppercase">My Cart</h2>
                    </div>
                    <div class="flex items-start font-bold flex-col gap-2  py-5 border-b border-[#DFDFDF]">
                        <template x-if="carts?.length > 0"> 
                            <div class="w-full px-4">
                                <div class="mt-4 flex flex-col gap-4">
                
                                    <template x-for="(cart, index) in carts" :key="index">
                                        <div class="flex justify-between items-center gap-4 hover:bg-gray-100 py-2" >
                                            <div class="flex gap-4 items-center px-6">
                                                <div style="background-image: url('{{ asset('images/checkout-bg.png') }}')" class="w-20 h-20 object-cover rounded-md scale-110 bg-center">
                                                    <img
                                                        x-ref="productImage"
                                                        x-init="
                                                            let img = $refs.productImage;
                                                            img.onerror = () => {
                                                                img.src = '{{ asset('images/no-image.jpg') }}';
                                                            };
                                                            img.src = cart?.product?.photos?.length > 0
                                                                ? cart.product.photos[cart.product.photos.length - 1]?.url
                                                                : '{{ asset('images/no-image.jpg') }}';
                                                        "
                                                        :alt="cart?.product?.name"
                                                        class="w-20 h-20 object-cover rounded-md scale-110"
                                                    />
                                                </div>
                                                <div class="flex flex-col gap-1">
                                                    <div class="flex flex-wrap items-center gap-1">
                                                        <div class="font-bold" x-text="cart?.product?.name"></div>
                                                        <span class="italic" x-text="cart?.paella ? 'Boneless with Paella' : ''"></span>
                                                    </div>
                                                    <div class="flex flex-wrap items-center gap-1">
                                                        <div class="text-sm text-gray-600" x-text="new Intl.NumberFormat('en-PH',{style:'currency',currency:'PHP'}).format(getItemPrice(cart) * (cart?.qty || 1))"></div>

                                                        <span class="italic text-sm text-gray-600" x-text="cart?.paella_price > 0 ? '+ ₱' + parseFloat(cart.product.paella_price * cart.qty).toLocaleString(undefined, { minimumFractionDigits: 2 }) : ''"></span>
                                                    </div>
                                                    
                                                    <!-- Quantity Selector -->
                                                    <div class="flex items-center space-x-1">
                                                        <!-- Minus Button -->
                                                        <button @click="handleQtyChange(cart.product.id, cart.qty, -1, cart?.paella_price)" class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                                                <path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                
                                                        <!-- Quantity Display (Fix: Use `x-text`) -->
                                                        <span class="w-8 text-center font-bold text-green-600" x-text="cart.qty"></span>
                
                                                        <!-- Plus Button -->
                                                        <button @click="handleQtyChange(cart.product.id, cart.qty, 1, cart?.paella_price)" class="w-8 h-8 flex items-center justify-center border rounded-md bg-gray-100 text-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                                                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="text-xs italic text-gray-400" x-text="new Date(cart.created_at).toLocaleDateString('en-PH', { 
                                                        year: 'numeric', 
                                                        month: 'short', 
                                                        day: 'numeric', 
                                                        hour: '2-digit', 
                                                        minute: '2-digit' 
                                                    })"></div>
                                                </div>
                                            </div>
                                            <div class="pr-2">
                                                <button @click="removeCart(cart?.product?.id)" class="text-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                
                                    <!-- Coupon Code Section -->
                                    <div class="rounded-md mt-10" x-data="{ 
                                        couponCode: '',
                                        showMessage: false,
                                        submitCouponCode() {
                                            if (this.couponCode) {
                                                this.showMessage = true;
                                            } else {
                                                this.showMessage = false;
                                            }
                                        }
                                    }">
                                        <!-- Subtotal Section -->
                                        <div class="border-t border-gray-200 mt-4 pt-4">
                                            <div class="flex justify-between">
                                                <span class="font-bold text-gray-800">Subtotal</span>
                                                <span class="font-bold text-lg" 
                                                x-text="
                                                    new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(
                                                        carts.reduce(
                                                            (total, cart) => 
                                                                total +
                                                                (Number((cart?.paella_price > 0 ? cart?.product?.paella_price : 0) * (cart?.qty || 1)) || 0) +
                                                                ((cart?.is_free_product ? 0 : (Number(cart?.product?.price) || 0) * (Number(cart?.qty) || 1))),
                                                            0
                                                        )
                                                    )
                                                "></span>
                                            </div>
                                            
                                           <div x-data="{ showCouponModal: false }">

                                        <!-- Coupon summary / clickable area -->
                                        <!-- Coupon selection container -->
<div x-data="{ showCouponModal: false }">
    <!-- Coupon summary -->
    <div 
    class="flex justify-between mb-2 big cursor-pointer bg-gray-100 p-2 rounded-md hover:bg-gray-200"
    @click="showCouponModal = true"
>
    <span class="font-medium italic text-red-600">
        Add Coupon
    </span>
</div>

    <div class="flex flex-col gap-1 text-sm">
    <!-- Auto coupon -->
    <template x-if="autoCoupon">
        <div class="text-green-700">
            <span x-text="autoCoupon.coupon_name"></span> 
            <span x-text="autoCoupon.discount_type === 'free delivery' ? '(Free Delivery)' : ''"></span>
        </div>
    </template>

    <!-- Manual coupon -->
    <template x-if="manualCoupon">
        <div class="text-green-700">
            <span x-text="manualCoupon.coupon_name"></span> 
            <span x-text="manualCoupon.discount_type === 'fixed' ? '- ₱' + Number(manualCoupon.discount_value).toFixed(2) : '- ' + manualCoupon.discount_value + '%'"></span>
        </div>
    </template>
</div>


    <!-- Coupon Modal -->
    <!-- Coupon Modal -->
<div 
    x-show="showCouponModal" 
    class="fixed inset-0 bg-black big-modal bg-opacity-50 flex items-start justify-center z-50"
    x-transition
>
    <!-- Large modal container -->
    <div class="bg-white rounded-lg p-6 overflow-y-auto shadow-2xl max-w-[800px] w-full max-h-[70vh]">
        <h3 class="font-bold text-2xl mb-6 text-center">Select a Coupon</h3>

        <template x-if="coupons.length === 0">
            <p class="text-center text-gray-600">No available coupons.</p>
        </template>

        <!-- Coupon list -->
        <div class="flex flex-col items-center gap-2">
            <template x-for="coupon in coupons" :key="coupon.id">
   <label
    class="coupon-card p-2 text-sm transition"
    style="width:260px;max-width:260px;"
    :class="{
        'opacity-40 cursor-not-allowed': coupon.product_id && !hasCouponItem(coupon),
        'border-green-500 bg-green-50 ring-2 ring-green-300':
            manualCoupon && manualCoupon.id === coupon.id
    }"
>

           <input
    type="radio"
    name="selectedCoupon"
    :disabled="coupon.product_id && !hasCouponItem(coupon)"
    :checked="manualCoupon && manualCoupon.id === coupon.id"
    @change="
        if (coupon.product_id && !hasCouponItem(coupon)) return;
        manualCoupon = coupon;
        applyCoupons();
    "
>


            <div class="flex flex-col w-full gap-0.5">
                <div class="coupon-header">
                    <div class="coupon-title font-semibold text-xs" x-text="coupon.coupon_name"></div>
                    <div class="coupon-tag text-[10px] px-1 py-0.5 bg-green-100 rounded" 
                         x-text="coupon.discount_type"></div>
                </div>

                <div class="coupon-desc text-[11px] text-gray-600 leading-tight" 
                     x-text="coupon.coupon_desc ?? 'No description available.'"></div>

                <div class="text-[10px] text-gray-400">
                    Expires: <span x-text="coupon.end_date"></span>
                </div>

                <div class="text-xs font-bold text-green-700">

    <!-- FIXED AMOUNT -->
    <template x-if="coupon.discount_type === 'fixed'">
        <span>
            ₱<span x-text="Number(coupon.discount_value).toFixed(2)"></span> OFF
        </span>
    </template>

    <!-- PERCENTAGE -->
    <template x-if="coupon.discount_type === 'percentage'">
        <span>
            <span x-text="coupon.discount_value"></span>% OFF
        </span>
    </template>

    <!-- FREE DELIVERY -->
    <template x-if="coupon.discount_type === 'free delivery'">
        <span>FREE DELIVERY</span>
    </template>

</div>

            </div>
        </label>
    </template>
</div>
<div class="mt-3 flex justify-center">
    <button 
        class="bg-gray-300 py-2 px-6 text-sm rounded hover:bg-gray-400 transition"
        @click="showCouponModal = false">
        Close
    </button>
    </div>
</div>

</div>

                                    </div>
                                        </div>

                                            <p class="text-gray-600 text-sm">Delivery fee is calculated upon checkout</p>
                                        </div>
    
                                        <div class="border-t border-gray-200 mt-2 py-4 gap-1 flex flex-col text-lg">
                                            <div class="flex justify-between">
                                                <span class="font-medium text-gray-800 font-semibold">Total</span>
                                                <span class="font-bold">
                                                    <span 
                                                            x-text="
                                                                new Intl.NumberFormat('en-PH', { 
                                                                    style: 'currency', 
                                                                    currency: 'PHP' 
                                                                }).format(getTotal())
                                                            ">
                                                        </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="w-full mx-auto text-center">
                                        <a href="{{ route('checkout') }}" class="bg-primary custom-btn btn-primary-dark text-white text-center  py-3 rounded-md mt-2 w-full lg:max-w-sm flex justify-center ml-auto">Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <template x-if="carts?.length == 0">
                            <div class="w-full flex justify-center mb-10">
                                <div class="mt-6 px-6 flex items-center justify-center flex-col h-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                    <div class="font-bold text-lg">Your cart is empty</div>
                    
                                    <a href="{{ url('/menu') }}" class="bg-primary custom-btn btn-primary-dark text-white text-center px-6 py-3 rounded-md mt-4 w-full">Continue Shopping</a>
                                </div>
                            </div>
                        </template>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
     
<x-footer-component />

@endsection 