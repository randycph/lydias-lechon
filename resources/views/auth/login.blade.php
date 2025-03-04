@extends('auth.main')

@section('content')
    <div class="sign-wrapper">
        @if($message = Session::get('error'))
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
            </div>
        @endif

        @if($message = Session::get('msg'))
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
            </div>
        @endif
        <div class="wd-100p">
            <h3 class="mg-b-3">Sign In</h3>
            <p class="tx-color-03 tx-14 mg-b-40">Welcome to {{ \App\Setting::getWebsiteName() }} Admin Portal. Please sign in to continue.</p>
            <form autocomplete="off" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email"><i class="tx-danger">*</i> Email</label>
                    <input required type="text" id="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}">
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                </div>

                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password"><i class="tx-danger">*</i> Password</label>
                    <input required type="password" id="password" name="password" class="form-control" placeholder="********" >
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                </div>
                
                <div class="form-group">
                    <div id="chxbx">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck" name="is_kiosk" value="1">
                            <label class="custom-control-label" for="customCheck">Kiosk Login</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('branch') ? 'has-error' : '' }}">
                    <label for="password"><i class="tx-danger">*</i> Branch</label>

                    <select name="branch" required="required" id="branch" class="form-control">
                        <option value="" selected disabled>Select Branch</option>
                        <option value="web">Web</option>
                        <option value="admin">Admin Portal</option>
                        <option value="forecaster">Forecaster Portal</option>
                        {!! \App\EcommerceModel\Branch::branches() !!}

                    </select>
                    <span class="text-danger">{{ $errors->first('branch') }}</span>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Sign In</button>
                <a href="{{route('password.request')}}" class="btn btn-link btn-sm">Forgot Password?</a>
            </form>
            <div class="cms-footer mg-t-50">
                <hr>
                <p class="tx-gray-500 tx-10">Admin Portal v1.0 • Developed by WebFocus Solutions, Inc. &copy; {{date('Y')}}</p>
            </div>
        </div>
    </div>
@endsection
