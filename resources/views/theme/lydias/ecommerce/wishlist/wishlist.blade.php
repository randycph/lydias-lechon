@extends('theme.'.config('app.frontend_template').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/legande/plugins/vanilla-zoom/vanilla-zoom.css') }}" />
@endsection

@section('content')
    <main>
        <section id="cart-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cart-title">
                            <h2>My Wishlist</h2>
                        </div>
                        <ul class="cart-wrap">   
                            @forelse($products as $product)                         
                            <li class="item">
                                <div class="remove-item">
                                    <a href="#" class="remove">Remove <span class="lnr lnr-cross"></span></a>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-4">
                                        <div class="img-wrap">
                                            <a href="{{route('product.front.show',$product->product->slug)}}"><img src="{{ asset('storage/products/'.$product->product->photoPrimary) }}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="info-wrap">
                                            <div class="cart-description">
                                                <h3 class="cart-product-title"><a href="{{route('product.front.show',$product->product->slug)}}">{{$product->product->name}}</a></h3>
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                                                    <li class="breadcrumb-item active" aria-current="page">{{$product->product->category->name}}</li>
                                                </ol>
                                            </div>
                                            
                                            <div class="cart-info">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table>
                                                            @if($product->color)
                                                                <tr>
                                                                    <td>
                                                                        <p>Color</p>
                                                                    </td>
                                                                    <td>
                                                                        <p>{{$product->color}}</p>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @if($product->size)
                                                                <tr>
                                                                    <td>
                                                                        <p>Size</p>
                                                                    </td>
                                                                    <td>
                                                                        <p>{{$product->size}}</p>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="cart-product-price" id="total_price{{$loop->iteration}}">Php {{number_format($product->itemTotalPrice,2)}}</div>
                                                        <input type="hidden" name="price{{$loop->iteration}}" id="price{{$loop->iteration}}" value="{{$product->price}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @empty
                            @endforelse
                            
                        </ul>
                    </div>
                   
                </div>
            </div>
        </section>
    </main>
@endsection

@section('jsscript')

@endsection