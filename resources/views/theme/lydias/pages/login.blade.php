@extends('theme.'.config('app.frontend_template').'.main')

@section('pagecss')

@endsection

@section('content')
    <main>
        <section id="login-wrapper">
            <div class="container">
                <div class="login-wrap">
                    <div class="row">
                        <div class="col-lg-8 col-md-12">
                            <h2 class="login-title">Welcome to Lydia's Lechon! <span class="white-spc">Please Login.</span></h2>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <p class="new-account">New to Lydia's Lechon? <a href="#">Sign Up</a> here.</p>
                            <div class="gap-20"></div>
                        </div>
                    </div>

{{--                    <form method="POST" action="{{ url('/cms/checklogin') }}">--}}
{{--                        @csrf--}}
{{--                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">--}}
{{--                            <label for="email"><i class="tx-danger">*</i> Email</label>--}}
{{--                            <input required type="text" id="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}">--}}
{{--                            <span class="text-danger">{{ $errors->first('email') }}</span>--}}
{{--                        </div>--}}

{{--                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">--}}
{{--                            <label for="password"><i class="tx-danger">*</i> Password</label>--}}
{{--                            <input required type="password" id="password" name="password" class="form-control" placeholder="********" >--}}
{{--                            <span class="text-danger">{{ $errors->first('password') }}</span>--}}
{{--                        </div>--}}
{{--                        <button type="submit" class="btn btn-primary btn-sm">Sign In</button>--}}
{{--                        <a href="{{route('password.request')}}" class="btn btn-info btn-sm">Forgot Password</a>--}}
{{--                    </form>--}}

                    <div class="login-form-wrap">
                        <form method="POST" action="{{ route('ecommerce.checkLogin') }}">
                            @csrf
                            @method('POST')
                            <div class="form-style-alt">
                                <div class="row">
                                    <div class="col-md-7">
                                        <p>Phone Number or Email *</p>
                                        <div class="form-wrap">
{{--                                            <input type="text" class="form-input" id="email" name="email">--}}
                                            <input required type="text" id="email" name="email" class="form-input" value="{{ old('email') }}">
                                            <label class="form-label" for="email">Please enter your Phone Number or Email</label>
                                        </div>
                                        <div class="gap-20"></div>
                                        <p>Password *</p>
                                        <div class="form-wrap">
{{--                                            <input type="password" class="form-input" id="password" name="password">--}}
                                            <input required type="password" id="password" name="password" class="form-input">
                                            <label class="form-label" for="password">Please enter your Password</label>
                                        </div>
                                        <div class="gap-20"></div>
                                        <p class="text-right"><a href="#">Forgot Password?</a></p>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div class="gap-30"></div>
                                        <button type="login" class="btn btn-md primary-btn btn-block">Login</button>
                                        <div class="gap-10"></div>
                                        <p class="txt-alt">Or, login with</p>
                                        <div class="gap-10"></div>
                                        <button type="" class="btn btn-primary btn-block fb"><span class="fab fa-facebook-f pr-3"></span>Facebook</button>
                                        <button type="" class="btn btn-primary btn-block gl"><span class="fab fa-google-plus-g pr-3"></span>Google</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('jsscript')
@endsection
