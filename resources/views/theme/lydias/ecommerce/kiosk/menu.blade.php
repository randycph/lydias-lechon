@extends('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.kiosk.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/slick/slick-theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <style>
        .order-page-wrapper {}
        .order-page-items {position:relative;margin-bottom:26px;border:none;box-shadow: rgba(84, 89, 94, 0.2) 0px 3px 4px 0px; height:350px;}
        .order-page-items .order-page-image {overflow:hidden;display:flex;align-items:center;}
        .order-page-items .order-page-image img {width:100%;}

        .order-page-items .card-body {
            display: grid;
            grid-template-columns: 100% auto;
            text-align:center;
        }

        .order-page-items .card-body h5 {font-size:15px;text-align:center;line-height:20px;color:#333;font-weight:bold;}
        .card:hover {  box-shadow: 3px 3px 3px 3px #888888;; }

        .menu-category-list {
            background-color: #fff;
            overflow: hidden;
            width: 100%;
            position: sticky;
            z-index: 10;
            box-shadow: 0 2px 16px 0 rgb(0 0 0 / 8%);
            font: 500 13px "Montserrat";
        }

        .menu-category-list ul {
            flex-wrap: nowrap;
            display: inline-flex;
            margin: 0;
            padding: 0;
            list-style-type: none;
            transition: transform .2s cubic-bezier(.25,.46,.45,.94);
        }

        .menu-category-list li {
            padding: 0 0 0 25px;
            white-space: nowrap;
        }

        .menu-category-list li:first-of-type {
            padding-left: 0;
        }

        .menu-category-list li a {
            position: relative;
            overflow: hidden;
            height: 64px;
            line-height: 64px;
            font-weight: 700;
            color: #909090;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
            transform: translateZ(0);
        }

        .menu-category-list li a:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #f26522;
            transform: translateY(2px);
            transition: transform .15s cubic-bezier(.25,.46,.45,.94);
        }

        .menu-category-list li.current a {
            color: #222;
        }

        .menu-category-list li.current a:after, .menu-category-list li a:hover:after {
            transform: translateY(0px);
        }

        .section-wrapper {
            margin-top: -160px;
            padding-top: 160px;
        }

        .section-wrapper .section {
            padding: 20px 0;
        }

        .category-heading {
            color: #f26522;
            font-weight: 700;
        }

        .nav-holder {
            overflow-x: auto;
        }

        .nav-holder::-webkit-scrollbar {
            height: 5px;
        }

        .nav-holder::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        
        .nav-holder::-webkit-scrollbar-thumb {
            background: #004e1f; 
        }

        .nav-holder::-webkit-scrollbar-thumb:hover {
            background: #002E12; 
        }

        .modal-header .close {
            padding: 1rem;
            margin: 0;
            position: absolute;
            top: 0;
            right: 0;
            background: white;
            opacity: 1;
        }

        .btn-buy-now {
            background-color: #FF6C00;
            border-color: #FF6C00;
            border-top-left-radius:  0.2rem !important;
            border-bottom-left-radius: 0.2rem !important;
        }

        .sweet-alert button.cancel {
            background-color: #004E1F;
        }

    </style>
@endsection

@section('content')
    <div id="mySidenav" class="sidenav">
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    </div>
    <span onclick="closeNav()" class="dark-curtain"></span>

    <div class="menu-page">
        <section class="menu-category-list" style="top:95px">
            <div class="container">
                <div class="nav-holder">
                    <ul id="nav">
                        @foreach($productCategories as $category)
                            <li class="@if($loop->iteration == 1) current @endif">
                                <a href="#category_{{$category->id}}">{{$category->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <div class="gap-70"></div>
        <div class="container">
            <br>
            @php
                $var = 0;
                $misc_total = $miscs->count();
                $misc = \App\Product::misc();
            @endphp

            @foreach($productCategories as $category)
                <div class="section-wrapper" id="category_{{$category->id}}">
                    <div class="section">
                        <h4 class="category-heading">{{ $category->name }}</h4>
                        <div class="gap-20"></div>
                        <div class="menu-block">
                            <div class="row">
                                @php
                                    $products = \App\Product::menu_products($category->id);
                                @endphp

                                @forelse($products as $product)
                                    @php
                                        $main = \App\Product::info($product->name);
                                        $sizes = \App\Product::detail($product->name);
                                        $min_price = $sizes->min('price');
                                        $max_price = $sizes->max('price');
                                        $var++;
                                        $prod_stat = 0;
                                        $prod_url = route('product.front.show',$main->slug);
                                        foreach($sizes as $si){
                                            if(strtoupper($si->status) == 'PUBLISHED'){
                                                $prod_stat = 1;
                                                $prod_url = route('product.front.show',$si->slug);
                                            }
                                        }
                                    @endphp

                                    <div class="col-lg-3 col-sm-4 col-6 order-page-wrapper d-flex">
                                        <div class="d-flex flex-fill">
                                            <a href="javascript:void(0);" @if($prod_stat > 0) onclick="addCart('{{$main->id}}'); @endif">
                                                <div class="card order-page-items flex-fill">
                                                    <div class="order-page-image">
                                                        @if($min_price == $max_price)
                                                            <h4 class="card-price d-block d-lg-none"><span>&#8369;{{number_format($max_price,2)}}</span></h4>
                                                        @else
                                                            <h4 class="card-price d-block d-lg-none"><span>&#8369;{{number_format($min_price,2)}} - &#8369;{{number_format($max_price,2)}}</span></h4>
                                                        @endif

                                                        <img src="{{ asset('storage/products/'.$main->photoPrimary) }}"  @if($prod_stat == 0) style="-webkit-filter: grayscale(100%);filter: grayscale(100%);" @endif alt="@if($prod_stat == 0) Product Unavailable @else {{$main->name}} @endif">

                                                        @if($prod_stat == 0)
                                                            <a class="btn btn-danger btn-sm" href="javascript:void(0)" style=" position: absolute;bottom: 15px;right: 15px;">Not Available</a>
                                                        @endif
                                                    </div>
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{$main->name}}</h5><br>
                                                        @if($min_price == $max_price)
                                                            <h4 class="card-price d-none d-lg-block pb-4"><span>&#8369;{{number_format($max_price,2)}}</span></h4>
                                                        @else
                                                            <h4 class="card-price d-none d-lg-block pb-4"><span>&#8369;{{number_format($min_price,2)}} - &#8369;{{number_format($max_price,2)}}</span></h4>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                    @if($prod_stat > 0)
                                        <div class="modal fade pb-5 mb-2 " id="add-to-cart-modal-{{$main->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <img src="{{ asset('storage/products/'.$main->photoPrimary) }}" alt="{{ $main->name }}" class="w-100">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h4 class="mb-2">{{ $main->name }}</h4>
                                                        @if($min_price == $max_price)
                                                            <p class="price">&#8369;{{number_format($max_price,2)}}</p>
                                                        @else
                                                            <p class="price">&#8369;{{number_format($min_price,2)}} - &#8369;{{number_format($max_price,2)}}</p>
                                                        @endif          
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="cart-box">
                                                                    <div class="">
                                                                        <div class="form-group" style="text-align:left;">
                                                                            <label>Quantity</label>
                                                                            <div class="input-group qty-spin">
                                                                                <div class="input-group-prepend">
                                                                                    <button type="button" class="btn btn-secondary btn-number minus-btn" disabled="disabled" data-type="minus" data-field="qty{{$main->id}}">-</button>
                                                                                </div>
                                                                                <input type="text" name="qty{{$main->id}}" id="qty{{$main->id}}" class="form-control input-number quantity-fld text-center" value="1" min="1" max="1000" onchange="item_qty({{$main->id}});">
                                                                                <div class="input-group-append">
                                                                                    <button type="button" class="btn btn-secondary btn-number plus-btn" data-type="plus" data-field="qty{{$main->id}}">+</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        @if($sizes->count() > 1)
                                                                            <input type="hidden" id="item{{$main->id}}_size" value="{{$sizes->count()}}">
                                                                            <div class="form-group" style="text-align:left;">
                                                                                <label class="d-block">Weight</label>
                                                                                <select class="custom-select" id="item{{$main->id}}" placeholder="Select Weight" name="item" onchange="item_weight({{$main->id}});">
                                                                                    <option value="" selected style="color:gray">Select..</option>
                                                                                    @forelse($sizes->where('status','PUBLISHED') as $d)
                                                                                        <option value="{{$d->id}}|{{$d->price}}|{{$d->weight}}" data-value="{{$d->price}}">
                                                                                            {{$d->weight}} / {{$d->no_of_pax}}
                                                                                        </option>
                                                                                    @empty
                                                                                    @endforelse

                                                                                </select>
                                                                            </div>
                                                                        @else
                                                                            <input type="hidden" id="item{{$main->id}}_size" value="{{$sizes->count()}}">
                                                                            @forelse($sizes->where('status','PUBLISHED') as $d)
                                                                                <input type="hidden" id="item{{$main->id}}" value="{{$d->id}}|{{$d->price}}|{{$d->weight}}">
                                                                            @empty
                                                                            @endforelse
                                                                        @endif

                                                                        @if($main->paella_price > 0)
                                                                            <div class="form-group" style="text-align:left;">
                                                                                <div class="custom-control custom-checkbox">
                                                                                    <input type="hidden" name="paella_price" id="paella_price{{$main->id}}" value="{{$main->paella_price}}">
                                                                                    <input type="checkbox" class="custom-control-input" id="customCheck{{$main->id}}" onchange="item_paella({{$main->id}})">
                                                                                    <label class="custom-control-label" for="customCheck{{$main->id}}">Add Seafood Paella</label>
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        <div class="form-group" style="text-align:left;">
                                                                            <label class="d-block">Add Misc</label>
                                                                            <form id="misc_form{{$main->id}}">
                                                                                <div class="input-group mb-3">
                                                                                    <select class="form-control custom-select" id="misc{{$main->id}}" placeholder="Select.." required="required" name="misc">
                                                                                        <option value="" selected style="color:gray">Select Products</option>
                                                                                        @forelse($misc as $m)
                                                                                            <option value="{{$m->id}}|{{$m->price}}|{{$m->name}}" data-value="{{$m->price}}">{{$m->name}}</option>
                                                                                        @empty
                                                                                        @endforelse
                                                                                    </select>
                                                                                    <div class="input-group-append">
                                                                                        <button class="btn btn-green" type="button" onclick="add_misc({{$main->id}});">Add</button>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                            <span class="text-danger" id="misc_help" style="display:none;">Select misc item from the list.</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <form id="cart_frm{{$main->id}}">
                                                                    @csrf
                                                                    <input type="hidden" value="0" name="ac_paella" id="ac_paella{{$main->id}}">
                                                                    <input type="hidden" value="addcart" name="action">
                                                                    <div class="cart-box">
                                                                        <div class="delivery">
                                                                            <div class="delivery-header__title">
                                                                                Order Summary
                                                                            </div>
                                                                            <input type="hidden" id="misc_cntr{{$main->id}}" name="misc_cntr" value="0">
                                                                            <div class="delivery-body">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>Product</th>
                                                                                        <th>Price</th>
                                                                                        <th align="right">Total</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody id="order_table{{$main->id}}"></tbody>
                                                                                </table>
                                                                                <div class="gap-30"></div>
                                                                                <div id="misc_div{{$main->id}}" style="display:none;">
                                                                                    <div class="delivery-header__title">
                                                                                        Misc Items
                                                                                    </div>
                                                                                    <table class="table" id="misc_table">
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th>Product</th>
                                                                                            <th>Price</th>
                                                                                            <th>Qty</th>
                                                                                            <th>Total</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody id="misc_tbody{{$main->id}}"></tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <div class="border-top my-3"></div>
                                                                                <p class="text-right price" style="margin-bottom:40px;">&#8369; <span id="total_price{{$main->id}}">0.00</span></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <div class="col-md-12">
                                                            <button type="button" class="btn btn-warning btn-buy-now text-white w-100" onclick="add_to_cart('buynow',{{$main->id}})" style="margin-bottom:10px;">Buy Now</button>
                                                            <button type="button" class="btn btn-green w-100" onclick="add_to_cart('addcart',{{$main->id}})" >Add to Cart</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="gap-70"></div>
    </div>

    @if((auth()->check()))
        <input type="hidden" id="utype" value="{{Auth::user()->user_type}}">
    @endif

    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Order Successfully Submitted</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    We've sent you an email for your order summary.
                </div>
                <div class="modal-footer">
                    <a class="btn btn-success" href="{{route('product.front.show_forsale')}}">Ok</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsscript')
    <script>
        $(document).ready(function(){
            var width = $(window).width();
            var height = $(window).height();
            
            $('.one-time').slick({
                dots: false,
                infinite: true,
                speed: 500,
                arrows: false,
                draggable: false,
                slidesToShow: 1,
                autoplay: true,
                fade: true,
                adaptiveHeight: true
            });

            $('#nav').onePageNav({
                scrollChange: function($currentListItem) {
                    $('.nav-holder').animate({'scrollLeft': $currentListItem[0].offsetLeft}, 500);    
                }
            });
        });
    </script>

    <script type="text/javascript" src="{{ asset('theme/lydias/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('theme/lydias/plugins/slick/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('theme/lydias/js/jquery.nav.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script>
        @if(isset($_GET['success']))
            $('#successModal').modal('show');
        @endif

        $( document ).ready(function() {
            @if(isset($_GET['order_completed']))
                $('#successModal').modal('show');
            @endif
            
            @if(Session::has('product_added'))
                $('#product_added').modal('show');
            @endif

            $('.product-with-1qty').each(function(i, obj) {
                compute($(this).val());
            });
        });

        function compute(x){
            var a = ($('#inputGroupSelect01'+x).val()).split('|');

            var price_total = parseFloat(a[1]) * parseFloat($('#qty'+x).val());
            var price_item_total = parseFloat(a[1]) * parseFloat($('#qty'+x).val());
            var qty = $('#qty'+x).val()
            var paella = parseFloat($('#paella_price'+x).val());
            if($('#paella'+x).is(':checked'))
            {
                price_total += (parseFloat($('#paella_price'+x).val()) * parseFloat($('#qty'+x).val()));
            } else {
                paella = 0;
            }

            $('#price_div'+x).html(addCommas(price_total+'.00')) ;
            $('#price_item_div'+x).html(addCommas(price_item_total+'.00'));
            $('#price_paella_div'+x).html(paella);
            $('#item_div'+x).html(addCommas(parseFloat(a[1])+'.00'));
            $('#qty_div'+x).html(qty);
        }


        function addCommas(nStr){
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }
    </script>

    <script>
        function buyNow(id){

            var size = parseInt($('#item'+id+'_size').val());
            if(size == 1){
                summarize_order(id);
            }

            $('#btn-buy-now-'+id).css('display','block');
            $('#btn-add-to-cart-'+id).css('display','none');
            $('#add-to-cart-modal-'+id).modal('show');
        }

        function addCart(id){

            var size = parseInt($('#item'+id+'_size').val());
            if(size == 1){
                summarize_order(id);
            }

            $('#btn-buy-now-'+id).css('display','none');
            $('#btn-add-to-cart-'+id).css('display','block');
            $('#add-to-cart-modal-'+id).modal('show');
        }

        function misc_total(id,x){
            var tot = parseFloat($('#misc_price'+id+'_'+x).val()) * parseFloat($('#misc_qty'+id+'_'+x).val());
            $('#misc_td_'+id+'_'+x).html(addCommas(parseFloat(tot).toFixed(2)));
            summarize_order(id);
        }

        function misc_remove(id,x){
            var result = confirm("Are you sure you want to delete this item?");
            if (result) {
                $('#misc_tr'+id+'_'+x).remove();
                summarize_order(id);
            }
        }

        function add_misc(id){
            
            if($("#misc"+id).val() == ""){
                alert('Please select a product.');
                return false;
            }

            $('#misc_div'+id).show();
            var a = ($("#misc"+id).val()).split('|');


            var misc_cntr = parseInt($('#misc_cntr'+id).val()) + 1;

            $('#misc_tbody'+id).append(
                '<tr id="misc_tr'+id+'_'+misc_cntr+'">'+
                    '<td><input type="hidden" value="'+a[0]+'" name="misc_id'+misc_cntr+'" id="misc_id'+misc_cntr+'"><a title="remove" href="javascript:void(0)" onclick="misc_remove('+id+','+misc_cntr+')">x</a> '+a[2]+'</td>'+
                    '<td><input type="hidden" value="'+a[1]+'" name="misc_price'+misc_cntr+'" id="misc_price'+id+'_'+misc_cntr+'">'+addCommas(parseFloat(a[1]).toFixed(2))+'</td>'+
                    '<td><input type="number" onchange="misc_total('+id+','+misc_cntr+')" id="misc_qty'+id+'_'+misc_cntr+'" name="misc_qty'+misc_cntr+'" min="1" size="3" maxlength="3" value="1" style="text-align:right;width: 60px;"></td>'+
                    '<td align="right" id="misc_td_'+id+'_'+misc_cntr+'">'+addCommas(parseFloat(a[1]).toFixed(2))+'</td>'+
                '</tr>'
            );

            $('#misc_cntr'+id).val(misc_cntr);

            //remove selected option
            var index = $('#misc'+id).get(0).selectedIndex;
            $('#misc'+id+' option:eq(' + index + ')').remove();

            summarize_order(id);
        }

        function item_weight(id){
            compute_item(id);
        }

        function item_qty(id){
            compute_item(id);
        }


        function compute_item(id){
            if ($("#item"+id).val() === "") {
                $('#order_table'+id).empty();
            }
            else{
                summarize_order(id);
            }
        }

        function item_paella(id){
            compute_item(id);
        }

        function summarize_order(id){
            var total_amount = 0;

            if ($("#item"+id).val() === "") {
                var total_amount = 0;
                $('#order_table'+id).empty();
            } else{
                $('#order_table'+id).empty();
                var a = ($("#item"+id).val()).split('|');
                
                var price_total = parseFloat(a[1]) * parseFloat($('#qty'+id).val());
                total_amount += price_total;

                $('#order_table'+id).append(
                    '<tr>'+
                        '<td><input type="hidden" value="'+a[0]+'" name="ac_item"><input type="hidden" value="'+$('#qty'+id).val()+'" name="ac_qty"><i class="fa fa-cart-plus"></i> '+a[2]+'</td>'+
                        '<td>'+addCommas(parseFloat(a[1]).toFixed(2))+' x'+$('#qty'+id).val()+'</td>'+
                        '<td align="right">'+addCommas(price_total.toFixed(2))+'</td>'+
                    '</tr>'
                );

                if($('#customCheck'+id).is(':checked')){

                    var paella = parseFloat($('#paella_price'+id).val()) * parseFloat($('#qty'+id).val());
                    $('#order_table'+id).append(
                        '<tr>'+
                            '<td><i class="fa fa-cart-plus"></i> Seafood Paella</td>'+
                            '<td>'+addCommas(parseFloat($('#paella_price'+id).val()).toFixed(2))+' x'+$('#qty'+id).val()+'</td>'+
                            '<td align="right">'+addCommas(paella.toFixed(2))+'</td>'+
                        '</tr>'
                    );

                    total_amount += paella;
                    $('#ac_paella'+id).val('1');
                } else{
                    $('#ac_paella'+id).val('0');
                }


                var total_misc_count = $('#misc_cntr'+id).val();
                for(i=1;i<=total_misc_count;i++){
                    if( $('#misc_price'+id+'_'+i).length ) {
                        total_amount += parseFloat($('#misc_price'+id+'_'+i).val()) * parseFloat($('#misc_qty'+id+'_'+i).val());
                    }
                }

                $('#total_price'+id).html(addCommas(parseFloat(total_amount).toFixed(2)));
            }

        }


        function add_to_cart(act,id){
            if ($("#item"+id).val() === "") {
                alert("Please select the item's weight!");
            } else {
                save_to_cart(act,id);
            }
            return false;
        }


        function save_to_cart(act,id) {

            let data = $('#cart_frm'+id).serialize();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                data: data,
                type: "post",
                url: "{{route('cart.add')}}",
                beforeSend: function(){
                    $("#loading-overlay").show();
                },
                success: function(returnData) {
                    $("#loading-overlay").hide();
                    if (returnData['success']) {
                        $('.cart-counter').html(returnData['totalItems']);
                        if(act == 'addcart'){
                            swal({
                                toast: true,
                                position: 'center',
                                title: "Product Added to your cart!",
                                type: "success",
                                showCancelButton: true,
                                cancelButtonColor: "#DD6B55",
                                timerProgressBar: true,
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "View Cart",
                                cancelButtonText: "Continue Shopping",
                                closeOnConfirm: false,
                                closeOnCancel: false
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    window.location.href = "{{route('kiosk.cart')}}";
                                } else {
                                    window.location.href = "{{route('kiosk.menu')}}";
                                }
                            });
                        }
                        else{
                            window.location.href = "{{route('kiosk.cart')}}";
                        }
                    }
                },
                failed: function() {
                    $("#loading-overlay").hide();
                }
            });
        }

        function addCommas(nStr){
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }
    </script>
@endsection
