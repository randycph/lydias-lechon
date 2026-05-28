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
    /*
    |--------------------------------------------------------------------------
    | COUPON + GIFT CERTIFICATE EMAIL SUMMARY
    |--------------------------------------------------------------------------
    | Fixed:
    | - Coupon discount now reads multiple possible fields, not only discount_used.
    | - Free shipping uses delivery fee when discount_used is missing.
    | - Discount is deducted from gross_amount correctly.
    | - Gift Certificate is shown separately as payment/credit.
    */

    $rawAppliedCoupons = collect($h->applied_coupons ?? []);

    $subtotal = (float) ($h->gross_amount ?? 0);

    $deliveryFee = (
        (float) ($h->delivery_fee_amount ?? 0) > 0 &&
        $h->delivery_type == 'Door to door delivery'
    ) ? (float) $h->delivery_fee_amount : 0;

    $isGiftCertificateLike = function ($row) {
        $text = strtolower(implode(' ', array_filter([
            $row->type ?? null,
            $row->discount_type ?? null,
            $row->payment_type ?? null,
            $row->reward ?? null,
            $row->coupon_code ?? null,
            $row->code ?? null,
            $row->name ?? null,
            $row->description ?? null,
            $row->coupon?->coupon_code ?? null,
            $row->coupon?->name ?? null,
            $row->coupon?->reward ?? null,
        ])));

        return str_contains($text, 'gift cert')
            || str_contains($text, 'gift certificate')
            || preg_match('/(^|\s|-)gc($|\s|-)/', $text);
    };

    $resolveCouponDiscount = function ($coupon) use ($deliveryFee) {
        $reward = $coupon->reward ?? $coupon->coupon?->reward ?? null;

        /*
        |--------------------------------------------------------------------------
        | 1. Already computed/saved discount amount
        |--------------------------------------------------------------------------
        */

        $savedDiscount = (float) (
            $coupon->discount_used
            ?? $coupon->discount_amount
            ?? $coupon->discount
            ?? $coupon->coupon_discount
            ?? $coupon->amount_used
            ?? $coupon->used_amount
            ?? 0
        );

        if ($savedDiscount > 0) {
            return abs($savedDiscount);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Free shipping coupon
        |--------------------------------------------------------------------------
        | If no discount_used was saved, the discount should be the delivery fee
        | or the location-specific free shipping amount.
        */

        if ($reward === 'free-shipping-optn') {
            $locationDiscount = (float) (
                $coupon->location_discount_amount
                ?? $coupon->coupon?->location_discount_amount
                ?? 0
            );

            return abs($locationDiscount > 0 ? $locationDiscount : $deliveryFee);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Fixed amount coupon
        |--------------------------------------------------------------------------
        */

        if ($reward === 'discount-amount-optn') {
            return abs((float) (
                $coupon->amount
                ?? $coupon->coupon?->amount
                ?? 0
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Percentage coupon
        |--------------------------------------------------------------------------
        | This fallback is only used if discount_used/discount_amount was missing.
        */

        if ($reward === 'discount-percentage-optn') {
            $percentage = (float) (
                $coupon->percentage
                ?? $coupon->coupon?->percentage
                ?? 0
            );

            $baseAmount = (float) (
                $coupon->base_amount
                ?? $coupon->eligible_amount
                ?? $coupon->cart_subtotal
                ?? $coupon->order_amount
                ?? 0
            );

            if ($percentage > 0 && $baseAmount > 0) {
                return abs($baseAmount * ($percentage / 100));
            }
        }

        return 0;
    };

    $couponRows = $rawAppliedCoupons
        ->filter(function ($coupon) use ($isGiftCertificateLike) {
            return !$isGiftCertificateLike($coupon);
        })
        ->map(function ($coupon) use ($resolveCouponDiscount) {
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
                'name' => $coupon->name ?? $coupon->coupon?->name ?? $coupon->coupon_code ?? 'Coupon',
                'reward_label' => $rewardLabel,
                'reward' => $reward,
                'discount_used' => $resolveCouponDiscount($coupon),
            ];
        })
        ->filter(function ($coupon) {
            return $coupon->discount_used > 0;
        })
        ->values();

    $couponDiscount = (float) $couponRows->sum('discount_used');

    /*
    |--------------------------------------------------------------------------
    | GIFT CERTIFICATES
    |--------------------------------------------------------------------------
    */

    $giftCertificates = collect();

    try {
        if (isset($h->giftCertificates)) {
            $giftCertificates = collect($h->giftCertificates);
        } elseif (isset($h->gift_certificates)) {
            $giftCertificates = collect($h->gift_certificates);
        } elseif (!empty($h->id) && class_exists(\App\EcommerceModel\GiftCertificate::class)) {
            $giftCertificates = \App\EcommerceModel\GiftCertificate::where('sales_header_id', $h->id)->get();
        }
    } catch (\Throwable $e) {
        $giftCertificates = collect();
    }

    $salesPayments = collect($h->payments ?? $h->salesPayments ?? $h->sales_payments ?? []);

    $giftCertificatePayments = $salesPayments
        ->filter(function ($payment) {
            $paymentType = strtolower($payment->payment_type ?? $payment->type ?? '');

            return $paymentType === 'gift cert'
                || $paymentType === 'gift certificate'
                || str_contains($paymentType, 'gift cert')
                || preg_match('/(^|\s|-)gc($|\s|-)/', $paymentType);
        })
        ->values();

    $paymentAmount = function ($payment) {
        return abs((float) (
            $payment->amount
            ?? $payment->payment_amount
            ?? $payment->paid_amount
            ?? $payment->value
            ?? 0
        ));
    };

    $certificateAmount = function ($gc) {
        return abs((float) (
            $gc->amount
            ?? $gc->value
            ?? $gc->balance
            ?? $gc->discount_used
            ?? 0
        ));
    };

    $giftCertificateRows = collect();

    foreach ($giftCertificates as $gc) {
        $amount = $certificateAmount($gc);

        if ($amount > 0) {
            $giftCertificateRows->push((object) [
                'code' => $gc->code ?? $gc->serial_number ?? $gc->gc_code ?? 'Gift Certificate',
                'amount' => $amount,
                'source' => 'certificate',
            ]);
        }
    }

    foreach ($giftCertificatePayments as $payment) {
        $amount = $paymentAmount($payment);

        if ($amount > 0) {
            $giftCertificateRows->push((object) [
                'code' => $payment->receipt_number ?? $payment->reference_number ?? $payment->code ?? 'Gift Certificate',
                'amount' => $amount,
                'source' => 'payment',
            ]);
        }
    }

    $giftCertificateRows = $giftCertificateRows
        ->unique(function ($row) {
            return strtolower(trim($row->code)) . '|' . number_format((float) $row->amount, 2, '.', '');
        })
        ->values();

    $giftCertificateTotal = (float) $giftCertificateRows->sum('amount');

    /*
    |--------------------------------------------------------------------------
    | FINAL TOTALS
    |--------------------------------------------------------------------------
    */

    $grossPlusDelivery = $subtotal + $deliveryFee;

    // This is the important corrected formula.
    $orderTotalAfterCoupon = max(0, $grossPlusDelivery - $couponDiscount);

    // GC is payment/credit, not coupon discount.
    $balanceAfterGiftCertificate = max(0, $orderTotalAfterCoupon - $giftCertificateTotal);

    $hasGiftCertificate = $giftCertificateRows->count() > 0;
    $showBalanceAfterGiftCertificate = $hasGiftCertificate && $giftCertificateTotal > 0;

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
| | | | | Gross Amount | {{ number_format($subtotal, 2) }} |
@endif

@if($deliveryFee > 0)
| | | | | Delivery Fee | {{ number_format($deliveryFee, 2) }} |
@endif

@if($couponRows->count() > 0)
@foreach($couponRows as $coupon)
| | | | | Coupon - {{ $coupon->name }} ({{ $coupon->reward_label }}) | -{{ number_format($coupon->discount_used, 2) }} |
@endforeach
@endif

@if($giftCertificateRows->count() > 0)
@foreach($giftCertificateRows as $gc)
| | | | | Gift Certificate {{ $gc->code ? '(' . $gc->code . ')' : '' }} | -{{ number_format($gc->amount, 2) }} |
@endforeach
@endif

| | | | | **Grand Total** | **{{ number_format($hasGiftCertificate ? $balanceAfterGiftCertificate : $orderTotalAfterCoupon, 2) }}** |

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
| | | | | | Gross Amount | {{ number_format($subtotal, 2) }} |
@endif

@if($deliveryFee > 0)
| | | | | | Delivery Fee | {{ number_format($deliveryFee, 2) }} |
@endif

@if($couponRows->count() > 0)
@foreach($couponRows as $coupon)
| | | | | | Coupon - {{ $coupon->name }} ({{ $coupon->reward_label }}) | -{{ number_format($coupon->discount_used, 2) }} |
@endforeach
@endif

@if($giftCertificateRows->count() > 0)
@foreach($giftCertificateRows as $gc)
| | | | | | Gift Certificate {{ $gc->code ? '(' . $gc->code . ')' : '' }} | -{{ number_format($gc->amount, 2) }} |
@endforeach
@endif

| | | | | | **Grand Total** | **{{ number_format($hasGiftCertificate ? $balanceAfterGiftCertificate : $orderTotalAfterCoupon, 2) }}** |

@endif

---

<i>This is a system generated email, no reply is required.</i>

&copy; {{ env('APP_URL') }} 2026

@endcomponent
