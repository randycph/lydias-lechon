@extends('admin.layouts.app')

@section('pagecss')
<link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2 {width:100% !important;}
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        position: relative;
        margin-top: 4px;
        margin-right: 4px;
        padding: 3px 10px 3px 20px;
        border-color: transparent;
        border-radius: 1px;
        background-color: #0168fa;
        color: #fff;
        font-size: 13px;
        line-height: 1.45;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
        color: #fff;
        opacity: .5;
        font-size: 14px;
        font-weight: 400;
        display: inline-block;
        position: absolute;
        top: 4px;
        left: 7px;
        line-height: 1.2;
    }
    .coupon-card {
        max-width: 700px;
        margin: auto;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        padding: 35px;
    }
    .form-label { font-weight: 600; }
    .btn-primary { padding: 10px 25px; border-radius: 10px; font-weight: 600; }
    .section-title { font-size: 1.3rem; font-weight: 700; margin-bottom: 20px; }
</style>
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('dashboard') }}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('coupons.index') }}">Coupons</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Coupon</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit Coupon</h4>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('coupon.update', $coupon->id) }}">
        @csrf
        <div class="coupon-card">
            <!-- Coupon Name -->
            <div class="mb-3">
                <label class="form-label">Coupon Name</label>
                <input type="text" name="coupon_name" class="form-control" value="{{ old('coupon_name', $coupon->coupon_name) }}">
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label">Coupon Description</label>
                <textarea name="coupon_desc" class="form-control">{{ old('coupon_desc', $coupon->coupon_desc) }}</textarea>
            </div>

            <!-- Code -->
            <div class="mb-3">
                <label class="form-label">Coupon Code</label>
                <input type="text" name="coupon_code" class="form-control" value="{{ old('code', $coupon->code) }}">
            </div>

            <!-- Discount Type -->
            <div class="mb-3">
                <label class="form-label">Discount Type</label>
                <select class="form-control" name="discount_type">
                    <option value="percentage" {{ $coupon->discount_type=='percentage'?'selected':'' }}>Percentage (%)</option>
                    <option value="fixed" {{ $coupon->discount_type=='fixed'?'selected':'' }}>Fixed Amount (₱)</option>
                    <option value="free delivery" {{ $coupon->discount_type=='free delivery'?'selected':'' }}>Free Delivery</option>
                </select>
            </div>

            <!-- Discount Value -->
            <div class="mb-3">
                <label class="form-label">Discount Value</label>
                <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value) }}">
            </div>

            <!-- Min Spend & Max Discount -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Minimum Spend</label>
                    <input type="number" name="min_spend" class="form-control" value="{{ old('min_spend', $coupon->min_spend) }}">
                </div>
                
            </div>

            <!-- Product Dropdown -->
            <div x-data="productDropdown()" x-init="loadProducts(); productId='{{ $coupon->product_id }}'">
                <label class="form-label">Product Discount</label>
                <select x-model="productId" name="product_id" class="form-control">
                    <option value="">-- Select Product --</option>
                    <template x-for="product in products" :key="product.id">
                        <option :value="product.id" x-text="product.name" :selected="product.id == {{ $coupon->product_id }}"></option>
                    </template>
                </select>
            </div>

            <!-- Usage Limit -->
            <div class="mb-3">
                <label class="form-label">Usage Limit</label>
                <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}">
            </div>

            <!-- Auto Apply -->
            <div class="mb-3">
                <label class="form-label">Auto Apply</label>
                <select class="form-control" name="auto_apply">
                    <option value="Yes" {{ $coupon->is_auto_apply=='Yes'?'selected':'' }}>Yes</option>
                    <option value="No" {{ $coupon->is_auto_apply=='No'?'selected':'' }}>No</option>
                </select>
            </div>

            <!-- Address -->
            <div x-data="addressForm()"
     x-init="
        loadData().then(() => {
            regionCode = '{{ $coupon->region_code }}';
            filterProvinces();

            provinceCode = '{{ $coupon->province_code }}';
            filterCities();

            cityCode = '{{ $coupon->city_code }}';
            filterBarangays();

            barangayCode = '{{ $coupon->barangay_code }}';
        })
     ">

                <!-- REGION -->
    <div class="mb-3">
        <label>Region</label>
        <select x-model="regionCode"
                @change="setRegionName($event)"
                class="form-control">
            <option value="">-- Select Region --</option>
            <template x-for="region in regions" :key="region.region_code">
                <option :value="region.region_code"
                        :data-name="region.region_name"
                        x-text="region.region_name">
                </option>
            </template>
        </select>
    </div>

    <!-- PROVINCE -->
    <div class="mb-3">
        <label>Province</label>
        <select x-model="provinceCode"
                @change="setProvinceName($event)"
                class="form-control">
            <option value="">-- Select Province --</option>
            <template x-for="p in provincesFiltered" :key="p.province_code">
                <option :value="p.province_code"
                        :data-name="p.province_name"
                        x-text="p.province_name">
                </option>
            </template>
        </select>
    </div>

    <!-- CITY -->
    <div class="mb-3">
        <label>City</label>
        <select x-model="cityCode"
                @change="setCityName($event)"
                class="form-control">
            <option value="">-- Select City --</option>
            <template x-for="c in citiesFiltered" :key="c.city_code">
                <option :value="c.city_code"
                        :data-name="c.city_name"
                        x-text="c.city_name">
                </option>
            </template>
        </select>
    </div>

    <!-- BARANGAY -->
    <div class="mb-3">
        <label>Barangay</label>
        <select x-model="barangayCode"
                @change="setBarangayName($event)"
                class="form-control">
            <option value="">-- Select Barangay --</option>
            <template x-for="b in barangaysFiltered" :key="b.brgy_code">
                <option :value="b.brgy_code"
                        :data-name="b.brgy_name"
                        x-text="b.brgy_name">
                </option>
            </template>
        </select>
    </div>

    <!-- ✅ REQUIRED HIDDEN INPUTS (SAVE TO DB) -->
    <input type="hidden" name="region_code" x-model="regionCode">
    <input type="hidden" name="province_code" x-model="provinceCode">
    <input type="hidden" name="city_code" x-model="cityCode">
    <input type="hidden" name="barangay_code" x-model="barangayCode">

