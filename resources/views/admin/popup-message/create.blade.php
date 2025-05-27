@extends('admin.layouts.app')

@section('pagecss')
	<link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
	<link href="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
	<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
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
	</style>
@endsection

@section('content')
<div class="container pd-x-0">
	<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
		<div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb breadcrumb-style1 mg-b-10">
					<li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="{{ route('popup-message.index') }}">Popup Messages</a></li>
					<li class="breadcrumb-item active" aria-current="page">Create Popup Message</li>
				</ol>
			</nav>
			<h4 class="mg-b-0 tx-spacing--1">Create Popup Message</h4>
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
	<form method="post" action="{{ route('popup-message.store') }}" id="couponForm" autocomplete="off">
		@csrf
		<div class="row row-sm">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="d-block">Title *</label>
					<input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">

				</div>
				<div class="form-group">
					<label class="d-block">Message *</label>
					<textarea name="message" rows="3" class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
				</div>
				<div class="form-group">
					<label class="d-block">Button text *</label>
					<input type="text" name="button_text" class="form-control @error('button_text') is-invalid @enderror" value="{{ old('button_text') }}">

				</div>
				<div class="form-group">
					<label class="d-block">Button text URL*</label>
					<input type="text" name="button_text_url" class="form-control @error('button_text_url') is-invalid @enderror" value="{{ old('button_text_url') }}">

				</div>
				<div class="form-group">
					<label class="d-block">Close Button text *</label>
					<input type="text" name="close_button_text" class="form-control @error('close_button_text') is-invalid @enderror" value="{{ old('close_button_text') }}">
				</div>
				<div class="form-group">
					<label class="d-block">Page URL to show popup *</label>
					<input type="text" name="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}">
					<p class="tx-11 mg-t-4">Available are the following index, our-story, our-stores, lechon-pricelist, lechon-menu. Can only accept one.</p>
				</div>
				<div class="form-group">
					<label class="d-block">Start to show in seconds*</label>
					<input type="text" name="start_to_show" class="form-control @error('start_to_show') is-invalid @enderror" value="{{ old('start_to_show') }}">
					<p class="tx-11 mg-t-4">Enter the number of seconds after which the popup will be shown. Default is 0 seconds.</p>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="form-group">
					<label class="d-block">Status</label>
					<div class="custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="enableSwitch1" name="is_active" {{ (old("is_active") ? "checked":"") }}>
						<label class="custom-control-label" for="enableSwitch1" id="label_status">@if(old('is_active')) Active @else Inactive @endif</label>
					</div>
				</div>
			</div>

			<div class="col-lg-12 mg-t-30">
				<button class="btn btn-primary btn-sm btn-uppercase" type="submit" id="btnSubmit">Save</button>
				<a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
			</div>
		</div>
	</form>
	<!-- row -->
</div>

@endsection

@section('pagejs')
	<script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
	<script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
	<script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
	<script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection


@section('customjs')
<script>
$(function() {
	$('.selectpicker').selectpicker();
});
</script>
@endsection