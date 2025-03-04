@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
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
                            
                            <a href="{{ route('my-account.manage-account')}}" class="list-group-item list-group-item-action active">Manage Account</a>
                            <a href="{{ route('my-account.update-password') }}" class="list-group-item list-group-item-action ">Change Password</a>
                            <a href="{{ route('profile.sales') }}" class="list-group-item list-group-item-action ">Sales Transaction</a>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="gap-40"></div>
                      
                        <div id="res-tabs-account">

                            <nav>
                                <div class="nav nav-tabs account-tabs" id="nav-tab" role="tablist">
                                    <a href="#tab-1" class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" role="tab" aria-controls="nav-home" aria-selected="true">Personal Information</a>
                                    <a href="#tab-2" class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" role="tab" aria-controls="nav-profile" aria-selected="false">Contact Information</a>
                                    <a href="#tab-3" class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" role="tab" aria-controls="nav-contact" aria-selected="false">Delivery Address</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active login-forms" id="tab-1" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <h2>Personal Information</h2>
                                    <hr>
                                    <div class="gap-10"></div>
                                    <div class="form-style-alt">
                                        @if (Session::has('success-personal'))
                                            <div class="alert alert-success" role="alert"><span class="fa fa-info-circle"></span>{{ Session::get('success-personal') }}</div>
                                        @endif
                                        <form method="post" class="row" action="{{ route('my-account.update-personal-info') }}">
                                            @csrf
                                            <input type="hidden" name="is_org" value="{{$member->is_org}}">
                                            @if($member->is_org==0)
                                                <div class="col-lg-6">
                                                    <label>First Name *</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ old('firstname', $member->firstname) }}">
                                                        @hasError(['inputName' => 'firstname'])
                                                        @endhasError
                                                    </div>
                                                    <div class="gap-20"></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label>Last Name *</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname', $member->lastname) }}">
                                                        @hasError(['inputName' => 'lastname'])
                                                        @endhasError
                                                    </div>
                                                    <div class="gap-20"></div>
                                                </div>
                                            @else
                                                <div class="col-lg-6">
                                                    <label>Organization Name</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control @error('organization') is-invalid @enderror" id="organization" name="organization" value="{{ old('organization', $member->organization) }}">
                                                        @hasError(['inputName' => 'organization'])
                                                        @endhasError
                                                    </div>
                                                    <div class="gap-20"></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label>Contact Person</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person', $member->contact_person) }}">
                                                        @hasError(['inputName' => 'contact_person'])
                                                        @endhasError
                                                    </div>
                                                    <div class="gap-20"></div>
                                                </div>
                                            @endif
                                            <div class="col-lg-6">
                                                <label>Birthday *</label>
                                                <div class="form-group">
                                                    <input type="date" class="form-control @error('birthday') is-invalid @enderror" id="birthday" name="birthday" value="{{ old('birthday', $member->birthday) }}">
                                                    @hasError(['inputName' => 'birthday'])
                                                    @endhasError
                                                </div>
                                                <div class="gap-20"></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <button type="submit" class="btn btn-md btn-success">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade login-forms" id="tab-2" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    <h2>Contact Information</h2>
                                    <hr>
                                    <div class="gap-10"></div>
                                    <div class="form-style-alt">
                                        @if (Session::has('success-contact'))
                                            <div class="alert alert-success" role="alert"><span class="fa fa-info-circle"></span>{{ Session::get('success-contact') }}</div>
                                        @endif
                                        <form method="post" class="row" action="{{ route('my-account.update-contact-info') }}">
                                            @csrf
                                           
                                            <div class="col-lg-6">
                                                <label>Mobile Number *</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $member->contact_mobile) }}">
                                                    @hasError(['inputName' => 'mobile'])
                                                    @endhasError
                                                </div>
                                                <div class="gap-20"></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Telephone Number </label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control @error('tel') is-invalid @enderror" id="tel" name="tel" value="{{ old('tel', $member->contact_tel) }}">
                                                    @hasError(['inputName' => 'tel'])
                                                    @endhasError
                                                </div>
                                                <div class="gap-20"></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Fax </label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control @error('fax') is-invalid @enderror" id="fax" name="fax" value="{{ old('fax', $member->contact_fax) }}">
                                                    @hasError(['inputName' => 'fax'])
                                                    @endhasError
                                                </div>
                                                <div class="gap-20"></div>
                                            </div>
                                            <div class="col-lg-12">
                                                <button type="submit" class="btn btn-md btn-success">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade login-forms" id="tab-3" role="tabpanel" aria-labelledby="nav-contact-tab">
                                    <form method="post" action="{{ route('my-account.update-address-info') }}">
                                        @csrf
                                        @if (Session::has('success-address'))
                                            <div class="alert alert-success" role="alert"><span class="fa fa-info-circle">Personal information has been updated</span>{{ Session::get('success-contact') }}</div>
                                        @endif
                                       
                                        <h4>Delivery Address</h4>
                                       
                                        <div class="gap-20"></div>
                                        <div class="address-card">
                                            <div class="form-style-alt">                                               
                                                
                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <label>Unit No./Building/House No./Street *</label>
                                                    <input type="text" class="form-control @error('address_delivery_street') is-invalid @enderror" id="delivery_street" name="address_delivery_street" value="{{ old('address_delivery_street', $member->address_street) }}"/>
                                                    @hasError(['inputName' => 'address_delivery_street'])
                                                    @endhasError
                                                </div>
                                               
                                                <div class="gap-10"></div>
                                                <div class="form-group form-wrap">
                                                    <label>City/Municipality *</label>
                                                    <input type="text" class="form-control @error('address_delivery_city') is-invalid @enderror" id="delivery_zip" name="address_delivery_city" value="{{ old('address_delivery_city', $member->address_city) }}"/>      

                                                    @hasError(['inputName' => 'address_delivery_city'])
                                                    @endhasError
                                                </div>       
                                                <div class="gap-10"></div>                                        
                                                 <div class="form-group form-wrap">
                                                    <label>Region *</label>
                                                    <input type="text" class="form-control @error('address_delivery_province') is-invalid @enderror" id="delivery_zip" name="address_delivery_province" value="{{ old('address_delivery_province', $member->address_region) }}"/>                                                  
                                                    @hasError(['inputName' => 'address_delivery_province'])
                                                    @endhasError
                                                </div>
                                                
                                                <div class="gap-10"></div>
                                               
                                            </div>
                                        </div>
                                        <div class="gap-10"></div>
                                        <button type="submit" class="btn btn-md btn-success">Save</button>
                                    </form>
                                </div>
                            </div>
                            <div class="gap-20"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@section('jsscript')
    <script src="{{ asset('theme/lydias/plugins/responsive-tabs/js/jquery.responsiveTabs.js') }}"></script>
    <script>
        $("#res-tabs-account").responsiveTabs({
            startCollapsed: "accordion",
            active: {{$selectedTab}},
            scrollToAccordion: true
        });

        function compare( a, b ) {
            if ( a.provDesc < b.provDesc ){
                return -1;
            }
            if ( a.provDesc > b.provDesc ){
                return 1;
            }
            return 0;
        }

        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function(txt){
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        $(document).ready(function () {
            $('#tab-3').submit(function(e) {
                $('#delivery_country option:selected').attr('value', $('#delivery_country option:selected').text());
                $('#delivery_province option:selected').attr('value', $('#delivery_province option:selected').text());
                $('#delivery_municipality option:selected').attr('value', $('#delivery_municipality option:selected').text());
                $('#delivery_barangay option:selected').attr('value', $('#delivery_barangay option:selected').text());

                $('#country option:selected').attr('value', $('#country option:selected').text());
                $('#province option:selected').attr('value', $('#province option:selected').text());
                $('#municipality option:selected').attr('value', $('#municipality option:selected').text());
                $('#barangay option:selected').attr('value', $('#barangay option:selected').text());
            });

            $('#sameAddress').click(function() {
                $('#delivery_country').html($('#country').html());
                $('#delivery_province').html($('#province').html());
                $('#delivery_municipality').html($('#municipality').html());
                $('#delivery_barangay').html($('#barangay').html());

                $('#delivery_country').val($('#country').val());
                $('#delivery_province').val($('#province').val());
                $('#delivery_municipality').val($('#municipality').val());
                $('#delivery_barangay').val($('#barangay').val());
                $('#delivery_street').val($('#street').val());
                $('#delivery_zip').val($('#zip').val());
            });
        });

        // Address
        $.ajax({
            type: 'GET',
            url: '{{asset('data/countriesWithCode.json')}}',
            data: { get_param: 'value' },
            dataType: 'json',
            success: function (data) {

                $.each(data, function(key, val) {
                    let selected = (val.code == 'PH' || val.name == '{{ old('address_country', $member->address_country) }}') ? 'selected' : 'disabled';
                    $('#country').append(`<option value="${val.code}" ${selected}>${val.name}</option>`);
                });
                $('#country').change();
            }
        });

        $('#country').change(function() {
            $('#province').find('option').not(':first').remove();
            $('#municipality').find('option').not(':first').remove();
            $('#barangay').find('option').not(':first').remove();
            if ($('#country').val() == 'PH') {
                $.ajax({
                    type: 'GET',
                    url: '{{asset('data/philippines/json/refprovince.json')}}',
                    data: {get_param: 'value'},
                    dataType: 'json',
                    success: function (data) {
                        data.RECORDS.sort(compare);
                        $.each(data.RECORDS, function (key, val) {
                            let province = toTitleCase(val.provDesc.toLowerCase());
                            let selected = (province == '{{ old('address_province', $member->address_province) }}') ? 'selected' : '';
                            $('#province').append(`<option value="${val.provCode}" data-name=" " ${selected}>`+province+`</option>`);
                            if (selected == 'selected') {
                                $('#province').change();
                            }
                        });
                    }
                });
            }
        });

        $('#province').change(function() {
            $('#municipality').find('option').not(':first').remove();
            $('#barangay').find('option').not(':first').remove();

            $.ajax({
                type: 'GET',
                url: '{{asset('data/philippines/json/refcitymun.json')}}',
                data: { get_param: 'value' },
                dataType: 'json',
                success: function (data) {

                    $.each(data, function(index, element) {

                        $.each(element, function(key, val) {
                            let city = toTitleCase(val.citymunDesc.toLowerCase());
                            let selected = (city == '{{ old('address_city', $member->address_city) }}') ? 'selected' : '';
                            if(val.provCode == $('#province').val())
                                $('#municipality').append(`<option value="${val.citymunCode}" ${selected}>`+city+`</option>`);

                            if (selected == 'selected') {
                                $('#municipality').change();
                            }
                        });
                    });
                }
            });
        });

        $('#municipality').change(function() {
            $('#barangay').find('option').not(':first').remove();
            $.ajax({
                type: 'GET',
                url: '{{asset('data/philippines/json/refbrgy.json')}}',
                data: { get_param: 'value' },
                dataType: 'json',
                success: function (data) {

                    $.each(data, function(index, element) {

                        $.each(element, function(key, val) {
                            let barangay = toTitleCase(val.brgyDesc.toLowerCase());
                            let selected = (barangay == '{{ old('address_barangay', $member->address_barangay) }}') ? 'selected' : '';
                            if(val.citymunCode == $('#municipality').val())
                                $('#barangay').append(`<option value="${val.brgyCode}" ${selected}>`+barangay+`</option>`);

                        });
                    });
                }
            });
        });
        // End Address



        // Delivery Address
        $.ajax({
            type: 'GET',
            url: '{{asset('data/countriesWithCode.json')}}',
            data: { get_param: 'value' },
            dataType: 'json',
            success: function (data) {

                $.each(data, function(key, val) {
                    let selected = (val.code == 'PH' || val.name == '{{ old('address_delivery_country', $member->address_delivery_country) }}') ? 'selected' : 'disabled';
                    $('#delivery_country').append(`<option value="${val.code}" ${selected}>${val.name}</option>`);
                });

                $('#delivery_country').change();
            }
        });

        $('#delivery_country').change(function() {
            $('#delivery_province').find('option').not(':first').remove();
            $('#delivery_municipality').find('option').not(':first').remove();
            $('#delivery_barangay').find('option').not(':first').remove();
            if ($('#delivery_country').val() == 'PH') {
                $.ajax({
                    type: 'GET',
                    url: '{{asset('data/philippines/json/refprovince.json')}}',
                    data: {get_param: 'value'},
                    dataType: 'json',
                    success: function (data) {

                        data.RECORDS.sort(compare);
                        $.each(data.RECORDS, function (key, val) {
                            let province = toTitleCase(val.provDesc.toLowerCase());
                            let selected = (province == '{{ old('address_delivery_province', $member->address_delivery_province) }}') ? 'selected' : '';
                            $('#delivery_province').append(`<option value="${val.provCode}" ${selected}>`+province+`</option>`);
                            if (selected == 'selected') {
                                $('#delivery_province').change();
                            }
                        });
                    }
                });
            }
        });

        $('#delivery_province').change(function() {
            $('#delivery_municipality').find('option').not(':first').remove();
            $('#delivery_barangay').find('option').not(':first').remove();

            $.ajax({
                type: 'GET',
                url: '{{asset('data/philippines/json/refcitymun.json')}}',
                data: { get_param: 'value' },
                dataType: 'json',
                success: function (data) {
                    $.each(data.RECORDS, function(key, val) {
                        let city = toTitleCase(val.citymunDesc.toLowerCase());
                        let selected = (city == '{{ old('address_delivery_city', $member->address_delivery_city) }}') ? 'selected' : '';
                        if(val.provCode == $('#delivery_province').val())
                            $('#delivery_municipality').append(`<option value="${val.citymunCode}" ${selected}>`+city+`</option>`);

                        if (selected == 'selected') {
                            $('#delivery_municipality').change();
                        }
                    });
                }
            });
        });

        $('#delivery_municipality').change(function() {
            $('#delivery_barangay').find('option').not(':first').remove();
            $.ajax({
                type: 'GET',
                url: '{{asset('data/philippines/json/refbrgy.json')}}',
                data: { get_param: 'value' },
                dataType: 'json',
                success: function (data) {

                    $.each(data, function(index, element) {

                        $.each(element, function(key, val) {
                            let barangay = toTitleCase(val.brgyDesc.toLowerCase());
                            let selected = (barangay == '{{ old('address_delivery_barangay', $member->address_delivery_barangay) }}') ? 'selected' : '';
                            if(val.citymunCode == $('#delivery_municipality').val())
                                $('#delivery_barangay').append(`<option value="${val.brgyCode}" ${selected}>`+barangay+`</option>`);

                        });
                    });
                }
            });
        });
    </script>
@endsection
