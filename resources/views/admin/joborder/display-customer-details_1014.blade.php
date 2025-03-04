<div class="cs-search">
    <label class="d-block tx-semibold">Customer Details</label>
    <div class="table-responsive">
       
        <table class="table table-sm table-borderless">
            <tbody>
                <tr>
                    <th scope="row">Name</th>
                    <td>{{ $details->customer_name }}</td>
                </tr>
                <tr>
                    <th scope="row">E-mail</th>
                    <td>{{ $details->email }}</td>
                </tr>
                <tr>
                    <th scope="row">Contact Number</th>
                    <td>{{$details->customer_contact_number}}</td>
                </tr>
                <tr>
                    <th scope="row">Delivery Address</th>
                    <td>{{$details->customer_address }}</td>
                    <td>
                        <input type="hidden" value="{{$details->user_id}}" name="customer_id">
                        <input type="hidden" value="{{$details->customer_name}}" name="customer_name">
                        <input type="hidden" value="{{$details->customer_contact_number}}" name="customer_mobile">
                        <input type="hidden" value="{{$details->customer_address }}" name="customer_address" id="customer_address">
                    </td>
                </tr>
            </tbody>
        </table>
    
    </div>
</div>