<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/x-icon">
    <title>Libgen</title>
    @vite('resources/css/app.css')
</head>

<body>
    <x-client.header />

    <main class="min-h-[calc(100vh-4rem-3.5rem)]">
        @yield('main')
    </main>

    <x-client.footer />
</body>

</html>