@extends('admin.layouts.app')

@section('pagecss')
	<link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
	{{-- <link href="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet"> --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<style>

		/* Make Select2 typing/search area more obvious */
.select2-container--default .select2-search--dropdown .select2-search__field {
    height: 40px;
    border: 2px solid #0168fa !important;
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 14px;
    font-weight: 600;
    outline: none;
    background-color: #f8fbff;
}

.select2-container--default .select2-search--dropdown .select2-search__field:focus {
    border-color: #0056d6 !important;
    box-shadow: 0 0 0 3px rgba(1, 104, 250, 0.18);
    background-color: #fff;
}

/* Make multiple select typing field visible */
.select2-container--default .select2-selection--multiple {
    min-height: 42px;
    padding: 4px 6px;
    border: 1px solid #c7ced8;
    border-radius: 6px;
}

.select2-container--default.select2-container--focus .select2-selection--multiple,
.select2-container--default.select2-container--open .select2-selection--multiple {
    border-color: #0168fa !important;
    box-shadow: 0 0 0 3px rgba(1, 104, 250, 0.15);
}

.select2-container--default .select2-selection--multiple .select2-search__field {
    min-width: 180px !important;
    font-size: 14px;
    font-weight: 600;
    color: #111;
}

.select2-container--default .select2-selection--multiple .select2-search__field::placeholder {
    color: #0168fa;
    font-weight: 600;
    opacity: 1;
}
		/* Highlight already selected options inside Select2 dropdown */
		.select2-container--default .select2-results__option[aria-selected="true"],
		.select2-container--default .select2-results__option--selected {
			background-color: #0168fa !important;
			color: #fff !important;
			font-weight: 600;
		}

		/* Keep hover readable */
		.select2-container--default .select2-results__option--highlighted[aria-selected] {
			background-color: #0056d6 !important;
			color: #fff !important;
		}
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
	</style>
@endsection

@section('content')
<div class="container pd-x-0">
	<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
		<div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb breadcrumb-style1 mg-b-10">
					<li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="{{ route('coupons.index') }}">Coupons</a></li>
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
	<form method="post" action="{{ route('coupons.store') }}" id="couponForm" autocomplete="off">
		@csrf
		<div class="row row-sm">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="d-block">Name *</label>
					<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">

				</div>
				<div class="form-group">
					<label class="d-block">Description *</label>
					<textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
					
				</div>
				<div class="form-group">
					<label class="d-block">Terms and Conditions *</label>
					<textarea name="terms_and_conditions" rows="3" class="form-control @error('terms_and_conditions') is-invalid @enderror">{{ old('terms_and_conditions') }}</textarea>
					
				</div>
				<div class="form-group">
					<label class="d-block">Distribution Type *</label>
					<div class="row" style="padding-bottom: 10px;">
						<div class="col-6">
							<div class="custom-control custom-radio">
								<input @if(old('coupon_activation') == 'auto') checked @endif checked type="radio" id="coupon-activate-auto" name="coupon_activation" class="custom-control-input" value="auto"  onclick="ShowHideDiv()">
								<label class="custom-control-label" for="coupon-activate-auto">Automatically Enabled</label>
							</div>
							<small style="font-style: italic;">Coupon is automatically enabled after customer completes an activity.</small>
						</div>
						<div class="col-6">
							<div class="custom-control custom-radio">
								<input @if(old('coupon_activation') == 'manual') checked @endif type="radio" id="coupon-activate-manual" name="coupon_activation" class="custom-control-input" value="manual" onclick="ShowHideDiv()">
								<label class="custom-control-label" for="coupon-activate-manual">Manual</label>
							</div>
							<small style="font-style: italic;">Customer inputs a code to redeem coupon reward.</small>
						</div>
					</div>
					<div class="mb-3" id="coupon-code" style="display: @if(old('coupon_activation') == 'manual') block @else none @endif;">
						<label class="d-block">Coupon Code *</label>
						<input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}">
						
					</div>
				</div>

				<div class="form-group">
					<label class="d-block">Customer Scope</label>
					<div class="row" style="padding-bottom: 10px;">
						<div class="col-6">
							<div class="custom-control custom-radio">
								<input @if(old('coupon_scope') == 'all') checked @endif checked type="radio" id="coupon-scope-all" name="coupon_scope" class="custom-control-input" value="all" checked onclick="ShowHideDiv()">
								<label class="custom-control-label" for="coupon-scope-all">All</label>
							</div>
							<small style="font-style: italic;">Coupon will be applicable to all customers who completed an activity.</small>
						</div>
						<div class="col-6">
							<div class="custom-control custom-radio">
								<input @if(old('coupon_scope') == 'specific') checked @endif type="radio" id="coupon-scope-specific" name="coupon_scope" class="custom-control-input" value="specific" onclick="ShowHideDiv()">
								<label class="custom-control-label" for="coupon-scope-specific">Specific</label>
							</div>
							<small style="font-style: italic;">Only the specific customer will be able to use and claim the coupon reward.</small>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="mb-3" id="customer-optn" style="display: @if(old('coupon_scope') == 'specific') block @else none @endif;">
						<label class="d-block">Customer Name *</label>
						<select class="form-control select2" name="customer[]" multiple="multiple">
							<option label="Choose one"></option>
							@foreach($customers as $customer)
								<option @if(is_array(old('customer')) && in_array($customer->id, old('customer'))) selected @endif value="{{$customer->id}}">{{ $customer->name }}</option>
							@endforeach
						</select>
						<small id="specific_customer_count" class="text-primary font-weight-bold d-block mt-2"></small>
					</div>
				</div>


				<div class="form-group">
					<label class="d-block">Reward *</label>
					<select class="custom-select @error('reward') is-invalid @enderror" id="reward-optn" name="reward">
						<option value="" class="text-secondary">Select Reward</option>
						<option @if(old('reward') == 'free-shipping-optn') selected @endif value="free-shipping-optn">Free Shipping (FS</option>
						<option @if(old('reward') == 'discount-amount-optn') selected @endif value="discount-amount-optn">Discount Amount (DA)</option>
						<option @if(old('reward') == 'discount-percentage-optn') selected @endif value="discount-percentage-optn">Discount Percentage (DP)</option>
						<option @if(old('reward') == 'free-product-optn') selected @endif value="free-product-optn">Free Product/Gift (FP)</option>
					</select>
				
				</div>

				<div class="form-group">
					<div class="mb-3 reward-option" id="free-shipping-optn" style="display:@if($errors->any() && old('reward') == 'free-shipping-optn') block @else none @endif">
						<label class="d-block">Location *</label>
						<select class="form-control select2 select-location" name="location[]" multiple="multiple" style="min-height: 32px;">
							<option value="all">All Area</option>
							@foreach($locations as $location)
								<option @if(is_array(old('location')) && in_array($location->city, old('location'))) selected @endif value="{{$location->city}}">{{ $location->city }}</option>
							@endforeach
						</select>
						
						<br><br>
						<label class="d-block">Discount Type *</label>
						<div class="row" style="padding-bottom: 10px;">
							<div class="col-6">
								<div class="custom-control custom-radio">
									<input @if(old('discount_type') == 'partial') checked @endif checked type="radio" id="coupon-discount-type-partial" name="discount_type" class="custom-control-input" value="partial" onchange="sf_discount_type()">
									<label class="custom-control-label" for="coupon-discount-type-partial">Partial</label>
								</div>
							</div>
							<div class="col-6">
								<div class="custom-control custom-radio">
									<input @if(old('discount_type') == 'full') checked @endif type="radio" id="coupon-discount-type-full" name="discount_type" class="custom-control-input" value="full" onchange="sf_discount_type()">
									<label class="custom-control-label" for="coupon-discount-type-full">Full</label>
								</div>
							</div>
						</div>

						<label id="discount_amount_label" style="display: @if(old('discount_type') == 'full') none @else block @endif;">Shipping Fee Discount Amount *</label>
						<input style="display: @if(old('discount_type') == 'full') none @else block @endif;" type="number" name="shipping_fee_discount_amount" class="form-control @error('shipping_fee_discount_amount') is-invalid @enderror" id="discount_amount_input" value="{{ old('shipping_fee_discount_amount') }}">
						
					</div>

					<div class="mb-3 reward-option" id="discount-amount-optn" style="display:@if($errors->any() && old('reward') == 'discount-amount-optn') block @else none @endif">
						<label class="d-block">Discount Amount *</label>
						<input name="discount_amount" type="number" class="form-control @error('discount_amount') is-invalid @enderror" value="{{ old('discount_amount') }}" placeholder="Php">
						
					</div>

					<div class="mb-3 reward-option" id="discount-percentage-optn" style="display:@if($errors->any() && old('reward') == 'discount-percentage-optn') block @else none @endif">
						<label class="d-block">Discount Percentage % *</label>
						<input name="discount_percentage" type="number" class="form-control @error('discount_percentage') is-invalid @enderror" placeholder="%" value="{{ old('discount_percentage') }}">
				
					</div>

					<div id="div_product_amount" style="display: @if(old('reward') == 'discount-amount-optn' || old('reward') == 'discount-percentage-optn') block @else none @endif;">
                		<div class="row" style="padding-bottom: 10px;margin-top: 20px;">
							<div class="col-6">
								<div class="custom-control custom-radio">
									<input @if(old('amount_discount') == 1) checked @endif checked type="radio" id="discount-total-amount" name="amount_discount" class="custom-control-input" value="1" onclick="product_discount_amount(1)">
									<label class="custom-control-label" for="discount-total-amount">Total Amount</label>
								</div>
							</div>
							<div class="col-6 d-none">
								<div class="custom-control custom-radio">
									<input @if(old('amount_discount') == 2) checked @endif type="radio" id="discount-product-price" name="amount_discount" class="custom-control-input" value="2" onclick="product_discount_amount(2)">
									<label class="custom-control-label" for="discount-product-price">Product Price</label>
								</div>
							</div>
						</div>

						<div class="row" style="padding-bottom: 10px;margin-top: 20px;display: @if(old('amount_discount') == 2) flex @else none @endif;" id="discount_selection">
							<div class="col-6">
								<div class="custom-control custom-radio">
									<input @if(old('product_discount') == 'current') checked @endif type="radio" id="same-product" name="product_discount" class="custom-control-input" value="current" onchange="productdiscount('current')">
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
									<input @if(old('product_discount') == 'specific') checked @endif type="radio" id="specific-product" name="product_discount" class="custom-control-input" value="specific" onchange="productdiscount('specific')">
									<label class="custom-control-label" for="specific-product">Specific Product</label>
								</div>
							</div>
						</div>

						<div style="display: @if(old('product_discount') == 'specific') block @else none @endif;" id="discount_productid">
							<select class="form-control select2" name="discount_productid">
								<option label="Choose Product"></option>
								@foreach($products as $product)
									<option @if(old('discount_productid') == $product->id) selected @endif value="{{$product->id}}">{{ $product->name }}</option>
								@endforeach
							</select>
						</div>
                	</div>

					<div class="mb-3 reward-option" id="free-product-optn" style="display:@if($errors->any() && old('reward') == 'free-product-optn') block @else none @endif">


						@error('location')
							<span class="text-danger d-block mt-1">
								<strong>{{ $message }}</strong>
							</span>
						@enderror

						<br><br>

						<label class="d-block">Free Product *</label>
						<select class="form-control select2" name="free_product_id[]" style="min-height: 32px;" multiple="multiple">
							<option label="Choose one"></option>
							@foreach($free_products as $product)
								<option 
									@if(is_array(old('free_product_id')) && in_array($product->id, old('free_product_id'))) selected @endif 
									value="{{ $product->id }}"
								>
									{{ $product->name }}
								</option>
							@endforeach
						</select>

						@error('free_product_id')
							<span class="invalid-feedback d-block" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror

						@error('free_product_id.*')
							<span class="invalid-feedback d-block" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror

						<label class="d-block">Location *</label>
						<select class="form-control select2 select-location" name="location[]" multiple="multiple" style="min-height: 32px;">
							<option value="all">All Area</option>
							@foreach($locations as $location)
								<option 
									@if(is_array(old('location')) && in_array($location->city, old('location'))) selected @endif 
									value="{{ $location->city }}"
								>
									{{ $location->city }}
								</option>
							@endforeach
						</select>
					</div>
					<hr>
				</div>



				<br>
				<h4 class="mg-b-0 tx-spacing--1">Coupon Settings</h4>
				<hr>

				<div class="form-group">	
					<label class="d-block">Time 
						&nbsp;&nbsp;<span style="font-style: italic;">Set the date/time validity of the coupon.</span>
					</label>
					<input type="checkbox" class="custom-control-input" id="coupon-time" name="coupon_setting[]" value="time" checked>
				</div>

				<div class="form-row border rounded p-3 pt-4 mb-4" id="coupon-time-option" style="display:flex;">
					<div class="col-md-3">
						<div class="custom-control custom-radio">
							<input type="radio" id="coupon-date-time" name="coupon_time[]" class="custom-control-input" onclick="ShowHideDiv()" value="datetime" checked>
							<label class="custom-control-label" for="coupon-date-time">Date and Time</label>
						</div>
					</div>
					<div class="col-md-3 d-none">
						<div class="custom-control custom-radio">
							<input type="radio" id="coupon-custom" name="coupon_time[]" class="custom-control-input" onclick="ShowHideDiv()" value="custom" @if(is_array(old('coupon_time')) && in_array('custom', old('coupon_time'))) checked @endif>
							<label class="custom-control-label" for="coupon-custom">Custom</label>
						</div>
					</div>

					<div class="col-12" id="coupon-date-time-form" style="display:block;">
						<div class="row mt-3">
							<div class="col-6">
								<label class="d-block">Start Date *</label>
								<input name="startdate" type="date" id="dateFrom" class="form-control" placeholder="From" autocomplete="off" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
								<small id="spanDatefrom" style="display: none;" class="text-danger"></small>
							</div>
							<div class="col-6">
								<label class="d-block">End Date</label>
								<input name="enddate" type="date" id="dateTo" class="form-control" placeholder="To" autocomplete="off" value="{{ old('enddate') }}">
							</div>
						</div>

						<div class="row mt-3">
							<div class="col-6">
								<label class="d-block">Start Time</label>
								<input name="starttime" type="time" class="form-control" autocomplete="off" value="{{ old('starttime') }}">
							</div>
							<div class="col-6">
								<label class="d-block">End Time</label>
								<input name="endtime" type="time" class="form-control" autocomplete="off" value="{{ old('endtime') }}">
							</div>
						</div>
					</div>

					<div class="col-12" id="coupon-custom-form" style="display:@if(is_array(old('coupon_time')) && in_array('custom', old('coupon_time'))) block @else none @endif;">
						<div class="row mt-3">
							<div class="col-md-6">
								<label class="d-block">Event Name *</label>
								<input name="eventname" id="eventname" type="text" class="form-control" autocomplete="off" value="{{ old('eventname') }}">
								<small class="text-danger" style="display: none;" id="spanEventName"></small>
							</div>
							<div class="col-md-6">
								<label class="d-block">Date *</label>
								<input name="eventdate" id="eventdate" type="text" class="form-control singlecalendar" placeholder="Choose date" autocomplete="off" value="{{ old('eventdate') }}">
								<small class="text-danger" style="display: none;" id="spanEventDate"></small>
							</div>
							<div class="col-12 mt-3">
								<div class="custom-control custom-switch">
									<input name="repeat_annually" type="checkbox" {{ (old("repeat_annually") ? "checked":"") }} class="custom-control-input" id="customSwitch1" >
									<label class="custom-control-label" for="customSwitch1">Repeat Annually</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="coupon-purchase" onclick="myFunction()" name="coupon_setting[]" value="purchase" @if(is_array(old('coupon_setting')) && in_array('purchase', old('coupon_setting'))) checked @endif>
						<label class="custom-control-label" for="coupon-purchase">Purchase 
							&nbsp;&nbsp;<span style="font-style: italic;">Coupon is received after the purchase conditions have been met.</span>
						</label>
					</div>
				</div>

				<div class="form-row border rounded p-3 mb-4" id="coupon-purchase-option" style="display:@if(is_array(old('coupon_setting')) && in_array('purchase', old('coupon_setting'))) flex @else none @endif;">
					<div class="col-md-3">
						<div class="custom-control custom-checkbox">
							<input {{ (old("purchase_product") ? "checked":"") }} type="checkbox" id="coupon-product" name="purchase_product" class="custom-control-input" onclick="purchase_products()">
							<label class="custom-control-label" for="coupon-product">Product</label>
						</div>
					</div>

					<div class="col-md-3">
						<div class="custom-control custom-checkbox">
							<input {{ (old("purchase_total_amount") ? "checked":"") }} type="checkbox" id="coupon-amount" name="purchase_total_amount" class="custom-control-input" onclick="total_amount_purchase()">
							<label class="custom-control-label" for="coupon-amount">Total Amount</label>
						</div>
					</div>

					<div class="col-md-3">
						<div class="custom-control custom-checkbox">
							<input {{ (old("purchase_total_qty") ? "checked":"") }} type="checkbox" id="coupon-quantity" name="purchase_total_qty" class="custom-control-input" onclick="total_amount_purchase()">
							<label class="custom-control-label" for="coupon-quantity">Total Quantity</label>
						</div>
					</div>

					<div class="col-12 mt-3" id="coupon-product-form" style="display:{{ (old('purchase_product') ? 'block':'none') }};">
						<small class="text-danger" style="display: none;" id="spanProductOpt"></small>
						<div class="form-group">
							<label class="d-block">Product Name</label>
							<select class="form-control select2" multiple="multiple" name="product_name[]" id="product_opt">
								<option label="Choose one"></option>
								@foreach($products as $product)
									<option @if(is_array(old('product_name')) && in_array($product->id, old('product_name'))) selected @endif value="{{$product->id}}">{{ $product->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label class="d-block">Category</label>
							<select class="form-control select2" multiple="multiple" name="product_category[]" id="category_opt">
								<option label="Choose one"></option>
								@foreach($categories as $category)
									<option @if(is_array(old('product_category')) && in_array($category->id, old('product_category'))) selected @endif value="{{$category->id}}">{{ $category->name }}</option>
								@endforeach
							</select>
						</div>

						
					</div>

					<div class="col-12 mt-3" id="coupon-amount-form" style="display:{{ (old('purchase_total_amount') || old('purchase_qty') ? 'block':'none') }};">
						<div class="row">
							<div class="col-12" id="total-amount-div" style="display: {{ (old('purchase_total_amount') ? 'block':'none') }};">
								<label class="d-block">Total Minimum Amount *</label>
							</div>
							<div class="col-md-6" id="total-amount-input" style="display: {{ (old('purchase_total_amount') ? 'block':'none') }};">
								<input name="purchase_amount" id="purchase_amount" type="number" min="1" class="form-control" value="{{ old('purchase_amount') }}">
								<small id="spanPurchaseAmount" style="display: none;" class="text-danger"></small>
							</div>

							<!-- Quantity -->
							<div class="col-12" id="total-quantity-div" style="padding-top: 10px;display: {{ (old('purchase_total_qty') ? 'block':'none') }};">
								<label class="d-block">Total Quantity *</label>
							</div>
							<div class="col-md-6" id="total-quantity-input" style="display: {{ (old('purchase_total_qty') ? 'block':'none') }};">
								<input name="purchase_qty" id="purchase_qty" type="number" min="1" class="form-control" value="{{ old('purchase_qty') }}">
								<small id="spanPurchaseQty" style="display: none;" class="text-danger"></small>
							</div>
							<div class="col-md-6" id="total-quantity-select" style="display: {{ (old('purchase_total_qty') ? 'block':'none') }};">
								<select class="custom-select" name="qty_opt" id="qty_opt">
									<option value="min">Minimum</option>
								</select>
								<small id="spanQtyOpt" style="display: none;" class="text-danger"></small>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">	
					<label class="d-block">Rules 
						&nbsp;&nbsp;<span style="font-style: italic;">Set specific rules on the coupon.</span>
					</label>
				</div>

				<div class="form-row border rounded p-3">
					<div class="col-12">
						<div class="custom-control custom-checkbox">
							<input {{ (old("has_usage_limit") || old("usage_limit")) ? "checked" : "" }} type="checkbox" class="custom-control-input" id="coupon-usage-limit" name="has_usage_limit" onclick="myFunction()">
							<label class="custom-control-label" for="coupon-usage-limit">
								Usage Limit
								&nbsp;&nbsp;<span style="font-style: italic;">Maximum total paid uses allowed for this coupon across all customers.</span>
							</label>
						</div>

						<div class="mt-3" style="display:{{ (old("has_usage_limit") || old("usage_limit")) ? "block":"none" }}" id="coupon-usage-limit-form">
							<div class="input-group border rounded">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="usage_limit">
										<span class="fa fa-minus"></span>
									</button>
								</span>
								<input type="text" name="usage_limit" id="usage_limit" class="form-control input-number border border-top-0 border-bottom-0" value="{{ old('usage_limit', 0) }}" min="0" max="100000">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-number" data-type="plus" data-field="usage_limit">
										<span class="fa fa-plus"></span>
									</button>
								</span>
							</div>
							<small class="text-muted d-block mt-2">
								Set 0 for unlimited total usage. Example: 4 means this coupon can only be used 4 paid times total.
								<br>
								If Customer Scope is Specific, this will automatically match the number of selected customers and become readonly.
							</small>
							<hr>
						</div>
					</div>

					<div class="col-12 mt-3">
						<div class="custom-control custom-checkbox">
							<input {{ (old("has_customer_limit") || old("customer_limit")) ? "checked" : "" }} type="checkbox" class="custom-control-input" id="coupon-customer-limit" name="has_customer_limit" onclick="myFunction()">
							<label class="custom-control-label" for="coupon-customer-limit">
								Customer Limit
								&nbsp;&nbsp;<span style="font-style: italic;">Maximum paid uses allowed per customer.</span>
							</label>
						</div>

						<div class="mt-3" style="display:{{ (old("has_customer_limit") || old("customer_limit")) ? "block":"none" }}" id="coupon-customer-limit-form">
							<div class="input-group border rounded">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="customer_limit">
										<span class="fa fa-minus"></span>
									</button>
								</span>
								<input type="text" name="customer_limit" class="form-control input-number border border-top-0 border-bottom-0" value="{{ old('customer_limit', 0) }}" min="0" max="100000">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-number" data-type="plus" data-field="customer_limit">
										<span class="fa fa-plus"></span>
									</button>
								</span>
							</div>
							<small class="text-muted d-block mt-2">
								Set 0 for unlimited per-customer usage. Example: 1 means each customer can only use this coupon once.
							</small>
							<hr>
						</div>
					</div>

					<div class="col-12 mt-3">
						<div class="custom-control custom-checkbox">
							<input {{ (old("combination") ? "checked":"") }} type="checkbox" class="custom-control-input" id="coupon-combination" name="combination">
							<label class="custom-control-label" for="coupon-combination">Coupon Combination &nbsp;&nbsp;<span style="font-style: italic;">Can be used together with other coupons.</span></label>
						</div>
					</div>

				</div>

				<hr>
			</div>
			<div class="col-lg-12">
				<div class="form-group">
					<label class="d-block">Status</label>
					<div class="custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="enableSwitch1" name="status" {{ (old("status") ? "checked":"") }}>
						<label class="custom-control-label" for="enableSwitch1" id="label_status">@if(old('status')) Active @else Inactive @endif</label>
					</div>
				</div>
			</div>

			<div class="col-lg-12 mg-t-30">
				<button class="btn btn-primary btn-sm btn-uppercase" type="button" id="btnSubmit">Save</button>
				<a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
			</div>
		</div>
	</form>
	<!-- row -->
</div>

<div class="modal effect-scale" id="prompt-no-selected" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
	<script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
	<script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
	<script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
	{{-- <script src="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.js') }}"></script> --}}
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection


@section('customjs')
<script>
	$('#coupon-scope-specific').click(function(){
		$('#customer-optn').show();

		if (typeof updateSpecificCustomerCount === 'function') {
			updateSpecificCustomerCount();
		}
	});

	$('#coupon-scope-all').click(function(){
		$('#customer-optn').hide();
		$('select[name="customer[]"]').val(null).trigger('change');

		if (typeof updateSpecificCustomerCount === 'function') {
			updateSpecificCustomerCount();
		}
	});

	function productdiscount(x){
		if(x == 'specific'){
			$('#discount_productid').css('display','block');
		} else {
			$('#discount_productid').css('display','none');
		}
	}
	function product_discount_amount(x){
		if(x == 2){
			$('#discount_selection').css('display','flex');
		} else {
			$('#discount_selection').css('display','none');
		}
	}

	$('#reward-optn').change(function(){
		$('.reward-option').hide();
		$('#' + $(this).val()).show();

		if($(this).val() == 'discount-amount-optn' || $(this).val() == 'discount-percentage-optn'){
			$('#div_product_amount').show();
		} else {
			$('#div_product_amount').hide();
		}
	});

	function sf_discount_type(){
		var option = $('input[name="discount_type"]:checked').val();

		if(option == 'full'){
			$('#discount_amount_label').css('display','none');
			$('#discount_amount_input').css('display','none');
		} else {
			$('#discount_amount_label').css('display','block');
			$('#discount_amount_input').css('display','block');
		}
	}

	function total_amount_purchase(){
		if($('#coupon-amount').is(':checked') || $('#coupon-quantity').is(':checked')){
			$('#coupon-amount-form').css('display','block');
		} else {
			$('#coupon-amount-form').css('display','none');
		}

		if($('#coupon-amount').is(':checked')){
			$('#total-amount-div').css('display','block');
			$('#total-amount-input').css('display','block');
			$('#total-amount-select').css('display','block');
		} else {
			$('#total-amount-div').css('display','none');
			$('#total-amount-input').css('display','none');
			$('#total-amount-select').css('display','none');
		}

		if($('#coupon-quantity').is(':checked')){
			$('#total-quantity-div').css('display','block');
			$('#total-quantity-input').css('display','block');
			$('#total-quantity-select').css('display','block');
		} else {
			$('#total-quantity-div').css('display','none');
			$('#total-quantity-input').css('display','none');
			$('#total-quantity-select').css('display','none');
		}
	}

	function purchase_products(){
		if($('#coupon-product').is(':checked')){
			$('#coupon-product-form').css('display','block');
		} else {
			$('#coupon-product-form').css('display','none');
		}
		
	}

	$('#product_opt').change(function(){
		var value = $(this).val();

		if(value != ''){
			$('#category_opt').attr("disabled", true);
			$('#brand_opt').attr("disabled", true);
		} else {
			$('#category_opt').removeAttr("disabled");
			$('#brand_opt').removeAttr("disabled");
		}
	});

	$('#category_opt').change(function(){
		var selected = '';
		
		$('#category_opt :selected').each(function(){
		    selected += $(this).val()+'|';
		});

		

// 		var value = parseInt($(this).val());
        var value = $(this).val();
		if(value != ''){
			$('#product_opt').attr("disabled", true);
		} else {
			$('#product_opt').removeAttr("disabled");
		}
	});

	$('#brand_opt').change(function(){
		var value = $(this).val();

		if(value != ''){
			$('#product_opt').attr("disabled", true);
		} else {
			$('#product_opt').removeAttr("disabled");
		}
	});




	$('#btnSubmit').click(function(){

		if(!$("input[name='coupon_setting[]']:checked").val()) {  
				$('#no_selected_title').html('Please select at least one (1) coupon setting.');      
                $('#prompt-no-selected').modal('show');
                return false;
            } else {
            	var rs;
            	$('input[name="coupon_setting[]"]:checked').each(function(){

				   	if(this.value == 'time') {
				   		var selectedOption = $('input[name="coupon_time[]"]:checked').val();

				   		if(selectedOption == 'datetime'){
				   			var startdate = $('#dateFrom').val();
					   		if(startdate.length === 0){
					   			$('#dateFrom').addClass('is-invalid');
					   			$('#spanDatefrom').css('display','block');
					   			$('#spanDatefrom').html('Start Date field is required.');
					   			rs = false;
            					return false;
					   		}
				   		}

				   		if(selectedOption == 'custom'){
				   			var eventname = $('#eventname').val();
				   			var eventdate = $('#eventdate').val();

				   			if(eventname.length === 0){
				   				$('#eventname').addClass('is-invalid');
					   			$('#spanEventName').css('display','block');
					   			$('#spanEventName').html('Event name field is required.');
					   			rs = false;
            					return false;
				   			}

				   			if(eventdate.length === 0){
				   				$('#eventdate').addClass('is-invalid');
					   			$('#spanEventDate').css('display','block');
					   			$('#spanEventDate').html('Event date field is required.');
					   			rs = false;
            					return false;
				   			}
				   		}

				   		rs = true;	
				   	}

				   	if(this.value == 'purchase') {
				   		if($('#coupon-product').is(':checked') || $('#coupon-amount').is(':checked') || $('#coupon-quantity').is(':checked')) {

				   			$('#coupon-purchase-option').removeClass('is-invalid');
				   			var selectedOption = $('input[name="coupon_purchase[]"]:checked').val();

				   			if($('#coupon-product').is(':checked')){
				   				var product = $('#product_opt').val();
				   				var category = $('#category_opt').val();
				   				var brand = $('#brand_opt').val();

				   				if(product.length === 0 && category.length === 0 && brand.length === 0){
				   					$('.select2-container').css('border','1px solid red');
				   					$('.select2-container').css('border-radius','0.25rem');
				   					$('#spanProductOpt').css('display','block');
				   					$('#spanProductOpt').html('Please select at least one(1) option.');
				   					rs = false;
	            					return false;
				   				}

				   				rs = true;
				   			}

				   			if($('#coupon-amount').is(':checked')){
				   				var amount = $('#purchase_amount').val();
						   		var amounttype = $('#amount_opt').val();

						   		if(amount.length === 0){
						   			$('#purchase_amount').addClass('is-invalid');
						   			$('#spanPurchaseAmount').css('display','block');
						   			$('#spanPurchaseAmount').html('Amount field is required.');
						   			rs = false;
	            					return false;
						   		}

						   		// if(amounttype.length === 0){
						   		// 	$('#amount_opt').addClass('is-invalid');
						   		// 	$('#spanAmountOpt').css('display','block');
						   		// 	$('#spanAmountOpt').html('Please select one(1) option.');
						   		// 	rs = false;
	            				// 	return false;
						   		// }

						   		rs = true;
				   			}

				   			if($('#coupon-quantity').is(':checked')){
				   				var qty = $('#purchase_qty').val();
						   		var qtytype = $('#qty_opt').val();

						   		if(qty.length === 0){
						   			$('#purchase_qty').addClass('is-invalid');
						   			$('#spanPurchaseQty').css('display','block');
						   			$('#spanPurchaseQty').html('Quantity field is required.');
						   			rs = false;
	            					return false;
						   		}

						   		if(qtytype.length === 0){
						   			$('#qty_opt').addClass('is-invalid');
						   			$('#spanQtyOpt').css('display','block');
						   			$('#spanQtyOpt').html('Please select one(1) option.');
						   			rs = false;
						   			return false;
						   		}
				   			}

				   			rs = true;
				   		} else {
				   			$('#coupon-purchase-option').addClass('is-invalid');
				   			$('#no_selected_title').html('Please select at least one (1) purchase option.');      
                			$('#prompt-no-selected').modal('show');
                			rs = false;
	            			return false;
				   		}
				   	}
				});
				
				if(rs == true){
					if ($('#coupon-scope-specific').is(':checked')) {
						const selectedCustomers = $('select[name="customer[]"]').val() || [];

						$('input[name="usage_limit"]')
							.val(selectedCustomers.length)
							.prop('disabled', false);

						$('#coupon-usage-limit').prop('disabled', false);
					}

					$('#couponForm').submit();
				}
            }
	});
	

	$("#enableSwitch1").change(function() {
        if(this.checked) {
            $('#label_status').html('Active');
        }
        else{
            $('#label_status').html('Inactive');
        }
    });

	// $('.datetime').clockpicker();

	$('.singlecalendar').datepicker({
		dateFormat: 'yy-mm-dd'
	});

	var dateToday = new Date(); 
	$('#dateFrom').datepicker({
		dateFormat: 'yy-mm-dd',
		minDate: dateToday,
	});
	$('#dateTo').datepicker({
		dateFormat: 'yy-mm-dd',
		minDate: dateToday,
	});




	function myFunction() {
		var checkCouponTime = document.getElementById("coupon-time");
		var fieldCouponOption = document.getElementById("coupon-time-option");
		if (checkCouponTime.checked == true){
			fieldCouponOption.style.display = "flex";
		} else {
			fieldCouponOption.style.display = "none";
		};

		var couponPurchase = document.getElementById("coupon-purchase");
		var fieldCouponOption = document.getElementById("coupon-purchase-option");
		if (couponPurchase.checked == true){
			fieldCouponOption.style.display = "flex";
		} else {
			fieldCouponOption.style.display = "none";
		};

		var couponUsageLimit = document.getElementById("coupon-usage-limit");
		var fieldUsageLimitOption = document.getElementById("coupon-usage-limit-form");
		var scopeSpecific = document.getElementById("coupon-scope-specific");

		if (scopeSpecific && scopeSpecific.checked) {
			if (couponUsageLimit) {
				couponUsageLimit.checked = true;
				couponUsageLimit.disabled = true;
			}

			if (fieldUsageLimitOption) {
				fieldUsageLimitOption.style.display = "block";
			}

			const selectedCustomers = $('select[name="customer[]"]').val() || [];

			$('input[name="usage_limit"]')
				.val(selectedCustomers.length)
				.prop('readonly', true)
				.prop('disabled', true);

			$('.btn-number[data-field="usage_limit"]').prop('disabled', true);
		} else if (couponUsageLimit && couponUsageLimit.checked == true){
			fieldUsageLimitOption.style.display = "block";
		} else if (fieldUsageLimitOption) {
			fieldUsageLimitOption.style.display = "none";
			$('input[name="usage_limit"]').val(0).prop('readonly', false).prop('disabled', false);
			$('.btn-number[data-field="usage_limit"]').prop('disabled', false);
		};

		var couponCustomerLimit = document.getElementById("coupon-customer-limit");
		var fieldCustomerLimitOption = document.getElementById("coupon-customer-limit-form");
		if (couponCustomerLimit && couponCustomerLimit.checked == true){
			fieldCustomerLimitOption.style.display = "block";
		} else if (fieldCustomerLimitOption) {
			fieldCustomerLimitOption.style.display = "none";
			$('input[name="customer_limit"]').val(0).prop('readonly', false).prop('disabled', false);
			$('.btn-number[data-field="customer_limit"]').prop('disabled', false);
		};
	};

	function ShowHideDiv() {
		var couponDateTime = document.getElementById("coupon-date-time");
		var couponDateTimeForm = document.getElementById("coupon-date-time-form");
		couponDateTimeForm.style.display = couponDateTime.checked ? "block" : "none";

		var couponCustom = document.getElementById("coupon-custom");
		var couponCustomForm = document.getElementById("coupon-custom-form");
		couponCustomForm.style.display = couponCustom.checked ? "block" : "none";

		var activateManual= document.getElementById("coupon-activate-manual");
		var couponCodeManual = document.getElementById("coupon-code");
		couponCodeManual.style.display = activateManual.checked ? "none" : "block";

		var autoManual= document.getElementById("coupon-activate-auto");
		var couponCodeAuto = document.getElementById("coupon-code");
		couponCodeAuto.style.display = autoManual.checked ? "none" : "block";
	};


// Points Earned start --------------------->
    $('.btn-number').click(function(e){
      	e.preventDefault();

      	fieldName = $(this).attr('data-field');
      	type      = $(this).attr('data-type');
      	var input = $("input[name='"+fieldName+"']");
      	var currentVal = parseInt(input.val());
      	if (!isNaN(currentVal)) {
        	if(type == 'minus') {

          		if(currentVal > input.attr('min')) {
            		input.val(currentVal - 1).change();
          		} 
          		if(parseInt(input.val()) == input.attr('min')) {
            		$(this).attr('disabled', true);
          		}
        	} else if(type == 'plus') {

          		if(currentVal < input.attr('max')) {
            		input.val(currentVal + 1).change();
          		}
          		if(parseInt(input.val()) == input.attr('max')) {
            		$(this).attr('disabled', true);
          		}

        	}
      	} else {
        	input.val(0);
      	}
    });

    $('.input-number').focusin(function(){
    	$(this).data('oldValue', $(this).val());
    });

    $('.input-number').change(function() {

      	minValue =  parseInt($(this).attr('min'));
      	maxValue =  parseInt($(this).attr('max'));
      	valueCurrent = parseInt($(this).val());

      	name = $(this).attr('name');
      	if(valueCurrent >= minValue) {
        	$(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
      	} else {
        	alert('Sorry, the minimum value was reached');
        	$(this).val($(this).data('oldValue'));
      	}
      	if(valueCurrent <= maxValue) {
        	$(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    	} else {
        	alert('Sorry, the maximum value was reached');
        	$(this).val($(this).data('oldValue'));
    	}
    });

    $(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
       	// Allow: Ctrl+A
      	(e.keyCode == 65 && e.ctrlKey === true) || 
       	// Allow: home, end, left, right
      	(e.keyCode >= 35 && e.keyCode <= 39)) {
         	// let it happen, don't do anything
         	return;
    	}
    	// Ensure that it is a number and stop the keypress
    	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
      		e.preventDefault();
    	}
    });
// Points Earned end --------------------->

$(function() {
    $('.selectpicker').selectpicker();

    $('.select2').each(function () {
        const isMultiple = $(this).prop('multiple');

        $(this).select2({
            placeholder: 'Type to search...',
            width: '100%',
            closeOnSelect: !isMultiple,
            minimumResultsForSearch: 0
        });
    });

    // Add clearer placeholder when dropdown opens
    $(document).on('select2:open', function () {
        setTimeout(function () {
            let searchField = document.querySelector('.select2-container--open .select2-search__field');

            if (searchField) {
                searchField.setAttribute('placeholder', 'Type here to search...');
                searchField.focus();
            }
        }, 0);
    });

    let isHandlingSelect = false;

    $('.select-location').on('select2:select', function (e) {
        if (isHandlingSelect) return;

        isHandlingSelect = true;

        const $select = $(this);
        const selectedId = e.params.data.id;
        let selected = $select.val() || [];

        if (selectedId === 'all') {
            $select.val(['all']).trigger('change.select2');
        } else {
            selected = selected.filter(value => value !== 'all');
            $select.val(selected).trigger('change.select2');
        }

        isHandlingSelect = false;
    });
});

function updateSpecificCustomerCount() {
    const isSpecific = $('#coupon-scope-specific').is(':checked');
    const selectedCustomers = $('select[name="customer[]"]').val() || [];
    const selectedCount = selectedCustomers.length;

    const $usageLimitCheckbox = $('#coupon-usage-limit');
    const $usageLimitForm = $('#coupon-usage-limit-form');
    const $usageLimitInput = $('input[name="usage_limit"]');
    const $usageLimitButtons = $('.btn-number[data-field="usage_limit"]');

    const $customerLimitCheckbox = $('#coupon-customer-limit');
    const $customerLimitInput = $('input[name="customer_limit"]');
    const $customerLimitButtons = $('.btn-number[data-field="customer_limit"]');

    if (isSpecific) {
        $('#customer-optn').show();

        $('#specific_customer_count').html(
            'Selected specific customers: ' + selectedCount
        );

        // Specific users control the total available recipients.
        // Auto enable Usage Limit, set it to selected count, and disable editing.
        $usageLimitCheckbox
            .prop('checked', true)
            .prop('disabled', true);

        $usageLimitForm.show();

        $usageLimitInput
            .val(selectedCount)
            .prop('readonly', true)
            .prop('disabled', true);

        $usageLimitButtons.prop('disabled', true);

        // Customer Limit must remain enabled/editable.
        $customerLimitCheckbox.prop('disabled', false);
        $customerLimitInput
            .prop('readonly', false)
            .prop('disabled', false);

        $customerLimitButtons.prop('disabled', false);
    } else {
        $('#customer-optn').hide();
        $('#specific_customer_count').html('');

        // For "All", Usage Limit is editable again.
        $usageLimitCheckbox.prop('disabled', false);
        $usageLimitInput
            .prop('readonly', false)
            .prop('disabled', false);

        $usageLimitButtons.prop('disabled', false);

        // Customer Limit remains editable for all customers.
        $customerLimitCheckbox.prop('disabled', false);
        $customerLimitInput
            .prop('readonly', false)
            .prop('disabled', false);

        $customerLimitButtons.prop('disabled', false);
    }
}

$('select[name="customer[]"]').on('change select2:select select2:unselect', function () {
    updateSpecificCustomerCount();
});

$('#coupon-scope-specific').on('click change', function () {
    $('#customer-optn').show();
    updateSpecificCustomerCount();
});

$('#coupon-scope-all').on('click change', function () {
    $('#customer-optn').hide();
    $('select[name="customer[]"]').val(null).trigger('change');

    $('#coupon-usage-limit').prop('disabled', false);
    $('input[name="usage_limit"]').prop('readonly', false).prop('disabled', false);
    $('.btn-number[data-field="usage_limit"]').prop('disabled', false);

    updateSpecificCustomerCount();
});

updateSpecificCustomerCount();
</script>
@endsection