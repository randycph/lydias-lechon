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
        <br>
        <div class="text-center mg-b-20"><img height="100px" src="{{ asset('images/lydias1965.png') }}" alt="">
        <div class="text-center"><h5>Forecast Report</h5></div><br>
        <div class="row">
            <div class="col-md-12">
                <form>
                <table width="100%" style="font-size:12px;font-family:Arial;">
                    <tr>
                        <td>Agents:
                            <select name="branch" id="branch" class="form-control">
                                <option value="">Select Branch</option>
                                @forelse($branches as $b)
                                    <option value="{{$b->id}}">{{$b->name}}</option>
                                @empty
                                @endforelse
                            </select>
                        </td>
                        <td>Start: <input type="date" name="startdate" class="form-control input-sm "></td>
                        <td>End: <input type="date" name="enddate" class="form-control input-sm "></td>                        
                        <td><br><input type="submit" value="Generate" class="btn btn-md btn-success"></td>
                    </tr>
                </table>
                </form>
               
            </div>
        </div>
        <br><br>
        @if(isset($rs))
            <div class="row row-sm">
                <!-- Start Filters -->
                <div class="col-md-12">
                    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
                        <thead>
                        <tr>
                            <th>Qty</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Date Needed</th>
                            <th>Delivery Date</th>
                            <th>Complimentary</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($rs as $r)
                            <tr style="text-align: left">
                                <td>{{number_format($r->qty,2)}}</td>
                                <td>{{$r->product_name}}</td>
                                <td></td>
                                <td>{{$r->customer_name}}</td>
                                <td>
                                     @php
                                        $text_array = explode(" ", $r->customer_delivery_adress);
                                        $chunks = array_chunk($text_array, 3);
                                        foreach ($chunks as $chunk) {
                                            echo implode(" ", $chunk)."<br>";                                          
                                        }
                                    @endphp
                                </td>
                                <td>{{date('Y-m-d h:i A',strtotime($r->date_needed))}}</td>
                                <td>{{date('Y-m-d h:i A',strtotime($r->delivery_date))}}</td>
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
                
            ],
            columnDefs: [ {
                targets: [],
                visible: false
            } ]
        } );
    } );
</script>
@endsection



