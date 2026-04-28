<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-document class="w-8 text-info" />
            Relatório de Baixas Manuais
        </x-title>
        <x-subtitle>
            Pagamentos que tiveram baixa registrada manualmente pelo administrador.
        </x-subtitle>
    </x-heading>

    <x-card class="mb-4">
        <x-card-body>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <x-form-group>
                    <x-label>De</x-label>
                    <x-input type="date" wire:model="from" />
                </x-form-group>

                <x-form-group>
                    <x-label>Até</x-label>
                    <x-input type="date" wire:model="to" />
                </x-form-group>

                <x-button type="button" wire:click="applyFilter" class="btn btn-info">
                    Aplicar filtro
                </x-button>

                <div class="text-right">
                    <div class="text-xs text-base-content/60">Total no período</div>
                    <div class="text-xl font-bold text-success">
                        R$ {{ number_format($this->totalAmount, 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </x-card-body>
    </x-card>

    <x-card>
        <x-card-body>
            @if ($payments->isEmpty())
                <div class="text-center py-8 text-base-content/60">
                    Nenhuma baixa manual no período.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Pagamento</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Baixado em</th>
                                <th>Por</th>
                                <th>Observação</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $p)
                                <tr>
                                    <td class="font-mono">#{{ $p->id }}</td>
                                    <td>{{ class_basename($p->payable_type ?? '—') }}</td>
                                    <td class="font-mono">R$ {{ number_format((float) $p->amount, 2, ',', '.') }}</td>
                                    <td>{{ $p->manual_paid_at?->format('d/m/Y H:i') }}</td>
                                    <td>Master #{{ $p->manual_paid_by_master_id ?? '—' }}</td>
                                    <td class="text-xs">{{ $p->manual_paid_note }}</td>
                                    <td>
                                        <a wire:navigate
                                            href="{{ route('master.payment.show', ['payment' => $p->id]) }}"
                                            class="btn btn-info btn-xs">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $payments->links() }}</div>
            @endif
        </x-card-body>
    </x-card>
</div>
