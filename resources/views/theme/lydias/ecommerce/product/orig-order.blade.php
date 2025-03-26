@extends('theme.'.config('app.frontend_template').'.main')

@section('pagecss')
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
        /*.order-page-items .card-price span {font-size:15px;text-align:center;color:#333;}*/
        /*.nav-link {font-size:15px;text-align:center;}*/
        .card:hover {  box-shadow: 3px 3px 3px 3px #888888;; }



    </style>
@endsection
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
</div>
<span onclick="closeNav()" class="dark-curtain"></span>
@section('content')
    <div class="menu-page">
        <div class="gap-70"></div>
        <div class="container">
            <div class="row">
            @php
                $var = 0;
                $misc_total = $miscs->count();
            @endphp
            <!-- ito diagdag ko start -->
                <div class="col-12 mb-4"><span onclick="openNav()" class="filter-btn d-block d-lg-none"><i class="fa fa-arrow-left"></i> Menus</span></div>
                <div class="col-md-3">
                    <!-- nagsingit ako ng class dito class="desk-cat" start -->
                    <div class="desk-cat">
                        <div class="nav flex-column nav-pills menu-category order-page" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            @forelse($categories as $category)
                                <a class="nav-link @if($category->category_id == 1) active @endif" id="catd{$category->category_id}}-tab" data-toggle="pill" href="#cat{{$category->category_id}}" role="tab" aria-controls="#cat{{$category->category_id}}" aria-selected="true">{{$category->category->name}}</a>
                            @empty
                            @endforelse

                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-12">
                    <div class="tab-content" id="v-pills-tabContent">
                        @forelse($categories as $category)

                            <div class="tab-pane fade @if($category->category_id == 1) show active @endif" id="cat{{$category->category_id}}" role="tabpanel" aria-labelledby="cat{{$category->category_id}}-tab">
                                <div class="menu-block">
                                    <div class="row">
                                        @php
                                            $cat_products = \App\Models\Product::where('category_id',$category->category_id)->where('for_sale_web','1')->select('name')->distinct()->get();
                                        @endphp
                                        @forelse($cat_products as $product)
                                            @php
                                                $main = \App\Models\Product::info($product->name);
                                                $sizes = \App\Models\Product::detail($product->name);
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
                                            <div class="col-lg-4 col-sm-4 col-6 order-page-wrapper">
                                                @if($prod_stat == 0)
                                                    <a href="javascript:void(0);" title="Product Unavailable">
                                                        <div class="card order-page-items">
                                                            <div class="order-page-image">

                                                                @if($min_price == $max_price)
                                                                    <h4 class="card-price d-block d-lg-none"><span>&#8369;{{number_format($max_price,2)}}</span></h4>
                                                                @else
                                                                    <h4 class="card-price d-block d-lg-none"><span>&#8369;{{number_format($min_price,2)}} - &#8369;{{number_format($max_price,2)}}</span></h4>
                                                                @endif
                                                                <img src="{{ asset('storage/products/'.$main->photoPrimary) }}" style="-webkit-filter: grayscale(100%);filter: grayscale(100%);" alt="Product Unavailable">
                                                                <a class="btn btn-danger btn-sm" href="javascript:void(0)" style=" position: absolute;bottom: 15px;right: 15px;">Not Available</a>
                                                            </div>
                                                            <div class="card-body">
                                                                <h5 class="card-title">{{$main->name}}</h5><br>
                                                                @if($min_price == $max_price)
                                                                    <h4 class="card-price d-none d-lg-block"><span>&#8369;{{number_format($max_price,2)}}</span></h4>
                                                                @else
                                                                    <h4 class="card-price d-none d-lg-block"><span>&#8369;{{number_format($min_price,2)}} - &#8369;{{number_format($max_price,2)}}</span></h4>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    <a href="{{$prod_url}}">
                                                        <div class="card order-page-items">
                                                            <div class="order-page-image">

                                                                @if($min_price == $max_price)
                                                                    <h4 class="card-price d-block d-lg-none"><span>&#8369;{{number_format($max_price,2)}}</span></h4>
                                                                @else
                                                                    <h4 class="card-price d-block d-lg-none"><span>&#8369;{{number_format($min_price,2)}} - &#8369;{{number_format($max_price,2)}}</span></h4>
                                                                @endif
                                                                @if($prod_stat == 0)
                                                                    <img src="{{ asset('storage/products/'.$main->photoPrimary) }}" style="-webkit-filter: grayscale(100%);filter: grayscale(100%);" alt="Product Unavailable">
                                                                @else
                                                                    <img src="{{ asset('storage/products/'.$main->photoPrimary) }}" alt="{{$main->name}}">
                                                                @endif
                                                            </div>
                                                            <div class="card-body">
                                                                <h5 class="card-title">{{$main->name}}</h5><br>
                                                                @if($min_price == $max_price)
                                                                    <h4 class="card-price d-none d-lg-block"><span>&#8369;{{number_format($max_price,2)}}</span></h4>
                                                                @else
                                                                    <h4 class="card-price d-none d-lg-block"><span>&#8369;{{number_format($min_price,2)}} - &#8369;{{number_format($max_price,2)}}</span></h4>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endif
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="gap-70"></div>
    </div>
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
            //compute(100);
        });
        // $('#inputGroupSelect01').on('change',function(){
        //  compute();
        // });
        // $('#qty').on('change',function(){
        //  compute();
        // });
        // $('#paella').on('change',function(){
        //  compute();
        // });

        function compute(x){
            //alert(x);
            var a = ($('#inputGroupSelect01'+x).val()).split('|');
            //alert(x+' aa '+$('#inputGroupSelect01'+x).val());
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

            //alert(price_total);
            $('#price_div'+x).html(addCommas(price_total+'.00')) ;
            $('#price_item_div'+x).html(addCommas(price_item_total+'.00'));
            $('#price_paella_div'+x).html(paella);
            $('#item_div'+x).html(addCommas(parseFloat(a[1])+'.00'));
            $('#qty_div'+x).html(qty);

            //console.log(x);
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
