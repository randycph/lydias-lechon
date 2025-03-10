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
    if(isset($_GET['receiver']) && strlen($_GET['receiver']) > 0){
        $br = \App\EcommerceModel\Branch::whereId($_GET['receiver'])->first();
        $dbranch = "<br>".$br['name'];
    }

    
    $total_lechon_order = 0;
    $total_lechon_overall = 0;
    $total_misc = 0;
    $ex_array = ['Pantaga','Display','Alpha Size','Belly Pantaga'];
    foreach(collect($results) as $j){
        if($j->is_misc == 0 && $j->production_item == 1){

            $paid = (float) \App\EcommerceModel\SalesPayment::where('sales_header_id',$j->hid)->whereStatus('PAID')->sum('amount');
                                        
            if((($j->gros - $paid) <= 0) || $j->isConfirm == 1){

                if(!in_array($j->jo_category,$ex_array) ){
                    $total_lechon_order += $j->qty;
                }
                         
                    $total_lechon_overall += $j->qty;
            }
            
        }
        if($j->is_misc == 1){
            $paid = (float) \App\EcommerceModel\SalesPayment::where('sales_header_id',$j->hid)->whereStatus('PAID')->sum('amount');
                                        
            if((($j->gros - $paid) <= 0) || $j->isConfirm == 1){

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

    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        @page {
          size: auto;
        }
        .bords{
            border: 2px solid red;
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
                                    <label class="tx-13">Receiver Branch</label>
                                    <select name="receiver" id="receiver" class="form-control">
                                        <option value="">- Select Receiver -</option>
                                        @forelse(\App\EcommerceModel\Branch::orderBy('name')->get() as $cus)
                                            <option @isset($_GET['receiver']) @if(app('request')->input('receiver') == $cus->id) selected @endif @endif value="{{$cus->id}}">{{$cus->name}}</option>
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
                                    <label class="tx-13">Item Type</label>
                                    <select name="item_type" id="item_type" class="form-control">
                                        <option value="">- Select Item Type -</option>
                                        <option value="Miscellaneous">Miscellaneous</option>
                                        <option value="WRA">WRA</option>                                                                           
                                        @isset($_GET['item_type'])
                                            <option value="{{$_GET['item_type']}}" selected="selected">{{ $_GET['item_type'] }}</option>
                                        @endisset
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
                                <th>Instruction</th>                                
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
                            </tr>
                            </thead>
                            <tbody>
                            @php 
                                //$results = $results->sortBy('delivery_date');
                                $results = $results->sort(
                                                function ($a, $b) {
                                                    return strcmp($a->timeneeded, $b->timeneeded)
                                                        ?: strcmp($a->dateneeded, $b->dateneeded)
                                                        ?: strcmp($a->customer_name, $b->customer_name)
                                                        ?: strcmp($a->order_number, $b->order_number);
                                                }
                                            );
                                $results = $results->values()->all();
                                
                            @endphp
                            @forelse($results as $r)                           
                                @if($r->trantype == 'sales')

                                    @php
                                        $address = trim($r->address_street)."<br>".trim($r->address_municipality).",<br>".trim($r->address_city).", ".trim($r->address_region);
                                        $cntsales = collect($rs)->where('order_number',$r->order_number)->count();
                                        $isAllowed = 0;
                                        $paid = (float) \App\EcommerceModel\SalesPayment::where('sales_header_id',$r->hid)->whereStatus('PAID')->sum('amount');
                                        
                                        if((($r->gros - $paid) <= 0) || $r->isConfirm == 1){
                                                $isAllowed = 1;
                                        }

                                        $itemType = '';
                                        if($r->is_misc == 1){
                                            $itemType = 'Miscellaneous';
                                        }
                                        if(in_array($r->prodid,$wra_array)){
                                            $itemType = 'WRA';
                                        }
                                        
                                    @endphp
                                    @if($isAllowed == 1)
                                    <tr style="text-align: left">
                                        
                                        <td class="bord">{{number_format($r->qty,2)}}</td>
                                        <td class="bord">{{$r->product_name}} @if($r->paella_price > 0) Boneless @endif</td>

                                        @if($old_value <> $r->order_number)
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">@if(strlen($address)>15){!!$address!!}@endif</td>   
                                        @else        
                                            <td class="wala" style="display:none;"></td>   
                                        @endif  

                                        <td class="bord">{{number_format($r->price,2)}}</td>
                                        @if($old_value <> $r->order_number)
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">
                                                @php
                                                    $payment = \App\EcommerceModel\SalesPayment::where('sales_header_id',$r->hid)->get();
                                                @endphp
                                                <table>
                                                    @forelse($payment as $pp)
                                                    <tr>
                                                        <td class="bord">{{$pp->payment_type}}</td>
                                                        <td class="bord">{{number_format($pp->amount,2)}}</td>
                                                    </tr>
                                                    @empty
                                                    @endforelse
                                                </table>
                                            </td> 
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">
                                                @php
                                                    $text_array = explode(" ", $r->customer_delivery_adress);
                                                    $chunks = array_chunk($text_array, 3);
                                                    foreach ($chunks as $chunk) {
                                                        echo implode(" ", $chunk)."<br>";                                          
                                                    }
                                                @endphp
                                            </td>                                      
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->customer_name}}</td>   
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{date('m-d-Y',strtotime($r->delivery_date))}}</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">@if(date('h:i A',strtotime($r->delivery_date)) == '12:00 PM') 12:00 NOON @else {{date('h:i A',strtotime($r->delivery_date))}} @endif</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">
                                                @php
                                                    $text_array = explode(" ", $r->instruction);
                                                    $chunks = array_chunk($text_array, 3);
                                                    foreach ($chunks as $chunk) {
                                                        echo implode(" ", $chunk)."<br>";                                          
                                                    }
                                                @endphp
                                            </td> 
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">@if(date('m-d-Y',strtotime($r->deldate)) <> '1970-01-01'){{date('m-d-Y',strtotime($r->deldate))}} @endif</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">@if(date('m-d-Y',strtotime($r->deldate)) <> '1970-01-01'){{date('h:i A',strtotime($r->deldate))}} @endif</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->delivery_type}}</td>   
                                        @else 
                                            <td class="wala" style="display:none;"></td>  
                                            <td class="wala" style="display:none;"></td>         
                                            <td class="wala" style="display:none;"></td>   
                                            <td class="wala" style="display:none;"></td>  
                                            <td class="wala" style="display:none;"></td>         
                                            <td class="wala" style="display:none;"></td>   
                                            <td class="wala" style="display:none;"></td>  
                                            <td class="wala" style="display:none;"></td>         
                                            <td class="wala" style="display:none;"></td>   
                                        @endif  
                                        
                                          
                                        
                                        <td class="bord">{{$r->jo_number}}</td>
                                        <td class="bord">{{$r->pbname}}</td>

                                        @if($old_value <> $r->order_number)
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->delstat}}</td>
                                            
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->agent}}</td>                                    
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top"><a target="_blank" href="{{ route('sales-transaction.view',$r->hid) }}">{{$r->order_number}}</a></td> 
                                            
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->customer_name}}</td>
                                                                                                                          
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->customer_contact_number}}</td>
                                            
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">&nbsp;</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{number_format($r->delivery_fee_amount,2)}}</td>
                                        @else
                                            <td class="wala" style="display:none;"></td>  
                                            <td class="wala" style="display:none;"></td>         
                                            <td class="wala" style="display:none;"></td>   
                                            <td class="wala" style="display:none;"></td>  
                                            <td class="wala" style="display:none;"></td>         
                                            <td class="wala" style="display:none;"></td> 
                                            <td class="wala" style="display:none;"></td> 
                                        @endif

                                        
                                        <td class="bord">{{number_format(($r->price * $r->qty),2)}}</td>

                                        @if($old_value <> $r->order_number)
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">&nbsp;</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">
                                               {{$r->order_source}}
                                            </td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">
                                               {{$r->receiver}}
                                            </td>
                                        @else
                                            <td class="wala" style="display:none;"></td>  
                                            <td class="wala" style="display:none;"></td>         
                                            <td class="wala" style="display:none;"></td> 
                                        @endif

                                        <td class="bord">
                                           {{$r->catname}}
                                        </td>

                                        @if($old_value <> $r->order_number)
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">
                                               {{$r->username}}
                                            </td>
                                        @else
                                            <td class="wala" style="display:none;"></td>   
                                        @endif
                                        <td class="bord">
                                           {{$r->hordertype}}
                                        </td>
                                        <td class="bord">
                                           {{$itemType}}
                                        </td>
                                        
                                        @if($old_value <> $r->order_number)
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{ $r->forecast_dt }}</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{ $r->del_branch }}</td>   
                                        @else        
                                            <td class="wala" style="display:none;"></td>   
                                            <td class="wala" style="display:none;"></td>   
                                        @endif
                                        
                                        
                                    </tr>
                                    @endif
                                    @php if($isAllowed == 1) { $old_value=$r->order_number; } @endphp

                                @elseif($r->trantype == 'jo')

                                    @php
                                        $address = trim($r->address_street)."<br>".trim($r->address_municipality).",<br>".trim($r->address_city).", ".trim($r->address_region);
                                        $cnt = collect($jo)->where('jo_number',$r->jo_number)->count();


                                        $itemType = '';
                                        if($r->is_misc == 1){
                                            $itemType = 'Miscellaneous';
                                        }
                                        if(in_array($r->prodid,$wra_array)){
                                            $itemType = 'WRA';
                                        }
                                    @endphp
                                    <tr style="text-align: left">                                    
                                        <td class="bord">{{number_format($r->qty,2)}}</td>
                                        <td class="bord">{{$r->jo_category}}</td>
                                        <td class="bord">@if(strlen($address)>15){!!$address!!} @endif</td>
                                        <td class="bord">{{number_format($r->price,2)}}</td>
                                        
                                        <td class="bord">
                                           &nbsp;
                                        </td>
                                        <td class="bord">{{$r->customer_delivery_adress}}</td> 
                                        @if($old_value <> $r->jo_number)
                                            <td class="bord" rowspan="{{$cnt}}">{{$r->customer_name}}</td>  
                                        @endif 
                                                                          
                                        <td class="bord">{{date('m-d-Y',strtotime($r->delivery_date))}}</td>
                                        <td class="bord">
                                            @if(date('h:i A',strtotime($r->delivery_date)) == '12:00 PM') 12:00 NOON @else {{date('h:i A',strtotime($r->delivery_date))}} @endif
                                        </td>
                                        <th>{{$r->instruction}}</th>
                                        <td class="bord">@if(date('m-d-Y',strtotime($r->deldate)) <> '1970-01-01'){{date('m-d-Y',strtotime($r->deldate))}} @endif</td>
                                        <td class="bord">@if(date('m-d-Y',strtotime($r->deldate)) <> '1970-01-01'){{date('h:i A',strtotime($r->deldate))}} @endif</td>
                                        <td class="bord">&nbsp;</td>   
                                         
                                        <td class="bord">{{$r->jo_number}}</td> 
                                        <td class="bord">{{$r->pbname}}</td>
                                        <td class="bord">&nbsp;</td>
                                        <td class="bord">&nbsp;</td> 
                                        <td class="bord">&nbsp;</td>                                                                                               
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <td class="bord">0</td>
                                        <td class="bord">0</td>
                                        
                                        <td class="bord">
                                           &nbsp;
                                        </td>
                                        <td class="bord">
                                           Forecaster
                                        </td>
                                        <td class="bord">
                                           {{$r->receiver}}
                                        </td>
                                        <td class="bord">
                                           {{$r->catname}}
                                        </td>
                                        <td class="bord">
                                           {{$r->username}}
                                        </td>
                                        <td class="bord">
                                           {{$r->hordertype}}
                                        </td>
                                        <td class="bord">
                                           {{$itemType}}
                                        </td>
                                        
                                            <td class="bord">{{ $r->forecast_dt }}</td>
                                            <td class="bord">{{ $r->del_branch }}</td>   
                                        
                                        
                                        
                                    </tr>
                                    @php $old_value=$r->jo_number; @endphp

                                @endif
                            @empty
                            @endforelse
                            @forelse($jo as $r)
                                
                            @empty
                            @endforelse

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
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/prismjs/prism.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>

