<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-wallet class="w-8 text-info" />
            Meus Créditos
        </x-title>
        <x-subtitle>
            Acompanhe seus créditos de sessão e seu saldo disponível
        </x-subtitle>
    </x-heading>

    {{-- Créditos do plano da empresa (pacote de créditos) --}}
    @if ($this->companyCreditInfo)
        @php($info = $this->companyCreditInfo)

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <x-card>
                <x-card-body>
                    <div class="flex items-center gap-3">
                        <div class="p-3 rounded-lg bg-info/10">
                            <x-carbon-wallet class="w-6 h-6 text-info" />
                        </div>
                        <div>
                            <p class="text-sm text-base-content/60">Seus créditos neste mês</p>
                            <p class="text-2xl font-bold">
                                @if ($info['limit'] !== null)
                                    {{ $info['employee_remaining'] }} de {{ $info['limit'] }}
                                @else
                                    Sem limite individual
                                @endif
                            </p>
                            <p class="text-xs text-base-content/60">Renovam todo início de mês</p>
                        </div>
                    </div>
                </x-card-body>
            </x-card>

            <x-card>
                <x-card-body>
                    <div class="flex items-center gap-3">
                        <div class="p-3 rounded-lg bg-success/10">
                            <x-carbon-checkmark-outline class="w-6 h-6 text-success" />
                        </div>
                        <div>
                            <p class="text-sm text-base-content/60">Sessões usadas no mês</p>
                            <p class="text-2xl font-bold">{{ $info['used'] }}</p>
                            <p class="text-xs text-base-content/60">Plano: {{ $info['plan_name'] }}</p>
                        </div>
                    </div>
                </x-card-body>
            </x-card>

            <x-card>
                <x-card-body>
                    <div class="flex items-center gap-3">
                        <div class="p-3 rounded-lg bg-warning/10">
                            <x-carbon-money class="w-6 h-6 text-warning" />
                        </div>
                        <div>
                            <p class="text-sm text-base-content/60">Custo por sessão</p>
                            <p class="text-2xl font-bold">R$ {{ number_format($info['unit_price'], 2, ',', '.') }}</p>
                            <p class="text-xs text-base-content/60">Pago pela {{ $info['company_name'] }}</p>
                        </div>
                    </div>
                </x-card-body>
            </x-card>
        </div>

        <div class="bg-info/10 p-4 rounded-lg mb-6">
            <div class="flex items-start gap-3">
                <x-carbon-information class="w-5 h-5 text-info flex-shrink-0 mt-0.5" />
                <div class="text-sm text-base-content/80">
                    Cada sessão agendada consome <strong>1 crédito</strong>, pago pela sua empresa
                    (R$ {{ number_format($info['unit_price'], 2, ',', '.') }} por sessão).
                    @if ($info['limit'] !== null)
                        Seu limite mensal é de <strong>{{ $info['limit'] }} crédito(s)</strong> e renova no início de
                        cada mês.
                    @endif
                    @if ($info['company_balance'] < 1)
                        <span class="text-warning font-medium">No momento a empresa está sem créditos disponíveis —
                            fale com o RH.</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Histórico de uso --}}
        <x-card class="mb-6">
            <x-card-body>
                <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                    <x-carbon-time class="w-5 h-5 text-info" />
                    Sessões pagas com créditos da empresa
                </h3>

                @if ($this->companyUsages->isEmpty())
                    <p class="text-sm text-base-content/60">Você ainda não usou créditos da empresa.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Data do uso</th>
                                    <th>Sessão</th>
                                    <th>Especialista</th>
                                    <th class="text-right">Créditos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->companyUsages as $usage)
                                    <tr>
                                        <td>{{ $usage->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if ($usage->appointment)
                                                {{ \Carbon\Carbon::parse($usage->appointment->appointment_date)->format('d/m/Y') }}
                                                {{ \Carbon\Carbon::parse($usage->appointment->appointment_time)->format('H:i') }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $usage->appointment?->specialist?->name ?? '—' }}</td>
                                        <td class="text-right font-semibold">{{ $usage->credits_used }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card-body>
        </x-card>
    @elseif ($this->companyUser)
        {{-- Plano por funcionário: as consultas vêm do plano individual (1/2/4 por mês) --}}
        <div class="bg-info/10 p-4 rounded-lg mb-6">
            <div class="flex items-start gap-3">
                <x-carbon-information class="w-5 h-5 text-info flex-shrink-0 mt-0.5" />
                <div class="text-sm text-base-content/80">
                    Sua empresa ({{ $this->companyUser->company->name }}) oferece o plano
                    "{{ $this->companyUser->companyPlan->name }}" por funcionário. Escolha um dos planos de consultas
                    (1, 2 ou 4 por mês) em
                    <a wire:navigate href="{{ route('user.plan.index') }}" class="link link-info">Planos</a>.
                    Cada sessão TimePlus custa R$ 30,00.
                </div>
            </div>
        </div>
    @endif

    {{-- Saldo monetário pessoal --}}
    <x-card>
        <x-card-body>
            <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                <x-carbon-money class="w-5 h-5 text-info" />
                Meu saldo em dinheiro
            </h3>

            <p class="text-2xl font-bold mb-2">R$ {{ number_format($this->personalBalance, 2, ',', '.') }}</p>
            <p class="text-sm text-base-content/60 mb-4">
                Saldo gerado por cancelamentos e estornos. É usado automaticamente como desconto no pagamento das suas
                próximas sessões.
            </p>

            @if ($this->personalCredits->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Origem</th>
                                <th>Recebido em</th>
                                <th>Válido até</th>
                                <th class="text-right">Saldo restante</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->personalCredits as $credit)
                                <tr>
                                    <td>{{ $credit->description ?? ucfirst($credit->source) }}</td>
                                    <td>{{ $credit->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $credit->expires_at?->format('d/m/Y') ?? 'Sem validade' }}</td>
                                    <td class="text-right font-semibold">
                                        R$ {{ number_format((float) $credit->amount_remaining, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card-body>
    </x-card>
</div>
