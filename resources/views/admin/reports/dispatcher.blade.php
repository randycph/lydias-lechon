@extends('admin.layouts.report')

@section('pagecss')
    <!-- vendor css -->
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashforge.dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skin.deepblue.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-admin.css') }}">

    <style>
        .items_table td {
            padding: 10px;
        }
    </style>
@endsection

@section('content')
@php
    $datetxt='';
    $dstart='';
    $old_value = '';
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
    $dbranch = '';
    if(isset($_GET['receiver']) && strlen($_GET['receiver']) > 0){
        $br = \App\EcommerceModel\Branch::whereId($_GET['receiver'])->first();
        $dbranch = "<br>".$br['name'];
    }

    foreach(collect($jo)->duplicates('jo_number') as $j){
        echo $j." x ".collect($jo)->where('jo_number',$j)->count()."<br>";
    }
@endphp
         
        <div class="container-fluid">
            <div class="text-center mg-b-20">
                <img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
                <h4 class="mg-b-0 tx-spacing--1">Forecaster Report</h4>
                {!! $datetxt !!} {!!$dbranch!!}
            </div>
            <input type="hidden" id="datetxt" value="{!! $datetxt !!}">
            <input type="hidden" id="dbranch" value="{!! $dbranch !!}">

            <div class="row-sm">
                <div class="col-md-12">
                    <form action="{{route('admin.report.forecaster')}}" method="get">
                        @csrf
                        <div class="row row-sm">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="tx-13">Start Date (Date Needed)</label>
                                    <input type="date" class="form-control input-sm" name="startdate" autocomplete="off" value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset">
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="tx-13">End (Date Needed)</label>
                                    <input type="date" class="form-control input-sm" name="enddate" autocomplete="off" value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset">
                                </div>
                            </div>
                            
                            <div class="col-md-4 filter-action mg-r-5">
                                <a href="#" onclick="$('#adv').toggle();" class="btn btn-success mg-t-7 mg-r-5">Advance Filter</a>
                                <button type="submit" class="btn btn-primary mg-t-7 mg-r-5">Generate</button>
                                <a href="{{route('admin.report.forecaster')}}" class="btn btn-info mg-t-7 mg-r-5">Reset</a>
                            </div>
                        </div>
                        <div class="row" id="adv" style="display:none;">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Time Needed</label>
                                    <select name="start_time" id="start_time" class="form-control">      
                                        <option value="">- Select Time -</option>
                                        <option value="07:00:00">07:00 AM</option>                                         
                                        <option value="12:00:00">12:00 NOON</option>                                        
                                        <option value="14:00:00">02:00 PM</option>
                                        <option value="17:00:00">05:00 PM</option>                             
                                        <option value="00:00:00">12:00 AM</option>
                                        <option value="01:00:00">01:00 AM</option>
                                        <option value="02:00:00">02:00 AM</option>
                                        <option value="03:00:00">03:00 AM</option>
                                        <option value="04:00:00">04:00 AM</option>
                                        <option value="05:00:00">05:00 AM</option>
                                        <option value="06:00:00">06:00 AM</option>
                                        <option value="08:00:00">08:00 AM</option>
                                        <option value="09:00:00">09:00 AM</option>
                                        <option value="10:00:00">10:00 AM</option>
                                        <option value="11:00:00">11:00 AM</option>
                                        <option value="13:00:00">01:00 PM</option>
                                        <option value="15:00:00">03:00 PM</option>
                                        <option value="16:00:00">04:00 PM</option>
                                        <option value="18:00:00">06:00 PM</option>
                                        <option value="19:00:00">07:00 PM</option>
                                        <option value="20:00:00">08:00 PM</option>
                                        <option value="21:00:00">09:00 PM</option>
                                        <option value="22:00:00">10:00 PM</option>
                                        <option value="23:00:00">11:00 PM</option>
                                        @isset($_GET['start_time'])
                                            <option value="{{$_GET['start_time']}}" selected="selected">{{ $_GET['start_time'] }}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            
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
                                    <label class="tx-13">Receiver Branch</label>
                                    <select name="receiver" id="receiver" class="form-control">
                                        <option value="">- Select Receiver -</option>
                                        @forelse(\App\EcommerceModel\Branch::orderBy('name')->get() as $cus)
                                            <option value="{{$cus->id}}">{{$cus->name}}</option>
                                        @empty
                                        @endforelse
                                        @isset($_GET['receiver'])
                                            <option value="{{$_GET['receiver']}}" selected="selected">{{ $_GET['receiver'] }}</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Category</label>
                                    <select name="item_type" id="item_type" class="form-control">
                                        <option value="">- Select Category -</option>
                                        <option value="Miscellaneous">Miscellaneous</option>
                                        @forelse(\App\Models\ProductCategory::orderBy('name')->get() as $ca)
                                            <option value="{{$ca->name}}">{{$ca->name}}</option>
                                        @empty
                                        @endforelse                                       
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
                <div class="row row-sm">
                    <!-- Start Filters -->
                    <div class="col-md-12">
                        <div class="card-body pd-y-30 d-flex align-items-center justify-content-center">
                            <div class="d-sm-flex">
                                <div class="media mg-r-10">
                                    <div class="wd-20 wd-md-50 ht-40 ht-md-50 bg-primary tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
                                        <i class="fa fa-chart-bar"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">TOTAL WHOLE LECHON ORDER</h6>
                                        <h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0">{{collect($jo)->where('jo_category','=','Order')->where('is_misc','0')->where('production_item','1')->sum('qty')  + collect($rs)->where('is_misc','0')->where('production_item','1')->sum('qty')}}</h4>
                                    </div>
                                </div>

                                <div class="media mg-r-10">
                                    <div class="wd-20 wd-md-50 ht-40 ht-md-50 bg-success tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
                                        <i class="fa fa-chart-bar"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">TOTAL PANTAGA</h6>
                                        <h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0">{{collect($jo)->where('jo_category','=','Pantaga')->sum('qty')}}</h4>
                                    </div>
                                </div>
                                <div class="media mg-r-10">
                                    <div class="wd-20 wd-md-50 ht-40 ht-md-50 bg-warning tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
                                        <i class="fa fa-chart-bar"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Total Display</h6>
                                        <h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0">{{collect($jo)->where('jo_category','=','Display')->sum('qty')}}</h4>
                                    </div>
                                </div>
                                <div class="media mg-r-10">
                                    <div class="wd-20 wd-md-50 ht-40 ht-md-50 bg-danger tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
                                        <i class="fa fa-chart-bar"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Total Alpha Size</h6>
                                        <h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0">{{collect($jo)->where('jo_category','=','Alpha Size')->sum('qty')}}</h4>
                                    </div>
                                </div>
                                <div class="media mg-r-10">
                                    <div class="wd-20 wd-md-50 ht-40 ht-md-50 bg-orange tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
                                        <i class="fa fa-chart-bar"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Total Lechon</h6>
                                        <h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0">{{collect($jo)->where('is_misc','0')->where('production_item','1')->whereIn('jo_category',['Order','Pantaga','Display','Alpha Size'])->sum('qty') 
                                    + collect($rs)->where('is_misc','0')->where('production_item','1')->sum('qty')}}</h4>
                                    </div>
                                </div>
                                <div class="media mg-r-10">
                                    <div class="wd-20 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
                                        <i class="fa fa-chart-bar"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Total Miscellaneous Qty</h6>
                                        <h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0">{{collect($rs)->where('is_misc','=','1')->sum('qty')}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive-lg">
                            <table class="table mg-b-0 table-light table-bordered" style="width:100%;">   
                                <tbody> 
                                @foreach($rs as $r)
                                    @if($r[0]->trantype == 'sales')
                                    <tr style="background-color: #FF6C00;" class="tr_thead">
                                        <th scope="col" width="7%" style="color:#fff;">Order #</th>
                                        <th scope="col" width="10%" style="color:#fff;">Customer</th>
                                        <th scope="col" width="10%" style="color:#fff;">Date & Time Needed</th>
                                        <th scope="col" width="10%" style="color:#fff;">Production Date & Time</th>
                                        <th scope="col" width="10%" style="color:#fff;">Delivery Type</th>
                                        <th scope="col" width="8%" style="color:#fff;">Status</th>
                                    </tr>
                                    <tr style="background-color:#e3e7ed;">
                                        <th style="color:#000;">{{ $r[0]->order_number }}</th>
                                        <td>{{ $r[0]->customer_name }}</td>
                                        <td>
                                            {{ date('m-d-Y',strtotime($r[0]->delivery_date)) }} 
                                            @if(date('h:i A',strtotime($r[0]->delivery_date)) == '12:00 PM') 
                                                12:00 NOON 
                                            @else 
                                                {{ date('h:i A',strtotime($r[0]->delivery_date)) }} 
                                            @endif
                                        </td>
                                        <td>
                                            @if(date('m-d-Y',strtotime($r[0]->deldate)) <> '1970-01-01')
                                                {{ date('m-d-Y',strtotime($r[0]->deldate)) }} 
                                            @endif
                                            
                                            @if(date('m-d-Y',strtotime($r[0]->deldate)) <> '1970-01-01')
                                                {{ date('h:i A',strtotime($r[0]->deldate)) }} 
                                            @endif
                                        </td>
                                        <td>{{ $r[0]->delivery_type }}</td>
                                        <td>{{ $r[0]->delstat }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="padding-top:20px;padding-bottom: 10px;">
                                            <div class="accordian-body">
                                                <div class="autoship-table">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-sm table-hover mg-0 items_table">
                                                            <thead>
                                                                <th scope="col" width="30%">JO #</th>
                                                                <th scope="col" width="30%">Product</th>
                                                                <th scope="col">Item Category</th>
                                                                <th scope="col">Category</th>
                                                                <th scope="col">Order Type</th>
                                                                <th scope="col">Qty</th>
                                                                <th scope="col">Price</th>
                                                                <th scope="col">Total</th>
                                                            </thead>
                                                            <tbody>
                                                                @php $total = 0; @endphp
                                                                @foreach($r as $x)
                                                                    @php $total += $x->price*$x->qty; @endphp
                                                                    <tr>
                                                                        <td>{{ $x->jo_number }}</td>
                                                                        <td>{{ $x->product_name }}</td>
                                                                        <td>{{ $x->catname }}</td>
                                                                        <td>{{ $x->item_type}}</td>
                                                                        <td>{{ $x->jo_order_type }}</td>
                                                                        <td>{{ number_format($x->qty,2) }}</td>
                                                                        <td>{{ number_format($x->price,2) }}</td>
                                                                        <td>{{ number_format($x->price*$x->qty,2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <hr>
                                                    <div class="row" style="padding-bottom:0px;align-items:inherit;">
                                                        @php
                                                            $address = trim($r[0]->address_street)." ".trim($r[0]->address_municipality).", ".trim($r[0]->address_city).", ".trim($r[0]->address_region);
                                                        @endphp
                                                        <div class="col-sm-8">
                                                            <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Customer Details</label>
                                                            <p>
                                                                Customer Contact # : {{ $r[0]->customer_contact_number }}<br>
                                                                Customer Address : @if(strlen($address)>15) {!!$address!!} @endif<br>
                                                                Delivery Address : {{ $r[0]->customer_delivery_adress }}<br>
                                                                Instruction : {{ $r[0]->instruction }}
                                                            </p>
                                                            <br>
                                                            <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Other Details</label>
                                                            <p>
                                                                Agent # : {{ $r[0]->agent }}<br>
                                                                Order Source : {{ $r[0]->order_source }}<br>
                                                                Pickup Branch : {{ $r[0]->receiver }}
                                                            </p>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Order Summary</label>
                                                            <ul class="list-unstyled lh-7 pd-r-10">
                                                                <li class="d-flex justify-content-between">
                                                                    <span>Subtotal</span>
                                                                    <span>{{ number_format($total,2) }}</span>
                                                                </li>
                                                                <li class="d-flex justify-content-between">
                                                                    <span>Delivery Fee</span>
                                                                    <span>{{ number_format($r[0]->delivery_fee_amount,2) }}</span>
                                                                </li>
                                                                <li class="d-flex justify-content-between">
                                                                    <strong>Total Amount</strong>
                                                                    <strong>Php {{ number_format($total+$r[0]->delivery_fee_amount,2) }}</strong>
                                                                </li>
                                                            </ul>

                                                            <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Payment Summary</label>
                                                            <ul class="list-unstyled lh-7 pd-r-10">
                                                            @php
                                                                $payment = \App\EcommerceModel\SalesPayment::where('sales_header_id',$r[0]->hid)->get();
                                                            @endphp

                                                            @forelse($payment as $pp)
                                                                <li class="d-flex justify-content-between">
                                                                    <span>{{ $pp->payment_type }}</span>
                                                                    <span>{{ number_format($pp->amount,2) }}</span>
                                                                </li>
                                                            @empty
                                                            @endforelse
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                        
                    </div>
                    <!-- End Filters -->
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        @if ($rs->firstItem() == null)
                            <p class="tx-gray-400 tx-12 d-inline">Showing 0 of 0 items</p>
                        @else
                            <p class="tx-gray-400 tx-12 d-inline">Showing {{ $rs->firstItem() }} to {{ $rs->lastItem() }} of {{ $rs->total() }} items</p>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="float-right">
                            {{ $rs->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
   

@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/prismjs/prism.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/feather-icons/feather.min.js') }}"></script>

    <script src="{{ asset('js/dashforge.js') }}"></script>
@endsection



