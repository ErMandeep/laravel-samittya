@component('mail::message')
#Hello {{$content['email']}}

Congratulations you have received this course gift from {{auth()->user()->name}} 
@if($content['newuser'])

Your login Details:

#Id: {{$content['email']}}
#Password: samitya123
@endif

Happy Shopping.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
