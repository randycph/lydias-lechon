<div class="cs-search">
    <label class="d-block tx-semibold">Customer Details</label>
    <div class="table-responsive">
        <table class="table table-sm table-borderless">
            <tbody>
                <tr>
                    <th scope="row">Name</th>
                    <td>{{ $details->name }}</td>
                </tr>
                <tr>
                    <th scope="row">E-mail</th>
                    <td>{{ $details->email }}</td>
                </tr>
                <tr>
                    <th scope="row">Contact Number</th>
                    <td>{{$details->contact_mobile}}</td>
                </tr>
                <tr>
                    <th scope="row">Delivery Address</th>
                    <td>{{$details->complete_address() }}</td>
                    <td>
                        <input type="hidden" value="{{$details->id}}" name="customer_id">
                        <input type="hidden" value="{{$details->name}}" name="customer_name">
                        <input type="hidden" value="{{$details->contact_mobile}}" name="customer_mobile">
                        <input type="hidden" value="{{$details->complete_address() }}" name="customer_address" id="customer_address">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Contact Person</th>
                    <td colspan="2"><input type="text" name="ex_contact_person" class="form-control" id="ex_contact_person"></td>
                </tr>
            </tbody>

        </table>
    </div>
</div>