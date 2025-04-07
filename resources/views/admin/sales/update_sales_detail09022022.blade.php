@extends('admin.layouts.app')

@section('pagetitle')
    Job Order
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/prismjs/themes/prism-vs.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datextime/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lydias-admin.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">

    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">

    <style>
        .bootstrap-tagsinput .tag {
            background-color : rgb(255, 255, 255, 0.5);
            color : black;
        }
    </style>
@endsection

@section('content')

<div class="container pd-x-0">
    <div class="d-sm-flex justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page">Portal</li>
                    <li class="breadcrumb-item active" aria-current="page">Job Orders</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Update Sales Details</h4>
        </div>
    </div>
        
    <div class="row row-sm">
        <div class="col-lg-6">
            @if(auth()->user()->role_id <= 3)
                @if($dateneeded > date('Y-m-d H:i:s'))
                <form method="post" action="{{route('update_dateneeded')}}" id="updatefrm">
                    @csrf
                    <div class="order-details-place">
                        <br>
                        <input type="hidden" name="update_dateneeded_id" value="{{$salesheader->id}}">
                        <input type="hidden" name="update_dateneeded_deliverytype" value="{{$salesheader->delivery_type}}"> 
                        <h5>Old Date Needed : {{ $dateneeded }}<br>Old Location : {{ $locationed }} ( {{ $salesheader->delivery_type }} )</h5>
                        <br>

                        @if($salesheader->delivery_type == 'Door to door delivery')
                            @if(auth()->user()->has_access_to_route('sales.update_delivery_branch'))
                                <div class="form-group">
                                    <label class="d-block">Delivery Branch <span class="tx-danger">*</span></label>
                                    <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select branch to deliver" data-width="100%" name="delivery_branch" id="delivery_branch">
                                        <option value="">- Select Branch -</option>
                                        @foreach($branches_store->where('pickup_branch','1') as $b)
                                            <option @if($salesheader->delivery_branch == $b->name) selected @endif value="{{$b->name}}">{{$b->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        @endif
                        
                        @if(auth()->user()->role_id <= 2)
                        <div class="form-group">
                            <label class="d-block">Location <span class="tx-danger">*</span></label>

                            @if($salesheader->delivery_type == 'Door to door delivery')
                                <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Choose New Location" data-width="100%" name="update_dateneeded_d2d">
                                    @foreach(\App\Models\Deliverablecities::all() as $b)
                                    <option @if($b->name == $locationed) selected @endif value="{{$b->name}}">{{$b->name}}</option>
                                    @endforeach
                                    <option value="Other">Other</option>
                                </select> 
                            @elseif($salesheader->delivery_type == 'Store Pickup')
                                <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Choose New Location" data-width="100%" name="update_dateneeded_sp">
                                    @foreach(\App\EcommerceModel\Branch::all() as $b)
                                    <option @if($b->name == $locationed) selected @endif value="{{$b->name}}">{{$b->name}}</option>
                                    @endforeach
                                </select>    
                            @endif
                        </div>
                        
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="d-block">Date & Time Needed <i class="text-danger">*</i></label>
                                    <input type="text" name="update_dateneeded_date" class="form-control" placeholder="Choose Date" id="date2" value="{{$date_only}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="d-block">&nbsp;</label>
                                    <div class="input-group timepicker">
                                        <select class="selectpicker" data-style="btn btn-outline-light btn-md btn-block tx-left tx-black" title="Choose Time" data-width="100%" name="update_dateneeded_time">
                                            <option @if($time_only == '05:00') selected @endif value="05:00">05:00 AM</option>
                                            <option @if($time_only == '06:00') selected @endif value="06:00">06:00 AM</option>
                                            <option @if($time_only == '07:00') selected @endif value="07:00">07:00 AM</option>
                                            <option @if($time_only == '08:00') selected @endif value="08:00">08:00 AM</option>
                                            <option @if($time_only == '09:00') selected @endif value="09:00">09:00 AM</option>
                                            <option @if($time_only == '10:00') selected @endif value="10:00">10:00 AM</option>
                                            <option @if($time_only == '11:00') selected @endif value="11:00">11:00 AM</option>
                                            <option @if($time_only == '12:00') selected @endif value="12:00">12:00 NN</option>
                                            <option @if($time_only == '13:00') selected @endif value="13:00">01:00 PM</option>
                                            <option @if($time_only == '14:00') selected @endif value="14:00">02:00 PM</option>
                                            <option @if($time_only == '15:00') selected @endif value="15:00">03:00 PM</option>
                                            <option @if($time_only == '16:00') selected @endif value="16:00">04:00 PM</option>
                                            <option @if($time_only == '17:00') selected @endif value="17:00">05:00 PM</option>
                                            <option @if($time_only == '18:00') selected @endif value="18:00">06:00 PM</option>
                                            <option @if($time_only == '19:00') selected @endif value="19:00">07:00 PM</option>
                                            <option @if($time_only == '20:00') selected @endif value="20:00">08:00 PM</option>
                                            <option @if($time_only == '21:00') selected @endif value="21:00">09:00 PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="form-group">
                            <label class="d-block">Location <span class="tx-danger">*</span></label>
                            @if($salesheader->delivery_type == 'Door to door delivery')
                                <input type="text" name="update_dateneeded_d2d" class="form-control" value="{{$locationed}}" style="pointer-events: none;background-color:#E9ECEF">
                            @elseif($salesheader->delivery_type == 'Store Pickup')
                                <input type="text" name="update_dateneeded_sp" class="form-control" value="{{$locationed}}" style="pointer-events: none;background-color:#E9ECEF">
                            @endif
                        </div>
                        
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="d-block">Date & Time Needed <i class="text-danger">*</i></label>
                                    <input type="text" name="update_dateneeded_date" class="form-control" value="{{$date_only}}" style="pointer-events: none;background-color:#E9ECEF">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" title="{{\Carbon\Carbon::parse($time_only)->format('h:i A')}}">
                                    <label class="d-block">&nbsp;</label>
                                    <div class="input-group timepicker">
                                        <input type="text" name="update_dateneeded_time" class="form-control" value="{{\Carbon\Carbon::parse($time_only)->format('H:i')}}" style="pointer-events: none;background-color:#E9ECEF">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($salesheader->delivery_type == 'Door to door delivery')
                        <div class="form-group">
                            <label class="d-block">Delivery Address <span class="tx-danger">*</span></label>
                            <textarea name="new_delivery_address" class="form-control" rows="5" @if(auth()->user()->role_id > 2) style="pointer-events: none;background-color:#E9ECEF" @endif>{{ $salesheader->customer_delivery_adress }}</textarea>
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="d-block">Instruction <span class="tx-danger">*</span></label>
                            <textarea name="new_instruction" class="form-control" @if(auth()->user()->role_id > 2) style="pointer-events: none;background-color:#E9ECEF" @endif>{{ $salesheader->instruction }}</textarea>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save Changes</button>
                        </div>
                    </div>
                </form>
                @endif
            @endif
            <br>
            <form action="{{route('sales-transaction.update_items')}}" method="post">
                @csrf
                <input type="hidden" name="ui_sales_id" value="{{$salesheader->id}}">
                <input type="hidden" name="ui_total_new" id="ui_total_new" value="0">

                <div class="item-details-place">
                    <table class="table table-bordered">
                        <thead>
                            <th width="5%"></th>
                            <th width="25%">Name</th>
                            <th width="15%">Qty</th>
                            <th>Paella</th>
                            <th>Price</th>
                            <th>Total</th>
                        </thead>
                        <tbody id="ui_body">        
                            @foreach($salesheader->items as $item)
                                <tr id="ui_tr{{$item->id}}">
                                    <td><input type="hidden" name="product[]" value="{{$item->product_id}}"><a href="#" class="btn btn-xs btn-danger" onclick="ui_removeitem('ui_tr{{$item->id}}');">x</a></td>
                                    <td>{{$item->product_name}}<input name="uia_product{{$item->id}}" value="{{$item->product_id}}" type="hidden"></td>
                                    <td>
                                        <input type="number" class="form-control uiu_qty" title="{{$item->id}}" onchange="ui_change_qty('uiu',{{$item->id}});" name="uiu_qty{{$item->id}}" min="0" id="uiu_qty{{$item->id}}" value="{{number_format($item->qty,0)}}">
                                    </td>
                                    <td>
                                        @if($item->product->paella_price > 0)
                                            <input type="checkbox" onchange="ui_change_qty('uiu',{{$item->id}});" value="{{$item->product->paella_price}}" name="uiu_paella{{$item->id}}" id="uiu_paella{{$item->id}}" @if($item->paella_price > 0) checked="checked" @endif> {{number_format($item->product->paella_price,2)}}
                                        @endif
                                    </td>   
                                    <td>
                                        {{number_format($item->price,2)}}
                                        <input type="hidden" name="uiu_price{{$item->id}}" id="uiu_price{{$item->id}}" value="{{$item->price}}">
                                    </td> 
                                    <td>
                                        <span id="uiu_total{{$item->id}}">{{number_format($item->gross_amount,2)}}</span>
                                        <input type="hidden" name="uiu_subtotal{{$item->id}}" id="uiu_subtotal{{$item->id}}" value="{{$item->gross_amount}}">
                                    </td> 
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h6>Add Item</h6>
                    <table>
                        <tr>
                            <td>
                                <select class="form-control" id="ui_product">
                                    <option value="">- Select -</option>
                                    @foreach($products as $product)
                                        <option value="{{$product->name}}|{{$product->price}}|{{$product->paella_price}}|{{$product->id}}|{{$product->production_item}}">{{$product->name}} - {{number_format($product->price,2)}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><a href="#" class="btn btn-sm btn-info ml-3" onclick="ui_add_product($('#ui_product').val());">Add</a></td>
                        </tr>
                    </table>
                    <br>
                    <div class="form-group">
                        <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save Changes</button>
                    </div>
                </div>
            </form>
            <a href="{{ route('sales-transaction.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Back to Sales Transaction</a>
        </div>



        
    </div>
</div>

<div class="modal effect-scale" id="prompt-product-validation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="prompt_msg"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/prismjs/prism.js') }}"></script>
    <script src="{{ asset('lib/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('lib/typeahead.js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

    <script src="{{ asset('lib/datextime/moment.min.js') }}"></script>
    <script src="{{ asset('lib/datextime/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('customjs')

    <script>
        $("#updatefrm").submit(function() {
            if($('#delivery_branch').val() == ''){
                alert('Please select Delivery Branch');
                return false;
            }
            else{
                return true;
            }
                        
        });
    /** page level plugins **/
        $('.select2').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search options'
        });
        
        $(function() {
            $('.selectpicker').selectpicker();
        });

        var dateToday = new Date(); 

        $(function(){
            'use strict'

            $('#date1,#date2').datepicker({
                minDate: dateToday,
                dateFormat: 'yy-mm-dd',
            });
        });
    /** page level plugins **/

    function ui_add_product(x){  
        if(x == ''){
            alert('Please select an item.');
            return false;
        }

        var i = x.split("|");

        var arr = [];
        var c = 0;
        $("input[name='product[]']").each(function(){
            var value = $(this).val();
            arr.push(value);

            if(arr.indexOf(i[3]) < 0){
                arr.push(value);
            } else {
                $('#prompt-product-validation').modal('show');
                $('#exampleModalCenterTitle').html('Error');
                $('#prompt_msg').html('Selected product is already in the list.');

                $("#ui_product").val('').trigger('change');
                c = 1;
            }
        });

        if(parseInt(c) > 0){
            return false;
        }
        $('#ui_total_new').val(parseInt($('#ui_total_new').val())+1);
        var y = parseInt($('.uia_tr').length)+1;
     
        
        var pael = '&nbsp;<input type="hidden" value="0" name="uia_paella'+y+'" id="uia_paella'+y+'">';
        if(parseFloat(i[2]) > 0){
            var pael = '<input type="checkbox" onchange="ui_change_qty(\'uia\','+y+');" value="'+i[2]+'" name="uia_paella'+y+'" id="uia_paella'+y+'"> '+addCommas(parseFloat(i[2]).toFixed(2));
        }
        var s = '<tr id="ui_tr'+y+'" class="uia_tr">'+
            '<td><input type="hidden" name="product[]" value="'+i[3]+'"><a href="#" class="btn btn-xs btn-danger" onclick="ui_removeitem(\'ui_tr'+y+'\');">x</a></td>'+
            '<td>'+i[0]+'<input name="uia_product'+y+'" value="'+i[3]+'" type="hidden"></td>'+
            '<td>'+
                '<input type="number" onchange="ui_change_qty(\'uia\','+y+');" class="form-control" title="'+y+'" name="uia_qty'+y+'" min="0" id="uia_qty'+y+'" value="1">'+
            '</td>'+
            '<td>'+
                pael+
            '</td>'+   
            '<td>'+
                ''+addCommas(parseFloat(i[1]).toFixed(2))+''+
                '<input type="hidden" name="uia_price'+y+'" id="uia_price'+y+'" value="'+i[1]+'">'+
            '</td> '+
            '<td>'+
                '<span id="uia_total'+y+'">'+addCommas(parseFloat(i[1]).toFixed(2))+'</span>'+
                '<input type="hidden" name="uia_subtotal'+y+'" id="uia_subtotal'+y+'" value="'+i[1]+'">'+
            '</td>'+ 
        '</tr>';

        $('#ui_body').append(s);
        $("#ui_product").val('').trigger('change');
    }

    function ui_change_qty(i,x){
        var qty = $('#'+i+'_qty'+x).val();
        var price = $('#'+i+'_price'+x).val();
        var paella = 0;
        if($('#'+i+'_paella'+x).is(':checked')){
            paella = $('#'+i+'_paella'+x).val();
        }
        var subtotal = parseFloat(parseFloat(qty) * parseFloat(price)) + parseFloat(parseFloat(paella) * parseFloat(qty));
        $('#'+i+'_total'+x).html(addCommas(parseFloat(subtotal).toFixed(2)));
    }

    function ui_removeitem(tr){
        var txt;
        var r = confirm("Are you sure you want to remove this item?");
        if (r == true) {
          $('#uia_product'+tr).remove();
          $('#'+tr).remove();
        }
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
