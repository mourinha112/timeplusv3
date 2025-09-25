<div class="space-y-4">
    <x-heading>
        <x-title>Videochamadas</x-title>
        <x-subtitle>Entre em sua sessão.</x-subtitle>
    </x-heading>

    <div class="grid grid-cols-1 md:grid-cols-2 my-8">

        <x-card>
            <x-card-body>
                <x-card-title>Entrar em sala existente</x-card-title>
                <form wire:submit="joinRoom" class="mt-4 space-y-4">
                    <div>
                        <x-input wire:model="roomCode" type="text" placeholder="XXXX-XXXX"
                            class="input input-bordered w-full uppercase" pattern="[A-Z0-9]{4}-[A-Z0-9]{4}" />
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">
                        <x-carbon-login class="w-4 h-4" />
                        Entrar
                    </button>
                </form>
                <x-text class="text-sm mt-2">Use código no formato <strong>XXXX-XXXX</strong>.</x-text>
            </x-card-body>
        </x-card>
    </div>

    @if ($openRooms->count() > 0)
        <div class="max-w-3xl">
            <x-card>
                <x-card-body>
                    <x-card-title>Salas ativas</x-card-title>
                    <div class="overflow-x-auto mt-4">
                        <table class="table table-zebra table-sm w-full">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($openRooms as $room)
                                    <tr>
                                        <td class="font-mono">{{ $room->code }}</td>
                                        <td>
                                            <div class="badge badge-success badge-sm">
                                                {{ strtoupper($room->status) }}
                                            </div>
                                        </td>
                                        <td class="space-x-2">
                                            <a href="{{ route('user.videocall.show', $room->code) }}" wire:navigate
                                                class="btn btn-primary btn-sm">
                                                <x-carbon-video class="w-4 h-4" />
                                                Abrir
                                            </a>
                                            <button wire:click="closeRoom('{{ $room->code }}')"
                                                wire:confirm="Encerrar sala {{ $room->code }}?"
                                                class="btn btn-error btn-sm">
                                                <x-carbon-close class="w-4 h-4" />
                                                Encerrar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card-body>
            </x-card>
        </div>
    @endif

    @if ($scheduledRooms->count() > 0)
        <div class="max-w-3xl">
            <x-card>
                <x-card-body>
                    <x-card-title>Salas agendadas</x-card-title>
                    <div class="overflow-x-auto mt-4">
                        <table class="table table-zebra table-sm w-full">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Consulta</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($scheduledRooms as $room)
                                    <tr>
                                        <td class="font-mono">{{ $room->code }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($room->appointment->appointment_date)->format('d/m/Y') }}
                                            às {{ \Carbon\Carbon::parse($room->appointment->appointment_time)->format('H:i') }}
                                        </td>
                                        <td>
                                            @php
                                                $appointmentDateTime = \Carbon\Carbon::parse($room->appointment->appointment_date . ' ' . $room->appointment->appointment_time);
                                                $openTime = $appointmentDateTime->subMinutes(10);
                                                $now = \Carbon\Carbon::now();
                                            @endphp
                                            @if ($now >= $openTime)
                                                <span class="badge badge-info badge-sm">
                                                    <x-carbon-time class="w-3 h-3 mr-1" />
                                                    Disponível agora
                                                </span>
                                            @else
                                                @php
                                                    $diffInMinutes = $now->diffInMinutes($openTime, false);
                                                    if ($diffInMinutes >= 60) {
                                                        $hours = intval($diffInMinutes / 60);
                                                        $minutes = $diffInMinutes % 60;
                                                        $timeText = "Disponível em {$hours}h" . ($minutes > 0 ? " {$minutes}min" : "");
                                                    } else {
                                                        $timeText = "Disponível em {$diffInMinutes}min";
                                                    }
                                                @endphp
                                                <span class="badge badge-warning badge-sm">
                                                    <x-carbon-time class="w-3 h-3 mr-1" />
                                                    {{ $timeText }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card-body>
            </x-card>
        </div>
    @endif

    @if ($closedRooms->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2">
            <x-card>
                <x-card-body>
                    <x-card-title>Encerradas recentemente</x-card-title>
                    <div class="overflow-x-auto mt-4">
                        <table class="table table-zebra table-sm w-full">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Encerrada em</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($closedRooms as $room)
                                    <tr>
                                        <td class="font-mono">{{ $room->code }}</td>
                                        <td>{{ $room->closed_at?->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card-body>
            </x-card>
        </div>
    @endif

    @if ($openRooms->count() === 0 && $scheduledRooms->count() === 0 && $closedRooms->count() === 0)
        <x-card>
            <x-card-body class="text-center py-12">
                <div class="text-base-content/20 mb-4">
                    <x-carbon-video-chat class="w-16 h-16 mx-auto" />
                </div>
                <x-card-title>Nenhuma sala encontrada</x-card-title>
                <x-text>Crie sua primeira sala de videoconferência ou entre em uma sala existente.</x-text>
            </x-card-body>
        </x-card>
    @endif
</div>
