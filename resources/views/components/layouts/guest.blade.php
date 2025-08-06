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

{{-- <body class="min-h-screen flex flex-col bg-cover bg-center overflow-x-hidden relative" style="background-image: url('https://www.eniac.edu.br/hs-fs/hubfs/Design%20sem%20nome%20-%202023-10-19T165649.301.jpg?width=1200&height=600&name=Design%20sem%20nome%20-%202023-10-19T165649.301.jpg')">
    <div class="absolute inset-0 bg-black opacity-70"></div>

    <div class="min-h-screen flex flex-col justify-between items-center relative z-10">
        <div class="w-full max-w-lg px-5 py-10 grow"> --}}

<body class="min-h-screen flex flex-col bg-gray-100 overflow-x-hidden">
    <div class="min-h-screen flex flex-col justify-between items-center">
        <div class="w-full max-w-lg px-5 py-10 grow">
            <div class="flex justify-center">
                <x-logotipo class="mb-8 w-50" />
            </div>

            {{ $slot }}
        </div>

        <div class="p-6 text-center flex flex-col gap-3 text-gray-500">
            <small>
                <a class="link link-info" onclick="terms_contract_modal.showModal()">Termos e Condições</a>
                e
                <a class="link link-info" onclick="privacy_policy_modal.showModal()">Politica de Privacidade</a>.
            </small>

            {{-- Modal::Termos e Condições --}}
            <dialog id="terms_contract_modal" class="modal">
                <div class="modal-box w-11/12 max-w-5xl">
                    <h3 class="text-lg font-bold text-black">Termos e Condições</h3>
                    <p class="py-4">
                        Termos e Condições serão adicionados em breve. Por enquanto, você pode utilizar o sistema normalmente, mas recomendamos que leia os termos assim que estiverem disponíveis.
                    </p>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>

            {{-- Modal::Politica de Privacidade --}}
            <dialog id="privacy_policy_modal" class="modal">
                <div class="modal-box w-11/12 max-w-5xl">
                    <h3 class="text-lg font-bold text-black">Políticas de Privacidade</h3>
                    <p class="py-4">
                        Políticas de Privacidade serão adicionadas em breve. Por enquanto, você pode utilizar o sistema normalmente, mas recomendamos que leia os termos assim que estiverem disponíveis.
                    </p>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>

            <small>
                {{ env('APP_NAME') }} © 2025
            </small>
        </div>
    </div>
</body>
</html>
