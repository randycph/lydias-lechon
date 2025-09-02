@component('mail::message')
# Delivery Assignment

Hi {{ $driver?->name ?? 'Driver' }},

You have been assigned a new delivery. Please see the details below:

@component('mail::panel')
**Order Number:** {{ $delivery->order_number }}  
**Customer Name:** {{ $delivery->customer_name }}  
**Contact Number:** {{ $delivery->customer_contact_number }}  
@if ($delivery?->deliveryAddress && count($delivery->deliveryAddress) > 0)
@else
@if ($delivery?->items && $delivery->items->count() > 0)
@php ($items = $delivery->items->first()) @endphp
**Scheduled Date and Time:** {{ \Carbon\Carbon::parse($items->delivery_date)->format('F d, Y g:i A') }}
@endif
@endif

@if ($delivery?->deliveryAddress && count($delivery->deliveryAddress) > 0)
---

@foreach ($delivery->deliveryAddress as $k => $address)
### Address {{ $k + 1 }}

- **Address:** {{ $address->address }}  
- **Contact Person:** {{ $address->contact_person }}  
- **Contact Number:** {{ $address->contact_tel }}  
- **Delivery Fee:** ₱{{ number_format($address->delivery_fee, 2) }}  
- **Location:** {{ $address->location }}  
- **Delivery Date and Time:** {{ date('F d, Y g:i A', strtotime($address->delivery_date . ' ' . $address->delivery_time)) }}  

**Orders:**
@if ($address->products)
@php
    $products = is_string($address->products) ? json_decode($address->products) : $address->products;
@endphp

@if (is_iterable($products))
@foreach ($products as $product)

@php 
    $price = $product->product->price ?? 0;
    if (!empty($product->paella)) {
        $price += $product->product->paella_price ?? 0;
    }
@endphp

- {{ $product->product->name ?? 'Unknown Product' }} x {{ $product->qty }} - ₱{{ number_format($price, 2) }}
@endforeach
@endif
@endif

@if ($address->note)
**Note:** {{ $address->note }}
@endif

@endforeach

@else
**Delivery Address:** {{ $delivery->customer_delivery_address ?? $delivery->customer_address }}  
@endif
@endcomponent

**Items to Deliver:**
@if ($delivery?->items && $delivery->items->count() > 0)
@component('mail::table')
| Item              | Quantity |
|-------------------|----------|
@foreach($delivery->items as $item)
| {{ $item->product_name }} | {{ number_format($item->qty, 0) }} |
@endforeach
@endcomponent
@endif

Please ensure a safe and timely delivery.

@component('mail::button', ['url' => route('login')])
Manage Deliveries
@endcomponent

Thanks,  
**{{ config('app.name') }}
@endcomponent
