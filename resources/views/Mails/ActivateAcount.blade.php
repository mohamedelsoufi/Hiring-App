@component('mail::message')
# Introduction

The body of your message.

{{$token}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent