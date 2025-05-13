@component('mail::message')
# Order Cancelled

Hi {{ $order->customer_name }},

Your order (Order #{{ $order->order_number }}) has been **automatically cancelled** because we did not receive payment within the required timeframe.

If this was a mistake or you still wish to place an order, please visit our site to reorder.

@component('mail::button', ['url' => url('/')])
Reorder Now
@endcomponent

We're here to help if you have any questions.

Thank you,  
{{ config('app.name') }}
@endcomponent
