@extends('admin.layouts.report')

@section('pagetitle')
Audit Trail (Sales)
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
            <h4 class="mg-b-0 tx-spacing--1">Audit Trail (Sales)</h4></div>
          

            <div class="row-sm">
                <div class="col-md-12">
                    <form action="{{route('admin.report.audit_trail_per_sales')}}" method="get">
                      
                        @csrf
                        <div class="row row-sm">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">Input Sales#</label>
                                    <input type="text" required class="form-control input-sm" name="pb"  autocomplete="off" value="@isset($_GET['pb']){{ $_GET['pb'] }}@endisset">
                                </div>
                            </div>
                           
                            <div class="col-md-3 filter-action mg-r-5">
                            
                                <button type="submit" class="btn btn-sm btn-primary mg-t-7 mg-r-5">Generate</button>
                                <a href="{{route('admin.report.audit_trail_per_sales')}}" class="btn btn-sm btn-info mg-t-7 mg-r-5">Reset</a>
                            </div>
                        </div>
                        

                    </form>
                </div>
            </div>

            @if(isset($rs))
                <div class="row row-sm">
                    <!-- Start Filters -->
                    <div class="col-md-12">
                        <!-- <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;"> -->
                        <table  id="example" border="1" class="display nowrap" style="width:100%;font: bold 13px/150% Arial, sans-serif, Helvetica;">
                            <thead>
                            <tr> 
                                <th>User</th>
                                <th>Date</th>
                                <th>Action</th> 
                                <th>Name</th>  
                                <th>Description</th> 
                                <th>Reference</th>   
                                <th>Old Value</th>
                                <th>New Value</th> 
                                                   
                            </tr>
                            </thead>
                            <tbody>
                         
                            @forelse($rs as $r) 
                                @php
                                    $user = $r->created_by;
                                    if(($user)){
                                        $uu = \App\Models\User::where('id', $user)->orWhere('email', $user)->first();
                                        if($uu) {
                                            $user = $uu->name;
                                        } else {
                                            $user = 'Unknown User';
                                        }
                                    }
                                @endphp
                                <tr style="text-align: left">
                                    <td>{{$user}}</td>
                                    <td>@if(date('Y-m-d',strtotime($r->activity_date)) <> '1970-01-01'){{date('m-d-Y H:i:s',strtotime($r->activity_date))}} @endif</td>
                                    <td>{{$r->activity_type}}</td>
                                    <td>{{$r->dashboard_activity}}</td>
                                    <td>{{$r->activity_desc}}</td>
                                    <td>{{$r->reference}}</td>
                                    <td>{{$r->old_value}}</td>
                                    <td>{{$r->new_value}}</td>
                                </tr>
                            @empty
                            @endforelse
                        

                            </tbody>

                        </table>
                    </div>
                 
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
                {
                    extend: 'colvis',
                    text: 'Column visibility',
                    buttons: [
                        {
                            extend: 'colvisGroup',
                            text: 'Show all columns',
                            show: ':hidden'
                        },
                        {
                            extend: 'colvisGroup',
                            text: 'Hide extra columns',
                            hide: []
                        },
                        'columnsToggle'
                    ]
                }
            ],
            columnDefs: [ {
                targets: [],
                visible: false
            },{ type: 'time-uni', targets: [2] } ]
        } );
    } );
</script>
@endsection



