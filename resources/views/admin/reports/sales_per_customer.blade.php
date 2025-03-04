@extends('admin.layouts.report')
@section('pagecss')   
    <style>       
        @page {
          size: auto;
        }
    </style>
@endsection
@section('content')
    
    <div class="container">
        <br><div class="text-center mg-b-20"><img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
        <div class="text-center"><h5>Sales per Customer Report</h5></div><br>
        <div class="row">
            <div class="col-md-12">
                <form method="get" action="{{route('admin.report.sales-per-customer')}}">
                    @csrf
                <table width="100%" style="font-size:12px;font-family:Arial;">
                    <tr>
                        <td>Customer:
                            <select name="customer" id="customer" class="form-control">
                                <option value="">Select Customer</option>
                                @forelse($customers as $c)
                                    <option value="{{$c->customer_name}}">{{$c->customer_name}}</option>
                                @empty
                                @endforelse
                                @isset($_GET['customer'])
                                    <option value="{{$_GET['customer']}}" selected="selected">{{ $_GET['customer'] }}</option>
                                @endisset
                            </select>
                        </td>
                        <td>Start: <input type="date" name="startdate" class="form-control input-sm " value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset"></td>
                        <td>End: <input type="date" name="enddate" class="form-control input-sm " value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset"></td>                        
                        <td><br><input type="submit" value="Generate" class="btn btn-md btn-success"></td>
                        <td><br><a href="{{route('admin.report.sales-per-customer')}}" class="btn btn-info mg-t-7 mg-r-5">Reset</a></td>
                    </tr>
                </table>
                </form>
               
            </div>
        </div>
        <br><br>
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
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($rs as $r)
                                @php
                                    // $jo_number='';
                                    // $jo = \App\EcommerceModel\JobOrder::where('sales_detail_id',$r->did)->first();                                    
                                    // if(!empty($jo))
                                    //     $jo_number=$jo->jo_number;
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
                targets: [4,5,6,7,10,11,12],
                visible: false
            } ]
        } );
    } );
</script>
@endsection

