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
    foreach(collect($original_results) as $j){
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

@section('pagetitle', 'Forecaster Report ' . strip_tags($datetxt) . ' ' . strip_tags($dbranch))

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
            border: 2px solid black;
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

@media print {
  /* unlock any scroll/height clamps */
  html, body { height:auto !important; overflow:visible !important; }
  .modal, .modal-dialog, .modal-content, .modal-body,
  .content, .page-content, .wrapper, .container,
  .dataTables_wrapper, .dataTables_scroll, .dataTables_scrollHead, .dataTables_scrollBody,
  [style*="overflow"], [class*="scroll"], [class*="overflow"],
  [style*="max-height"], [style*="height:"]
  { position:static !important; overflow:visible !important; height:auto !important; max-height:none !important; }

  /* neutralize HTML border="1" */
  table[border] { border:0 !important; }

  /* === FULL GRID with merge support (scoped to #example only) === */
  #example { border-collapse: collapse !important; width:100% !important; font-size: 16px !important; }
  #example th, 
  #example td,
  #example td.bord, 
  #example th.bord {             /* override any .bord styles */
    border: 1px solid #000 !important;
  }


/* merged blocks */
  #example td.merge-same { border-top:    0 !important; }   /* continuation rows */
  #example td.merge-first { border-bottom: 0 !important; }   /* first row of block */

  /* keep header underline even if header cells are "merged" */
  #example thead th { border-bottom: 1px solid #000 !important; }

  /* allow wrapping for nowrap tables */
  #example.display.nowrap td, 
  #example.display.nowrap th { white-space: normal !important; }

  /* hide DT chrome */
  .dataTables_filter, .dataTables_info, .dataTables_length,
  .dataTables_paginate, .dt-buttons, .no-print { display:none !important; }

  /* avoid broken borders on page breaks */
  #example tr, #example td, #example th { page-break-inside: avoid; }
  .d-none {
    display: flex !important;
    justify-content: center !important;
  }
  .subtext {
    font-size: 1rem !important;
    /* line-height: 25px !important; */
    position: relative; 
    top: -20px; 
  }

  .border-print {
    display: none;
    border: 2px solid black;
    padding-top: 10px;
    padding-bottom: 5px !important;
  }

  .print-table {
    margin-top: 3px;
    
  }

  .print-table td {
    border: 1px solid black !important;
    padding: 1px !important;
  }
}









    </style>
@endsection

