<header class="bg-base-100 py-3 w-full relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="navbar bg-base-100">

            <div class="navbar-start hidden sm:block">
                <x-logotipo :guard="$guard" />
            </div>

            @if ($guard === 'user')
                <div class="navbar-start sm:hidden">
                    <div class="dropdown">
                        <div tabindex="0" role="button" class="btn btn-ghost">
                            <x-carbon-menu class="w-7 h-7 text-slate-500" />
                        </div>
                        <ul tabindex="0"
                            class="menu menu-sm dropdown-content bg-base-100 text-base-content rounded-box z-1 mt-3 w-52 p-2 shadow border border-base-300">
                            <li>
                                <a href="{{ route('user.dashboard.show') }}"
                                    class="{{ !Route::is('user.dashboard.show') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-home class="w-4 h-4" />
                                    Início
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.specialist.index') }}"
                                    class="{{ !Route::is('user.specialist.index') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-reminder-medical class="w-4 h-4" />
                                    Especialistas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.appointment.index') }}"
                                    class="{{ !Route::is('user.appointment.index') ?: 'border bg-base-200 font-semibold' }}">
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
                            <a href="{{ route('user.dashboard.show') }}"
                                class="{{ !Route::is('user.dashboard.show') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-home class="w-5 h-5" />
                                Início
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.specialist.index') }}"
                                class="{{ !Route::is('user.specialist.index') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-reminder-medical class="w-5 h-5" />
                                Especialistas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.appointment.index') }}"
                                class="{{ !Route::is('user.appointment.index') ?: 'border bg-base-200 font-semibold' }}">
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
                                <span class="text-xs text-base-content/60">Cliente
                                    #{{ Auth::guard('user')->user()->id }}</span>
                            </div>

                            <div tabindex="0" role="button" class="header-btn btn btn-ghost btn-circle avatar">
                                <div class="w-10 rounded-full flex items-center justify-center">
                                    {{-- <x-carbon-user-avatar-filled class="w-10 h-10" /> --}}
                                    @if (Auth::guard('user')->user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::guard('user')->user()->avatar) }}"
                                            class="w-10 h-10 rounded-full" />
                                    @else
                                        <img src="{{ asset('images/avatar.png') }}" class="w-10 h-10 rounded-full" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <ul tabindex="0"
                            class="menu menu-sm dropdown-content bg-base-100 text-base-content rounded-box z-1 mt-3 w-52 p-2 shadow border border-base-300">
                            <li>
                                <a href="{{ route('user.profile.update') }}"
                                    class="profile-dropdown-item justify-between flex items-center gap-3">
                                    <div class="flex items-center gap-3">
                                        <x-carbon-user class="w-4 h-4" />
                                        <span>Perfil</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.subscribe.show') }}"
                                    class="profile-dropdown-item flex items-center gap-3">
                                    <x-carbon-layers class="w-4 h-4" />
                                    <span>Planos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.auth.logout') }}"
                                    class="profile-dropdown-item flex items-center gap-3">
                                    <x-carbon-logout class="w-4 h-4" />
                                    <span>Sair</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth

            @if ($guard === 'specialist')
                <div class="navbar-start sm:hidden">
                    <div class="dropdown">
                        <div tabindex="0" role="button" class="btn btn-ghost">
                            <x-carbon-menu class="w-7 h-7 text-slate-500" />
                        </div>
                        <ul tabindex="0"
                            class="menu menu-sm dropdown-content bg-base-100 text-base-content rounded-box z-1 mt-3 w-52 p-2 shadow border border-base-300">
                            <li>
                                <a href="{{ route('specialist.dashboard.show') }}"
                                    class="{{ !Route::is('specialist.dashboard.show') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-home class="w-4 h-4" />
                                    Início
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('specialist.appointment.index') }}"
                                    class="{{ !Route::is('specialist.appointment.index') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-calendar-heat-map class="w-4 h-4" />
                                    Agendamentos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('specialist.availability.index') }}"
                                    class="{{ !Route::is('specialist.availability.index') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-calendar-tools class="w-4 h-4" />
                                    Disponibilidades
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('specialist.client.index') }}"
                                    class="{{ !Route::is('specialist.client.index') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-user-feedback class="w-4 h-4" />
                                    Clientes
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('specialist.finance.payment-data') }}"
                                    class="{{ !Route::is('specialist.finance.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-wallet class="w-4 h-4" />
                                    Financeiro
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="navbar-center hidden sm:flex">
                    <ul class="menu menu-horizontal px-1">
                        <li>
                            <a href="{{ route('specialist.dashboard.show') }}"
                                class="{{ !Route::is('specialist.dashboard.show') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-home class="w-5 h-5" />
                                Início
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('specialist.appointment.index') }}"
                                class="{{ !Route::is('specialist.appointment.index') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-calendar-heat-map class="w-5 h-5" />
                                Agendamentos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('specialist.availability.index') }}"
                                class="{{ !Route::is('specialist.availability.index') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-calendar-tools class="w-5 h-5" />
                                Disponibilidades
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('specialist.client.index') }}"
                                class="{{ !Route::is('specialist.client.index') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-user-feedback class="w-5 h-5" />
                                Clientes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('specialist.finance.payment-data') }}"
                                class="{{ !Route::is('specialist.finance.*') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-wallet class="w-5 h-5" />
                                Financeiro
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
                                <span class="text-xs text-base-content/60">Especialista
                                    #{{ Auth::guard('specialist')->user()->id }}</span>
                            </div>

                            <div tabindex="0" role="button" class="header-btn btn btn-ghost btn-circle avatar">
                                <div class="w-10 rounded-full flex items-center justify-center">
                                    @if (Auth::guard('specialist')->user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::guard('specialist')->user()->avatar) }}"
                                            class="w-10 h-10 rounded-full" />
                                    @else
                                        <img src="{{ asset('images/avatar.png') }}"
                                            class="w-10 h-10 rounded-full" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <ul tabindex="0"
                            class="menu menu-sm dropdown-content bg-base-100 text-base-content rounded-box z-1 mt-3 w-52 p-2 shadow border border-base-300">
                            <li>
                                <a href="{{ route('specialist.profile.personal-details') }}"
                                    class="profile-dropdown-item justify-between flex items-center gap-3">
                                    <div class="flex items-center gap-3">
                                        <x-carbon-user class="w-4 h-4" />
                                        <span>Perfil</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('specialist.auth.logout') }}"
                                    class="profile-dropdown-item flex items-center gap-3">
                                    <x-carbon-logout class="w-4 h-4" />
                                    <span>Sair</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth

            @if ($guard === 'company')
                <div class="navbar-start sm:hidden">
                    <div class="dropdown">
                        <div tabindex="0" role="button" class="btn btn-ghost">
                            <x-carbon-menu class="w-7 h-7 text-slate-500" />
                        </div>
                        <ul tabindex="0"
                            class="menu menu-sm dropdown-content bg-base-100 text-base-content rounded-box z-1 mt-3 w-52 p-2 shadow border border-base-300">
                            <li>
                                <a href="{{ route('company.dashboard.show') }}"
                                    class="{{ !Route::is('company.dashboard.show') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-home class="w-4 h-4" />
                                    Início
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company.plan.index') }}"
                                    class="{{ !Route::is('company.plan.index') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-layers class="w-4 h-4" />
                                    Planos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company.employee.index') }}"
                                    class="{{ !Route::is('company.employee.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-user-multiple class="w-4 h-4" />
                                    Funcionários
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company.payment.index') }}"
                                    class="{{ !Route::is('company.payment.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-money class="w-4 h-4" />
                                    Pagamentos
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="navbar-center hidden sm:flex">
                    <ul class="menu menu-horizontal px-1">
                        <li>
                            <a href="{{ route('company.dashboard.show') }}"
                                class="{{ !Route::is('company.dashboard.show') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-home class="w-5 h-5" />
                                Início
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('company.plan.index') }}"
                                class="{{ !Route::is('company.plan.index') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-layers class="w-5 h-5" />
                                Planos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('company.employee.index') }}"
                                class="{{ !Route::is('company.employee.*') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-user-multiple class="w-5 h-5" />
                                Funcionários
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('company.payment.index') }}"
                                class="{{ !Route::is('company.payment.*') ?: 'border bg-base-200 font-semibold' }}">
                                <x-carbon-money class="w-5 h-5" />
                                Pagamentos
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="navbar-end">
                    <!-- Profile Dropdown -->
                    <div class="dropdown dropdown-end">
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col items-end">
                                <span class="text-sm">{{ Auth::guard('company')->user()->name }}</span>
                                <span class="text-xs text-base-content/60">Empresa
                                    #{{ Auth::guard('company')->user()->id }}</span>
                            </div>

                            <div tabindex="0" role="button" class="header-btn btn btn-ghost btn-circle">
                                <div class="avatar avatar-placeholder">
                                    <div class="bg-primary text-primary-content w-10 rounded-full">
                                        <span class="text-lg">
                                            <x-carbon-enterprise class="w-5 h-5" />
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul tabindex="0"
                            class="menu menu-sm dropdown-content bg-base-100 text-base-content rounded-box z-1 mt-3 w-52 p-2 shadow border border-base-300">
                            <li>
                                <a href="{{ route('company.profile.show') }}"
                                    class="profile-dropdown-item justify-between flex items-center gap-3">
                                    <div class="flex items-center gap-3">
                                        <x-carbon-enterprise class="w-4 h-4" />
                                        <span>Perfil da Empresa</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company.auth.logout') }}"
                                    class="profile-dropdown-item flex items-center gap-3">
                                    <x-carbon-logout class="w-4 h-4" />
                                    <span>Sair</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth

            @if ($guard === 'master')
                <div class="navbar-start sm:hidden">
                    <div class="dropdown">
                        <div tabindex="0" role="button" class="btn btn-ghost">
                            <x-carbon-menu class="w-7 h-7  text-slate-500" />
                        </div>
                        <ul tabindex="0"
                            class="menu menu-sm dropdown-content bg-base-100 text-base-content rounded-box z-1 mt-3 w-52 p-2 shadow border border-base-300">
                            <li>
                                <a href="{{ route('master.dashboard.show') }}"
                                    class="{{ !Route::is('master.dashboard.show') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-home class="w-4 h-4" />
                                    Início
                                </a>
                            </li>
                            <li>
                                <h3 class="menu-title text-xs font-semibold text-base-content/60 px-3 py-2">Gestão de Usuários</h3>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.user.index') }}" class="{{ !Route::is('master.user.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-user class="w-4 h-4" /> Usuários
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.company.index') }}" class="{{ !Route::is('master.company.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-enterprise class="w-4 h-4" /> Empresas
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.specialist.index') }}" class="{{ !Route::is('master.specialist.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-reminder-medical class="w-4 h-4" /> Especialistas
                                </a>
                            </li>
                            <li>
                                <h3 class="menu-title text-xs font-semibold text-base-content/60 px-3 py-2">Gerenciamento Interno</h3>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.appointment.index') }}" class="{{ !Route::is('master.appointment.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-calendar-heat-map class="w-4 h-4" /> Agendamentos
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.payment.index') }}" class="{{ !Route::is('master.payment.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-money class="w-4 h-4" /> Pagamentos
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.plan.index') }}" class="{{ !Route::is('master.plan.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-plan class="w-4 h-4" /> Planos
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.subscribe.index') }}" class="{{ !Route::is('master.subscribe.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-license class="w-4 h-4" /> Assinaturas
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.room.index') }}" class="{{ !Route::is('master.room.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-video class="w-4 h-4" /> Salas
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.availability.index') }}" class="{{ !Route::is('master.availability.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-calendar-tools class="w-4 h-4" /> Disponibilidades
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.finance.index') }}" class="{{ !Route::is('master.finance.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-wallet class="w-4 h-4" /> Financeiro
                                </a>
                            </li>
                            <li>
                                <h3 class="menu-title text-xs font-semibold text-base-content/60 px-3 py-2">Configurações</h3>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.specialty.index') }}" class="{{ !Route::is('master.specialty.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-catalog class="w-4 h-4" /> Especialidades
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.reason.index') }}" class="{{ !Route::is('master.reason.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-list-checked class="w-4 h-4" /> Motivos
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.gender.index') }}" class="{{ !Route::is('master.gender.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-gender-female class="w-4 h-4" /> Gêneros
                                </a>
                            </li>
                            <li class="ml-2">
                                <a href="{{ route('master.training-type.index') }}" class="{{ !Route::is('master.training-type.*') ?: 'border bg-base-200 font-semibold' }}">
                                    <x-carbon-education class="w-4 h-4" /> Tipos de Formação
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="navbar-center hidden sm:flex">
                    <ul class="menu menu-horizontal gap-1">
                        <li>
                            <a href="{{ route('master.dashboard.show') }}"
                                class="btn btn-ghost {{ Route::is('master.dashboard.show') ? 'border bg-base-200 font-semibold' : 'font-light' }}">
                                <x-carbon-home class="w-5 h-5" />
                                Início
                            </a>
                        </li>

                        <!-- Dropdown Gestão de Usuários -->
                        <li class="dropdown dropdown-bottom">
                            <button tabindex="0"
                                class="btn btn-ghost {{ Route::is('master.user.*', 'master.company.*', 'master.specialist.*') ? 'border bg-base-200 font-semibold' : 'font-light' }}">
                                <x-carbon-user-multiple class="w-5" />
                                Gestão de Usuários
                            </button>
                            <ul tabindex="0"
                                class="dropdown-content menu bg-base-100 text-base-content rounded-box shadow-md z-10 w-52 p-2 border border-base-300">
                                <li>
                                    <a href="{{ route('master.user.index') }}" class="{{ !Route::is('master.user.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-user class="w-4" /> Usuários
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('master.company.index') }}" class="{{ !Route::is('master.company.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-enterprise class="w-4" /> Empresas
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('master.specialist.index') }}" class="{{ !Route::is('master.specialist.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-reminder-medical class="w-4" /> Especialistas
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Dropdown Gerenciamento Interno -->
                        <li class="dropdown dropdown-bottom">
                            <button tabindex="0"
                                class="btn btn-ghost {{ Route::is('master.appointment.*', 'master.payment.*', 'master.plan.*', 'master.subscribe.*', 'master.room.*', 'master.availability.*') ? 'border bg-base-200 font-semibold' : 'font-light' }}">
                                <x-carbon-settings class="w-5 h-5" />
                                Gerenciamento
                            </button>
                            <ul tabindex="0"
                                class="dropdown-content menu bg-base-100 text-base-content rounded-box shadow-md z-10 w-56 p-2 border border-base-300">
                                <li>
                                    <a href="{{ route('master.appointment.index') }}" class="{{ !Route::is('master.appointment.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-calendar-heat-map class="w-4 h-4" /> Agendamentos
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('master.payment.index') }}" class="{{ !Route::is('master.payment.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-money class="w-4 h-4" /> Pagamentos
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('master.plan.index') }}" class="{{ !Route::is('master.plan.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-plan class="w-4 h-4" /> Planos
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('master.subscribe.index') }}" class="{{ !Route::is('master.subscribe.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-license class="w-4 h-4" /> Assinaturas
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('master.room.index') }}" class="{{ !Route::is('master.room.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-video class="w-4 h-4" /> Salas
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('master.availability.index') }}" class="{{ !Route::is('master.availability.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-calendar-tools class="w-4 h-4" /> Disponibilidades
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Financeiro -->
                        <li>
                            <a href="{{ route('master.finance.index') }}"
                                class="btn btn-ghost {{ Route::is('master.finance.*') ? 'border bg-base-200 font-semibold' : 'font-light' }}">
                                <x-carbon-wallet class="w-5 h-5" />
                                Financeiro
                            </a>
                        </li>

                        <!-- Dropdown Configurações -->
                        <li class="dropdown dropdown-bottom">
                            <button tabindex="0"
                                class="btn btn-ghost {{ Route::is('master.specialty.*', 'master.reason.*', 'master.gender.*', 'master.training-type.*') ? 'border bg-base-200 font-semibold' : 'font-light' }}">
                                <x-carbon-settings-adjust class="w-5 h-5" />
                                Configurações
                            </button>
                            <ul tabindex="0"
                                class="dropdown-content menu bg-base-100 text-base-content rounded-box shadow-md z-10 w-56 p-2 border border-base-300">
                                <li>
                                    <a href="{{ route('master.specialty.index') }}" class="{{ !Route::is('master.specialty.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-catalog class="w-4 h-4" /> Especialidades
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('master.reason.index') }}" class="{{ !Route::is('master.reason.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-list-checked class="w-4 h-4" /> Motivos de Consulta
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('master.gender.index') }}" class="{{ !Route::is('master.gender.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-gender-female class="w-4 h-4" /> Gêneros
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('master.training-type.index') }}" class="{{ !Route::is('master.training-type.*') ?: 'bg-base-200 font-semibold' }}">
                                        <x-carbon-education class="w-4 h-4" /> Tipos de Formação
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="navbar-end">
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-sm">
                            <x-carbon-user class="w-4 h-4" />
                            {{ Auth::guard('master')->user()->name }}
                        </div>
                        <ul tabindex="0" class="dropdown-content menu bg-base-100 text-base-content rounded-box shadow-md z-10 w-48 p-2 border border-base-300">
                            <li>
                                <a href="{{ route('master.profile.edit') }}">
                                    <x-carbon-user class="w-4 h-4" /> Meu Perfil
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('master.auth.logout') }}">
                                    <x-carbon-logout class="w-4 h-4" /> Sair
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth
</div>
</div>

<!-- Gradient Border Bottom -->
<div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-[#39D5F8] via-[#53ACFD] to-[#7464FF]">
</header>
