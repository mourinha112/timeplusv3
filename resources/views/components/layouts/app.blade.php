<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
<body class="bg-gray-100/40 w-screen h-full">

    <div class="w-full h-16 bg-yellow-500 flex justify-center">
        <div class="w-3/5 h-16 flex justify-between items-center">
            <div>
                <x-logotipo />
            </div>
            <div class="flex gap-3">
                <a href="{{ route('user.dashboard.show') }}" class="{{ !Route::is('user.dashboard.show') ?: 'bg-yellow-600' }}">Início</a>
                <a href="{{ route('user.specialist.index') }}" class="{{ !Route::is('user.specialist.index') ?: 'bg-yellow-600' }}">Especialistas</a>
                <a href="{{ route('user.appointment.index') }}" class="{{ !Route::is('user.appointment.index') ?: 'bg-yellow-600' }}">Sessões</a>
                <livewire:user.auth.logout />
            </div>
        </div>
    </div>

    <div class="py-5 w-full flex justify-center">
        <div class="w-5/6 md:w-3/5">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
