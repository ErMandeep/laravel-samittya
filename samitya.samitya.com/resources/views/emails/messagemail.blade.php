@component('mail::message')

#Hello {{$content['user_data']->name}}

@if($content['msg_type'] == 'text')


	{{auth()->user()->name}} send you a Message 

	Message: {{ $content['msg'] }}

@elseif($content['msg_type'] == 'audio')
	You Received audio Message From {{auth()->user()->name}} 

@else
	You Received a new Message From {{auth()->user()->name}}

@endif


Thanks,<br>
{{ config('app.name') }}
@endcomponent
