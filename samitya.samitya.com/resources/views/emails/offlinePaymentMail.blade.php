@component('mail::message')
#Hello {{ $content['name'] }}


<a href="{{ route('offline.login') }}"> Click Here </a> to make payment for {{ $content['month'] }} month for your {{ $content['category'] }} at {{ config('app.name') }}
course.





Thanks,<br>
{{ config('app.name') }}
@endcomponent
