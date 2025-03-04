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
                            <li class="breadcrumb-item active" aria-current="page">Edit Branch</li>
                        </ol>
                    </nav>
                    <h4 class="mg-b-0 tx-spacing--1">Edit a Branch</h4>
                </div>
            </div>


            <form autocomplete="off" action="{{ route('branch.update', $branches->id) }}" method="post">
                <div class="row row-sm">
                    @method('PUT')
                    @csrf
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="d-block">Branch Name *</label>
                            <input name="name" id="name" value="{{ old('name', $branches->name) }}" required type="text" class="form-control @error('name') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'name'])
                            @endhasError
                        </div>
                        <div class="form-group">
                            <label class="d-block">Code *</label>
                            <input name="code" id="code" value="{{ old('code',$branches->code) }}" required type="text" class="form-control @error('code') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'code'])
                            @endhasError
                        </div>
                        <div class="form-group mg-b-20">
                            <label class="mg-b-5 tx-color-03">Store Type </label>
                            <select name="branch_type" class="form-control" id="branch_type">
                                <option value="">Select</option>
                                <option value="Restaurant" @if($branches->branch_type=='Restaurant') selected="selected" @endif>Restaurant</option>
                                <option value="Mall Based Foodcourtk" @if($branches->branch_type=='Mall Based Foodcourt') selected="selected" @endif>Mall Based Foodcourt</option>
                                <option value="Kiosk" @if($branches->branch_type=='Kiosk') selected="selected" @endif>Kiosk</option>
                            </select>
                        </div>
                        <div class="form-group mg-b-20">
                            <input type="checkbox" name="pickup_branch" id="pickup_branch" @if($branches->pickup_branch=='1') checked="checked" @endif>
                            <label class="mg-b-5 tx-color-03">Pickup Branch</label>                                
                        </div>
                        <div class="form-group" style="display:none;">
                            <label class="d-block">Token *</label>
                            <input name="token" id="token" value="{{ old('token',$branches->token) }}" required type="text" class="form-control @error('token') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'token'])
                            @endhasError
                        </div>
                        <div class="form-group">
                            <label class="d-block">Address *</label>
                            <input name="address" id="address" value="{{ old('address',$branches->address) }}" required type="text" class="form-control @error('address') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'address'])
                            @endhasError
                        </div>
                        <div class="form-group">
                            <label class="d-block">Contact Number *</label>
                            <input name="contact_nos" id="contact_nos" value="{{ old('contact_nos',$branches->contact_nos) }}" required type="text" class="form-control @error('contact_nos') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'contact_nos'])
                            @endhasError
                        </div>
                        <div class="form-group">
                            <label class="d-block">Contact Person *</label>
                            <input name="contact_person" id="contact_person" value="{{ old('name',$branches->contact_person) }}" type="text" class="form-control @error('contact_person') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'contact_person'])
                            @endhasError
                        </div>
                        <div class="form-group">
                            <label class="d-block">Hotline</label>
                            <input name="hotline" id="hotline" value="{{ old('name',$branches->hotline) }}" type="text" class="form-control @error('hotline') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'hotline'])
                            @endhasError
                        </div>
                        <div class="form-group">
                            <label class="d-block">Email Address *</label>
                            <input name="email_address" id="email_address" value="{{ old('name',$branches->email_address) }}" type="text" class="form-control @error('email_address') is-invalid @enderror" maxlength="250">
                            @hasError(['inputName' => 'email_address'])
                            @endhasError
                        </div>
                    </div>
                    <div class="col-lg-12 mg-t-30">
                        <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Update Branch">
                        <a href="{{ route('branch.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
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
    <script>
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


