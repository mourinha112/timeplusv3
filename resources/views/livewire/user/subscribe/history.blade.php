<div>
    <div class="space-y-3 mb-8">
        <x-title>Histórico de assinaturas</x-title>
        <x-subtitle>Veja os detalhes dos seus assinaturas anteriores.</x-subtitle>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Plano</th>
                        <th>Recorrência</th>
                        <th>Situação</th>
                        <th>Data de pagamento</th>
                        <th>Data de término</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->subscribes as $subscribe)
                    <tr>
                        <th>{{ $subscribe->id }}</th>
                        <td>{{ $subscribe->plan->name }}</td>
                        <td>{{ $subscribe->plan->duration_days }} dias</td>
                        <td>
                            @if($subscribe->end_date > now() && !$subscribe->cancelled_date)
                            <span class="badge badge-success badge-sm">Ativo</span>
                            @elseif($subscribe->cancelled_date)
                            <span class="badge badge-error badge-sm">Cancelado</span>
                            @else
                            <span class="badge badge-warning badge-sm">Expirado</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($subscribe->start_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($subscribe->end_date)->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
