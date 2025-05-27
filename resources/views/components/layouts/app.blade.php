<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ env('APP_NAME') }} | {{ $title ?? 'Page Title' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    (APP)
    {{ $slot }}
</body>
</html>
