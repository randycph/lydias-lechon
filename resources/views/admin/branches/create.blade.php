@extends('admin.layouts.app')

@section('pagetitle')
    Branch Manager
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
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('branch.index')}}">Branch</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Branch</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Create a Branch</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <form autocomplete="off" action="{{ route('branch.store') }}" method="post">
                    @method('POST')
                    @csrf
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Branch Name <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="category_title" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                @hasError(['inputName' => 'name'])
                                @endhasError
                                <small id="category_slug"></small>
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Code <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('code') is-invalid @enderror" name="code" id="category_title" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                @hasError(['inputName' => 'code'])
                                @endhasError
                                <small id="category_slug"></small>
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Store Type</label>
                                <select name="branch_type" class="form-control" id="" >
                                    <option value="">Select</option>
                                    <option value="Restaurant">Restaurant</option>
                                    <option value="Mall Based Foodcourt">Mall Based Foodcourt</option>
                                    <option value="Kiosk">Kiosk</option>
                                    
                                </select>
                            </div>
                            <div class="form-group mg-b-20">
                                <input type="checkbox" name="pickup_branch" id="pickup_branch">
                                <label class="mg-b-5 tx-color-03">Pickup Branch</label>                                
                            </div>
                            <div class="form-group mg-b-20" style="display:none;">
                                <label class="mg-b-5 tx-color-03">Token <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('token') is-invalid @enderror" name="token" value="{{uniqid()}}" id="token" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                @hasError(['inputName' => 'token'])
                                @endhasError
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Address <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="category_title" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                @hasError(['inputName' => 'address'])
                                @endhasError
                                <small id="category_slug"></small>
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Contact Number <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('contact_nos') is-invalid @enderror" name="contact_nos" id="contact_nos" maxlength="250" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                @hasError(['inputName' => 'contact_nos'])
                                @endhasError
                                <small id="category_slug"></small>
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Contact Person</label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror" name="contact_person" id="category_title" >
                                @hasError(['inputName' => 'contact_person'])
                                @endhasError
                                <small id="category_slug"></small>
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Hotline </label>
                                <input type="text" class="form-control @error('hotline') is-invalid @enderror" name="hotline" id="category_title">
                                @hasError(['inputName' => 'hotline'])
                                @endhasError
                                <small id="category_slug"></small>
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Email Address <i class="tx-danger">*</i></label>
                                <input type="email" class="form-control @error('email_address') is-invalid @enderror" name="email_address" id="category_title" >
                                @hasError(['inputName' => 'email_address'])
                                @endhasError
                                <small id="category_slug"></small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary btn-uppercase">Save Branch</button>
                    <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </form>
            </div>
        </div>
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
            $("#contact_nos").keypress(function (e) {

                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });  

    </script>
@endsection
