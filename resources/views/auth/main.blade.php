<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="">
    <meta name="author">

    <title>Admin Panel | {{ Setting::info()->company_name }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage').'/icons/'.Setting::getFaviconLogo()->website_favicon }}">

    <!-- vendor css -->
    <link href="{{ asset('lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">

    <!-- DashForge CSS -->
    <link href="{{ asset('css/dashforge.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashforge.auth.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-admin.css') }}" rel="stylesheet">
</head>

<body id="scroll1">

<div class="content-auth">
    <div class="row no-gutters">
        <div class="col-lg-6">
            <div class="signin-hero"></div>
        </div>
        <div class="col-lg-6">
            <div class="sign-wrapper">
                @yield('content')
            </div><!-- sign-wrapper -->
        </div>
    </div>
</div><!-- content -->

<div class="pos-fixed b-10 r-10">
    <div id="toast_success" class="toast bg-success bd-0 wd-350" data-delay="3000" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success">
            <h6 class="tx-white tx-14 mg-b-0 mg-r-auto tx-semibold"><i data-feather="check-circle" width="14"></i> Success</h6>
            <button type="button" class="ml-2 mb-1 close tx-normal" data-dismiss="toast" aria-label="Close">
                <i data-feather="x" aria-hidden="true" class="tx-white wd-15"></i>
            </button>
        </div>
        <div class="toast-body bg-success tx-white">
            {{ Session::get('success') }}
        </div>
    </div>
</div>

<script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('lib/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

<script src="{{ asset('js/dashforge.js') }}"></script>

@if(Session::has('success'))
    <script>
        $('#toast_success').toast('show');
    </script>
@endif

<script>
    $('#customCheck').change(function(){
        $("#branch").val('').trigger('change');
        
        if(this.checked) {
            $("#branch option[value='web']").hide();
            $("#branch option[value='admin']").hide();
            $("#branch option[value='forecaster']").hide();
        } else {
            $("#branch option[value='web']").show();
            $("#branch option[value='admin']").show();
            $("#branch option[value='forecaster']").show();
        }
    });
</script>

</body>

</html>
