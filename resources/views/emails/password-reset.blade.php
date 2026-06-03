@component('mail::message')
{{-- Header --}}
@component('mail::header', ['url' => config('app.url')])
Donation UI
@endcomponent

# Reset your password

Hi {{ $user->name ?? 'there' }},

You recently requested to reset your password for your Donation UI account. Click the button below to reset it.

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Reset password
@endcomponent

This password reset link will expire in {{ $expires }} minutes.

If you did not request a password reset, please ignore this email or contact support if you have concerns.

Thanks,  
**The Donation UI Team**

@component('mail::subcopy')
If you're having trouble clicking the "Reset password" button, copy and paste the URL below into your web browser: {{ $url }}
@endcomponent
@endcomponent
