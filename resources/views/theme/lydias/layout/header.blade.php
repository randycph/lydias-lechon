<div class="header">

    <div id="content-anchor"></div>
    <div id="sticky-phantom"></div>
    <div id="sticky">
        <div class="top-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-9" data-overlay-logo="{{ Setting::get_company_logo_storage_path() }}">
                        <a href="{{route('home')}}" class="logo-content">
                            <img src="{{ Setting::get_company_logo_storage_path() }}" alt="Lydia's Lechon" />
                        </a>
                    </div>

                    <div class="col-lg-7">
                        <div class="main-links main-links-desktop">
                            <ul>
                                <li @if($page->name == 'Lechon Pricelist') class="active" @endif><a href="{{ env('APP_URL').'/lechon-pricelist' }}">Lechon Pricelist</a></li>
                                <li @if($page->name == 'Order') class="active" @endif>
                                    @if(strlen(Setting::info()->announcement) > 7)
                                        <a href="#" data-toggle="modal" data-target="#terms-modal">Order Online</a></li>
                                    @else
                                        <a href="#" data-toggle="modal" data-target="#terms-modal">Order Online</a></li>
                                    @endif
                                    
                                <li class="call-mobile" @if($page->name == 'Call Hotline') class="active" @endif><a href="#">Call Hotline</a>
                                    <ul>
                                        @php
                                            //$branches = \App\EcommerceModel\Branch::hotline();
                                            $branches = \App\EcommerceModel\Branch::whereNotNull('hotline')
                                                        ->orderByRaw("FIELD(id, 16,17,3,5,6,1,25,7,23,24,30,4,8,9,32,31,11,10)")
                                                        ->get();

                                            $modal_hotline='';
                                        @endphp
                                        @foreach ($branches as $branch)
                                            <li><a href="#" data-toggle="modal" data-target="#branch_m{{$branch->id}}">{{$branch->name}}</a>
                                                @if($branch->numbers->count() > 0)
                                                    @php 
                                                        $subnum='<ul>';
                                                    @endphp
                                                    <ul>                                                        
                                                    @foreach($branch->numbers as $n)
                                                        <li><a href="#" data-toggle="modal" data-target="#branch_ms{{$n->id}}">{{$n->name}} - {{$n->number}}</a></li>
                                                        @php
                                                            $subnum.='<li><a href="tel:'.$n->number.'">'.$n->name.' - '.$n->number.'</a></li>';
                                                        @endphp
                                                        @php
                                                            $modal_hotline.='<div class="modal fade" id="branch_ms'.$n->id.'" tabindex="-1" role="dialog" aria-labelledby="branch_ms'.$n->id.'" aria-hidden="true">
                                                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="exampleModalLongTitle">'.$branch->name.' '.$n->name.'</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <div class="row no-gutters">
                                                                                                <div class="col-9">
                                                                                                    '.$n->number.'
                                                                                                </div>
                                                                                                <div class="col-3 text-right">
                                                                                                    <a href="tel:'.$n->number.'" type="button" class="btn btn-success btn-sm"><i class="fa fa-phone"></i></a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>';
                                                        @endphp
                                                    @endforeach
                                                    </ul>
                                                    @php 
                                                        $subnum.='<ul>';
                                                    @endphp
                                                @endif
                                            </li>
                                                
                                                
                                                @if($branch->numbers->count() > 0)
                                                    @php
                                                    $modal_hotline.='
                                                        <div class="modal fade" id="branch_mx'.$branch->id.'" tabindex="-1" role="dialog" aria-labelledby="branch_mx'.$branch->id.'" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLongTitle">'.$branch->name.'</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        '.$subnum.'
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                                    @endphp
                                                @endif
                                                @php
                                                $modal_hotline.='<div class="modal fade" id="branch_m'.$branch->id.'" tabindex="-1" role="dialog" aria-labelledby="branch_m'.$branch->id.'" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLongTitle">'.$branch->name.'</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="row no-gutters">
                                                                                    <div class="col-9">
                                                                                        '.$branch->contact_nos.'
                                                                                    </div>
                                                                                    <div class="col-3 text-right">
                                                                                        <a href="tel:'.$branch->contact_nos.'" type="button" class="btn btn-success btn-sm"><i class="fa fa-phone"></i></a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>';
                                                    @endphp
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="call-desktop" @if($page->name == 'Call Hotline') class="active" @endif><a href="#" data-toggle="modal" data-target="#call-desktop">Call Hotline</a>
                                    <ul>
                                        @foreach ($branches as $branch)
                                            <li><a href="#" data-toggle="modal" data-target="#branch_m{{$branch->id}}">{{$branch->name}}</a>
                                                @if($branch->numbers->count() > 0)
                                                    <ul>                                                        
                                                    @foreach($branch->numbers as $n)
                                                        <li><a href="#" data-toggle="modal" data-target="#branch_ms{{$n->id}}">{{$n->name}} - {{$n->number}}</a></li>
                                                    @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-12">
                        <div class="top-cart">
                            <ul>
                                <li><a href="https://www.facebook.com/lydiaslechonrestaurant/" target="_blank" class="rounded-circle text-center" 
                                    style="height:25px;width:25px;background:#3b5998;display:inline-block;line-height:25px;"><span class="fab fa-facebook-f"></span></a>
                                </li>
                                <li class="border-right pr-3"><a href="https://www.instagram.com/lydiaslechon" target="_blank" class="rounded-circle text-center" 
                                    style="height:25px;width:25px;background:#3f729b;display:inline-block;line-height:25px;"><span class="fab fa-instagram"></span></a>
                                </li>
							@if (Auth::check())
                                    <li class="user-menu">
                                        <a href="#" data-toggle="tooltip" data-placement="bottom" title="Manage Account">
                                            <i width="20" data-feather="user"></i>
                                        </a>
                                        <ul class="user-menu-list">
                                            <li><a href="{{ route('my-account.manage-account')}}" >Manage Account</a></li>
                                            <li><a href="{{ route('my-account.update-password') }}" >Change Password</a></li>
                                            <li><a href="{{ route('profile.sales') }}" >Sales Transaction</a></li>
                                            <li class="user-menu-logout"><a href="{{ route('customer-front.logout') }}" >Logout</a></li>
                                        </ul>
                                    </li>
							@else
                                <li class="user-menu">
                                    <a href="#" data-toggle="tooltip" data-placement="bottom" title="Log-in">
                                        <i width="20" data-feather="user"></i>
                                    </a>
                                    <ul class="user-menu-list">
                                        <li><a href="{{ route('customer-front.customer_login') }}" >Log in</a></li>
                                        <li><a href="{{ route('customer-front.sign-up') }}" >Sign up</a></li>
                                    </ul>
                                </li>
							@endif
                                <li>
                                    <a href="{{route('cart.front.show')}}" title="Cart">
                                        <i width="20" data-feather="shopping-cart" ></i>
                                        <span class="badge badge-light cart-counter">{{ Setting::EcommerceCartTotalItems() }}</span>
                                    </a>
                                </li>
                                

                            </ul>
                        </div>
                    </div>

                    <div class="trigger">
                        <a href="#menu-desk"><span></span></a>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="nav-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div id="navigation">
                        <div id="menu-desk">
                            @include('theme.'.config('app.frontend_template').'.layout.menu')
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="top-social">
                        <ul>
                            @if(!empty(Setting::social_account('facebook')->media_account))
                                <li><a href="https://facebook.com/{!!Setting::social_account('facebook')->media_account!!}" target="_blank"><span class="fab fa-facebook-square"></span></a></li>
                            @endif
                            @if(!empty(Setting::social_account('twitter')->media_account))
                                <li><a href="https://twitter.com/{!!Setting::social_account('twitter')->media_account!!}" target="_blank"><span class="fab fa-twitter"></span></a></li>
                            @endif
                            @if(!empty(Setting::social_account('instagram')->media_account))
                                <li><a href="https://instagram.com/{!!Setting::social_account('instagram')->media_account!!}" target="_blank"<span class="fab fa-instagram"></span></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

</div>

           
                   
{!! $modal_hotline !!}
