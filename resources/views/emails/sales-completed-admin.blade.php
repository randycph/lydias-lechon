@component('mail::message')
![Lydia's Lechon Logo]({{ asset('images/lydias-lechon-logo-small.jpg') }})

# A new order has been placed.

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

---

**Admin View:**  
[{{ env('APP_URL') }}/view/admin/{{ $h->order_number }}]({{ env('APP_URL') }}/view/admin/{{ $h->order_number }})

---

@php
    $appliedCoupons = collect($h->applied_coupons ?? []);

    $couponRows = $appliedCoupons->map(function ($coupon) {
        $reward = $coupon->reward ?? $coupon->coupon?->reward ?? null;

        $rewardLabel = match ($reward) {
            'free-shipping-optn' => 'Free Shipping',
            'discount-amount-optn' => 'Amount Discount',
            'discount-percentage-optn' => 'Percentage Discount',
            'free-product-optn' => 'Free Product',
            default => 'Coupon',
        };

        return (object) [
            'code' => $coupon->coupon_code ?? $coupon->coupon?->coupon_code ?? null,
            'name' => $coupon->name ?? $coupon->coupon?->name ?? 'Coupon',
            'reward_label' => $rewardLabel,
            'discount_used' => abs((float) ($coupon->discount_used ?? 0)),
        ];
    })->filter(function ($coupon) {
        return $coupon->discount_used > 0;
    })->values();

    $couponDiscount = $couponRows->sum('discount_used');

    $subtotal = (float) ($h->gross_amount ?? 0);

    $deliveryFee = (
        (float) ($h->delivery_fee_amount ?? 0) > 0 &&
        $h->delivery_type == 'Door to door delivery'
    ) ? (float) $h->delivery_fee_amount : 0;

    // FINAL EMAIL TOTAL
    $emailTotal = max(0, ($subtotal + $deliveryFee) - $couponDiscount);

    $isFreeItem = function ($item) {
        return (float) ($item->gross_amount ?? 0) <= 0;
    };

    $itemPrice = function ($item) use ($isFreeItem) {
        if ($isFreeItem($item)) {
            return 0;
        }

        return (float) ($item->paella_price ?? 0) + (float) ($item->price ?? 0);
    };

    $itemTotal = function ($item) use ($isFreeItem) {
        if ($isFreeItem($item)) {
            return 0;
        }

        return (float) ($item->gross_amount ?? 0);
    };
@endphp

@if(count($h->deliveryAddress ?? []) > 0)

### Order Items

| Code | Product | No of Pax | Qty | Price | Total |
|------|---------|-----------|-----|-------|-------|
@foreach($h->items as $details)
| {{ $details?->product?->code }} | {!! highlightPaella($details?->product_name) !!} | {{ $details->no_of_pax }} | {{ number_format($details->qty, 0) }} | {{ number_format($itemPrice($details), 2) }} | {{ number_format($itemTotal($details), 2) }} |
@endforeach

@if($subtotal > 0)
| | | | | Subtotal | {{ number_format($subtotal, 2) }} |
@endif

@if($couponRows->count() > 0)
@foreach($couponRows as $coupon)
| | | | | {{ $coupon->name }} ({{ $coupon->reward_label }}) | -{{ number_format($coupon->discount_used, 2) }} |
@endforeach
@endif

@if($deliveryFee > 0)
| | | | | Delivery Fee | {{ number_format($deliveryFee, 2) }} |
@endif

| | | | | **Total** | **{{ number_format($emailTotal, 2) }}** |

---

### Delivery Addresses

| Contact Person | Contact Number | Delivery Date | Address |
|----------------|----------------|---------------|---------|
@foreach($h->deliveryAddress as $address)
| {{ $address->contact_person }} | {{ $address->contact_tel }} | {{ $address->delivery_date }} | {{ $address->address }} |
@endforeach

@else

### Order Items

| Code | Product | No of Pax | Date Needed | Qty | Price | Total |
|------|---------|-----------|-------------|-----|-------|-------|
@foreach($h->items as $details)
| {{ $details?->product?->code }} | {!! highlightPaella($details?->product_name) !!} | {{ $details->no_of_pax }} | {{ date('F d, Y H:i A', strtotime($details->delivery_date)) }} | {{ number_format($details->qty, 0) }} | {{ number_format($itemPrice($details), 2) }} | {{ number_format($itemTotal($details), 2) }} |
@endforeach

@if($subtotal > 0)
| | | | | | Subtotal | {{ number_format($subtotal, 2) }} |
@endif

@if($couponRows->count() > 0)
@foreach($couponRows as $coupon)
| | | | | | {{ $coupon->name }} ({{ $coupon->reward_label }}) | -{{ number_format($coupon->discount_used, 2) }} |
@endforeach
@endif
@if($deliveryFee > 0)
| | | | | | Delivery Fee | {{ number_format($deliveryFee, 2) }} |
@endif

| | | | | | **Total** | **{{ number_format($emailTotal, 2) }}** |

@endif

---

<i>This is a system generated email, no reply is required.</i>

&copy; {{ env('APP_URL') }} 2026

@endcomponent