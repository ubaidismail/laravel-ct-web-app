<div>
    {{-- img --}}
    <div style="text-align: center;"><img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100px; height: auto;"></div>

    {{-- content --}}
    <p>Hi {{ $user->name }},</p>
    <p>Congratulations! You have successfully registered!</p>
    <p>Thank you for being a part of us!</p>
    <p>Best regards,</p>
    <p>The {{ config('app.name') }} Team</p>
</div>