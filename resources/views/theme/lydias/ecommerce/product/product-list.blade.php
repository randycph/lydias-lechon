@extends('theme.'.config('app.frontend_template').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/lydias/plugins/ion.rangeslider/css/ion.rangeSlider.min.css') }}" />
@endsection

@section('content')
    <main>
        <section id="listing-wrapper">
            <div class="container">
                <div class="row">
                    <div id="col1" class="col-lg-3">
                        <h2 class="listing-title">Products</h2>
                        <nav class="rd-navbar rd-navbar-listing">
                            <div class="listing-filter-wrap">
                                <div class="rd-navbar-listing-close-toggle rd-navbar-static--hidden toggle-original"><span
                                        class="lnr lnr-cross"></span> Close</div>
                                @if ($categories->count())
                                    <h3 class="listing-category-title">Categories</h3>
                                    <div class="side-menu menu-category">
                                        <ul class="listing-category">
                                            @foreach ($categories as $category)
                                                <li @if(request()->has('criteria') && $category->id == $selectedCategory) class="active" @endif>
                                                    <a href="{{ route('product.front.list') }}?type=category&criteria={{$category->id}}">{{$category->name}}</a>
                                                    @php $subCategories = $category->child_categories; @endphp
                                                    @if ($subCategories && $subCategories->count())
                                                        <ul>
                                                            @foreach ($subCategories as $subCategory)
                                                                <li @if(request()->has('criteria') && $subCategory->id == $selectedCategory) class="active" @endif>
                                                                    <a href="{{ route('product.front.list') }}?type=category&criteria={{$subCategory->id}}">{{$subCategory->name}}</a>
                                                                    @php $subSubCategories = $subCategory->child_categories; @endphp
                                                                    @if ($subSubCategories && $subSubCategories->count())
                                                                        <ul>
                                                                            @foreach ($subSubCategories as $subSubCategory)
                                                                                <li @if(request()->has('criteria') && $subSubCategory->id == $selectedCategory) class="active" @endif>
                                                                                    <a href="{{ route('product.front.list') }}?type=category&criteria={{$subSubCategory->id}}">{{$subSubCategory->name}}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="gap-70"></div>
                                @endif
                                <h3 class="listing-filter-title">Price Range</h3>
                                <div class="gap-10"></div>
                                <div class="row">
                                    <div class="col-md-10"><input type="text" class="js-range-slider" id="my_range" name="my_range" value="" /></div>
                                    <div class="col-md-2"><a href="#" onclick="filter_price();" class="btn btn-xs btn-info" style="margin-top:13px;color:white;"><span class="fa fa-search"></span></a></div>
                                </div>


                                <div class="gap-70"></div>

                                <h3 class="listing-filter-title">Ratings</h3>
                                <div class="gap-10"></div>
                                <div class="rating">
                                    <a id="five-star" href="#" onclick="filter_rating(5)">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="rating-count">5</span>
                                    </a>
                                </div>
                                <div class="rating">
                                    <a id="four-star" href="#" onclick="filter_rating(4)">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star unchecked"></span>
                                        <span class="rating-count">4</span>
                                    </a>
                                </div>
                                <div class="rating">
                                    <a id="three-star" href="#" onclick="filter_rating(3)">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star unchecked"></span>
                                        <span class="fa fa-star unchecked"></span>
                                        <span class="rating-count">3</span>
                                    </a>
                                </div>
                                <div class="rating">
                                    <a id="two-star" href="#" onclick="filter_rating(2)">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star unchecked"></span>
                                        <span class="fa fa-star unchecked"></span>
                                        <span class="fa fa-star unchecked"></span>
                                        <span class="rating-count">2</span>
                                    </a>
                                </div>
                                <div class="rating">
                                    <a id="one-star" href="#" onclick="filter_rating(1)">
                                        <span class="fa fa-star checked"></span>
                                        <span class="fa fa-star unchecked"></span>
                                        <span class="fa fa-star unchecked"></span>
                                        <span class="fa fa-star unchecked"></span>
                                        <span class="fa fa-star unchecked"></span>
                                        <span class="rating-count">1</span>
                                    </a>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="col-lg-9">
                        <div class="filter-product">
                            <div class="form-row">
                                <div id="col2" class="col-6">
                                    <nav class="rd-navbar">
                                        <div class="rd-navbar-listing-toggle rd-navbar-static--hidden toggle-original"
                                             data-rd-navbar-toggle=".listing-filter-wrap"><span class="lnr lnr-list"></span> Filter</div>
                                    </nav>
                                    <div class="btn-group">
                                        <button type="button" class="btn dropdown-filter-btn dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">Show
                                                @if(isset($_GET['limit']))
                                                    {{$_GET['limit']}}
                                                @else
                                                    10
                                                @endif item/s
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" onclick="filter_limit('10')">10</a>
                                            <a class="dropdown-item" href="#" onclick="filter_limit('25')">25</a>
                                            <a class="dropdown-item" href="#" onclick="filter_limit('50')">50</a>
                                            <a class="dropdown-item" href="#" onclick="filter_limit('75')">75</a>
                                            <a class="dropdown-item" href="#" onclick="filter_limit('100')">100</a>
                                            <a class="dropdown-item" href="#" onclick="filter_limit('All')">All</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="btn-group">
                                        <p class="filter-item-count ml-auto">{{$products->count()}} Item/s found</p>
                                        <button type="button" class="btn dropdown-filter-btn dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            @if(isset($_GET['sort']))
                                                {{$_GET['sort']}}
                                            @else
                                                Sort by
                                            @endif
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" onclick="filter_sort('Price low to high')">Price low to high</a>
                                            <a class="dropdown-item" href="#" onclick="filter_sort('Price high to low')">Price high to low</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-20"></div>
                        <div class="list-product">
                            <div class="row">
                                @forelse($products as $product)
                                    @if ($product->id == 1)
                                        @continue
                                    @endif
                                    <div class="col-md-4 col-sm-6 item">
                                        <div class="product-link">
                                            <div class="product-card">
                                                <a href="{{route('product.front.show',$product->slug)}}">
                                                    <div class="product-img">
                                                        <img src="{{ asset('storage/products/'.$product->photoPrimary) }}" alt="" />
                                                    </div>
                                                    <div class="gap-10"></div>
                                                    <p class="product-title">{{$product->name}}</p>
                                                </a>
                                                <h3 class="product-price">{{$product->priceWithCurrency}}</h3>

                                                <div class="rating small">
                                                    {!!$product->ratingStar!!}
                                                </div>

                                                @if (auth()->check() && (auth()->user()->is_a_member_user() || auth()->user()->is_a_cms_user()))
                                                    <div class="prod-pv">
                                                        <p class="text-danger p-0 m-0">{{$product->point_value}} PV</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    No Product Found..
                                @endforelse

                            </div>
                        </div>

                        <ul class="pagination" style="display:none;">
                            <li class="page-item">
                                <a class="page-link" href="#" title="Back"><i class="lnr lnr-chevron-left"></i></a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3 <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#" title="Next"><i class="lnr lnr-chevron-right"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('jsscript')
<script src="{{ asset('theme/lydias/plugins/ion.rangeslider/js/ion.rangeSlider.js') }}"></script>
<script>
    $(document).ready(function () {
        $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: 0,
            max: {{$max}},
            from: 200,
            to: 500,
            grid: true
        });
    });

    function filter_price(){
        var slider = $("#my_range").data("ionRangeSlider");

        var from = slider.result.from;
        var to = slider.result.to;

        var url = "?type=price&price_start="+from+"&price_end="+to;
        return filter(url);
    }

    function filter_sort(par){

        var url = '';
        if(location.search.length > 1)
            url = location.search+"&sort="+par;
        else
            url = "?sort="+par;
        return filter(url);

    }

    function filter_limit(par){

        if(location.search.length > 1)
            url = location.search+"&limit="+par;
        else
            url = "?limit="+par;
        //var url = location.search+"&limit="+par;
        return filter(url);

    }


    function filter_category(par){

        var url = "?type=category&criteria="+par;
        return filter(url);

    }

    function filter_rating(par){
        var url = "?type=rating&criteria="+par;
        return filter(url);
    }

    function filter(filters){
        window.location.href = "{{route('product.front.list')}}"+filters;
    }

</script>
@endsection
