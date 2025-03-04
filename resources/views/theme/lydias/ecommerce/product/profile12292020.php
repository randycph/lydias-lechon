@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
   <style>
       .delivery-header__title {
            display: table-cell;
            font-size: 14px;
            color: #757575;
            font-weight: 500;
            width: 300px;
        }
        .delivery-body {
            font-size: 12px;
            color: #757575;
            font-weight: 500;
        }
        .delivery-body table{
            width: 100%;
       
        }       

        .jun {
            position: absolute;            
            bottom:   10px;
            right:60px;
        }

        .other_details label{
            color:gray;
            font-family: lato, sans-serif, Arial ;
        }

        #loading-overlay {
            position: absolute;
            width: 100%;
            height:180%;
            left: 0;
            top: 0;
            display: none;
            align-items: center;
            background-color: #000;
            z-index: 999;
            opacity: 0.5;
        }
        .loading-icon{ position:absolute;border-top:2px solid #fff;border-right:2px solid #fff;border-bottom:2px solid #fff;border-left:2px solid #767676;border-radius:25px;width:25px;height:25px;margin:0 auto;position:absolute;left:50%;margin-left:-20px;top:50%;margin-top:-20px;z-index:4;-webkit-animation:spin 1s linear infinite;-moz-animation:spin 1s linear infinite;animation:spin 1s linear infinite;}
        @-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
        @-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
        @keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
       
   </style>
@endsection

@section('content')
    @php

        $sizes = \App\Product::detail($product->name);
        $misc = \App\Product::misc();
    @endphp
    <main>
        <section id="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-wrap">
                        <div class="gap-70"></div>
                            <div class="row">
                                
                                <div class="col-lg-4">
                                    <div class="food-img">
                                        @if ($product->photoPrimary == '0/no-image-available.jpg')
                                            <img src="{{ asset('storage/products/'.$product->photoPrimary) }}" />
                                        @else
                                            <img src="{{ asset('storage/products/'.$product->photoPrimary) }}" xoriginal="{{ asset('storage/products/'.$product->photoPrimary) }}" />
                                        @endif                                        
                                    </div>
                                    <div style="display:none;" class="sharethis-inline-share-buttons"></div>
                                </div>

                                <div class="col-lg-8">   
                                    
                                    <h3>
                                        {{$product->name}}
                                        <br>
                                        
                                    </h3>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="cart-box">
                                                <div class=""> 

                                                    <div class="form-group">
                                                        <label>Quantity</label>
                                                        <div class="input-group qty-spin">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="btn btn-secondary btn-number minus-btn" disabled="disabled" data-type="minus" data-field="qty">-</button>
                                                            </div>
                                                            <input type="text" name="qty" id="qty" class="form-control input-number quantity-fld text-center" value="1" min="1" max="1000">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-secondary btn-number plus-btn" data-type="plus" data-field="qty">+</button>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    @if($sizes->count() > 1)
                                                        <div class="form-group">
                                                            <label class="d-block">Weight</label>
                                                            <select class="custom-select" id="item" placeholder="Select Weight" name="item">
                                                                <option value="" selected style="color:gray">Select..</option>
                                                                @forelse($sizes as $d)
                                                                    <option value="{{$d->id}}|{{$d->price}}|{{$d->weight}}" data-value="{{$d->price}}">{{$d->weight}} / {{$d->no_of_pax}}</option>
                                                                @empty
                                                                @endforelse

                                                            </select>
                                                        </div> 

                                                         
                                                    @else
                                                        <select class="custom-select" style="display:none;" id="item" name="item">
                                                            @forelse($sizes as $d)
                                                                <option value="{{$d->id}}|{{$d->price}}|{{$d->weight}}" selected="selected" data-value="{{$d->price}}">{{$d->weight}} / {{$d->no_of_pax}}</option>
                                                            @empty
                                                            @endforelse
                                                        </select>
                                                    @endif

                                                    @if($product->paella_price > 0)
                                                        <div class="form-group">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="hidden" name="paella_price" id="paella_price" value="{{$product->paella_price}}">
                                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                                <label class="custom-control-label" for="customCheck1">Add Seafood Paella</label>
                                                            </div>
                                                        </div>  
                                                    @endif
 

                                                    

                                                    <div class="form-group">
                                                        <label class="d-block">Add Misc</label>
                                                        <form id="misc_form">
                                                            <div class="input-group mb-3">
                                                                <select class="form-control custom-select" id="misc" placeholder="Select.." required="required" name="misc">
                                                                    <option value="" selected style="color:gray">Select Products</option>
                                                                    @forelse($misc as $m)
                                                                        <option value="{{$m->id}}|{{$m->price}}|{{$m->name}}" data-value="{{$m->price}}">{{$m->name}}</option>
                                                                    @empty
                                                                    @endforelse
                                                                </select>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-info" type="submit" id="add_misc">Add</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <span class="text-danger" id="misc_help" style="display:none;">Select misc item from the list.</span>
                                                    </div> 

                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{route('cart.add')}}" method="post" id="cart_frm">
                                                @csrf
                                                <input type="hidden" value="0" name="ac_paella" id="ac_paella">
                                                <input type="hidden" value="addcart" name="action" id="action">
                                                <div class="cart-box">
                                                    <div class="delivery">
                                                        <div class="delivery-header__title">
                                                            Order Summary
                                                        </div>
                                                        <input type="hidden" id="misc_cntr" name="misc_cntr" value="0">
                                                        <div class="delivery-body">
                                                            <table>

                                                                <thead>
                                                                    <tr>
                                                                        <th>Product</th>
                                                                        <th>Price</th>
                                                                        <th align="right">Total</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody id="order_table">
                                                                 
                                                                </tbody>
                                                                
                                                            </table>
                                                            <div class="gap-30"></div>
                                                            <div id="misc_div" style="display:none;">
                                                                <div class="delivery-header__title">
                                                                    Misc Items
                                                                </div>
                                                                <table id="misc_table">                                                            
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Product</th>
                                                                            <th>Price</th>
                                                                            <th>Qty</th>
                                                                            <th>Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="misc_tbody">
                                                                        
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="border-top my-3"></div>
                                                            <p class="" style="font-style:italic;font-size:12px;">Note: Delivery charge will be calculated at checkout</p>
                                                            <p class="text-right price" style="margin-bottom:40px;">&#8369; <span id="total_price">0.00</span></p>
                                                        </div>
                                                        @if($product->for_sale > 0)
                                                        <div class="jun" style="text-align:center">
                                                            <button type="button" id="buynowbtn" class="btn btn-warning"><i class="fa fa-handshake"></i> Buy Now</button>
                                                            <button type="button" id="addcartbtn" class="btn btn-primary"><i class="fa fa-cart-plus"></i> Add to Cart</button>
                                                  
                                                        </div>
                                                        @endif
                                                    </div>
                                                    
                                                </div>
                                            </form>
                                        </div>
                                    </div>                                 
                                    
                                </div>
                            </div>
                         
                        </div>
                        <div class="gap-30"></div>
                        <div class="card" style="display:none;">
                            <div class="card-header">
                                Product Details
                            </div>
                            <div class="card-body">
                                <p align="justify">{!! $product->description !!}</p>
                                <div class="border-top my-3"></div>
                                <h5>Other Details</h5>
                                <div class="form-horizontal other_details" role="form">
                                    <div class="form-body">  
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-12">Unit of Measurement:</label>
                                                    <div class="col-md-12">
                                                        <p class="form-control-static"> {{ $product->uom }} </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-12">Product Code:</label>
                                                    <div class="col-md-12">
                                                        <p class="form-control-static"> {{ $product->code }} </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-12">Tags:</label>
                                                    <div class="col-md-12">
                                                        <p class="form-control-static">   
                                                            @forelse($product->tags as $tag)
                                                                @if ($loop->last)
                                                                    {{$tag->tag}}
                                                                @else
                                                                    {{$tag->tag}},&nbsp;
                                                                @endif
                                                            @empty
                                                            @endforelse 
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        
                                    </div>
                                    
                                </div>
                                <!-- END FORM-->
                              
                            </div>
                            
                        </div>
                        <div class="gap-30"></div>

                       
                    </div>
                </div>
            </div>
        </section>
    </main>
    <div id="loading-overlay">
        <div class="loading-icon"></div>
    </div>  
    @if((auth()->check()))
        <input type="hidden" id="utype" value="{{Auth::user()->user_type}}">
    @endif
