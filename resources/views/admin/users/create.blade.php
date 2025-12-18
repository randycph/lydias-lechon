@extends('admin.layouts.app')

@section('pagetitle')
User Management
@endsection

@section('pagecss')
<link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">

<style>
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


    .select2-container--default .select2-search--inline .select2-search__field {
        padding-left: 8px;
    }

    .select2 {
        width: 100% !important;
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
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('users.index')}}">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create a User</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Create a User</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form autocomplete="off" action="{{ route('users.store') }}" method="post" id="user_form">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label class="d-block">First Name *</label>
                    <input type="text" name="fname" id="fname" value="{{ old('fname')}}"
                        class="form-control @error('fname') is-invalid @enderror" required>
                    @error('fname')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="d-block">Last Name *</label>
                    <input type="text" name="lname" id="lname" value="{{ old('lname')}}"
                        class="form-control @error('lname') is-invalid @enderror" required>
                    @error('lname')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="d-block">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email')}}"
                        class="form-control @error('email') is-invalid @enderror" required
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                @php
                    $oldRoleId = old('role');
                    $oldRole = $roles->firstWhere('id', (int)$oldRoleId);
                    $showBranches = (optional($oldRole)->has_branches ?? 0) == 1;
                    $showProd = (optional($oldRole)->has_production_branch ?? 0) == 1;

                    $branchesHasError = $errors->has('branches') || $errors->has('branches.*');
                    $prodHasError = $errors->has('production_branch_id');
                @endphp

                <div class="form-group">
                    <label class="d-block">Role *</label>
                    <select name="role" class="form-control select2-no-search" required onchange="user_role();">
                        <option value=""></option>
                        @foreach($roles as $r)
                        <option value="{{ $r->id }}" data-has-branches="{{ (int)$r->has_branches }}"
                            data-has-production-branch="{{ (int)$r->has_production_branch }}"
                            data-can-approve-payment="{{ (int)$r->can_approve_payment }}" {{ old('role')==$r->id ?
                            'selected' : '' }}
                            >{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('role')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Branches --}}
                <div class="form-group {{ $showBranches || $branchesHasError ? 'd-block' : 'd-none' }}"
                    id="branches_div">
                    <label class="d-block">Branches *</label>
                    <select name="branches[]" id="branches"
                        class="form-control select2 select-branch {{ $branchesHasError ? 'is-invalid' : '' }}" multiple {{
                        $showBranches || $branchesHasError ? '' : 'disabled' }} data-placeholder="Choose one">
                        <option value=""></option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" @if(in_array($branch->id, (array)old('branches', [])))
                            selected @endif>
                            {{ ucwords($branch->name) }}
                        </option>
                        @endforeach
                    </select>
                    @error('branches')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    @error('branches.*')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Production Branch --}}
                <div class="form-group {{ $showProd || $prodHasError ? 'd-block' : 'd-none' }}"
                    id="production_branches_div">
                    <label class="d-block">Production Branch *</label>
                    <select name="production_branch_id[]" multiple="multiple" id="production_branch_id"
                        class="form-control select2 select-production-branch {{ $prodHasError ? 'is-invalid' : '' }}" {{ $showProd ||
                        $prodHasError ? '' : 'disabled' }} data-placeholder="Choose one">
                        <option value=""></option>
                        @foreach($production_branches as $branch)
                        <option value="{{ $branch->id }}" {{ in_array($branch->id, (array)old('production_branch_id', [])) ? 'selected' : '' }}>
                            {{ ucwords($branch->name) }}
                        </option>
                        @endforeach
                    </select>
                    @error('production_branch_id')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                @php
                    $oldRoleId = old('role');
                    $oldRole   = $roles->firstWhere('id', (int)$oldRoleId);
                    $showPayment = (optional($oldRole)->can_approve_payment ?? 0) == 1;
                    $paymentHasError = $errors->has('payment_types') || $errors->has('payment_types.*');
                    $oldPayments = (array) old('payment_types', []); // keep selections after error
                    $paymentOptions = \App\EcommerceModel\SalesPayment::get_types();
                @endphp

                <div class="form-group {{ $showPayment || $paymentHasError ? 'd-block' : 'd-none' }}" id="payment_div">
                    <label>Allowed to Approve (Payment Types)</label>
                    <select name="payment_types[]" multiple
                            id="payment_types"
                            class="form-control select2 select-payment-types {{ $paymentHasError ? 'is-invalid' : '' }}"
                            style="width:100%"
                            {{ $showPayment || $paymentHasError ? '' : 'disabled' }}
                            data-placeholder="Choose payment types">
                        <option value=""></option>
                        @foreach($paymentOptions as $p)
                            <option value="{{ $p }}" {{ in_array($p, $oldPayments, true) ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                    @error('payment_types')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    @error('payment_types.*')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    <small>These rules only apply if the user can confirm/deny payments.</small>
                </div>


                <button class="btn btn-primary btn-sm btn-uppercase" type="submit" id="submitBtn">Create User</button>
                <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('users.index') }}">Cancel</a>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@section('pagejs')
<script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/dashforge.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endsection

@section('customjs')
<script>
    $("#user_form").submit(function(e){
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerText = 'Saving...';
    });
    $(function(){
            'use strict'

            $('.select-branch').select2({
                placeholder: 'Choose Branches',
                searchInputPlaceholder: 'Search options',
            });

            $('.select-payment-types').select2({
                placeholder: 'Choose Payment Types',
                searchInputPlaceholder: 'Search options',
            });

            $('.select-production-branch').select2({
                minimumResultsForSearch: Infinity,
                placeholder: 'Choose Production Branches'
            });
        });

        function user_role(){
            const $roleOpt = $('select[name="role"] option:selected');
            const has_branches = Number($roleOpt.data('has-branches')) === 1;
            const has_production_branch = Number($roleOpt.data('has-production-branch')) === 1;
            const can_approve_payment = Number($roleOpt.data('can-approve-payment')) === 1;

            // Branches
            if (has_branches) {
                $('#branches_div').removeClass('d-none').addClass('d-block');
                $('#branches').prop('disabled', false).prop('required', true).trigger('change.select2');
            } else {
                $('#branches_div').removeClass('d-block').addClass('d-none');
                $('#branches').prop('required', false).prop('disabled', true).val(null).trigger('change');
            }

            // Production branch
            if (has_production_branch) {
                $('#production_branches_div').removeClass('d-none').addClass('d-block');
                $('#production_branch_id').prop('disabled', false).prop('required', true).trigger('change.select2');
            } else {
                $('#production_branches_div').removeClass('d-block').addClass('d-none');
                $('#production_branch_id').prop('required', false).prop('disabled', true).val('').trigger('change');
            }

            // Payment types (if you have it)
            if (can_approve_payment == 1){
                $('#payment_div').removeClass('d-none').addClass('d-block');
                $('#payment_types').prop('disabled', false).prop('required', false).trigger('change.select2');
            } else {
                $('#payment_div').removeClass('d-block').addClass('d-none');
                $('#payment_types').prop('required', false).prop('disabled', true).val(null).trigger('change');
            }
        }
</script>
@endsection