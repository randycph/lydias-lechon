@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/jssocials/jssocials-theme-flat.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/jssocials/jssocials.css') }}" />
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
						<h2>Contact Us</h2>
						<p>Use the form below to get in touch with the sales team.</p>
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
								<div class="form-group has-feedback 1">
									<script src="https://www.google.com/recaptcha/api.js?hl=en" async="" defer="" ></script>
									<div class="g-recaptcha" data-sitekey="{{ \Setting::info()->google_recaptcha_sitekey }}"></div>
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
						<div class="row">
							<h2>&nbsp;</h2>
							<div class="col-md-12 text-center">
								<div class="contact-info">
									<i class="fa fa-hashtag fa-2x"></i>
									<p>
										<strong>Follow Us</strong><br />For more updates, follow
										us on our social media accounts.
									</p>
									<ul>
										<li><a href="https://www.facebook.com/lydiaslechonrestaurant" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
										<li><a href="https://instagram.com/lydiaslechon" target="_blank"><i class="fab fa-instagram"></i></a></li>
										<li><a href="https://twitter.com/lydiaslechon" target="_blank"><i class="fab fa-twitter"></i></a></li>
									</ul>
								</div>
							</div>
							<div class="gap-70"></div>
							<div class="col-md-6 text-center">
								<div class="contact-info">
									<i class="fa fa-map-marker-alt fa-2x"></i>
									<p>
										<strong>Office Location</strong><br />
										{{Setting::info()->company_address}}
									</p>				
								</div>
							</div>
							<div class="col-md-6 text-center">
								<div class="contact-info">
									<i class="fa fa-phone fa-2x"></i>
									<p>	
										<strong>Telephone</strong>		
										
										{!!"<br />".Setting::info()->tel_no!!}
										{!!"<br />".Setting::info()->fax_no!!}
										{!!"<br />".Setting::info()->mobile_no!!}
																										
									</p>
								</div>
							</div>
							
						</div>
						
						
					</div>
				</div>
			</div>
			<div class="gap-70"></div>
		</div>
	</section>
@endsection

@section('jsscript')
    <script src="{{ asset('theme/lydias/plugins/jssocials/jssocials.js') }}"></script>

@endsection
