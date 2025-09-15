@extends('layouts.guest', ['page' => $page])

@section('title', 'Available Coupons')
@section('meta_description', 'View your available coupons.')

@section('alpine.plugins')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('content')

<div x-data="{ step: 1, accountType: 'individual', country: '{{ old('country', auth()->user()->country ?? '') }}' }"
    class="bg-cream">
    <div class="py-20 px-4 container">
        <div class="flex gap-6 lg:flex-row flex-col mt-10">
            <div class="w-full lg:w-1/4">
                <x-account-menu-component />
            </div>

            <div class="w-full lg:w-3/4">
                @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded-md mb-4">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
                @endif
                <div class="rounded-lg border bg-white border-[#DFDFDF] shadow-md">
                    <div class="px-6 py-4 border-b border-[#DFDFDF]">
                        <h2 class="font-semibold text-tertiary text-left uppercase">Available Coupons</h2>
                    </div>
                    <div class="flex items-start font-bold flex-col gap-2 px-5 py-5 border-b border-[#DFDFDF]">

                        <!-- Pixel-perfect Coupon Grid + Ticket-style Modal (Tailwind + Alpine) -->
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
                                open:false, active:{},
                                openModal(c){ this.active=c; this.open=true; document.body.classList.add('overflow-hidden') },
                                close(){ this.open=false; document.body.classList.remove('overflow-hidden') }
                            }" @keydown.escape.window="close()" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <!-- GRID: 1 / 2 / 3 columns -->
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2">

                                @foreach ($eligibleCoupons as $c)
                                @php
                                $expires = \Carbon\Carbon::parse(($c->end_date ?? '') . ' ' . ($c->end_time ??
                                '00:00'))->format('d M Y');
                                $points = $c->points ?? 100; // adjust to your field
                                $logo = $c->logo_url ?? null; // optional
                                $brand = $c->brand ?? '';
                                $locations = \Str::contains($c->location, 'all') ? 'All Stores' : ($c->location ? explode('|', $c->location) : []);
                                $discountAmount = $c->location_discount_type === 'full' ? 100 . '% off' : ($c->location_discount_type === 'partial' ? number_format($c->location_discount_amount, 0) . '% off' : ($c->reward == 'discount-amount-optn' ? '₱' . number_format($c->amount, 0) : ($c->reward == 'discount-percentage-optn' ? number_format($c->percentage, 0) . '% off' : '0')));
                                $headlinePct = $c->percentage ? ($c->percentage . '%') : null;
                                $reward = match (trim((string)($c->reward ?? ''))) {
                                'free-shipping-optn' => 'Free Shipping',
                                'discount-amount-optn' => 'Discount Amount',
                                'discount-percentage-optn' => 'Discount Percent',
                                'free-product-optn' => 'Free Product',
                                '' => 'Special Offer', // when null/empty
                                default => $c->reward, // any other custom label
                                };
                                @endphp

                                <!-- COUPON CARD -->
                                <button type="button" @click="openModal({
                                    id: {{ $c->id }},
                                    title: @js($c->name ?? 'Coupon'),
                                    desc: @js($c->description ?? ''),
                                    code: @js($c->coupon_code ?? ''),
                                    expires: @js($expires),
                                    percent: @js($headlinePct),
                                    locations: @js($locations ?? 'All stores'),
                                    logo: @js($logo),
                                    reward: @js($reward),
                                    discountAmount: @js($discountAmount),
                                    })" class="relative w-full text-left">

                                    <!-- card shell -->
                                    <div
                                        class="relative overflow-visible rounded-2xl border border-gray-200 bg-white shadow-sm">
                                        <!-- left vertical strip -->
                                        <div class="absolute inset-y-0 left-0 w-12 rounded-l-2xl overflow-hidden">
                                            <div class="h-full w-full bg-gradient-to-b from-indigo-600 to-violet-600">
                                            </div>
                                            <div
                                                class="absolute inset-y-0 left-0 flex w-12 items-center justify-center">
                                                <span
                                                    class="vertical-rl rotate-180 text-white/90 tracking-widest text-[11px] font-bold uppercase text-center ml-2">{{
                                                    $reward }}</span>
                                            </div>
                                        </div>

                                        <!-- side ticket holes -->
                                        <div class="pointer-events-none absolute top-1/2 -left-3 -translate-y-1/2 h-6 w-6 rounded-full border border-white"
                                            style="background:var(--page-bg);"></div>
                                        {{-- <div
                                            class="pointer-events-none absolute top-1/2 -right-3 -translate-y-1/2 h-6 w-6 rounded-full border border-white"
                                            style="background:var(--page-bg);"></div> --}}

                                        <!-- content -->
                                        <div class="pl-16 pr-28 py-4">
                                            <div class="flex items-start gap-3">


                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm leading-5 text-gray-800 flex flex-col gap-1">
                                                        <span class="font-semibold">{{ $c->name }}</span>
                                                        {!! $c->description
                                                        ? \Illuminate\Support\Str::of($c->description)->replace('%',
                                                        '<span class="font-extrabold">%</span>')
                                                        : ($c->name ?? 'Special offer') !!}

                                                    </p>

                                                    <div class="mt-3">
                                                        <p class="text-[11px] uppercase tracking-wide text-gray-400">
                                                            Expires</p>
                                                        <p class="text-sm font-semibold text-gray-900">{{ $expires }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- right pill button -->
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                            <span
                                                class="inline-flex items-center rounded-full bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-600">
                                                View
                                            </span>
                                        </div>
                                    </div>
                                </button>
                                @endforeach
                            </div>

                            <!-- MODAL: Ticket poster (orange), dashed cut, barcode -->
                            <div x-cloak x-show="open" class="fixed inset-0 z-50">
                                <!-- backdrop -->
                                <div class="fixed inset-0 bg-black/50" @click="close()"></div>

                                <!-- panel -->
                                <div class="fixed inset-0 flex items-center justify-center p-4">
                                    <div x-show="open" x-transition class="w-full max-w-sm">
                                        <div class="relative rounded-2xl bg-white shadow-2xl overflow-hidden">
                                            <!-- close -->
                                            <button @click="close()"
                                                class="absolute right-3 top-3 z-10 rounded-full bg-black/10 p-1 text-white hover:bg-black/20"
                                                aria-label="Close">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293 10 10l5.707-5.707 1.414 1.414L11.414 11.414l5.707 5.707-1.414 1.414L10 12.828l-5.707 5.707-1.414-1.414 5.707-5.707-5.707-5.707 1.414-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <!-- ticket body (orange poster) -->
                                            <div class="relative px-8 pt-12 pb-20 text-white"
                                                style="background: radial-gradient(120% 90% at 20% -10%, rgba(255,255,255,.15) 0%, rgba(255,255,255,0) 60%), linear-gradient(180deg,#ff8545 0%, #f4672b 100%);">
                                                <div class="text-center">
                                                    <p class="text-xl/5 opacity-90 py-5" x-text="active.reward"></p>
                                                    <h2 class="mt-2 text-5xl font-extrabold font-cubao"
                                                        x-text="active.discountAmount"></h2>
                                                    <p class="mt-3 opacity-90"
                                                        x-text="active.desc || 'Get this limited-time discount in-store or online.'">
                                                    </p>
                                                    <div>Applicable locations: <span x-text="active.locations"></span>
                                                    </div>
                                                </div>

                                                <div class="flex justify-center mt-10">
                                                    <div class="relative inline-block text-center">
                                                        <span
                                                            class="inline-flex items-center rounded-md border border-gray-200 bg-gray-50 pl-3 pr-10 py-2 font-mono text-sm text-gray-800 shadow-sm"
                                                            x-text="active.code">
                                                        </span>

                                                        <button type="button"
                                                            class="absolute right-1 top-1/2 -translate-y-1/2 inline-flex h-7 w-7 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 shadow-sm">
                                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2">
                                                                <rect x="9" y="9" width="13" height="13" rx="2" />
                                                                <path
                                                                    d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="mt-3 text-center text-sm opacity-90 w-full">
                                                    <div class="font-light">Valid until <span
                                                            x-text="active.expires"></span></div>
                                                </div>

                                            </div>

                                            <!-- side holes (center) to keep the ticket vibe even in modal -->
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

<script>
    function addressSelector() {
    return {
        regions: [],
        provinces: [],
        cities: [],
        barangays: [],
        provincesFiltered: [],
        citiesFiltered: [],
        barangaysFiltered: [],

        regionCode: '',
        provinceCode: '',
        cityCode: '',
        barangayCode: '',
        isReady: false,

        async loadData() {
            const [regions, provinces, cities, barangays] = await Promise.all([
                fetch('{{asset("addresses/region.json")}}').then(res => res.json()),
                fetch('{{asset("addresses/province.json")}}').then(res => res.json()),
                fetch('{{asset("addresses/city.json")}}').then(res => res.json()),
                fetch('{{asset("addresses/barangay.json")}}').then(res => res.json()),
            ]);

            this.regions = regions;
            this.provinces = provinces;
            this.cities = cities;
            this.barangays = barangays;

            this.isReady = true;

            this.$nextTick(() => {
                this.regionCode = @json(old('address_region', auth()->user()->address_region));
                this.filterProvinces();

                this.$nextTick(() => {
                    this.provinceCode = @json(old('address_municipality', auth()->user()->address_municipality));
                    this.filterCities();

                    this.$nextTick(() => {
                        this.cityCode = @json(old('address_city', auth()->user()->address_city));
                        this.filterBarangays();

                        this.$nextTick(() => {
                            this.barangayCode = @json(old('address_brgy', auth()->user()->address_brgy));
                        });
                    });
                });
            });
        },

        filterProvinces() {
            const region = this.regions.find(r => r.region_name === this.regionCode);
            const regionCode = region?.region_code;
            this.provincesFiltered = this.provinces.filter(p => p.region_code === regionCode);
            this.citiesFiltered = [];
            this.barangaysFiltered = [];
        },

        filterCities() {
            const province = this.provinces.find(p => p.province_name === this.provinceCode);
            const provinceCode = province?.province_code;
            this.citiesFiltered = this.cities.filter(c => c.province_code === provinceCode);
            this.barangaysFiltered = [];
        },

        filterBarangays() {
            const city = this.cities.find(c => c.city_name === this.cityCode);
            const cityCode = city?.city_code;
            this.barangaysFiltered = this.barangays.filter(b => b.city_code === cityCode);
        }
    };
}

</script>


@endsection