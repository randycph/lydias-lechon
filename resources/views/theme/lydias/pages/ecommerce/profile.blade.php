@extends('theme.'.config('app.frontend_template').'.main')

@section('pagecss')
@endsection

@section('content')
    <main>
        <section id="cart-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cart-title">
                            <h2>Profile</h2>
                        </div>
                        <div class="summary-wrap">
                            @if($message = Session::get('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ $message }}
                                </div>
                            @endif
                            <form action="{{ route('ecommerce.profile.update_name') }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="d-block">First Name</label>
                                    <input type="text" name="firstname" value="{{ old('firstname', auth()->user()->firstname)}}" class="form-control @error('fname') is-invalid @enderror" required>
                                    <x-error-message inputName="firstname" />
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Last Name</label>
                                    <input type="text" name="lastname" value="{{ old('lastname', auth()->user()->lastname)}}" class="form-control @error('lastname') is-invalid @enderror" required>
                                    <x-error-message inputName="lastname" />
                                </div>
                                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update</button>
                            </form>
                            <hr/>
                            <form action="{{ route('ecommerce.profile.update_contact') }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="d-block">Mobile No.</label>
                                    <input type="text" name="mobile" value="{{ old('mobile', auth()->user()->profile->mobile)}}" class="form-control @error('mobile') is-invalid @enderror" required>
                                    <x-error-message inputName="mobile" />
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Telephone No.</label>
                                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->profile->phone)}}" class="form-control @error('phone') is-invalid @enderror" required>
                                    <x-error-message inputName="phone" />
                                </div>
                                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update</button>
                            </form>
                            <hr/>
                            <form action="{{ route('ecommerce.profile.update_address') }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="d-block">Street</label>
                                    <input type="text" name="address_street" value="{{ old('address_street', auth()->user()->profile->address_street)}}" class="form-control @error('address_street') is-invalid @enderror" required>
                                    <x-error-message inputName="address_street" />
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Barangay</label>
                                    <input type="text" name="address_brgy" value="{{ old('address_brgy', auth()->user()->profile->address_brgy)}}" class="form-control @error('address_brgy') is-invalid @enderror" required>
                                    <x-error-message inputName="address_brgy" />
                                </div>
                                <div class="form-group">
                                    <label class="d-block">City/Municipality</label>
                                    <input type="text" name="address_city" value="{{ old('address_city', auth()->user()->profile->address_city)}}" class="form-control @error('address_city') is-invalid @enderror" required>
                                    <x-error-message inputName="address_city" />
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Province</label>
                                    <input type="text" name="address_province" value="{{ old('address_province', auth()->user()->profile->address_province)}}" class="form-control @error('address_province') is-invalid @enderror" required>
                                    <x-error-message inputName="address_province" />
                                </div>
                                <div class="form-group">
                                    <label class="d-block">ZIP/Postal Code</label>
                                    <input type="text" name="address_zip" value="{{ old('address_zip', auth()->user()->profile->address_zip)}}" class="form-control @error('address_zip') is-invalid @enderror" required>
                                    <x-error-message inputName="address_zip" />
                                </div>
                                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update</button>
                            </form>
                            <hr/>
                            <form action="{{ route('ecommerce.profile.update_delivery_address') }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="d-block">Delivery Street</label>
                                    <input type="text" name="address_delivery_street" value="{{ old('address_delivery_street', auth()->user()->profile->address_delivery_street)}}" class="form-control @error('address_delivery_street') is-invalid @enderror" required>
                                    <x-error-message inputName="address_delivery_street" />
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Delivery Barangay</label>
                                    <input type="text" name="address_delivery_brgy" value="{{ old('address_delivery_brgy', auth()->user()->profile->address_delivery_brgy)}}" class="form-control @error('address_delivery_brgy') is-invalid @enderror" required>
                                    <x-error-message inputName="address_delivery_brgy" />
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Delivery City/Municipality</label>
                                    <input type="text" name="address_delivery_city" value="{{ old('address_delivery_city', auth()->user()->profile->address_delivery_city)}}" class="form-control @error('address_delivery_city') is-invalid @enderror" required>
                                    <x-error-message inputName="address_delivery_city" />
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Delivery Province</label>
                                    <input type="text" name="address_delivery_province" value="{{ old('address_delivery_province', auth()->user()->profile->address_delivery_province)}}" class="form-control @error('address_delivery_province') is-invalid @enderror" required>
                                    <x-error-message inputName="address_delivery_province" />
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Delivery ZIP/Postal Code</label>
                                    <input type="text" name="address_delivery_zip" value="{{ old('address_delivery_zip', auth()->user()->profile->address_delivery_zip)}}" class="form-control @error('address_delivery_zip') is-invalid @enderror" required>
                                    <x-error-message inputName="address_delivery_zip" />
                                </div>
                                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update</button>
                            </form>
                            <hr/>
                            <form action="{{ route('ecommerce.profile.update_email') }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="d-block">Email</label>
                                    <input type="text" name="email" value="{{ old('email', auth()->user()->email)}}" class="form-control @error('email') is-invalid @enderror" required>
                                    <x-error-message inputName="email" />
                                </div>
                                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update</button>
                            </form>
                            <hr/>
                            <form action="{{ route('ecommerce.profile.update_password') }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="d-block">Old Password</label>
                                    <input type="password" name="old_password" value="" class="form-control @error('old_password') is-invalid @enderror" required>
                                    <x-error-message inputName="old_password" />
                                    <label class="d-block">New Password</label>
                                    <input type="password" name="password" value="" class="form-control @error('password') is-invalid @enderror" required>
                                    <x-error-message inputName="password" />
                                    <label class="d-block">Confirm Password</label>
                                    <input type="password" name="password_confirmation" value="" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                    <x-error-message inputName="password_confirmation" />
                                </div>
                                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('jsscript')
@endsection
