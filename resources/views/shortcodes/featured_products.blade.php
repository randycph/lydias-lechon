<div class="gap-40"></div>

<h2>Featured Products</h2>
<div class="gap-20"></div>

<div class="row">
    @foreach ($featuredProducts as $product)
        <div class="col-lg-3 col-md-6 col-sm-12 item">
            <div class="product-link">
                <div class="product-card">
                    <a href="{{route('product.front.show',$product->slug)}}">
                        <div class="product-img">
                            <img src="{{ asset('storage/products/'.$product->photoPrimary) }}" alt="" />
                        </div>
                        <div class="gap-30"></div>
                        <p class="product-title">{{ $product->name }}</p>
                    </a>
                    <div class="rating small">
                        {!!$product->ratingStar!!}
                    </div>
                    <h3 class="product-price">{{$product->priceWithCurrency}}</h3>
                </div>
            </div>
        </div>
    @endforeach
</div>
