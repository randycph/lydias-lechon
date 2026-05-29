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

    $subtotal = (float) (
        $h->email_gross_amount ??
        $h->gross_amount ??
        ($h->items ? $h->items->sum('gross_amount') : 0)
    );

    $deliveryFee = (
        (float) ($h->email_delivery_fee_amount ?? $h->delivery_fee_amount ?? 0) > 0 &&
        $h->delivery_type === 'Door to door delivery'
    ) ? (float) ($h->email_delivery_fee_amount ?? $h->delivery_fee_amount) : 0;

    $couponRows = collect();

    if (!empty($h->applied_coupons) && count($h->applied_coupons) > 0) {
        $couponRows = collect($h->applied_coupons);
    } elseif (!empty($h->couponUsed) && count($h->couponUsed) > 0) {
        $couponRows = collect($h->couponUsed);
    } else {
        $couponRows = \App\EcommerceModel\CouponCart::where('sales_header_id', $h->id)
            ->where('status', 1)
            ->where('discount_used', '>', 0)
            ->select('coupon_id', 'coupon_code', 'discount_used')
            ->get();
    }

    $discountAmount = (float) (
        $h->email_discount_amount ??
        $h->discount_amount ??
        0
    );

    if ($discountAmount <= 0 && $couponRows->count() > 0) {
        $discountAmount = (float) $couponRows->sum('discount_used');
    }

    $grandTotal = (float) (
        $h->email_net_amount ??
        $h->net_amount ??
        max(0, ($subtotal + $deliveryFee) - $discountAmount)
    );
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

@php
    $address = is_array($row) ? ($row[0] ?? null) : $row;
    if (!$address) continue;
@endphp

### Address {{ $k + 1 }}

- **Address:** {{ $address->address ?? '' }}  
- **Contact Person:** {{ $address->contact_person ?? '' }}  
- **Contact Number:** {{ $address->contact_tel ?? '' }}  
- **Delivery Fee:** ₱{{ number_format((float) ($address->delivery_fee ?? 0), 2) }}  
- **Location:** {{ $address->location ?? '' }}  
- **Delivery Date and Time:** {{ date('F d, Y g:i A', strtotime(($address->delivery_date ?? '') . ' ' . ($address->delivery_time ?? ''))) }}  

**Orders:**

@if (!empty($address->products))
@php
    $products = is_string($address->products) ? json_decode($address->products) : $address->products;
@endphp

@if (is_iterable($products))
@foreach ($products as $product)

@php
    $prod = \App\Models\Product::find($product->product_id ?? null);

    $productName = $product->product->name
        ?? $prod->name
        ?? 'Unknown Product';

    $qty = (float) ($product->qty ?? 0);

    $price = (float) ($prod->price ?? $product->price ?? 0);

    if (!empty($product->paella)) {
        $price += (float) ($prod->paella_price ?? $product->paella_price ?? 0);
    }

    $lineTotal = $price * $qty;
@endphp

- {{ $productName }} x {{ number_format($qty, 0) }} - ₱{{ number_format($lineTotal, 2) }}

@endforeach
@endif
@endif

@if (!empty($address->note))
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
@php
    $itemPrice = (float) ($details->paella_price ?? 0) + (float) ($details->price ?? 0);
    $itemTotal = (float) ($details->gross_amount ?? 0);
@endphp
| {{ $details->product->code ?? '' }} | {!! highlightPaella($details?->product_name) !!} | {{ number_format($details->qty, 0) }} | {{ number_format($itemPrice, 2) }} | {{ number_format($itemTotal, 2) }} |
@empty
| *No items found* |  |  |  |  |
@endforelse
| **Subtotal** |  |  |  | **{{ number_format($subtotal, 2) }}** |
@if($deliveryFee > 0)
| **Delivery Fee** |  |  |  | **{{ number_format($deliveryFee, 2) }}** |
@endif
@if($couponRows && count($couponRows) > 0)
@foreach($couponRows as $coupon)
@php
    $isGiftCertificate = empty($coupon->coupon_id);
    $discountLabel = $isGiftCertificate ? 'Gift Certificate' : 'Coupon Discount';
@endphp
| **{{ $discountLabel }}**<br><small>{{ $coupon->coupon_code ?? 'N/A' }}</small> |  |  |  | **-{{ number_format((float) ($coupon->discount_used ?? 0), 2) }}** |
@endforeach
@elseif($discountAmount > 0)
| **Discount** |  |  |  | **-{{ number_format($discountAmount, 2) }}** |
@endif
| **Grand Total** |  |  |  | **{{ number_format($grandTotal, 2) }}** |

<br><br>
Thanks,  
{{ config('app.name') }}
@endcomponent
