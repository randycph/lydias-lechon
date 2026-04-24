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
                        <h2 class="font-semibold text-tertiary text-left uppercase">Coupons I Used</h2>
                    </div>

                    <div class="flex items-start font-bold flex-col gap-2 px-5 py-5 border-b border-[#DFDFDF]">

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

                        <div x-data="{
                                open:false,
                                active:{},
                                openModal(c){
                                    this.active = c;
                                    this.open = true;
                                    document.body.classList.add('overflow-hidden');
                                },
                                close(){
                                    this.open = false;
                                    document.body.classList.remove('overflow-hidden');
                                }
                            }"
                            @keydown.escape.window="close()"
                            class="max-w-7xl w-full">

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 w-full">

                                @forelse ($usedCoupons as $used)
                                    @php
                                        $c = $used->coupon;

                                        $couponName = $c->name ?? $used->coupon_code ?? 'Coupon';
                                        $couponCode = $c->coupon_code ?? $used->coupon_code ?? '-';
                                        $couponDescription = $c->description ?? 'This coupon was already used.';

                                        $usedDate = $used->created_at
                                            ? \Carbon\Carbon::parse($used->created_at)->format('d M Y')
                                            : '-';

                                        $expires = $c && $c->end_date
                                            ? \Carbon\Carbon::parse(($c->end_date ?? '') . ' ' . ($c->end_time ?? '00:00'))->format('d M Y')
                                            : '-';

                                        $locations = $c && $c->location
                                            ? (\Illuminate\Support\Str::contains($c->location, 'all') ? 'All Stores' : explode('|', $c->location))
                                            : 'N/A';

                                        $purchase_amount = $c->purchase_amount ?? 0;
                                        $percentage = $c->percentage ?? 0;
                                        $amount = $c->amount ?? 0;

                                        $reward = match (trim((string)($c->reward ?? ''))) {
                                            'free-shipping-optn' => 'Free Shipping',
                                            'discount-amount-optn' => 'Discount Amount',
                                            'discount-percentage-optn' => 'Discount Percent',
                                            'free-product-optn' => 'Free Product',
                                            '' => 'Used Coupon',
                                            default => $c->reward,
                                        };

                                        if (($used->discount_used ?? 0) > 0) {
                                            $discountAmount = '₱' . number_format($used->discount_used, 2);
                                        } elseif ($c && $c->reward == 'discount-amount-optn') {
                                            $discountAmount = '₱' . number_format($amount, 0);
                                        } elseif ($c && $c->reward == 'discount-percentage-optn') {
                                            $discountAmount = number_format($percentage, 0) . '% off';
                                        } elseif ($c && $c->reward == 'free-shipping-optn') {
                                            $discountAmount = 'Free Shipping';
                                        } else {
                                            $discountAmount = 'Used';
                                        }
                                    @endphp

                                    <button type="button"
                                        @click="openModal({
                                            title: @js($couponName),
                                            desc: @js($couponDescription),
                                            code: @js($couponCode),
                                            usedDate: @js($usedDate),
                                            expires: @js($expires),
                                            locations: @js($locations),
                                            reward: @js($reward),
                                            purchase_amount: @js($purchase_amount),
                                            discountAmount: @js($discountAmount),
                                            percentage: @js($percentage),
                                            amount: @js($amount),
                                            orderId: @js($used->sales_header_id ?? '-'),
                                            discountUsed: @js('₱' . number_format($used->discount_used ?? 0, 2))
                                        })"
                                        class="relative w-full text-left">

                                        <div class="relative overflow-visible rounded-2xl border border-gray-200 bg-white shadow-sm opacity-90">

                                            <!-- USED STAMP -->
                                            <div class="absolute top-4 right-14 z-20 rotate-[-18deg] pointer-events-none">
                                                <span class="inline-block border-[4px] border-red-700 text-red-700 font-black text-sm sm:text-base tracking-[0.35em] px-4 py-1 rounded-lg bg-white/80 shadow-md uppercase">
                                                    USED
                                                </span>
                                            </div>

                                            <div class="absolute inset-y-0 left-0 w-12 rounded-l-2xl overflow-hidden">
                                                <div class="h-full w-full bg-gradient-to-b from-gray-500 to-gray-700"></div>

                                                <div class="absolute inset-y-0 left-0 flex w-12 items-center justify-center">
                                                    <span class="vertical-rl rotate-180 text-white/90 tracking-widest text-[11px] font-bold uppercase text-center ml-2">
                                                        Used
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="pointer-events-none absolute top-1/2 -left-3 -translate-y-1/2 h-6 w-6 rounded-full border border-white"
                                                style="background:var(--page-bg);"></div>

                                            <div class="pl-16 pr-28 py-4">
                                                <div class="flex items-start gap-3">
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-sm leading-5 text-gray-800 flex flex-col gap-1">
                                                            <span class="font-semibold">{{ $couponName }}</span>

                                                            {!! $couponDescription
                                                                ? \Illuminate\Support\Str::of($couponDescription)->replace('%', '<span class="font-extrabold">%</span>')
                                                                : 'Used coupon' !!}
                                                        </p>

                                                        <div class="mt-1">
                                                            <p class="text-[11px] uppercase tracking-wide text-gray-400">
                                                                Discount Used
                                                            </p>
                                                            <p class="text-sm font-semibold text-gray-900">
                                                                {{ $discountAmount }}
                                                            </p>
                                                        </div>

                                                        <div class="mt-1">
                                                            <p class="text-[11px] uppercase tracking-wide text-gray-400">
                                                                Used Date
                                                            </p>
                                                            <p class="text-sm font-semibold text-gray-900">
                                                                {{ $usedDate }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                                <span class="inline-flex items-center rounded-full bg-gray-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-600">
                                                    View
                                                </span>
                                            </div>
                                        </div>
                                    </button>
                                @empty
                                    <div class="col-span-1 sm:col-span-2 text-center py-10 text-gray-500">
                                        You have not used any coupons yet.
                                    </div>
                                @endforelse
                            </div>

                            <div x-cloak x-show="open" class="fixed inset-0 z-50">
                                <div class="fixed inset-0 bg-black/50" @click="close()"></div>

                                <div class="fixed inset-0 flex items-center justify-center p-4">
                                    <div x-show="open" x-transition class="w-full max-w-sm">
                                        <div class="relative rounded-2xl bg-white shadow-2xl overflow-hidden">

                                            <button @click="close()"
                                                class="absolute right-3 top-3 z-30 rounded-full bg-black/10 p-1 text-white hover:bg-black/20"
                                                aria-label="Close">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293 10 10l5.707-5.707 1.414 1.414L11.414 11.414l5.707 5.707-1.414 1.414L10 12.828l-5.707 5.707-1.414-1.414 5.707-5.707-5.707-5.707 1.414-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <div class="relative px-8 pt-12 pb-20 text-white overflow-hidden"
                                                style="background: radial-gradient(120% 90% at 20% -10%, rgba(255,255,255,.15) 0%, rgba(255,255,255,0) 60%), linear-gradient(180deg,#6b7280 0%, #374151 100%);">

                                                <!-- BIG MODAL USED STAMP -->
                                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                                                    <span class="rotate-[-20deg] border-[6px] border-red-200 text-red-200/90 font-black text-4xl sm:text-5xl tracking-[0.35em] px-6 py-2 rounded-xl uppercase">
                                                        USED
                                                    </span>
                                                </div>

                                                <div class="relative z-20 text-center">
                                                    <p class="text-xl/5 opacity-90 py-5" x-text="active.reward"></p>

                                                    <h2 class="mt-2 text-5xl font-extrabold font-cubao"
                                                        x-text="active.discountAmount"></h2>

                                                    <p class="mt-3 opacity-90"
                                                        x-text="active.desc || 'This coupon was already used.'">
                                                    </p>

                                                    <template x-if="active.locations != ''">
                                                        <div class="mt-3 text-sm">
                                                            Applicable locations:
                                                            <span x-text="active.locations"></span>
                                                        </div>
                                                    </template>
                                                </div>

                                                <div class="relative z-20 flex justify-center mt-10">
                                                    <div class="relative inline-block text-center">
                                                        <span class="inline-flex items-center rounded-md border border-gray-200 bg-gray-50 px-3 py-2 font-mono text-sm text-gray-800 shadow-sm"
                                                            x-text="active.code">
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="relative z-20 mt-5 text-center text-sm opacity-90 w-full space-y-1">
                                                    <div class="font-light">
                                                        Used on <span x-text="active.usedDate"></span>
                                                    </div>

                                                    <div class="font-light">
                                                        Order # <span x-text="active.orderId"></span>
                                                    </div>

                                                    <div class="font-light">
                                                        Valid until <span x-text="active.expires"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pointer-events-none absolute top-1/2 -left-3 -translate-y-1/2 h-6 w-6 rounded-full border border-white/60"
                                                style="background:var(--page-bg);"></div>

                                            <div class="pointer-events-none absolute top-1/2 -right-3 -translate-y-1/2 h-6 w-6 rounded-full border border-white/60"
                                                style="background:var(--page-bg);"></div>
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