@extends('admin.layouts.report')

@section('pagetitle')

@endsection

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

@section('content')


        <div class="container-fluid">
            <div class="text-center mg-b-20"><img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
            <h4 class="mg-b-0 tx-spacing--1">Sales Transaction Report</h4></div>
          

            <div class="row-sm">
                <div class="col-md-12">
                    <form action="{{route('admin.report.sales_transaction')}}" method="get">
                      
                        @csrf
                        <div class="row row-sm">
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
                                <a href="{{route('admin.report.sales')}}" class="btn btn-sm btn-info mg-t-7 mg-r-5">Reset</a>
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
                                    <label class="tx-13">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">- Select Category -</option>
                                        @forelse(\App\Models\ProductCategory::whereStatus('Published')->orderBy('name')->get() as $cus)
                                            <option value="{{$cus->id}}">{{$cus->name}}</option>
                                        @empty
                                        @endforelse                                       
                                        @if(isset($_GET['category']) && strlen($_GET['category'])>0)                                        
                                            @php 
                                                $bb = \App\Models\ProductCategory::whereStatus('Published')->whereId($_GET['category'])->first();
                                               
                                            @endphp
                                            <option value="{{$_GET['category']}}" selected="selected">{{ $bb->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Agent</label>
                                    <select name="agent" id="agent" class="form-control">
                                        <option value="">- Select Agent -</option>
                                        @forelse(\App\EcommerceModel\SalesHeader::select('agent')->distinct('agent')->orderBy('agent')->get() as $cus)
                                            <option value="{{$cus->agent}}">{{$cus->agent}}</option>
                                        @empty
                                        @endforelse
                                        @isset($_GET['agent'])
                                            <option value="{{$_GET['agent']}}" selected="selected">{{ $_GET['agent'] }}</option>
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
                        <table style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
                            <thead>
                            <tr style="color:blue;font-size:16px;">
                                <th width="10%">Order#</th>
                                <th width="10%">Order Date</th>
                                <th width="10%">Order Time</th>
                                <th width="10%">Customer</th>                               
                                
                                <th width="10%">Delivery Type</th>
                                <th width="40%">Delivery Address</th>
                                <!-- 
                                <th>Delivery Status</th>
                                <th>Contact Number</th>
                                <th>Order Source</th>
                               
                                <th width="10%">Total Amount</th>    
                                 -->                            
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="10"><hr></td>
                                </tr>
                            @forelse($rs as $r)                                                          
                                <tr style="text-align: left;background-color:#F0E5E3;height:40px;vertical-align:middle; ">
                                    <td style="padding-left:10px;" valign="middle"><a target="_blank" href="{{ route('sales-transaction.view',$r->hid) }}">{{$r->order_number}}</a></td>
                                    <td valign="middle">{{date('Y-m-d',strtotime($r->hcreated))}}</td>
                                    <td valign="middle">{{date('h:i A',strtotime($r->hcreated))}}</td>
                                    <td valign="middle">{{$r->customer_name}}</td>                                   
                                    <td valign="middle">{{$r->delivery_type}}</td>
                                    <td valign="middle">
                                        {!!$r->customer_delivery_adress!!}
                                        @php
                                        /*
                                            $text_array = explode(" ", $r->customer_delivery_adress);
                                            $chunks = array_chunk($text_array, 3);
                                            foreach ($chunks as $chunk) {
                                                echo implode(" ", $chunk)."<br>";                                          
                                            }
                                        */
                                        @endphp
                                    </td>  
                                    <!-- 
                                    <td>{{$r->delivery_status}}</td>
                                    <td>{{$r->customer_contact_number}}</td>     
                                    <td>{{$r->order_source}}</td>  
                                                                                         
                                    <td valign="top">{{number_format(($r->gross_amount),2)}}</td>   
                                      -->                                
                                </tr>
                                <tr>
                                    <td colspan="10">
                                        <table width="60%" align="center" style="margin: 20px 50px 20px 50px; ">                                            
                                            <tr style="color:blue;font-weight:bold;">
                                                <td>Product</td>
                                                <td>Category</td>
                                                <td align="right">Price</td>
                                                <td align="right">Qty</td>
                                                <td align="right">Total Amount</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><hr></td>
                                            </tr>

                                            
                                            @php
                                                $st = 0;
                                                $ds = \DB::select("SELECT pb.name as prod_branch,jo.jo_number as jnum,h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,d.id as did
                                                    FROM `ecommerce_sales_details` d
                                                    left join ecommerce_sales_headers h on h.id=d.sales_header_id
                                                    left join products p on p.id=d.product_id
                                                    left join product_categories c on c.id=p.category_id
                                                    left join job_orders jo on jo.sales_detail_id = d.id
                                                    left join production_orders po on po.joborder_id = jo.id
                                                    left join production_branches pb on pb.id = po.branch_id
                                                 where h.id>0 and h.deleted_at is null and jo.deleted_at is null and h.id='".$r->hid."'");
                                            @endphp
                                            @forelse($ds as $d)
                                                @php
                                                    $st += $d->price * $d->qty;
                                                @endphp
                                            <tr>
                                                <td>{{$d->product_name}}</td>
                                                <td>{{$d->catname}}</td>
                                                <td align="right">{{number_format($d->price,2)}}</td>
                                                <td align="right">{{number_format($d->qty,2)}}</td>
                                                <td align="right">{{number_format(($d->price * $d->qty),2)}}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                            @endforelse
                                            <tr>
                                                <td colspan="5"><hr></td>
                                            </tr>
                                            <tr>
                                                <td>Total</td>
                                                <td colspan="4" align="right">{{number_format($st,2)}}</td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                                
                            @empty
                            @endforelse


                            </tbody>

                        </table>
                    </div>
                    <!-- End Filters 
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
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ],
            columnDefs: [ {
                targets: [4,5,6,7],
                visible: false
            },{ type: 'time-uni', targets: [2] } ]
        } );
    } );
</script>
@endsection



