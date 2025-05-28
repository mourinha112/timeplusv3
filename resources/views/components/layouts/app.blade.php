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
<body class="bg-gray-100 w-screen h-full">

    <div class="w-full h-16 bg-yellow-500 flex justify-center">
        <div class="w-3/5 h-16 flex justify-between items-center">
            <div>
                <x-logotipo />
            </div>
            <div class="flex gap-3">
                <a href="{{ route('user.dashboard.show') }}" class="{{ !Route::is('user.dashboard.show') ?: 'bg-yellow-600' }}">Início</a>
                <a href="#">Especialistas</a>
                <a href="#">Sessões</a>
                <livewire:user.auth.logout />
            </div>
        </div>
    </div>

    <div class="py-5 w-full flex justify-center">
        <div class="w-3/5">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
