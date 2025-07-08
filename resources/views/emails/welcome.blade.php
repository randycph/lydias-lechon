@component('mail::message')
# Welcome, {{ $user->name }}!

We're excited to have you on board.

@component('mail::button', ['url' => route('home')])
Visit Our Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
