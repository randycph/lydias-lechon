
@if(count($rs) > 0)
    <h5>{{ $user->name }}'s Purchases</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Order Date</th>
                    <th>Delivery Date</th>
                    <th>Order #</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rs as $row)
                    <tr>
                        <td>{{ $row->product_name }}</td>
                        <td>₱{{ number_format($row->price, 2) }}</td>
                        <td>{{ (int)$row->qty }}</td>
                        <td>
                            {{ optional($row->header)->created_at 
                                ? \Carbon\Carbon::parse($row->header->created_at)->format('Y-m-d') 
                                : '' }}
                        </td>
                        <td>
                            @if ($row->header->delivery_status <> 'Open Date')
                                {{ \Carbon\Carbon::parse($row->delivery_date)->format('Y-m-d') }}
                            @else
                                Open Date
                            @endif
                        </td>
                        <td>
                            <a target="_blank" 
                            href="{{ route('sales.print', base64_encode($row->header->id ?? 0)) }}">
                            {{ $row->header->order_number ?? '' }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <h5>{{ $user->name }}'s Purchases</h5>
    <div class="text-center text-muted">
        No purchases found.
    </div>
@endif