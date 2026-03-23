<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireUnpaidAppointmentsCommand extends Command
{
    protected $signature = 'appointments:expire-unpaid';

    protected $description = 'Cancela agendamentos não pagos após 30 minutos';

    public function handle()
    {
        $now = Carbon::now();

        $this->info("Buscando agendamentos não pagos... ({$now->format('Y-m-d H:i:s')})");

        $expiredAppointments = Appointment::where('status', 'scheduled')
            ->where('created_at', '<=', $now->copy()->subMinutes(30))
            ->whereDoesntHave('payment', function ($query) {
                $query->where('status', 'paid');
            })
            ->get();

        $cancelledCount = 0;

        if ($expiredAppointments->isEmpty()) {
            $this->info('Nenhum agendamento expirado encontrado.');

            return Command::SUCCESS;
        }

        $this->info("Encontrados {$expiredAppointments->count()} agendamentos para expirar:");

        foreach ($expiredAppointments as $appointment) {
            try {
                $appointment->update(['status' => 'cancelled']);

                $this->line("  Cancelado: #{$appointment->id} - criado em {$appointment->created_at}");

                Log::info('Agendamento expirado por falta de pagamento', [
                    'appointment_id' => $appointment->id,
                    'user_id'        => $appointment->user_id,
                    'specialist_id'  => $appointment->specialist_id,
                    'created_at'     => $appointment->created_at,
                    'expired_at'     => $now,
                ]);

                $cancelledCount++;
            } catch (\Exception $e) {
                $this->error("  Erro ao cancelar #{$appointment->id}: {$e->getMessage()}");

                Log::error('Erro ao expirar agendamento', [
                    'appointment_id' => $appointment->id,
                    'error'          => $e->getMessage(),
                ]);
            }
        }

        $this->info("Resultado: {$cancelledCount} agendamentos expirados.");

        return Command::SUCCESS;
    }
}
