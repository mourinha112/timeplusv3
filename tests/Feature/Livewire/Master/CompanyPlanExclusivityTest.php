<?php

use App\Facades\Asaas;
use App\Livewire\Master\Company\Show;
use App\Livewire\Master\CompanyPlan\Create;
use App\Models\{Company, CompanyPlan};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeCompanyForPlans(): Company
{
    return Company::create([
        'name'      => 'Empresa Planos',
        'cnpj'      => '12.345.678/0001-90',
        'email'     => 'planos@teste.com',
        'password'  => 'secret123',
        'phone'     => '11999999999',
        'address'   => 'Rua X, 123',
        'city'      => 'São Paulo',
        'state'     => 'SP',
        'zip_code'  => '01000-000',
        'is_active' => true,
    ]);
}

function makePlan(Company $company, string $model, bool $active = true): CompanyPlan
{
    return $company->companyPlans()->create([
        'name'                => 'Plano ' . $model,
        'billing_model'       => $model,
        'monthly_credits'     => $model === CompanyPlan::BILLING_CREDIT_PACK ? 10 : 0,
        'discount_percentage' => 0,
        'price_per_unit'      => 30,
        'is_active'           => $active,
        'contracted_at'       => now(),
    ]);
}

it('blocks creating a second plan while another is active', function () {
    $company = makeCompanyForPlans();
    makePlan($company, CompanyPlan::BILLING_PER_EMPLOYEE);

    Livewire::test(Create::class, ['company' => $company])
        ->set('name', 'Pacote de Créditos')
        ->set('billing_model', CompanyPlan::BILLING_CREDIT_PACK)
        ->set('monthly_credits', 10)
        ->call('save')
        ->assertHasErrors('billing_model');

    expect($company->companyPlans()->count())->toBe(1);
});

it('creates a credit pack plan, grants monthly credits and generates the first invoice', function () {
    $company = makeCompanyForPlans();

    Asaas::shouldReceive('customer->create')->andReturn(['id' => 'cus_test']);
    Asaas::shouldReceive('payment->createWithPix')->andReturn([
        'id'          => 'pay_test',
        'pix_key'     => 'chave-pix',
        'pix_qr_code' => 'qr-base64',
    ]);

    Livewire::test(Create::class, ['company' => $company])
        ->set('name', 'Pacote de Créditos')
        ->set('billing_model', CompanyPlan::BILLING_CREDIT_PACK)
        ->set('monthly_credits', 10)
        ->call('save')
        ->assertHasNoErrors();

    $plan = $company->companyPlans()->first();

    expect($plan)->not->toBeNull()
        ->and($plan->is_active)->toBeTrue()
        ->and($plan->creditBalances()->count())->toBe(1);

    $invoice = $company->payments()
        ->where('metadata->type', 'company_monthly_billing')
        ->first();

    expect($invoice)->not->toBeNull()
        ->and((float) $invoice->amount)->toBe(300.0)
        ->and($invoice->status)->toBe('pending_payment');
});

it('blocks reactivating a plan while another is active', function () {
    $company  = makeCompanyForPlans();
    makePlan($company, CompanyPlan::BILLING_PER_EMPLOYEE);
    $inactive = makePlan($company, CompanyPlan::BILLING_CREDIT_PACK, active: false);

    Livewire::test(Show::class, ['company' => $company])
        ->call('togglePlanStatus', $inactive->id);

    expect($inactive->fresh()->is_active)->toBeFalse();
});
