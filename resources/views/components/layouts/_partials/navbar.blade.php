<header class="bg-base-100 py-3 w-full relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="navbar bg-base-100">

            <div class="navbar-start">
                <a href="#"><x-logotipo /></a>
            </div>

            {{-- <div class="navbar-center">
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-ghost bg-white  lg:hidden">
                        <x-carbon-menu class="w-5 h-5 text-slate-900" />
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-white  rounded-box z-1 mt-3 w-52 p-2 shadow">
                        @foreach ($navItems as $item)
                        <li>
                            <a href="{{ route($item['route-name']) }}" class="header-link flex items-center gap-3 {{ $activeNav === $item['key'] ? 'active text-blue-500' : '' }}">
                                @if (Str::startsWith($item['icon'], 'carbon-'))
                                <x-dynamic-component :component="$item['icon']" class="w-4 h-4 {{ $activeNav === $item['key'] ? 'text-blue-500' : '' }}" />
                                @else
                                <img src="{{ asset('assets/images/' . $item['icon']) }}" alt="{{ $item['label'] }}" class="w-4 h-4 {{ $activeNav === $item['key'] ? 'filter-blue' : '' }}">
                                @endif
                                <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div> --}}

            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1">
                    <li>
                        <a href="{{ route('user.dashboard.show') }}" class="{{ !Route::is('user.dashboard.show') ?: 'border bg-base-200 font-semibold' }}">
                           <x-carbon-home class="w-5 h-5" />
                            Início
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.specialist.index') }}" class="{{ !Route::is('user.specialist.index') ?: 'border bg-base-200 font-semibold' }}">
                           <x-carbon-reminder-medical class="w-5 h-5" />
                            Especialistas
                        </a>
                    </li>
                    <li>
                        <a href="#">
                           <x-carbon-home class="w-5 h-5" />
                            Sessões
                        </a>
                    </li>
                </ul>
            </div>
            {{-- <div class="navbar-end">
                <!-- Notification Dropdown -->
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="header-btn btn btn-ghost btn-circle">
                        <div class="indicator">
                            <x-carbon-notification-filled class="w-6 h-6" />
                            <span class="badge badge-sm indicator-item bg-red-500 text-white border-none">3</span>
                        </div>
                    </div>
                    <div tabindex="0" class="card card-compact dropdown-content bg-white z-10 mt-3 w-80 shadow-lg border border-gray-200 rounded-lg">
                        <div class="card-body p-4">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-lg font-bold text-gray-800">Notificações</span>
                                <span class="text-sm text-gray-500">3 novas</span>
                            </div>

                            <!-- Notification Items -->
                            <div class="space-y-3 max-h-64 overflow-y-auto notifications-scroll">
                                <div class="notification-item flex items-start gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800">Nova sessão agendada</p>
                                        <p class="text-xs text-gray-600">Sua sessão com Dr. João foi confirmada para
                                            amanhã às 14h</p>
                                        <span class="text-xs text-gray-400">2 min atrás</span>
                                    </div>
                                </div>

                                <div class="notification-item flex items-start gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800">Pagamento confirmado</p>
                                        <p class="text-xs text-gray-600">Seu pagamento do plano mensal foi processado
                                            com sucesso</p>
                                        <span class="text-xs text-gray-400">1 hora atrás</span>
                                    </div>
                                </div>

                                <div class="notification-item flex items-start gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800">Lembrete de sessão</p>
                                        <p class="text-xs text-gray-600">Você tem uma sessão agendada em 30 minutos</p>
                                        <span class="text-xs text-gray-400">3 horas atrás</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-actions mt-4">
                                <button class="btn btn-sm btn-ghost w-full text-blue-500 hover:bg-blue-50 border-none">
                                    Ver todas as notificações
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="header-btn btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full flex items-center justify-center">
                            <x-carbon-user-avatar-filled class="w-8 h-8" />
                        </div>
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-white rounded-box z-1 mt-3 w-52 p-2 shadow">
                        <li>
                            <a href="/users/perfil" class="profile-dropdown-item justify-between flex items-center gap-3">
                                <div class="flex items-center gap-3">
                                    <x-carbon-user class="w-4 h-4" />
                                    <span>Perfil</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/users/planos" class="profile-dropdown-item flex items-center gap-3">
                                <x-carbon-layers class="w-4 h-4" />
                                <span>Planos</span>
                            </a>
                        </li>
                        <li>
                            <a href="/users/ajuda" class="profile-dropdown-item flex items-center gap-3">
                                <x-carbon-help class="w-4 h-4" />
                                <span>Ajuda e Suporte</span>
                            </a>
                        </li>
                        <li>
                            <a href="/users/logout" class="profile-dropdown-item flex items-center gap-3">
                                <x-carbon-logout class="w-4 h-4" />
                                <span>Sair</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Gradient Border Bottom -->
    <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-[#39D5F8] via-[#53ACFD] to-[#7464FF]">

</header>
