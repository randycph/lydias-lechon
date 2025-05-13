@component('mail::message')
# Reminder: Your Order is Unpaid

Hi {{ $order->customer_name }},

We noticed your order (Order #{{ $order->order_number }}) placed on {{ $order->created_at->format('F j, Y') }} is still marked as unpaid.

Please complete your payment to ensure we can process and deliver your order on time.

@component('mail::button', ['url' => url('/account/orders')])
Pay Now
@endcomponent

If payment is not completed within 5 days, your order will be automatically canceled.

Thanks,  
{{ config('app.name') }}
@endcomponent
