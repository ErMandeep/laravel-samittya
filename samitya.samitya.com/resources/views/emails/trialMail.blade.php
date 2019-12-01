@component('mail::message')
#Hello {{$content['user']}}

This is a Reminder E-mail for your Trial Lesson with {{$content['teacher']}}

#Time : {{$content['time']}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
