@extends('admin.layouts.app')

@section('pagetitle')
Manage Customer
@endsection

@section('pagecss')
<link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
<style>
    .row-selected {
        background-color: #92b7da !important;
    }
    .table td {
        padding: 10 10px !important;
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
                <h4 class="mg-b-0 tx-spacing--1">{{$branch_name}} Orders</h4>
            </div>
        </div>

            <!-- Start Pages -->
            <div class="col-md-12">
                <a href="{{ route('forecaster.index') }}" class="btn btn-xs btn-secondary mg-b-5"><i data-feather="arrow-left"></i> Back to Schedules</a>
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 20%;overflow: hidden;">Product</th>
                                    <th style="width: 10%;">Qty</th>
                                    <th style="width: 15%;">Delivery Date</th>
                                    <th style="width: 15%;">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <strong> {{ $order->salesdetail->product_name }}</strong>
                                        </td>
                                        <td class="text-right">{{ $order->qty }}</td>
                                        <td>{{ date('d M Y h:i A',strtotime($order->delivery_date)) }}</td>
                                        <td>
                                            <nav class="nav table-options">
                                                <a class="nav-link" href="javascript:void(0)" onclick="update_order('{{$order->id}}','{{$order->qty}}')" title="Update Order">Update</a>
                                                <a class="nav-link" href="javascript:void(0)" onclick="cancel_order('{{$order->id}}')" title="Cancel Order">Cancel</a>
                                            </nav>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4"><center>No Orders found.</center></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Pages -->
        </div>
    </div>

    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="categories" name="categories">
        <input type="text" id="status" name="status">
    </form>

    
    <div class="modal effect-scale" id="prompt-update-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Update Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form autocomplete="off" action="{{ route('forecaster.update-order') }}" method="post">
                    @method('POST')
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="order_id">

                        <label class="d-block">Qty *</label>
                        <input required type="number" class="form-control mg-b-20 text-right" id="qty" name="qty">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
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
                <form action="{{route('forecaster.branch-cancel-order')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="cancel_order_id">
                        <p>You are about to cancel this order. Do you want to continue?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger">Yes, Cancel</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script>
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
        function update_order(id,qty){
            $('#prompt-update-order').modal('show');
            $('#order_id').val(id);
            $('#qty').val(qty);
            $('#qty').attr({"max" : qty, "min" : 1});        
        }

        function cancel_order(id){
            $('#prompt-cancel-order').modal('show');
            $('#cancel_order_id').val(id);       
        }
    </script>
@endsection
