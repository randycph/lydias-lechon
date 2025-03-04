<div class="header">
    <div style="height: 113px; display: block;"></div>
    <div id="sticky" class="stick">
        <div class="top-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-9" data-overlay-logo="{{ Setting::get_company_logo_storage_path() }}">
                        <a href="{{route('kiosk.home')}}" class="logo-content">
                            <img src="{{ Setting::get_company_logo_storage_path() }}" alt="Lydia's Lechon" />
                        </a>
                    </div>

                    <div class="col-lg-7"></div>

                    <div class="col-lg-3 col-md-12">
                        <div class="top-cart">
                            <ul>
                                <li>
                                    <a href="{{route('kiosk.cart')}}" title="Cart">
                                        <i width="20" data-feather="shopping-cart" ></i>
                                        <span class="badge badge-light cart-counter">{{ Setting::EcommerceCartTotalItems() }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- <div class="trigger">
                        <a href="#menu-desk"><span></span></a>
                    </div> -->

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
