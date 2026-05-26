@php
    $grandTotal = 0;
@endphp

@component('mail::message')
# Don't Forget Your Cart!

You still have items waiting in your cart.

Please complete your order <strong>at least 24 hours before your preferred delivery or pick-up-time.</strong> Otherwise, the system will automatically close your pending order.

Here's a quick summary of your cart:

@component('mail::table')
| Product | Quantity | Price | Total |
|:--------|:--------:|------:|------:|
@foreach ($cartItems as $item)
    @php
        $price = $item->price ?? 0;
        $total = $price * $item->qty;
        $grandTotal += $total;
    @endphp
| {{ $item->product->name ?? 'Unknown Product' }} | {{ $item->qty }} | {{ format_price($price) }} | {{ format_price($total) }} |
@endforeach
@endcomponent

@php
    $productIds = implode(',', $cartItems->pluck('product_id')->toArray());
@endphp

## Grand Total: **{{ format_price($grandTotal) }}**

@component('mail::button', ['url' => route('my-cart') . '?product_ids=' . $productIds])
Return to Shop
@endcomponent

Thanks for shopping with us!  
{{ config('app.name') }}
@endcomponent
