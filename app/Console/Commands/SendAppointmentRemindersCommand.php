<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\Specialist\AppointmentReminderNotification as SpecialistReminder;
use App\Notifications\User\AppointmentReminderNotification as UserReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAppointmentRemindersCommand extends Command
{
    protected $signature = 'appointments:send-reminders';

    protected $description = 'Envia lembretes por email 1 hora antes da sessão';

    public function handle()
    {
        $now  = Carbon::now();
        $from = $now->copy()->addMinutes(55);
        $to   = $now->copy()->addMinutes(65);

        $this->info("Buscando sessões entre {$from->format('H:i')} e {$to->format('H:i')}...");

        $appointments = Appointment::where('status', 'scheduled')
            ->where('appointment_date', $now->toDateString())
            ->whereHas('payment', function ($query) {
                $query->where('status', 'paid');
            })
            ->with(['user', 'specialist'])
            ->get()
            ->filter(function ($appointment) use ($from, $to) {
                $appointmentTime = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);

                return $appointmentTime->between($from, $to);
            });

        if ($appointments->isEmpty()) {
            $this->info('Nenhum lembrete para enviar.');

            return Command::SUCCESS;
        }

        $sent = 0;

        foreach ($appointments as $appointment) {
            try {
                $appointment->user->notify(new UserReminder($appointment));
                $appointment->specialist->notify(new SpecialistReminder($appointment));

                $this->line("  Lembrete enviado: #{$appointment->id} - {$appointment->appointment_time}");

                Log::info('Lembrete de sessão enviado', [
                    'appointment_id' => $appointment->id,
                    'user_id'        => $appointment->user_id,
                    'specialist_id'  => $appointment->specialist_id,
                ]);

                $sent++;
            } catch (\Exception $e) {
                $this->error("  Erro ao enviar lembrete #{$appointment->id}: {$e->getMessage()}");

                Log::error('Erro ao enviar lembrete', [
                    'appointment_id' => $appointment->id,
                    'error'          => $e->getMessage(),
                ]);
            }
        }

        $this->info("Resultado: {$sent} lembretes enviados.");

        return Command::SUCCESS;
    }
}
