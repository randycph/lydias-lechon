<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@extends('admin.layouts.app')


@section('pagecss')
<link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
{{--
<link href="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet"> --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
	.select2 {
		width: 100% !important;
	}

	.select2-container--default .select2-selection--multiple .select2-selection__choice {
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

	.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
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
		box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
		padding: 35px;
	}

	.form-label {
		font-weight: 600;
	}

	.btn-primary {
		padding: 10px 25px;
		border-radius: 10px;
		font-weight: 600;
	}

	.section-title {
		font-size: 1.3rem;
		font-weight: 700;
		margin-bottom: 20px;
	}
</style>

@endsection

@section('content')
<div class="container pd-x-0">
	<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
		<div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb breadcrumb-style1 mg-b-10">
					<li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="{{ route('coupons.index') }}">Coupons</a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Create Coupon</li>
				</ol>
			</nav>
			<h4 class="mg-b-0 tx-spacing--1">Create Coupon</h4>
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
	<form method="POST" action="{{ route('coupon.insert') }}">
		@csrf
		<div class="coupon-card">
			<div class="mb-3">
				<label class="form-label">Coupon Name *</label>
				<input type="text" name="coupon_name" class="form-control" placeholder="e.g. Special Coupon">
			</div>
			<div class="mb-3">
				<label class="form-label">Coupon Description *</label>
				<textarea name="coupon_desc" class="form-control"
					placeholder="e.g. 20% off on the selected items"></textarea>
			</div>
			<div class="mb-3">
				<label class="form-label">Coupon Code *</label>
				<input type="text" name="coupon_code" class="form-control" placeholder="e.g. SAVE20">
			</div>


			<div class="mb-3">
				<label class="form-label">Discount Type *</label>
				<select class="form-control" name="discount_type" id="discount_type">
					<option value="percentage">Percentage (%)</option>
					<option value="fixed">Fixed Amount</option>
					<option value="free delivery">Free Delivery</option>
				</select>
			</div>

			<div class="mb-3">
				<label class="form-label">Discount Value *</label>
				<div class="input-group">
					<span class="input-group-text" id="discount_symbol">%</span>
					<input type="number" name="discount_value" class="form-control" placeholder="Enter value" min="0"
						step="0.01">
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 mb-3">
					<label class="form-label">Minimum Spend *</label>
					<input type="number" name="min_spend" class="form-control" placeholder="Minimum spend" required
						min="0" step="0.01">
				</div>
			</div>


			<div x-data="categoryProductDropdown()">

				<div class="mb-3">
					<label class="form-label">Category</label>
					<select x-model="selectedCategory" @change="fetchProducts()" class="form-control">
						<option value="">-- Select Category --</option>
						<template x-for="category in categories" :key="category.id">
							<option :value="category.id" x-text="category.name"></option>
						</template>
					</select>
				</div>
				<div class="form-group">
					<div class="mb-3 reward-option" id="free-shipping-optn"
						style="display:@if($errors->any() && old('reward') == 'free-shipping-optn') block @else none @endif">
						<label class="d-block">Location *</label>
						<select class="form-control select2 select-location" name="location[]" multiple="multiple"
							style="min-height: 32px;">
							<option value="all">All Area</option>
							@foreach($locations as $location)
							<option @if(is_array(old('location')) && in_array($location->city, old('location')))
								selected @endif value="{{$location->city}}">{{ $location->city }}</option>
							@endforeach
						</select>

						<br><br>
						<label class="d-block">Discount Type *</label>
						<div class="row" style="padding-bottom: 10px;">
							<div class="col-6">
								<div class="custom-control custom-radio">
									<input @if(old('discount_type')=='partial' ) checked @endif checked type="radio"
										id="coupon-discount-type-partial" name="discount_type"
										class="custom-control-input" value="partial" onchange="sf_discount_type()">
									<label class="custom-control-label"
										for="coupon-discount-type-partial">Partial</label>
								</div>
							</div>
							<div class="col-6">
								<div class="custom-control custom-radio">
									<input @if(old('discount_type')=='full' ) checked @endif type="radio"
										id="coupon-discount-type-full" name="discount_type" class="custom-control-input"
										value="full" onchange="sf_discount_type()">
									<label class="custom-control-label" for="coupon-discount-type-full">Full</label>
								</div>
							</div>
						</div>

						<label id="discount_amount_label"
							style="display: @if(old('discount_type') == 'full') none @else block @endif;">Shipping Fee
							Discount Amount *</label>
						<input style="display: @if(old('discount_type') == 'full') none @else block @endif;"
							type="number" name="shipping_fee_discount_amount"
							class="form-control @error('shipping_fee_discount_amount') is-invalid @enderror"
							id="discount_amount_input" value="{{ old('shipping_fee_discount_amount') }}">

					</div>

					<div class="mb-3 reward-option" id="discount-amount-optn"
						style="display:@if($errors->any() && old('reward') == 'discount-amount-optn') block @else none @endif">
						<label class="d-block">Discount Amount *</label>
						<input name="discount_amount" type="number"
							class="form-control @error('discount_amount') is-invalid @enderror"
							value="{{ old('discount_amount') }}" placeholder="Php">

					</div>

					<div class="mb-3 reward-option" id="discount-percentage-optn"
						style="display:@if($errors->any() && old('reward') == 'discount-percentage-optn') block @else none @endif">
						<label class="d-block">Discount Percentage % *</label>
						<input name="discount_percentage" type="number"
							class="form-control @error('discount_percentage') is-invalid @enderror" placeholder="%"
							value="{{ old('discount_percentage') }}">

					</div>

					<div id="div_product_amount"
						style="display: @if(old('reward') == 'discount-amount-optn' || old('reward') == 'discount-percentage-optn') block @else none @endif;">
						<div class="row" style="padding-bottom: 10px;margin-top: 20px;">
							<div class="col-6">
								<div class="custom-control custom-radio">
									<input @if(old('amount_discount')==1) checked @endif checked type="radio"
										id="discount-total-amount" name="amount_discount" class="custom-control-input"
										value="1" onclick="product_discount_amount(1)">
									<label class="custom-control-label" for="discount-total-amount">Total Amount</label>
								</div>
							</div>
							<div class="col-6 d-none">
								<div class="custom-control custom-radio">
									<input @if(old('amount_discount')==2) checked @endif type="radio"
										id="discount-product-price" name="amount_discount" class="custom-control-input"
										value="2" onclick="product_discount_amount(2)">
									<label class="custom-control-label" for="discount-product-price">Product
										Price</label>
								</div>
							</div>
						</div>

						<div class="row"
							style="padding-bottom: 10px;margin-top: 20px;display: @if(old('amount_discount') == 2) flex @else none @endif;"
							id="discount_selection">
							<div class="col-6">
								<div class="custom-control custom-radio">
									<input @if(old('product_discount')=='current' ) checked @endif type="radio"
										id="same-product" name="product_discount" class="custom-control-input"
										value="current" onchange="productdiscount('current')">
									<label class="custom-control-label" for="same-product">Same Product</label>
								</div>
							</div>
							<!-- <div class="col-4">
								<div class="custom-control custom-radio">
									<input @if(old('product_discount') == 'highest') checked @endif type="radio" id="product-highest-price" name="product_discount" class="custom-control-input" value="highest" onchange="productdiscount('highest')">
									<label class="custom-control-label" for="product-highest-price">Highest Price</label>
								</div>
							</div> -->
							<div class="col-6">
								<div class="custom-control custom-radio">
									<input @if(old('product_discount')=='specific' ) checked @endif type="radio"
										id="specific-product" name="product_discount" class="custom-control-input"
										value="specific" onchange="productdiscount('specific')">
									<label class="custom-control-label" for="specific-product">Specific Product</label>
								</div>
							</div>
						</div>

						<div style="display: @if(old('product_discount') == 'specific') block @else none @endif;"
							id="discount_productid">
							<select class="form-control select2" name="discount_productid">
								<option label="Choose Product"></option>
								@foreach($products as $product)
								<option @if(old('discount_productid')==$product->id) selected @endif
									value="{{$product->id}}">{{ $product->name }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="mb-3 reward-option" id="free-product-optn"
						style="display:@if($errors->any() && old('reward') == 'free-product-optn') block @else none @endif">
						<label class="d-block">Free Product *</label>
						<select class="form-control select2" name="free_product_id" style="min-height: 32px;"
							multiple="multiple">
							<option label="Choose one"></option>
							@foreach($free_products as $product)
							<option @if(old('free_product_id')==$product->id) selected @endif
								value="{{$product->id}}">{{ $product->name }}</option>
							@endforeach
						</select>
						@error('free_product_id')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
					<hr>
				</div>


				<div class="mb-3">
					<label class="form-label">Product</label>
					<select x-model="selectedProduct" name="product_id" class="form-control">
						<option value="">-- Select Product --</option>
						<template x-for="product in products" :key="product.id">
							<option :value="product.id" x-text="product.name"></option>
						</template>
					</select>
				</div>
			</div>


			<div class="mb-3">
				<label class="form-label">Usage Limit</label>
				<input type="number" name="usage_limit" class="form-control" placeholder="e.g. 100">
			</div>


			<div class="mb-3">
				<label class="form-label">Auto Apply</label>
				<select class="form-control" name="auto_apply">
					<option value="Yes">Yes</option>
					<option value="No">No</option>

				</select>
			</div>

			<!-- LOCATION -->
			<div x-data="addressForm()" x-init="loadData()">

				<!-- REGION -->
				<div class="mb-3">
					<label>Region</label>
					<select x-model="regionCode" @change="setRegionName($event)" class="form-control">
						<option value="">-- Select Region --</option>
						<template x-for="region in regions" :key="region.region_code">
							<option :value="region.region_code" :data-name="region.region_name"
								x-text="region.region_name">
							</option>
						</template>
					</select>
				</div>

				<!-- PROVINCE -->
				<div class="mb-3">
					<label>Province</label>
					<select x-model="provinceCode" @change="setProvinceName($event)" class="form-control">
						<option value="">-- Select Province --</option>
						<template x-for="p in provincesFiltered" :key="p.province_code">
							<option :value="p.province_code" :data-name="p.province_name" x-text="p.province_name">
							</option>
						</template>
					</select>
				</div>

				<!-- CITY -->
				<div class="mb-3">
					<label>City</label>
					<select x-model="cityCode" @change="setCityName($event)" class="form-control">
						<option value="">-- Select City --</option>
						<template x-for="c in citiesFiltered" :key="c.city_code">
							<option :value="c.city_code" :data-name="c.city_name" x-text="c.city_name">
							</option>
						</template>
					</select>
				</div>

				<!-- BARANGAY -->
				<div class="mb-3">
					<label>Barangay</label>
					<select x-model="barangayCode" @change="setBarangayName($event)" class="form-control">
						<option value="">-- Select Barangay --</option>
						<template x-for="b in barangaysFiltered" :key="b.brgy_code">
							<option :value="b.brgy_code" :data-name="b.brgy_name" x-text="b.brgy_name">
							</option>
						</template>
					</select>
				</div>

				<input type="hidden" name="region_code" x-model="regionCode">
				<input type="hidden" name="province_code" x-model="provinceCode">
				<input type="hidden" name="city_code" x-model="cityCode">
				<input type="hidden" name="barangay_code" x-model="barangayCode">

			</div>

			<div class="row">
				<div class="col-md-6 mb-3">
					<label class="form-label">Start Date *</label>
					<input type="date" name="start_date" class="form-control">
					<input type="time" name="start_time" class="form-control mt-2">
				</div>
				<div class="col-md-6 mb-3">
					<label class="form-label">End Date *</label>
					<input type="date" name="end_date" class="form-control">
					<input type="time" name="end_time" class="form-control mt-2">
				</div>
			</div>

			<div class="mb-4">
				<label class="form-label">Status *</label>
				<select class="form-control" name="status" required>
					<option value="" disabled selected>-- Select Status --</option>
					<option value="active">Active</option>
					<option value="inactive">Inactive</option>
				</select>
			</div>

			<button type="submit" class="btn btn-primary w-100">Create Coupon</button>
		</div>
	</form>


</div>

<div class="modal effect-scale" id="prompt-no-selected" tabindex="-1" role="dialog"
	aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.no_selected_title')}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p id="no_selected_title"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('pagejs')

{{-- <script src="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('customjs')

<script>
	function addressForm() {
    return {
        regions: [],
        provinces: [],
        cities: [],
        barangays: [],
        provincesFiltered: [],
        citiesFiltered: [],
        barangaysFiltered: [],

        // For filtering
        regionCode: '',
        provinceCode: '',
        cityCode: '',
        barangayCode: '',

        // For storing text
        regionName: '',
        provinceName: '',
        cityName: '',
        barangayName: '',

        async loadData() {
            const [regions, provinces, cities, barangays] = await Promise.all([
                fetch('/addresses/region.json').then(r => r.json()),
                fetch('/addresses/province.json').then(r => r.json()),
                fetch('/addresses/city.json').then(r => r.json()),
                fetch('/addresses/barangay.json').then(r => r.json()),
            ]);

            this.regions = regions;
            this.provinces = provinces;
            this.cities = cities;
            this.barangays = barangays;
        },

        setRegionName(e) {
            this.regionName = e.target.selectedOptions[0].dataset.name;
            this.filterProvinces();
        },
        setProvinceName(e) {
            this.provinceName = e.target.selectedOptions[0].dataset.name;
            this.filterCities();
        },
        setCityName(e) {
            this.cityName = e.target.selectedOptions[0].dataset.name;
            this.filterBarangays();
        },
        setBarangayName(e) {
            this.barangayName = e.target.selectedOptions[0].dataset.name;
        },

        filterProvinces() {
            this.provincesFiltered = this.provinces.filter(
                p => p.region_code === this.regionCode
            );
            this.provinceCode = '';
            this.citiesFiltered = [];
            this.cityCode = '';
            this.barangaysFiltered = [];
            this.barangayCode = '';
        },

        filterCities() {
            this.citiesFiltered = this.cities.filter(
                c => c.province_code === this.provinceCode
            );
            this.cityCode = '';
            this.barangaysFiltered = [];
            this.barangayCode = '';
        },

        filterBarangays() {
            this.barangaysFiltered = this.barangays.filter(
                b => b.city_code === this.cityCode
            );
            this.barangayCode = '';
        }
    }
}
</script>

<script>
	function categoryProductDropdown() {
    return {
        categories: [],
        products: [],
        selectedCategory: '',
        selectedProduct: '',

        init() {
            this.loadCategories();
        },

        async loadCategories() {
            const res = await fetch('/category_list'); 
            this.categories = await res.json();
        },

        async fetchProducts() {
            if (!this.selectedCategory) {
                this.products = [];
                this.selectedProduct = '';
                return;
            }

            const res = await fetch(`/products_list?category_id=${this.selectedCategory}`);
            this.products = await res.json();
            this.selectedProduct = '';
        }
    }
}
</script>
<script>
	document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('discount_type');
    const symbol = document.getElementById('discount_symbol');

    function updateSymbol() {
        if (typeSelect.value === 'percentage') {
            symbol.textContent = '%';
        } else {

            symbol.textContent = '₱';
        }
    }


    updateSymbol();


    typeSelect.addEventListener('change', updateSymbol);
});
</script>
@endsection