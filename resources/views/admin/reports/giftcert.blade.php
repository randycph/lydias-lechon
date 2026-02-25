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

@php
    $status_display='';
    if(isset($_GET['status']) && strlen($_GET['status'])>=2){
        $status_display=$_GET['status'];
    }
    
    $date_display='';
    if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
        $date_display=date('M d, Y',strtotime($_GET['startdate']))." to ".date('M d, Y',strtotime($_GET['enddate']));
    }
@endphp
@section('pagetitle')
                Gift Certificate Report
                {{ $status_display }}
                {{$date_display}}
@endsection


@section('content')
    <div class="container">
        <div class="text-center mg-b-10">
            <img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
            <h4 class="mg-b-0 tx-spacing--1">Gift Certificate Report</h4>
        </div><br>
        
      
        <div class="row row-sm">
            <div class="col-md-12">
                <form>
                <table width="100%" style="font-size:12px;font-family:Arial;">
                    <tr>
                        <td>Status:
                            <select name="status" id="status" class="form-control">
                                <option value="">Select Status</option>                                            
                                    <option value="" selected>All</option>
                                    <option @if(isset($_GET['status']) && $_GET['status'] == 'Used') selected @endif value="Used">Used</option>
                                    <option @if(isset($_GET['status']) && $_GET['status'] == 'Unused') selected @endif value="Unused">Unused</option>
                                
                            </select>
                        </td>
                        <td>Start Date (Date Created)<input type="date" required name="startdate" class="form-control input-sm " value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset"></td>
                        <td>End Date (Date Created)<input type="date" required name="enddate" class="form-control input-sm " value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset"></td>                        
                        <td><br><input type="submit" value="Generate" class="btn btn-primary btn-sm"><a href="{{route('admin.report.gift_cert')}}" class="btn btn-info ml-1 btn-sm">Reset</a></td>
                        <td style="display: none;"><br><a href="#" onclick="$('#advance_modal').modal('show');" class="btn btn-md btn-info">Advance Search</a></td>
                    </tr>
                </table>
                </form>
               
            </div>
        </div>
        @if(isset($rs))
        <br><br>
            <div class="row row-sm">
                <!-- Start Filters -->
                <div class="col-md-12">
                    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
                        <thead>
                            <tr>
                                
                                <th>Code</th>
                                <th>Serial</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Status</th>                                
                                <th>Sales/JO order #</th>
                                <th>Claimed Date</th>
                                
                                                             
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rs as $r)
                            <tr>
                                <td>{{$r->code}}</td>
                                <td>{{$r->serial_number}}</td>
                                <td align="right">{{number_format($r->amount,2)}}</td>
                                <td>{{$r->gc_type}}</td>                                
                                <td>{{$r->status}}</td>
                                <td>{{$r->sales_header_id}}</td>
                                <td>@if(isset($r->claimed_at)){{date('Y-m-d h:i A',strtotime($r->claimed_at))}} @endif</td>
                               
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
                    filename: 'LeftoverReport',
                    title: 'Leftover Report ({{ $status_display }} | {{$date_display}})',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    filename: 'LeftoverReport',
                    title: 'Leftover Report ({{ $status_display }} | {{$date_display}})',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    filename: 'LeftoverReport',
                    title: 'Leftover Report ({{ $status_display }} | {{$date_display}})',
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
            } ]
        } );
    } );
</script>
@endsection



