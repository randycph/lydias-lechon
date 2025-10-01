@extends('admin.layouts.report')

@php
    $datetxt='';
    $dstart='';
    $old_value = '';
    if(isset($_GET['startdate']) && strlen($_GET['startdate']) > 0){
        $dstart = $_GET['startdate'];
    }
    $dend='';
    if(isset($_GET['enddate']) && strlen($_GET['enddate']) > 0){
        $dend = $_GET['enddate'];
    }
    if(strlen($dstart)>0){
        if($dstart == $dend)
            $datetxt = '<br>'.date('M d, Y (l)',strtotime($dstart));                                    
        else
            $datetxt = '<br>'.date('M d, Y (l)',strtotime($dstart))." - ".date('M d, Y (l)',strtotime($dend));
    }
    $dbranch = '';

    if(isset($_GET['receiver']) && count($_GET['receiver']) > 0){
            $br_opts = "<br>";            
            foreach($_GET['receiver'] as $re){
                $br = \App\EcommerceModel\Branch::whereId($re)->first();
                $br_opts .= $br->name.",";            
            }
            $br_opts = rtrim($br_opts,",");
         
        //$br = \App\EcommerceModel\Branch::whereId($_GET['receiver'])->first();
        $dbranch = $br_opts;
    }

    
    $total_lechon_order = 0;
    $total_lechon_overall = 0;
    $total_misc = 0;
    $ex_array = ['Pantaga','Display','Alpha Size','Belly Pantaga'];
    foreach(collect($results) as $j){
        if($j->is_misc == 0 && $j->production_item == 1){

                                         
            if($j->isConfirm == 1){

                if(!in_array($j->jo_category,$ex_array) ){
                    $total_lechon_order += $j->qty;
                }
                         
                    $total_lechon_overall += $j->qty;
            }
            
        }
        if($j->is_misc == 1){
                                        
            if($j->isConfirm == 1){

                $total_misc += $j->qty;

            }
        }
    }
   
@endphp

@section('pagecss')
    <!-- vendor css -->
    <link href="{{ asset('lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashforge.dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashforge.demo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skin.deepblue.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-admin.css') }}">

    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">

    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        @page {
          size: auto;
        }
        .bords{
            border: 2px solid red;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0168fa;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            position: static;
            color: #fff;
        }


        .select2-dropdown,
        .select2-results__options { overflow-x: hidden; } 
        .select2-results__option {
            white-space: normal; 
            line-height: 1.25;
        }

        td.merge-same {
        color: transparent; 
        border-top: none !important;
        }
        td.merge-same::selection { color: transparent; }
        
        table.dataTable.row-border tbody th, table.dataTable.row-border tbody td, table.dataTable.display tbody th, table.dataTable.display tbody td {
            border-bottom: 1px solid #fff !important;
        }


    </style>
@endsection

@section('pagetitle')
    <table width="100%" style="font-size:18px;font-weight:bold;"><tr><td class="bord" align="center">Forecast Report {!! $datetxt !!} {!!$dbranch!!}</td></tr></table>
    <table width="40%" border="1" style="font-size:14px;font-weight:bold;">
        <tr>
            <td>TOTAL WHOLE LECHON ORDER:</td>
            <td align="center">{{$total_lechon_order}}</td>
        </tr>
        <tr>
            <td>TOTAL PANTAGA:</td>
            <td align="center">{{collect($jo)->where('jo_category','=','Pantaga')->sum('qty')}}</td>
        </tr>
        <tr>
            <td>TOTAL BELLY PANTAGA:</td>
            <td align="center">{{collect($jo)->where('jo_category','=','Belly Pantaga')->sum('qty')}}</td>
        </tr>
        <tr>
            <td>TOTAL DISPLAY:</td>
            <td align="center">{{collect($jo)->where('jo_category','=','Display')->sum('qty')}}</td>
        </tr>
        <tr>
            <td>TOTAL ALPHA SIZE:</td>
            <td align="center">{{collect($jo)->where('jo_category','=','Alpha Size')->sum('qty')}}</td>
        </tr>
        <tr>
            <td>OVERALL TOTAL LECHON: </td>
            <td align="center">{{$total_lechon_overall}}</td>
        </tr>
        <tr>
            <td>TOTAL MISC QTY:</td>
            <td align="center">{{$total_misc}}</td>
        </tr>
    </table>
