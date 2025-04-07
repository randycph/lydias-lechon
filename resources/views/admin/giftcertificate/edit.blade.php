@extends('admin.layouts.app')

@section('pagetitle')
    Gift Certificate Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
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
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('gift-certificate.index')}}">Gift Certificate</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Gift Certificate</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit Gift Certificate</h4>
            </div>
        </div>

        <form autocomplete="off" action="{{ route('gift-certificate.update', $giftcertificate->id) }}" method="post" enctype="multipart/form-data">
            <div class="row row-sm">
                @method('PUT')
                @csrf
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Code *</label>
                        <input name="code" id="code" value="{{ old('code', $giftcertificate->code) }}" required type="text" class="form-control @error('code') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="code" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Amount *</label>
                        <input name="amount" id="amount" value="{{ old('code', $giftcertificate->amount) }}" required type="text" class="form-control @error('amount') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="amount" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Gift Certificate Type *</label>
                        <select class="selectpicker mg-b-5 @error('gc_type') is-invalid @enderror" id="gc_type" name="gc_type" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select GC Type" data-width="100%">
                            <option value="E-gift" {{ ('E-gift' == $giftcertificate->gc_type ? "selected":"") }}>E-gift (electronic gift) </option>
                            <option value="Physical GC" {{ ('Physical GC' == $giftcertificate->gc_type ? "selected":"") }}>Physical GC </option>
                            <option value="Complimentary" {{ ('Complimentary' == $giftcertificate->gc_type ? "selected":"") }}>Complimentary (Amount)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Status *</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") == "ON" || $giftcertificate->status == "Used" ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucfirst(strtolower($giftcertificate->status))}}</label>
                            <x-error-message inputName="status" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mg-t-30">
                    <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Update Gift Certificate">
                    <a href="{{ route('gift-certificate.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $("#customSwitch1").change(function() {
            if(this.checked) {
                $('#label_visibility').html('Used');
            }
            else{
                $('#label_visibility').html('Unused');
            }
        });
    </script>

@endsection
