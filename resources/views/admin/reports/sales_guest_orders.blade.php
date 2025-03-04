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
<div class="container">
    <div class="text-center mg-b-20">
        <img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
        <h4 class="mg-b-0 tx-spacing--1">Guest Orders Report</h4>
        {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}
    </div>
    <form action="" method="get">
        <input type="hidden" name="act" value="go">
        @csrf
        <table>
            <tr>
                <td>Start date</td>
                <td>End Date</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <input type="date" class="form-control input-sm" name="startdate" autocomplete="off" value="{{ $startDate }}">
                </td>
                <td>
                    <input type="date" class="form-control input-sm" name="enddate" autocomplete="off" value="{{ $endDate }}">
                </td>
                <td><button type="submit" class="btn btn-primary" style="margin:0px 0px 0px 20px;">Generate</button></td>
                <td><a href="{{ route('admin.report.sales_social') }}" class="btn btn-success" style="margin:0px 0px 0px 5px;">Reset</a></td>
            </tr>
        </table>
    </form>
    <br>

    @if(isset($rs))
    <br><br>
    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
        <thead>
            <th></th>
            <th>Customer</th>
            <th>Email</th>
            <th>Order No.</th>
            <th>Order Date</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
            @foreach($rs as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->customer_name }}</td>
                    <td>{{ $r->email }}</td>
                    <td>{{ $r->order_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->created_at)->format('Y-m-d h:i A') }}</td>
                   <td class="text-right">{{ number_format($r->gross_amount,2) }}</td>
                   <td>{{ $r->delivery_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/prismjs/prism.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
@endsection

@section('customjs')
<script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.colVis.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            pageLength: 20,
            order: [0, 'desc'],
            buttons: [
                {
                    extend: 'print',
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
                }
            ],
            columnDefs: [ 
                {
                    targets: [ 0 ],
                    visible: false
                }
            ]
        } );
    } );
</script>
@endsection