</div>

            <!-- Dates -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $coupon->start_date) }}">
                    <input type="time" name="start_time" class="form-control mt-2" value="{{ old('start_time', $coupon->start_time) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $coupon->end_date) }}">
                    <input type="time" name="end_time" class="form-control mt-2" value="{{ old('end_time', $coupon->end_time) }}">
                </div>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="active" {{ $coupon->status==active?'selected':'' }}>Active</option>
                    <option value="inactive" {{ $coupon->status==inactive?'selected':'' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Coupon</button>
        </div>
    </form>
</div>
@endsection

@section('pagejs')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection

@section('customjs')
<script>
function addressForm() {
    return {
        regions: [], provinces: [], cities: [], barangays: [],
        provincesFiltered: [], citiesFiltered: [], barangaysFiltered: [],
        regionCode:'', provinceCode:'', cityCode:'', barangayCode:'',
        regionName:'', provinceName:'', cityName:'', barangayName:'',

        async loadData() {
            const [regions, provinces, cities, barangays] = await Promise.all([
                fetch('/addresses/region.json').then(r=>r.json()),
                fetch('/addresses/province.json').then(r=>r.json()),
                fetch('/addresses/city.json').then(r=>r.json()),
                fetch('/addresses/barangay.json').then(r=>r.json()),
            ]);
            this.regions = regions;
            this.provinces = provinces;
            this.cities = cities;
            this.barangays = barangays;
            this.filterProvinces();
            this.filterCities();
            this.filterBarangays();
        },

        setRegionName(e){ this.regionName = e.target.selectedOptions[0].dataset.name; this.filterProvinces(); },
        setProvinceName(e){ this.provinceName = e.target.selectedOptions[0].dataset.name; this.filterCities(); },
        setCityName(e){ this.cityName = e.target.selectedOptions[0].dataset.name; this.filterBarangays(); },
        setBarangayName(e){ this.barangayName = e.target.selectedOptions[0].dataset.name; },

        filterProvinces(){ this.provincesFiltered = this.provinces.filter(p=>p.region_code===this.regionCode); this.provinceCode=''; this.filterCities(); },
        filterCities(){ this.citiesFiltered = this.cities.filter(c=>c.province_code===this.provinceCode); this.cityCode=''; this.filterBarangays(); },
        filterBarangays(){ this.barangaysFiltered = this.barangays.filter(b=>b.city_code===this.cityCode); this.barangayCode=''; }
    }
}

function productDropdown() {
    return {
        products: [], productId:'',
        async loadProducts(categoryId='') {
            let url='/products_list';
            if(categoryId) url += '?category_id='+categoryId;
            const res = await fetch(url);
            this.products = await res.json();
        }
    }
}
</script>
@endsection
