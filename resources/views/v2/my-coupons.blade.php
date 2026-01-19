@extends('layouts.guest', ['page' => $page])

@section('title', 'Available Coupons')
@section('meta_description', 'View your available coupons.')

@section('alpine.plugins')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>

        body {
            background: #e8f6e8;
            font-family: Arial, sans-serif;
        }

        .coupon-card {
            background: #ffffff;
            border: 2px dashed #2e7d32;
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 20px;
            position: relative;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Perforated cutouts */
        .coupon-card:before,
        .coupon-card:after {
            content: "";
            width: 24px;
            height: 24px;
            background: #e8f6e8;
            border-radius: 50%;
            position: absolute;
        }

        .coupon-card:before {
            top: 50%;
            left: -12px;
            transform: translateY(-50%);
        }

        .coupon-card:after {
            top: 50%;
            right: -12px;
            transform: translateY(-50%);
        }

        .coupon-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .coupon-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1b5e20;
        }

        .coupon-tag {
            background: #ff8c3f;
            color: white;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .coupon-desc {
            font-size: 0.85rem;
            margin: 5px 0;
        }

        .coupon-validity {
            font-size: 0.75rem;
            color: #4e6e54;
        }

        .redeem-btn {
            margin-top: 8px;
            width: 100%;
            background: #2e7d32;
            color: white;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 6px;
            border-radius: 8px;
        }

        .redeem-btn:hover {
            background: #256428;
        }


    </style>
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
                    <div class="container py-5">
                        
                       @foreach ($eligibleCoupons as $coupon)
    <div class="coupon-card">  
        <div class="coupon-content">
            <div class="coupon-header">
                <div class="coupon-title">{{ $coupon->coupon_name }}</div>
                <div class="coupon-title">Code: {{ $coupon->code }}</div>
                <div class="coupon-tag">{{ $coupon->discount_value }}</div>
                <div class="coupon-tag">{{ $coupon->discount_type }}</div>
            </div>

            <p class="coupon-desc">{{ $coupon->coupon_desc ?? 'No description available.' }}</p>

            <div class="coupon-validity">
                Expires in: 
                <span class="timer" data-expiry="{{ $coupon->end_date }}T{{ $coupon->end_time }}"></span>
            </div>

            <button class="btn redeem-btn" data-id="{{ $coupon->id }}">Redeem Now</button>
        </div>
    </div>
@endforeach

                                        
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
<script>
document.querySelectorAll('.timer').forEach(timer => {
    let card = timer.closest('.coupon-card');
    let expiry = new Date(timer.dataset.expiry).getTime();

    function updateTimer() {
        let now = Date.now();
        let distance = expiry - now;

        if (distance <= 0) {
            // Remove the coupon card from DOM
            if (card) {
                card.remove();
            }
            return;
        }

        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timer.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    updateTimer();
    setInterval(updateTimer, 1000);
});

</script>

<script>
document.querySelectorAll('.redeem-btn').forEach(btn => {
    btn.addEventListener('click', function () {

        let id = this.dataset.id;
        let button = this;

        fetch('/redeem-coupon/' + id, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                alert(data.success);

                button.textContent = "Claimed";
                button.classList.remove("btn-primary");
                button.classList.add("btn-success");
                button.disabled = true;
            }
        });

    });
});
</script>

@endsection