@extends('admin.layouts.report')
@section('pagecss')   
    <style>       
        @page {
          size: auto;
        }
    </style>
@endsection
@section('content')
    
    <div class="container">
        <div class="text-center"><h5>Job Order Report</h5></div><br>
        <div class="row">
            <div class="col-md-12">
                <form>
                <table width="100%" style="font-size:12px;font-family:Arial;">
                    <tr>
                        <td>Branch:
                            <select name="branch" id="branch" class="form-control">
                                <option value="">Select Branch</option>
                                @forelse($branches as $b)
                                    <option value="{{$b->id}}">{{$b->name}}</option>
                                @empty
                                @endforelse
                                @isset($_GET['branch'])                                            
                                    @php 
                                        $bb = \App\EcommerceModel\ProductionBranch::whereId($_GET['branch'])->first();
                                       
                                    @endphp
                                    <option value="{{$_GET['branch']}}" selected="selected">{{ $bb->name }}</option>
                                @endisset
                            </select>
                        </td>
                        <td>Start: <input type="date" name="startdate" class="form-control input-sm " value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset"></td>
                        <td>End: <input type="date" name="enddate" class="form-control input-sm " value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset"></td>                        
                        <td><br><input type="submit" value="Generate" class="btn btn-md btn-success"></td>
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
                                <th>Job Order #</th>
                                <th>Delivery Date</th>
                                <th>Branch</th>
                                <th>Schedule Type</th>   
                                <th>Remarks</th>                           
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rs as $r)
                            <tr>
                                <td>{{$r->jo_number}}</td>
                                <td>{{date('d F Y h:i A',strtotime($r->delivery_date))}}</td>
                                <td>{{$r->production_name}}</td>
                                <td>{{$r->schedule_type}}</td>
                                <td>{{$r->remarks}}</td>
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



