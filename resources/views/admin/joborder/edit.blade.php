@extends('admin.layouts.app')

@section('pagetitle')
    Job Order
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/prismjs/themes/prism-vs.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
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
            <h4 class="mg-b-0 tx-spacing--1">Edit Job Order</h4>
        </div>
    </div>
    <form autocomplete="off" action="{{ route('joborders.update',$details->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="row row-sm">
            <div class="col-lg-6">
                <h6 class="mg-b-10 tx-semibold">Customer Details</h6>
                <div class="cs-search">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <th scope="row">Name</th>
                                    <td>{{ $details->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">E-mail</th>
                                    <td>{{ $details->customer_details->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Contact Number</th>
                                    <td>{{ $details->customer_details->contact_mobile }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Delivery Address</th>
                                    <td>{{$details->customer_delivery_adress }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h6 class="mg-t-10 mg-b-10 tx-semibold">Order Details</h6>
                <hr>
                <div class="form-group">
                    <div class="jo-products">
                        <div class="card bg-gray-100">
                            <div class="card-header"><i class="fa fa-calculator"></i> On-Screen Computation</div>
                            <div class="card-body">
                                <div>Added Products</div>
                                <table class="table table-borderless table-xs mg-b-20 bd-b">
                                    <thead>
                                        <th>Product Name</th>
                                        <th>Price/Item</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                    </thead>
                                    <tbody id="order_summary">
                                        @php
                                            $total_price_per_product = 0;
                                            $total_paella_price = 0;
                                            $total_paella_qty = 0;
                                            $total_product_qty = 0;
                                        @endphp
                                        @foreach($ordered_products as $p)
                                            @if($p->product->production_item == 1)
                                            @php
                                                $total_price_per_product += $p->price*$p->qty;
                                                $total_product_qty += $p->qty;
                                            @endphp
                                            <tr id="order_row{{$loop->iteration}}">
                                                <td>{{$p->product_name}}</td>
                                                <td class="text-right">{{number_format($p->price,2)}}</td>
                                                <td class="order_qty{{$loop->iteration}} text-center">{{number_format($p->qty,0)}}</td>
                                                <td class="order_total_price{{$loop->iteration}} text-right">PHP {{number_format($p->price*$p->qty,2)}}</td>
                                                <td style="display: none;"><input type="text" class="input-total-qty-per-product" id="product_total_qty_per_product{{$loop->iteration}}" value="{{number_format($p->qty,0)}}"/></td>
                                                <td style="display: none;"><input type="text" class="input-total-price-per-product" id="product_total_price{{$loop->iteration}}" value="{{number_format($p->price*$p->qty,2,'.','')}}"/></td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td colspan="4">
                                                    <table class="table table-borderless table-xs">
                                                        <tbody id="paella_row{{$loop->iteration}}">
                                                            @if($p->paella_qty > 0)
                                                                @php
                                                                    $total_paella_price += $p->paella_price*$p->paella_qty;
                                                                    $total_paella_qty += $p->paella_qty;
                                                                @endphp
                                                                <tr id="paella{{$loop->iteration}}">
                                                                    <td width="40%">Add On: Paella</td>
                                                                    <td width="20%" class="text-right">{{number_format($p->paella_price,2)}}</td>
                                                                    <td width="20%" class="text-center" id="paellaqty{{$loop->iteration}}">{{number_format($p->paella_qty,0)}}</td>
                                                                    <td width="20%" class="text-right" id="paellaprice{{$loop->iteration}}">PHP {{number_format($p->paella_price*$p->paella_qty,2)}}</td>
                                                                    <td style="display: none;"><input type="text" class="input_paella_price" id="paella_{{$loop->iteration}}" value="{{number_format($p->paella_price*$p->paella_qty,2,'.','')}}" /></td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div>Added Miscellaneous Products</div>
                                <table class="table table-borderless table-xs mg-b-20 bd-b">
                                    <thead>
                                        <th>Product Name</th>
                                        <th>Price/Item</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="misc_summary">
                                        <tr style="display: none;"><td><input type="text" name="total_misc" value="0" id="total_misc"></td></tr>
                                        @php
                                            $total_misc_qty = 0;
                                            $total_misc_price = 0;
                                            $misc_last_row = 0;
                                        @endphp
                                        @foreach($ordered_products as $p)
                                            @if($p->product->is_misc == 1)
                                                @php
                                                    $total_misc_qty += $p->qty;
                                                    $total_misc_price += $p->price*$p->qty;
                                                    $misc_last_row++;
                                                @endphp
                                                <input type="hidden" id="misc_product_last_row" value="{{$misc_last_row}}">
                                                <tr id="misc_row{{$loop->iteration}}">
                                                    <td style="display: none;"><input type="text" name="misc[]" value="{{$p->product_name}}"/></td>
                                                    <td>{{$p->product_name}}</td>
                                                    <td>{{number_format($p->price,2)}}</td>
                                                    <td>{{number_format($p->qty,0)}}</td>
                                                    <td class="text-right">PHP {{number_format($p->price*$p->qty,2)}}</td>
                                                    <td style="display: none;" class="text-right"><input type="text" name="misc_qty[]" value="{{number_format($p->qty,0)}}" /></td>
                                                    <td style="display: none;"><input type="text" name="misc_id[]" value="{{$p->product_id}}" /></td>
                                                    <td class="text-right"><a href="javascript:;" onclick="delete_order('{{$details->id}}','{{$p->product_id}}');" class="remove_misc">x</a></td>
                                                    <td style="display: none;"><input type="text" class="input-total-qty-per-misc-product" id="product_total_qty_per_misc_product{{$loop->iteration}}" value="{{number_format($p->qty,0)}}"/></td>
                                                    <td style="display: none;"><input type="text" class="input-total-price-per-misc-product" id="product_total_misc_price{{$loop->iteration}}" value="{{number_format($p->price*$p->qty,2,'.','')}}"/></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="table-responsive pd-b-20 on-screen-computation">
                                    <table class="table table-borderless table-xs mg-b-0 bd-b">
                                        <tbody>
                                            @php
                                                $total_qty = $total_product_qty+$total_paella_qty+$total_misc_qty;
                                                $subtotal  = $total_price_per_product+$total_paella_price;
                                                $grndtotal = $subtotal+$total_misc_price+$details->delivery_fee_amount;
                                            @endphp
                                            <tr>
                                                <th scope="row"><strong>Sub Total :</strong> </th>
                                                <td id="subtotal">PHP {{number_format($subtotal,2)}}</td>
                                                <td style="display: none;"><input type="text" id="input_subtotal" value="{{number_format($subtotal,2,'.','')}}"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Quantity :</strong></th>
                                                <td id="totalQty">{{$total_qty}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Delivery Charge :</strong></th>
                                                <td id="summary_delivery_charge">PHP {{number_format($details->delivery_fee_amount,2)}}</td>
                                                <td style="display: none;"><input type="text" id="input_delivery_charge" value="{{number_format($details->delivery_fee_amount,2,'.','')}}"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><strong>Total Misc :</strong></th>
                                                <td id="summary_total_misc">PHP {{number_format($total_misc_price,2)}}</td>
                                                <td style="display: none;"><input type="text" id="input_total_misc" value="{{number_format($total_misc_price,2,'.','')}}"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-borderless table-xs mg-b-0 bd-b">
                                        <tbody>
                                            <tr>
                                                <th scope="row"><strong>Total :</strong> </th>
                                                <td id="grand_total">PHP {{number_format($grndtotal,2)}}</td>
                                                <td style="display: none;"><input type="text" name="gross" id="summary_input_gross" value="{{number_format($grndtotal,2,'.','')}}"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form>
                    <div style="padding-bottom:0px;" class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="d-block">Select Products</label>
                                <select class="form-control select2" id="selected_product" onchange="check_if_product_already_selected();">
                                    <option></option>
                                    @foreach($products as $product)
                                        <option value="{{$product->name}}|{{$product->price}}|{{$product->paella_price}}|{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group d-flex justify-content-end mg-t-25">
                                <label class="d-block">&nbsp;</label>
                                <button type="button" class="btn btn-info btn-sm" onclick="add_more_product();">+</button>
                            </div>
                        </div>
                    </div>

                    <div id="products">
                        @php $last_row = 0; @endphp
                        @foreach($ordered_products as $p)
                            @if($p->product->production_item == 1)
                                @php $last_row++ @endphp
                                <input type="hidden" name="ordertype[]" value="old"> 
                                <input type="hidden" id="order_product_last_row" value="{{$last_row}}">
                                <div style="padding-bottom:0px;" class="row" id="row{{$loop->iteration}}">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="d-block">Product <span class="tx-danger">*</span></label>
                                            <input type="hidden" class="form-control" name="product_id[]" value="{{$p->product_id}}" readonly>
                                            <input type="text" class="form-control" value="{{$p->product_name}}" readonly>
                                            <div class="custom-control custom-checkbox mg-t-15 d-flex justify-content-end">
                                                @if($p->paella_qty > 0)
                                                    <input checked type="checkbox" onclick="add_paella('{{$loop->iteration}}','{{$p->price}}','{{$p->paella_price}}');" class="custom-control-input cb" id="cb{{$loop->iteration}}">
                                                @else
                                                    <input type="checkbox" onclick="add_paella('{{$loop->iteration}}','{{$p->price}}','{{$p->paella_price}}');" class="custom-control-input cb" id="cb{{$loop->iteration}}">
                                                @endif
                                                <label class="custom-control-label" for="cb{{$loop->iteration}}">Include Paella</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mg-t-10">
                                        <div class="form-group">
                                            <label class="d-block">Quantity <span class="tx-danger">*</span></label>
                                            <input type="number" name="order_qty[]" min="1" value="{{number_format($p->qty,0)}}" id="qty_{{$loop->iteration}}" onchange="qty_change('{{$loop->iteration}}','{{$p->price}}','{{$p->paella_price}}');" class="form-control" required>
                                            @if($p->paella_qty > 0)
                                                <input type="number" name="paella_qty[]" min="1" value="{{number_format($p->paella_qty,0)}}" id="paella_qty{{$loop->iteration}}" onchange="paella_qty_change('{{$loop->iteration}}','{{$p->price}}','{{$p->paella_price}}');" class="form-control mg-t-10" readonly>
                                            @else
                                                <input type="number" name="paella_qty[]" min="1" value="0" id="paella_qty{{$loop->iteration}}" onchange="paella_qty_change('{{$loop->iteration}}','{{$p->price}}','{{$p->paella_price}}');" class="form-control mg-t-10" readonly>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group d-flex justify-content-end">
                                            <label class="d-block">&nbsp;</label>
                                            <button type="button" onclick="delete_order('{{$details->id}}','{{$p->product_id}}');" class="btn btn-danger btn-sm remove">X</button>
                                            <span>&nbsp;</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="justify-content-between mg-b-5 text-right">
                    <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#addmiscellaneous">Add miscellaneous</a>
                    </div>
                    
                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="d-block">Delivery Date & Time <i class="text-danger">*</i></label>
                                <input required type="text" name="delivery_date" class="form-control" value="{{ date('Y-m-d',strtotime($details->delivery_date)) }}" id="date1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">&nbsp;</label>
                                <div class="input-group timepicker">
                                    <select required class="selectpicker" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select delivery time" data-width="100%" name="delivery_time" id="delivery_time" onchange="check_time($(this).val());">
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '07:00') selected @endif value="07:00">07:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '12:00') selected @endif value="12:00">12:00 NN</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '14:00') selected @endif value="14:00">02:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '17:00') selected @endif value="17:00">05:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '01:00') selected @endif value="01:00">01:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '02:00') selected @endif value="02:00">02:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '03:00') selected @endif value="03:00">03:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '04:00') selected @endif value="04:00">04:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '05:00') selected @endif value="05:00">05:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '06:00') selected @endif value="06:00">06:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '08:00') selected @endif value="08:00">08:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '09:00') selected @endif value="09:00">09:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '10:00') selected @endif value="10:00">10:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '11:00') selected @endif value="11:00">11:00 AM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '13:00') selected @endif value="13:00">01:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '15:00') selected @endif value="15:00">03:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '16:00') selected @endif value="16:00">04:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '17:00') selected @endif value="17:00">05:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '18:00') selected @endif value="18:00">06:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '19:00') selected @endif value="19:00">07:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '20:00') selected @endif value="20:00">08:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '21:00') selected @endif value="21:00">09:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '22:00') selected @endif value="22:00">10:00 PM</option>
                                        <option @if(date('H:i',strtotime($details->delivery_date)) == '23:00') selected @endif value="23:00">11:00 PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Order Type <span class="tx-danger">*</span></label>
                        <select required class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select order type" data-width="100%" name="order_type">
                            <option @if($details->order_type == 'Whole') selected @endif value="Whole">Whole</option>
                            <option @if($details->order_type == 'Reserved') selected @endif value="Reserved">Reserved</option>
                            <option @if($details->order_type == 'Additional') selected @endif value="Additional">Additional</option>
                        </select>
                    </div>

                    <div class="delivery-pickup-place">
                        <div class="form-group">
                            <label class="d-block">Delivery Type <span class="tx-danger">*</span></label>
                            <select required class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select delivery type" data-width="100%" id="delivery_type" name="delivery_type">
                                <option @if($details->delivery_type == 'door-to-door') selected @endif value="1">Door to door</option>
                                <option @if($details->delivery_type == 'pick-up at store') selected @endif value="2">Pick-up at store</option>
                            </select>
                        </div>
                        
                        
                            <div class="form-group" id="outlet_div" style="@if($details->delivery_type == 'door-to-door') @else display:none; @endif">
                                <label class="d-block">Outlet <span class="tx-danger">*</span></label>
                                <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select delivery/pick-up details" data-width="100%" id="outlet_rate" name="outlet">
                                    @foreach($branches as $b)
                                    <option @if($details->outlet == $b->name) selected @endif value="{{$b->name}}|{{$b->rate}}">{{$b->name}}</option>
                                    @endforeach
                                    <option @if($details->outlet == 'others') selected @endif value="others|0">Others</option>
                                </select>
                            </div>
                        
                        @if($details->outlet == 'others')
                        <div class="form-group" id="other_outlet_div">
                            <input type="text" class="form-control" name="other_outlet" id="other_outlet" value="{{$details->outlet}}">    
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="d-block">Delivery Charge <span class="tx-danger">*</span></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Php</div>
                                </div>
                                <input readonly type="number" name="delivery_charge" class="form-control text-right" id="set_delivery_charge" value="{{ number_format($details->delivery_fee_amount,2) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Remarks *</label>
                        <textarea required name="instruction" class="form-control" rows="2">{{$details->instruction}}</textarea>
                    </div>
                </form>
            </div>

            <div class="col-lg-12 mg-t-20 mg-b-30">
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save Changes</button>
                <a  href="{{route('joborders.index')}}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
            </div>
        </div>
    </form>
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

<div class="modal effect-scale" id="prompt-delete-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post" action="{{route('order-product-delete')}}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="header_id" id="delete_header_id">
                    <input type="hidden" name="product_id" id="delete_product_id">
                    <p>You are about to remove this product from you order. Do you want to continue?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger">Yes, Remove</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div> 
            </form>
        </div>
    </div>
</div>

@include('admin.joborder.modal')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
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
    /** page level plugins **/
        $('.select2').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search options'
        });

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });
        
        $(function() {
            $('.selectpicker').selectpicker();
        });

        $(document).ready(function() {
            $('input[type="radio"]').click(function() {
                var inputValue = $(this).attr("value");
                var targetBox = $("." + inputValue);
                $(".box").not(targetBox).hide();
                $(targetBox).show();
            });
        });

        var dateToday = new Date(); 

        $(function(){
            'use strict'

            $('#date1').datepicker({
                minDate: dateToday,
                dateFormat: 'yy-mm-dd',
            });
        });
    /** page level plugins **/
    </script>

    <script>
    /** form validations **/
        function check_time(time){
            var arr_time = [ '23:00','01:00','02:00','03:00','04:00','05:00'];

            if(time == '23:00'){
                t = '11:00 PM';
            } else {
                t = time+' AM';
            }
            if($.inArray(time,arr_time) != -1){
                $('#prompt-product-validation').modal('show');
                $('#exampleModalCenterTitle').html('REMINDER')
                $('#prompt_msg').html("The time you have selected is not within the delivery hours of our stores. Are you sure you want to deliver your order at "+t+" ?");

                $("#delivery_time").val('').trigger('change');
            } else {
            }
        }
    /** form validations **/

    /** functions and validation of selecting products **/
        var n = $('#order_product_last_row').val();
        function add_more_product(){
            n++;
            var selected_product = $('#selected_product').val();
            var product = selected_product.split('|');

            if(selected_product.length > 0){
                if(product[2] > 0){

                    $('#products').append('<div style="padding-bottom:0px;" class="row" id="row'+n+'">'+
                        '<div class="col-md-8">'+
                            '<div class="form-group">'+
                                '<label class="d-block">Product <span class="tx-danger">*</span></label>'+
                                '<input type="hidden" name="ordertype[]" value="new">'+
                                '<input type="hidden" class="form-control" name="product_id[]" value="'+product[3]+'" readonly>'+
                                '<input type="text" class="form-control" value="'+product[0]+'" readonly>'+
                                '<div class="custom-control custom-checkbox mg-t-15 d-flex justify-content-end">'+
                                    '<input type="checkbox" onclick="add_paella('+n+','+product[1]+','+product[2]+');" class="custom-control-input cb" id="cb'+n+'">'+
                                    '<label class="custom-control-label" for="cb'+n+'">Include Paella</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2 mg-t-10">'+
                            '<div class="form-group">'+
                                '<label class="d-block">Quantity <span class="tx-danger">*</span></label>'+
                                '<input type="number" name="order_qty[]" min="1" value="1" id="qty_'+n+'" onchange="qty_change('+n+','+product[1]+','+product[2]+');" class="form-control" required>'+
                                '<input type="number" name="paella_qty[]" min="1" value="0" id="paella_qty'+n+'" onchange="paella_qty_change('+n+','+product[1]+','+product[2]+');" class="form-control mg-t-10" readonly>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<div class="form-group d-flex justify-content-end">'+
                                '<label class="d-block">&nbsp;</label>'+
                                '<button type="button" id="'+n+'" class="btn btn-danger btn-sm remove">X</button>'+
                                '<span>&nbsp;</span>'+
                            '</div>'+
                        '</div>'+
                    '</div>');
                } else {
                    $('#products').append('<div style="padding-bottom:0px;" class="row" id="row'+n+'">'+
                        '<div class="col-md-8">'+
                            '<div class="form-group">'+
                                '<label class="d-block">Product <span class="tx-danger">*</span></label>'+
                                '<input type="hidden" class="form-control" name="product_id[]" value="'+product[3]+'" readonly>'+
                                '<input type="text" class="form-control" value="'+product[0]+'" readonly>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<div class="form-group">'+
                                '<label class="d-block">Quantity <span class="tx-danger">*</span></label>'+
                                '<input type="number" name="order_qty[]" min="1" value="1" id="qty_'+n+'" onchange="qty_change('+n+','+product[1]+','+product[2]+');" class="form-control" required>'+
                                '<input type="hidden" name="paella_qty[]" value="0" class="form-control mg-t-10">'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<div class="form-group d-flex justify-content-end mg-t-25">'+
                                '<label class="d-block">&nbsp;</label>'+
                                '<button type="button" id="'+n+'" class="btn btn-danger btn-sm remove">X</button>'+
                            '</div>'+
                        '</div>'+
                    '</div>');
                }
                
                $('#order_summary').append('<tr id="order_row'+n+'">'+
                    '<td>'+product[0]+'</td>'+
                    '<td class="text-right">'+FormatAmount(product[1],2)+'</td>'+
                    '<td class="order_qty'+n+' text-center">1</td>'+
                    '<td class="order_total_price'+n+' text-right">PHP '+FormatAmount(product[1],2)+'</td>'+
                    '<td style="display:none;"><input type="text" class="input-total-qty-per-product" id="product_total_qty_per_product'+n+'" value="1"/></td>'+
                    '<td style="display:none;"><input type="text" class="input-total-price-per-product" id="product_total_price'+n+'" value="'+product[1]+'"/></td>'+
                '</tr>'+
                '<tr>'+
                    '<td colspan="4">'+
                        '<table class="table table-borderless table-xs">'+
                            '<tbody id="paella_row'+n+'"></tbody>'+
                        '</table>'+
                    '</td>'+
                '</tr>');

                $("#selected_product").val('').trigger('change');
                calculate_sub_total();

            } else {
                $('#prompt-already-selected').modal('show');
            }
        }

        $(document).on('click', '.remove', function(){
            var btn_id = $(this).attr("id");
            $('#row'+btn_id+'').remove();
            $('#order_row'+btn_id+'').remove();
            $('#paella'+btn_id+'').remove();

            calculate_sub_total();
            calculate_grand_total();
        });

        function delete_order(headerId,productId){
            $('#prompt-delete-order').modal('show')
            $('#delete_product_id').val(productId);
            $('#delete_header_id').val(headerId); 
        }

        function check_if_product_already_selected() {
            var a = $('#selected_product').val();
            var x = a.split('|');

            var arr = [];
            $("input[name='product[]']").each(function(){
                var value = $(this).val();
                arr.push(value);

                if(arr.indexOf(x[0]) < 0){
                    arr.push(value);
                } else {
                    $('#prompt-product-validation').modal('show');
                    $('#prompt_msg').html('Selected product is already in the list.');

                    $("#selected_product").val('').trigger('change');
                }
            });
        }
    /** functions and validation of selecting products **/

    /** miscellaneous functions **/

        function add_misc_product(){
            var mics_product = $('#misc_products').val();

            var product = mics_product.split('|');

            $('#product_id').val(product[2]);
            $('#product_name').val(product[0]);
            $('#product_price').val(product[1]);
            $('#total_misc_price').html(FormatAmount(product[1],2));

            var arr = [];
            $("input[name='misc[]']").each(function(){
                var value = $(this).val();
                arr.push(value);

                if(arr.indexOf(product[0]) < 0){
                    arr.push(value);
                    $('#misc_msg').hide();
                    $('#misc_btn_add').prop('disabled',false);
                } else {
                    $('#misc_msg').show();
                    $('#misc_btn_add').prop('disabled',true);
                }
            });

        }

        function misc_qty(){
            var qty = $('#misc_qty').val();
            var price = $('#product_price').val();

            var total_misc_price = parseInt(price)*qty;
            $('#total_misc_price').html(FormatAmount(total_misc_price,2));
        }

        var m = $('#misc_product_last_row').val();;
        function  add_to_screen_computation() {
            m++;

            var old_total_misc = $('#total_misc').val();
            var total_added_misc = parseInt(old_total_misc)+1;

            $('#total_misc').val(total_added_misc);

            var id   = $('#product_id').val();
            var name = $('#product_name').val();
            var price= $('#product_price').val();
            var qty  = $('#misc_qty').val();
            var total = parseInt(price)*qty;

            $('#misc_summary').append('<tr id="misc_row'+m+'">'+
                '<td style="display:none;"><input type="text" name="misc[]" value="'+name+'"/></td>'+
                '<td>'+name+'</td>'+
                '<td>'+FormatAmount(price,2)+'</td>'+
                '<td>'+qty+'</td>'+
                '<td class="text-right">PHP '+FormatAmount(total,2)+'</td>'+
                '<td style="display:none;" class="text-right"><input type="text" name="misc_qty[]" value="'+qty+'" /></td>'+
                '<td style="display:none;" class="text-right"><input type="text" name="misc_id[]" value="'+id+'" /></td>'+
                '<td class="text-right"><a href="javascript:;" id="'+m+'" class="remove_misc">x</a></td>'+
                '<td style="display:none;"><input type="text" class="input-total-qty-per-misc-product" id="product_total_qty_per_misc_product'+m+'" value="'+qty+'"/></td>'+
                '<td style="display:none;"><input type="text" class="input-total-price-per-misc-product" id="product_total_misc_price'+m+'" value="'+total.toFixed(2)+'"/></td>'+
            '</tr>');

            $('#addmiscellaneous').modal('hide');
            $('#product_price').val(0);
            $('#misc_products').val('');
            $('#misc_qty').val(1);

            total_misc();
            $("#misc_products").val('').trigger('change');
            $('#total_misc_price').html('0.00');
        } 

        $(document).on('click', '.remove_misc', function(){
            var btn_id = $(this).attr("id");
            var old_total_misc = $('#total_misc').val();
            var total_misc = parseInt(old_total_misc)-1;

            $('#total_misc').val(total_misc);
            $('#misc_row'+btn_id+'').remove();

            total_misc();
        });

        function total_misc(){
            var total_misc = 0;
            
            $(".input-total-price-per-misc-product").each(function() {
                //add only if the value is number
                if(!isNaN(this.value) && this.value.length!=0) {
                    total_misc += parseFloat(this.value);
                }
            });

            $('#summary_total_misc').html('PHP '+FormatAmount(total_misc,2));
            $('#input_total_misc').val(total_misc.toFixed(2));

            calculate_grand_total();
        }
    /** miscellaneous functions **/

    /** On screen computation functions **/

        function qty_change(id,price){

            $('#paella'+id).remove();

            $('#cb'+id).prop('checked',false);
            $('#paella_qty'+id).val(0);
            $('#paella_qty'+id).prop('readonly',true);
            $('#paella_qty'+id).prop('required',false);

            var qty = $('#qty_'+id).val();
        
            update_total_price_per_product(id,price,qty);
            calculate_sub_total();

        }

        function add_paella(n,product_price,paella_price){
            var qty = $('#qty_'+n).val();
            var pprice = paella_price*qty;

            if($('#cb'+n).prop("checked") == true){

                $('#paella_row'+n).append('<tr id="paella'+n+'">'+
                        '<td width="40%">Add On: Paella</td>'+
                        '<td width="20%" class="text-right">'+FormatAmount(paella_price,2)+'</td>'+
                        '<td width="20%" class="text-center" id="paellaqty'+n+'">'+qty+'</td>'+
                        '<td width="20%" class="text-right" id="paellaprice'+n+'">PHP '+FormatAmount(pprice,2)+'</td>'+
                        '<td style="display:none;"><input type="text" class="input_paella_price" id="paella_'+n+'" value="'+pprice+'" /></td>'+
                    '</tr>'
                );

                $('#paella_qty'+n).val(qty);
                $("#paella_qty"+n).attr({"max" : qty});
                $('#paella_qty'+n).prop('readonly',false);
                $('#paella_qty'+n).prop('required',true);


            }
            else if($('#cb'+n).prop("checked") == false){

                $('#paella'+n).remove();

                $('#paella_qty'+n).val(0);
                $('#paella_qty'+n).prop('readonly',true);
                $('#paella_qty'+n).prop('required',false);

            }

            calculate_sub_total();
        }

        function paella_qty_change(n,product_price,paella_price){
            var qty = $('#paella_qty'+n).val();
            var qty1 = $('#qty_'+n).val();

            var product_total_price = parseInt(product_price)*qty1;
            var total_paella_price  = parseInt(paella_price)*qty;


            var total = parseInt(product_total_price)+total_paella_price;

            $('#paella_'+n).val(total_paella_price);
            $('#paellaqty'+n).html(qty);
            $('#paellaprice'+n).html('PHP '+FormatAmount(total_paella_price,2));

            calculate_sub_total();
        }

        function total_price_per_product_qty_and_paella(n,total){

            $('.order_total_price'+n).html('PHP '+FormatAmount(total,2));
            $('#product_total_price'+n).val(total.toFixed(2));

            calculate_sub_total();

        }

        function update_total_price_per_product(n,price,qty){
            var total_price = parseInt(price)*qty;

            $('.order_qty'+n).html(qty);
            $('.order_total_price'+n).html('PHP '+FormatAmount(total_price,2));
            $('#product_total_price'+n).val(total_price.toFixed(2));

            $('#product_total_qty_per_product'+n).val(qty);
        }

        function calculate_sub_total(){

            var price_per_product = 0;
            var total_qty = 0;
            var price_per_paella = 0;
            
            $(".input_paella_price").each(function() {
                //add only if the value is number
                if(!isNaN(this.value) && this.value.length!=0) {
                    price_per_paella += parseFloat(this.value);
                }
            });

            $(".input-total-price-per-product").each(function() {
                //add only if the value is number
                if(!isNaN(this.value) && this.value.length!=0) {
                    price_per_product += parseFloat(this.value);
                }
            });

            $(".input-total-qty-per-product").each(function() {
                //add only if the value is number
                if(!isNaN(this.value) && this.value.length!=0) {
                    total_qty += parseFloat(this.value);
                }
            });
            var subtotal = price_per_product+price_per_paella;

            $("#subtotal").html('PHP '+FormatAmount(subtotal,2));
            $("#totalQty").html(total_qty);
            $('#input_subtotal').val(subtotal.toFixed(2));

            calculate_grand_total();
        }

        function calculate_grand_total(){

            var subtotal = $('#input_subtotal').val();
            var delivery_charge = $('#input_delivery_charge').val();
            var total_misc = $('#input_total_misc').val();

            var grand_total = parseInt(subtotal)+parseInt(delivery_charge)+parseInt(total_misc);

            $('#summary_input_gross').val(grand_total.toFixed(2));
            $('#grand_total').html('PHP '+FormatAmount(grand_total,2));
        }

        function FormatAmount(number, numberOfDigits) {

            var amount = parseFloat(number).toFixed(numberOfDigits);
            var num_parts = amount.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return num_parts.join(".");

        }
    /** On screen computation functions **/
    </script>

    <script>
    /** delivery functions **/
        $(document).on('change','#set_delivery_charge', function(){
            var rate = $(this).val();

            $('#summary_delivery_charge').html(FormatAmount(rate,2));
            $('#input_delivery_charge').val(rate);

            calculate_grand_total();
        });

        $(document).on('change', '#delivery_type', function(){
            var type = $(this).val();

            if(type == 1){
                $('#set_delivery_charge').val(0);
                $('#set_delivery_charge').prop('readonly',true);
                $('#outlet_rate').prop('required',true);
                $('#outlet_div').show();
            }

            if(type == 2){
                $('#outlet_div').hide();

                $('#other_outlet_div').hide();
                $('#other_outlet').prop('required',false);

                $('#set_delivery_charge').val(0);
                $('#outlet_rate').prop('required',false);
                $('#set_delivery_charge').prop('readonly',true);

                $('#summary_delivery_charge').html('0.00');
                $('#input_delivery_charge').val(0);
            }

            calculate_grand_total();

        });

        $(document).on('change', '#outlet_rate', function(){
            var v = $(this).val();
            var x = v.split('|');

            if(x[0] == 'others'){
                $('#set_delivery_charge').val(0);
                $('#prompt-product-validation').modal('show');
                $('#exampleModalCenterTitle').html('Info');
                $('#prompt_msg').html('Please ask the staff how much the delivery charge of your desired delivery address.');
                $('#set_delivery_charge').prop('readonly',false);

                $('#other_outlet_div').show();
                $('#other_outlet').prop('required',true);
            } else {

                $('#set_delivery_charge').val(x[1]);
                $('#set_delivery_charge').prop('readonly',true);

                $('#other_outlet_div').hide();
                $('#other_outlet').prop('required',false);

                $('#summary_delivery_charge').html(FormatAmount(x[1],2));
                $('#input_delivery_charge').val(x[1]);

            }

            calculate_grand_total();
        });
    /** delivery functions **/
    </script>

@endsection
