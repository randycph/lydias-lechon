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
                        <li class="breadcrumb-item active" aria-current="page">Create Gift Certificate</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Create Gift Certificate</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <form autocomplete="off" action="{{ route('gift-certificate.store') }}" method="post">
                    @method('POST')
                    @csrf
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Code <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('code') is-invalid @enderror" name="code" id="code" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="code" />
                            </div>
                            <div class="form-group mg-b-20">
                                <label class="mg-b-5 tx-color-03">Amount <i class="tx-danger">*</i></label>
                                <input required type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" id="amount" @htmlValidationMessage({{__('standard.empty_all_field')}})>
                                <x-error-message inputName="amount" />
                            </div>
                            <div class="form-group">
                                <label class="mg-b-5 tx-color-03">Gift certificate Type <i class="tx-danger">*</i></label>
                                <select name="gc_type" class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-sm btn-block tx-left" title="Select GC Type" data-width="100%" required @error('transition_out') @htmlValidationMessage({{__('standard.banner.empty_transition')}}) @enderror >
                                    <option value="E-gift">E-gift (electronic gift)</option>
                                    <option value="Physical GC">Physical GC</option>
                                    <option value="Complimentary">Complimentary (Amount)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="mg-b-5 tx-color-03">Status</label>
                                <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                                    <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") ? "checked":"") }} id="customSwitch1">
                                    <label class="custom-control-label" id="label_visibility" for="customSwitch1">Unused</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary btn-uppercase">Save Gift Certificate</button>
                    <a href="{{ route('gift-certificate.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
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
