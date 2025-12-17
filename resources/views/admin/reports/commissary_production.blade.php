@extends('admin.layouts.report')

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
        .row-selected {
            background-color: #92b7da !important;
        }
        @page {
          size: auto;
        }
    </style>
@endsection


@php
    $branch_display='';
    if(isset($_GET['pb']) && strlen($_GET['pb'])>=1){
        $b = \App\EcommerceModel\ProductionBranch::whereId($_GET['pb'])->first();
        $branch_display=$b->name;
    }
    
    $date_display='';
    if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
        $date_display=date('M d, Y',strtotime($_GET['startdate']))." to ".date('M d, Y',strtotime($_GET['enddate']));
    }
@endphp
@section('pagetitle')
                Commissary Production
                {{ $branch_display }}
                {{$date_display}}
@endsection

@section('content')


        <div class="container-fluid">
            <div class="text-center mg-b-20"><img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
            <h4 class="mg-b-0 tx-spacing--1">Commissary Production</h4></div>
          

            <div class="row-sm">
                <div class="col-md-12">
                    <form action="{{route('admin.report.commissary_production')}}" method="get">
                      
                        @csrf
                        <div class="row row-sm">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">Production Branch</label>
                                    <select name="pb" id="pb" class="form-control">
                                        <option value="">- Select Production -</option>
                                        @forelse(\App\EcommerceModel\ProductionBranch::orderBy('name')->get() as $cus)
                                            <option value="{{$cus->id}}" {{ (isset($_GET['pb']) && $_GET['pb'] == $cus->id) ? 'selected' : '' }}>{{$cus->name}}</option>
                                        @empty
                                        @endforelse  
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">Start Date</label>
                                    <input type="date" class="form-control input-sm" name="startdate"  autocomplete="off" value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">End Date</label>
                                    <input type="date" class="form-control input-sm" name="enddate"  autocomplete="off" value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset">
                                </div>
                            </div>
                            <div class="col-md-3 filter-action mg-r-5">
                                <a href="#" onclick="$('#adv').toggle();" class="btn btn-sm btn-success mg-t-7 mg-r-5">Advance Filter</a>
                                <button type="submit" class="btn btn-sm btn-primary mg-t-7 mg-r-5">Generate</button>
                                <a href="{{route('admin.report.commissary_production')}}" class="btn btn-sm btn-info mg-t-7 mg-r-5">Reset</a>
                            </div>
                        </div>
                        <div class="row" id="adv" style="display:none;">
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
                                        <option value="">- Select Source -</option>
                                            <option value="Miscellaneous">Miscellaneous</option>
                                            <option value="Order">Lechon from Order</option>
                                            <option value="Pantaga">Pantaga</option>
                                            <option value="Belly Pantaga">Belly Pantaga</option>
                                            <option value="Display">Display</option>
                                            <option value="Alpha Size">Alpha Size</option>
                                        @isset($_GET['item_type'])
                                            <option value="{{$_GET['item_type']}}" selected="selected">{{ ($_GET['item_type'] == 'Order') ? 'Lechon from Order': $_GET['item_type'] }}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
            </div>

            @if(isset($rs))
                <div class="row row-sm">
                    <!-- Start Filters -->
                    <div class="col-md-12">
                        <!-- <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;"> -->
                        <table  id="example" border="1" class="display nowrap" style="width:100%;font: bold 13px/150% Arial, sans-serif, Helvetica;">
                            <thead>
                            <tr> 
                                <th>Production Branch</th>
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
                            </tr>
                            </thead>
                            <tbody>
                         
                            @forelse($rs as $r)                           
             

                                    @php
                                        $address = '';
                                        $cntsales = collect($rs)->where('order_number',$r->order_number)->count();
                                        $isAllowed = 0;
                                        $cntsales = 1;
                                        
                                        if($r->isConfirm == 1){
                                                $isAllowed = 1;
                                        }

                                        $itemType = '';
                                      
                                        
                                    @endphp
                               
                                    <tr style="text-align: left">
                                        <td class="bord">{{$r->prod_branch}}</td>
                                        <td class="bord">{{number_format($r->qty,2)}}</td>
                                        <td class="bord">{{$r->product_name}} @if($r->paella_price > 0) Boneless @endif</td>

                                
                                        <td class="bord" rowspan="{{$cntsales}}" valign="top">@if(strlen($address)>15){!!$address!!}@endif</td>   
                                      

                                        <td class="bord">{{number_format($r->price,2)}}</td>
                               
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
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">
                                                @php
                                                    $text_array = explode(" ", $r->customer_name);
                                                    $chunks = array_chunk($text_array, 3);
                                                    foreach ($chunks as $chunk) {
                                                        echo implode(" ", $chunk)."<br>";                                          
                                                    }
                                                @endphp
                                           
                                            </td>   
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
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">@if(date('Y-m-d',strtotime($r->delivery_date)) <> '1970-01-01'){{date('m-d-Y',strtotime($r->delivery_date))}} @endif</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">@if(date('Y-m-d',strtotime($r->delivery_date)) <> '1970-01-01'){{date('h:i A',strtotime($r->delivery_date))}} @endif</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->delivery_type}}</td>   
                                   
                                        
                                          
                                        
                                        <td class="bord">{{$r->jnum}}</td>
                                        

                                       
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->delstat}}</td>
                                            
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->agent}}</td>                                    
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top"><a target="_blank" href="{{ route('sales-transaction.view',$r->hid) }}">{{$r->order_number}}</a></td> 
                                            
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->contact_person}}</td>
                                                                                                                          
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{$r->customer_contact_number}}</td>
                                            
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">&nbsp;</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{number_format($r->delivery_fee_amount,2)}}</td>
                                 

                                        
                                        <td class="bord">{{number_format(($r->price * $r->qty),2)}}</td>

                                        
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">&nbsp;</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">
                                               {{$r->order_source}}
                                            </td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">
                                               
                                            </td>
                                      

                                        <td class="bord">
                                           {{$r->catname}}
                                        </td>

                                      
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">
                                       
                                            </td>
                                       
                                        <td class="bord">
                                           {{$r->order_type}}
                                        </td>
                                        <td class="bord">
                                           {{$itemType}}
                                        </td>
                                        
                                        
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{ $r->forecast_date }}</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">{{ $r->delivery_branch }}</td>   
                                        
                                       
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">@if(date('m-d-Y',strtotime($r->hcreated)) <> '1970-01-01'){{date('Y-m-d',strtotime($r->hcreated))}} @endif</td>
                                            <td class="bord" rowspan="{{$cntsales}}" valign="top">@if(date('m-d-Y',strtotime($r->hcreated)) <> '1970-01-01'){{date('h:i A',strtotime($r->hcreated))}} @endif</td>
                                       
                                        
                                    </tr>
                                   

                                

                            @empty
                            @endforelse
                        

                            </tbody>

                        </table>
                    </div>
                 
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
            pageLength: 20,
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
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
                    filename: 'CommissaryProduction',
                    title: 'Commissary Production ({{ $branch_display }} | {{$date_display}})',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    filename: 'CommissaryProduction',
                    title: 'Commissary Production ({{ $branch_display }} | {{$date_display}})',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    filename: 'CommissaryProduction',
                    title: 'Commissary Production ({{ $branch_display }} | {{$date_display}})',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ],
            columnDefs: [ {
                targets: [3,4,5,7,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33],
                visible: false
            },{ type: 'time-uni', targets: [2] } ]
        } );
    } );
</script>
@endsection



