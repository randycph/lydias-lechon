@php
    if (!isset($page)) {
        $page = new \App\Page;
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(!empty($page->name) && $page->name == 'Home')
        <title>{{ Setting::info()->company_name }}</title>
    @else
        @if (!empty($page->name) || !empty($page->meta_title))
            <title>{{ (empty($page->meta_title) ? $page->name:$page->meta_title) }} | {{ Setting::info()->company_name }}</title>
        @else
             <title>{{ Setting::info()->company_name }}</title>
        @endif
    @endif
    <meta name="description" content="{{ $page->meta_description }}">
    <meta name="keywords" content="{{ $page->meta_keyword }}">

    <link rel="shortcut icon" href="{{ Setting::get_company_favicon_storage_path() }}" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,700" rel="stylesheet">

    <script  src="https://code.jquery.com/jquery-2.2.4.js"  integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="  crossorigin="anonymous"></script>
    <link href="{{ asset('theme/lydias/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('theme/lydias/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <script src="https://unpkg.com/feather-icons"></script>
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/lydias/plugins/aos/dist/aos.css') }}" />
    <link href="{{ asset('theme/lydias/css/animate.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('theme/lydias/css/hover.css') }}" rel="stylesheet" type="text/css" media="screen" />
    
    <script src="{{ asset('theme/lydias/js/modernizr.custom.js') }}"></script>
    <script src="{{ asset('theme/lydias/js/snap.svg-min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/owl.carousel/assets/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/owl.carousel/assets/owl.theme.default.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/lydias/css/style.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/lydias/css/responsive.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/lydias/plugins/mmenu/css/jquery.mmenu.all.css') }}" />
    
    <style>
        .footer-wrapper {
            background: #004e1f;
            padding: 1rem 0 1rem 0;
        }
    </style>

    <script type="text/javascript" src="{{ asset('theme/lydias/plugins/mmenu/js/jquery.mmenu.all.min.js') }}"></script>
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f082f6581808f00125031c5&product=inline-share-buttons&cms=website' async='async'></script>
    <script type="text/javascript">
        $(window).on('orientationchange resize load', function() {
            ww = $(window).width();
            if (ww < 1024) {

                $("#menu-desk").mmenu({
                    "extensions": [
                        "effect-menu-zoom",
                        "pagedim-black"
                    ],
                    "offCanvas": {
                        "position": "right"
                    }
                });
            }
        });
    </script>

    {!! \Setting::info()->google_analytics !!}

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    @yield('pagecss')
</head>

<body>

    <div class="page">

        @include('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.kiosk.header')

        @yield('content')

        <!-- START FOOTER -->
        <div class="footer-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12" style="padding-top:20px;">
                        <div class="copyright" style="margin-top: 20px;">
                            <p>All information, pictures and images on this site are copyrighted material and owned by their respective creators or owners.<br />Copyright &copy; 2021 | Lydia&rsquo;s Lechon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="devs">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p style="font-size: 13px;">Powered by <a style="text-decoration: underline !important;" href="http://www.webfocus.ph" target="_blank">WebFocus Solutions, Inc.</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- END FOOTER -->
    </div>



@if(!empty($page->album) && $page->album->id != 0)
    <script type="text/javascript">
        let bannerFxIn = "{{ $page->album->animationIn->value }}";
        let bannerFxOut = "{{ $page->album->animationOut->value }}";
        let bannerCaptionFxIn = "fadeInUp";
        let autoPlayTimeout = "{{ $page->album->transition }}000";
        let bannerID = "banner";
    </script>
@else
    <script type="text/javascript">
        let bannerFxIn = "fadeIn";
        let bannerFxOut = "fadeOut";
        let bannerCaptionFxIn = "fadeInUp";
        let autoPlayTimeout = 4000;
        let bannerID = "banner";
    </script>
@endif
    <div id="top">
        <img src="{{ asset('theme/lydias/images/misc/top.png') }}" />
    </div>

    <script type="text/javascript" src="{{ asset('theme/lydias/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('theme/lydias/js/script.js') }}"></script>
    <script src="{{ asset('theme/lydias/js/wow.min.js') }}"></script>
    <script src="{{ asset('theme/lydias/js/custom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('theme/lydias/js/jquery.nicescroll.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('theme/lydias/plugins/owl.carousel/owl.carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('theme/lydias/plugins/owl.carousel/owl.carousel.extension.js') }}"></script>
    <script type="text/javascript" src="{{ asset('theme/lydias/plugins/aos/dist/aos.js') }}"></script>
    <script type="text/javascript" src="{{ asset('theme/lydias/js/dynamic-pills.js') }}"></script>



    <script>
        AOS.init();
    </script>
    <script>
        feather.replace()
    </script>

@yield('jsscript')


</body>
</html>
