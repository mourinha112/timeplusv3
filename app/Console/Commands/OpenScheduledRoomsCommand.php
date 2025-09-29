<?php

namespace App\Console\Commands;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OpenScheduledRoomsCommand extends Command
{
    protected $signature = 'rooms:open-scheduled';

    protected $description = 'Abre salas de videochamada 10 minutos antes do horário agendado';

    public function handle()
    {
        $startTime = microtime(true);
        $now       = Carbon::now();

        $this->info("🔍 Buscando salas para abrir... ({$now->format('Y-m-d H:i:s')})");

        // Buscar salas fechadas que devem ser abertas (10min antes até 1h depois)
        $roomsToOpen = Room::where('status', 'closed')
            ->whereNotNull('appointment_id')
            ->whereHas('appointment', function ($query) use ($now) {
                $query->whereRaw("
                    CONCAT(appointment_date, ' ', appointment_time) - INTERVAL 10 MINUTE <= ?
                    AND CONCAT(appointment_date, ' ', appointment_time) + INTERVAL 1 HOUR > ?
                ", [$now, $now]);
            })
            ->with('appointment')
            ->get();

        $openedCount = 0;
        $errors      = 0;

        if ($roomsToOpen->isEmpty()) {
            $this->info("✅ Nenhuma sala para abrir encontrada.");

            return Command::SUCCESS;
        }

        $this->info("📋 Encontradas {$roomsToOpen->count()} salas para abrir:");

        foreach ($roomsToOpen as $room) {
            try {
                // Abrir a sala
                $room->update(['status' => 'open']);

                $appointmentTime = $room->appointment->appointment_date . ' ' . $room->appointment->appointment_time;
                $this->line("  ✅ {$room->code} - Aberta (consulta: {$appointmentTime})");

                Log::info('Sala aberta automaticamente', [
                    'room_id'              => $room->id,
                    'room_code'            => $room->code,
                    'appointment_id'       => $room->appointment_id,
                    'appointment_datetime' => $appointmentTime,
                    'opened_at'            => $now,
                ]);

                $openedCount++;

            } catch (\Exception $e) {
                $this->error("  ❌ {$room->code} - Erro: {$e->getMessage()}");

                Log::error('Erro ao abrir sala automaticamente', [
                    'room_id'   => $room->id,
                    'room_code' => $room->code,
                    'error'     => $e->getMessage(),
                ]);

                $errors++;
            }
        }

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        $this->info("🎯 Resultado: {$openedCount} abertas, {$errors} erros em {$executionTime}ms");

        if ($openedCount > 0) {
            Log::info('Comando rooms:open-scheduled executado', [
                'opened_count'      => $openedCount,
                'errors'            => $errors,
                'execution_time_ms' => $executionTime,
            ]);
        }

        return Command::SUCCESS;
    }
}
