@component('mail::message')
#Hello {{ $content['name'] }}


This is just a reminder that your payment for {{ $content['month'] }} month  is due. You can make payment by login to your Dashboard.


<a href="{{ route('offline.login') }}"> Click Here to login </a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
