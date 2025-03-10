@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
@endsection

@section('content')
    <div id="mySidenav" class="sidenav">
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    </div>
    <span onclick="closeNav()" class="dark-curtain"></span> 
    <section>
        <div class="menu-page">
            <div class="gap-70"></div>
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-4"><span onclick="openNav()" class="filter-btn d-block d-lg-none"><i class="fa fa-arrow-left"></i> Menus</span></div>
                    <div class="col-md-3">
                        <div class="desk-cat">
                            <div class="nav flex-column nav-pills menu-category" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @forelse($productCategories as $category)
                                    
                                    <a class="nav-link @if(request()->has('criteria') && $category->id == $selectedCategory) active @endif" href="{{ route('menu.front.list') }}?type=category&criteria={{$category->id}}">{{$category->name}}</a>
                                   
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-12">
                        <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-best" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                    <div class="menu-block">
                                        <div class="row">
                                            @forelse($products as $product)
                                                @php
                                                    $main = \App\Models\Product::info($product->name);
                                                @endphp
                                                @if ($product->id == 1)
                                                    @continue
                                                @endif
                                            <div class="menu-item col-md-4">
                                                <a data-toggle="modal" data-target="#{{$product->slug}}">
                                                    <img src="{{ asset('storage/products/'.$main->photoPrimary) }}" />
                                                    <p class="menu-item-title">{{$product->name}}</p>
                                                </a>
                                                <!-- Modal -->
                                                <div class="modal fade" id="{{$product->slug}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalCenterTitle">{{$product->name}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <img src="{{ asset('storage/products/'.$main->photoPrimary) }}" />
                                                                        <p>{{$product->short_description}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                                No Product Found.
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="gap-70"></div>
        </div>
    </section>

@endsection

@section('jsscript')
    <script>
        // $('#inputGroupSelect01').on('change',function(){
        // 	compute();
        // });
        // $('#qty').on('change',function(){
        // 	compute();
        // });
        // $('#paella').on('change',function(){
        // 	compute();
        // });

        function compute(x){
            var a = ($('#inputGroupSelect01'+x).val()).split('|');
            //alert(a[0]);
            var price_total = parseFloat(a[1]) * parseFloat($('#qty'+x).val());

            if($('#paella'+x).is(':checked'))
            {
                price_total += parseFloat($('#paella_price'+x).val());
            }


            $('#price_div'+x).html(addCommas(price_total+'.00')) ;
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
