<!DOCTYPE html>
<html class="min-h-dvh overscroll-none font-sans antialiased" lang="zh-cmn-Hans">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>{{ $title ?? 'Dali AI' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('scripts')

</head>

<body>
    {{ $slot }}
    @livewire('wire-elements-modal')
</body>

</html>
