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
            <form autocomplete="off" action="{{ route('users.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label class="d-block">First Name *</label>
                        <input type="text" name="fname" id="fname" value="{{ old('fname')}}" class="form-control @error('fname') is-invalid @enderror" required>
                        @hasError(['inputName' => 'fname'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label class="d-block">Last Name *</label>
                        <input type="text" name="lname" id="lname" value="{{ old('lname')}}" class="form-control @error('lname') is-invalid @enderror" required>
                        @hasError(['inputName' => 'lname'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label class="d-block">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email')}}" class="form-control @error('email') is-invalid @enderror" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        @hasError(['inputName' => 'email'])
                        @endhasError
                    </div>
                    <div class="form-group">
                        <label class="d-block">Role *</label>
                        <select name="role" class="form-control select2-no-search" required onchange="user_role($(this).val());">
                            <option value=""></option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ (old("role") == $role->id ? "selected":"") }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @hasError(['inputName' => 'role'])
                        @endhasError
                    </div>

                    <div class="form-group d-none" id="branches_div">
                        <label class="d-block">Branches *</label>
                        <select name="branches[]" id="branches" class="form-control select2" multiple disabled>
                            <option label="Choose one"></option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (old("branches") == $branch->id ? "selected":"") }}>{{ ucwords($branch->name) }}</option>
                            @endforeach
                        </select>
                        @hasError(['inputName' => 'branches'])
                        @endhasError
                    </div>

                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Create User</button>
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
        $(function(){
            'use strict'

            $('.select2').select2({
                placeholder: 'Choose one',
                searchInputPlaceholder: 'Search options'
            });

            $('.select2-no-search').select2({
                minimumResultsForSearch: Infinity,
                placeholder: 'Choose one'
            });
        });

        function user_role(role){

            if(role == 2 || role == 4 || role == 12){ // check if selected user type is branch manager, staff, Cashier
                $('#branches_div').removeClass('d-none');
                $('#branches_div').addClass('d-block');
                $('#branches').removeAttr('disabled');
                $('#branches').prop('required',true);
            } else {
                $('#branches_div').removeClass('d-block');
                $('#branches_div').addClass('d-none');

                $('#branches').attr('disabled','disabled');
                $('#branches').prop('required',false);
            }
        }
    </script>
@endsection
