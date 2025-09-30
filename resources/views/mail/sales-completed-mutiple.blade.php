@component('mail::message')
![Lydia's Lechon Logo](https://lydias-lechon.com/images/lydias-lechon-logo-small.jpg)

# Thank you for ordering from us.

**Order number:** {{ $h->order_number }}  
**Order date:** {{ date('F d, Y H:i A', strtotime($h->created_at)) }}  
**Customer:** {{ $h->customer_name }}  
**Contact No:** {{ $h->customer_contact_number }}

@if($h->customer_name !== $h->contact_person)
**Contact Person:** {{ $h->contact_person }}
@endif

**Email:** {{ $h->email }}  
**{{ $h->delivery_type }}:** {{ $h->customer_delivery_adress }}  
**Note:** {{ $h->instruction }}

@component('mail::button', ['url' => route('confirmation', ['id' => $h->HashOrderNumber])])
Click here to view and manage this order
@endcomponent

@php
    $salesHeaders = \App\EcommerceModel\SalesHeader::where('parent_sales_header_id', $h->id)->get();
    $addresses = [];

    foreach ($salesHeaders as $header) {
        $address = json_decode($header->deliveryAddress);
        if ($address) {
            $addresses[] = $address;
        }
    }
@endphp

@component('mail::panel')
@if ($addresses && count($addresses) > 0)
@else
@if ($h?->items && $h->items->count() > 0)
@php ($items = $h->items->first()) @endphp
**Scheduled Date and Time:** {{ \Carbon\Carbon::parse($items->delivery_date)->format('F d, Y g:i A') }}
@endif
@endif

@if ($addresses && count($addresses) > 0)
---

@foreach ($addresses as $k => $row)

@php $address = is_array($row) ? ($row[0] ?? null) : $row;
if (!$address) continue;
@endphp
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
**Delivery Address:** {{ $h->customer_delivery_address ?? $h->customer_address }}  
@endif
@endcomponent

### Order Details

| Code | Product | Qty | Price | Total |
|:---- |:--------| ---:| ----:| ----:|
@forelse($h->items as $details)
| {{ $details->product->code }} | {!! highlightPaella($details?->product_name) !!} | {{ number_format($details->qty, 0) }} | {{ number_format($details->paella_price + $details->price, 2) }} | {{ number_format($details->gross_amount, 2) }} |
@empty
| *No items found* |  |  |  |  |
@endforelse
| **Subtotal** |  |  |  | **{{ number_format($h->items->sum('gross_amount'), 2) }}** |
@if($h->delivery_fee_amount > 0 && $h->delivery_type === 'Door to door delivery')
| **Delivery Fee** |  |  |  | **{{ number_format($h->delivery_fee_amount, 2) }}** |
@endif
| **Total** |  |  |  | **{{ number_format($h->items->sum('gross_amount') + ($h->delivery_fee_amount > 0 && $h->delivery_type === 'Door to door delivery' ? $h->delivery_fee_amount : 0), 2) }}** |


<br><br>
Thanks,  
{{ config('app.name') }}
@endcomponent