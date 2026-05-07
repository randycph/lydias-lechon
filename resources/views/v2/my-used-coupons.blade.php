@extends('layouts.guest', ['page' => $page])

@section('title', 'Used Coupons')
@section('meta_description', 'View your used coupons.')

@section('alpine.plugins')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('content')

<div class="bg-cream">
    <div class="py-20 px-4 container">
        <div class="flex gap-6 lg:flex-row flex-col mt-10">
            <div class="w-full lg:w-1/4">
                <x-account-menu-component />
            </div>

            <div class="w-full lg:w-3/4">
                <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md">
                    <div class="px-6 py-4 border-b border-[#DFDFDF]">
                        <h2 class="font-semibold text-tertiary text-left uppercase">
                            Coupons
                        </h2>
                    </div>

                    <div class="flex items-start font-bold flex-col gap-2 px-5 py-5 border-b border-[#DFDFDF]">

                     <style>
    [x-cloak] {
        display: none !important;
    }

    /* =========================
       COUPON GRID
    ========================= */
    .coupon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 12px;
        width: 100%;
    }

    @media (max-width: 768px) {
        .coupon-grid {
            grid-template-columns: 1fr;
        }
    }

    /* =========================
       COUPON CARD BASE
    ========================= */
    .coupon-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all .2s ease;
    }

    .coupon-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
    }

    .coupon-card::before,
    .coupon-card::after {
        content: "";
        position: absolute;
        top: 50%;
        width: 18px;
        height: 18px;
        background: #fff7ed;
        border: 1px solid #e5e7eb;
        border-radius: 999px;
        transform: translateY(-50%);
        z-index: 5;
    }

    .coupon-card::before {
        left: -10px;
    }

    .coupon-card::after {
        right: -10px;
    }

    .coupon-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(0, 0, 0, .07) 1px, transparent 1px);
        background-size: 14px 14px;
        opacity: .30;
        pointer-events: none;
    }

    /* =========================
       COMPACT COUPON PREVIEW
    ========================= */
    .coupon-inner {
        position: relative;
        display: flex;
        min-height: 88px;
        z-index: 2;
    }

    .coupon-left {
    width: 38%;
    background: linear-gradient(135deg, #0f8f43 0%, #0b6b33 100%);
    color: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 8px 6px;
}

    .coupon-left-label {
        font-size: 6px;
        letter-spacing: .16em;
        text-transform: uppercase;
        font-weight: 800;
        opacity: .9;
    }

    .coupon-discount {
        margin-top: 5px;
        font-size: 15px;
        line-height: 1;
        font-weight: 950;
        word-break: break-word;
    }

    .coupon-reward {
        margin-top: 5px;
        font-size: 7px;
        line-height: 1.1;
        text-transform: uppercase;
        font-weight: 800;
        opacity: .95;
    }

    .coupon-right {
        position: relative;
        width: 62%;
        background: #ffffff;
        padding: 9px 10px 8px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-width: 0;
    }

    .coupon-right::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        border-left: 2px dashed #cbd5e1;
    }

    .coupon-title {
        padding-right: 55px;
        font-size: 12px;
        line-height: 1.15;
        font-weight: 950;
        color: #111827;
        word-break: break-word;
    }

    .coupon-stamp {
        position: absolute;
        right: 8px;
        top: 7px;
        z-index: 3;
        display: inline-block;
        border: 2px solid #dc2626;
        color: #dc2626;
        background: rgba(255, 255, 255, .92);
        border-radius: 4px;
        padding: 2px 5px;
        font-size: 8px;
        line-height: 1;
        font-weight: 950;
        letter-spacing: .16em;
        transform: rotate(-14deg);
    }

    .coupon-bottom {
        margin-top: 6px;
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 6px;
    }

    .coupon-small-label {
        font-size: 6px;
        letter-spacing: .14em;
        text-transform: uppercase;
        font-weight: 900;
        color: #9ca3af;
    }

    .coupon-date {
        font-size: 9px;
        font-weight: 950;
        color: #111827;
    }

    .coupon-view {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    background: #0f8f43;
    color: #ffffff;
    padding: 4px 10px;
    font-size: 8px;
    font-weight: 900;
    box-shadow: 0 3px 8px rgba(0, 0, 0, .12);
    white-space: nowrap;
    border: 0;
    cursor: pointer;
}

    .coupon-view:hover {
    background: #0b6b33;
}

    /* =========================
       MODAL
    ========================= */
    .coupon-modal-wrap {
        width: 100%;
        max-width: 360px;
    }

    .coupon-modal-wrap .coupon-card:hover {
        transform: none;
    }

    .coupon-modal-top {
    position: relative;
    background: linear-gradient(135deg, #0f8f43 0%, #0b6b33 100%);
    color: #ffffff;
    text-align: center;
    padding: 46px 24px 28px;
    overflow: hidden;
}

    .coupon-modal-stamp {
        position: absolute;
        left: 16px;
        top: 16px;
        transform: rotate(-14deg);
        border: 2px solid rgba(220, 38, 38, .95);
        color: #dc2626;
        background: rgba(255, 255, 255, .92);
        border-radius: 6px;
        padding: 4px 12px;
        font-size: 13px;
        font-weight: 950;
        letter-spacing: .2em;
        z-index: 5;
    }

    .coupon-modal-content {
        position: relative;
        z-index: 2;
    }

    .coupon-modal-label {
        font-size: 10px;
        letter-spacing: .28em;
        text-transform: uppercase;
        font-weight: 900;
        opacity: .95;
    }

    .coupon-modal-discount {
        margin-top: 12px;
        font-size: 34px;
        line-height: 1.05;
        font-weight: 950;
        word-break: break-word;
    }

    .coupon-modal-reward {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 800;
    }

    .coupon-modal-body {
        position: relative;
        background: #ffffff;
        padding: 20px 22px;
    }

    .coupon-modal-body::before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        border-top: 2px dashed #cbd5e1;
    }

    /* =========================
       MODAL DESCRIPTION
    ========================= */
    .modal-description-box {
        margin-top: 12px;
        padding: 10px 12px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
    }

    .modal-description-label {
        font-size: 9px;
        letter-spacing: .18em;
        text-transform: uppercase;
        font-weight: 900;
        color: #9ca3af;
        margin-bottom: 5px;
    }

    .modal-description-text {
        font-size: 12px;
        line-height: 1.45;
        font-weight: 700;
        color: #374151;
    }

    /* =========================
       MODAL DETAILS
    ========================= */
    .modal-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding-bottom: 7px;
        margin-bottom: 7px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 12px;
    }

    .modal-row span:first-child {
        color: #6b7280;
        font-weight: 700;
    }

    .modal-row span:last-child {
        color: #111827;
        font-weight: 950;
        text-align: right;
    }

    /* =========================
       CLOSE BUTTON
    ========================= */
    .coupon-close-btn {
        margin-top: 14px;
        width: 100%;
        border-radius: 999px;
        background: #111827;
        color: #ffffff;
        padding: 10px 20px;
        font-size: 12px;
        font-weight: 900;
        border: 0;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
    }

    .coupon-close-btn:hover {
        background: #1f2937;
    }
