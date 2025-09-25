<?php

namespace App\Livewire\User\VideoCall;

use App\Models\Room;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Videochamadas', 'guard' => 'user'])]
class Index extends Component
{

    public $roomCode = '';

    protected $rules = [
        'roomCode' => 'required|regex:/^[A-Z0-9]{4}-[A-Z0-9]{4}$/',
    ];


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

        return $this->redirect(route('user.videocall.show', ['code' => $room->code]), navigate: true);
    }

    public function closeRoom($roomCode)
    {
        $room = Room::where('code', $roomCode)
            ->where('created_by', auth('user')->id())
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
        Log::info('Sala fechada manualmente pelo usuário', [
            'room_id' => $room->id,
            'room_code' => $room->code,
            'user_id' => auth('user')->id(),
            'appointment_id' => $room->appointment_id,
            'appointment_status_updated' => $appointmentUpdated ? 'completed' : 'no_change',
            'closed_at' => now(),
            'type' => 'manual_close_by_user'
        ]);

        $message = $appointmentUpdated
            ? "Sala {$room->code} encerrada e sessão marcada como concluída!"
            : "Sala {$room->code} encerrada com sucesso!";

        LivewireAlert::title('Sala Encerrada')
            ->text($message)
            ->success()
            ->show();
    }

    public function getRoomOpenTime($room)
    {
        if (!$room->appointment) {
            return null;
        }

        $appointmentDateTime = \Carbon\Carbon::parse($room->appointment->appointment_date . ' ' . $room->appointment->appointment_time);
        return $appointmentDateTime->subMinutes(10); // 10min antes da consulta
    }

    public function render()
    {
        $openRooms = Room::open()
            ->where('created_by', auth('user')->id())
            ->latest()
            ->get();

        $scheduledRooms = Room::where('status', 'closed')
            ->whereNotNull('appointment_id')
            ->where('created_by', auth('user')->id())
            ->with('appointment')
            ->latest()
            ->get();

        $closedRooms = Room::closed()
            ->whereNull('appointment_id') // Apenas salas manuais
            ->orWhere(function($query) {
                $query->where('status', 'closed')
                      ->whereHas('appointment', function($subQuery) {
                          $subQuery->where('status', 'completed');
                      });
            })
            ->where('created_by', auth('user')->id())
            ->latest('closed_at')
            ->limit(20)
            ->get();

        return view('livewire.user.video-call.index', [
            'openRooms' => $openRooms,
            'scheduledRooms' => $scheduledRooms,
            'closedRooms' => $closedRooms,
        ]);
    }
}