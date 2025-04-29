@extends('layouts.guest')

@section('content')

{{-- @php
$lists = [
    [
        'image' => 'petite.png',
        'name' => 'Petite',
        'price' => '₱9,800',
        'description' => 'Good for 8-10 persons',
        'weight' => 'Cooked Weight Approx. 3-4Kgs',
        'serving' => 'Approximate Serving 1.5ft',
        'add' => 'Add ₱2,800 for Boneless Lechon Stuffed with Seafood Paella'
    ],
    [
        'image' => 'chochinillo.png',
        'name' => 'COCHINILLO',
        'price' => '₱10,800',
        'description' => 'Good for 8-10 persons',
        'weight' => 'Cooked Weight Approx. 3-4Kgs',
        'serving' => 'Approximate Serving 2ft',
        'free' => 'FREE Mexican Flavored Rice'
    ],
    [
        'image' => 'deleche.png',
        'name' => 'De leche',
        'price' => '₱12,800',
        'description' => 'Good for 12-15 persons',
        'weight' => 'Cooked Weight Approx. 5-6Kgs',
        'serving' => 'Approximate Serving 2.5ft',
        'add' => 'Add ₱2,800 for Boneless Lechon Stuffed with Seafood Paella'
    ],
    [
        'image' => 'small.png',
        'name' => 'Small',
        'price' => '₱9,800',
        'description' => 'Good for 20-25 persons',
        'weight' => 'Cooked Weight Approx. 8-11Kgs',
        'serving' => 'Approximate Serving 3ft',
        'add' => 'Add ₱4,800 for Boneless Lechon Stuffed with Seafood Paella'
    ],
    [
        'image' => 'medium.png',
        'name' => 'Medium',
        'price' => '₱17,800',
        'description' => 'Good for 30-45 persons',
        'weight' => 'Cooked Weight Approx. 12-15Kgs',
        'serving' => 'Approximate Serving 3.5ft',
        'add' => 'Add ₱5,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'large.png',
        'name' => 'Large',
        'price' => '₱21,800',
        'description' => 'Good for 40-50 persons',
        'weight' => 'Cooked Weight Approx. 16-20Kgs',
        'serving' => 'Approximate Serving 3.5-4ft',
        'add' => 'Add ₱6,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'xlarge.png',
        'name' => 'X-Large',
        'price' => '₱24,800',
        'description' => 'Good for 60-70 persons',
        'weight' => 'Cooked Weight Approx. 21-25Kgs',
        'serving' => 'Approximate Serving 4ft',
        'add' => 'Add ₱7,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'jumbo.png',
        'name' => 'Jumbo',
        'price' => '₱30,800',
        'description' => 'Good for 100-120 persons',
        'weight' => 'Cooked Weight Approx. 26-30Kgs',
        'serving' => 'Approximate Serving 4ft-4.5ft',
        'add' => 'Add ₱8,800 for Boneless Lechon stuffed with Seafood Paella'
    ],
    [
        'image' => 'lechonbaka.png',
        'name' => 'Lechon Baka',
        'price' => '₱65,800',
        'description' => 'Good for 150-200 persons',
        'weight' => 'Cooked Weight Approx. 26-30Kgs',
        'serving' => 'Live Weight 100-120Kgs',
        'add' => 'Add ₱3,500 for Service Fee Of Delivery and Reheating'
    ]
];
@endphp --}}

    <div class="px-1 container"
        x-data="{ 
            goToAnchor(anchor) {
                const element = document.getElementById(anchor);
                if (element) {
                    const yOffset = -160;
                    const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            },
            product: null,
            show(product) {
                this.product = product;
                this.lechonCart = true;
                console.log(product);
            },
            close() {
                this.lechonCart = false;
                this.product = null;
            },
            quantity: 1,
            get baseTotal() {
                return this.product?.price * this.quantity || 0;
            },
            addons: [],
            updateAddons() {
                if (!this.product?.addon_products) return;

                this.addons = this.product.addon_products
                    .filter(a => a.selected)
                    .map(a => ({
                        id: a.id,
                        qty: 1
                    }));
            },
            get addonsTotal() {
                if (!this.product?.addon_products) return 0;

                return this.product.addon_products
                    .filter(a => a.selected)
                    .reduce((sum, a) => sum + (a.price * this.quantity), 0);
            },
            get grandTotal() {
                return this.baseTotal + this.addonsTotal;
            },
            toggleAddon(index) {
                this.addons[index].selected = !this.addons[index].selected;
            },
            format(value) {
                return new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                }).format(value);
            },
            setProduct(prod) {
                this.product = prod;
                this.quantity = 1;
                this.addons.forEach(a => a.selected = false);
                this.open = true;
            }
            
        }" 
        x-init="
            (() => {
                const params = new URLSearchParams(window.location.search);
                const anchor = params.get('s');
                if (anchor) {
                    setTimeout(() => goToAnchor(anchor), 100);

                    params.delete('s');
                    const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
                    history.replaceState(null, '', newUrl);
                }
            })()
        "
    >
        <div class="pt-20 pb-5 px-4">
            <h3 class="font-medium uppercase text-center mt-10 lg:text-2xl text-base">LECHON AND BEYOND</h3>
            <h1 class="text-4xl lg:text-7xl font-cubao font-medium text-primary text-center">a menu made for every occassion</h1>
        </div>

        {{-- @php
            $products = [
                [
                    'name' => 'Pork BBQ',
                    'image' => 'pork-bbq.png',
                    'id' => 'pork-bbq'
                ],
                [   
                    'name' => 'Whole Lechon',
                    'image' => 'whole-lechon.png',
                    'id' => 'whole-lechon'
                ],
                [
                    'name' => 'Lechon Espesyal',
                    'image' => 'lechon1kg.png',
                    'id' => 'lechon-espesyal'
                ],
                [
                    'name' => 'Lydias Family Boxes',
                    'image' => 'family-box-2.png',
                    'id' => 'lydias-family-boxes'
                ],
                [
                    'name' => 'Lechon-In-A-Box',
                    'image' => 'lechon-in-a-box.png',
                    'id' => 'lechon-in-a-box'
                ],
                [
                    'name' => 'Party Trays',
                    'image' => 'party-trays.png',
                    'id' => 'party-trays'
                ],
                [
                    'name' => 'Lechon Quick Meals',
                    'image' => 'bopis.png',
                    'id' => 'lechon-quick-meals'
                ],
                [
                    'name' => 'Lydias Bento Meals',
                    'image' => 'bento-b.png',
                    'id' => 'lydias-bento-meals'
                ],
                [
                    'name' => 'Pampagana',
                    'image' => 'chicharon-bulaklak.png',
                    'id' => 'pampagana'
                ],
                [
                    'name' => 'Meaty Espesyal',
                    'image' => 'bopis.png',
                    'id' => 'meaty-espesyal'
                ],
                [
                    'name' => 'Lechon Humba',
                    'image' => 'lechon-humba.png',
                    'id' => 'lechon-humba'
                ],
                [
                    'name' => 'Gulay ATBP.',
                    'image' => 'chapsauey.png',
                    'id' => 'gulay-atbp'
                ],
                [
                    'name' => 'Yamang Dagat',
                    'image' => 'sinigang-salmon.png',
                    'id' => 'yamang-dagat'
                ],
                [
                    'name' => 'Meryeda',
                    'image' => 'pancit-con-lechon.png',
                    'id' => 'meryenda'
                ],
                [
                    'name' => 'Kanin',
                    'image' => 'rice-platter.png',
                    'id' => 'kanin'
                ],
                [
                    'name' => 'Mga Inumin',
                    'image' => 'mango-cooler.png',
                    'id' => 'mga-inumin'
                ]
            ];
        @endphp --}}

        <div class="relative px-4 py-5 lg:py-10">
            <div class="swiper swiper-menus relative">
                <div class="swiper-wrapper">
                    @foreach ($categories as $category)
                    <div @click="goToAnchor('{{ $category->slug }}')" class="swiper-slide !flex items-center justify-center p-4 flex-col !w-[140px] h-[140px]">
                        <div class="bg-secondary p-2 rounded-lg items-center w-[140px] h-[140px] flex flex-col justify-center overflow-hidden">
                            <img src="{{ asset('images/category/'.$category['image']) }}" alt="Shop {{ $category->name }}" class="cursor-pointer hover:scale-125 transition duration-300">
                        </div>
                        <div class="font-semibold text-center mt-2">{{ $category->name }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-prev-custom">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>
            <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white text-black p-3 rounded-full z-10 swiper-button-next-custom">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>

        @foreach ($categories as $category)
        <div class="mt-5 pb-5 font-cubao font-medium text-4xl lg:text-5xl text-primary px-4" id="{{ $category->slug }}">
            {{ $category->name }}
        </div>
        {{-- style="background-image: url('{{ asset('images/checkout-bg.png') }}')" --}}
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 px-2 lechons pb-10">
            @foreach ($category->products as $list)
            <div class="bg-white shadow-md  rounded-lg border-primary border lechon flex flex-col justify-between">
                <div class="p-2 flex flex-col justify-between h-full">
                    <div  class="object-cover overflow-hidden m-2 rounded-md lg:rounded-lg bg-center">
                        @if ($list->photos->count() > 0)
                                @foreach($list->photos as $photo)
                                    @if ($photo->path)
                                        <img onerror="this.onerror=null;this.src='{{ asset('images/no-image.jpg') }}';" src="{{ asset('storage/products/' . $photo->path) }}" alt="{{ $list->name }}" class="px-4 scale-110">
                                    @else
                                        <img src="{{ asset('images/no-image.jpg') }}" alt="{{ $list->name }}" class="px-4 scale-110">
                                    @endif
                                @endforeach
                            @else
                            <img src="{{ asset('images/no-image.jpg') }}" alt="{{ $list->name }}" class="px-4 scale-110">
                        @endif
                    </div>
                    <div class="mt-4 px-2">
                        <div class="text-primary text-base lg:text-2xl font-bold mt-2">₱{{ number_format($list->price, 2) }}</div>
                        <h2 class="text-left text-sm lg:text-xl mt-1 uppercase">{{ $list->name }}</h2>
                    </div>
                </div>
                
                <div class="mt-4 border-t border-primary ">
                    <button @click="show(@js($list)); console.log(@js($list))" class="text-primary px-4 py-3 lg:py-5 w-full custom-btn btn-primary text-base lg:text-xl">Add to Cart</button>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    
        <x-lechon-cart-component />

    </div>
    
    <x-footer-component />

    {{-- <script>
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
            if ($("#item"+id).val() === "") {add_to_cart
                alert("Please select the item's weight!");
            } else {
                save_to_cart(act,id);
            }
            return false;
        }


        function save_to_cart(act,id) {
            if($('#utype').length){
                var uty = $('#utype').val();
                if(uty != 'customer'){
                    swal({
                        title: '',
                        text: "You are logged in as CMS user. Please use a customer account to complete this transaction.",         
                    });
                    return false;
                }
            }

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
                                    window.location.href = "{{route('cart.front.show')}}";
                                } else {
                                    window.location.href = "{{route('menu.front.list')}}";
                                }
                            });
                        }
                        else{
                            window.location.href = "{{route('cart.front.show')}}";
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
    </script> --}}
    
@endsection
