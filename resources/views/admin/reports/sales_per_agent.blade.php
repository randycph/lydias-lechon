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
    
    <div class="container">
        <div class="text-center mg-b-20"><img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
        <h4 class="mg-b-0 tx-spacing--1">Sales per Agent Report</h4></div>
        
        <div class="row">
            <div class="col-md-12">
                 <form method="get" action="{{route('admin.report.sales-per-agent')}}">
                    @csrf
                <table width="100%" style="font-size:12px;font-family:Arial;">
                    <tr>
                        <td>Agent Code:
                            <select name="agent" id="agent" class="form-control">
                                <option value="">Select Agent Code</option>
                                @forelse($agents as $a)
                                    <option value="{{$a->agent}}">{{$a->agent}}</option>
                                @empty
                                @endforelse
                                @isset($_GET['agent'])
                                    <option value="{{$_GET['agent']}}" selected="selected">{{ $_GET['agent'] }}</option>
                                @endisset
                            </select>
                        </td>
                        <td>Start: <input type="date" name="startdate" class="form-control input-sm " value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset"></td>
                        <td>End: <input type="date" name="enddate" class="form-control input-sm " value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset"></td>                        
                        <td><br><input type="submit" value="Generate" class="btn btn-md btn-primary"></td>
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
                            <th>Date</th>
                            <th>Agent</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total Amount</th>

                        </tr>
                        </thead>
                        <tbody>
                        @forelse($rs as $r)
                            <tr>
                                <td>{{$r->order_number}}</td>
                                <td>{{$r->hcreated}}</td>
                                <td>{{$r->agent}}</td>
                                <td>{{$r->customer_name}}</td>
                                <td>{{$r->product_name}}</td>
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
                targets: [],
                visible: false
            } ]
        } );
    } );
</script>
@endsection



