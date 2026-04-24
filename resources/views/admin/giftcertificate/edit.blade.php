@extends('admin.layouts.app')

@section('pagetitle')
    Gift Certificate Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
    </style>
@endsection

@section('content')

<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{ route('dashboard') }}">CMS</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{ route('gift-certificate.index') }}">Gift Certificate</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Gift Certificate</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit Gift Certificate</h4>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
            <form autocomplete="off"
                  action="{{ route('gift-certificate.update', $giftcertificate->id) }}"
                  method="post"
                  enctype="multipart/form-data"
                  id="giftCertificateForm">

                @method('PUT')
                @csrf

                @php
                    /*
                        This supports multiple possible storage styles:
                        1. old('customer') from validation error
                        2. $giftcertificate->customer_id
                        3. $giftcertificate->customer_ids as array/json
                        4. $giftcertificate->customers relationship
                    */

                    $selectedCustomers = old('customer');

                    if (is_null($selectedCustomers)) {
                        if (isset($giftcertificate->customer_ids)) {
                            $selectedCustomers = is_array($giftcertificate->customer_ids)
                                ? $giftcertificate->customer_ids
                                : json_decode($giftcertificate->customer_ids, true);
                        } elseif (isset($giftcertificate->customers)) {
                            $selectedCustomers = $giftcertificate->customers->pluck('id')->toArray();
                        } elseif (isset($giftcertificate->customer_id)) {
                            $selectedCustomers = [$giftcertificate->customer_id];
                        } else {
                            $selectedCustomers = [];
                        }
                    }

                    $selectedCustomers = is_array($selectedCustomers) ? $selectedCustomers : [$selectedCustomers];
                @endphp

                <div class="row row-sm">
                    <div class="col-sm-6">

                        <div class="form-group mg-b-20">
                            <label class="mg-b-5 tx-color-03">Code <i class="tx-danger">*</i></label>
                            <input required
                                   type="text"
                                   class="form-control @error('code') is-invalid @enderror"
                                   name="code"
                                   id="code"
                                   value="{{ old('code', $giftcertificate->code) }}"
                                   maxlength="250"
                                   @htmlValidationMessage({{ __('standard.empty_all_field') }})>
                            <x-error-message inputName="code" />
                        </div>
                        
                        <div class="form-group mg-b-20">
                            <label class="mg-b-5 tx-color-03">Amount <i class="tx-danger">*</i></label>
                            <input required
                                   type="text"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   name="amount"
                                   id="amount"
                                   value="{{ old('amount', $giftcertificate->amount) }}"
                                   maxlength="250"
                                   @htmlValidationMessage({{ __('standard.empty_all_field') }})>
                            <x-error-message inputName="amount" />
                        </div>

                        <div class="form-group mg-b-20">
                            <label class="mg-b-5 tx-color-03">Gift Certificate Type <i class="tx-danger">*</i></label>
                            <select name="gc_type"
                                    id="gc_type"
                                    class="selectpicker mg-b-5 @error('gc_type') is-invalid @enderror"
                                    data-style="btn btn-outline-light btn-sm btn-block tx-left"
                                    title="Select GC Type"
                                    data-width="100%"
                                    required>
                                <option value="E-gift" {{ old('gc_type', $giftcertificate->gc_type) == 'E-gift' ? 'selected' : '' }}>
                                    E-gift (electronic gift)
                                </option>
                                <option value="Physical GC" {{ old('gc_type', $giftcertificate->gc_type) == 'Physical GC' ? 'selected' : '' }}>
                                    Physical GC
                                </option>
                                <option value="Complimentary" {{ old('gc_type', $giftcertificate->gc_type) == 'Complimentary' ? 'selected' : '' }}>
                                    Complimentary (Amount)
                                </option>
                            </select>
                            <x-error-message inputName="gc_type" />
                        </div>

                        <div class="form-group mg-b-20">
                            <label class="d-block">Customer Name *</label>
                            <select class="form-control select2 @error('customer') is-invalid @enderror"
                                    name="customer[]"
                                    multiple
                                    required>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ in_array($customer->id, $selectedCustomers) ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error-message inputName="customer" />
                        </div>

                        

                        <div class="form-group" style="visibility:hidden;">
                            <label class="mg-b-5 tx-color-03">Status</label>
                            <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       name="status"
                                       {{ old('status') == 'ON' || $giftcertificate->status == 'Used' ? 'checked' : '' }}
                                       id="customSwitch1">

                                <label class="custom-control-label" id="label_visibility" for="customSwitch1">
                                    {{ ucfirst(strtolower($giftcertificate->status ?? 'Unused')) }}
                                </label>

                                <x-error-message inputName="status" />
                            </div>
                        </div>

                    </div>
                </div>

                <button type="submit" class="btn btn-sm btn-primary btn-uppercase" id="submitBtn">
                    Update Gift Certificate
                </button>

                <a href="{{ route('gift-certificate.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">
                    Cancel
                </a>

            </form>
        </div>
    </div>
</div>

@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Choose Options',
                width: '100%'
            });

            $("#customSwitch1").change(function() {
                if (this.checked) {
                    $('#label_visibility').html('Used');
                } else {
                    $('#label_visibility').html('Unused');
                }
            });

            $("#giftCertificateForm").submit(function() {
                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerText = 'Updating...';
            });
        });
    </script>
@endsection