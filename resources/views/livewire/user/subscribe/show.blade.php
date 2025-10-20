<div>
    <ul class="menu menu-vertical lg:menu-horizontal bg-base-100 rounded-box w-full mb-6">
        <li><a class="{{ !Route::is('user.subscribe.show') ?: 'menu-active' }}"
                href="{{ route('user.subscribe.show') }}">Plano atual</a></li>
        <li><a class="{{ !Route::is('user.subscribe.history') ?: 'menu-active' }}"
                href="{{ route('user.subscribe.history') }}">Histórico</a></li>
    </ul>

    @if (session()->has('success'))
        <div role="alert" class="alert alert-success">
            <x-carbon-checkmark class="w-6 h-6" />
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($this->pendingSubscribe)
        <div role="alert" class="alert alert-warning mb-6 shadow-lg border-2 border-warning">
            <x-carbon-warning class="w-8 h-8 flex-shrink-0" />
            <div class="flex-1">
                <h3 class="font-bold text-lg">⚠️ Pagamento Pendente!</h3>
                <div class="text-sm mt-2 space-y-1">
                    <p>Você tem uma assinatura do plano
                        <strong class="text-warning-content">{{ $this->pendingSubscribe->plan->name }}</strong> aguardando pagamento.
                    </p>
                    <p>
                        <strong>Status:</strong>
                        @if ($this->pendingSubscribe->payments->isNotEmpty() && $this->pendingSubscribe->payments->first()->payment_method === 'pix')
                            Aguardando pagamento via PIX
                        @elseif ($this->pendingSubscribe->payments->isNotEmpty())
                            Processando pagamento via {{ strtoupper($this->pendingSubscribe->payments->first()->payment_method) }}
                        @else
                            Aguardando seleção do método de pagamento
                        @endif
                    </p>
                    <p class="text-xs opacity-80">
                        Criado em: {{ $this->pendingSubscribe->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-xs font-semibold mt-2 bg-warning/20 p-2 rounded">
                        💡 Complete o pagamento para ativar sua assinatura. Caso contrário, ela será cancelada automaticamente.
                    </p>
                </div>
            </div>
            <div class="flex-none">
                <a href="{{ route('user.plan.payment', ['plan_id' => $this->pendingSubscribe->plan_id]) }}"
                    wire:navigate class="btn btn-warning btn-sm gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    Finalizar Pagamento
                </a>
            </div>
        </div>
    @endif

    <x-heading>
        <x-title>Plano atual</x-title>
        <x-subtitle>Veja os detalhes do seu plano atual.</x-subtitle>
    </x-heading>



    @if ($hasCompanyPlan)
        @if ($companyPlan->companyPlan->is_active)
            <x-card class="bg-gradient-to-r from-info/10 to-info/20 border border-info/30 mt-4">
                <x-card-body>
                    <div class="flex items-center gap-3 mb-4">
                        <x-carbon-building class="w-8 h-8 text-info" />
                        <div>
                            <h3 class="text-lg font-semibold text-info">Plano Empresarial Ativo</h3>
                            <x-text>Você está vinculado ao plano da sua empresa</x-text>
                        </div>
                        <x-badge class="badge-success ml-auto">ATIVO</x-badge>
                    </div>

                    <x-card class="mb-4">
                        <x-card-body>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-text>Empresa</x-text>
                                    <p class="font-semibold">{{ $companyPlan->companyPlan->company->name }}</p>
                                </div>
                                <div>
                                    <x-text>Plano</x-text>
                                    <p class="font-semibold">{{ $companyPlan->companyPlan->name }}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <x-text>Desconto oferecido</x-text>
                                <p class="font-bold text-success text-xl">
                                    {{ number_format($companyPlan->companyPlan->discount_percentage, 1) }}%</p>
                                <p class="text-xs text-base-content/60">A empresa cobre
                                    {{ number_format($companyPlan->companyPlan->discount_percentage, 1) }}% do valor
                                    dos
                                    seus serviços</p>
                            </div>
                        </x-card-body>
                    </x-card>

                    <div class="alert alert-info">
                        <x-carbon-information class="w-5 h-5" />
                        <div>
                            <h4 class="font-semibold">Benefícios do plano empresarial</h4>
                            <p class="text-sm">Enquanto estiver vinculado à empresa, você terá desconto automático nas
                                suas
                                consultas e não poderá contratar planos individuais.</p>
                        </div>
                    </div>
                </x-card-body>
            </x-card>
        @else
            <x-card class="bg-gradient-to-r from-warning/10 to-warning/20 border border-warning/30 mt-4">
                <x-card-body>
                    <div class="flex items-center gap-3 mb-4">
                        <x-carbon-building class="w-8 h-8 text-warning" />
                        <div>
                            <h3 class="text-lg font-semibold text-warning">Plano Empresarial Inativo</h3>
                            <x-text>Seu plano empresarial está temporariamente inativo</x-text>
                        </div>
                        <x-badge class="badge-warning ml-auto">INATIVO</x-badge>
                    </div>

                    <x-card class="mb-4">
                        <x-card-body>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-text>Empresa</x-text>
                                    <p class="font-semibold">{{ $companyPlan->companyPlan->company->name }}</p>
                                </div>
                                <div>
                                    <x-text>Plano</x-text>
                                    <p class="font-semibold">{{ $companyPlan->companyPlan->name }}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <x-text>Desconto (suspenso)</x-text>
                                <p class="font-bold text-base-content/50 text-xl line-through">
                                    {{ number_format($companyPlan->companyPlan->discount_percentage, 1) }}%</p>
                                <p class="text-xs text-warning">⚠️ Desconto suspenso até que o plano seja reativado pela
                                    empresa</p>
                            </div>
                        </x-card-body>
                    </x-card>

                    <div class="alert alert-warning">
                        <x-carbon-warning class="w-5 h-5" />
                        <div>
                            <h4 class="font-semibold">Plano temporariamente inativo</h4>
                            <p class="text-sm">O desconto empresarial não está sendo aplicado no momento. Entre em
                                contato com sua empresa para reativação do plano.</p>
                        </div>
                    </div>
                </x-card-body>
            </x-card>
        @endif
    @elseif($subscribe)
        <x-card class="mt-4">
            <x-card-body>
                <div class="flex justify-between">
                    <span class="badge badge-xs badge-info">Plano finaliza em
                        {{ \Carbon\Carbon::parse($subscribe->end_date)->format('d/m/Y') }}</span>
                    @php
                        // Mostrar o valor que foi efetivamente pago (sem desconto)
                        $latestPayment = $subscribe->payments->first();
                        $displayAmount = $latestPayment?->amount ?? ($subscribe->plan?->price ?? 0);
                    @endphp
                    <span class="text-xl">
                        <x-text>R$</x-text>
                        {{ number_format($displayAmount, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <h2 class="text-3xl font-bold">{{ $subscribe->plan->name }}</h2>
                    @if (!$subscribe->cancelled_date && $subscribe->end_date > now())
                        <button class="btn btn-soft btn-xs btn-error" wire:click="cancel({{ $subscribe->id }})">
                            <x-carbon-close class="w-4 h-4" />
                            Cancelar
                        </button>
                    @else
                        <span class="badge badge-xs badge-error">Assinatura cancelada</span>
                    @endif
                </div>

                <ul class="mt-6 flex flex-col gap-6 text-xs">
                    <li class="flex gap-2">
                        <x-carbon-checkmark class="w-4 h-4 text-info" />
                        Acesso completo à biblioteca de conteúdos Timeplus
                    </li>
                    <li class="flex gap-2">
                        <x-carbon-checkmark class="w-4 h-4 text-info" />
                        Fácil acesso a toda comunidade de psicólogos, psicanalistas, terapeutas e coaches
                    </li>
                    <li class="flex gap-2">
                        <x-carbon-checkmark class="w-4 h-4 text-info" />
                        Atendimento prioritário pela nossa equipe de suporte
                    </li>
                </ul>

            </x-card-body>
        </x-card>
    @else
        <div class="min-h-[50vh] flex items-center justify-center">
            <div class="flex flex-col items-center justify-center space-y-8 max-w-sm w-full px-6">


                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="180" height="180"
                        viewBox="0 0 964.71029 806.00281" xmlns:xlink="http://www.w3.org/1999/xlink" role="img"
                        artist="Katerina Limpitsouni" source="https://undraw.co/">
                        <path
                            d="M327.05924,550.68732l16.47283,9.46591a13.21787,13.21787,0,0,0,18.98413-6.87915v0a13.21785,13.21785,0,0,0-11.67273-17.77919l-19.94851-1.097-60.0914-18.18889-9.68789-36.27347-25.33312,14.22993,17.41875,41.23932Z"
                            transform="translate(-117.64486 -46.99859)" fill="#ffb8b8" />
                        <path
                            d="M248.52,362.41877c-3.8108,4.32-3.12293,9.51645-.34752,13.735s7.39378,7.72855,11.4357,11.4508c2.59652,2.39116,4.99867,4.92886,6.16172,7.806a8.46832,8.46832,0,0,1-1.38871,8.95551c-2.64677,3.131-7.57691,5.25136-12.38725,7.0688a244.1299,244.1299,0,0,1-42.408,11.67787c12.18063-14.23247,18.27794-30.26091,17.38473-45.70051-.18746-3.24025-.66875-6.51051.46279-9.758a13.25989,13.25989,0,0,1,9.08467-7.9492c4.84146-1.39017,11.21868-.01516,11.961,3.15719"
                            transform="translate(-117.64486 -46.99859)" fill="#2f2e41" />
                        <polygon points="26.523 772.15 43.953 772.908 78.355 614.001 48.5 615.284 26.523 772.15"
                            fill="#ffb8b8" />
                        <polygon points="200.819 738.049 186.42 745.627 96.355 612.001 125.038 599.37 200.819 738.049"
                            fill="#ffb8b8" />
                        <circle cx="100.42729" cy="337.8699" r="27.57008" fill="#ffb8b8" />
                        <path
                            d="M204.03482,399.3235s-9.85149,20.46078-23.492,19.703c0,0-25.76543-10.6093-34.1013,18.18736l-9.09368,22.7342,120.49126,20.46078s-6.82026-41.67937-22.7342-42.43718c0,0-18.18736-1.51561-15.91394-8.33587s10.60929-22.7342,10.60929-22.7342Z"
                            transform="translate(-117.64486 -46.99859)" fill="#ffb8b8" />
                        <path
                            d="M244.19857,503.90083l-18.18736,39.40594L241,607l73,137s-33.61949,30.16239-42,35c-7.26951,4.19629-67.79-109.55479-70-133-.72937-7.73772-14,149-14,149s-50.02954-8.2558-64-11c0,0,10.06518-71.291,16-138,4.57469-51.42075.41494-98.02176,26.14448-120.12278l-2.27342-43.95279L224.4956,479.651Z"
                            transform="translate(-117.64486 -46.99859)" fill="#2f2e41" />
                        <path
                            d="M150.03652,848.37651l27.82578,4.04028a9.2607,9.2607,0,0,0,11.5581-4.62v0a9.26068,9.26068,0,0,0-1.53816-10.35916l-11.8106-12.71924-8.50029-18.34182c-5.08375-5.19247-8.55881-3.35606-10.50161,5.17508l-13.58848-4.26728-3.56285,23.94949A15.81137,15.81137,0,0,0,150.03652,848.37651Z"
                            transform="translate(-117.64486 -46.99859)" fill="#2f2e41" />
                        <path
                            d="M308.63877,823.31634l28.1174-.09816a9.26068,9.26068,0,0,0,10.75247-6.27044h0a9.26069,9.26069,0,0,0-3.04573-10.02007l-13.55362-10.8429-11.10669-16.89138c-5.79247-4.3879-8.95947-2.06013-9.6258,6.664l-14.06848-2.22134v24.21306A15.81136,15.81136,0,0,0,308.63877,823.31634Z"
                            transform="translate(-117.64486 -46.99859)" fill="#2f2e41" />
                        <path
                            d="M204.14642,342.48823c-4.683-2.04669-10.25575-1.24415-14.777,1.13862s-8.13367,6.1826-11.29533,10.19793A86.01322,86.01322,0,0,0,159.891,402.25234c-.24766,4.818-.01528,9.91043,2.49308,14.03149,2.75651,4.52874,7.80193,7.12846,12.79953,8.89816a67.33042,67.33042,0,0,0,51.15114-2.81387,181.33408,181.33408,0,0,1-23.27937-22.615c-1.32458-1.54042-2.70952-3.518-2.03587-5.43463a5.95382,5.95382,0,0,1,1.90922-2.344l16.23212-14.09621-.40869,5.7025A16.54522,16.54522,0,0,0,233.34021,372.707c-.78268,1.57966.71155,3.51981,2.4383,3.87512s3.47489-.35657,5.09426-1.0534c3.92746-1.69,8.19353-3.70248,9.936-7.607a10.91477,10.91477,0,0,0-.23018-8.61518,21.66419,21.66419,0,0,0-5.2829-7.04327A44.18788,44.18788,0,0,0,204.042,342.00676"
                            transform="translate(-117.64486 -46.99859)" fill="#2f2e41" />
                        <path
                            d="M198.63856,546.74242,210.744,561.38533a13.21785,13.21785,0,0,0,20.19089.21747l0,0a13.21786,13.21786,0,0,0-4.69418-20.74409l-18.296-8.0251L158.05248,494.721l3.65218-37.36685-28.715,4.439,1.84543,44.72906Z"
                            transform="translate(-117.64486 -46.99859)" fill="#ffb8b8" />
                        <path
                            d="M273.753,513.75231s-11.79947-43.21548-18.02464-43.20523-14.561-.74755-14.561-.74755-9.85148-28.79666-60.62453-19.703-28.79665-8.33587-43.195-1.51561-19.703,54.56208-19.703,54.56208,78.05408-25.76543,94.72583-11.3671a185.11783,185.11783,0,0,0,31.07007,21.97639S235.8627,532.69748,273.753,513.75231Z"
                            transform="translate(-117.64486 -46.99859)" fill="#2f2e41" />
                        <rect x="120.70066" y="773.94745" width="306" height="2" fill="#3f3d56" />
                        <circle cx="661.70066" cy="243.94745" r="185" fill="#e6e6e6" />
                        <rect x="320.70066" y="140.94745" width="306" height="2" fill="#3f3d56" />
                        <rect x="320.70066" y="195.94745" width="601" height="2" fill="#3f3d56" />
                        <path
                            d="M854.34551,439.946c-102.56055,0-186-83.43945-186-186s83.43945-186,186-186,186,83.43945,186,186S956.90606,439.946,854.34551,439.946Zm0-370c-101.458,0-184,82.542-184,184s82.542,184,184,184,184-82.542,184-184S955.80352,69.946,854.34551,69.946Z"
                            transform="translate(-117.64486 -46.99859)" fill="#3f3d56" />
                        <circle cx="719.54007" cy="24.42044" r="24.42044" fill="#38bdf8" />
                        <circle cx="551.39847" cy="193.85839" r="15.80146" fill="#ff6584" />
                        <circle cx="928.62295" cy="194.11826" r="36.08734" fill="#38bdf8" />
                        <path
                            d="M539.00887,383.50684h-109.499v2h109.499a20.32666,20.32666,0,0,1,0,40.65332H373.318a20.32666,20.32666,0,0,1,0-40.65332h6.1919v-2H373.318a22.32666,22.32666,0,0,0,0,44.65332H539.00887a22.32666,22.32666,0,0,0,0-44.65332Z"
                            transform="translate(-117.64486 -46.99859)" fill="#3f3d56" />
                        <path
                            d="M539.00887,452.50684h-109.499v2h109.499a20.32666,20.32666,0,0,1,0,40.65332H373.318a20.32666,20.32666,0,0,1,0-40.65332h6.1919v-2H373.318a22.32666,22.32666,0,0,0,0,44.65332H539.00887a22.32666,22.32666,0,0,0,0-44.65332Z"
                            transform="translate(-117.64486 -46.99859)" fill="#3f3d56" />
                        <path
                            d="M539.00887,521.50684h-109.499v2h109.499a20.32666,20.32666,0,0,1,0,40.65332H373.318a20.32666,20.32666,0,0,1,0-40.65332h6.1919v-2H373.318a22.32666,22.32666,0,0,0,0,44.65332H539.00887a22.32666,22.32666,0,0,0,0-44.65332Z"
                            transform="translate(-117.64486 -46.99859)" fill="#3f3d56" />
                        <path id="be0e5baf-e5ec-4f16-9492-7d4921bb2c9b-446" data-name="Path 40"
                            d="M391.86936,378.22316a5.94683,5.94683,0,0,0,0,11.892h26.08572a5.94683,5.94683,0,0,0,.19526-11.892q-.09764-.00165-.19526,0Z"
                            transform="translate(-117.64486 -46.99859)" fill="#38bdf8" />
                        <path id="f5faaf73-4e1d-4e3d-a937-9f255bff3911-447" data-name="Path 40"
                            d="M391.86936,448.22316a5.94683,5.94683,0,0,0,0,11.892h26.08572a5.94683,5.94683,0,0,0,.19526-11.892q-.09764-.00165-.19526,0Z"
                            transform="translate(-117.64486 -46.99859)" fill="#ff6584" />
                        <path id="bdcbecaa-58f8-42a6-983f-c76dd899917c-448" data-name="Path 40"
                            d="M391.86936,516.22316a5.94683,5.94683,0,0,0,0,11.89205h26.08572a5.94683,5.94683,0,0,0,.19526-11.89205q-.09764-.00165-.19526,0Z"
                            transform="translate(-117.64486 -46.99859)" fill="#3f3d56" />
                        <path id="bbcd49d7-9efc-41e7-bbf8-c6009cf796dd-449" data-name="Path 40"
                            d="M381.52292,399.88748a5.94682,5.94682,0,0,0,0,11.892H530.60865a5.94682,5.94682,0,1,0,.19525-11.892q-.09762-.00167-.19525,0Z"
                            transform="translate(-117.64486 -46.99859)" fill="#e6e6e6" />
                        <path id="b24c44c6-9087-448d-956a-9230903e4c37-450" data-name="Path 40"
                            d="M381.52292,468.88748a5.94682,5.94682,0,0,0,0,11.892H530.60865a5.94682,5.94682,0,1,0,.19525-11.892q-.09762-.00167-.19525,0Z"
                            transform="translate(-117.64486 -46.99859)" fill="#e6e6e6" />
                        <path id="f49c9183-be6f-4e21-8191-a3b3cb38d75e-451" data-name="Path 40"
                            d="M381.52292,537.88748a5.94682,5.94682,0,0,0,0,11.892H530.60865a5.94682,5.94682,0,1,0,.19525-11.892q-.09762-.00166-.19525,0Z"
                            transform="translate(-117.64486 -46.99859)" fill="#e6e6e6" />
                        <path
                            d="M532.99414,761.25207a102.53212,102.53212,0,0,0-8.998-28.88666l-.64307-1.32977-4.77637,5.29583,3.53907-7.88836-.23145-.45783a188.61182,188.61182,0,0,0-10.36767-17.98865l-.70166-1.07446-.936,1.03778.65527-1.46106-.32812-.49243c-4.686-7.02862-8.28613-11.47388-8.32178-11.51861l-1.01807-1.25251-.65478,1.49359c-.12012.27375-12.019,27.6922-13.59522,54.78608l-.0249.42224,8.0083,9.68011-8.167-5.12695.01416,1.8609a79.264,79.264,0,0,0,.64893,9.5224q.53952,4.07511,1.27,7.95691a4.2433,4.2433,0,0,1,2.17163.67737q-.84623-4.33759-1.45966-8.91614c-.31543-2.43677-.51416-4.91229-.59326-7.38062l15.79687,9.91816L488.6377,751.22c1.42432-22.907,10.26465-45.98608,12.74658-52.07757,1.332,1.7276,3.84473,5.08117,6.85059,9.561l-5.46436,12.182,7.79053-8.63819a186.51624,186.51624,0,0,1,9.32178,16.25184L511.46827,747.257l11.377-12.61548a100.38218,100.38218,0,0,1,8.1709,26.91992c1.99121,15.04108,1.60254,29.49-1.09473,40.6861-2.5835,10.725-7.13672,17.31714-12.49268,18.0874a8.46568,8.46568,0,0,1-3.30035-.19617,4.89829,4.89829,0,0,1-2.96075.90357h-.29193a11.607,11.607,0,0,0,5.42364,1.45672,9.83945,9.83945,0,0,0,1.40283-.10076c6.29688-.90448,11.32569-7.88123,14.15918-19.643C534.61914,791.30578,535.023,776.575,532.99414,761.25207Z"
                            transform="translate(-117.64486 -46.99859)" fill="#3f3d56" />
                        <path
                            d="M515.37061,821.708c-8.23682,0-20.11279-4.53125-31.89209-12.7793a50.89,50.89,0,0,1-4.957-3.98535l-1.31934-1.20508,4.98828-1.43652-6.64209-.24316-.28027-.29395c-12.4502-13.03906-20.19775-31.457-20.2749-31.64063l-.60547-1.45507,1.57422.07129c.15674.00683,3.89941.18359,9.52441.98144l.35059.04981v-.00293l1.2334.1875a123.75355,123.75355,0,0,1,13.62939,2.86425l.479.12989,1.49659,3.68652-.22705-3.30664,1.373.39648a66.22881,66.22881,0,0,1,18.32764,8.49707c17.91357,12.54395,27.82568,28.22168,22.58594,35.70411C522.94385,820.48534,519.647,821.707,515.37061,821.708Zm-34.14307-17.04785c1.09424.92676,2.23291,1.80859,3.40088,2.63183,16.40674,11.48926,34.023,15.834,38.46778,9.48926,4.44384-6.3457-5.66553-21.415-22.07471-32.90527a64.1612,64.1612,0,0,0-16.38086-7.81446l.88721,12.917L479.69971,774.623c-3.83838-1.01856-7.76514-1.85645-11.69629-2.4961l.644,9.375-4.01123-9.87988c-3.10791-.42676-5.61328-.6582-7.1372-.77637,2.07763,4.5625,8.94287,18.65137,18.93652,29.24415l18.33008.67089Z"
                            transform="translate(-117.64486 -46.99859)" fill="#3f3d56" />
                    </svg>
                </div>
                <h2 class="text-base-content/70">Nenhuma assinatura contratada.</h2>

                <a href="{{ route('user.plan.index') }}" class="btn btn-info btn-block">
                    Realizar assinatura
                </a>
                <p class="text-center text-xs">O histórico será mostrado aqui após a realização da sua assinatura.</p>
            </div>
        </div>
    @endif
</div>
