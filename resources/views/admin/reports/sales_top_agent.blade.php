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
    <div class="text-center mg-b-20">
        <img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
        <h4 class="mg-b-0 tx-spacing--1">Top Agents Report</h4>
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
                <td><a href="{{ route('admin.report.top_agents') }}" class="btn btn-success" style="margin:0px 0px 0px 5px;">Reset</a></td>
            </tr>
        </table>
    </form>
    <br>

    @if(isset($rs))
    <br><br>
    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
        <thead>
            <th>Agent Name</th>
            <th>No. of Orders</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            @foreach($rs as $r)
                <tr>
                    <td>{{ $r->agent }}</td>
                    <td>{{ number_format($r->total_orders,0) }}</td>
                    <td class="text-right"><a href="{{route('admin.report.sales-per-agent')}}?startdate={{$startDate}}&enddate={{$endDate}}&agent={{$r->agent}}" target="_blank">View Orders</a></td>
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
            order: [1, 'desc'],
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        columns: function (idx, data, node) {
                            if (node.innerHTML == "")
                                return false;
                            return true;
                        }
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
            ]
        } );
    } );
</script>
@endsection



