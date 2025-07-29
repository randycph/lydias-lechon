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
                        <td>Start Date <input type="date" required name="startdate" class="form-control input-sm " value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset"></td>
                        <td>End Date <input type="date" required name="enddate" class="form-control input-sm " value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset"></td>                        
                        <td><br><input type="submit" value="Generate" class="btn btn-primary btn-sm"></td>
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
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Status</th>                                
                                <th>Sales order #</th>
                                                             
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rs as $r)
                            <tr>
                                <td>{{$r->code}}</td>
                                <td align="right">{{number_format($r->amount,2)}}</td>
                                <td>{{$r->gc_type}}</td>
                                <td>{{$r->status}}</td>
                                <td>{{$r->sales_header_id}}</td>
                               
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
                targets: [],
                visible: false
            } ]
        } );
    } );
</script>
@endsection



