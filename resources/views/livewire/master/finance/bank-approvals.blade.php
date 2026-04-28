<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-finance class="w-8 text-info" />
            Aprovação de Dados Bancários
        </x-title>
        <x-subtitle>
            Revise e aprove os dados bancários cadastrados pelos especialistas.
        </x-subtitle>
    </x-heading>

    <div class="flex gap-2 mb-4">
        <button type="button" wire:click="setFilter('pending')"
            class="btn btn-sm {{ $filter === 'pending' ? 'btn-warning' : 'btn-soft' }}">
            Pendentes
        </button>
        <button type="button" wire:click="setFilter('approved')"
            class="btn btn-sm {{ $filter === 'approved' ? 'btn-success' : 'btn-soft' }}">
            Aprovados
        </button>
        <button type="button" wire:click="setFilter('all')"
            class="btn btn-sm {{ $filter === 'all' ? 'btn-info' : 'btn-soft' }}">
            Todos
        </button>
    </div>

    <x-card>
        <x-card-body>
            @if ($profiles->isEmpty())
                <div class="text-center py-10 text-base-content/60">
                    Nenhum dado bancário {{ $filter === 'pending' ? 'pendente' : ($filter === 'approved' ? 'aprovado' : '') }}.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Especialista</th>
                                <th>Tipo</th>
                                <th>Dados</th>
                                <th>Titular</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profiles as $profile)
                                <tr>
                                    <td>
                                        <div class="font-semibold">{{ $profile->specialist?->name ?? '—' }}</div>
                                        <div class="text-xs text-base-content/60">{{ $profile->specialist?->email }}</div>
                                    </td>
                                    <td>
                                        @if ($profile->payment_type === 'pix')
                                            <span class="badge badge-info">PIX</span>
                                        @else
                                            <span class="badge badge-primary">Conta bancária</span>
                                        @endif
                                    </td>
                                    <td class="font-mono text-xs">
                                        @if ($profile->payment_type === 'pix')
                                            {{ ucfirst($profile->pix_key_type) }}: {{ $profile->pix_key }}
                                        @else
                                            {{ $profile->bank_name }} ({{ $profile->bank_code }})<br>
                                            Ag: {{ $profile->agency }} / Cc: {{ $profile->account_number }}-{{ $profile->account_digit }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $profile->holder_name }}<br>
                                        <span class="text-xs text-base-content/60">CPF: {{ $profile->holder_cpf }}</span>
                                    </td>
                                    <td>
                                        @if ($profile->is_verified)
                                            <span class="badge badge-success">Aprovado</span>
                                            @if ($profile->verified_at)
                                                <div class="text-xs text-base-content/60">
                                                    em {{ $profile->verified_at->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge badge-warning">Pendente</span>
                                        @endif
                                    </td>
                                    <td class="flex gap-1">
                                        @if (!$profile->is_verified)
                                            <button type="button" wire:click="approve({{ $profile->id }})"
                                                class="btn btn-success btn-xs">
                                                Aprovar
                                            </button>
                                        @else
                                            <button type="button" wire:click="revoke({{ $profile->id }})"
                                                class="btn btn-warning btn-xs"
                                                wire:confirm="Reverter aprovação dos dados bancários deste especialista?">
                                                Reverter
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $profiles->links() }}
                </div>
            @endif
        </x-card-body>
    </x-card>
</div>
