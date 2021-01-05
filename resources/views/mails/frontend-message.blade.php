@component('mail::message')
Name: {{ $name }},<br>
Email: {{ $email }},<br>
Phone: {{ $phone }},<br>

{{ $message }} <br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
