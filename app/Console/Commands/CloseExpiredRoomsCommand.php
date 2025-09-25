<?php

namespace App\Console\Commands;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloseExpiredRoomsCommand extends Command
{
    protected $signature = 'rooms:close-expired';
    protected $description = 'Fecha salas de videochamada que passaram do horário programado';

    public function handle()
    {
        $startTime = microtime(true);
        $now = Carbon::now();

        $this->info("🔍 Buscando salas expiradas... ({$now->format('Y-m-d H:i:s')})");

        $expiredRooms = Room::where('status', 'open')
            ->whereNotNull('appointment_id')
            ->whereHas('appointment', function ($query) use ($now) {
                $query->whereRaw("
                    CONCAT(appointment_date, ' ', appointment_time) + INTERVAL 1 HOUR <= ?
                ", [$now]);
            })
            ->with('appointment')  // Carregar relacionamento
            ->get();

        $closedCount = 0;
        $errors = 0;

        if ($expiredRooms->isEmpty()) {
            $this->info("✅ Nenhuma sala expirada encontrada.");
            return Command::SUCCESS;
        }

        $this->info("📋 Encontradas {$expiredRooms->count()} salas para fechar:");

        foreach ($expiredRooms as $room) {
            try {
                // Fechar a sala
                $room->update([
                    'status' => 'closed',
                    'closed_at' => $now,
                ]);

                // Se tem appointment vinculado, marcar como completed
                if ($room->appointment && $room->appointment->status !== 'completed') {
                    $room->appointment->update([
                        'status' => 'completed'
                    ]);

                    $this->line("  ✅ {$room->code} - Fechada e sessão #{$room->appointment->id} marcada como concluída");
                } else {
                    $appointmentTime = $room->appointment->appointment_date . ' ' . $room->appointment->appointment_time;
                    $this->line("  ✅ {$room->code} - Fechada (consulta: {$appointmentTime})");
                }

                $appointmentDateTime = Carbon::parse($room->appointment->appointment_date . ' ' . $room->appointment->appointment_time);
                $scheduledCloseAt = $appointmentDateTime->addHour();

                Log::info('Sala fechada automaticamente', [
                    'room_id' => $room->id,
                    'room_code' => $room->code,
                    'appointment_id' => $room->appointment_id,
                    'appointment_status_updated' => $room->appointment ? 'completed' : 'no_appointment',
                    'appointment_datetime' => $room->appointment->appointment_date . ' ' . $room->appointment->appointment_time,
                    'scheduled_close_at' => $scheduledCloseAt,
                    'closed_at' => $now,
                    'minutes_late' => $now->diffInMinutes($scheduledCloseAt),
                ]);

                $closedCount++;

            } catch (\Exception $e) {
                $this->error("  ❌ {$room->code} - Erro: {$e->getMessage()}");

                Log::error('Erro ao fechar sala automaticamente', [
                    'room_id' => $room->id,
                    'room_code' => $room->code,
                    'error' => $e->getMessage(),
                ]);

                $errors++;
            }
        }

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        $this->info("🎯 Resultado: {$closedCount} fechadas, {$errors} erros em {$executionTime}ms");

        if ($closedCount > 0) {
            Log::info('Comando rooms:close-expired executado', [
                'closed_count' => $closedCount,
                'errors' => $errors,
                'execution_time_ms' => $executionTime,
            ]);
        }

        return Command::SUCCESS;
    }
}