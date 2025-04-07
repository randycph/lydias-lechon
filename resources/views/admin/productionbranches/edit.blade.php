@extends('admin.layouts.app')

@section('pagetitle')
    Production Branch
@endsection

@section('pagecss')
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
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('production-branches.index')}}">Production Branches</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Production Branch</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit a Production Branch</h4>
            </div>
        </div>


        <form autocomplete="off" action="{{ route('production-branches.update', $productions->id) }}" method="post" enctype="multipart/form-data">
            <div class="row row-sm">
                @method('PUT')
                @csrf
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input name="name" id="name" value="{{ old('name', $productions->name) }}" required type="text" class="form-control @error('namename') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="name" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Region *</label>
                        <input name="address_region" id="address_region" value="{{ old('address_region',$productions->address_region) }}" required type="text" class="form-control @error('code') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="address_region" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Province *</label>
                        <input name="address_province" id="address_province" value="{{ old('address_province',$productions->address_province) }}" required type="text" class="form-control @error('address') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="address_province" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">City *</label>
                        <input name="address_city" id="address_city" value="{{ old('address_city',$productions->address_city) }}" required type="text" class="form-control @error('contact_nos') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="address_city" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Street *</label>
                        <input name="address_street" id="address_street" value="{{ old('address_street',$productions->address_street) }}" required type="text" class="form-control @error('contact_person') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="address_street" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Zip *</label>
                        <input name="address_zip" id="address_zip" value="{{ old('address_zip',$productions->address_zip) }}" required type="text" class="form-control @error('email_address') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="address_zip" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Telephone Number *</label>
                        <input name="contact_tel" id="contact_tel" value="{{ old('contact_tel',$productions->contact_tel) }}" required type="text" class="form-control @error('email_address') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="contact_tel" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Mobile Number *</label>
                        <input name="contact_mobile" id="contact_mobile" value="{{ old('contact_mobile',$productions->contact_mobile) }}" required type="text" class="form-control @error('email_address') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="contact_mobile" />
                    </div>
                    <div class="form-group">
                        <label class="d-block">Contact Person *</label>
                        <input name="contact_person" id="contact_person" value="{{ old('contact_person',$productions->contact_person) }}" required type="text" class="form-control @error('email_address') is-invalid @enderror" maxlength="250">
                        <x-error-message inputName="contact_person" />
                    </div>
                </div>
                <div class="col-lg-12 mg-t-30">
                    <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Update Production Branch">
                    <a href="{{ route('production-branches.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
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

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });

        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#address_zip,#contact_mobile,#contact_tel").keypress(function (e) {

                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });  

    </script>
@endsection
