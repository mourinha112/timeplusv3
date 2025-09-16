<?php

namespace Database\Seeders;

use App\Models\{Appointment, Specialist, User};
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $users       = User::all();
        $specialists = Specialist::all();

        if ($users->isEmpty() || $specialists->isEmpty()) {
            $this->command->warn('Nenhum usuário ou especialista encontrado. Execute os seeders de User e Specialist primeiro.');

            return;
        }

        // Criar appointments dos últimos 12 meses com distribuição variada
        $existingSlots = [];
        $created       = 0;
        $attempts      = 0;

        while ($created < 150 && $attempts < 300) {
            $attempts++;
            $randomDate = Carbon::now()->subDays(rand(0, 365));

            // Gerar horário
            $hour     = rand(8, 18);
            $minute   = rand(0, 1) * 30;
            $timeSlot = sprintf('%02d:%02d', $hour, $minute);
            $dateTime = $randomDate->format('Y-m-d') . ' ' . $timeSlot;

            // Verificar se o slot já existe
            if (in_array($dateTime, $existingSlots)) {
                continue;
            }

            try {
                Appointment::create([
                    'user_id'          => $users->random()->id,
                    'specialist_id'    => $specialists->random()->id,
                    'total_value'      => rand(80, 300), // Valores entre R$ 80 e R$ 300
                    'appointment_date' => $randomDate->format('Y-m-d'),
                    'appointment_time' => $timeSlot,
                    'status'           => collect(['scheduled', 'completed', 'cancelled'])->random(),
                    'notes'            => 'Sessão de ' . collect(['fisioterapia', 'psicologia', 'nutrição', 'personal training'])->random(),
                    'created_at'       => $randomDate,
                    'updated_at'       => $randomDate,
                ]);

                $existingSlots[] = $dateTime;
                $created++;
            } catch (\Exception $e) {
                // Se houver erro (ex: chave duplicada), continua tentando
                continue;
            }
        }

        $this->command->info("$created appointments criados com sucesso!");
    }
}