@endsection

@section('jsscript')
     
    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5e71c70cebeba800120faa31&product=inline-share-buttons" async="async"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>
        $( document ).ready(function() {
            
            @if($sizes->count() == 1)
                summarize_order();
            @endif
        });
        function misc_total(x){
            var tot = parseFloat($('#misc_price'+x).val()) * parseFloat($('#misc_qty'+x).val());
            $('#misc_td'+x).html(addCommas(parseFloat(tot).toFixed(2)));
            summarize_order();
        }

        function misc_remove(x){   
            var result = confirm("Are you sure you want to delete this item?");
            if (result) {
                $('#misc_tr'+x).remove();
                summarize_order();
            }         
            
        }
        $('#misc_form').submit(function(e){
            e.preventDefault();  
            $('#misc_div').show();
            var a = ($("#misc").val()).split('|');
            var misc_cntr = parseInt($('#misc_cntr').val()) + 1;

            $('#misc_tbody').append('<tr id="misc_tr'+misc_cntr+'">'+                                       
                                        '<td><input type="hidden" value="'+a[0]+'" name="misc_id'+misc_cntr+'" id="misc_id'+misc_cntr+'"><a title="remove" href="javascript:void(0)" onclick="misc_remove('+misc_cntr+')">x</a> '+a[2]+'</td>'+
                                        '<td><input type="hidden" value="'+a[1]+'" name="misc_price'+misc_cntr+'" id="misc_price'+misc_cntr+'">'+addCommas(parseFloat(a[1]).toFixed(2))+'</td>'+
                                        '<td><input type="number" onchange="misc_total('+misc_cntr+')" id="misc_qty'+misc_cntr+'" name="misc_qty'+misc_cntr+'" min="1" size="3" maxlength="3" value="1" style="text-align:right;width: 60px;"></td>'+
                                        '<td align="right" id="misc_td'+misc_cntr+'">'+addCommas(parseFloat(a[1]).toFixed(2))+'</td>'+
                                    '</tr>');
            
            $('#misc_cntr').val(misc_cntr);
            
            //remove selected option
                var index = $('#misc').get(0).selectedIndex;
                $('#misc option:eq(' + index + ')').remove();

            summarize_order();
        });

        $('#qty').change(function(){            
           compute_item();
        });

        $('#customCheck1').change(function(){
            compute_item();
        });

        $('#item').change(function(){            
            compute_item();
        });

        function compute_item(){
            
            if ($("#item").val() === "") {
                $('#order_table').html('');     
            }
            else{ 
                summarize_order();  
            }
            
        }

        function summarize_order(){
            var total_amount = 0;
            if ($("#item").val() === "") {
                $('#order_table').html('');     
            }
            else{

                var a = ($("#item").val()).split('|');
                var price_total = parseFloat(a[1]) * parseFloat($('#qty').val());   
                total_amount += price_total;
                $('#order_table').html('<tr>'+                                       
                                            '<td><input type="hidden" value="'+a[0]+'" name="ac_item"><input type="hidden" value="'+$('#qty').val()+'" name="ac_qty"><i class="fa fa-cart-plus"></i> '+a[2]+'</td>'+
                                            '<td>'+addCommas(parseFloat(a[1]).toFixed(2))+' x'+$('#qty').val()+'</td>'+                                    
                                            '<td align="right">'+addCommas(price_total.toFixed(2))+'</td>'+
                                        '</tr>');

                if($('#customCheck1').is(':checked')){

                    var paella = parseFloat($('#paella_price').val()) * parseFloat($('#qty').val());   
                    $('#order_table').append('<tr>'+                                       
                                            '<td><i class="fa fa-cart-plus"></i> Seafood Paella</td>'+
                                            '<td>'+addCommas(parseFloat($('#paella_price').val()).toFixed(2))+' x'+$('#qty').val()+'</td>'+                                    
                                            '<td align="right">'+addCommas(paella.toFixed(2))+'</td>'+
                                        '</tr>');
                    total_amount += paella;
                    $('#ac_paella').val('1');
                }
                else{
                    $('#ac_paella').val('0');
                }

                var total_misc_count = $('#misc_cntr').val();
                for(i=1;i<=total_misc_count;i++){
                    if( $('#misc_price'+i).length ) {
                        total_amount += parseFloat($('#misc_price'+i).val()) * parseFloat($('#misc_qty'+i).val());                    
                    }
                }

                $('#total_price').html(addCommas(parseFloat(total_amount).toFixed(2)));
                
            }

        }
    </script>

    <script>
        $(document).on("keydown", "#cart_frm", function(event) { 
            return event.key != "Enter";
        });
        $(function() {         
            $('#addcartbtn').click(function() {                
                if ($("#item").val() === "") {
                    alert("Please select the item's weight!");
                } else {
                    add_to_cart('addcart');
                }
                return false;
            });

            $('#buynowbtn').click(function() {                
                if ($("#item").val() === "") {
                    alert("Please select the item's weight!");
                } else {
                    add_to_cart('buynow');
                }
                return false;
            });
        });

        function add_to_cart(act) {
            if($('#utype').length){
                var uty = $('#utype').val();
                if(uty != 'customer'){
                    alert('You are logged in as CMS user. Please use a customer account to complete this transaction');
                    return false;
                }
            }
            //$("#loading-overlay").show();
            // alert('submitted');
            $('#action').val(act);
            let data = $('#cart_frm').serialize();
            console.log(data);
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
                        if(returnData['act'] == 'addcart'){
                            swal({
                                toast: true,
                                position: 'center',
                                title: "Product Added to your cart!",
                                type: "success",
                                showCancelButton: true,
                                timerProgressBar: true,
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "View Cart",
                                cancelButtonText: "Continue Shopping",
                                closeOnConfirm: false,
                                closeOnCancel: false
                                // onOpen: (toast) => {
                                //     toast.addEventListener('mouseenter', Swal.stopTimer)
                                //     toast.addEventListener('mouseleave', Swal.resumeTimer)
                                // }
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    //swal("Deleted!", "Your imaginary file has been deleted.", "success");
                                    window.location.href = "{{route('cart.front.show')}}";
                                } else {
                                    window.location.href = "{{route('product.front.show_forsale')}}";
                                    //swal("Cancelled", "Your imaginary file is safe :)", "error");
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

        function capitalizeFirstLetter(word) {
            word = word.toLowerCase();
            return word.charAt(0).toUpperCase() + word.slice(1);
        }
    </script>

    
@endsection
