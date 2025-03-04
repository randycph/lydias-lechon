@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <style>
        .contactfrm {
          border-right: 1px dashed #EDF0ED;
        }
    </style>
@endsection

@section('content')
<section>
    <div class="content-wrapper contact-details">
        <div class="gap-70"></div>
        <div class="container">
            <div class="row">

                <div class="col-md-6 contactfrm">
                    <h4>Have some questions?</h4>
                    <p>For more inquiries, please send us a message by filling in the form below and we’ll get back to you.</p>
                    <form id="contactUsForm" method="POST" action="{{ route('contact-us') }}">
                        <div class="form-style-alt">
                            @method('POST')
                            @csrf
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                            <div class="form-group has-feedback 1">
                                <input type="text" id="fullName" class="form-control" name="name" placeholder="First and Last Name"  required />
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 has-feedback 1">
                                    <input type="email" id="emailAddress" class="form-control" placeholder="hello@email.com" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" data-content="Please ensure the e-mail address is accessible and up-to-date" required />
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                                <div class="form-group col-md-6 has-feedback 1">
                                    <input type="text" id="contactNumber" class="form-control" placeholder="Landline or Mobile"  name="contact" data-content="Please ensure the contact number is accessible and up-to-date" required />
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                            </div>
                            <div class="form-group has-feedback 1">
                                <textarea name="message" id="message" class="form-control" rows="5" placeholder="Tell us what you thought about it" required></textarea>
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            </div>
                            <!-- 6Lfgj7cUAAAAAJfCgUcLg4pjlAOddrmRPt86tkQK -->
                            <div class="form-group has-feedback 1">
                                <script src="https://www.google.com/recaptcha/api.js?hl=en" async="" defer="" ></script>

                                <div class="g-recaptcha" data-sitekey="{{ Setting::info()->google_recaptcha_sitekey }}"></div>
                                <label class="control-label text-danger" for="g-recaptcha-response" id="catpchaError" style="display:none;"><i class="fa fa-times-circle-o"></i>The Captcha field is required.</label></br>
                                @if($errors->has('g-recaptcha-response'))
                                    @foreach($errors->get('g-recaptcha-response') as $message)
                                        <label class="control-label text-danger" for="g-recaptcha-response"><i class="fa fa-times-circle-o"></i>{{ $message }}</label></br>
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-md primary-btn">
                                    Submit</button>&nbsp;
                                <button type="reset" class="btn btn-md default-btn">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    {!! $page->contents !!}
                </div>
            </div>
        </div>
        <div class="gap-70"></div>
    </div>
</section>
@endsection
