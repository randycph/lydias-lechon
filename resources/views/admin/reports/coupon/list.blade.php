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

    <style>
        @page {
            size: auto;
        }

        .row-selected {
            background-color: #92b7da !important;
        }
    </style>
@endsection

@section('content')

<div class="container">
    <div class="text-center mg-b-10">
        <img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
        <h4 class="mg-b-0 tx-spacing--1">Coupon Usage Report</h4>
    </div>
    <br>

    <div class="row row-sm">
        <div class="col-md-12">
            <form action="{{route('report.coupon.list')}}" method="get">
                <input type="hidden" name="act" value="go">
                @csrf

                <table width="100%" style="font-size:12px;font-family:Arial;">
                <tr>
                    <td>Coupon Code</td>
                    <td>Type of Coupon</td>
                    <td>Order Source</td>
                    <td>Order Start date</td>
                    <td>Order End Date</td>
                    <td></td>
                    <td></td>
                </tr>
                    <tr>
                        <td>
                            <input style="font-size:12px;width: 140px;" type="text" class="form-control input-sm" name="coupon_code" autocomplete="off"
                            @if(isset($_GET['coupon_code'])) value="{{$_GET['coupon_code']}}" @endif >
                        </td>
                        <td>
                        <select style="font-size:12px;width: 160px;"
                                name="coupon_type"
                                class="form-control input-sm">
                            <option value="">All</option>

                            <option value="free-shipping-optn"
                                @if(isset($_GET['coupon_type']) && $_GET['coupon_type'] == 'free-shipping-optn') selected @endif>
                                Free Shipping
                            </option>

                            <option value="discount-amount-optn"
                                @if(isset($_GET['coupon_type']) && $_GET['coupon_type'] == 'discount-amount-optn') selected @endif>
                                Amount Discount
                            </option>

                            <option value="discount-percentage-optn"
                                @if(isset($_GET['coupon_type']) && $_GET['coupon_type'] == 'discount-percentage-optn') selected @endif>
                                Percentage Discount
                            </option>

                            <option value="free-product-optn"
                                @if(isset($_GET['coupon_type']) && $_GET['coupon_type'] == 'free-product-optn') selected @endif>
                                Free Product
                            </option>
                        </select>
                    </td>
                        <td>
                <select style="font-size:12px;width: 140px;"
                        name="order_source"
                        class="form-control input-sm">
                    <option value="">All</option>
                    <option value="web" @if(isset($_GET['order_source']) && $_GET['order_source'] == 'web') selected @endif>
                        Web
                    </option>
                    <option value="jo" @if(isset($_GET['order_source']) && $_GET['order_source'] == 'jo') selected @endif>
                        JO
                    </option>
                </select>
            </td>

                        <td>
                            <input style="font-size:12px;width: 140px;" type="date" class="form-control input-sm" name="start" autocomplete="off"
                            @if(isset($_GET['start'])) value="{{$_GET['start']}}" @endif >
                        </td>

                        <td>
                            <input style="font-size:12px;width: 140px;" type="date" class="form-control input-sm" name="end" autocomplete="off"
                            @if(isset($_GET['end'])) value="{{$_GET['end']}}" @endif >
                        </td>
                        
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm" style="margin:0px 0px 0px 20px;">Generate</button>
                        </td>

                        <a href="{{ route('report.coupon.list') }}" class="btn btn-success btn-sm" style="margin:0px 0px 0px 20px;">
                                Reset
                            </a>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    @if($rs <>'')
        <br><br>

        <div class="row row-sm">
            <div class="col-md-12">
                <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
                    <thead>
                        <tr>
                            <th align="left">ID</th>
                            <th align="left">Code</th>
                            <th align="left">Name</th>
                            <th align="left">Reward</th>
                            <th align="left">Order Source</th>
                            <th align="left">Order #</th>
                            <th align="left">Customer</th>
                            <th align="left">Coupon Amount</th>
                            <th align="left">Total Amount</th>
                            <th align="left">Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $fsCount = 0;
                        $adCount = 0;
                        $pdCount = 0;
                        $fpCount = 0;
                    @endphp
                       @forelse($rs as $r)
                        @php
                            $displayId = '';

                            if ($r->reward == 'free-shipping-optn') {
                                $fsCount++;
                                $displayId = 'FS-' . $fsCount;
                                $rewardName = 'Free Shipping';
                            } elseif ($r->reward == 'discount-amount-optn') {
                                $adCount++;
                                $displayId = 'AD-' . $adCount;
                                $rewardName = 'Amount Discount';
                            } elseif ($r->reward == 'discount-percentage-optn') {
                                $pdCount++;
                                $displayId = 'PD-' . $pdCount;
                                $rewardName = 'Percentage Discount';
                            } elseif ($r->reward == 'free-product-optn') {
                                $fpCount++;
                                $displayId = 'FP-' . $fpCount;
                                $rewardName = 'Free Product';
                            } else {
                                $displayId = 'CP-' . ($loop->iteration);
                                $rewardName = $r->reward;
                            }
                        @endphp

                        <tr>
                            <td>{{ $displayId }}</td>
                            <td>{{ $r->coupon_code }}</td>
                            <td>{{ $r->name }}</td>
                            <td>{{ $rewardName }}</td>
                            <td>{{ $r->order_source_label  }}</td>
                            <td>{{ $r->order_number }}</td>
                            <td>{{ $r->customer_name }}</td>
                            <td>₱{{ number_format($r->coupon_amount ?? 0, 2) }}</td>
                            <td>₱{{ number_format($r->net_amount ?? 0, 2) }}</td>
                            <td>{{ $r->order_date}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No report result.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

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
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            order: [[9, 'desc']],
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
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                'colvis'
            ]
        });
    });
</script>
@endsection