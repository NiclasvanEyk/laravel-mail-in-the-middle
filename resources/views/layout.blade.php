<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>📧 Mails - {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link type="text/css" href="{{ \VanEyk\MITM\Support\Route::asset('css/app.css') }}" rel="stylesheet">

    <script src="{{ \VanEyk\MITM\Support\Route::asset('js/main.js') }}"></script>
</head>
<body>
    @yield('content')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</body>
</html>
