@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
@endsection

@section('content')
    <main>
        <section id="cart-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="cart-title">
                            <h2>My Cart</h2>
                        </div>

                        <ul class="cart-wrap">
                            <li class="item">
                                <div class="remove-item">
                                    <a href="#" class="remove">Remove <span class="lnr lnr-cross"></span></a>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4">
                                        <div class="img-wrap">
                                            <a href="product-profile.htm"><img src="{{ url('/') }}/theme/legande/images/misc/img-placeholder.png" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="info-wrap">
                                            <div class="cart-description">
                                                <h3 class="cart-product-title"><a href="product-profile.htm">The Atelier Tailored Coat</a></h3>
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                                                    <li class="breadcrumb-item active" aria-current="page">Dress</li>
                                                </ol>
                                            </div>
                                            <div class="cart-quantity">
                                                <label for="quantity">Quantity</label>
                                                <select name="quantity" id="quantity">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                    <option value="21">21</option>
                                                    <option value="22">22</option>
                                                    <option value="23">23</option>
                                                    <option value="24">24</option>
                                                    <option value="25">25</option>
                                                </select>
                                            </div>
                                            <div class="cart-info">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <p>Color</p>
                                                                </td>
                                                                <td>
                                                                    <p>Black</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <p>Size</p>
                                                                </td>
                                                                <td>
                                                                    <p>Medium</p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="cart-product-price">Php 499.00</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="remove-item">
                                    <a href="#" class="remove">Remove <span class="lnr lnr-cross"></span></a>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4">
                                        <div class="img-wrap">
                                            <a href="product-profile.htm"><img src="{{ url('/') }}/theme/legande/images/misc/img-placeholder.png" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="info-wrap">
                                            <div class="cart-description">
                                                <h3 class="cart-product-title"><a href="product-profile.htm">The Atelier Tailored Coat</a></h3>
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                                                    <li class="breadcrumb-item active" aria-current="page">Dress</li>
                                                </ol>
                                            </div>
                                            <div class="cart-quantity">
                                                <label for="quantity">Quantity</label>
                                                <select name="quantity" id="quantity">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                    <option value="21">21</option>
                                                    <option value="22">22</option>
                                                    <option value="23">23</option>
                                                    <option value="24">24</option>
                                                    <option value="25">25</option>
                                                </select>
                                            </div>
                                            <div class="cart-info">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <p>Color</p>
                                                                </td>
                                                                <td>
                                                                    <p>Black</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <p>Size</p>
                                                                </td>
                                                                <td>
                                                                    <p>Medium</p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="cart-product-price">Php 499.00</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="remove-item">
                                    <a href="#" class="remove">Remove <span class="lnr lnr-cross"></span></a>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4">
                                        <div class="img-wrap">
                                            <a href="product-profile.htm"><img src="{{ url('/') }}/theme/legande/images/misc/img-placeholder.png" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="info-wrap">
                                            <div class="cart-description">
                                                <h3 class="cart-product-title"><a href="product-profile.htm">The Atelier Tailored Coat</a></h3>
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                                                    <li class="breadcrumb-item active" aria-current="page">Dress</li>
                                                </ol>
                                            </div>
                                            <div class="cart-quantity">
                                                <label for="quantity">Quantity</label>
                                                <select name="quantity" id="quantity">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                    <option value="21">21</option>
                                                    <option value="22">22</option>
                                                    <option value="23">23</option>
                                                    <option value="24">24</option>
                                                    <option value="25">25</option>
                                                </select>
                                            </div>
                                            <div class="cart-info">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <p>Color</p>
                                                                </td>
                                                                <td>
                                                                    <p>Black</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <p>Size</p>
                                                                </td>
                                                                <td>
                                                                    <p>Medium</p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="cart-product-price">Php 499.00</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="item">
                                <div class="remove-item">
                                    <a href="#" class="remove">Remove <span class="lnr lnr-cross"></span></a>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4">
                                        <div class="img-wrap">
                                            <a href="product-profile.htm"><img src="{{ url('/') }}/theme/legande/images/misc/img-placeholder.png" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="info-wrap">
                                            <div class="cart-description">
                                                <h3 class="cart-product-title"><a href="product-profile.htm">The Atelier Tailored Coat</a></h3>
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                                                    <li class="breadcrumb-item active" aria-current="page">Dress</li>
                                                </ol>
                                            </div>
                                            <div class="cart-quantity">
                                                <label for="quantity">Quantity</label>
                                                <select name="quantity" id="quantity">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                    <option value="21">21</option>
                                                    <option value="22">22</option>
                                                    <option value="23">23</option>
                                                    <option value="24">24</option>
                                                    <option value="25">25</option>
                                                </select>
                                            </div>
                                            <div class="cart-info">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <p>Color</p>
                                                                </td>
                                                                <td>
                                                                    <p>Black</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <p>Size</p>
                                                                </td>
                                                                <td>
                                                                    <p>Medium</p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="cart-product-price">Php 499.00</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-3">
                        <div class="cart-title">
                            <h2>Summary</h2>
                        </div>
                        <div class="summary-wrap">
                            <div class="promo-code">
                                <label for="promo">Enter promo code or <span class="white-spc">gift card</span></label>
                                <div class="input-group">
                                    <input type="text" class="promo-input form-control" name="promo" id="promo">
                                    <div class="input-group-append">
                                        <button class="btn promo-btn" type="button">Apply</button>
                                    </div>
                                </div>
                            </div>
                            <div class="subtotal">
                                <div class="table">
                                    <div class="table-row">
                                        <div class="table-cell">
                                            Subtotal
                                        </div>
                                        <div class="table-cell">
                                            Php 4,999.00
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grand-total">
                                <div class="table">
                                    <div class="table-row">
                                        <div class="table-cell">
                                            Grand Total
                                        </div>
                                        <div class="table-cell">
                                            Php 4,999.00
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="shipping-message">Shipping fees will apply <span class="white-spc">upon checkout</span></div>
                            <div class="cart-btn">
                                <div class="row">
                                    <div class="col-12">
                                        <a class="btn btn-lg secondary-btn" href="product-listing.htm">Update My Cart</a>
                                    </div>
                                    <div class="col-12">
                                        <a class="btn btn-lg primary-btn" href="">Proceed to Checkout</a>
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
