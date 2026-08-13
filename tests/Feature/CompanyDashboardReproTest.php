<?php

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeCompany(): Company
{
    return Company::create([
        'name'      => 'Empresa Teste',
        'cnpj'      => '12.345.678/0001-90',
        'email'     => 'empresa@teste.com',
        'password'  => 'secret123',
        'phone'     => '11999999999',
        'address'   => 'Rua X, 123',
        'city'      => 'São Paulo',
        'state'     => 'SP',
        'zip_code'  => '01000-000',
        'is_active' => true,
    ]);
}

it('renders the company dashboard', function () {
    $company = makeCompany();

    $response = $this->withoutVite()->actingAs($company, 'company')->get('/empresa/painel');

    $response->assertOk();
});

it('renders the dashboard with plan, employee and payments', function () {
    $company = makeCompany();

    $plan = $company->companyPlans()->create([
        'name'                => 'Plano Teste',
        'billing_model'       => \App\Models\CompanyPlan::BILLING_CREDIT_PACK,
        'monthly_credits'     => 10,
        'discount_percentage' => 0,
        'is_active'           => true,
        'contracted_at'       => now(),
    ]);

    $user = \App\Models\User::factory()->create();
    $company->employees()->attach($user->id, [
        'company_plan_id' => $plan->id,
        'department'      => 'RH',
        'is_active'       => true,
    ]);

    $company->payments()->create([
        'amount'         => 300,
        'payment_method' => 'pix',
        'status'         => 'paid',
        'currency'       => 'BRL',
        'company_id'     => $company->id,
    ]);

    $response = $this->withoutVite()->actingAs($company, 'company')->get('/empresa/painel');

    $response->assertOk();
});