@section('content')

        <div class="container-fluid">
            <div class="text-center mg-b-20 border-print">
                <img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="" class="no-print">
                <h4 class="" style="font-weight:bold; line-height: 20px">Forecaster Report</h4>
                <div class="subtext" style="font-weight: bold;">{!! $datetxt !!} {!!$dbranch!!}</div>
            </div>
            <input type="hidden" id="datetxt" value="{!! $datetxt !!}">
            <input type="hidden" id="dbranch" value="{!! $dbranch !!}">

            <div class="row-sm">
                <div class="col-md-12">
                    <form action="{{route('admin.report.forecaster')}}" method="get">
                      
                        @csrf
                        <div class="row row-sm no-print">
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

                                        @forelse(\App\EcommerceModel\Branch::where('status', 1)->orderBy('name')->get() as $cus)
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

                                        @foreach (range(0,23) as $hour)
                                            @php
                                            $timeValue = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
                                            $timeLabel = date('h:i A', strtotime($timeValue));
                                            if ($timeLabel === '12:00 PM') {
                                                $timeLabel = '12:00 NOON';
                                            }
                                            @endphp
                                            <option @if(isset($_GET['start_time']) && $_GET['start_time'] == $timeValue) selected="selected" @endif value="{{ $timeValue }}">{{ $timeLabel }}</option>
                                        @endforeach

                                        {{-- <option value="07:00:00">07:00 AM</option>                                         
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
                                        <option value="23:00:00">11:00 PM</option> --}}
                                        {{-- @isset($_GET['start_time'])
                                            <option value="{{$_GET['start_time']}}" selected="selected">{{ $_GET['start_time'] }}</option>
                                        @endisset --}}
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
                                        @forelse(\App\EcommerceModel\SalesHeader::select('order_source')->where('created_at', '>=', '2025-10-01')->distinct('order_source')->orderBy('order_source')->get() as $cus)
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
                                        <option value="Buhat">Buhat</option>
                                        <option value="Additional">Additional</option>                                                                           
                                        <option value="Reserve">Reserve</option>                                                                                    
                                        <option value="Miscellaneous">Miscellaneous</option>                                                                            
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
                    <table class="d-none" width="100%" style="font-size:18px;font-weight:bold; border: 1px solid black;">
                        <tr>
                            <td style="text-align:center; width:100%;">Forecast Report {!! $datetxt !!} {!! $dbranch !!}</td>
                        </tr>
                    </table>

                    <table class="print-table" width="100%" cellpadding="5" cellspacing="0" style="font-size: 14px; font-weight:bold;" >
                        <tr>
                            <!-- LEFT: Existing totals table -->
                            <td width="60%" valign="top">
                                <table class="print-table" border="1" width="100%" style="font-size:14px; font-weight:bold;">
                                    <tr>
                                        <td>TOTAL WHOLE LECHON ORDER:</td>
                                        <td align="center">
                                            <a href="{{ request()->fullUrlWithQuery(['filter' => 'whole-lechon']) }}">
                                                {{ $total_lechon_order }}
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TOTAL PANTAGA:</td>
                                        <td align="center">
                                            <a href="{{ request()->fullUrlWithQuery(['filter' => 'pantaga']) }}">
                                                {{collect($jo)->where('jo_category','=','Pantaga')->sum('qty')}}
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TOTAL BELLY PANTAGA:</td>
                                        <td align="center">
                                            <a href="{{ request()->fullUrlWithQuery(['filter' => 'belly-pantaga']) }}">
                                                {{collect($jo)->where('jo_category','=','Belly Pantaga')->sum('qty')}}
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TOTAL DISPLAY:</td>
                                        <td align="center">
                                            <a href="{{ request()->fullUrlWithQuery(['filter' => 'display']) }}">
                                                {{collect($jo)->where('jo_category','=','Display')->sum('qty')}}
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TOTAL ALPHA SIZE:</td>
                                        <td align="center">
                                            <a href="{{ request()->fullUrlWithQuery(['filter' => 'alpha-size']) }}">
                                                {{collect($jo)->where('jo_category','=','Alpha Size')->sum('qty')}}
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>OVERALL TOTAL LECHON:</td>
                                        <td align="center">
                                            <a href="{{ request()->fullUrlWithQuery(['filter' => 'overall-lechon']) }}">
                                                {{$total_lechon_overall}}
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TOTAL MISC QTY:</td>
                                        <td align="center">
                                            <a href="{{ request()->fullUrlWithQuery(['filter' => 'misc']) }}">
                                                {{$total_misc}}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <!-- RIGHT: Size breakdown -->
                            <td width="40%" valign="top">
                                <table class="print-table" border="1" width="100%" style="font-size:14px; font-weight:bold;">
                                    <tr>
                                        <td colspan="2" align="center">SIZE BREAKDOWN</td>
                                    </tr>

                                    @foreach ($sizeCounts as $size => $count)
                                        <tr>
                                            <td>{{ strtoupper($size) }}</td>
                                            <td align="center">{{ $count }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
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
    // $results = $results->filter(fn($r) => ($r->trantype ?? '') !== 'sales' || (int)($r->isConfirm ?? 0) === 1);

    /** 2) Sort unified list by delivery_date ASC (then stable tie-breakers incl. order_number) */
    // $results = $results->sort(function($a, $b) {
    //     $ad = strtotime((string)($a->delivery_date ?? '')) ?: 0;
    //     $bd = strtotime((string)($b->delivery_date ?? '')) ?: 0;
    //     if ($ad !== $bd) return $bd <=> $ad;

    //     return strcmp((string)$a->trantype, (string)$b->trantype)
    //         ?: strcmp((string)$a->order_number, (string)$b->order_number)
    //         ?: strcmp((string)($a->contact_person ?? ''), (string)($b->contact_person ?? ''))
    //         ?: strcmp((string)$a->customer_name, (string)$b->customer_name)
    //         ?: strcmp((string)$a->timeneeded, (string)$b->timeneeded)
    //         ?: strcmp((string)$a->dateneeded, (string)$b->dateneeded);
    // })->values();

    // Date DESC (by day), then TIME ASC (within the same day), then stable tie-breakers
    $results = $results->sort(function ($a, $b) {
        $ta = strtotime((string)($a->delivery_date ?? '')) ?: 0;
        $tb = strtotime((string)($b->delivery_date ?? '')) ?: 0;

        $da = $ta ? date('Y-m-d', $ta) : '';
        $db = $tb ? date('Y-m-d', $tb) : '';
        if ($da !== $db) {
            return strcmp($db, $da); // newer date first
        }

        $ha = $ta ? date('H:i:s', $ta) : '00:00:00';
        $hb = $tb ? date('H:i:s', $tb) : '00:00:00';
        $tcmp = strcmp($ha, $hb);
        if ($tcmp !== 0) return $tcmp;

        return strcmp((string)$a->trantype, (string)$b->trantype)
            ?: strcmp((string)$a->order_number, (string)$b->order_number)
            ?: strcmp((string)($a->contact_person ?? ''), (string)($b->contact_person ?? ''))
            ?: strcmp((string)$a->customer_name, (string)$b->customer_name);
    })->values();


    /** 3) Preload payments for sales */
    $allHids = $results->filter(fn($r) => ($r->trantype ?? '') === 'sales')
                       ->pluck('hid')->filter()->unique()->values()->all();


    $ids = [];                 
    $headers = \App\EcommerceModel\SalesHeader::whereIn('id', $allHids)->get();

    foreach($headers as $head){
        if (isset($head->is_sub) && $head->is_sub == 1) {
            $parentSale = \App\EcommerceModel\SalesHeader::where('id', $head->parent_sales_header_id)->first();
            $ids[] = $parentSale->id;
        } else {
            $ids[] = $head->id;
        }
    }

    $paymentsByHid = $allHids
        ? \App\EcommerceModel\SalesPayment::whereIn('sales_header_id', $ids)->get()->groupBy('sales_header_id')
        : collect();

    // Helpers
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
        $payments      = $isSales ? ($paymentsByHid->get($r->parent_sales_header_id > 0 ? $r->parent_sales_header_id : ($r->hid ?? 0), collect())) : collect();
        $custAddrSafe  = strip_tags($r->customer_delivery_adress ?? '');
        $contactMerged = $r->contact_person ?? $r->customer_name ?? '';
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
        $receiver      = (string)($r->outlet ?? '');
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
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }}"
            data-value="{{ strip_tags($addressHtml) }}">
            {!! $isSales
                ? ($isMerged ? '&nbsp;' : $addressHtml)
                : ($addressHtml !== '' ? $addressHtml : '&nbsp;') !!}
        </td>

        {{-- Price --}}
        <td class="bord">{{ number_format((float)($r->price ?? 0), 2) }}</td>

        {{-- Payment (merged for sales; blank for JO) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}"
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
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $custAddrSafe }}">
            {!! $isSales ? ($isMerged ? '&nbsp;' : $chunkWords($r->customer_delivery_adress ?? ''))
                         : e($r->customer_delivery_adress ?? '') !!}
        </td>

        {{-- Customer (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $isSales ? $r->customer_name : (string)($r->customer_name ?? '') }}">
            {{ $r->customer_name ?? $r->customer_delivery_adress ?? '' }}
        </td>

        {{-- Date Needed (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $deliveryDate }}">{{ $isSales ? ($isMerged ? '' : $deliveryDate) : $deliveryDate }}</td>

        {{-- Time Needed (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $deliveryTime }}">{{ $isSales ? ($isMerged ? '' : $deliveryTime) : $deliveryTime }}</td>

        {{-- Note (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ strip_tags($noteChunked) }}">
            {!! $isSales ? ($isMerged ? '&nbsp;' : $noteChunked) : e($r->instruction ?? '') !!}
        </td>

        {{-- Production Date / Time (merged for sales) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $delDate }}">{{ $isSales ? ($isMerged ? '' : $delDate) : $fmtDate($r->deldate) }}</td>
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $delTime }}">{{ $isSales ? ($isMerged ? '' : $delTime) : $fmtTime($r->deldate) }}</td>

        {{-- Delivery Type (merged for sales; JO blank) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $isSales ? $deliveryType : '' }}">
            {{ $isSales ? ($isMerged ? '' : e($deliveryType)) : '' }}
        </td>

        {{-- JO# --}}
        <td class="bord">{{ e($r->jo_number ?? '') }}</td>

        {{-- Production Branch --}}
        <td class="bord">{{ e($r->pbname ?? '') }}</td>

        {{-- Status (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $delstat }}">
            {{ $isSales ? ($isMerged ? '' : e($delstat)) : e($r->delstat ?? '') }}
        </td>

        {{-- Agent (merged for sales; JO blank) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $agent }}">
            {{ $isSales ? ($isMerged ? '' : e($agent)) : '' }}
        </td>

        {{-- Order# (merged for sales; JO blank) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $orderNoText }}">
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
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $contactMerged }}">
            {{ $contactMerged }}
        </td>

        {{-- Contact Number (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $contactNo }}">
            {{ $isSales ? ($isMerged ? '' : e($contactNo)) : e($r->customer_contact_number ?? '') }}
        </td>

        {{-- DR# (merged for sales, left blank) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="">{{ $isSales ? '' : '' }}</td>

        {{-- Del Fee (merged for sales; JO per-row=0) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $delFee }}">
            {{ $isSales ? ($isMerged ? '' : $delFee) : '0' }}
        </td>

        {{-- Total (per row) --}}
        <td class="bord">{{ number_format((float)($r->price ?? 0) * (float)($r->qty ?? 0), 2) }}</td>

        {{-- Releasing (merged for sales; JO blank) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="">{{ $isSales ? '' : '' }}</td>

        {{-- Order Source (merged for sales; JO = "Forecaster") --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $isSales ? $orderSource : 'Forecaster' }}">
            {{ $isSales ? ($isMerged ? '' : e($orderSource)) : 'Forecaster' }}
        </td>

        {{-- Pickup Branch (merged for sales; JO per-row receiver) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $isSales ? $receiver : (string)($r->receiver ?? '') }}">
            {{ $isSales ? ($isMerged ? '' : e($receiver)) : e($r->receiver ?? '') }}
        </td>

        {{-- Item Category --}}
        <td class="bord">{{ e($r->catname ?? '') }}</td>

        {{-- Encoded By (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $username }}">
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
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $forecastDt }}">
            {{ $isSales ? ($isMerged ? '' : e($forecastDt)) : e($r->forecast_dt ?? '') }}
        </td>

        {{-- Delivery Branch (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $branch }}">
            {{ $isSales ? ($isMerged ? '' : e($branch)) : e(($r->mbranch ?? '') ?: ($r->del_branch ?? '')) }}
        </td>

        {{-- Encoded Date / Time (merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $createdDate }}">
            {{ $isSales ? ($isMerged ? '' : $createdDate) : $fmtDate($r->created, 'Y-m-d') }}
        </td>
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $createdTime }}">
            {{ $isSales ? ($isMerged ? '' : $createdTime) : $fmtTime($r->created) }}
        </td>

        {{-- Delivery Status (duplicate of status; merged for sales; JO per-row) --}}
        <td class="bord {{ $isSales && $isMerged ? 'merge-same' :  ($isSales  ? 'merge-first' : '') }} }}" data-value="{{ $delstat }}">
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

    let dt = $('#example').DataTable({
        dom: 'Bfrtip',
        pageLength: 28,
        lengthMenu: [ [28, 50, 100, -1], [28, 50, 100, "All"] ],
        aaSorting: [],
        bSort: false,
        searching: true,
        asStripeClasses: [],
        buttons: [
            {
                extend: 'print',
                action: function (e, dt, node, config) {
                    e.preventDefault();
                    printReport();
                },
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
                    const $doc = $(win.document.body);

                    // ---- Base styles (yours) ----
                    const baseCss = `
                        body { font-size: 16pt !important; }
                        table, table * { font-size: 14pt !important; line-height: 1.25 !important; }
                        table.dataTable thead th, table.dataTable thead td { font-size: 15pt !important; border: none !important }
                        table.dataTable tbody td { font-size: 14pt !important; border: none !important}
                        td, th { padding: 6px 8px !important; white-space: normal !important;
                        word-break: break-word !important; vertical-align: top !important; }
                        table { width: 100% !important; table-layout: fixed !important; }
                        @page { margin: 12mm; }
                    `;
                    $('<style type="text/css">' + baseCss + '</style>').appendTo($(win.document.head));

                    // Title & borders
                    // $doc.prepend('<div style="margin-bottom:8px; font-weight:bold;">Forecast Report</div>');
                    $doc.find('h1').css({ 'font-weight':'bold','font-size':'18pt' });
                    $doc.find('td, th').css('border','1px solid #0a0');

                    // ---- Make PRODUCT 1-line (no ellipsis), others can wrap ----
                    // helper to find column index by header text
                    function colIndexByName($root, headerText){
                        let idx = -1;
                        $root.find('table.dataTable thead th').each(function(i){
                        if ($(this).text().trim().toLowerCase() === headerText) { idx = i + 1; return false; }
                        });
                        return idx;
                    }

                    const idxQty      = colIndexByName($doc, 'qty');
                    const idxProduct  = colIndexByName($doc, 'product');
                    const idxPrice    = colIndexByName($doc, 'price');
                    const idxCustomer = colIndexByName($doc, 'customer');
                    const idxTime     = colIndexByName($doc, 'time needed');

                    // Let columns size naturally so Product can expand fully
                    $('<style type="text/css">table.dataTable{table-layout:auto !important;}</style>')
                        .appendTo($(win.document.head));

                    let colCss = '';

                    if (idxProduct > 0) {
                        // Product: single line, full text (no clipping, no ellipsis)
                        colCss += `
                        table.dataTable thead th:nth-child(${idxProduct}),
                        table.dataTable tbody td:nth-child(${idxProduct}) {
                            white-space: nowrap !important;
                            word-break: normal !important;
                            overflow: visible !important;
                            text-overflow: clip !important;
                            width: 40% !important;   /* adjust as needed */
                        }
                        `;
                    }
                    if (idxQty > 0) {
                        colCss += `
                        table.dataTable thead th:nth-child(${idxQty}),
                        table.dataTable tbody td:nth-child(${idxQty}) { width: 6% !important; }
                        `;
                    }
                    if (idxPrice > 0) {
                        colCss += `
                        table.dataTable thead th:nth-child(${idxPrice}),
                        table.dataTable tbody td:nth-child(${idxPrice}) { width: 10% !important; }
                        `;
                    }
                    if (idxCustomer > 0) {
                        colCss += `
                        table.dataTable thead th:nth-child(${idxCustomer}),
                        table.dataTable tbody td:nth-child(${idxCustomer}) {
                            width: 18% !important;
                            white-space: normal !important; /* allow wrapping here */
                        }
                        `;
                    }
                    if (idxTime > 0) {
                        colCss += `
                        table.dataTable thead th:nth-child(${idxTime}),
                        table.dataTable tbody td:nth-child(${idxTime}) { width: 12% !important; }
                        `;
                    }

                    if (colCss) {
                        $('<style type="text/css">' + colCss + '</style>').appendTo($(win.document.head));
                    }

                    // ---- Rebuild #totals-table into compact 2-col layout (yours) ----
                    // const $src = $doc.find('#totals-table');
                    // if ($src.length) {
                    //     const rows = [];
                    //     $src.find('tr').each(function () {
                    //     const $tds = $(this).find('td');
                    //     rows.push({
                    //         label: $tds.eq(0).text(),
                    //         value: $tds.eq(1).html()
                    //     });
                    //     });

                    //     const $twoCol = $(`
                    //     <table id="totals-table"
                    //             style="border-collapse:collapse; margin:6px 0 12px 0; font-size:14px; font-weight:bold;
                    //                     width:100%; table-layout:fixed;">
                    //         <colgroup>
                    //         <col style="width:25%">
                    //         <col style="width:25%">
                    //         <col style="width:25%">
                    //         <col style="width:25%">
                    //         </colgroup>
                    //     </table>
                    //     `);

                    //     for (let i = 0; i < rows.length; i += 2) {
                    //     const a = rows[i];
                    //     const b = rows[i + 1] || { label: '', value: '' };

                    //     $twoCol.append(`
                    //         <tr>
                    //         <td style="border:1px solid black; padding:4px 6px; white-space:normal; word-break:break-word;">${a.label}</td>
                    //         <td style="border:1px solid black; padding:4px 6px; text-align:center;">${a.value ?? ''}</td>
                    //         <td style="border:1px solid black; padding:4px 6px; white-space:normal; word-break:break-word;">${b.label}</td>
                    //         <td style="border:1px solid black; padding:4px 6px; text-align:center;">${b.value ?? ''}</td>
                    //         </tr>
                    //     `);
                    //     }
                    //     $src.replaceWith($twoCol);
                    // }
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
                    stripHtml: true,
                    format: {
                        body: function (data) {
                        const txt = (data || '').toString();
                        // 1) remove any leftover tags
                        // 2) decode &nbsp; to spaces
                        // 3) replace Unicode NBSP (\u00A0) with normal spaces
                        // 4) collapse runs of whitespace
                        return txt
                            .replace(/<[^>]*>/g, '')
                            .replace(/&nbsp;/gi, ' ')
                            .replace(/\u00A0/g, ' ')
                            .replace(/\s+/g, ' ')
                            .trim();
                        }
                    }
                }
            },
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: '{{ date('Ymd') }}',
                exportOptions: {
                    columns: ':visible',
                    stripHtml: true,
                    format: {
                        body: function (data) {
                        const txt = (data || '').toString();
                        // 1) remove any leftover tags
                        // 2) decode &nbsp; to spaces
                        // 3) replace Unicode NBSP (\u00A0) with normal spaces
                        // 4) collapse runs of whitespace
                        return txt
                            .replace(/<[^>]*>/g, '')
                            .replace(/&nbsp;/gi, ' ')
                            .replace(/\u00A0/g, ' ')
                            .replace(/\s+/g, ' ')
                            .trim();
                        }
                    }
                }
            },
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Forecast Report',
                exportOptions: {
                    columns: ':visible',
                    stripHtml: true,
                    format: {
                        body: function (data) {
                        const txt = (data || '').toString();
                        // 1) remove any leftover tags
                        // 2) decode &nbsp; to spaces
                        // 3) replace Unicode NBSP (\u00A0) with normal spaces
                        // 4) collapse runs of whitespace
                        return txt
                            .replace(/<[^>]*>/g, '')
                            .replace(/&nbsp;/gi, ' ')
                            .replace(/\u00A0/g, ' ')
                            .replace(/\s+/g, ' ')
                            .trim();
                        }
                    }
                }
            },
            {
                extend: 'colvis',
                text: 'Column visibility',
                buttons: [
                    {
                        extend: 'colvisGroup',
                        text: 'Show all columns',
                        show: ':hidden'
                    },
                    {
                        extend: 'colvisGroup',
                        text: 'Hide extra columns',
                        hide: [2,4,5,7,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34]
                    },
                    'columnsToggle'
                ]
            }
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


// Helper: find scrollable ancestors and temporarily remove their scroll/height
function unlockScrollableAncestors(rootEl) {
  const el = (typeof rootEl === 'string') ? document.querySelector(rootEl) : rootEl;
  if (!el) return () => {};

  const changed = [];
  let node = el.parentElement;

  const isScrollable = (n) => {
    const cs = getComputedStyle(n);
    const ovY = cs.overflowY, ov = cs.overflow;
    const hasScroll = (ovY === 'auto' || ovY === 'scroll' || ov === 'auto' || ov === 'scroll');
    const fixedH = (cs.maxHeight && cs.maxHeight !== 'none') || (cs.height && cs.height !== 'auto');
    return hasScroll || fixedH;
  };

  while (node && node !== document.body) {
    if (isScrollable(node)) {
      // store current inline styles so we can restore
      changed.push({
        node,
        style: {
          overflow: node.style.overflow,
          overflowY: node.style.overflowY,
          height: node.style.height,
          maxHeight: node.style.maxHeight,
          position: node.style.position
        }
      });
      node.style.overflow = 'visible';
      node.style.overflowY = 'visible';
      node.style.height = 'auto';
      node.style.maxHeight = 'none';
      // some frameworks use fixed/relative with transforms; static is safest for print
      node.style.position = 'static';
    }
    node = node.parentElement;
  }

  // Also clear body-level locking (e.g., Bootstrap modal adds overflow hidden)
  changed.push({
    node: document.body,
    style: { overflow: document.body.style.overflow }
  });
  document.body.style.overflow = 'visible';

  // Return a restore function
  return function restore() {
    for (const c of changed) {
      const { node, style } = c;
      node.style.overflow = style.overflow ?? '';
      node.style.overflowY = style.overflowY ?? '';
      node.style.height = style.height ?? '';
      node.style.maxHeight = style.maxHeight ?? '';
      node.style.position = style.position ?? '';
    }
  };
}

// Respect current ColVis (hide hidden columns only for print)
function markHiddenColsForPrint(dt) {
  const hiddenIdx = dt.columns(':not(:visible)').indexes().toArray();
  hiddenIdx.forEach(i => {
    $(dt.column(i).header()).addClass('dt-print-hidden');
    $(dt.column(i).nodes()).addClass('dt-print-hidden');
    const foot = dt.column(i).footer();
    if (foot) $(foot).addClass('dt-print-hidden');
  });
}
function clearHiddenColsMarks() {
  $('#example th.dt-print-hidden, #example td.dt-print-hidden, #example tfoot .dt-print-hidden')
    .removeClass('dt-print-hidden');
}

function printReport() {
  const dt = $('#example').DataTable();

  // remember current pagination
  const prevLen  = dt.page.len();
  const prevPage = dt.page();

  // --- helper: unlock scrollable ancestors (returns a restore fn) ---
  function unlockScrollableAncestors(rootSel) {
    const el = document.querySelector(rootSel);
    if (!el) return () => {};
    const changed = [];
    let node = el.parentElement;

    const isScrollable = (n) => {
      const cs = getComputedStyle(n);
      const hasScroll = ['auto','scroll'].includes(cs.overflow) || ['auto','scroll'].includes(cs.overflowY);
      const fixedH = (cs.maxHeight && cs.maxHeight !== 'none') || (cs.height && cs.height !== 'auto');
      return hasScroll || fixedH;
    };

    while (node && node !== document.body) {
      if (isScrollable(node)) {
        changed.push({
          node,
          style: {
            overflow: node.style.overflow,
            overflowY: node.style.overflowY,
            height: node.style.height,
            maxHeight: node.style.maxHeight,
            position: node.style.position
          }
        });
        node.style.overflow   = 'visible';
        node.style.overflowY  = 'visible';
        node.style.height     = 'auto';
        node.style.maxHeight  = 'none';
        node.style.position   = 'static';
      }
      node = node.parentElement;
    }
    changed.push({ node: document.body, style: { overflow: document.body.style.overflow } });
    document.body.style.overflow = 'visible';

    return function restore() {
      for (const {node, style} of changed) {
        node.style.overflow  = style.overflow  ?? '';
        node.style.overflowY = style.overflowY ?? '';
        node.style.height    = style.height    ?? '';
        node.style.maxHeight = style.maxHeight ?? '';
        node.style.position  = style.position  ?? '';
      }
    };
  }

  // --- helper: mark first row of each merged block ---
  function tagMergeFirst() {
    const rows = $('#example tbody tr').get();
    for (let r = 1; r < rows.length; r++) {
      const cells = rows[r].cells;
      for (let c = 0; c < cells.length; c++) {
        if (cells[c].classList.contains('merge-same')) {
          const prev = rows[r - 1];
          if (prev && prev.cells[c]) prev.cells[c].classList.add('merge-first');
        }
      }
    }
  }
  function clearMergeFirst() {
    $('#example td.merge-first').removeClass('merge-first');
  }

  const restoreScroll = unlockScrollableAncestors('#example');

  // show ALL rows, then print after draw
  dt.one('draw', () => {
    document.body.classList.add('print-plain');

    // make sure DT scroll body (if any) isn't clipping
    $('#example_wrapper .dataTables_scrollBody').css({height:'auto', maxHeight:'none', overflow:'visible'});

    // add merge-first tags so borders print like rowspans
    tagMergeFirst();

    // const totalsSwap = twoColTotals_forPrint();


    const cleanup = () => {

        // if (totalsSwap.changed) totalsSwap.restore();

        clearMergeFirst();
        document.body.classList.remove('print-plain');
        restoreScroll();
        // restore pagination + page
        dt.page.len(prevLen).draw(false).one('draw', () => dt.page(prevPage).draw(false));
        window.removeEventListener('afterprint', cleanup);
    };
    window.addEventListener('afterprint', cleanup, { once: true });

    // Safari / WebKit fallback
    const mql = window.matchMedia('print');
    const onChange = e => { if (!e.matches) { cleanup(); mql.removeEventListener?.('change', onChange); } };
    mql.addEventListener?.('change', onChange);

    window.print();
  });

  // trigger ALL rows
  dt.page.len(-1).draw(false);
}

// turns the single-column totals table into 2 columns (label,val | label,val)
function twoColTotals_forPrint() {
//   const $src = $('#totals-table');
//   if ($src.length === 0) return { restore(){}, changed:false };

//   // save original HTML so we can put it back after printing
//   const originalHTML = $src.prop('outerHTML');

//   // read rows
//   const rows = [];
//   $src.find('tr').each(function () {
//     const $td = $(this).find('td');
//     rows.push({ label: $td.eq(0).html(), value: $td.eq(1).html() });
//   });

//   // build a 2-column (4-cell) layout
//   const $twoCol = $(`
//     <table id="totals-table"
//            style="border-collapse:collapse; margin:6px 0 12px 0; font-size:14px; font-weight:bold; width:100%; table-layout:fixed;">
//       <colgroup>
//         <col style="width:40%">
//         <col style="width:10%">
//         <col style="width:40%">
//         <col style="width:10%">
//       </colgroup>
//     </table>
//   `);

//   for (let i = 0; i < rows.length; i += 2) {
//     const a = rows[i];
//     const b = rows[i + 1] || { label: '&nbsp;', value: '&nbsp;' };
//     $twoCol.append(`
//       <tr>
//         <td style="border:1px solid #cbd5e1; padding:4px 6px;">${a.label}</td>
//         <td style="border:1px solid #cbd5e1; padding:4px 6px; text-align:center;">${a.value}</td>
//         <td style="border:1px solid #cbd5e1; padding:4px 6px;">${b.label}</td>
//         <td style="border:1px solid #cbd5e1; padding:4px 6px; text-align:center;">${b.value}</td>
//       </tr>
//     `);
//   }

//   // swap in
//   $src.replaceWith($twoCol);

//   // return a restore fn
//   return {
//     changed: true,
//     restore() {
//       $('#totals-table').replaceWith(originalHTML);
//     }
//   };
}




</script>

@endsection