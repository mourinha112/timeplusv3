<?php

namespace App\Livewire\Specialist\VideoCall;

use App\Models\Room;
use App\Services\JitsiService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Videochamadas', 'guard' => 'specialist'])]
class Index extends Component
{

    public $roomCode = '';

    protected $rules = [
        'roomCode' => 'required|regex:/^[A-Z0-9]{4}-[A-Z0-9]{4}$/',
    ];

    public function createRoom(JitsiService $jitsiService)
    {
        $code = $jitsiService->createRoomCode();

        $room = Room::create([
            'code' => $code,
            'status' => 'open',
            'created_by' => auth('specialist')->id(),
        ]);

        LivewireAlert::title('Sala Criada')
            ->text('Sala criada com sucesso!')
            ->success()
            ->show();

        return $this->redirect(route('specialist.videocall.show', ['code' => $room->code]), navigate: true);
    }

    public function joinRoom()
    {
        $this->validate();

        $room = Room::where('code', strtoupper($this->roomCode))->first();

        if (!$room) {
            LivewireAlert::title('Sala Não Encontrada')
                ->text('Sala não encontrada!')
                ->error()
                ->show();
            return;
        }

        if ($room->status !== 'open') {
            LivewireAlert::title('Sala Encerrada')
                ->text('Esta sala foi encerrada!')
                ->error()
                ->show();
            return;
        }

        return $this->redirect(route('specialist.videocall.show', ['code' => $room->code]), navigate: true);
    }

    public function closeRoom($roomCode)
    {
        // Especialistas podem fechar qualquer sala que tenham acesso (suas próprias ou de appointments)
        $room = Room::where('code', $roomCode)
            ->where(function($query) {
                $query->where('created_by', auth('specialist')->id())
                      ->orWhereHas('appointment', function($q) {
                          $q->where('specialist_id', auth('specialist')->id());
                      });
            })
            ->with('appointment') // Carregar relacionamento
            ->first();

        if (!$room) {
            LivewireAlert::title('Erro')
                ->text('Sala não encontrada ou você não tem permissão para encerrá-la!')
                ->error()
                ->show();
            return;
        }

        if ($room->status === 'closed') {
            LivewireAlert::title('Aviso')
                ->text('Sala já foi encerrada.')
                ->info()
                ->show();
            return;
        }

        $room->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        // Se tem appointment vinculado, marcar como completed
        $appointmentUpdated = false;
        if ($room->appointment && $room->appointment->status !== 'completed') {
            $room->appointment->update(['status' => 'completed']);
            $appointmentUpdated = true;
        }

        // Log do fechamento manual
        Log::info('Sala fechada manualmente pelo especialista', [
            'room_id' => $room->id,
            'room_code' => $room->code,
            'specialist_id' => auth('specialist')->id(),
            'appointment_id' => $room->appointment_id,
            'appointment_status_updated' => $appointmentUpdated ? 'completed' : 'no_change',
            'closed_at' => now(),
            'type' => 'manual_close_by_specialist'
        ]);

        $message = $appointmentUpdated
            ? "Sala {$room->code} encerrada e sessão marcada como concluída!"
            : "Sala {$room->code} encerrada com sucesso!";

        LivewireAlert::title('Sala Encerrada')
            ->text($message)
            ->success()
            ->show();
    }

    public function render()
    {
        $openRooms = Room::open()
            ->where('created_by', auth('specialist')->id())
            ->latest()
            ->get();

        $closedRooms = Room::closed()
            ->where('created_by', auth('specialist')->id())
            ->latest('closed_at')
            ->limit(20)
            ->get();

        return view('livewire.specialist.video-call.index', [
            'openRooms' => $openRooms,
            'closedRooms' => $closedRooms,
        ]);
    }
}