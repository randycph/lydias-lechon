@extends('auth.member.main')

@section('content')
    <div class="wd-100p">
        <h3 class="mg-b-3">{{ __('Reset Password') }}</h3>
        <p class="tx-color-03 tx-14 mg-b-40">{{ __('passwords.reset_title') }}</p>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}"  placeholder="Enter email address *" required autocomplete="email" autofocus  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" readonly>

                @error('email')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter new passowrd *">

                @error('password')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Enter confirm password *">

                @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-white font-weight-bold btn-sm">
                {{ __('Reset Password') }}
            </button>
            <a href="{{route('login')}}" class="btn btn-link text-white btn-sm">
                Cancel
            </a>
        </form>
    </div>
@endsection
