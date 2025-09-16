<?php

namespace Database\Seeders;

use App\Models\{Company, CompanyPlan, Plan};
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name'      => 'Empresa Dev',
                'cnpj'      => '12345678000166',
                'email'     => 'devvv@empresa.com',
                'password'  => bcrypt('password'),
                'phone'     => '11999887766',
                'address'   => 'Rua de Desenvolvimento, 123',
                'city'      => 'São Paulo',
                'state'     => 'SP',
                'zip_code'  => '01234567',
                'is_active' => true,
            ],
        ];

        foreach ($companies as $companyData) {
            $company = Company::create($companyData);

            // Criar plano para algumas empresas
            if ($company->is_active) {
                $plan = Plan::inRandomOrder()->first();

                if ($plan) {
                    CompanyPlan::create([
                        'company_id'          => $company->id,
                        'plan_id'             => $plan->id,
                        'duration_days'       => $plan->duration_days,
                        'price'               => $plan->price,
                        'discount_percentage' => rand(0, 20),
                    ]);
                }
            }
        }

        echo "Empresas criadas com sucesso!

Credenciais de desenvolvimento:
Email: dev@empresa.com
Senha: password

Outras empresas:
Email: admin@inovacaodigital.com.br (Senha: 123456)
Email: contato@consultoriaempresarial.com.br (Senha: 123456)
Email: hello@startuphub.com.br (Senha: 123456)
";
    }
}
