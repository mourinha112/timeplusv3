<?php

namespace App\Livewire\Master\Payment;

use App\Models\{Appointment, Payment, Plan, Subscribe};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\{Filter, PowerGrid};

#[Layout('components.layouts.app', ['title' => 'Pagamentos', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.payment.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Payment::query()->with(['payable', 'company']);
    }

    public function relationSearch(): array
    {
        return [
            'company' => ['name', 'cnpj'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('user_name', function (Payment $model) {
                if ($model->payable_type === Appointment::class && $model->payable) {
                    return $model->payable->user?->name ?? '—';
                }
                if ($model->payable_type === Subscribe::class && $model->payable) {
                    return $model->payable->user?->name ?? '—';
                }
                return '—';
            })
            ->add('user_cpf', function (Payment $model) {
                if (in_array($model->payable_type, [Appointment::class, Subscribe::class], true) && $model->payable) {
                    return $model->payable->user?->cpf ?? '—';
                }
                return '—';
            })
            ->add('company_name', function (Payment $model) {
                return $this->resolveCompanyName($model);
            })
            ->add('tipo_formatted', function (Payment $model) {
                return match ($model->payable_type) {
                    Appointment::class => 'Sessão',
                    Subscribe::class   => 'Assinatura',
                    Plan::class        => 'Plano',
                    default            => class_basename((string) $model->payable_type),
                };
            })
            ->add('payment_method_formatted', function (Payment $model) {
                return match ($model->payment_method) {
                    'credit_card'    => 'Cartão de Crédito',
                    'credit_balance' => 'Saldo/Crédito',
                    'pix'            => 'Pix',
                    default          => (string) $model->payment_method,
                };
            })
            ->add('status_formatted', function (Payment $model) {
                return match ($model->status) {
                    'pending'         => '<span class="badge badge-ghost badge-sm">Criado</span>',
                    'order_created'   => '<span class="badge badge-info badge-sm">Pedido criado</span>',
                    'paid'            => '<span class="badge badge-success badge-sm">Pago</span>',
                    'pending_payment' => '<span class="badge badge-warning badge-sm">Pendente</span>',
                    'processing'      => '<span class="badge badge-info badge-sm">Processando</span>',
                    'failed'          => '<span class="badge badge-error badge-sm">Falhou</span>',
                    'canceled'        => '<span class="badge badge-error badge-sm">Cancelado</span>',
                    'refunded'        => '<span class="badge badge-neutral badge-sm">Estornado</span>',
                    'partial_refunded' => '<span class="badge badge-neutral badge-sm">Estorno parcial</span>',
                    default           => (string) $model->status,
                };
            })
            ->add('amount_formatted', fn (Payment $model) => 'R$ ' . number_format((float) $model->amount, 2, ',', '.'))
            ->add('paid_at_formatted', fn (Payment $model) => $model->paid_at ? Carbon::parse($model->paid_at)->format('d/m/Y H:i') : '-')
            ->add('created_at_formatted', fn (Payment $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Usuário', 'user_name'),
            Column::make('CPF', 'user_cpf'),
            Column::make('Empresa', 'company_name'),
            Column::make('Tipo', 'tipo_formatted', 'payable_type')->sortable(),
            Column::make('Método', 'payment_method_formatted', 'payment_method')->sortable(),
            Column::make('Status', 'status_formatted', 'status')->sortable(),
            Column::make('Valor', 'amount_formatted', 'amount')->sortable(),
            Column::make('Pago em', 'paid_at_formatted', 'paid_at')->sortable(),
            Column::action('Ações'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('payable_type', 'payable_type')->dataSource([
                ['id' => Appointment::class, 'name' => 'Sessão'],
                ['id' => Subscribe::class,   'name' => 'Assinatura'],
                ['id' => Plan::class,        'name' => 'Plano'],
            ])->optionLabel('name')->optionValue('id'),
            Filter::select('status', 'status')->dataSource([
                ['id' => 'pending',          'name' => 'Criado'],
                ['id' => 'order_created',    'name' => 'Pedido criado'],
                ['id' => 'paid',            'name' => 'Pago'],
                ['id' => 'pending_payment', 'name' => 'Pendente'],
                ['id' => 'processing',       'name' => 'Processando'],
                ['id' => 'failed',          'name' => 'Falhou'],
                ['id' => 'canceled',         'name' => 'Cancelado'],
                ['id' => 'refunded',         'name' => 'Estornado'],
                ['id' => 'partial_refunded', 'name' => 'Estorno parcial'],
            ])->optionLabel('name')->optionValue('id'),
            Filter::select('payment_method', 'payment_method')->dataSource([
                ['id' => 'credit_card',    'name' => 'Cartão de Crédito'],
                ['id' => 'pix',            'name' => 'Pix'],
                ['id' => 'credit_balance', 'name' => 'Saldo/Crédito'],
            ])->optionLabel('name')->optionValue('id'),
            Filter::datepicker('paid_at', 'paid_at'),
        ];
    }

    #[\Livewire\Attributes\On('master::payment-show')]
    public function show($rowId): void
    {
        $this->redirect(route('master.payment.show', ['payment' => $rowId]));
    }

    public function actions(Payment $row): array
    {
        return [
            Button::add('show')
                ->slot('Visualizar')
                ->id()
                ->class('btn btn-info btn-sm')
                ->dispatch('master::payment-show', ['rowId' => $row->id]),
        ];
    }

    private function resolveCompanyName(Payment $payment): string
    {
        if ($payment->company?->name) {
            return $payment->company->name;
        }

        $payable = $payment->payable;
        $user    = null;

        if ($payable instanceof Appointment || $payable instanceof Subscribe) {
            $user = $payable->user;
        }

        if (!$user) {
            return '—';
        }

        $company = $user->activeCompanies()->orderBy('companies.name')->first()
            ?? $user->companies()->orderBy('companies.name')->first();

        return $company?->name ?? '—';
    }
}
