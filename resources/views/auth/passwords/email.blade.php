@extends('auth.main')

@section('content')
    <div class="wd-100p">
        <h3 class="mg-b-3">{{ __('Forgot Password') }}</h3>
        <p class="tx-color-03 tx-14 mg-b-40">{{ __('passwords.auth_title') }}</p>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email address *" value="{{ old('email') }}"  required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary" style="margin-right: 10px;">
                    {{ __('Reset Password') }}
                </button>
                <a href="{{route('login')}}" class="btn btn-outline-secondary btn-uppercase">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
