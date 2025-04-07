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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('production-branches.index')}}">Production Branches</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Production Branch</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Create a Production Branch</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <form autocomplete="off" action="{{ route('production-branches.store') }}" method="post">
                    @method('POST')
                    @csrf
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Name <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="name" />
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Region <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('address_region') is-invalid @enderror" name="address_region" id="address_region" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="address_region" />
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Province <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('address_province') is-invalid @enderror" name="address_province" id="address_province" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="address_province" />
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">City <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('address_city') is-invalid @enderror" name="address_city" id="address_city" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="address_city" />
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Street <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('address_street') is-invalid @enderror" name="address_street" id="address_street" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="address_street" />
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Zip<i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('address_zip') is-invalid @enderror" name="address_zip" id="address_zip" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="address_zip" />
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Telephone Number <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('contact_tel') is-invalid @enderror" name="contact_tel" id="contact_tel" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="contact_tel" />
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Mobile Number <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('contact_mobile') is-invalid @enderror" name="contact_mobile" id="contact_mobile" @htmlValidationMessage({{__('standard.empty_all_field')}}) maxlength="13">
                                <x-error-message inputName="contact_mobile" />
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Contact Person <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('contact_person') is-invalid @enderror" name="contact_person" id="contact_person" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="contact_person" />
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary btn-uppercase">Save Branch</button>
                    <a href="{{ route('production-branches.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        {{--let searchType = "{{ $searchType }}";--}}
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
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
