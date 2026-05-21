@extends('admin.layouts.app')

@section('pagetitle')
    User Management
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">

    <style>
        .select2 {
            width: 100% !important;
        }

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
            position: absolute;
            top: 4px;
            left: 7px;
            display: inline-block;
            color: #fff;
            opacity: .5;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.2;
        }

        .select2-container--default .select2-search--inline .select2-search__field {
            padding-left: 8px;
        }
    </style>
@endsection

@section('content')
    @php
        $selectedRoleId = old('role', $user->role_id);
        $selectedRole = $roles->firstWhere('id', (int) $selectedRoleId) ?? $user->assign_role;

        $showBranches = (optional($selectedRole)->has_branches ?? 0) == 1;
        $showProductionBranch = (optional($selectedRole)->has_production_branch ?? 0) == 1;
        $showPayment = (optional($selectedRole)->can_approve_payment ?? 0) == 1;

        $branchesHasError = $errors->has('branches') || $errors->has('branches.*');
        $productionBranchHasError = $errors->has('production_branch_id') || $errors->has('production_branch_id.*');
        $paymentHasError = $errors->has('payment_types') || $errors->has('payment_types.*');

        $selectedBranches = old('branches', $userbranch->pluck('branch_id')->toArray());
        $selectedProductionBranches = old('production_branch_id', array_filter(explode(',', (string) $user->production_branch_id)));
        $selectedPayments = old('payment_types', array_filter(explode(',', (string) $user->allowed_payments)));

        $paymentOptions = \App\EcommerceModel\SalesPayment::get_types();
    @endphp

    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="{{ route('dashboard') }}">CMS</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="{{ route('users.index') }}">Users</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit a User</li>
                    </ol>
                </nav>

                <h4 class="mg-b-0 tx-spacing--1">Edit a User</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-lg-6">
                <form action="{{ route('users.update', $user->id) }}" method="post" id="user_form" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="d-block">First Name *</label>
                        <input type="text"
                               name="fname"
                               id="fname"
                               value="{{ old('fname', $user->firstname) }}"
                               class="form-control @error('fname') is-invalid @enderror"
                               required>

                        @error('fname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message ?? '' }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Last Name *</label>
                        <input type="text"
                               name="lname"
                               id="lname"
                               value="{{ old('lname', $user->lastname) }}"
                               class="form-control @error('lname') is-invalid @enderror"
                               required>

                        @error('lname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message ?? '' }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Email *</label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email', strtolower($user->email)) }}"
                               class="form-control @error('email') is-invalid @enderror"
                               required
                               pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message ?? '' }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Role *</label>
                        <select name="role"
                                class="form-control select2-no-search"
                                required
                                onchange="user_role();">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                        data-has-branches="{{ (int) $role->has_branches }}"
                                        data-has-production-branch="{{ (int) $role->has_production_branch }}"
                                        data-can-approve-payment="{{ (int) $role->can_approve_payment }}"
                                        {{ (int) $selectedRoleId === (int) $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message ?? '' }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group {{ $showProductionBranch || $productionBranchHasError ? 'd-block' : 'd-none' }}"
                         id="production_branches_div">
                        <label class="d-block">Production Branch *</label>

                        <select name="production_branch_id[]"
                                id="production_branch_id"
                                multiple
                                class="form-control select2 select-production-branch {{ $productionBranchHasError ? 'is-invalid' : '' }}"
                                {{ $showProductionBranch || $productionBranchHasError ? '' : 'disabled' }}
                                data-placeholder="Choose Production Branches">
                            @foreach($production_branches as $branch)
                                <option value="{{ $branch->id }}"
                                        {{ in_array($branch->id, (array) $selectedProductionBranches) ? 'selected' : '' }}>
                                    {{ ucwords($branch->name) }}
                                </option>
                            @endforeach
                        </select>

                        @error('production_branch_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        @error('production_branch_id.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group {{ $showBranches || $branchesHasError ? 'd-block' : 'd-none' }}"
                         id="branches_div">
                        <label class="d-block">Branches *</label>

                        <select name="branches[]"
                                id="branches"
                                class="form-control select2 select-branch {{ $branchesHasError ? 'is-invalid' : '' }}"
                                multiple
                                {{ $showBranches || $branchesHasError ? '' : 'disabled' }}
                                data-placeholder="Choose Branches">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}"
                                        {{ in_array($branch->id, (array) $selectedBranches) ? 'selected' : '' }}>
                                    {{ ucwords($branch->name) }}
                                </option>
                            @endforeach
                        </select>

                        @error('branches')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        @error('branches.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group {{ $showPayment || $paymentHasError ? 'd-block' : 'd-none' }}"
                         id="payment_div">
                        <label>Allowed Payment Types</label>

                        <select name="payment_types[]"
                                id="payment_types"
                                multiple
                                class="form-control select2 select-payment-types {{ $paymentHasError ? 'is-invalid' : '' }}"
                                style="width:100%"
                                {{ $showPayment || $paymentHasError ? '' : 'disabled' }}
                                data-placeholder="Choose Payment Types">
                            @foreach($paymentOptions as $payment)
                                <option value="{{ $payment }}"
                                        {{ in_array($payment, (array) $selectedPayments, true) ? 'selected' : '' }}>
                                    {{ $payment }}
                                </option>
                            @endforeach
                        </select>

                        @error('payment_types')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        @error('payment_types.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <small>These rules only apply if the user has access to confirm or deny payments.</small>
                    </div>

                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit" id="submitBtn">
                        Update User
                    </button>

                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('users.index') }}">
                        Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    {{-- Do not reload jquery/bootstrap/dashforge here. They should be loaded once in admin.layouts.app. --}}
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(function () {
            $('.select-branch').select2({
                placeholder: 'Choose Branches',
                searchInputPlaceholder: 'Search options'
            });

            $('.select-payment-types').select2({
                placeholder: 'Choose Payment Types',
                searchInputPlaceholder: 'Search options'
            });

            $('.select-production-branch').select2({
                minimumResultsForSearch: Infinity,
                placeholder: 'Choose Production Branches'
            });

            user_role();

            $('#user_form').on('submit', function () {
                const btn = document.getElementById('submitBtn');

                if (btn) {
                    btn.disabled = true;
                    btn.innerText = 'Updating...';
                }
            });

            // Safe fallback for the header/user dropdown.
            $(document).on('click', '[data-toggle="dropdown"], [data-bs-toggle="dropdown"]', function (e) {
                if (typeof $.fn.dropdown !== 'function') {
                    return;
                }

                e.preventDefault();
                $(this).dropdown('toggle');
            });
        });

        function toggleField(wrapperSelector, fieldSelector, shouldShow, clearValue = true) {
            const $wrapper = $(wrapperSelector);
            const $field = $(fieldSelector);

            if (shouldShow) {
                $wrapper.removeClass('d-none').addClass('d-block');
                $field.prop('disabled', false).prop('required', true).trigger('change.select2');
                return;
            }

            $wrapper.removeClass('d-block').addClass('d-none');
            $field.prop('required', false).prop('disabled', true);

            if (clearValue) {
                $field.val(null).trigger('change');
            }
        }

        function user_role() {
            const $roleOpt = $('select[name="role"] option:selected');

            const hasBranches = Number($roleOpt.data('has-branches')) === 1;
            const hasProductionBranch = Number($roleOpt.data('has-production-branch')) === 1;
            const canApprovePayment = Number($roleOpt.data('can-approve-payment')) === 1;

            toggleField('#branches_div', '#branches', hasBranches, false);
            toggleField('#production_branches_div', '#production_branch_id', hasProductionBranch, false);

            if (canApprovePayment) {
                $('#payment_div').removeClass('d-none').addClass('d-block');
                $('#payment_types').prop('disabled', false).prop('required', false).trigger('change.select2');
            } else {
                $('#payment_div').removeClass('d-block').addClass('d-none');
                $('#payment_types').prop('required', false).prop('disabled', true).trigger('change.select2');
            }
        }
    </script>
@endsection
