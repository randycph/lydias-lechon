@component('mail::message')
# Reminder: Your Order is Unpaid

Hi {{ $order->customer_name }},

We noticed your order (Order #{{ $order->order_number }}) placed on {{ $order->created_at->format('F j, Y') }} is still marked as unpaid.

Please complete your payment to ensure we can process and deliver your order on time.

@component('mail::button', ['url' => url('/order-history')])
Pay Now
@endcomponent

If payment is not completed within **5 days**, your order will be **automatically canceled**.

If you have **already paid** for this order and believe this notice was sent in error, please contact **Lydia's Lechon Head Office** at:

- **0917 538 0304 (Globe)**
- **0918 967 5213 (Smart)**

Thanks,  
{{ config('app.name') }}
@endcomponent
