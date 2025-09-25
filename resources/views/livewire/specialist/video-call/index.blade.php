<div>
    @if (session('error'))
        <div class="alert alert-error mb-6">
            <x-carbon-close-filled class="w-6 h-6" />
            <div>
                <h3 class="font-bold">Sala Encerrada</h3>
                <div class="text-sm">{{ session('error') }}</div>
            </div>
        </div>
    @endif

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
                            class="input input-bordered w-full" pattern="[A-Z0-9]{4}-[A-Z0-9]{4}" />
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">
                        <x-carbon-login class="w-4 h-4" />
                        Entrar
                    </button>
                </form>
                <x-text class="text-sm mt-2">Use código no formato <strong>XXXX-XXXX</strong>.</x-text>
            </x-card-body>
        </x-card>

        @if ($openRooms->count() > 0)
            <x-card class="md:ml-8 mt-6 md:mt-0">
                <x-card-body>
                    <x-card-title>Salas ativas</x-card-title>
                    <div class="overflow-x-auto mt-4">
                        <table class="table table-zebra table-sm w-full">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Situação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($openRooms as $room)
                                    <tr>
                                        <td class="font-mono">{{ $room->code }}</td>
                                        <td>
                                            <div class="badge badge-success badge-soft badge-sm">
                                                {{ strtoupper($room->status) }}
                                            </div>
                                        </td>
                                        <td class="flex flex-wrap space-x-2 space-y-2">
                                            <a href="{{ route('specialist.videocall.show', $room->code) }}"
                                                wire:navigate class="btn btn-info btn-sm">
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
        @endif
    </div>



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

    @if ($openRooms->count() === 0 && $closedRooms->count() === 0)
        <div class="grid grid-cols-1 md:grid-cols-2 mt-8">
            <x-card>
                <x-card-body>
                    <div class="text-base-content/20 mb-4">
                        <x-carbon-video-chat class="w-8" />
                    </div>
                    <x-card-title>Nenhuma sala encontrada</x-card-title>
                    <x-text>Crie sua primeira sala de videoconferência ou entre em uma sala existente.</x-text>
                </x-card-body>
            </x-card>
        </div>
    @endif
</div>
