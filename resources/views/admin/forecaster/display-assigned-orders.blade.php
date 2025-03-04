
	{{-- <div class="list-group-item pd-y-5 pd-x-0 d-flex align-items-center" style="display:none;">
		<strong class="tx-12 tx-rubik">{{ $order->jobOrder_details->jo_number }}</strong>
        <div class="mg-l-auto tx-rubik tx-color-03"><strong class="tx-12 tx-rubik">{{ $order->jobOrder_details->product_name }} 
            @if($order->jobOrder_details->sales_detail_id > 0)
                {{ $order->jobOrder_details->sales_detail->weight }} 
                @if($order->jobOrder_details->sales_detail->paella_qty > 0) 
                    Boneless 
                @endif  
                {{ $order->jobOrder_details->sales_detail->no_of_pax }} 
            @endif
            </strong></div>
        <div class="mg-l-auto tx-rubik tx-color-03">{{ date('Y-m-d h:i A',strtotime($order->delivery_date)) }}</div>
        <div class="mg-l-auto tx-rubik tx-color-03">{{$order->schedule_type}}</div>

        <div class="mg-l-20 tx-rubik tx-color-03 wd-10p text-right">
            @if($order->jobOrder_details->sales_detail_id > 0)
        	<a target="_blank" title="View Order Summary" href="{{route('sales-transaction.view',$order->jobOrder_details->sales_detail->sales_header_id)}}"><i class="fa fa-eye"></i></a>
            <a class="dropdown-item" href="javascript:void(0);" onclick="change_delivery_status({{$order->jobOrder_details->sales_detail->sales_header_id }})" title="Update Delivery Status" data-id="{{$order->jobOrder_details->sales_detail->sales_header_id }}"><i class="fa fa-edit"></i></a>
        	<a title="Cancel this job order" href="{{route('forecaster.remove.order',$order->id)}}"><i class="fa fa-trash"></i></a>
            @endif
        </div>

    </div> --}}
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
        
                <th>Production</th>                
                <th>Date Needed</th>
                <th>Category</th>
                
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            @php
                $del_status = '';
                if($order->jobOrder_details->sales_detail_id > 0){
                    $del_status == $order->jobOrder_details->sales_detail->header->delivery_status;
                }
            @endphp
            @if($del_status <> 'Delivered')
                <tr style="font-size:12px !important;">
                    <td>
                        @if($order->jobOrder_details->sales_detail_id == '0'){{ $order->jobOrder_details->customer_address ?? '' }} @else {{ $order->jobOrder_details->customer_name ?? '' }} @endif
                       
                    </td>
                 
                    <td>
                        <strong class="tx-12 tx-rubik">{{ $order->jobOrder_details->product_name }} 
                            @if($order->jobOrder_details->sales_detail_id > 0)
                                {{ $order->jobOrder_details->sales_detail->weight }} 
                                @if($order->jobOrder_details->sales_detail->paella_qty > 0) 
                                    Boneless 
                                @endif  
                                {{ $order->jobOrder_details->sales_detail->no_of_pax }} 
                            @endif
                        </strong>
                    </td>
                    <td>{{number_format($order->jobOrder_details->qty,2)}}</td>
                    <td>
                        @if($order->jobOrder_details->sales_detail_id > 0)
                            {{number_format($order->jobOrder_details->sales_detail->gross_amount,2)}}
                        @endif
                    </td>
                 
                    <td>{{ date('Y-m-d h:i A',strtotime($order->delivery_date)) }}</td>
                    <td>
                        {{ date('Y-m-d h:i A',strtotime($order->jobOrder_details->date_needed)) }}
                        <form action="" style="display:none;" id="update_tym_frm">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <select name="" id="tym">
                                            <option value="">- Time -</option>                                           
                                            <option value="05:00 AM">05:00 AM</option>
                                            <option value="06:00 AM">06:00 AM</option>
                                            <option value="07:00 AM">07:00 AM</option>
                                            <option value="08:00 AM">08:00 AM</option>
                                            <option value="09:00 AM">09:00 AM</option>
                                            <option value="10:00 AM">10:00 AM</option>
                                            <option value="11:00 AM">11:00 AM</option>
                                            <option value="12:00 PM">12:00 NOON</option>
                                            <option value="01:00 PM">01:00 PM</option>
                                            <option value="02:00 PM">02:00 PM</option>
                                            <option value="03:00 PM">03:00 PM</option>
                                            <option value="04:00 PM">04:00 PM</option>
                                            <option value="05:00 PM">05:00 PM</option>
                                            <option value="06:00 PM">06:00 PM</option>
                                            <option value="07:00 PM">07:00 PM</option>
                                            <option value="08:00 PM">08:00 PM</option>
                                            <option value="09:00 PM">09:00 PM</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" id="dyt">
                                    </td>
                                    <td>
                                        <a href="#" title="Update Production Time"><i class="fa fa-save"></i></a>
                                    </td>
                                </tr>
                            </table>                        
                            
                        </form>
                    </td>
                    <td>{{$order->jobOrder_details->jo_category}}</td>
                    
                    <td>@if($order->jobOrder_details->sales_detail_id > 0) {{$order->jobOrder_details->sales_detail->header->delivery_status }} @endif</td>
                    <td width="20%">
                    @if($order->jobOrder_details->sales_detail_id > 0)
                        <a target="_blank" title="View Order Summary" href="{{route('sales-transaction.view',$order->jobOrder_details->sales_detail->sales_header_id)}}"><i class="fa fa-eye"></i></a>
                        <a href="javascript:void(0);" onclick="change_delivery_status({{$order->jobOrder_details->sales_detail->sales_header_id }})" title="Update Delivery Status" data-id="{{$order->jobOrder_details->sales_detail->sales_header_id }}"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0);" onclick="change_production_time({{date('Y-m-d',strtotime($order->jobOrder_details->date_needed)) }},'{{ date('h:i A',strtotime($order->jobOrder_details->date_needed)) }}')" title="Change Production Time" data-id="{{$order->jobOrder_details->sales_detail->sales_header_id }}"><i class="fa fa-clock"></i></a>
                    @endif
                        <a title="Cancel this job order" href="{{route('forecaster.remove.order',$order->id)}}"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>

