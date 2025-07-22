<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ env('APP_NAME') }} | {{ $title ?? 'Page Title' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body class="h-screen w-screen overflow-hidden">
    <div class="w-full h-full flex flex-col justify-between items-center bg-gray-100">
        <div class="w-full h-full max-w-md px-5 py-10">
            <x-logotipo class="mb-8" />

            {{ $slot }}
        </div>

        <div class="p-6 text-center flex flex-col gap-3 text-gray-500">
            <small>
                <x-link class="text-xs">Temos e Condições</x-link>
                e
                <x-link class="text-xs">Politica de Privacidade</x-link>.
            </small>

            <small>
                ©2025 {{ env('APP_NAME') }}
            </small>
        </div>
    </div>
</body>
</html>
