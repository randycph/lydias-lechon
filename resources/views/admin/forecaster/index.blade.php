@extends('admin.layouts.app')

@section('pagetitle')
Manage Customer
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datextime/daterangepicker.css') }}" rel="stylesheet">
    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
        .table td {
            padding: 10 10px !important;
        }

        .input-transparent {
            background-color: rgba(0,0,0,0);
            color: blue;
            border: none;
        }

    </style>
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Forecaster</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Manage Forecaster</h4>
        </div>
    </div>
    @php
        if(isset($param)){
            $date = new \Carbon\Carbon(app('request')->input('from'));
            $firstDayNextWeek = $date->startOfWeek();
        } else {
            $firstDayNextWeek = \Carbon\Carbon::today()->startOfWeek();
        }
    @endphp

    @php
        if(isset($param)){
            $date = new \Carbon\Carbon(app('request')->input('from'));
            $firstDayPrevWeek = $date->startOfWeek();
        } else {
            $firstDayPrevWeek = \Carbon\Carbon::today()->startOfWeek();
        }
    @endphp

    <div class="row row-sm">
        <div class="col-md-12 mg-b-5">
            <div class="filter-buttons">
                <div class="d-md-flex bd-highlight">
                    <div class="bd-highlight">
                        <div class="dropdown d-inline">
                            <form id="prev_records">
                                @csrf
                                <input type="hidden" name="prev_from" id="prev_from" value="{{ $firstDayPrevWeek->subDays(7) }}">
                                <button class="btn btn-xs btn-secondary" type="submit">
                                    <i data-feather="arrow-left"></i> Previous Week
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="ml-auto bd-highlight">
                        @php
                            if(isset($param)){
                                $date = new \Carbon\Carbon(app('request')->input('from'));
                                echo date('m/d/Y',strtotime($date->startOfWeek())).' - '.date('m/d/Y',strtotime($date->endOfWeek()));
                            } else {
                                echo \Carbon\Carbon::today()->startOfWeek()->format('m/d/Y').' - '.\Carbon\Carbon::today()->endOfWeek()->format('m/d/Y');
                            }
                        @endphp
                    </div>

                    <div class="ml-auto bd-highlight">
                        <form id="next_records">
                            @csrf
                            <input type="hidden" name="next_from" id="next_from" value="{{ $firstDayNextWeek->addDays(7) }}">
                            <button class="btn btn-xs btn-secondary" type="submit">
                                Next Week <i data-feather="arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Pages -->
        <div class="col-md-12 mg-b-50">
            <div class="card card-dashboard-table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th colspan="7"><center>Schedules</center></th>
                        </tr>
                        <tr>
                            <th>Branches</th>

                            @for($i = 0; $i <= 6; $i++)
                                @php
                                    if(isset($param)){
                                        $date = date('m/d/Y', strtotime("+$i days",strtotime(app('request')->input('from'))));
                                    } else {
                                        $date = date('m/d/Y', strtotime("+$i days",strtotime("this week")));
                                    }
                                @endphp
                                <th>{{ $date }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branches as $branch)
                            <tr>
                                <td>{{ ucwords($branch->name) }}</td>
                                @for($i = 0; $i <= 6; $i++)
                                    @php
                                        if(isset($param)){
                                            $date = date('Y-m-d', strtotime("+$i days",strtotime(app('request')->input('from'))));
                                        } else {
                                            $date = date('Y-m-d', strtotime("+$i days",strtotime("this week")));
                                        }
                                        $total = \App\EcommerceModel\ProductionOrder::total_order_forecast($branch->id,$date);
                                    @endphp
                                <td>
                                    @if($total > 0)
                                        <a href="javascript:void(0);" onclick="view_assigned_orders('{{$branch->id}}','{{$date}}');">{{$total}}</a>
                                    @endif
                                </td>
                                @endfor
                            </tr>
                         @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    <!-- End Pages -->

    <!-- Start Pages -->
    <div class="row row-sm">

            <!-- Start Filters -->
        <div class="col-md-4">
            @if (auth()->user()->has_access_to_route('forecaster.order.multiple.delete'))
                <div class="list-search d-inline mg-b-5">
                    <div class="dropdown d-inline mg-r-10">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item tx-danger" href="javascript:void(0)" onclick="multiple_cancel_orders()">Cancel Order</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-8">
            <div class="ml-auto bd-highlight mg-t-10 mg-r-10">
                <div class="search-form mg-r-10">
                    <input name="forecast_searchtxt" type="text" id="forecast_searchtxt" class="form-control"  placeholder="Search">
                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                </div>
            </div>
        </div>

    </div>

        <div class="col-md-12">

            <div class="table-list mg-b-10">
                <div class="table-responsive-lg">
                    <table class="table mg-b-0 table-light table-hover" id="forecast_table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                        <label class="custom-control-label" for="checkbox_all"></label>
                                    </div>
                                </th>
                                <th style="width: 20%;overflow: hidden;">Product</th>
                                <th style="width: 10%;">Order no.</th>
                                <th style="width: 10%;">Customer</th>
                                <th style="width: 10%;">Qty</th>
                                <th style="width: 15%;">Price</th>
                                <th style="width: 20%;">Date Needed</th>
                                <th style="width: 30%;">Delivery Address</th>
                                <th style="width: 15%;">Total</th>
                                <th style="width: 15%;">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr id="row{{$order->id}}">
                                    @php $delivery_date = date('d F Y h:i A',strtotime($order->delivery_date)); @endphp
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{ $order->id }}">
                                            <label class="custom-control-label" for="cb{{ $order->id }}"></label>
                                        </div>
                                    </th>
                                    <td>
                                        <strong> <a target="_blank" href="{{ route('sales-transaction.view',$order->sales_header_id) }}">{{ $order->product_name }} {{ $order->weight }} @if($order->paella_qty > 0) Boneless @endif  {{ $order->no_of_pax }} </a></strong>
                                    </td>
                                    <td>{{$order->header->order_number}}</td>
                                    <td>{{$order->header->customer_name}}</td>
                                    <td class="text-right">{{ number_format($order->qty,1) }}</td>
                                    <td class="text-right">{{ number_format($order->price,2) }}</td>
                                    <td>{{ $delivery_date }}</td>
                                    <td>{{ $order->header->delivery_type.":" }} {{ $order->header->customer_delivery_adress }}</td>
                                    <td class="text-right">{{ number_format($order->qty*$order->price,2) }} ({{$order->header->Paymentadminstatus}})</td>
                                    @if (auth()->user()->has_access_to_route('joborder.assign') || auth()->user()->has_access_to_route('forecaster.cancel-order'))
                                        <td>
                                            <nav class="nav table-options">
                                                @if (auth()->user()->has_access_to_route('joborder.assign'))
                                                    {{--<a class="nav-link" href="{{ route('joborder.assign',$order->id) }}" title="Assign Order"><i data-feather="send"></i></a>--}}
                                                @endif
                                                @if (auth()->user()->has_access_to_route('joborder.cancel-order'))
                                                    <a class="nav-link" href="javascript:void(0)"  onclick="cancel_order('{{$order->sales_header_id}}');" title="Cancel Order"><i data-feather="x"></i></a>
                                                @endif
                                            </nav>
                                        </td>
                                    @endif
                                </tr>

                            @empty
                                <tr><td colspan="8"><center>No job orders found<center></td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-right">
                                {{ $orders->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- End Pages -->


    </div>
</div>

<form action="" id="posting_form" style="display: none;"  method="post">
    @csrf
    <input type="text" id="orders" name="orders">
</form>

<div class="modal fade" id="prompt-view-assigned-orders" tabindex="-1" role="dialog"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body pd-x-25 pd-sm-x-30 pd-t-40 pd-sm-t-20 pd-b-15 pd-sm-b-20">
                <a href="" role="button" class="close pos-absolute t-15 r-15" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>

                <div class="tab-content">

                    <div id="rating" class="tab-pane fade active show">
                        <div class="pd-t-10 pd-b-15 d-flex align-items-baseline">
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5">Assigned Orders</h3>
                        </div>

                        <div class="list-group list-group-flush tx-13 mg-b-0" id="orders_list">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal effect-scale" id="prompt-cancel-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Cancel Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('forecaster.cancel-order') }}" method="post">
                @method('POST')
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="ordr_id">

                    <label class="d-block">Reason *</label>
                    <textarea required name="reason" class="form-control" cols="30" rows="10"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger">Cancel Order</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-no-selected" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.no_selected_title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{__('common.no_selected')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal effect-scale" id="prompt-multiple-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Cancel Orders</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    You are about to cancel the selected orders. Do you want to continue?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnDeleteMultiple">Yes, Cancel</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal effect-scale" id="prompt-change-delivery-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('Delivery Status')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="dd_form" method="POST" action="{{route('sales-transaction.delivery_status')}}">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="delivery_status">Status</label>
                            <select id="delivery_status" class="selectpicker mg-b-5" name="delivery_status" data-style="btn btn-outline-light btn-md btn-block tx-left" title="- None -" data-width="100%" required="required">

                                <option value="Ready For delivery">Ready For delivery</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Delivered">Delivered</option>

                            </select>
                            <p class="tx-10 text-danger" id="error">
                                <x-error-message inputName="delivery_status" />
                            </p>
                        </div>
                        <div class="form-group" style="display:none;" id="delivered_by_div">
                            <label for="delivered_by">Delivered by:</label>
                            <input type="text" class="form-control" name="delivered_by" id="delivered_by">
                        </div>
                        <div class="form-group">
                            <label for="delivery_status">Remarks</label>
                            <textarea name="del_remarks" required="required" class="form-control" id="del_remarks" cols="30" rows="4"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="del_id" name="del_id" value="">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/datextime/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/datextime/moment.min.js') }}"></script>
    <script>
        var dateToday = new Date();

        $(function(){
            'use strict'

            $('#date1').datepicker({
                minDate: dateToday,
                dateFormat: 'yy-mm-dd',
            });
        });

        $("#qty").keypress(function(e){
            var keyCode = e.which;

            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) {
                return false;
            }
        });
    </script>
