@extends('admin.layouts.app')

@section('pagetitle')
    Job Order
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/prismjs/themes/prism-vs.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datextime/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lydias-admin.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">

    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">

    <style>
        .bootstrap-tagsinput .tag {
            background-color : rgb(255, 255, 255, 0.5);
            color : black;
        }
    </style>
@endsection

@section('content')

<div class="container pd-x-0">
    <div class="d-sm-flex justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page">Portal</li>
                    <li class="breadcrumb-item active" aria-current="page">Forecaster</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Forecast Job Order</h4>
        </div>
    </div>
    <form autocomplete="off" action="{{ route('forecaster.store') }}" method="post">
        @csrf
        <div class="row row-sm">
            <div class="col-lg-6">
                <h6 class="mg-b-20 tx-semibold">Order Details</h6>
                <div class="cs-search mg-b-10">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <th scope="row">Product Name</th>
                                    <td>{{ $sales_detail->product->name }} {{ $sales_detail->weight }} @if($sales_detail->paella_qty > 0) Boneless @endif  {{ $sales_detail->no_of_pax }} </td>
                                </tr>
                                <tr>
                                    <th scope="row">Qty</th>
                                    <td>{{ number_format($sales_detail->qty,2) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Price</th>
                                    <td>{{ number_format($sales_detail->price,2) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Paella Qty</th>
                                    <td>{{ number_format($sales_detail->paella_qty,2) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date Needed</th>
                                    <td>{{ date('d F Y h:i A',strtotime($sales_detail->delivery_date)) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Delivery Address</th>
                                    <td>{{ $sales_detail->header->delivery_types }}: {{ $sales_detail->header->customer_delivery_adress }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Remarks</th>
                                    <td>{{ $sales_detail->header->instruction }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <input type="hidden" name="sales_id" value="{{$sales_detail->id}}">
                <input type="hidden" name="header_id" value="{{$sales_detail->sales_header_id}}">
                <div class="form-group">
                    <label class="d-block">Branch <span class="tx-danger">*</span></label>
                    <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select production branch" data-width="100%" name="branch_id" required>
                        @foreach($branches as $branch)
                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
              
                    
                    <div class="form-group">
                        <label class="d-block">Receiver <span class="tx-danger">*</span></label>
                        <select class="form-control mg-b-5" name="receiver" id="receiver" required="required">
                            
                            @if(empty($sales_detail->header->outlet))                          
                                <option value="" disabled selected="selected">Select</option>
                            @endif
                            @foreach($receivers as $receiver)                                
                            <option value="{{$receiver->id}}" 
                                @if($sales_detail->header->outlet == $receiver->name) selected="selected" @endif
                            >
                                {{$receiver->name}}</option>
                            @endforeach
                        </select>
                    </div>
                   
               
                
                
                <div class="form-group">
                    <label class="d-block">Order Type <span class="tx-danger">*</span></label>
                    <select class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select order type" data-width="100%" name="schedule_type">
                        <option value="Whole">Whole</option>
                        <option value="Reserved">Reserved</option>
                        <option value="Additional">Additional</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="d-block">Schedule Date & Time</label>
                            <input type="text" name="delivery_date" class="form-control" placeholder="Choose date" id="date1" required="required" value="{{date('Y-m-d',strtotime($sales_detail->delivery_date))}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="d-block">&nbsp;</label>
                            <div class="input-group timepicker">
                                <select class="selectpicker" data-style="btn btn-outline-light btn-md btn-block tx-left" required="required" title="Select delivery time" data-width="100%" name="delivery_time" id="delivery_time">
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '07:00') selected @endif value="07:00">07:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '12:00') selected @endif value="12:00">12:00 NN</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '14:00') selected @endif value="14:00">02:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '17:00') selected @endif value="17:00">05:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '01:00') selected @endif value="01:00">01:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '02:00') selected @endif value="02:00">02:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '03:00') selected @endif value="03:00">03:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '04:00') selected @endif value="04:00">04:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '05:00') selected @endif value="05:00">05:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '06:00') selected @endif value="06:00">06:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '08:00') selected @endif value="08:00">08:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '09:00') selected @endif value="09:00">09:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '10:00') selected @endif value="10:00">10:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '11:00') selected @endif value="11:00">11:00 AM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '13:00') selected @endif value="13:00">01:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '15:00') selected @endif value="15:00">03:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '16:00') selected @endif value="16:00">04:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '17:00') selected @endif value="17:00">05:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '18:00') selected @endif value="18:00">06:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '19:00') selected @endif value="19:00">07:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '20:00') selected @endif value="20:00">08:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '21:00') selected @endif value="21:00">09:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '22:00') selected @endif value="22:00">10:00 PM</option>
                                    <option @if(date('H:i',strtotime($sales_detail->delivery_date)) == '23:00') selected @endif value="23:00">11:00 PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:none;" id="warning_msg" class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Warning!</h4>                    
                    <hr>
                    <p class="mb-0">The time you've selected is past already.</p>
                </div>
            </div>

            <div class="col-lg-12 mg-t-20 mg-b-30">
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Submit Job Order</button>
                <a class="btn btn-outline-secondary btn-sm btn-uppercase " href="{{route('forecaster.index')}}">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>

    <script src="{{ asset('lib/datextime/moment.min.js') }}"></script>
    <script src="{{ asset('lib/datextime/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    
    <script>
        var dateToday = new Date(); 

        $(function(){
            'use strict'

            $('#date1').datepicker({
                minDate: dateToday,
                dateFormat: 'yy-mm-dd',
            });
        });

        $('#date1, #delivery_time').change(function(){
            var d = getAsDate($('#date1').val(),$('#delivery_time').val());
            var now = new Date();
            if(d < now){
                $('#warning_msg').show();
            }
            else{
                $('#warning_msg').hide();
            }
        })

        function getAsDate(day, time)
        {            
            var newDate = new Date(day + ' ' + time); ;
            return newDate;
        }

    </script>
@endsection

