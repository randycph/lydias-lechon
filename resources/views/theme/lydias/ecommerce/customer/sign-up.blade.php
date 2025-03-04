@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/legande/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/legande/plugins/gijgo/css/gijgo.min.css') }}" />
    <style>
        .reg-btn{
            border-radius: 100px;
            border: 2px solid #f26522;
            background: #f26522;
            color: #fff;
        }
        .cancel-btn{
            border-radius: 100px;
            border: 2px solid #f26522;
            background: #fff;
            color: #f26522;
        }
    </style>
@endsection

@section('content')

    <div class="content-wrapper">
        <div class="gap-70"></div>
        <div class="container">
            <div class="account-wrapper">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <form autocomplete="off" action="{{ route('customer-front.customer-sign-up') }}" method="post" style="font-family:sans-serif;">
                            @csrf
                            @method('POST')
                            <div class="form-group">
                                <label class="d-block">Customer Type *</label>
                                <select name="is_org" id="is_org" class="form-control mg-b-5" required>
                                    <option value="0" selected>Individual</option>
                                    <option value="1">Organization</option>
                                </select>
                                @hasError(['inputName' => 'is_org'])
                                @endhasError
                            </div>
                            <div class="form-group org" style="display:block">
                                <label class="d-block">Organization Name *</label>
                                <input type="text" name="organization" id="organization" value="{{ old('organization')}}" class="form-control @error('organization') is-invalid @enderror">
                                @hasError(['inputName' => 'organization'])
                                @endhasError
                            </div>
                            <div class="individual" style="display:block">
                                <div class="form-group">
                                    <label class="d-block">First Name *</label>
                                    <input type="text" name="fname" id="fname" value="{{ old('fname')}}" class="form-control @error('fname') is-invalid @enderror">
                                    @hasError(['inputName' => 'fname'])
                                    @endhasError
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Last Name *</label>
                                    <input type="text" name="lname" id="lname" value="{{ old('lname')}}" class="form-control @error('lname') is-invalid @enderror">
                                    @hasError(['inputName' => 'lname'])
                                    @endhasError
                                </div>
                                <div class="form-group">
                                    <label class="d-block">Birth Date </label>
                                    <input type="date" name="birthday" id="birthday" value="{{ old('birthday')}}" class="form-control @error('birthday') is-invalid @enderror">
                                    @hasError(['inputName' => 'birthday'])
                                    @endhasError
                                </div>
                            </div>
                            <br>
                            <h4 class="mg-b-0 tx-spacing--1">Account</h4>
                            <div class="form-group">
                                <label class="d-block">Email *</label>
                                <input type="email" name="email" id="email" required="required" value="{{ old('email')}}" class="form-control @error('email') is-invalid @enderror" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                @hasError(['inputName' => 'email'])
                                @endhasError
                            </div>
                            <div class="form-group">
                                <label class="d-block">Password *</label>
                                <input type="password" name="password" id="password" required="required" value="{{ old('password')}}" class="form-control @error('password') is-invalid @enderror">
                                @hasError(['inputName' => 'password'])
                                @endhasError
                            </div>
                            <div class="form-group">
                                <label class="d-block">Confirm Password *</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required="required" value="{{ old('password_confirmation')}}" class="form-control @error('password_confirmation') is-invalid @enderror">
                                @hasError(['inputName' => 'password_confirmation'])
                                @endhasError
                            </div>
                            <br>
                            
                            <h4 class="mg-b-0 tx-spacing--1">Address Information</h4>
                            
                            <div class="form-group">
                                <label class="d-block">Street *</label>
                                <input type="text" name="address_street" id="address_street" value="{{ old('address_street')}}" class="form-control @error('address_street') is-invalid @enderror" required>
                                @hasError(['inputName' => 'address_street'])
                                @endhasError
                            </div>  
                            <div class="form-group">
                                <label class="d-block">Municipality *</label>
                                <input type="text" name="address_municipality" id="address_municipality" value="{{ old('address_municipality')}}" class="form-control @error('address_municipality') is-invalid @enderror" required>
                                @hasError(['inputName' => 'address_municipality'])
                                @endhasError
                            </div>  
                            <div class="form-group">
                                <label class="d-block">City *</label>
                                <input type="text" name="address_city" id="address_city" value="{{ old('address_city')}}" class="form-control @error('address_city') is-invalid @enderror" required>
                                @hasError(['inputName' => 'address_city'])
                                @endhasError
                            </div>  
                            <div class="form-group">
                                <label class="d-block">Region *</label>
                                <input type="text" name="address_region" id="address_region" value="{{ old('address_region')}}" class="form-control @error('address_region') is-invalid @enderror" required>
                                @hasError(['inputName' => 'address_region'])
                                @endhasError
                            </div>
                            <br>
                            <h4 class="mg-b-0 tx-spacing--1">Contact Information</h4>
                            <br>
                            <div class="form-group org" style="display:block">
                                <label class="d-block">Contact Person *</label>
                                <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person')}}" class="form-control @error('contact_person') is-invalid @enderror">
                                @hasError(['inputName' => 'contact_person'])
                                @endhasError
                            </div>                            
                            <div class="form-group">
                                <label class="d-block">Telephone Number</label>
                                <input type="text" name="contact_tel" id="contact_tel" value="{{ old('contact_tel')}}" class="form-control @error('contact_tel') is-invalid @enderror" maxlength="200">
                                @hasError(['inputName' => 'contact_tel'])
                                @endhasError
                            </div>  
                            <div class="form-group">
                                <label class="d-block">Mobile Number *</label>
                                <input type="text" name="contact_mobile" id="contact_mobile" value="{{ old('contact_mobile')}}" class="form-control @error('contact_mobile') is-invalid @enderror" required="required" maxlength="13">
                                @hasError(['inputName' => 'contact_mobile'])
                                @endhasError
                            </div>  
                            <div class="form-group">
                                <label class="d-block">Fax Number</label>
                                <input type="text" name="contact_fax" id="contact_fax" value="{{ old('contact_fax')}}" class="form-control @error('contact_fax') is-invalid @enderror" maxlength="200">
                                @hasError(['inputName' => 'contact_fax'])
                                @endhasError
                            </div>  
                            <div class="form-group">
                                <label class="d-block">Agent Code </label>
                                <input type="text" name="agent_code" id="agent_code" value="{{ old('agent_code')}}" class="form-control @error('agent_code') is-invalid @enderror">
                                @hasError(['inputName' => 'agent_code'])
                                @endhasError
                            </div>     
                            <div class="form-check" style="margin-bottom: 1rem;">
                                <input type="checkbox" class="form-check-input" id="issubscribe" name="issubscribe" value="1">
                                <label class="form-check-label" for="issubscribe">I want to receive exclusive offers and promotions.</label>
                            </div>        
                            <button class="btn btn-primary reg-btn" type="submit">Register</button>&nbsp;&nbsp;
                            <a class="btn btn-outline-secondary btn-uppercase cancel-btn" href="{{ route('customers.index') }}" style="margin-right:5px;">Cancel</a>&nbsp;&nbsp;
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="gap-70"></div>
    </div>

@endsection

@section('jsscript')
    <script src="{{ asset('theme/legande/plugins/responsive-tabs/js/jquery.responsiveTabs.min.js') }}"></script>
    <script src="{{ asset('theme/legande/plugins/gijgo/js/gijgo.min.js') }}"></script>
    <script src="{{ asset('theme/legande/plugins/jquery-steps/build/jquery.steps.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#fname').attr('required', true);
            $('#lname').attr('required', true); 
            $('#contact_person').attr('required', false);
            $('#organization').attr('required', false);
            $('.org').hide();        
            $('.individual').show();
            //called when key is pressed in textbox
            $("#contact_tel,#contact_mobile,#contact_fax").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                // if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //     return false;
                // }
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
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
