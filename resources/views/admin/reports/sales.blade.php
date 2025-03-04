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

@section('content')


        <div class="container-fluid">
            <div class="text-center mg-b-20"><img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
            <h4 class="mg-b-0 tx-spacing--1">Sales Report</h4></div>
          

            <div class="row-sm">
                <div class="col-md-12">
                    <form action="{{route('admin.report.sales')}}" method="get">
                      
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
                                <br>
                                <a href="#" onclick="$('#adv').toggle();" class="btn btn-success mg-t-7 mg-r-5 btn-sm">Advance Filter</a>
                                <button type="submit" class="btn btn-primary mg-t-7 mg-r-5 btn-sm">Generate</button>
                                <a href="{{route('admin.report.sales')}}" class="btn btn-info mg-t-7 mg-r-5 btn-sm">Reset</a>
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
                                        @forelse(\App\Product::select('name')->distinct('name')->orderBy('name')->get() as $cus)
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
                                        @forelse(\App\ProductCategory::whereStatus('Published')->orderBy('name')->get() as $cus)
                                            <option value="{{$cus->id}}">{{$cus->name}}</option>
                                        @empty
                                        @endforelse                                       
                                        @if(isset($_GET['category']) && strlen($_GET['category'])>0)                                        
                                            @php 
                                                $bb = \App\ProductCategory::whereStatus('Published')->whereId($_GET['category'])->first();
                                               
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
                                        <option value="">- Select Item Type -</option>
                                        <option value="Miscellaneous">Miscellaneous</option>
                                        <option value="WRA">WRA</option>                                                                           
                                        @isset($_GET['item_type'])
                                            <option value="{{$_GET['item_type']}}" selected="selected">{{ $_GET['item_type'] }}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Start (Date Needed)</label>
                                    <input type="date" class="form-control input-sm" name="startdateneeded"  autocomplete="off" value="@isset($_GET['startdateneeded']){{ $_GET['startdateneeded'] }}@endisset">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">End (Date Needed)</label>
                                    <input type="date" class="form-control input-sm" name="enddateneeded"  autocomplete="off" value="@isset($_GET['enddateneeded']){{ $_GET['enddateneeded'] }}@endisset">
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
                        <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
                            <thead>
                            <tr>
                                <th>Order#</th>
                                <th>Order Date</th>
                                <th>Order Time</th>
                                <th>Customer</th>
                                <th>Delivery Type</th>
                                <th>Delivery Address</th>
                                <th>Delivery Status</th>
                                <th>Contact Number</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Order Source</th>
                                <th>JobOrder</th>
                                <th>Production Branch</th>
                                <th>Date Needed</th>
                                <th>Time Needed</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total Amount</th>
                                <th>Payment</th>
                                <th>Item Type</th>
                                <th>Confirmed</th>
                                <th>Delivery Branch</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($rs as $r)   
                                @php 
                                    $pays = '';
                                    foreach(\App\EcommerceModel\SalesPayment::where('sales_header_id',$r->sales_header_id)->whereStatus('PAID')->get() as $pay){
                                        $pays.=' '.number_format($pay->amount,2).' ('.$pay->payment_type.'),';
                                    }
                                    $pays = rtrim($pays,",");
                                @endphp                             
                                <tr style="text-align: left">
                                    <td><a target="_blank" href="{{ route('sales-transaction.view',$r->hid) }}">{{$r->order_number}}</a></td>
                                    <td>{{date('Y-m-d',strtotime($r->hcreated))}}</td>
                                    <td>{{date('h:i A',strtotime($r->hcreated))}}</td>
                                    <td>{{$r->customer_name}}</td>
                                    <td>{{$r->delivery_type}}</td>
                                    <th>
                                        @php
                                            $text_array = explode(" ", $r->customer_delivery_adress);
                                            $chunks = array_chunk($text_array, 3);
                                            foreach ($chunks as $chunk) {
                                                echo implode(" ", $chunk)."<br>";                                          
                                            }

                                            $itemType = '';
                                            if($r->is_misc == 1){
                                                $itemType = 'Miscellaneous';
                                            }
                                            if(in_array($r->prodid,$wra_array)){
                                                $itemType = 'WRA';
                                            }
                                        @endphp
                                    </th>  
                                    <th>{{$r->delivery_status}}</th>
                                    <th>{{$r->customer_contact_number}}</th>
                                    <td>{{$r->product_name}}</td>
                                    <th>{{$r->catname}}</th>
                                    <th>{{$r->order_source}}</th>
                                    <th>{{$r->jnum}}</th>
                                    <th>{{$r->prod_branch}}</th>
                                    <td>{{date('Y-m-d',strtotime($r->delivery_date))}}</td>
                                    <td>{{date('h:i A',strtotime($r->delivery_date))}}</td>
                                    <td>{{number_format($r->price,2)}}</td>
                                    <td>{{number_format($r->qty,2)}}</td>
                                    <td>{{number_format(($r->price * $r->qty),2)}}</td>
                                    <td>
                                        {!!$pays!!}
                                    </td>
                                    <td>{{$itemType}}</td>
                                    <td>@if($r->isConfirm==1) Yes @else No @endif</td>
                                    <td>{{ $r->del_branch }}</td>  
                                </tr>
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
                targets: [4,5,6,7,10,11,12,13,20],
                visible: false
            },{ type: 'time-uni', targets: [2,14] } ]
        } );
    } );
</script>
@endsection



