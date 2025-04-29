@php
    $grandTotal = 0;
@endphp

@component('mail::message')
# Don't Forget Your Cart!

You still have items waiting in your cart. Here’s a quick summary:

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

## Grand Total: **{{ format_price($grandTotal) }}**

@component('mail::button', ['url' => url('/')])
Return to Shop
@endcomponent

Thanks for shopping with us!  
{{ config('app.name') }}
@endcomponent
