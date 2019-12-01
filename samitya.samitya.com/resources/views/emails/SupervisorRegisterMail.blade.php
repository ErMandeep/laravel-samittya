@component('mail::message')
#Hello {{ $content['name'] }}

Your Supervisor Account has been created.

Please Log in to your account using below details<br>

Email: {{ $content['email'] }} <br>
Password: {{ $content['password'] }}


<a href="{{ route('frontend.auth.login') }}"> Click Here to login </a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
