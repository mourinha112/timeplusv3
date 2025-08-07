<header class="bg-base-100 py-3 w-full relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="navbar bg-base-100">

            <div class="navbar-start hidden sm:block">
                <a href="#">
                    <x-logotipo /></a>
            </div>
            @if($guard === 'user')
            <div class="navbar-start sm:hidden">
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-ghost">
                        <x-carbon-menu class="w-7 h-7 text-slate-900" />
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-white rounded-box z-1 mt-3 w-52 p-2 shadow">
                        <li>
                            <a href="{{ route('user.dashboard.show') }}" class="{{ !Route::is('user.dashboard.show') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-home class="w-4 h-4" />
                                Início
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.specialist.index') }}" class="{{ !Route::is('user.specialist.index') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-reminder-medical class="w-4 h-4" />
                                Especialistas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.appointment.index') }}" class="{{ !Route::is('user.appointment.index') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-home class="w-4 h-4" />
                                Sessões
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="navbar-center hidden sm:flex">
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
                        <a href="{{ route('user.appointment.index') }}" class="{{ !Route::is('user.appointment.index') ?: 'border bg-base-200 font-semibold' }}">
                            <x-carbon-home class="w-5 h-5" />
                            Sessões
                        </a>
                    </li>
                </ul>
            </div>

            <div class="navbar-end">
                <!-- Profile Dropdown -->
                <div class="dropdown dropdown-end">
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col items-end">
                            <span class="text-sm">{{ Auth::guard('user')->user()->name }}</span>
                            <span class="text-xs text-gray-500">Cliente #{{ Auth::guard('user')->user()->id }}</span>
                        </div>

                        <div tabindex="0" role="button" class="header-btn btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full flex items-center justify-center">
                                {{-- <x-carbon-user-avatar-filled class="w-10 h-10" /> --}}
                                @if(Auth::guard('user')->user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::guard('user')->user()->avatar) }}" class="w-10 h-10 rounded-full" />
                                @else
                                    <img src="{{ asset('images/avatar.png') }}" class="w-10 h-10 rounded-full" />
                                @endif
                            </div>
                        </div>
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-white rounded-box z-1 mt-3 w-52 p-2 shadow">
                        <li>
                            <a href="{{ route('user.profile.update') }}" class="profile-dropdown-item justify-between flex items-center gap-3">
                                <div class="flex items-center gap-3">
                                    <x-carbon-user class="w-4 h-4" />
                                    <span>Perfil</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.subscribe.show') }}" class="profile-dropdown-item flex items-center gap-3">
                                <x-carbon-layers class="w-4 h-4" />
                                <span>Planos</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.auth.logout') }}" class="profile-dropdown-item flex items-center gap-3">
                                <x-carbon-logout class="w-4 h-4" />
                                <span>Sair</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @endauth

            @if($guard === 'specialist')
            <div class="navbar-start sm:hidden">
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-ghost">
                        <x-carbon-menu class="w-7 h-7 text-slate-900" />
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-white rounded-box z-1 mt-3 w-52 p-2 shadow">
                        <li>
                            <a href="{{ route('specialist.appointment.index') }}" class="{{ !Route::is('specialist.appointment.index') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-calendar-heat-map class="w-4 h-4" />
                                Agendamentos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('specialist.availability.index') }}" class="{{ !Route::is('specialist.availability.index') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-calendar-tools class="w-4 h-4" />
                                Disponibilidades
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('specialist.client.index') }}" class="{{ !Route::is('specialist.client.index') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-user-feedback class="w-4 h-4" />
                                Clientes
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="navbar-center hidden sm:flex">
                <ul class="menu menu-horizontal px-1">
                    <li>
                        <a href="{{ route('specialist.appointment.index') }}" class="{{ !Route::is('specialist.appointment.index') ?: 'border bg-base-200 font-semibold' }}">
                            <x-carbon-calendar-heat-map class="w-5 h-5" />
                            Agendamentos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('specialist.availability.index') }}" class="{{ !Route::is('specialist.availability.index') ?: 'border bg-base-200 font-semibold' }}">
                            <x-carbon-calendar-tools class="w-5 h-5" />
                            Disponibilidades
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('specialist.client.index') }}" class="{{ !Route::is('specialist.client.index') ?: 'border bg-base-200 font-semibold' }}">
                            <x-carbon-user-feedback class="w-5 h-5" />
                            Clientes
                        </a>
                    </li>
                </ul>
            </div>

            <div class="navbar-end">
                <!-- Profile Dropdown -->
                <div class="dropdown dropdown-end">
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col items-end">
                            <span class="text-sm">{{ Auth::guard('specialist')->user()->name }}</span>
                            <span class="text-xs text-gray-500">Especialista #{{ Auth::guard('specialist')->user()->id }}</span>
                        </div>

                        <div tabindex="0" role="button" class="header-btn btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full flex items-center justify-center">
                                {{-- <x-carbon-user-avatar-filled class="w-10 h-10" /> --}}
                                <img src="{{ asset('images/avatar.png') }}" alt="User Avatar" class="w-10 h-10 rounded-full" />
                            </div>
                        </div>
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-white rounded-box z-1 mt-3 w-52 p-2 shadow">
                        <li>
                            <a href="{{ route('specialist.profile.personal-details') }}" class="profile-dropdown-item justify-between flex items-center gap-3">
                                <div class="flex items-center gap-3">
                                    <x-carbon-user class="w-4 h-4" />
                                    <span>Perfil</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('specialist.auth.logout') }}" class="profile-dropdown-item flex items-center gap-3">
                                <x-carbon-logout class="w-4 h-4" />
                                <span>Sair</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Gradient Border Bottom -->
    <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-[#39D5F8] via-[#53ACFD] to-[#7464FF]">
</header>
