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
        <div class="w-full h-full max-w-lg px-5 py-10">
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
