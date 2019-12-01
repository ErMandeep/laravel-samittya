@component('mail::message')
#Hello {{ $content['name'] }}

Your new Account has been created. Welcome to {{ config('app.name') }}.

Please Log in to your account using your Student number  {{ $content['student_id'] }}
to make payment 

<a href="{{ route('offline.login') }}"> Click Here to login </a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
