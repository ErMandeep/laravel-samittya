@lang('strings.emails.contact.email_body_title')

@lang('validation.attributes.frontend.name'): {{ $request->name }}
@lang('validation.attributes.frontend.email'): {{ $request->email }}
Subject: {{ ($request->subject == "") ? "N/A" : $request->subject }}
@lang('validation.attributes.frontend.message'): {{ $request->message }}
