@component('mail::message')
# Introduction

The body of your message.
{{$user->email}}
@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
