
<table style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
    <tr>
        <td align="center"><img src="{{ asset('images/lydias1965.png') }}" height="100" alt=""></td>
    </tr>
    <tr>
        <td align="center" style="font-size:18px;font-weight:bold;">Delivery Receipt</td>
    </tr>
    <tr>
        <td align="center">TELEPHONE NOS: 939-1221/851-2987 to 88</td>
    </tr>
    <tr>
        <td align="center">REFERENCE #: {{$rs->order_number}}</td>
    </tr>
</table>
<table width="100%">
    <tr>
        <td valign="top">
            <table>
                <tr>
                    <td>Customer Name:</td>
                    <td>{{$rs->customer_name}}</td>
                </tr>
                <tr>
                    <td>Company Name:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td>{{$rs->customer_delivery_address}}</td>
                </tr>
                <tr>
                    <td>Contact (Tel/Mobile):</td>
                    <td>{{$rs->customer_contact_number}}</td>
                </tr>
            </table>
        </td>
        <td valign="top">
            <table>
                <tr>
                    <td>Date:</td>
                    <td>{{date('F d, Y',strtotime($rs->created_at))}}</td>
                </tr>
                <tr>
                    <td>Time:</td>
                    <td>{{date('H:i A',strtotime($rs->update_at))}}</td>
                </tr>
                <tr>
                    <td>Payment Status:</td>
                    <td>Fully Paid</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br><br><br>
<table style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
    <thead>
        <tr>
            <td>Item</td>
            <td>Size</td>
            <td>Qty</td>
            <td>Amount</td>
            <td>Total</td>

        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @forelse($rs->items as $r)
        @php
            $total += ($r->net_amount * $r->qty);
        @endphp
        <tr>
            <td>{{$r->product_name}}</td>
            <td>{{$r->size}}</td>
            <td>{{number_format($r->qty,2)}}</td>
            <td>{{number_format($r->net_amount,2)}}</td>
            <td>{{number_format(($r->net_amount * $r->qty),2)}}</td>
            
        </tr>
        @empty
        @endforelse
        <tr>
            <td colspan="5"><hr></td>
        </tr>
        <tr>
            <td>Total</td>
            <td colspan="4" align="right">{{number_format($total,2)}}</td>
        </tr>
    </tbody>

</table>

<br><br>
<table width="100%">
    <tr>
        <td align="center" colspan="3">RECEIVED THE ABOVE IN GOOD ORDER AND CONDITION<br><br><br><br></td>
    </tr>
    <tr>
        <td>Print Name</td>
        <td>Signature</td>
        <td>Date Time</td>
    </tr>
</table>



