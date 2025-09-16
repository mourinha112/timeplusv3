<?php

namespace Database\Seeders;

use App\Models\{Payment, Plan, User};
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Nenhum usuário encontrado. Execute o seeder de User primeiro.');

            return;
        }

        // Primeiro, criar alguns planos se não existirem
        $plans = [
            ['name' => 'Plano Básico', 'price' => 99.90, 'duration_days' => 30, 'discount_percentage' => 0],
            ['name' => 'Plano Premium', 'price' => 179.90, 'duration_days' => 30, 'discount_percentage' => 10],
            ['name' => 'Plano Anual', 'price' => 1199.90, 'duration_days' => 365, 'discount_percentage' => 20],
            ['name' => 'Plano Família', 'price' => 249.90, 'duration_days' => 30, 'discount_percentage' => 15],
        ];

        foreach ($plans as $planData) {
            Plan::firstOrCreate(['name' => $planData['name']], $planData);
        }

        $plansCreated = Plan::all();

        // Criar pagamentos de planos dos últimos 12 meses
        for ($i = 0; $i < 80; $i++) {
            $randomDate = Carbon::now()->subDays(rand(0, 365));
            $plan       = $plansCreated->random();

            Payment::create([
                'gateway_order_id'  => 'ORDER_' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'gateway_charge_id' => 'CHARGE_' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'payable_type'      => Plan::class,
                'payable_id'        => $plan->id,
                'amount'            => $plan->price,
                'payment_method'    => collect(['credit_card', 'pix'])->random(),
                'status'            => collect(['paid', 'pending', 'canceled', 'refunded', 'failed'])->random(),
                'paid_at'           => rand(0, 100) > 20 ? $randomDate : null, // 80% dos pagamentos são pagos
                'expires_at'        => $randomDate->copy()->addDays(7),
                'currency'          => 'BRL',
                'description'       => 'Pagamento do ' . $plan->name,
                'metadata'          => [
                    'user_id'          => $users->random()->id,
                    'plan_name'        => $plan->name,
                    'discount_applied' => $plan->discount_percentage > 0,
                ],
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }

        $this->command->info('80 payments de planos criados com sucesso!');
    }
}
