<div>
    <div class="min-h-screen p-4">
        <ul class="menu menu-vertical lg:menu-horizontal bg-base-100 rounded-box w-full mb-6 gap-2">
            <li><a class="{{ !Route::is('specialist.profile.personal-details') ?: 'menu-active'}}" href="{{ route('specialist.profile.personal-details') }}">Dados pessoais</a></li>
            <li><a class="{{ !Route::is('specialist.profile.professional-details') ?: 'menu-active'}}" href="{{ route('specialist.profile.professional-details') }}">Dados profissionais</a></li>
        </ul>

        <div class="card">
            <div class="card-body">
                <div class="space-y-3 mb-8">
                    <x-title>Dados profissionais</x-title>
                    <x-subtitle>Veja os detalhes do perfil profissional.</x-subtitle>
                </div>
            </div>
        </div>
    </div>
</div>
