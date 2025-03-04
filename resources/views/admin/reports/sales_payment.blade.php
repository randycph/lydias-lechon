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
            <h4 class="mg-b-0 tx-spacing--1">Sales Payment Report</h4></div>
          

            <div class="row-sm">
                <div class="col-md-12">
                    <form action="{{route('admin.report.sales_payment')}}" method="get">
                      
                        @csrf
                        <div class="row row-sm">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">Payment Start Date</label>
                                    <input type="date" class="form-control input-sm" name="startdate"  autocomplete="off" value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">Payment End Date</label>
                                    <input type="date" class="form-control input-sm" name="enddate"  autocomplete="off" value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset">
                                </div>
                            </div>
                            <div class="col-md-3 filter-action mg-r-5">
                                <br>
                                <a href="#" onclick="$('#adv').toggle();" class="btn btn-success mg-t-7 mg-r-5 btn-sm">Advance Filter</a>
                                <button type="submit" class="btn btn-primary mg-t-7 mg-r-5 btn-sm">Generate</button>
                            </div>
                        </div>
                        <div class="row" id="adv" style="display:none;">
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
                                    <label class="tx-13">Status</label>
                                    <select name="status" id="status" class="form-control">   
                                        <option value="">ALL</option>                 
                                        <option value="PENDING">PENDING</option>
                                        <option value="PAID">PAID</option>     
                                        @isset($_GET['status'])
                                            <option value="{{$_GET['status']}}" selected="selected">{{ $_GET['status'] }}</option>
                                        @endisset                                  
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="tx-13">Payment Type</label>
                                    <select name="payment_type" id="payment_type" class="form-control">  
                                        <option value="">All</option>        
                                        <option value="Bank Deposit">Bank Deposit</option>
                                        <option value="Gift Certificate">Gift Certificate</option>
                                        <option value="IPAY">Ipay</option>                                               
                                        <option value="M Lhuillier">M Lhuillier</option>  
                                        <option value="money transfer">Money Transfer</option>
                                        <option value="payment center">Payment Center</option>  
                                        @isset($_GET['payment_type'])
                                            <option value="{{$_GET['payment_type']}}" selected="selected">{{ $_GET['payment_type'] }}</option>
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
                        <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
                            <thead>
                            <tr>
                                <th>Order#</th>
                                <th>Order Date</th>
                                <th>Order Time</th>
                                <th>Payment Date</th>
                                <th>Payment Time</th>
                                <th>Customer</th>
                                <th>Delivery Type</th>
                                <th>Delivery Address</th>
                                <th>Delivery Status</th>
                                <th>Contact Number</th>
                                <th>Order Source</th>
                                <th>Type</th>
                                <th>Ref#</th>
                                <th>Status</th>                               
                                <th>Amount</th>   
                                <th>Approval Code</th>  
                                <th>Approved By</th>    
                                <th>Remarks</th>  
                                <th>Attachment</th>  
                                <th>Date Needed</th>  
                                <th>Time Needed</th> 
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($rs as $r)
                                @php

                                    $count_unique_order = collect($rs)->where('hid',$r->hid)->sortBy('pcreated');
                                    //$cs = '';
                                    $order_number = 0;
                                    $x = 0;
                                    foreach($count_unique_order as $c){
                                        $x++;
                                        if($r->pid == $c->pid){
                                            $order_number = $x;
                                        }
                                        //$cs .="<br>".$r->pcreated." x ".$c->pcreated." x ".$c->pid." x ".$r->pid." x ".$x." x ".$order_number;
                                        
                                    }

                                    $approval_by = '';
                                    $approvalcode='';
                                    $approvalremarks='';
                                    if($r->status == 'PAID'){
                                        $b = \App\Approvals::where('reference_id',$r->sales_header_id)->where('approval_type','Payment')->get();
                                        if($b){
                                            $h = 0;
                                            foreach($b as $a){
                                                $h++;
                                                if($h == $order_number){
                                                    $approvalcode = $a->approval_code;
                                                    $approval_by = $a->user->name;
                                                    $approvalremarks=$a->remarks;
                                                }
                                            }
                                        }
                                    }
                                    
                                    $needed_d = '';
                                    $needed_t = '';
                                    $qs = \DB::select("select DATE_FORMAT(delivery_date,'%H:%i:%s') as timeneeded, DATE_FORMAT(delivery_date, '%Y-%m-%d') as dateneeded, delivery_date  from ecommerce_sales_details where sales_header_id='".$r->hid."'");
                                    foreach($qs as $qq){
                                        if(isset($qq->delivery_date)){
                                            $needed_d = $qq->dateneeded;
                                            $needed_t = $qq->timeneeded;
                                        }
                                    }
                                @endphp
                                <tr style="text-align: left">
                                    <td><a target="_blank" href="{{ route('sales-transaction.view',$r->hid) }}">{{$r->hnum}}</a></td>
                                    <td>{{date('Y-m-d',strtotime($r->hcreated))}}</td>
                                    <td>{{date('h:i A',strtotime($r->hcreated))}}</td>                                
                                    <td>{{date('Y-m-d',strtotime($r->pcreated))}}</td>
                                    <td>{{date('h:i A',strtotime($r->pcreated))}}</td>
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
                                    <th>{{$r->order_source}}</th>
                                    <th>{{$r->payment_type}}</th>
                                    <th>{{$r->receipt_number}}</th>
                                    <th>{{$r->status}}</th>                                    
                                    <td>{{number_format($r->amount,2)}}</td>
                                    <td>{{$approvalcode}}</td> 
                                    <td>{{$approval_by}}</td>   
                                    <td>{{$approvalremarks}}</td> 
                                    <td>@if($r->file_url !=null)
                                            <a href="{{$r->file_url}}" target="_blank">View</a>
                                        @else                                        
                                        @endif
                                       
                                    </td> 
                                    <td>{{$needed_d}}</td> 
                                    <td>{{$needed_t}}</td>
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
<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/time.js"></script>
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
                targets: [5,6,7,8,9,10,17,19,20],
                visible: false
            },{ type: 'time-uni', targets: [2,4] } ]
        } );
    } );
</script>
@endsection



