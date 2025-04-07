@extends('theme.'.config('app.frontend_template').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/legande/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
@endsection

@section('content')
    <section>
        <div class="content-wrapper">
            <div class="gap-70"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        
                        <h3>My Account</h3>
                        <div class="list-group">
                            
                            <a href="{{ route('my-account.manage-account')}}" class="list-group-item list-group-item-action">Manage Account</a>
                            <a href="{{ route('my-account.update-password') }}" class="list-group-item list-group-item-action active">Change Password</a>
                            <a href="{{ route('profile.sales') }}" class="list-group-item list-group-item-action ">Sales Transaction</a>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <h2>Change Password</h2>
                        <form class="form message-form" role="form" autocomplete="off" action="{{ route('my-account.update-password') }}" method="post">
                            @csrf
                            @if (Session::has('success'))
                                <div class="alert alert-success" role="alert"><span class="fa fa-info-circle"></span>{{ Session::get('success') }}</div>
                            @endif
                            <div class="form-group">
                                <label for="inputPasswordOld">Current Password</label>
                                <input type="password" class="form-control col-md-6 @error('current_password') is-invalid @enderror" name="current_password" required id="inputPasswordOld">
                                <x-error-message inputName="current_password" />
                            </div>
                            <div class="form-group">
                                <label for="inputPasswordNew">New Password</label>
                                <input type="password" class="form-control col-md-6 @error('password') is-invalid @enderror" name="password" required id="inputPasswordNew">
                                <span class="form-text small text-muted">
									Minimum of 10 characters, at least one 1 upper case and 1 special character
								</span>
                                <x-error-message inputName="password" />

                            </div>
                            <div class="form-group">
                                <label for="inputPasswordNewVerify">Verify Password</label>
                                <input type="password" class="form-control col-md-6 @error('confirm_password') is-invalid @enderror" name="confirm_password" required id="inputPasswordNewVerify">
                                <span class="form-text small text-muted">
									To confirm, type the new password again.
								</span>
                                <x-error-message inputName="confirm_password" />

                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn primary-btn more2">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="gap-70"></div>
        </div>
    </section>

@endsection

@section('jsscript')

@endsection
