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
        @page {
          size: auto;
        }
    </style>
@endsection

@section('content')

    @php
        $datetxt='';
        $dstart='';
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
    @endphp
    
    <div class="container-fluid">
        <div class="text-center mg-b-10">
            <img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
            <h4 class="mg-b-0 tx-spacing--1">Job Order Report</h4>
            {!! $datetxt !!}
        </div><br>

        <div class="row-sm">
                <div class="col-md-12">
                    <form action="{{route('admin.report.joborder')}}" method="get">                      
                        @csrf
                        <div class="row row-sm">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">Start Date</label>
                                    <input type="date" class="form-control" name="startdate"  value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">End Date</label>
                                    <input type="date" class="form-control" name="enddate"  value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset">
                                </div>
                            </div>
                            <div class="col-md-3 filter-action mg-r-5">
                                <br>
                                <a href="#" onclick="$('#adv').toggle();" class="btn btn-success mg-t-7 mg-r-5 btn-sm">Advance Filter</a>
                                <button type="submit" class="btn btn-primary mg-t-7 mg-r-5 btn-sm">Generate</button>
                                <a href="{{route('admin.report.joborder')}}" class="btn btn-info mg-t-7 mg-r-5 btn-sm">Reset</a>
                            </div>
                        </div>
                        <div class="row" id="adv" style="display:none;">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="tx-13">Branch</label>
                                    <select name="branch" id="branch" class="form-control">
                                        <option value="">- Select Branch -</option>
                                        @forelse(\App\EcommerceModel\Branch::all() as $br)
                                            <option value="{{$br->name}}">{{$br->name}}</option>
                                        @empty
                                        @endforelse
                                        @isset($_GET['branch'])
                                            <option value="{{$_GET['branch']}}" selected="selected">{{ $_GET['branch'] }}</option>
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
        <br><br>
            <div class="row row-sm">
                <!-- Start Filters -->
                <div class="col-md-12">
                    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Payment</th>
                                <th>Delivery Address</th> 
                                <th>Date Needed</th>
                                <th>Time Needed</th>
                                <th>Instruction</th>
                                <th>JobOrder</th>                                
                                <th>Product</th>
                                <th>Delivery Type</th> 
                                <th>Type</th>
                                <th>Production Branch</th>
                                <th>Order#</th>
                                <th>Order Date</th>
                                <th>Order Time</th>
                                <th>Delivery Status</th>
                                <th>Contact Number</th>                                
                                <th>Category</th>
                                <th>Order Source</th>                                 
                                <th>Total Amount</th>     
                                <th>Order Type</th>  
                                <th>Item Type</th>                     
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rs as $r)
                            <tr style="text-align: left">
                                <td>{{$r->customer_name}}</td>
                                <td>{{number_format($r->qty,2)}}</td>
                                <td>{{number_format($r->price,2)}}</td>
                                <td>{{$r->payment_status}}</td>
                                <th>
                                    @php
                                        $text_array = explode(" ", $r->customer_delivery_adress);
                                        $chunks = array_chunk($text_array, 3);
                                        foreach ($chunks as $chunk) {
                                            echo implode(" ", $chunk)."<br>";                                          
                                        }
                                    @endphp
                                </th>   
                                <td>{{date('Y-m-d',strtotime($r->delivery_date))}}</td>
                                <td>{{date('h:i A',strtotime($r->delivery_date))}}</td>
                                <th>{{$r->instruction}}</th>
                                <th>{{$r->jnum ?? $r->ordnum}}</th>
                                
                                <td>{{$r->product_name}}</td>
                                
                                
                                
                                
                                <td>{{$r->delivery_type}}</td>
                                                             
                                
                                <th>{{$r->schedtype}}</th>
                                <th>{{$r->prod_branch}}</th>
                                <td><a target="_blank" href="{{ route('sales-transaction.view',$r->hid) }}">{{$r->order_number}}</a></td>
                                <td>{{date('Y-m-d',strtotime($r->hcreated))}}</td>
                                <td>{{date('h:i A',strtotime($r->hcreated))}}</td>
                                <th>{{$r->delivery_status}}</th>
                                <th>{{$r->customer_contact_number}}</th>                                
                                <th>{{$r->catname}}</th>
                                <th>{{$r->order_source}}</th> 
                                <td>{{number_format(($r->price * $r->qty),2)}}</td>
                                <th>{{$r->jo_order_type}}</th> 
                                <th>{{$r->item_type}}</th>
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



@endsection

@section('customjs')
<script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.colVis.min.js') }}"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/time.js"></script>
<script>
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
                targets: [8,9,10,11,12,13,14,15,16,17,18,19,20,21],
                visible: false
            },{ type: 'time-uni', targets: [6,15] } ]
        } );
    } );
</script>
@endsection



