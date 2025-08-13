@component('mail::message')
# Delivery Assignment

Hi {{ $driver?->name ?? 'Driver' }},

You have been assigned a new delivery. Please see the details below:

@component('mail::panel')
**Order Number:** {{ $delivery->order_number ?? 'N/A' }}  
**Customer Name:** {{ $delivery->customer_name ?? 'N/A' }}  
**Contact Number:** {{ $delivery->customer_contact_number ?? 'N/A' }}  
@endcomponent

## Delivery Address Details

@component('mail::panel')
**Address:** {{ $addresses->address ?? 'N/A' }}  
**Contact Person:** {{ $addresses->contact_person ?? 'N/A' }}  
**Contact Number:** {{ $addresses->contact_tel ?? 'N/A' }}  
**Delivery Date:** {{ $addresses->delivery_date ?? 'N/A' }}  
**Delivery Time:** {{ $addresses->delivery_time ?? 'N/A' }}  
**Delivery Status:** {{ $addresses->delivery_status ?? 'N/A' }}  
**Location:** {{ $addresses->location ?? 'N/A' }}  
**Branch:** {{ $addresses->branch ?? 'N/A' }}  
**Note:** {{ $addresses->note ?? 'N/A' }}  
@endcomponent

@if(!empty($addresses->products))
## Products in Delivery
@php
    $products = json_decode($addresses->products, true);
@endphp
@foreach($products as $p)
- **Product:** {{ isset($p['paella']) && $p['paella'] ? $p['product']['name'] . ' With Boneless Paella' : $p['product']['name'] }} <br>
  **Qty:** {{ $p['qty'] ?? 'N/A' }} <br>
@endforeach
@endif

Thanks,  
{{ config('app.name') }}
@endcomponent