@endsection

@section('content')

         
        <div class="container-fluid">
            <div class="text-center mg-b-20">
                <img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
                <h4 class="mg-b-0 tx-spacing--1">Forecaster Report</h4>
                {!! $datetxt !!} {!!$dbranch!!}
            </div>
            <input type="hidden" id="datetxt" value="{!! $datetxt !!}">
            <input type="hidden" id="dbranch" value="{!! $dbranch !!}">

            <div class="row-sm">
                <div class="col-md-12">
                    <form action="{{route('admin.report.forecaster')}}" method="get">
                      
                        @csrf
                        <div class="row row-sm">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">Start Date (Date Needed)</label>
                                    <input type="date" class="form-control input-sm" name="startdate" autocomplete="off" value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset">
                                </div>
                            </div>
                           
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">End (Date Needed)</label>
                                    <input type="date" class="form-control input-sm" name="enddate" autocomplete="off" value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13" style="font-size:15px;font-weight:bold;">Receiver Branch</label>
                                    <select class="receiver-branch-multiple w-100" name="receiver[]" multiple="multiple" >
                                        @php
                                            $selectedReceivers = collect(old('receiver', request('receiver', [])))->map(fn($v) => (string)$v)->all();
                                        @endphp

                                        @forelse(\App\EcommerceModel\Branch::orderBy('name')->get() as $cus)
                                            <option value="{{ $cus->id }}"
                                                {{ in_array((string)$cus->id, $selectedReceivers, true) ? 'selected' : '' }}>
                                                {{ $cus->name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4 filter-action mg-r-5">
                                <a href="#" onclick="$('#adv').toggle();" class="btn btn-success mg-t-7 mg-r-5 btn-sm">Advance Filter</a>
                                <button type="submit" class="btn btn-primary mg-t-7 mg-r-5 btn-sm">Generate</button>
                                <a href="{{route('admin.report.forecaster')}}" class="btn btn-info mg-t-7 mg-r-5 btn-sm">Reset</a>
                            </div>
                        </div>
                        <div class="row" id="adv" style="display:none;">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Time Needed</label>
                                    <select name="start_time" id="start_time" class="form-control">      
                                        <option value="">- Select Time -</option>
                                        <option value="07:00:00">07:00 AM</option>                                         
                                        <option value="12:00:00">12:00 NOON</option>                                        
                                        <option value="14:00:00">02:00 PM</option>
                                        <option value="17:00:00">05:00 PM</option>                             
                                        <option value="00:00:00">12:00 AM</option>
                                        <option value="01:00:00">01:00 AM</option>
                                        <option value="02:00:00">02:00 AM</option>
                                        <option value="03:00:00">03:00 AM</option>
                                        <option value="04:00:00">04:00 AM</option>
                                        <option value="05:00:00">05:00 AM</option>
                                        <option value="06:00:00">06:00 AM</option>
                                        <option value="08:00:00">08:00 AM</option>
                                        <option value="09:00:00">09:00 AM</option>
                                        <option value="10:00:00">10:00 AM</option>
                                        <option value="11:00:00">11:00 AM</option>
                                        <option value="13:00:00">01:00 PM</option>
                                        <option value="15:00:00">03:00 PM</option>
                                        <option value="16:00:00">04:00 PM</option>
                                        <option value="18:00:00">06:00 PM</option>
                                        <option value="19:00:00">07:00 PM</option>
                                        <option value="20:00:00">08:00 PM</option>
                                        <option value="21:00:00">09:00 PM</option>
                                        <option value="22:00:00">10:00 PM</option>
                                        <option value="23:00:00">11:00 PM</option>
                                        @isset($_GET['start_time'])
                                            <option value="{{$_GET['start_time']}}" selected="selected">{{ $_GET['start_time'] }}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Customer</label>
                                    <select name="customer" id="customer" class="form-control">
                                        <option value="">- Select Customer -</option>
                                        @forelse(\App\EcommerceModel\SalesHeader::select('customer_name')->distinct('customer_name')->orderBy('customer_name')->get() as $cus)

                                            <option value="{{$cus->customer_name}}">{{$cus->customer_name}}</option>
                                        @empty
                                        @endforelse
                                        @isset($_GET['customer'])
                                            <option value="{{$_GET['customer']}}" selected="selected">{{ $_GET['customer'] }}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Product</label>
                                    <select name="product" id="product" class="form-control">
                                        <option value="">- Select Product -</option>
                                        @forelse(\App\Models\Product::select('name')->distinct('name')->orderBy('name')->get() as $cus)
                                            <option value="{{$cus->name}}">{{$cus->name}}</option>
                                        @empty
                                        @endforelse
                                        @isset($_GET['product'])
                                            <option value="{{$_GET['product']}}" selected="selected">{{ $_GET['product'] }}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Production Branch</label>
                                    <select name="production_branch" id="production_branch" class="form-control">
                                        <option value="">- Select Branch -</option>
                                        @forelse(\App\EcommerceModel\ProductionBranch::orderBy('name')->get() as $cus)
                                            <option value="{{$cus->id}}">{{$cus->name}}</option>
                                        @empty
                                        @endforelse

                                        @if(isset($_GET['production_branch']) && strlen($_GET['production_branch'])>0)                                            
                                            @php 
                                                $bb = \App\EcommerceModel\ProductionBranch::whereId($_GET['production_branch'])->first(); 
                                            @endphp
                                            <option value="{{$_GET['production_branch']}}" selected="selected">{{ $bb->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>                       
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Order Source</label>
                                    <select name="order_source" id="order_source" class="form-control">
                                        <option value="">- Select Source -</option>
                                        @forelse(\App\EcommerceModel\SalesHeader::select('order_source')->distinct('order_source')->orderBy('order_source')->get() as $cus)
                                            <option value="{{$cus->order_source}}">{{$cus->order_source}}</option>
                                        @empty
                                        @endforelse
                                        @isset($_GET['order_source'])
                                            <option value="{{$_GET['order_source']}}" selected="selected">{{ $_GET['order_source'] }}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Order Type</label>
                                    <select name="order_type" id="order_type" class="form-control">
                                        <option value="">- Select Item Type -</option>
                                        <option value="Whole">Whole</option>
                                        <option value="Reserved">Reserved</option>                                                                           
                                        <option value="Additional">Additional</option>                                                                           
                                        @isset($_GET['order_type'])
                                            <option value="{{$_GET['order_type']}}" selected="selected">{{ $_GET['order_type'] }}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Item Type</label>
                                    <select name="item_type[]" id="item_type" class="form-control" multiple="multiple">
                                        <option value="">- Select Item Type -</option>
                                        <option @if(isset($_GET['item_type']) && in_array("Miscellaneous",$_GET['item_type'])) selected="selected" @endif value="Miscellaneous">Miscellaneous</option>
                                        <option @if(isset($_GET['item_type']) && in_array("WRA",$_GET['item_type'])) selected="selected" @endif value="WRA">WRA</option>
                                        <option @if(isset($_GET['item_type']) && in_array("Belly Pantaga",$_GET['item_type'])) selected="selected" @endif value="Belly Pantaga">Belly Pantaga</option>
                                        <option @if(isset($_GET['item_type']) && in_array("Pantaga",$_GET['item_type'])) selected="selected" @endif value="Pantaga">Pantaga</option>
                                        <option @if(isset($_GET['item_type']) && in_array("Display",$_GET['item_type'])) selected="selected" @endif value="Display">Display</option>
                                        <option @if(isset($_GET['item_type']) && in_array("Alpha Size",$_GET['item_type'])) selected="selected" @endif value="Alpha Size">Alpha Size</option>                                                                           
                                        
                                    </select>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
            <div class="row row-sm">
                <div class="col-md-12">
                    <table width="40%" border="1" style="font-size:14px;font-weight:bold;">
                        <tr>
                            <td>TOTAL WHOLE LECHON ORDER:</td>
                            <td align="center">{{$total_lechon_order}}</td>
                        </tr>
                        <tr>
                            <td>TOTAL PANTAGA:</td>
                            <td align="center">{{collect($jo)->where('jo_category','=','Pantaga')->sum('qty')}}</td>
                        </tr>
                        <tr>
                            <td>TOTAL BELLY PANTAGA:</td>
                            <td align="center">{{collect($jo)->where('jo_category','=','Belly Pantaga')->sum('qty')}}</td>
                        </tr>
                        <tr>
                            <td>TOTAL DISPLAY:</td>
                            <td align="center">{{collect($jo)->where('jo_category','=','Display')->sum('qty')}}</td>
                        </tr>
                        <tr>
                            <td>TOTAL ALPHA SIZE:</td>
                            <td align="center">{{collect($jo)->where('jo_category','=','Alpha Size')->sum('qty')}}</td>
                        </tr>
                        <tr>
                            <td>OVERALL TOTAL LECHON: </td>
                            <td align="center">{{$total_lechon_overall}}</td>
                        </tr>
                        <tr>
                            <td>TOTAL MISC QTY:</td>
                            <td align="center">{{$total_misc}}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if(isset($rs))
                <div class="row row-sm">
                    <!-- Start Filters -->
                    <div class="col-md-12">
                        <table  id="example" border="1" class="display nowrap" style="width:100%;font: bold 13px/150% Arial, sans-serif, Helvetica;">
                            <thead>
                            <tr> 
                                   
                                <th>Qty</th> 
                                <th>Product</th>  
                                <th>Customer Address</th> 
                                <th>Price</th>   
                                <th>Payment</th>
                                <th>Delivery Address</th> 
                                <th>Customer</th>                                
                                <th>Date Needed</th>
                                <th>Time Needed</th>  
                                <th>Note</th>                                
                                <th>Production Date</th>
                                <th>Production Time</th>
                                <th>Delivery Type</th>  
                                <th>JO#</th>
                                <th>Production Branch</th>
                                <th>Status</th>                                
                                <th>Agent</th>  
                                <th>Order#</th>
                                <th>Contact Person</th>                                
                                <th>Contact Number</th>                                
                                <th>DR#</th>
                                <th>Del Fee</th>
                                <th>Total</th>                                
                                <th>Releasing</th>  
                                <th>Order Source</th>   
                                <th>Pickup Branch</th>   
                                <th>Item Category</th>   
                                <th>Encoded By</th>          
                                <th>Order Type</th> 
                                <th>Item Type</th>
                                <th>Forecaster Date</th>     
                                <th>Delivery Branch</th>
                                <th>Encoded Date</th>  
                                <th>Encoded Time</th>   
                                <th>Delivery Status</th>                     
                            </tr>
                            </thead>
                                <tbody>
                                @php

    /** 1) Filter out unconfirmed sales only (JO unaffected) */
    $results = $results->filter(fn($r) => ($r->trantype ?? '') !== 'sales' || (int)($r->isConfirm ?? 0) === 1);

    /** 2) Sort unified list by delivery_date ASC (then stable tie-breakers incl. order_number) */
    $results = $results->sort(function($a, $b) {
        $ad = strtotime((string)($a->delivery_date ?? '')) ?: 0;
        $bd = strtotime((string)($b->delivery_date ?? '')) ?: 0;
        if ($ad !== $bd) return $bd <=> $ad;

        return strcmp((string)$a->trantype, (string)$b->trantype)
            ?: strcmp((string)$a->order_number, (string)$b->order_number)
            ?: strcmp((string)($a->contact_person ?? ''), (string)($b->contact_person ?? ''))
            ?: strcmp((string)$a->customer_name, (string)$b->customer_name)
            ?: strcmp((string)$a->timeneeded, (string)$b->timeneeded)
            ?: strcmp((string)$a->dateneeded, (string)$b->dateneeded);
    })->values();

    /** 3) Preload payments for sales */
    $allHids = $results->filter(fn($r) => ($r->trantype ?? '') === 'sales')
                       ->pluck('hid')->filter()->unique()->values()->all();
    $paymentsByHid = $allHids
        ? \App\EcommerceModel\SalesPayment::whereIn('sales_header_id', $allHids)->get()->groupBy('sales_header_id')
        : collect();

    /** Helpers */
    $formatAddress = function($r) {
        $street = trim((string)($r->address_street ?? ''));
        $mun    = trim((string)($r->address_municipality ?? ''));
        $city   = trim((string)($r->address_city ?? ''));
        $reg    = trim((string)($r->address_region ?? ''));
        $out = '';
        if ($street !== '') $out .= $street;
        if ($mun !== '')    $out .= ($out ? '<br>' : '') . $mun . ',';
        if ($city !== '')   $out .= '<br>' . $city . ',';
        if ($reg !== '')    $out .= ' ' . $reg;
        return $out;
    };
    $chunkWords = function($text, $per=3) {
        $text = trim((string)($text ?? ''));
        if ($text === '') return '';
        $parts = preg_split('/\s+/', $text);
        $chunks = array_chunk($parts, $per);
        return collect($chunks)->map(fn($c) => e(implode(' ', $c)))->implode('<br>');
    };
    $fmtDate = fn($d, $out='m-d-Y') =>
        (strtotime((string)$d) && date('Y-m-d', strtotime((string)$d)) !== '1970-01-01')
            ? date($out, strtotime((string)$d)) : '';
    $fmtTime = function($d) {
        if (!strtotime((string)$d)) return '';
        $t = date('h:i A', strtotime((string)$d));
        return $t === '12:00 PM' ? '12:00 NOON' : $t;
    };

    /** 4) Inline “grouping” state for sales */
    $lastGroupKey = null;   // when same as current → mark merged columns as .merge-same
@endphp

@foreach($results as $r)
    @php
        $isSales = (($r->trantype ?? '') === 'sales');

        // compute group key only for sales rows (order_number + delivery_date + contact_person + customer_name)
        $groupKey = $isSales
            ? implode('|', [
                (string)$r->order_number,
                (string)$r->delivery_date,
                (string)($r->contact_person ?? ''),
                (string)$r->customer_name,
              ])
            : null;

        // are we inside the same sales group as previous row?
        $isMerged = $isSales && ($groupKey === $lastGroupKey);

        // “group-level” values (for sales; JO prints per row)
        $addressHtml   = $formatAddress($r);
        $payments      = $isSales ? ($paymentsByHid->get($r->hid ?? 0, collect())) : collect();
        $custAddrSafe  = strip_tags($r->customer_delivery_adress ?? '');
        $contactMerged = $isSales ? (($r->contact_person ?? '') ?: ($r->customer_name ?? '')) : '';
        $deliveryDate  = $fmtDate($r->delivery_date);
        $deliveryTime  = $fmtTime($r->delivery_date);
        $noteChunked   = $chunkWords($r->instruction ?? '');
        $delDate       = $fmtDate($r->deldate);
        $delTime       = $fmtTime($r->deldate);
        $deliveryType  = (string)($r->delivery_type ?? '');
        $delstat       = (string)($r->delstat ?? '');
        $agent         = (string)($r->agent ?? '');
        $orderNoText   = (string)($r->order_number ?? '');
        $customerName  = (string)($r->customer_name ?? '');
        $contactNo     = (string)($r->customer_contact_number ?? '');
        $delFee        = number_format((float)($r->delivery_fee_amount ?? 0), 2);
        $orderSource   = (string)($r->order_source ?? '');
        $receiver      = (string)($r->receiver ?? '');
        $username      = (string)($r->username ?? '');
        $forecastDt    = (string)($r->forecast_dt ?? '');
        $branch        = (string)(($r->mbranch ?? '') ?: ($r->del_branch ?? ''));
        $createdDate   = $fmtDate($r->created, 'Y-m-d');
        $createdTime   = $fmtTime($r->created);
    @endphp

    <tr style="text-align:left">
        {{-- Qty --}}
        <td class="bord">{{ number_format((float)($r->qty ?? 0), 2) }}</td>

        {{-- Product / JO Category --}}
        <td class="bord" style="font-weight:normal">
            @if($isSales)
                {!! function_exists('highlightPaella')
                    ? highlightPaella($r->dproduct_name ?? $r->product_name)
                    : e($r->dproduct_name ?? $r->product_name) !!}
            @else
                {{ e($r->jo_category ?? '') }}
            @endif
        </td>

        {{-- Customer Address (merged for sales, per-row for JO) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}"
            data-value="{{ strip_tags($addressHtml) }}">
            {!! $isSales
                ? ($isMerged ? '&nbsp;' : $addressHtml)
                : ($addressHtml !== '' ? $addressHtml : '&nbsp;') !!}
        </td>

        {{-- Price --}}
        <td class="bord">{{ number_format((float)($r->price ?? 0), 2) }}</td>

        {{-- Payment (merged for sales; blank for JO) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}"
            data-value="{{ $isSales && $payments->count() ? $payments->map(fn($p)=>$p->payment_type.': '.number_format((float)$p->amount,2))->implode(', ') : '' }}">
            @if($isSales && !$isMerged && $payments->count())
            <table>
                @foreach($payments as $pp)
                    <tr>
                        <td class="bord">{{ e($pp->payment_type) }}</td>
                        <td class="bord">{{ number_format((float)$pp->amount, 2) }}</td>
                    </tr>
                @endforeach
            </table>
            @else &nbsp; @endif
        </td>

        {{-- Delivery Address (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $custAddrSafe }}">
            {!! $isSales ? ($isMerged ? '&nbsp;' : $chunkWords($r->customer_delivery_adress ?? ''))
                         : e($r->customer_delivery_adress ?? '') !!}
        </td>

        {{-- Customer (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $isSales ? $contactMerged : (string)($r->customer_name ?? '') }}">
            {!! $isSales ? ($isMerged ? '&nbsp;' : $chunkWords($contactMerged))
                         : e((strlen((string)($r->customer_name ?? '')) < 2) ? ($r->customer_delivery_adress ?? '') : ($r->customer_name ?? '')) !!}
        </td>

        {{-- Date Needed (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $deliveryDate }}">{{ $isSales ? ($isMerged ? '' : $deliveryDate) : $deliveryDate }}</td>

        {{-- Time Needed (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $deliveryTime }}">{{ $isSales ? ($isMerged ? '' : $deliveryTime) : $deliveryTime }}</td>

        {{-- Note (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ strip_tags($noteChunked) }}">
            {!! $isSales ? ($isMerged ? '&nbsp;' : $noteChunked) : e($r->instruction ?? '') !!}
        </td>

        {{-- Production Date / Time (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $delDate }}">{{ $isSales ? ($isMerged ? '' : $delDate) : $fmtDate($r->deldate) }}</td>
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $delTime }}">{{ $isSales ? ($isMerged ? '' : $delTime) : $fmtTime($r->deldate) }}</td>

        {{-- Delivery Type (merged for sales; JO blank) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $isSales ? $deliveryType : '' }}">
            {{ $isSales ? ($isMerged ? '' : e($deliveryType)) : '' }}
        </td>

        {{-- JO# --}}
        <td class="bord">{{ e($r->jo_number ?? '') }}</td>

        {{-- Production Branch --}}
        <td class="bord">{{ e($r->pbname ?? '') }}</td>

        {{-- Status (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $delstat }}">
            {{ $isSales ? ($isMerged ? '' : e($delstat)) : e($r->delstat ?? '') }}
        </td>

        {{-- Agent (merged for sales; JO blank) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $agent }}">
            {{ $isSales ? ($isMerged ? '' : e($agent)) : '' }}
        </td>

        {{-- Order# (merged for sales; JO blank) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $orderNoText }}">
            @if($isSales && !$isMerged)
                @if(!empty($r->hid))
                    <a target="_blank" href="{{ route('sales.print', base64_encode($r->hid)) }}">{{ e($orderNoText) }}</a>
                @else
                    {{ e($orderNoText) }}
                @endif
            @else
                {{ $isSales ? '' : '' }}
            @endif
        </td>

        {{-- Contact Person / Customer Name (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $customerName }}">
            {{ $isSales ? ($isMerged ? '' : e($customerName)) : e($r->customer_name ?? '') }}
        </td>

        {{-- Contact Number (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $contactNo }}">
            {{ $isSales ? ($isMerged ? '' : e($contactNo)) : e($r->customer_contact_number ?? '') }}
        </td>

        {{-- DR# (merged for sales, left blank) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="">{{ $isSales ? '' : '' }}</td>

        {{-- Del Fee (merged for sales; JO per-row=0) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $delFee }}">
            {{ $isSales ? ($isMerged ? '' : $delFee) : '0' }}
        </td>

        {{-- Total (per row) --}}
        <td class="bord">{{ number_format((float)($r->price ?? 0) * (float)($r->qty ?? 0), 2) }}</td>

        {{-- Releasing (merged for sales; JO blank) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="">{{ $isSales ? '' : '' }}</td>

        {{-- Order Source (merged for sales; JO = "Forecaster") --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $isSales ? $orderSource : 'Forecaster' }}">
            {{ $isSales ? ($isMerged ? '' : e($orderSource)) : 'Forecaster' }}
        </td>

        {{-- Pickup Branch (merged for sales; JO per-row receiver) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $isSales ? $receiver : (string)($r->receiver ?? '') }}">
            {{ $isSales ? ($isMerged ? '' : e($receiver)) : e($r->receiver ?? '') }}
        </td>

        {{-- Item Category --}}
        <td class="bord">{{ e($r->catname ?? '') }}</td>

        {{-- Encoded By (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $username }}">
            {{ $isSales ? ($isMerged ? '' : e($username)) : e($r->username ?? '') }}
        </td>

        {{-- Order Type --}}
        <td class="bord">{{ e($r->hordertype ?? '') }}</td>

        {{-- Item Type (misc/WRA logic; JO = product_name or overrides) --}}
        @php
            $itemType = '';
            if ((int)($r->is_misc ?? 0) === 1) $itemType = 'Miscellaneous';
            if (isset($wra_array) && is_array($wra_array) && in_array($r->prodid ?? null, $wra_array)) $itemType = 'WRA';
            if (!$isSales && $itemType === '') $itemType = (string)($r->product_name ?? '');
        @endphp
        <td class="bord">{{ e($itemType) }}</td>

        {{-- Forecaster Date (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $forecastDt }}">
            {{ $isSales ? ($isMerged ? '' : e($forecastDt)) : e($r->forecast_dt ?? '') }}
        </td>

        {{-- Delivery Branch (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $branch }}">
            {{ $isSales ? ($isMerged ? '' : e($branch)) : e(($r->mbranch ?? '') ?: ($r->del_branch ?? '')) }}
        </td>

        {{-- Encoded Date / Time (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $createdDate }}">
            {{ $isSales ? ($isMerged ? '' : $createdDate) : $fmtDate($r->created, 'Y-m-d') }}
        </td>
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $createdTime }}">
            {{ $isSales ? ($isMerged ? '' : $createdTime) : $fmtTime($r->created) }}
        </td>

        {{-- Delivery Status (duplicate of status; merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' : '' }}" data-value="{{ $delstat }}">
            {{ $isSales ? ($isMerged ? '' : e($delstat)) : e($r->delstat ?? '') }}
        </td>
    </tr>

    @php
        // update last sales group key (only for sales rows)
        if ($isSales) $lastGroupKey = $groupKey;
        else $lastGroupKey = null; // reset between JO and next sales group (keeps logic simple)
    @endphp
@endforeach
                                </tbody>


                        </table>
                    </div>
                    <!-- End Filters -->
                </div>
            @endif

        </div>
        <!-- container -->
    </div>
   

@endsection

@section('pagejs')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/prismjs/prism.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>

@endsection

@section('customjs')
<script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.colVis.min.js') }}"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/time.js"></script>
<script>

    (function() {
        const $table = $('#example');
        const expected = $table.find('thead th').length;

        $table.find('tbody th').each(function() {
            const td = $('<td/>', { class: this.className, html: this.innerHTML });
            $(this).replaceWith(td);
        });

        $table.find('tbody td').removeAttr('rowspan').removeAttr('colspan');

        $table.find('tbody tr').each(function(i) {
            const $cells = $(this).children('td');
            const diff = expected - $cells.length;
            if (diff !== 0) {
                console.warn('[DT] Row', i, 'has', $cells.length, 'cells; expected', expected, '→ padding with', diff);
                for (let k = 0; k < diff; k++) {
                    $(this).append('<td class="bord"></td>');
                }
            }
        });
    })();

    $('#example').DataTable({
        dom: 'Bfrtip',
        pageLength: 28,
        aaSorting: [],
        bSort: false,
        searching: false,
        asStripeClasses: [],
        buttons: [
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible',
                    stripHtml: false,
                    format: {
                    body: function (data, row, col, node) {
                        const val = $(node).attr('data-value');
                        return val !== undefined ? val : data;
                    }
                    }
                },
                customize: function (win) {
                    $(win.document.body).css('font-size','12pt').css('font-weight','bold').prepend('Forecast Report');
                    $(win.document.body).find('h1').css('font-weight','bold').css('font-size','14pt');
                    $(win.document.body).find('table').addClass('compact').css('font-size','inherit');
                    $(win.document.body).find('td').css('border','1px solid green');
                }
            },
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':visible',
                    format: { body: (data, r, c, node) => $(node).attr('data-value') ?? data }
                }
            },
            {
                extend: 'csv',
                title: '{{ date('Ymd') }}',
                exportOptions: {
                    columns: ':visible',
                    format: { body: (data, r, c, node) => $(node).attr('data-value') ?? data }
                }
            },
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: '{{ date('Ymd') }}',
                exportOptions: {
                    columns: ':visible',
                    format: { body: (data, r, c, node) => $(node).attr('data-value') ?? data }
                }
            },
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Forecast Report',
                exportOptions: {
                    columns: ':visible',
                    format: { body: (data, r, c, node) => $(node).attr('data-value') ?? data }
                }
            },
            'colvis'
        ],
        columnDefs: [
            // keep this list in sync with your actual header count
            { targets: [2,4,5,7,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34], visible: false }
        ]
    });


    $(document).ready(function() {
        $(document).ready(function() {
            $('.receiver-branch-multiple').select2({
                placeholder: "Select a branch",
                width: '300px',
                dropdownAutoWidth: true
            });
        });
    } );

    
</script>
@endsection