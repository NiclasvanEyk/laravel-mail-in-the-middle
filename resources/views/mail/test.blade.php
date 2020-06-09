@component('mail::message')
# Test Mail

If you can read this email, the `laravel-mail-in-the-middle` package should work!

@component('mail::button', ['url' => 'https://github.com/niclasvaneyk/laravel-mail-in-the-middle'])
Find out what this package can do
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent