@extends('admin.layouts.app')

@section('pagetitle')
    Update Sales Details
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
            background-color: rgb(255, 255, 255, 0.5);
            color: black;
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
                        <li class="breadcrumb-item active" aria-current="page">Update sales details</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Update Sales Details</h4>
            </div>
        </div>
        <div class="row row-sm">
            <div class="col-lg-6">
                <form action="{{ route('sales-transaction.update_all') }}" method="post" id="items_form" autocomplete="off">
                    @csrf
                    @if (auth()->user()->role_id <= 3 || auth()->user()->id == 10097 || auth()->user()->id == 10102 || auth()->user()->has_access_to_route('sales-transaction.restore') || auth()->user()->has_access_to_route('sales-transaction.update'))

                        @if ($dateneeded > date('Y-m-d H:i:s') || $salesheader->delivery_status == 'Open Date')
                            
                                <div class="order-details-place">
                                    <br>
                                    <input type="hidden" name="update_dateneeded_id" value="{{ $salesheader->id }}">
                                    <input type="hidden" name="update_dateneeded_deliverytype"
                                        value="{{ $salesheader->delivery_type }}">

                                    <div class="form-group">
                                        <label for="shipping_type" class="control-label" id="shipping_type_label">Shipping Type</label>
                                        <select name="shipping_type" id="shipping_type" class="form-control"
                                            required="required">
                                            <option value="d2d" @if ($salesheader->delivery_type == 'Door to door delivery') selected="selected" @endif>
                                                Door to door</option>
                                            <option value="storepickup"
                                                @if ($salesheader->delivery_type == 'Store Pickup') selected="selected" @endif>Pick up at nearest
                                                store</option>
                                        </select>
                                    </div>


                                    <h5 style="display:none;">Old Date Needed : {{ $dateneeded }}<br>Old Location :
                                        {{ $locationed }} ( {{ $salesheader->delivery_type }} )</h5>
                                    <br>


                                    @if (auth()->user()->has_access_to_route('sales.update_delivery_branch'))

                                        <div class="form-group">    
                                            <label class="d-block">Location <span class="tx-danger">*</span></label>

                                            <div class="divd2d" @if ($salesheader->delivery_type != 'Door to door delivery')
                                                    style="display:none;" @endif>
                                                    <select class="selectpicker mg-b-5"
                                                        data-style="btn btn-outline-light btn-md btn-block tx-left"
                                                        title="Choose New Location" data-width="100%" name="update_dateneeded_d2d"
                                                        id="update_dateneeded_d2d">
                                                        @foreach ($locations as $b)
                                                        <option @if ($b->name == $locationed) selected @endif
                                                            value="{{$b->name}}">{{$b->name}}</option>
                                                        @endforeach
                                                        <option value="Other" @if ($locationed == 'Other') selected @endif>Other
                                                        </option>
                                                    </select>
                                                    <div id="delivery_fee_amount_div" @if ($locationed != 'Other')
                                                        style="display:none;" @endif>
                                                        Delivery Fee:
                                                        <input class="form-control" type="number" step="0.01" min="0.00"
                                                            value="{{ old('delivery_fee_amount', number_format((float) $salesheader->delivery_fee_amount, 2, '.', '')) }}"
                                                            name="delivery_fee_amount" id="delivery_fee_amount">

                                                    </div>
                                            </div>

                                            <div class="divsp" @if ($salesheader->delivery_type != 'Store Pickup')
                                                    style="display:none;" @endif >
                                                    <select class="selectpicker mg-b-5"
                                                        data-style="btn btn-outline-light btn-md btn-block tx-left"
                                                        title="Choose New Location" data-width="100%" name="update_dateneeded_sp"
                                                        id="update_dateneeded_sp">
                                                        @foreach (\App\EcommerceModel\Branch::where('status', 1)->where('pickup_branch', 1)->orderBy('name')->get() as $b)
                                                        <option @if ($b->name == $locationed) selected @endif
                                                            value="{{$b->name}}">{{$b->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                    @if ($errors->has('update_dateneeded_sp'))
                                                        <span class="text-danger">{{ $errors->first('update_dateneeded_sp') }}</span>
                                                    @endif
                                            </div>



                                            <!-- Allow Multiple Address Toggle -->
                                            {{-- <div class="form-check mb-3 mt-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="allowMultiple" />
                                                <label class="form-check-label" for="allowMultiple">
                                                    Allow multiple address
                                                </label>
                                            </div> --}}

                                            <!-- Dynamic Address Sections -->
                                            <div id="multipleAddressesWrapper"></div>

                                            <!-- Add More Button -->
                                            <button type="button" class="btn btn-outline-primary mt-3 d-none" id="addMoreBtn">
                                                + Add More Address
                                            </button>

                                            <!-- Hidden Template (NO name or required attributes) -->
                                            <div id="addressSectionTemplate" class="address-section d-none" aria-hidden="true">
                                                <fieldset disabled>
                                                    <div class="d-flex justify-content-between flex-column 3">
                                                        <div class="d-none">
                                                        @foreach ($salesheader->items as $item)
                                                            <div class="d-flex justify-content-between product-row"
                                                                data-product-id="{{ $item->product_id }}">
                                                                <div class="form-check me-2 d-flex align-items-center">
                                                                    <input class="form-check-input product-checkbox"
                                                                        type="checkbox" value="{{ $item->product_id }}"
                                                                        data-product-id="{{ $item->product_id }}"
                                                                        data-name="product_ids"
                                                                        id="item_{{ $item->product_id }}">
                                                                    <label
                                                                        class="form-check-label">{!! highlightPaella($item?->product_name) !!}</label>
                                                                </div>
                                                                <div>
                                                                    <select class="form-select form-select-sm mb-2 product-qty"
                                                                        data-product-id="{{ $item->product_id }}"
                                                                        data-name="product_qty"
                                                                        id="item_qty_{{ $item->product_id }}">
                                                                        @for ($i = 1; $i <= $item->qty; $i++)
                                                                            <option value="{{ $i }}">
                                                                                {{ $i }}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold address-label">Address</label>
                                                        <textarea rows="5" class="form-control address"></textarea>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label class="d-block">Date & Time Needed <i
                                                                        class="text-danger">*</i></label>
                                                                <input type="text" class="form-control date-field"
                                                                    placeholder="Choose Date" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="d-block">&nbsp;</label>
                                                                <div class="input-group timepicker">
                                                                    <select class="form-control time-field ">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" class="delivery-fee" name="delivery_fee[]"
                                                        value="0" />

                                                    {{-- <div class="form-group">
                                                <label class="control-label">Location Type</label>
                                                <select class="form-control location">
                                                    <option value="">Select Location</option>
                                                    @foreach ($locations as $location)
                                                    <option value="{{ $location->name }}">{{ $location->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}

                                                    <div class="form-group d-none">
                                                        <label class="d-block">Region *</label>
                                                        <select class="form-control region" id="region_select"
                                                            style="width:100%">
                                                            <option value="">Select Region</option>
                                                        </select>
                                                        @if ($errors->has('region'))
                                                            <span class="text-danger">{{ $errors->first('region') }}</span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="d-block">Province *</label>
                                                        <select class="form-control province" id="province_select"
                                                            style="width:100%">
                                                            <option value="">Select Province</option>
                                                            @foreach ($provinces as $province)
                                                                <option value="{{ $province }}">{{ $province }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('province'))
                                                            <span class="text-danger">{{ $errors->first('province') }}</span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="d-block">City/Municipality *</label>
                                                        <select class="form-control city" id="city_select" style="width:100%"
                                                            disabled>
                                                            <option value="">Select City/Municipality</option>
                                                        </select>
                                                        @if ($errors->has('city'))
                                                            <span class="text-danger">{{ $errors->first('city') }}</span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="d-block">Barangay *</label>
                                                        <select class="form-control barangay" id="barangay_select"
                                                            style="width:100%" disabled>
                                                            <option value="">Select Barangay</option>
                                                        </select>
                                                        @if ($errors->has('barangay'))
                                                            <span class="text-danger">{{ $errors->first('barangay') }}</span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="d-block">Delivery Branch <span
                                                                class="tx-danger">*</span></label>
                                                        <select class="form-control branch">
                                                            <option value="">- Select Branch -</option>
                                                            @foreach (\App\EcommerceModel\Branch::where('status', 1)->where('delivery_branch', 1)->orderBy('name')->get() as $b)
                                                                <option value="{{ $b->name }}">{{ $b->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold note-label">Note</label>
                                                        <textarea class="form-control note"></textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold contact_person-label">Contact
                                                            Person</label>
                                                        <input type="text" class="form-control contact_person" />
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold contact_tel-label">Contact
                                                            Number</label>
                                                        <input type="text" class="form-control contact_tel" />
                                                    </div>

                                                    {{-- <button type="button"
                                                        class="btn btn-sm btn-danger remove-address">Remove</button> --}}
                                                </fieldset>
                                            </div>



                                            <button type="button" class="btn btn-outline-primary mt-3 d-none"
                                                id="addMoreBtn">
                                                + Add More Address
                                            </button>


                                        </div>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between mg-b-5 mb-3">
                                        <div class="form-check form-check-inline">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="open_date" 
                                            id="open-date"
                                            @checked($salesheader->delivery_status === 'Open Date')
                                        >
                                            <label class="form-check-label" for="open-date">Is Open Date?</label>
                                        </div>
                                    </div>

                                    @if ($salesheader->deliveryAddress->count() == 0)
                                        <div class="form-row datetime_field datetime">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="d-block">Date & Time Needed <i class="text-danger asterisk-date">*</i></label>
                                                    <input type="text" name="update_dateneeded_date" class="form-control"
                                                        placeholder="Choose Date" id="date2" value="{{$date_only}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="d-block">&nbsp;</label>
                                                    <div class="input-group timepicker">
                                                        <select class="selectpicker"
                                                            data-style="btn btn-outline-light btn-md btn-block tx-left tx-black"
                                                            title="Choose Time" data-width="100%" name="update_dateneeded_time">
                                                            <option @if ($time_only == '05:00') selected @endif value="05:00">05:00 AM
                                                            </option>
                                                            <option @if ($time_only == '06:00') selected @endif value="06:00">06:00 AM
                                                            </option>
                                                            <option @if ($time_only == '07:00') selected @endif value="07:00">07:00 AM
                                                            </option>
                                                            <option @if ($time_only == '08:00') selected @endif value="08:00">08:00 AM
                                                            </option>
                                                            <option @if ($time_only == '09:00') selected @endif value="09:00">09:00 AM
                                                            </option>
                                                            <option @if ($time_only == '10:00') selected @endif value="10:00">10:00 AM
                                                            </option>
                                                            <option @if ($time_only == '11:00') selected @endif value="11:00">11:00 AM
                                                            </option>
                                                            <option @if ($time_only == '12:00') selected @endif value="12:00">12:00 NN
                                                            </option>
                                                            <option @if ($time_only == '13:00') selected @endif value="13:00">01:00 PM
                                                            </option>
                                                            <option @if ($time_only == '14:00') selected @endif value="14:00">02:00 PM
                                                            </option>
                                                            <option @if ($time_only == '15:00') selected @endif value="15:00">03:00 PM
                                                            </option>
                                                            <option @if ($time_only == '16:00') selected @endif value="16:00">04:00 PM
                                                            </option>
                                                            <option @if ($time_only == '17:00') selected @endif value="17:00">05:00 PM
                                                            </option>
                                                            <option @if ($time_only == '18:00') selected @endif value="18:00">06:00 PM
                                                            </option>
                                                            <option @if ($time_only == '19:00') selected @endif value="19:00">07:00 PM
                                                            </option>
                                                            <option @if ($time_only == '20:00') selected @endif value="20:00">08:00 PM
                                                            </option>
                                                            <option @if ($time_only == '21:00') selected @endif value="21:00">09:00 PM
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group divd2d" @if ($salesheader->delivery_type !=
                                            'Door to door delivery')
                                                style="display:none;" @endif>
                                                <label class="d-block">Delivery Address <span class="tx-danger">*</span></label>
                                                <textarea name="new_delivery_address" class="form-control" rows="5"
                                                    {{-- @if (auth()->user()->role_id <= 3 || auth()->user()->id == 10097 || auth()->user()->id == 10102) @else style="pointer-events: none;background-color:#E9ECEF" @endif --}}
                                                    >{{ $salesheader->customer_delivery_adress }}</textarea>
                                        </div>


                                        <div class="form-group">
                                            <label class="d-block">Note <span class="tx-danger">*</span></label>
                                            <textarea name="new_instruction" class="form-control"
                                                {{-- @if (auth()->user()->role_id <= 3 || auth()->user()->id == 10097 || auth()->user()->id == 10102) @else style="pointer-events: none;background-color:#E9ECEF" @endif --}}
                                                >{{ $salesheader->instruction }}</textarea>
                                        </div>
                                    @endif
                                </div>
                        @endif
                    @endif
                    <br>
                
                    <input type="hidden" name="ui_sales_id" value="{{ $salesheader->id }}">
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
                                @foreach ($salesheader->items as $item)
                                    <tr id="ui_tr{{ $item->id }}">
                                        <td>
                                            <input type="hidden" name="product[]" value="{{ $item->product_id }}">
                                                <a href="#" class="btn btn-xs btn-danger"
                                                    onclick="ui_removeitem('ui_tr{{ $item->id }}');">x</a>
                                            </td>
                                        <td>{{ $item->product_name }}<input name="uia_product{{ $item->id }}"
                                                value="{{ $item->product_id }}" type="hidden"></td>
                                        <td>
                                            <input type="number" class="form-control uiu_qty"
                                                title="{{ $item->id }}"
                                                {{ $item->product_id == 270 ? 'readonly' : '' }}
                                                onchange="ui_change_qty('uiu',{{ $item->id }});"
                                                name="uiu_qty{{ $item->id }}" min="0"
                                                id="uiu_qty{{ $item->id }}"
                                                value="{{ number_format($item->qty, 0) }}">
                                        </td>
                                        <td>
                                            @if ($item->product->paella_price > 0)
                                                <input type="checkbox"
                                                    onchange="ui_change_qty('uiu',{{ $item->id }});"
                                                    value="{{ $item->product->paella_price }}"
                                                    name="uiu_paella{{ $item->id }}"
                                                    id="uiu_paella{{ $item->id }}"
                                                    @if ($item->paella_price > 0) checked="checked" @endif>
                                                {{ number_format($item->product->paella_price, 2) }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ number_format($item->price, 2) }}
                                            <input type="hidden" name="uiu_price{{ $item->id }}"
                                                id="uiu_price{{ $item->id }}" value="{{ $item->price }}">
                                        </td>
                                        <td>
                                            <span
                                                id="uiu_total{{ $item->id }}">{{ number_format($item->gross_amount, 2) }}</span>
                                            <input type="hidden" name="uiu_subtotal{{ $item->id }}"
                                                id="uiu_subtotal{{ $item->id }}" value="{{ $item->gross_amount }}">
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
                                        @foreach ($products as $product)
                                            <option
                                                value="{{ $product->name }}|{{ $product->price }}|{{ $product->paella_price }}|{{ $product->id }}|{{ $product->production_item }}">
                                                {{ $product->name }} - {{ number_format($product->price, 2) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><a href="#" class="btn btn-sm btn-info ml-3"
                                        onclick="ui_add_product($('#ui_product').val());">Add</a></td>
                            </tr>
                        </table>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm btn-uppercase" type="submit" id="submitItemsBtn">Save Changes</button>
                        </div>
                    </div>
                    <a href="{{ route('sales-transaction.index') }}"
                        class="btn btn-outline-secondary btn-sm btn-uppercase">Back to Sales Transaction</a>
                
                </form>    
            </div>




        </div>
    </div>

    <div class="modal effect-scale" id="prompt-product-validation" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
        $('#update_dateneeded_d2d').on('change', function() {
            if ($(this).val() == 'Other') {
                $('#delivery_fee_amount_div').show();
            } else {
                $('#delivery_fee_amount_div').hide();
            }
        })
        // $("#updatefrm").submit(function() {
        //     const btn = document.getElementById('submitBtn');
        //     btn.disabled = true;
        //     btn.innerText = 'Saving...';
        //     if ($('#shipping_type').val() == 'd2d') {
        //         if ($('#delivery_branch').val() == '') {
        //             alert('Please select Delivery Branch');
        //             return false;
        //         } else {
        //             return true;
        //         }
        //     }

        // });
        $("#items_form").submit(function() {
            const btn = document.getElementById('submitItemsBtn');
            btn.disabled = true;
            btn.innerText = 'Saving...';
        });
        /** page level plugins **/
        $('.select2').select2({
            placeholder: 'Choose one',
            searchInputPlaceholder: 'Search options'
        });

        $(function() {
            $('#shipping_type').trigger('change');
            $('.selectpicker').selectpicker();
        });

        /** page level plugins **/

        function ui_add_product(x) {
            if (x == '') {
                alert('Please select an item.');
                return false;
            }

            var i = x.split("|");

            var arr = [];
            var c = 0;
            $("input[name='product[]']").each(function() {
                var value = $(this).val();
                arr.push(value);

                if (arr.indexOf(i[3]) < 0) {
                    arr.push(value);
                } else {
                    $('#prompt-product-validation').modal('show');
                    $('#exampleModalCenterTitle').html('Error');
                    $('#prompt_msg').html('Selected product is already in the list.');

                    $("#ui_product").val('').trigger('change');
                    c = 1;
                }
            });

            if (parseInt(c) > 0) {
                return false;
            }
            $('#ui_total_new').val(parseInt($('#ui_total_new').val()) + 1);
            var y = parseInt($('.uia_tr').length) + 1;


            var pael = '&nbsp;<input type="hidden" value="0" name="uia_paella' + y + '" id="uia_paella' + y + '">';
            if (parseFloat(i[2]) > 0) {
                var pael = '<input type="checkbox" onchange="ui_change_qty(\'uia\',' + y + ');" value="' + i[2] +
                    '" name="uia_paella' + y + '" id="uia_paella' + y + '"> ' + addCommas(parseFloat(i[2]).toFixed(2));
            }
            var s = '<tr id="ui_tr' + y + '" class="uia_tr">' +
                '<td><input type="hidden" name="product[]" value="' + i[3] +
                '"><a href="#" class="btn btn-xs btn-danger" onclick="ui_removeitem(\'ui_tr' + y + '\');">x</a></td>' +
                '<td>' + i[0] + '<input name="uia_product' + y + '" value="' + i[3] + '" type="hidden"></td>' +
                '<td>' +
                '<input type="number" onchange="ui_change_qty(\'uia\',' + y + ');" class="form-control" title="' + y +
                '" name="uia_qty' + y + '" min="0" id="uia_qty' + y + '" value="1">' +
                '</td>' +
                '<td>' +
                pael +
                '</td>' +
                '<td>' +
                '' + addCommas(parseFloat(i[1]).toFixed(2)) + '' +
                '<input type="hidden" name="uia_price' + y + '" id="uia_price' + y + '" value="' + i[1] + '">' +
                '</td> ' +
                '<td>' +
                '<span id="uia_total' + y + '">' + addCommas(parseFloat(i[1]).toFixed(2)) + '</span>' +
                '<input type="hidden" name="uia_subtotal' + y + '" id="uia_subtotal' + y + '" value="' + i[1] + '">' +
                '</td>' +
                '</tr>';

            $('#ui_body').append(s);
            $("#ui_product").val('').trigger('change');
        }

        // function ui_change_qty(i, x) {
        //     var qty = $('#' + i + '_qty' + x).val();
        //     var price = $('#' + i + '_price' + x).val();
        //     var paella = 0;
        //     if ($('#' + i + '_paella' + x).is(':checked')) {
        //         paella = $('#' + i + '_paella' + x).val();
        //     }
        //     var subtotal = parseFloat(parseFloat(qty) * parseFloat(price)) + parseFloat(parseFloat(paella) * parseFloat(
        //         qty));
        //     $('#' + i + '_total' + x).html(addCommas(parseFloat(subtotal).toFixed(2)));
        // }

        function ui_change_qty(i, x) {
            var qty    = parseFloat($('#' + i + '_qty' + x).val()) || 0;
            var price  = parseFloat($('#' + i + '_price' + x).val()) || 0;
            var paella = 0;

            if ($('#' + i + '_paella' + x).length && $('#' + i + '_paella' + x).is(':checked')) {
                paella = parseFloat($('#' + i + '_paella' + x).val()) || 0;
            }

            var subtotal = (qty * price) + (qty * paella);

            $('#' + i + '_total' + x).html(addCommas(subtotal.toFixed(2)));
            if ($('#' + i + '_subtotal' + x).length) {
                $('#' + i + '_subtotal' + x).val(subtotal.toFixed(2));
            }

            // sync lechon baka service (270) with lechon baka (178)
            // get product id of the row that was changed
            var productId = $('input[name="uia_product' + x + '"]').val();

            // if this row is lechon baka (178), update lechon baka service (270)
            if (parseInt(productId, 10) === 178) {
                var bakaQty = qty;

                // find the row whose product is 270
                $('input[name^="uia_product"]').each(function () {
                    if (parseInt($(this).val(), 10) === 270) {
                        var serviceId = this.name.replace('uia_product', ''); // extract item id

                        // set qty of the service row
                        $('#' + i + '_qty' + serviceId).val(bakaQty);

                        // recalc subtotal for service row (same logic as above)
                        var sQty    = bakaQty;
                        var sPrice  = parseFloat($('#' + i + '_price' + serviceId).val()) || 0;
                        var sPaella = 0;

                        if ($('#' + i + '_paella' + serviceId).length &&
                            $('#' + i + '_paella' + serviceId).is(':checked')) {
                            sPaella = parseFloat($('#' + i + '_paella' + serviceId).val()) || 0;
                        }

                        var sSubtotal = (sQty * sPrice) + (sQty * sPaella);

                        $('#' + i + '_total' + serviceId).html(addCommas(sSubtotal.toFixed(2)));
                        if ($('#' + i + '_subtotal' + serviceId).length) {
                            $('#' + i + '_subtotal' + serviceId).val(sSubtotal.toFixed(2));
                        }
                    }
                });
            }
        }

        function ui_removeitem(tr) {
            var txt;
            var r = confirm("Are you sure you want to remove this item?");
            if (r == true) {
                $('#uia_product' + tr).remove();
                $('#' + tr).remove();
            }
        }

        function addCommas(nStr) {
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


        $('#shipping_type').on('change', function() {
            var r = $(this).val();

            if (r == 'd2d') {
                $('.divd2d').show();
                $('.divsp').hide();

                $('#update_dateneeded_sp').prop('required', false);
                $('#update_dateneeded_d2d').prop('required', true);

            } else if (r == 'storepickup') {
                $('.divd2d').hide();
                $('.divsp').show();

                $('#update_dateneeded_sp').prop('required', true);
                $('#update_dateneeded_d2d').prop('required', false);
            }

            $('.selectpicker').selectpicker('refresh');
        });

        window.preloadedAddresses = @json($salesheader->deliveryAddress ?? []);
        let skipInitialBlock = false;

        $(document).ready(function() {
            $('#allowMultiple').on('change', function() {
                const isChecked = this.checked;
                $('#addMoreBtn').toggleClass('d-none', !isChecked);
                $('#multipleAddressesWrapper').empty();

                if (isChecked && !skipInitialBlock) {
                    addNewAddressBlock();
                }

                skipInitialBlock = false; // reset for future toggles
            });


            $('#addMoreBtn').on('click', function() {
                if (allProductsUsedUp()) {
                    alert(
                        'All products and their quantities have already been assigned. You cannot add more address blocks.');
                    return;
                }

                addNewAddressBlock();
                updateProductAvailability();
            });

            function allProductsUsedUp() {
                const used = getUsedQuantities();
                let hasAvailable = false;

                $('#addressSectionTemplate .form-check-input').each(function() {
                    const productId = this.id.replace('item_', '');
                    const $qtySelect = $(this).closest('div').next().find('select');
                    const maxQty = parseInt($qtySelect.find('option:last-child').val()) || 0;
                    const alreadyUsed = used[productId] || 0;

                    if (alreadyUsed < maxQty) {
                        hasAvailable = true;
                    }
                });

                return !hasAvailable; // returns true if everything is used
            }



            // Add new address block
            function addNewAddressBlock(data = {}) {
                const $template = $('#addressSectionTemplate').clone().removeClass('d-none').removeAttr('id')
                    .removeAttr('aria-hidden');

                const used = getUsedQuantities();

                $template.find('.form-check-input').each(function() {
                    const productId = this.id.replace('item_', '');
                    const $qtySelect = $(this).closest('div').next().find('select');
                    const maxQty = parseInt($qtySelect.find('option:last-child').val()) || 0;

                    if (used[productId] >= maxQty) {
                        $(this).closest('.d-flex.justify-content-between').remove();

                    }
                });

                const $fieldset = $template.find('fieldset').prop('disabled', false);

                $fieldset.find('.address').attr({
                    name: 'address[]',
                    required: true
                }).val(data.address || ''); // New Fields
                $fieldset.find('.location').attr({
                    name: 'location[]',
                    required: true
                }).val(data.location || '');
                $fieldset.find('.branch').attr({
                    name: 'branch[]',
                    required: true
                }).val(data.branch || '');
                $fieldset.find('.note').attr({
                    name: 'note[]',
                    required: true
                }).val(data.note || '');
                $fieldset.find('.contact_person').attr({
                    name: 'contact_person[]',
                    required: true
                }).val(data.contact_person || '');
                $fieldset.find('.contact_tel').attr({
                    name: 'contact_tel[]',
                    required: true
                }).val(data.contact_tel || '');

                $fieldset.find('.province').attr({
                    name: 'province[]',
                    required: true
                }).val(data.province || '');
                $fieldset.find('.city').attr({
                    name: 'city[]',
                    required: true
                }).val(data.city || '');
                $fieldset.find('.barangay').attr({
                    name: 'barangay[]',
                    required: true
                }).val(data.barangay || '');

                $fieldset.find('.date-field')
                    .attr({
                        name: 'dateneeded_date[]',
                        required: true
                    })
                    .val(data.date || '')
                    .datepicker({
                        minDate: 0,
                        dateFormat: 'yy-mm-dd'
                    });

                const $timeSelect = $fieldset.find('.time-field').attr({
                    name: 'dateneeded_time[]',
                    required: true
                });
                const timeOptions = [
                    '05:00', '06:00', '07:00', '08:00', '09:00',
                    '10:00', '11:00', '12:00', '13:00', '14:00',
                    '15:00', '16:00', '17:00', '18:00', '19:00',
                    '20:00', '21:00'
                ];
                $timeSelect.append(`<option value="">Choose Time</option>`);
                timeOptions.forEach(time => {
                    const label = moment(time, 'HH:mm').format('hh:mm A');
                    $timeSelect.append(
                        `<option value="${time}" ${data.time === time ? 'selected' : ''}>${label}</option>`
                        );
                });

                // ✅ Populate checked products & quantities
                if (data.products) {
                    try {
                        const selectedProducts = JSON.parse(data.products);
                        console.log(selectedProducts)
                        selectedProducts.forEach(item => {
                            const productId = item.product?.id;
                            const qty = item.qty;

                            const $checkbox = $fieldset.find(`#item_${productId}`);
                            const $qtySelect = $fieldset.find(`#item_qty_${productId}`);

                            if ($checkbox.length) {
                                $checkbox.prop('checked', true);
                            }

                            if ($qtySelect.length) {
                                $qtySelect.val(qty);
                            }
                        });
                    } catch (e) {
                        console.error('Invalid products JSON:', data.products);
                    }
                }


                $('#multipleAddressesWrapper').append($template);
                updateLabels();


                const blockIndex = $('#multipleAddressesWrapper .address-section').length - 1;

                $template.find('.product-checkbox').each(function() {
                    const productId = $(this).data('product-id');
                    const name = $(this).data('name');
                    $(this).attr('name', `${name}[${blockIndex}][]`);
                });

                $template.find('.product-qty').each(function() {
                    const productId = $(this).data('product-id');
                    const name = $(this).data('name');
                    const $checkbox = $template.find(`#item_${productId}`);

                    if ($checkbox.is(':checked')) {
                        $(this).attr('name', `${name}[${blockIndex}][${productId}]`);
                    } else {
                        $(this).removeAttr('name'); // Don't submit qty if not checked
                    }
                });

            }

            async function updateFeeForFieldset($fieldset, location, products) {
                try {
                    const response = await fetch(
                        '{{ route('cart.front.get_shipping_fee_for_multiple_address_new') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                locations: [location],
                                products
                            }),
                        });

                    if (!response.ok) throw new Error('Network error');

                    const data = await response.json();
                    const fee = parseFloat(data.fee || 0);

                    $fieldset.find('.delivery-fee')
                        .attr('name', 'delivery_fee[]')
                        .val(fee);

                    console.log('Set fee', fee, 'for fieldset', $fieldset.index());


                } catch (e) {
                    console.error(`Failed to fetch delivery fee for ${location}`, e);
                }
            }

            $(document).on('change', '.location', async function() {
                $('.address-section').each(function(i, el) {
                    const $fieldset = $(el);
                    const location = $fieldset.find('.location').val();

                    if (!location) {
                        console.warn(`Location is empty for fieldset ${i}`);
                        return;
                    }
                    const products = $fieldset.find('[data-product-id]:checked').map((i, el) =>
                        $(el).val()).get();

                    updateFeeForFieldset($fieldset, location, products);
                });
            });

            // Remove block
            $(document).on('click', '.remove-address', function() {
                $(this).closest('.address-section').remove();
                updateLabels();
            });

            // Update Address 1, 2, 3...
            function updateLabels() {
                $('#multipleAddressesWrapper .address-section').each(function(index) {
                    $(this).find('.address-label').text(`Address ${index + 1}`);
                });
            }



            // If deliveryAddress is preloaded, populate on page load
            if (Array.isArray(window.preloadedAddresses) && window.preloadedAddresses.length > 0) {
                skipInitialBlock = true; // prevent empty block
                $('#allowMultiple').prop('checked', true).trigger('change');

                window.preloadedAddresses.forEach(item => {
                    addNewAddressBlock({
                        address: item.address,
                        date: item.delivery_date,
                        time: item.delivery_time,
                        note: item.note,
                        contact_tel: item.contact_tel,
                        contact_person: item.contact_person,
                        branch: item.branch,
                        location: item.location,
                        products: item.products,
                        delivery_fee: item.delivery_fee,
                        province: item.province,
                        city: item.city,
                        barangay: item.barangay,
                    });
                });

                updateLabels();
            }

            function getUsedQuantities() {
                const used = {};

                $('#multipleAddressesWrapper .address-section').each(function() {
                    $(this).find('.form-check-input:checked').each(function() {
                        const productId = this.id.replace('item_', '');
                        const qty = parseInt($(this).closest('div').next().find('select').val()) ||
                            0;

                        used[productId] = (used[productId] || 0) + qty;
                    });
                });

                return used;
            }

            $(document).on('change', '.product-checkbox, .product-qty', function() {
                cleanupFollowingBlocks($(this).closest('.address-section'));
                updateProductAvailability();
            });

            function cleanupFollowingBlocks($changedBlock) {
                const changedIndex = $('#multipleAddressesWrapper .address-section').index($changedBlock);

                $('#multipleAddressesWrapper .address-section').each(function(index) {
                    if (index > changedIndex) {
                        $(this).remove(); // remove all blocks after the one that changed
                    }
                });
            }

            function updateProductAvailability() {
                const used = getUsedQuantities();

                // Get product max quantity from original template
                const productMaxQtyMap = {};
                $('#addressSectionTemplate .product-qty').each(function() {
                    const productId = $(this).data('product-id');
                    const maxQty = parseInt($(this).find('option:last-child').val()) || 0;
                    productMaxQtyMap[productId] = maxQty;
                });

                // Loop through ALL blocks and update each product row
                $('#multipleAddressesWrapper .address-section').each(function() {
                    const $block = $(this);

                    $block.find('.product-row').each(function() {
                        const $row = $(this);
                        const productId = $row.data('product-id');
                        const maxQty = productMaxQtyMap[productId] || 0;
                        const alreadyUsed = used[productId] || 0;

                        const isCheckedHere = $row.find('.product-checkbox').is(':checked');

                        // Hide only if used up and not checked in this block
                        if (alreadyUsed >= maxQty && !isCheckedHere) {
                            $row.hide();
                        } else {
                            $row.show();
                        }
                    });
                });
            }

            $(document).on('change', '.product-checkbox', function() {
                const productId = $(this).data('product-id');
                const $qtySelect = $(this).closest('.product-row').find(`#item_qty_${productId}`);
                const blockIndex = $(this).closest('.address-section').index();

                if (this.checked) {
                    $qtySelect.prop('disabled', false);
                    $qtySelect.attr('name', `product_qty[${blockIndex}][${productId}]`);
                } else {
                    $qtySelect.prop('disabled', true);
                    $qtySelect.removeAttr('name');
                }
            });

            $(document).on('change', '.product-qty', function() {
                const productId = $(this).data('product-id');
                const $checkbox = $(this).closest('.product-row').find(`#item_${productId}`);
                if (parseInt(this.value) > 0) {
                    $checkbox.prop('checked', true).trigger('change');
                }
            });

            const ADDR_URL =
                "{{ asset('addresses/philippine_provinces_cities_municipalities_and_barangays_2019v2.json') }}";

            const $region = $('#region_select');
            const $province = $('#province_select');
            const $city = $('#city_select');
            const $barangay = $('#barangay_select');

            // Only these provinces should appear initially:
            const ALLOWED_PROVINCES = new Set(@json($provinces->values()));
            const ALLOWED_CITIES = new Set(@json($cities->values()));

            const deliveriess = @json($salesheader->deliveryAddress ?? []);
            const initial = {
                region: deliveriess.length ? deliveriess[0].region : @json(old('region', $rate->region ?? '')),
                region_code: deliveriess.length ? deliveriess[0].region_code : @json(old('region_code', $rate->region_code ?? '')),
                province: deliveriess.length ? deliveriess[0].province : @json(old('province', $rate->province ?? '')),
                city: deliveriess.length ? deliveriess[0].city : @json(old('city', $rate->city ?? '')),
                barangay: deliveriess.length ? deliveriess[0].barangay : @json(old('barangay', $rate->barangay ?? '')),
            };

            let ADDR = null;

            const norm = s => (s || '').toString().trim(); // add your own case mapping if needed
            const isAllowedCity = name => ALLOWED_CITIES.has(norm(name));

            function ensureOption($el, value, text) {
                if (!value) return;
                const exists = $el.find('option').filter(function() {
                    return $(this).val() === value;
                }).length > 0;
                if (!exists) {
                    $el.append(new Option(text || value, value, false, false));
                    $el.trigger('change.select2');
                }
            }

            function provincesData() {
                const list = [];
                Object.values(ADDR).forEach(region => {
                    const provs = region.province_list || {};
                    Object.keys(provs).forEach(name => {
                        if (ALLOWED_PROVINCES.has(norm(name))) {
                            list.push({
                                id: name,
                                text: name
                            });
                        }
                    });
                });
                return list.sort((a, b) => a.text.localeCompare(b.text));
            }

            // --- cities filtered by province ---
            function citiesByProvince(provinceName) {
                const out = [];
                Object.values(ADDR).some(region => {
                    const prov = region.province_list?.[provinceName];
                    if (!prov) return false;

                    const muns = prov.municipality_list || {};
                    Object.keys(muns).forEach(name => {
                        if (isAllowedCity(name)) out.push({
                            id: name,
                            text: name
                        });
                    });
                    return true;
                });
                return out.sort((a, b) => a.text.localeCompare(b.text));
            }

            function barangaysByCity(provinceName, cityName) {
                const out = [];
                Object.values(ADDR).some(region => {
                    const prov = region.province_list?.[provinceName];
                    if (!prov) return false;
                    const mun = prov.municipality_list?.[cityName];
                    if (!mun) return true;
                    (mun.barangay_list || []).forEach(b => out.push({
                        id: b,
                        text: b
                    }));
                    return true;
                });
                return out.sort((a, b) => a.text.localeCompare(b.text));
            }

            function loadOptions($el, items, placeholder) {
                $el.empty();
                $el.append(new Option(placeholder || '- Select -', '', true, false));
                items.forEach(it => $el.append(new Option(it.text, it.id, false, false)));
                $el.trigger('change.select2');
            }

            $('.select2').select2({
                width: '100%',
                allowClear: true
            });

            function applyInitialSelection() {
                const prov = norm(initial.province);
                const city = norm(initial.city);
                const brgy = norm(initial.barangay);

                if (!prov) return;

                // make sure province option exists then select it
                ensureOption($province, prov, prov);
                $province.val(prov).trigger('change');

                // build allowed cities for this province
                const cityItems = citiesByProvince(prov).filter(c => isAllowedCity(c.id));
                loadOptions($city, cityItems, '- Select city/municipality -');
                $city.prop('disabled', cityItems.length === 0);

                // select saved city if present (normalize for safety)
                const hasCity = city && cityItems.some(c => norm(c.id) === city);
                if (hasCity) {
                    $city.val(city).trigger('change.select2');

                    const brgyItems = barangaysByCity(prov, city);
                    loadOptions($barangay, brgyItems, '- Select barangay -');
                    $barangay.prop('disabled', brgyItems.length === 0);

                    const hasBrgy = brgy && brgyItems.some(b => norm(b.id) === brgy);
                    if (hasBrgy) {
                        $barangay.val(brgy).trigger('change.select2');
                    }
                } else {
                    loadOptions($barangay, [], '- Select barangay -');
                    $barangay.prop('disabled', true);
                }
            }

            $.getJSON(ADDR_URL).done(json => {
                ADDR = json;
                applyInitialSelection();
            });

            // Province change → cities
            $province.on('change', function() {
                const prov = $(this).val();
                loadOptions($city, [], '- Select city/municipality -');
                loadOptions($barangay, [], '- Select barangay -');
                $barangay.prop('disabled', true);

                if (!prov) {
                    $city.prop('disabled', true);
                    return;
                }

                const cities = citiesByProvince(prov); // already filtered later
                loadOptions($city, cities, '- Select city/municipality -');
                $city.prop('disabled', cities.length === 0);
            });

            // City change → barangays
            $city.on('change', function() {
                const prov = $province.val();
                const city = $(this).val();

                loadOptions($barangay, [], '- Select barangay -');
                if (!city || !isAllowedCity(city)) {
                    $barangay.prop('disabled', true);
                    return;
                }

                const brgys = barangaysByCity(prov, city);
                loadOptions($barangay, brgys, '- Select barangay -');
                $barangay.prop('disabled', brgys.length === 0);
            });
        });

        $(function() {

            var dateToday = new Date(); 
            
            $('#date1, #date2').datepicker({ 
                minDate: dateToday, 
                dateFormat: 'yy-mm-dd', 
            });

            function toggleDateTime() {
                if ($('#open-date').is(':checked')) {
                    $('#date2').prop('required', false);
                    $('select[name="update_dateneeded_time"]').prop('required', false);

                    $('.asterisk-date').text('');
                    $('.datetime').hide();

                } else {
                    $('#date2').prop('required', true);
                    $('select[name="update_dateneeded_time"]').prop('required', true);

                    $('.asterisk-date').text('*');
                    $('.datetime').show();

                    $('#date2').val('');
                    $('select[name="update_dateneeded_time"]').val('').selectpicker('refresh');
                }

                $('.selectpicker').selectpicker('refresh');
            }

            // Run on load
            toggleDateTime();

            $('#date2').datepicker('setDate', '{{$date_only}}');
            $('select[name="update_dateneeded_time"]').val('{{$time_only}}').selectpicker('refresh');

            // Run on change
            $('#open-date').on('change', toggleDateTime);
        });
        
    </script>
@endsection
