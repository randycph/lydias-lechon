@extends('auth.member.main')

@section('content')
    <p class="tx-white tx-16 mg-b-40">Welcome to {{ \App\Setting::getWebsiteName() }} Entrepreneur Portal. Please sign in to continue.</p>
    <form method="POST" action="{{ route('member.verify-login') }}">
        @csrf

        @if($message = Session::get('error'))
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
            </div>
        @endif

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <input required type="text" id="email" name="email" class="form-control" placeholder="Enter username or email address *" value="{{ old('email') }}">
        </div>

        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <input required type="password" id="password" name="password" class="form-control" placeholder="Enter password *">
        </div>
        <button type="submit" class="btn btn-white font-weight-bold btn-sm">Sign In</button>
        <a href="{{route('password.request')}}" class="btn btn-link text-white btn-sm">Forgot Password?</a>
    </form>
@endsection
