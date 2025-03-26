@php
    if (!isset($page)) {
        $page = new \App\Models\Page;
    }
    
    if(isset($_GET['origin'])){
        \App\Models\User::order_origin($_GET['origin']);
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
        <title>{{ \App\Helpers\Webfocus\Setting::info()->company_name }}</title>
    @else
        @if (!empty($page->name) || !empty($page->meta_title))
            <title>{{ (empty($page->meta_title) ? $page->name:$page->meta_title) }} | {{ \App\Helpers\Webfocus\Setting::info()->company_name }}</title>
        @else
             <title>{{ \App\Helpers\Webfocus\Setting::info()->company_name }}</title>
        @endif
    @endif
    <meta name="description" content="{{ $page->meta_description }}">
    <meta name="keywords" content="{{ $page->meta_keyword }}">

    <link rel="shortcut icon" href="{{ \App\Helpers\Webfocus\Setting::get_company_favicon_storage_path() }}" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,700" rel="stylesheet">
    {{-- <script  src="https://code.jquery.com/jquery-3.5.1.js"  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="  crossorigin="anonymous"></script> 
    <script  src="https://code.jquery.com/jquery-2.2.4.js"  integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="  crossorigin="anonymous"></script>--}}
    <script src="{{ asset('theme/lydias/js/jquery2.js') }}"></script>
    <link href="{{ asset('theme/lydias/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('theme/lydias/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <script src="https://unpkg.com/feather-icons"></script>
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/lydias/plugins/aos/dist/aos.css') }}" />
    <link href="{{ asset('theme/lydias/css/animate.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('theme/lydias/css/hover.css') }}" rel="stylesheet" type="text/css" media="screen" />
    
    
    {{--<script type="text/javascript" src="{{ asset('theme/plugins/bootstrap/js/html5shiv.js') }}"></script>--}}
    {{--<script type="text/javascript" src="{{ asset('theme/plugins/plugins/bootstrap/js/respond.min.js') }}"></script>--}}
    
    
    <script src="{{ asset('theme/lydias/js/modernizr.custom.js') }}"></script>
    <script src="{{ asset('theme/lydias/js/snap.svg-min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/owl.carousel/assets/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/owl.carousel/assets/owl.theme.default.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/lydias/css/style.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/lydias/css/responsive.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/lydias/plugins/mmenu/css/jquery.mmenu.all.css') }}" />
    
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

    {!! \App\Helpers\Webfocus\Setting::info()->google_analytics !!}

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    
    <script >
    !function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '546242270152998');
    fbq('track', 'PageView'); 
</script> 
<noscript ><img height = "1" width = "1" src = "https://www.facebook.com/tr?id=546242270152998&ev=PageView & noscript = 1 "/> </noscript>

    @yield('pagecss')
</head>

<body>

    <div class="page">

        @include('theme.'.config('app.frontend_template').'.layout.header')
        @include('theme.'.config('app.frontend_template').'.layout.banner')
{{--                <div id="alert_success" style="display:none;" class="alert alert-success" role="alert">--}}
{{--                    {{ Session::get('success') }}--}}
{{--                </div>--}}
{{--                <div id="alert_error" style="display:none;" class="alert alert-danger" role="alert">--}}
{{--                    {{ Session::get('error') }}--}}
{{--                </div>--}}

        @yield('content')

        @if($page->slug == "home")
        @php
            $count = 0;
            $productCategories = \App\Models\ProductCategory::where('status', 'PUBLISHED')->orderByRaw("FIELD(id, 1,13,18,12,2,17,19,20,21,4,5,6,8,3,22)")->get();
            $catcount = $productCategories->count();
        @endphp

{{--        --}}

            <div class="food-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="food-content">
                                <h4>Our Menu</h4>
                                <input type="hidden" value="{{$catcount}}" id="catcount" name="catcount">
                                <ul class="nav justify-content-center nav-pills mb-4" id="pills-tab" role="tablist">
                                    @forelse($productCategories as $category)
                                        @php
                                            $product = \App\Models\Product::where('status', 'PUBLISHED')->where('category_id', $category->id)->where('is_featured', 1)->get();
                                        @endphp
                                            @php
                                                $count++;
                                            @endphp
                                        @if(!empty($product))
                                            <li class="nav-item">
                                                <a class="nav-link @if($category->slug == 'best-sellers') active @endif" id="{{$category->slug}}-tab" data-toggle="pill" href="#menucat{{$count}}" role="tab" aria-controls="{{$category->slug}}" aria-selected="true" data-count="{{$count}}">{{$category->name}}</a>
                                            </li>
                                        @endif
                                    @empty
                                    @endforelse

                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    @php
                                        $count2=0;
                                    @endphp
                                    @forelse($productCategories as $category)
                                        @php
                                            $featured_product = \App\Models\Product::where('status', 'PUBLISHED')->where('category_id', $category->id)->get();

                                            $count2++;
                                        @endphp
                                        <div class="tab-pane fade show active" id="menucat{{$count2}}" role="tabpanel" aria-labelledby="{{$category->slug}}-tab">
                                            <div class="owl-carousel" id="owl{{$count2}}">

                                                @forelse($featured_product as $product)
                                                    @php
                                                        $main = \App\Models\Product::info($product->name);
                                                    @endphp
                                                    <div class="food-box">
                                                        <img src="{{ asset('storage/products/'.$main->photoPrimary) }}" class="mb-2"/>
                                                        <a href="#">{{$product->name}}</a>
                                                    </div>
                                                @empty
                                                @endforelse
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                                <div class="download-menu">
                  
                                    <a href="{{ asset('laravel-filemanager/file-manager/Files/Menu.pdf') }}" target="_blank" style="display:none;">Download Menu</a>
                                    <a href="{{ asset('laravel-filemanager/file-manager/MENU%20FEB2022.jpg') }}" target="_blank" style="display:none;">Download Menu</a>
                                    <a href="{{ asset('laravel-filemanager/file-manager/lydias-menu.jpg') }}" target="_blank" >Download Menu</a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {!! \App\Helpers\Webfocus\Setting::getAds()->contents !!}

        {!! \App\Helpers\Shortcode::process("[latest homepage]") !!}

        <section>
            <div class="subcription wrapper">
                <div class="gap-70"></div>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <h2>Subscribe to our newsletter</h2>
                            <p>Join our subscriber's list to get the latest updates and articles delivered straight to your inbox..</p>
                            <div class="gap-10"></div>
                            <form id="subscribeForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Name" name="first_name" autocomplete="on" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Email" name="email" autocomplete="on" required>
                                    </div>
                                </div>
                                <div class="gap-20"></div>
                                <button id="btnSubscribe" class="btn btn-primary more1"><strong>Subscribe</strong></button>
                                <div class="modal fade" id="newsletter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Thank you for subscribing.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="newsletter-exist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Your email is already in our list.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="gap-70"></div>
            </div>
        </section>
        @endif        

        <div class="main-links-resp">
            <div class="main-links">
                <ul>
                    <li @if($page->name == 'Lechon Pricelist') class="active" @endif><a href="{{ env('APP_URL').'/lechon-pricelist' }}">Lechon Pricelist</a></li>
                    <li @if($page->name == 'Order') class="active" @endif><a href="#" data-toggle="modal" data-target="#terms-modal">Order Online</a></li>
                    <li  @if($page->name == 'Call Hotline') class="active" @endif><a id="callHotline">Call Hotline</a>
                        <div class="call-hotline">
                            <button class="call-hotline-close-btn"><span class="fa fa-times"></span></button>
                            <ul>
                                @php
                                    $branches = \App\EcommerceModel\Branch::hotline();
                                @endphp
                                @foreach ($branches as $branch)
                                    @if($branch->numbers->count() > 0)
                                        <li><a href="#" data-toggle="modal" data-target="#branch_mx{{$branch->id}}">{{$branch->name}}</a></li>
                                    @else
                                        <li><a href="tel:{{ $branch->contact_nos }}">{{$branch->name}}</a></li>
                                    @endif
                                @endforeach
                             </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

  
            <!-- Messenger Chat Plugin Code -->
            <div id="fb-root"></div>
        
            <!-- Your Chat Plugin code -->
            <div id="fb-customer-chat" class="fb-customerchat">
            </div>
     
        
        @include('theme.'.config('app.frontend_template').'.layout.footer')
        
       
        <!-- Page Footer -->

            <!-- END Page Footer -->
    </div>

    <div id="privacy-policy" class="privacy-policy dark" style="display:none;">
        <div class="privacy-policy-desc">
            <p class="title">Privacy-Policy</p>
            <p>
                This website uses cookies to ensure you get the best experience.
            </p>
        </div>
        <div class="privacy-policy-btn">
            <a class="primary-btn small" href="#" id="cookieAcceptBarConfirm">Accept</a>
            <a class="default-btn small" href="{{ route('privacy-policy') }}">Learn More</a>
        </div>
    </div>

    <div class="modal fade pb-5 mb-2" id="call-desktop" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Call Hotline</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body call-hotline-modal">
                    <table style="width: 100%;">
                        <tbody>
                        <tr>
                            <td>Branch</td>
                            <td>Contact Number</td>
                        </tr>
                    @php
                        $branches = \App\EcommerceModel\Branch::hotline();
                    @endphp
                    @foreach ($branches as $branch)
                        <tr>
                            <td>{{$branch->name}}</td>
                            <td><a href="tel:{{$branch->contact_nos}}">{{$branch->contact_nos}} </a></td>
                        </tr>
                    @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default more2 btn-callhotline" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade pb-5 mb-2"  id="alert_success" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{ Session::get('success') }}</h5>
                </div>
                <div class="modal-footer">
                    <a href="{{route('product.front.show_forsale')}}" class="btn btn-success">Close</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade pb-5 mb-2"  id="alert_error" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{ Session::get('error') }}</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <!-- Important Reminders asked to be removed by team lydias
    <div class="modal fade pb-5 mb-2" id="terms-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{!! \App\Helpers\Webfocus\Setting::getImportantReminders()->label !!}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! \App\Helpers\Webfocus\Setting::getImportantReminders()->contents !!}
                </div>
                <div class="modal-footer">
                    <a href="{{route('product.front.show_forsale')}}" class="btn btn-success">I accept</a>
                </div>
            </div>
        </div>
    </div> 
    -->
    <div class="modal fade pb-5 mb-2" id="terms-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal" role="document">
            <div class="modal-content">
            @php
                $disabled_order_dates = explode(",",\App\Helpers\Webfocus\Setting::info()->disable_pickup_dates);
                $disabled_delivery_dates = explode(",",\App\Helpers\Webfocus\Setting::info()->disable_delivery_dates);
                $current_date=date('Y-m-d');
                $is_order_allow = 1;
                if(\App\Helpers\Webfocus\Setting::info()->disable_order == 1 && \App\Helpers\Webfocus\Setting::info()->disable_delivery == 1){
                    if(in_array($current_date, $disabled_order_dates) && in_array($current_date, $disabled_delivery_dates)){
                        $is_order_allow = 0;
                    }
                }
            @endphp

            @if($is_order_allow == 1)
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Delivery or Pickup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deliveryoption_form" method="post">
                        @csrf
                        <div class="payment-options">
                            <div class="row no-gutters">
                                <div class="col-6 text-center border rounded p-3">
                                    <h6><strong>Pick-up</strong></h6>
                                    @if(\App\Helpers\Webfocus\Setting::info()->disable_order == 0)
                                        <a href="#" onclick="set_deliver_option('pick-up');">
                                            <img src="{{ asset('images/order/pickup.jpg') }}" />
                                        </a>
                                    @else
                                      
                                        @if(in_array($current_date, $disabled_order_dates))
                                            <a href="#" onclick="show_order_message();">
                                                <img src="{{ asset('images/order/pickup.jpg') }}" style="-webkit-filter: grayscale(100%);filter: grayscale(100%);" />
                                            </a>
                                        @else
                                            <a href="#" onclick="set_deliver_option('pick-up');">
                                                <img src="{{ asset('images/order/pickup.jpg') }}" />
                                            </a>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-6 text-center border rounded p-3">
                                    <h6><strong>Delivery</strong></h6>
                                    @if(\App\Helpers\Webfocus\Setting::info()->disable_delivery == 0)
                                        <a href="#"  onclick="set_deliver_option('delivery');">                                            
                                            <img src="{{ asset('images/order/delivery.jpg') }}" />
                                        </a>
                                    @else
                                        
                                        @if(in_array($current_date, $disabled_delivery_dates))
                                            <a href="#" onclick="show_order_message();">                                            
                                                <img src="{{ asset('images/order/delivery.jpg') }}" style="-webkit-filter: grayscale(100%);filter: grayscale(100%);" />
                                            </a>
                                        @else
                                            <a href="#"  onclick="set_deliver_option('delivery');">                                            
                                                <img src="{{ asset('images/order/delivery.jpg') }}" />
                                            </a>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-12 text-center mt-1">
                                    <small class="text-danger">Note: Delivery fee will be added upon checkout</small>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="delivery_option" id="delivery_option" value="">                         

                    </form>

                </div>
            @else
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">To our valued customers!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="white-space: pre-line">
                   {!! \App\Helpers\Webfocus\Setting::info()->order_message !!}
                   </div>
                </div>
            @endif
               
            </div>
            
        </div>
    </div> 

    <div class="modal fade pb-5 mb-2" id="order_message_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal" role="document">
            <div class="modal-content">            
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">To our valued customers!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="white-space: pre-line">
                   {!! \App\Helpers\Webfocus\Setting::info()->order_message !!}
                   </div>
                </div>
            </div>            
        </div>
    </div> 
    
    <div class="modal fade pb-5 mb-2" id="announcement_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal" role="document">
            <div class="modal-content">           
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">To our valued customers!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="white-space: pre-line">
                   {!! \App\Helpers\Webfocus\Setting::info()->announcement !!}
                    </div>
                </div>   
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                </div>        
            </div>            
        </div>
    </div>


    @if(\App\Helpers\Webfocus\Setting::getPromoAds()->status == "PUBLISHED")
        <div id="ns-box" class="modal fade onload-page" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ns_hide()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-promo">
                         {!! \App\Helpers\Webfocus\Setting::getPromoAds()->contents !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
    <script type="text/javascript" src="{{ asset('theme/lydias/js/stickynav.js') }}"></script>
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
@if(Session::has('success'))
    <script>
        $('#alert_success').modal('show');
    </script>
@endif
@if(Session::has('error'))
    <script>
        $('#alert_error').modal('show');
    </script>
@endif

    <script>
        function show_announcement_global(){
            @if(strlen(\App\Helpers\Webfocus\Setting::info()->announcement) > 7)
                $('#announcement_modal').modal('show');
            @endif
        }
    </script>

<script>
    function show_terms(){
        //$('#announcement_modal').modal('hide');

        $('#terms-modal').modal('show');
    }
    $('#subscribeForm').submit(function (e) {
        e.preventDefault();
        $('#btnSubscribe').prop('disabled', true);
        let data = $(this).serialize();
        $.ajax({
            data: data,
            url: "{{ route('mailing-list.front.subscribe') }}",
            type: "POST",
            success: function (result) {
                $('#btnSubscribe').prop('disabled', false);
                if (result.success) {
                    $('#newsletter').modal('show');
                } else {
                    $('#newsletter-exist').modal('show');
                }
            }
        });

        return false;
    });

    function set_deliver_option(x){        
        
        $('#delivery_option').val(x);
        
        $.ajax({
            type: "POST",
            url: "{{route('set-delivery-option')}}",
            data: $('#deliveryoption_form').serialize(),
            success: function(){
                window.location.href = "{{route('product.front.show_forsale')}}";                
            }
        });

       return false;
    }

    function show_order_message(){
        $('#order_message_modal').modal('show');
    }

    $('#HomeModal').on('hidden.bs.modal', function () {
      show_announcement_global();
    })

</script>



    
        <script>
          var chatbox = document.getElementById('fb-customer-chat');
          chatbox.setAttribute("page_id", "453454724672376");
          chatbox.setAttribute("attribution", "biz_inbox");
    
          window.fbAsyncInit = function() {
            FB.init({
              xfbml            : true,
              version          : 'v11.0'
            });
          };
    
          (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));
        </script>



</body>
</html>
