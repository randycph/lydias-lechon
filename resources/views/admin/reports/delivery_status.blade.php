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
        <h4 class="mg-b-0 tx-spacing--1">Delivery Status Report</h4></div>
        
        <div class="row-sm">
            <div class="col-md-12">
                <form action="{{route('admin.report.delivery_status')}}" method="get">
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
                            <button type="submit" class="btn btn-primary mg-t-7 mg-r-5 btn-sm">Generate</button>
                            <a href="{{route('admin.report.delivery_status')}}" class="btn btn-info mg-t-7 btn-sm">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        @if(isset($rs))
        <br>
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
                            <tr style="text-align: left">
                                <td><a target="_blank" href="{{ route('admin.report.delivery_report',$r->hid) }}">{{$r->order_number}}</a></td>
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
                targets: [1,2,7,9,10,11,13],
                visible: false
            },
            { type: 'time-uni', targets: [2,14] } ]
        } );
    } );
</script>
@endsection



