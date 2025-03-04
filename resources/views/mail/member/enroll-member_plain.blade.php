Dear {{ $user->name }},

{{ $sponsor->company_name }} enrolled you at Legande Inc.

Please access the link below to set up your password and complete the activation process of your account.

{{ url('password/reset', $token) }}?email={{ $user->email }}&user=new

Thank you.


Regards,
{{ $setting->company_name }}



{{ $setting->company_name }}
{{ $setting->company_address }}
{{ $setting->tel_no }} | {{ $setting->mobile_no }}

{{ url('/') }}