@endsection

@section('customjs')
<script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.colVis.min.js') }}"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/time.js"></script>
<script>
    $(function() {
        'use strict'

        $('#datepicker1').datepicker();

        $('#datepicker2').datepicker();
    });

    $(document).ready(function() {
        $('#example').DataTable( {            
            dom: 'Bfrtip',
            pageLength: 28,
            // aaSorting: [[ 8, "asc" ]],
            // bPaginate: false,
            aaSorting: [],
            bSort: false,
            asStripeClasses: [],
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible',
                        stripHtml: false
                    },
                    customize: function ( win ) {
                        $(win.document.body)
                            .css( 'font-size', '12pt' )
                            .css( 'font-weight', 'bold' )
                            .prepend('Forecast Report' );

                        $(win.document.body).find( 'h1' )
                            .css( 'font-weight', 'bold' )
                            .css( 'font-size', '14pt' );
     
                        $(win.document.body).find( 'table' )
                            .css( 'font-weight', 'bold' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );

                        var tds =  $(win.document.body).find( 'td' );
                        for (var i = 0; i<tds.length; i++) {

                          // If it currently has the ColumnHeader class...
                          //if (tds[i].className == 'bord') {
                            // Set a new width
                            tds[i].style.border = '1px solid green';
                          //}
                        }

                        // $(win.document.getElementsByClassName('bord'))
                        //     .addClass( 'bords' );

                        // $(win.document.getElementsByClassName('wala'))
                        //     .css('border','1px solid black');
                        // $(win.document.body).find( 'td' )
                        //     .css('border','1px solid black');
                    }                    
                },
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    title: '{{date('Ymd')}}',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    title: '{{date('Ymd')}}',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: '{{date('Ymd')}}',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ],
            columnDefs: [ 
                {
                    targets: [3,4,7,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29],
                    visible: false
                },
                { type: 'time-uni', targets: [8,11] }
             ]
        } );
    } );
</script>
@endsection



