@extends('admin.layouts.app')

@section('pagetitle')
    Customer Management
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('users.index')}}">Customers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit a Customer</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit a Customer</h4>
            </div>
        </div>

        {{--    @if($message = Session::get('duplicate'))--}}
        {{--        <div class="alert alert-warning d-flex align-items-center mg-t-15" role="alert">--}}
        {{--            <p class="mg-b-0"><i data-feather="alert-circle" class="mg-r-10"></i>{{ $message }}--}}
        {{--        </div>--}}
        {{--    @endif--}}

        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('customers.update',$user->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="d-block">Customer Type *</label>
                        {{-- <select name="role" class="selectpicker mg-b-5 @error('role') is-invalid @enderror" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select role" data-width="100%" required> --}}
                        {{-- @foreach($roles as $role) --}}
                        {{--    <option value="{{ $role->id }}" {{ (old("role", $user->role_id) == $role->id ? "selected":"") }}>{{ $role->name }}</option> --}}
                        {{-- @endforeach --}}
                        {{-- </select> --}}
                        {{-- @error(['inputName' => 'role']) --}}
                        {{-- @enderror --}}
                        <select name="is_org" id="is_org" class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Customer Type" data-width="100%" required>
                            <option value="{{ $collection['is_org'] }}" selected>{{ $customer_type }}</option>
                            <option value="0">Individual</option>
                            <option value="1">Organization</option>
                        </select>
                    </div>
                   

                    <h4 class="mg-b-0 tx-spacing--1">General Information</h4>

                    <div class="individual" style="@if($user->is_org == 1 ) display:none @endif">
                        <div class="form-group">
                            <label class="d-block">First Name *</label>
                            <input type="text" name="fname" id="fname" value="{{ old('fname', $user->firstname)}}" class="form-control @error('fname') is-invalid @enderror" required>
                            @error(['inputName' => 'fname'])
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Last Name *</label>
                            <input type="text" name="lname" id="lname" value="{{ old('lname', $user->lastname)}}" class="form-control @error('lname') is-invalid @enderror" required>
                            @error(['inputName' => 'lname'])
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Birth Date </label>
                            <input type="date" name="birthday" id="birthday" value="{{ old('birthday', $user->birthday)}}" class="form-control @error('birthday') is-invalid @enderror">
                            @error(['inputName' => 'birthday'])
                            @enderror
                        </div>
                    </div>
                    <div class="form-group org" style="@if($user->is_org <> 1 ) display:none @endif">
                        <label class="d-block">Organization *</label>
                        <input type="text" name="organization" id="organization" value="{{ old('organization',$user->organization)}}" class="form-control @error('organization') is-invalid @enderror" required>
                        @error(['inputName' => 'organization'])
                        @enderror
                    </div>

                    <h4 class="mg-b-0 tx-spacing--1">Address Information</h4>

                    <div class="form-group">
                        <label class="d-block">Street *</label>
                        <input type="text" name="address_street" id="address_street" value="{{ old('address_street',$user->address_street)}}" class="form-control @error('address_street') is-invalid @enderror" required>
                        @error(['inputName' => 'address_street'])
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Municipality *</label>
                        <input type="text" name="address_municipality" id="address_municipality" value="{{ old('address_municipality',$user->address_municipality)}}" class="form-control @error('address_municipality') is-invalid @enderror" required>
                        @error(['inputName' => 'address_municipality'])
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">City *</label>
                        <input type="text" name="address_city" id="address_city" value="{{ old('address_city',$user->address_city)}}" class="form-control @error('address_city') is-invalid @enderror" required>
                        @error(['inputName' => 'address_city'])
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Region *</label>
                        <input type="text" name="address_region" id="address_region" value="{{ old('address_region',$user->address_region)}}" class="form-control @error('address_region') is-invalid @enderror" required>
                        @error(['inputName' => 'address_region'])
                        @enderror
                    </div>
                    <h4 class="mg-b-0 tx-spacing--1">Contact Information</h4>
                    <div class="form-group">
                        <label class="d-block">Email *</label>
                        <input type="text" name="email" id="email" required="required" value="{{ old('email', $user->email)}}" class="form-control @error('email') is-invalid @enderror" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        @error(['inputName' => 'email'])
                        @enderror
                    </div>
                    <div class="form-group org" style="@if($user->is_org <> 1 ) display:none @endif">
                        <label class="d-block">Contact Person *</label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $user->contact_person)}}" class="form-control @error('contact_person') is-invalid @enderror" required>
                        @error(['inputName' => 'contact_person'])
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Telephone Number *</label>
                        <input type="text" name="contact_tel" id="contact_tel" value="{{ old('contact_tel',$user->contact_tel)}}" class="form-control @error('contact_tel') is-invalid @enderror">
                        @error(['inputName' => 'contact_tel'])
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Mobile Number *</label>
                        <input type="text" name="contact_mobile" id="contact_mobile" value="{{ old('contact_mobile',$user->contact_mobile)}}" class="form-control @error('contact_mobile') is-invalid @enderror">
                        @error(['inputName' => 'contact_mobile'])
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Fax Number</label>
                        <input type="text" name="contact_fax" id="contact_fax" value="{{ old('contact_fax',$user->contact_fax)}}" class="form-control @error('contact_fax') is-invalid @enderror">
                        @error(['inputName' => 'contact_fax'])
                        @enderror
                    </div>
                    <hr>
                    <div class="form-group" style="display:none;">
                        <label class="d-block">Registration Source </label>
                        <input type="text" name="registration_source" id="registration_source" value="{{ old('registration_source',$user->registration_source)}}" class="form-control @error('registration_source') is-invalid @enderror">
                        @error(['inputName' => 'registration_source'])
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Agent Code </label>
                        <input type="text" name="agent_code" id="agent_code" value="{{ old('agent_code',$user->agent_code)}}" class="form-control @error('agent_code') is-invalid @enderror">
                        @error(['inputName' => 'agent_code'])
                        @enderror
                    </div>
                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Customer</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('customers.index') }}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(function() {
            @if($user->is_org <> 1 )
                $('#fname').attr('required', true);
                $('#lname').attr('required', true);
                $('#contact_person').attr('required', false);
                $('#organization').attr('required', false);
                $('.org').hide();
                $('.individual').show();
            @else
                $('#fname').attr('required', false);
                $('#lname').attr('required', false);
                $('#contact_person').attr('required', true);
                $('#organization').attr('required', true);
                $('.org').show();
                $('.individual').hide();
            @endif
        });
    </script>
    <script type="text/javascript">
        $("#is_org").change(function(){
            var x = $("#is_org").val();
            if(x == '0'){
                $('#fname').attr('required', true);
                $('#lname').attr('required', true);
                $('#contact_person').attr('required', false);
                $('#organization').attr('required', false);
                $('.org').hide();
                $('.individual').show();
            }
            else
            {
                $('#fname').attr('required', false);
                $('#lname').attr('required', false);
                $('#contact_person').attr('required', true);
                $('#organization').attr('required', true);
                $('.individual').hide();
                $('.org').show();
            }
        });
    </script>
@endsection
