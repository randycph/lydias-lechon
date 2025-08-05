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
**Instruction:** {{ $h->instruction }}

@component('mail::button', ['url' => route('confirmation', ['id' => $h->HashOrderNumber])])
Click here to view and manage this order
@endcomponent

---

**Admin View:**  
[{{ env('APP_URL') }}/view/admin/{{ $h->order_number }}]({{ env('APP_URL') }}/view/admin/{{ $h->order_number }})

---

@if(count($h->deliveryAddress ?? []) > 0)

### Order Items

| Code | Product | No of Pax | Qty | Price | Total |
|------|---------|-----------|-----|--------|--------|
@foreach($h->items as $details)
| {{ $details->product->code }} | {{ $details->product_name }} @if($details->paella_price > 0) Boneless with Paella @endif | {{ $details->no_of_pax }} | {{ number_format($details->qty, 0) }} | {{ number_format($details->paella_price + $details->price, 2) }} | {{ number_format($details->gross_amount, 2) }} |
@endforeach

@if($h->delivery_fee_amount > 0 && $h->delivery_type == 'Door to door delivery')
| | | | | **Delivery Fee** | {{ number_format($h->delivery_fee_amount, 2) }} |
@endif

@if($h->gross_amount > 0)
| | | | | **Total** | **{{ number_format($h->gross_amount, 2) }}** |
@endif

---

### Delivery Addresses

| Contact Person | Contact Number | Delivery Date | Address |
|----------------|----------------|----------------|----------|
@foreach($h->deliveryAddress as $address)
| {{ $address->contact_person }} | {{ $address->contact_tel }} | {{ $address->delivery_date }} | {{ $address->address }} |
@endforeach

@else

### Order Items

| Code | Product | No of Pax | Date Needed | Qty | Price | Total |
|------|---------|-----------|-------------|-----|--------|--------|
@foreach($h->items as $details)
| {{ $details->product->code }} | {{ $details->product_name }} @if($details->paella_price > 0) Boneless with Paella @endif | {{ $details->no_of_pax }} | {{ date('F d, Y H:i A', strtotime($details->delivery_date)) }} | {{ number_format($details->qty, 0) }} | {{ number_format($details->paella_price + $details->price, 2) }} | {{ number_format($details->gross_amount, 2) }} |
@endforeach

@if($h->delivery_fee_amount > 0 && $h->delivery_type == 'Door to door delivery')
| | | | | **Delivery Fee** | {{ number_format($h->delivery_fee_amount, 2) }} |
@endif

@if($h->gross_amount > 0)
| | | | | **Total** | **{{ number_format($h->gross_amount, 2) }}** |
@endif

@endif

---

<i>This is a system generated email, no reply is required.</i>

&copy; {{ env('APP_URL') }} 2020

@endcomponent