@endsection

@section('customjs')
    <script>
        $(document).ready(function(){
            $("#forecast_searchtxt").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#forecast_table tr").not('thead tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    <script>
        /*** Handles the Select All Checkbox ***/
        $("#checkbox_all").click(function(){
            $('.cb').not(this).prop('checked', this.checked);
        });

        function view_assigned_orders(branch_id,date)
        {
            $.ajax({
                type: "GET",
                url: "{{ route('display.assigned-orders') }}",
                data: { branch_id : branch_id, date_needed : date },
                success: function( response ) {
                    $('#orders_list').html(response);
                    $('#prompt-view-assigned-orders').modal('show');
                }
            });
        }

        function cancel_order(id)
        {
            $('#prompt-cancel-order').modal('show');
            $('#ordr_id').val(id);
        }

        function multiple_cancel_orders(){
            var counter = 0;
            var selected_orders = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_orders += fid.substring(2, fid.length)+'|';
            });

            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                $('#prompt-multiple-delete').modal('show');
                $('#btnDeleteMultiple').on('click', function() {
                    post_form("{{route('forecaster.order.multiple.delete')}}",selected_orders);
                });
            }
        }

        function post_form(url,orders){
            $('#posting_form').attr('action',url);
            $('#orders').val(orders);
            $('#posting_form').submit();
        }

        $('#next_records').submit(function(e){
            e.preventDefault();
            var type = 'next';

            filter(type);

        });

        $('#prev_records').submit(function(e){
            e.preventDefault();
            var type = 'prev';

            filter(type);

        });

        /*** generate the parameters for filtering of pages ***/
        function filter(type){

            var url = '';
            if(type == 'next'){

                url = 'from='+$('#next_from').val();

            } else {

                url = 'from='+$('#prev_from').val();
            }

            window.location.href = "{{ route('forecaster.show-deliveries') }}?"+url;
        }
    </script>

    <script>

        /*** handles the click function on filter classes, redirects it to function filter ***/
        $('#prev_records').submit(function(e){
            e.preventDefault();
            var type = 'prev';

            filter(type);

        });
        function change_delivery_status(id){
            $('#prompt-change-delivery-status').modal('show');
            $('#del_id').val(id);
            // $('#btnChangeDeliveryStatus').on('click', function() {
            //     let sales = $('#delivery_status').val();
            //     post_form("{{route('sales-transaction.delivery_status')}}",sales,id)
            // });
        }

        function change_production_time(dyt,tym){
            $('#tym').val(tym);
            // var now = new Date();

            // var day = ("0" + now.getDate()).slice(-2);
            // var month = ("0" + (now.getMonth() + 1)).slice(-2);

            // var today = now.getFullYear()+"-"+(month)+"-"+(day) ;

            $('#dyt').val("2021-03-12");
            $('#update_tym_frm').show();
        }

        $('#delivery_status').change(function(){
            if($(this).val() == 'In Transit'){
                $('#delivered_by_div').modal('show');
            }
            else{
                $('#delivered_by_div').modal('hide');
            }
        })
    </script>
@endsection
