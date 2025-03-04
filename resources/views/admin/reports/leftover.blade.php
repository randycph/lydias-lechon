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
            <h4 class="mg-b-0 tx-spacing--1">Leftover Report</h4>
        </div><br>
        
        <div class="modal" id="advance_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Advance Search</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="advance_form">
                            <table width="100%" style="font-size:12px;font-family:Arial;">
                                <tr>
                                    <td>Product:
                                        <input type="text" name="product" class="form-control input-sm " placeholder="Input product name">
                                    </td>
                                    <td>Branch:
                                        <select name="branch" id="branch" class="form-control">
                                            <option value="">Select Branch</option>
                                            @forelse($branches as $b)
                                                <option value="{{$b->id}}">{{$b->name}}</option>
                                            @empty
                                            @endforelse
                                            @isset($_GET['branch'])
                                                <option value="{{$_GET['branch']}}" selected="selected">{{ $_GET['branch'] }}</option>
                                            @endisset
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Start: <input type="date" name="startdate" class="form-control input-sm " value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset"></td>
                                    <td>End: <input type="date" name="enddate" class="form-control input-sm " value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset"></td>
                                </tr>
                                <tr>                                   
                                    <td>Product Category:
                                        <select name="branch" id="branch" class="form-control">
                                            <option value="">Select Category</option>
                                            @forelse($categories as $c)
                                                <option value="{{$c->id}}">{{$c->name}}</option>
                                            @empty
                                            @endforelse
                                            @isset($_GET['branch'])
                                                <option value="{{$_GET['branch']}}" selected="selected">{{ $_GET['branch'] }}</option>
                                            @endisset
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="$('#advance_form').submit();" class="btn btn-primary">Generate</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div><br>
        <div class="row row-sm">
            <div class="col-md-12">
                <form>
                <table width="100%" style="font-size:12px;font-family:Arial;">
                    <tr>
                        <td>Branch
                            <select name="branch" id="branch" class="form-control">
                                <option value="">Select Branch</option>
                                @forelse($branches as $b)
                                    <option value="{{$b->id}}">{{$b->name}}</option>
                                @empty
                                @endforelse                              
                                @if(isset($_GET['branch']) && strlen($_GET['branch'])>0)   
                                    @php 
                                        $bb = \App\EcommerceModel\Branch::whereId($_GET['branch'])->first();
                                       
                                    @endphp
                                    <option value="{{$_GET['branch']}}" selected="selected">{{ $bb->name }}</option>
                                @endif
                            </select>
                        </td>
                        <td>Start Date <input type="date" name="startdate" class="form-control input-sm " value="@isset($_GET['startdate']){{ $_GET['startdate'] }}@endisset"></td>
                        <td>End Date <input type="date" name="enddate" class="form-control input-sm " value="@isset($_GET['enddate']){{ $_GET['enddate'] }}@endisset"></td>                        
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
                                
                                <th>Date</th>
                                <th>Branch</th>
                                <th>Category</th>
                                <th>Product</th>                                
                                <th>Qty</th>
                                <th>UoM</th>
                                <th>Remarks</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rs as $r)
                            <tr>
                                <td>{{$r->date}}</td>
                                <td>{{$r->bname}}</td>
                                <td>{{$r->cname}}</td>
                                <td>{{$r->pname}}</td>
                                <td align="right">{{number_format($r->qty,2)}}</td>
                                <td>{{$r->uom}}</td>
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