</style>

                        <div
                            x-data="{
                                open: false,
                                active: {},
                                openModal(c) {
                                    this.active = c;
                                    this.open = true;
                                    document.body.classList.add('overflow-hidden');
                                },
                                close() {
                                    this.open = false;
                                    document.body.classList.remove('overflow-hidden');
                                }
                            }"
                            @keydown.escape.window="close()"
                            class="max-w-7xl w-full"
                        >

                            <div class="coupon-grid">

                                @forelse ($usedCoupons as $used)
                                    @php
                                        $c = $used->coupon;

                                        $couponName = $c->name ?? $used->coupon_code ?? 'Coupon';
                                        $couponDescription = $c->description ?? 'This coupon was already used.';
                                        $couponDescriptionText = strip_tags($couponDescription);

                                        $usedDate = $used->created_at
                                            ? \Carbon\Carbon::parse($used->created_at)->format('d M Y')
                                            : '-';

                                        $expires = $c && $c->end_date
                                            ? \Carbon\Carbon::parse(($c->end_date ?? '') . ' ' . ($c->end_time ?? '00:00'))->format('d M Y')
                                            : '-';

                                        $locations = $c && $c->location
                                            ? (\Illuminate\Support\Str::contains($c->location, 'all') ? 'All Stores' : explode('|', $c->location))
                                            : 'N/A';

                                        $locationText = is_array($locations) ? implode(', ', $locations) : $locations;

                                        $purchase_amount = $c->purchase_amount ?? 0;
                                        $percentage = $c->percentage ?? 0;
                                        $amount = $c->amount ?? 0;

                                        $reward = match (trim((string)($c->reward ?? ''))) {
                                            'free-shipping-optn' => 'Free Shipping',
                                            'discount-amount-optn' => 'Amount Discount',
                                            'discount-percentage-optn' => 'Percentage Discount',
                                            'free-product-optn' => 'Free Product',
                                            '' => 'Used Coupon',
                                            default => $c->reward,
                                        };

                                        if (($used->discount_used ?? 0) > 0) {
                                            $discountAmount = '₱' . number_format($used->discount_used, 2);
                                        } elseif ($c && $c->reward == 'discount-amount-optn') {
                                            $discountAmount = '₱' . number_format($amount, 0);
                                        } elseif ($c && $c->reward == 'discount-percentage-optn') {
                                            $discountAmount = number_format($percentage, 0) . '% OFF';
                                        } elseif ($c && $c->reward == 'free-shipping-optn') {
                                            $discountAmount = 'FREE SHIP';
                                        } elseif ($c && $c->reward == 'free-product-optn') {
                                            $discountAmount = 'FREE ITEM';
                                        } else {
                                            $discountAmount = 'USED';
                                        }
                                    @endphp
                                    <div class="w-full text-left">
                                        <div class="coupon-card">
                                            <div class="coupon-pattern"></div>

                                            <div class="coupon-inner">
                                                <div class="coupon-left">
                                                    <div class="coupon-left-label">Used Coupon</div>

                                                    <div class="coupon-discount">
                                                        {{ $discountAmount }}
                                                    </div>

                                                    <div class="coupon-reward">
                                                        {{ $reward }}
                                                    </div>
                                                </div>

                                                <div class="coupon-right">
                                                    <div class="coupon-stamp">USED</div>

                                                    <h3 class="coupon-title">
                                                        {{ $couponName }}
                                                    </h3>

                                                    <div class="coupon-bottom">
                                                        <div class="coupon-preview-info">
                                                            <div class="coupon-info-row">
                                                                <span class="coupon-small-label">Order #</span>
                                                                <span class="coupon-date">{{ $used->sales_header_id ?? '-' }}</span>
                                                            </div>
                                                            <div class="coupon-info-row">
                                                                <span class="coupon-small-label">Used</span>
                                                                <span class="coupon-date">{{ $usedDate }}</span>
                                                            </div>
                                                        </div>

                                                        <button
                                                            type="button"
                                                            class="coupon-view"
                                                            @click="openModal({
                                                                title: @js($couponName),
                                                                desc: @js($couponDescriptionText),
                                                                usedDate: @js($usedDate),
                                                                expires: @js($expires),
                                                                locations: @js($locationText),
                                                                reward: @js($reward),
                                                                purchase_amount: @js($purchase_amount),
                                                                discountAmount: @js($discountAmount),
                                                                percentage: @js($percentage),
                                                                amount: @js($amount),
                                                                orderId: @js($used->sales_header_id ?? '-'),
                                                                discountUsed: @js('₱' . number_format($used->discount_used ?? 0, 2))
                                                            })"
                                                        >
                                                            View
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-1 sm:col-span-2 text-center py-10 text-gray-500">
                                        You have not used any coupons yet.
                                    </div>
                                @endforelse
                            </div>

                            <div x-cloak x-show="open" class="fixed inset-0 z-50">
                                <div
                                    x-show="open"
                                    x-transition.opacity
                                    class="fixed inset-0 bg-black/60"
                                    @click="close()"
                                ></div>

                                <div class="fixed inset-0 flex items-center justify-center p-4">
                                    <div
                                        x-show="open"
                                        x-transition
                                        class="coupon-modal-wrap"
                                    >
                                        <div class="coupon-card bg-white">

                                            <button
                                                @click="close()"
                                                class="absolute right-3 top-3 z-30 rounded-full bg-black/10 p-2 text-white hover:bg-black/20"
                                                aria-label="Close"
                                            >
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M4.293 4.293 10 10l5.707-5.707 1.414 1.414L11.414 11.414l5.707 5.707-1.414 1.414L10 12.828l-5.707 5.707-1.414-1.414 5.707-5.707-5.707-5.707 1.414-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>

                                            <div class="coupon-modal-top">
                                                <div class="coupon-pattern"></div>

                                                <div class="coupon-modal-stamp">
                                                    USED
                                                </div>

                                                <div class="coupon-modal-content">
                                                    <div class="coupon-modal-label">
                                                        Used Coupon
                                                    </div>

                                                    <div
                                                        class="coupon-modal-discount"
                                                        x-text="active.discountAmount"
                                                    ></div>

                                                    <div
                                                        class="coupon-modal-reward"
                                                        x-text="active.reward"
                                                    ></div>
                                                </div>
                                            </div>

                                            <div class="coupon-modal-body">
                                                <h3
                                                    class="text-lg font-black text-gray-900 text-center"
                                                    x-text="active.title"
                                                ></h3>

                                                <div class="modal-description-box">
                                                    <div class="modal-description-label">
                                                        Description
                                                    </div>

                                                    <div
                                                        class="modal-description-text"
                                                        x-text="active.desc || 'This coupon was already used.'"
                                                    ></div>
                                                </div>

                                                

                                                <div class="mt-5">
                                                    <div class="modal-row">
                                                        <span>Used on</span>
                                                        <span x-text="active.usedDate"></span>
                                                    </div>

                                                    <div class="modal-row">
                                                        <span>Order #</span>
                                                        <span x-text="active.orderId"></span>
                                                    </div>

                                                    <div class="modal-row">
                                                        <span>Valid until</span>
                                                        <span x-text="active.expires"></span>
                                                    </div>

                                                    <div class="modal-row">
                                                        <span>Location</span>
                                                        <span x-text="active.locations"></span>
                                                    </div>
                                                </div>

                                                <button
                                                type="button"
                                                @click="close()"
                                                class="coupon-close-btn"
                                            >
                                                Close
                                            </button>
                                            </div>
                                        </div>
                                    </div>
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

@endsection