@extends('admin.layouts.app')

@section('pagetitle')
    Manage Roles
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('role.index')}}">Roles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit a Role</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit a Role</h4>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card ht-lg-100p">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="mg-b-0">Roles</h6>
                </div><!-- card-header -->
                <div class="card-body pd-0">
                    <div class="table-responsive">
                        <form action="{{ route('role.update', $role->id) }}" method="post">
                            @method('PUT')
                            @csrf
                            <div class="modal-body pd-sm-t-30 pd-sm-b-40 pd-sm-x-30">
                                <div class="row row-sm">
                                    <div class="col-sm">
                                        <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">Role Name <i class="tx-danger">*</i></label>
                                        <input required type="text" class="form-control" name="role" value="{{ $role->name }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="d-block">Associated with branches</label>
                                    <div class="custom-control custom-switch @error('has_branches') is-invalid @enderror">
                                        <input type="checkbox" class="custom-control-input" name="has_branches" {{ (old("has_branches", $role->has_branches) ? "checked":"") }} id="customSwitch13">
                                        <label class="custom-control-label" id="label_visibility13" for="customSwitch13">{{ (old("has_branches", $role->has_branches) ? "Yes":"No") }}</label>
                                        <x-error-message inputName="has_branches" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="d-block">Can approve payment</label>
                                    <div class="custom-control custom-switch @error('can_approve_payment') is-invalid @enderror">
                                        <input type="checkbox" class="custom-control-input" name="can_approve_payment" {{ (old("can_approve_payment", $role->can_approve_payment) ? "checked":"") }} id="customSwitch14">
                                        <label class="custom-control-label" id="label_visibility14" for="customSwitch14">{{ (old("can_approve_payment", $role->can_approve_payment) ? "Yes":"No") }}</label>
                                        <x-error-message inputName="can_approve_payment" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="d-block">Has production branch</label>
                                    <div class="custom-control custom-switch @error('has_production_branch') is-invalid @enderror">
                                        <input type="checkbox" class="custom-control-input" name="has_production_branch" {{ (old("has_production_branch", $role->has_production_branch) ? "checked":"") }} id="customSwitch15">
                                        <label class="custom-control-label" id="label_visibility15" for="customSwitch15">{{ (old("has_production_branch", $role->has_production_branch) ? "Yes":"No") }}</label>
                                        <x-error-message inputName="has_production_branch" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="tx-10 tx-uppercase tx-medium tx-spacing-1 mg-b-5 tx-color-03">Role Description <i class="tx-danger">*</i></label>
                                    <textarea required class="form-control" name="description" rows="2">{{ $role->description }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer pd-x-20 pd-y-15">
                                <a href="{{ route('role.index') }}" class="btn btn-danger text-white">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Role</button>
                            </div>
                        </form>
                    </div>
                </div><!-- card-body -->
            </div><!-- card -->
        </div>
    </div>
</div>
@endsection


@section('pagejs')
    <script src="{{ asset('lib/nestable2/jquery.nestable.min.js') }}"></script>
    <script src="{{ asset('lib/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $('[data-toggle="tooltip"]').tooltip();

        $("#customSwitch13").change(function() {
            if(this.checked) {
                $('#label_visibility13').html('Yes');
            }
            else{
                $('#label_visibility13').html('No');
            }
        });

        $("#customSwitch14").change(function() {
            if(this.checked) {
                $('#label_visibility14').html('Yes');
            }
            else{
                $('#label_visibility14').html('No');
            }
        });

        $("#customSwitch15").change(function() {
            if(this.checked) {
                $('#label_visibility15').html('Yes');
            }
            else{
                $('#label_visibility15').html('No');
            }
        });
    </script>
@endsection
