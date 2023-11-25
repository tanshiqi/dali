<!DOCTYPE html>
<html class="h-full bg-gray-700 font-sans antialiased" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Dali' }}</title>
    @vite('resources/css/app.css')
</head>

<body class="">
    {{ $slot }}
</body>

</html>