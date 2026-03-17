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
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet" />

    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
        @page {
          size: auto;
        }
        #example {
            table-layout: fixed;
        }

        #example td, #example th {
            white-space: normal !important;
            word-break: break-word;
        }
        .clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;

            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal !important;
        }

        .break-all {
            word-break: break-all;
        }

    </style>
@endsection


@php
    $user_display='';
    if(isset($_GET['pb']) && strlen($_GET['pb'])>=1){
        $user_display=$_GET['pb'];
    }

    $date_display='';
    if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
        $date_display=date('M d, Y',strtotime($_GET['startdate']))." to ".date('M d, Y',strtotime($_GET['enddate']));
    }
@endphp
@section('pagetitle')
                Module Audit Trail
                {{$date_display}}
@endsection

@section('content')


        <div class="container-fluid">
            <div class="text-center mg-b-20"><img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
            <h4 class="mg-b-0 tx-spacing--1">Audit Trail (Module)</h4></div>
          

            <div class="row-sm">
                <div class="col-md-12">
                    <form action="{{route('admin.report.audit_trail_per_module')}}" method="get">
                      
                        @csrf
                        <div class="row row-sm">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">Select Module</label>
                                    <select id="module" name="module" class="form-control select2-ajax" style="width:100%">
                                        <option value="">Select Module</option>
                                        @foreach ($allowedTables as $k => $table)
                                            <option value="{{ $k }}" @if(request('module') == $k) selected @endif>{{ $table }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">Start Date (Log Date)</label>
                                    <input type="date" class="form-control input-sm" name="startdate"  autocomplete="off" value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="tx-13">End Date (Log Date)</label>
                                    <input type="date" class="form-control input-sm" name="enddate"  autocomplete="off" value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset">
                                </div>
                            </div>
                            <div class="col-md-3 filter-action mg-r-5">
                            
                                <button type="submit" class="btn btn-sm btn-primary mg-t-7 mg-r-5">Generate</button>
                                <a href="{{route('admin.report.audit_trail_per_module')}}" class="btn btn-sm btn-info mg-t-7 mg-r-5">Reset</a>
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
                                <th>Date</th>
                                <th>Created by</th>
                                <th>Activity Type</th> 
                                <th>Activity</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Description</th> 
                                <th>Reference</th>   
                                <th>Old Value</th>
                                <th>New Value</th> 
                                <th>Module</th> 
                                                   
                            </tr>
                            </thead>
                            <tbody>
                         
                            @foreach($rs as $r) 
                                <tr style="text-align: left">
                                    <td>@if(date('Y-m-d',strtotime($r->activity_date)) <> '1970-01-01'){{date('m-d-Y g:i A',strtotime($r->activity_date))}} @endif</td>
                                    <td>{{$r->user->name ?? 'Guest'}}</td>
                                    <td>{{$r->activity_type}}</td>
                                    <td>{{$r->dashboard_activity}}</td>
                                    <td>{{$r->email}}</td>
                                    <td>{{$r->role}}</td>
                                    <td>
                                        <div class="clamp-2 break-all"
                                            data-toggle="tooltip"
                                            title="{{ $r->activity_desc }}">
                                            {{ $r->activity_desc }}
                                        </div>
                                    </td>
                                    <td>{{$r->reference}}</td>
                                    <td>{{$r->old_value}}</td>
                                    <td>{{$r->new_value}}</td>
                                    <td>{{$r->db_table}}</td>
                                </tr>
                            @endforeach
                        

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
    
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
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
        $('[data-toggle="tooltip"]').tooltip();
        
        $('.select2-ajax').select2({
            placeholder: 'Select a module',
        });
        
        $('#example').DataTable( {
            dom: 'Bfrtip',
            pageLength: 20,
            sorting: [[ 0, "desc" ]],
            autoWidth: false,

            columnDefs: [
                { width: "140px", targets: 0 }, // Date
                { width: "140px", targets: 1 }, // Created by
                { width: "120px", targets: 2 }, // Activity Type
                { width: "120px", targets: 3 }, // Activity
                { width: "180px", targets: 4 }, // Email
                { width: "100px", targets: 5 }, // Role
                { width: "300px", targets: 6 }, // Description
                { width: "200px", targets: 7 }, // Reference
                { width: "220px", targets: 8 }, // Old Value
                { width: "220px", targets: 9 }, // New Value
                { width: "150px", targets: 10 }, // Module
                { type: 'time-uni', targets: [0] }
            ],
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
                    filename: 'UserAuditTrail',
                    title: 'User Audit Trail ({{ $user_display }} | {{$date_display}})',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    filename: 'UserAuditTrail',
                    title: 'User Audit Trail ({{ $user_display }} | {{$date_display}})',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    filename: 'UserAuditTrail',
                    title: 'User Audit Trail ({{ $user_display }} | {{$date_display}})',
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
            },{ type: 'time-uni', targets: [2] } ]
        } );
    } );
</script>
@endsection



