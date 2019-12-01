@component('mail::message')

Hello {{ $content['name'] }}

We have received your  payment for {{ $content['month'] }} Month.
Please Find the Attached Invoice.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
