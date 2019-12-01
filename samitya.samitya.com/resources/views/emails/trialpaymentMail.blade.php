@component('mail::message')
#Hello {{auth()->user()->name}}



Thank You for taking Trial Lesson for {{ $content['courese_name'] }}

Happy Shopping.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
