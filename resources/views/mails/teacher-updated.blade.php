@component('mail::message')
# Hello {{ $name }},

{{ $message }} <br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
