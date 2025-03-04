@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/vanilla-zoom/vanilla-zoom.css') }}" />
@endsection

@section('content')
    <main>
        <section id="listing-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-wrap">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div id="product-view" class="vanilla-zoom">
                                        <div class="zoomed-image"></div>
                                        <div class="bottom-bar">
                                            <div class="card">
                                                <img src="{{\URL::to('/')}}/theme/lydias/images/misc/img-placeholder.png" class="small-preview">
                                            </div>
                                            <div class="card">
                                                <img src="{{\URL::to('/')}}/theme/lydias/images/misc/img-placeholder.png" class="small-preview">
                                            </div>
                                            <div class="card">
                                                <img src="{{\URL::to('/')}}/theme/lydias/images/misc/img-placeholder.png" class="small-preview">
                                            </div>
                                            <div class="card">
                                                <img src="{{\URL::to('/')}}/theme/lydias/images/misc/img-placeholder.png" class="small-preview">
                                            </div>
                                            <div class="card">
                                                <img src="{{\URL::to('/')}}/theme/lydias/images/misc/img-placeholder.png" class="small-preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <form id="addToCart" data-source="addToCart">
                                        <div class="product-detail">

                                            <div class="product-description">
                                                <a class="go-back" href="product-listing.htm"><span class="fas fa-angle-left"></span> Back to Categories</a>
                                                <h2>The Atelier Tailored Coat</h2>
                                                <div class="rating small">
                                                    <span class="fa fa-star checked"></span>
                                                    <span class="fa fa-star checked"></span>
                                                    <span class="fa fa-star checked"></span>
                                                    <span class="fa fa-star checked"></span>
                                                    <span class="fa fa-star checked"></span>
                                                    <span class="rating-count">3 Reviews(s)</span>
                                                </div>
                                                <div class="product-price">Php 499.00</div>
                                                <div class="product-main">
                                                    <ul>
                                                        <li><span class="product-properties">Product Code:</span><span class="product-value">#4657</span></li>
                                                        <li><span class="product-properties">Tags:</span><span class="product-value">Fashion, Hood, Classic</span></li>
                                                    </ul>
                                                </div>
                                                <p>Sleek, polished, and boasting an impeccably modern fit, this blue, 2-but-ton Lazio suit features a notch lapelm flap pockets, and accompanying flat front trousers - all in pure wool by Vitale Barberis Canonico.</p>
                                                <p>&nbsp;</p>
                                                <ul>
                                                    <li>Dark blue suit for a tone-on-tone look</li>
                                                    <li>Regular fit</li>
                                                    <li>100% Cotton</li>
                                                    <li>Free shipping with 4 days delivery</li>
                                                </ul>
                                            </div>


                                            <div class="w-100">
                                                <div class="product-info">
                                                    <div class="container p-0">
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label for="product-color">Color</label>
                                                                    <select class="form-control" id="product-color">
                                                                        <option selected="">Select Color</option>
                                                                        <option value="1">Black</option>
                                                                        <option value="2">White</option>
                                                                        <option value="3">Red</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="product-color">Size</label>
                                                                    <select class="form-control" id="product-color">
                                                                        <option selected="">Select Size</option>
                                                                        <option value="1">Small</option>
                                                                        <option value="2">Medium</option>
                                                                        <option value="3">Large</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="quantity">Qty</label>
                                                                <div class="quantity">
                                                                    <input type="number" name="quantity" min="1" max="25" step="1" value="1" data-inc="1">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <button class="clear-btn" type="reset">Clear Selection</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-btn">
                                                    <button type="button" class="btn btn-lg add-cart-alt2-btn addToCartButton" data-loading-text="processing...">
                                                        <img src="{{\URL::to('/')}}/theme/lydias/images/misc/cart.png" alt=""> Add to cart
                                                    </button>
                                                    <button type="button" class="btn btn-lg wishlist-btn buyNowButton" data-loading-text="processing...">
                                                        <img src="{{\URL::to('/')}}/theme/lydias/images/misc/heart-black.png" alt=""> Add to wishlist
                                                    </button>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <a class="btn btn-lg primary-btn" href="member.htm">Be a Member</a>
                                                    </div>
                                                </div>
                                                <div class="product-share">
                                                    <div class="share-text">Share This</div>
                                                    <div class="share_link"></div>
                                                </div>

                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="gap-30"></div>
                        <div class="product-additional">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="synopsis-tab" data-toggle="tab" href="#synopsis" role="tab"
                                       aria-controls="synopsis" aria-selected="true">Synopsis</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail"
                                       aria-selected="false">Details about the product</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review"
                                       aria-selected="false">Reviews</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="synopsis" role="tabpanel" aria-labelledby="synopsis-tab">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                                        magna
                                        aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis
                                        aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                                        occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum
                                        dolor sit
                                        amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                        minim
                                        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                        in
                                        reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                                        non
                                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                    <p>&nbsp;</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                                        magna
                                        aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis
                                        aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                                        occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                </div>
                                <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                    <table>
                                        <tr>
                                            <td>
                                                <p><b>Color:</b></p>
                                            </td>
                                            <td>
                                                <p>Blue, Green, Red</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p><b>Size:</b></p>
                                            </td>
                                            <td>
                                                <p>XS, S, M, L, XL, XXL, XXXL</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                                    <div class="empty-review-wrap">
                                        <img src="{{\URL::to('/')}}/theme/lydias/images/misc/comment.png" />
                                        <p>There are no reviews yet.<br />Be the first to review “The Atelier Tailored Coat”</p>
                                    </div>
                                    <div class="gap-40"></div>
                                    <form id='leave-review'>
                                        <div class="form-style-alt fs-sm">
                                            <h3>We want to know your opinion!</h3>
                                            <label for="rating-count"><b>Your Rating</b></label>
                                            <div class="rating">
                                                <i class="fa fa-star" data-rate="1"></i>
                                                <i class="fa fa-star" data-rate="2"></i>
                                                <i class="fa fa-star" data-rate="3"></i>
                                                <i class="fa fa-star" data-rate="4"></i>
                                                <i class="fa fa-star" data-rate="5"></i>
                                                <input type="hidden" id="rating-count" name="rating" value="0">
                                            </div>
                                            <div class="gap-20"></div>
                                            <div class="form-wrap">
                                                <textarea id="message" class="form-control form-input" name="message"></textarea>
                                                <label class="form-label textarea" for="message">Tell us what you thought about it</label>
                                            </div>
                                        </div>
                                        <div class="gap-20"></div>
                                        <button type="submit" class="btn btn-md primary-btn">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="gap-80"></div>
                        <div class="product-related">
                            <h2 class="listing-title">Related Products</h2>
                            <div class="gap-10"></div>
                            <div class="row">
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="product-profile.htm">
                                                <div class="product-img">
                                                    <img src="{{\URL::to('/')}}/theme/lydias/images/misc/img-placeholder.png" alt="" />
                                                </div>
                                                <div class="gap-30"></div>
                                                <p class="product-title">Red Women Purses</p>
                                            </a>
                                            <div class="rating small">
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                            </div>
                                            <h3 class="product-price">Php 15.00</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="product-profile.htm">
                                                <div class="product-img">
                                                    <img src="{{\URL::to('/')}}/theme/lydias/images/misc/img-placeholder.png" alt="" />
                                                </div>
                                                <div class="gap-30"></div>
                                                <p class="product-title">Red Women Purses</p>
                                            </a>
                                            <div class="rating small">
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                            </div>
                                            <h3 class="product-price">Php 15.00</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="product-profile.htm">
                                                <div class="product-img">
                                                    <img src="{{\URL::to('/')}}/theme/lydias/images/misc/img-placeholder.png" alt="" />
                                                </div>
                                                <div class="gap-30"></div>
                                                <p class="product-title">Red Women Purses</p>
                                            </a>
                                            <div class="rating small">
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                            </div>
                                            <h3 class="product-price">Php 15.00</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('jsscript')
@endsection
