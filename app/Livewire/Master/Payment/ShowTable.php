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
                return $model->company?->name ?? '—';
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
                    'credit_card' => 'Cartão de Crédito',
                    'pix'         => 'Pix',
                    default       => (string) $model->payment_method,
                };
            })
            ->add('status_formatted', function (Payment $model) {
                return match ($model->status) {
                    'paid'            => '<span class="badge badge-success badge-sm">Pago</span>',
                    'pending_payment' => '<span class="badge badge-warning badge-sm">Pendente</span>',
                    'failed'          => '<span class="badge badge-error badge-sm">Falhou</span>',
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
            Column::make('Empresa', 'company_name')->searchable(),
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
            Filter::select('tipo_formatted', 'payable_type')->dataSource([
                ['id' => Appointment::class, 'name' => 'Sessão'],
                ['id' => Subscribe::class,   'name' => 'Assinatura'],
                ['id' => Plan::class,        'name' => 'Plano'],
            ])->optionLabel('name')->optionValue('id'),
            Filter::select('status_formatted', 'status')->dataSource([
                ['id' => 'paid',            'name' => 'Pago'],
                ['id' => 'pending_payment', 'name' => 'Pendente'],
                ['id' => 'failed',          'name' => 'Falhou'],
            ])->optionLabel('name')->optionValue('id'),
            Filter::select('payment_method_formatted', 'payment_method')->dataSource([
                ['id' => 'credit_card', 'name' => 'Cartão de Crédito'],
                ['id' => 'pix',         'name' => 'Pix'],
            ])->optionLabel('name')->optionValue('id'),
            Filter::datepicker('paid_at_formatted', 'paid_at'),
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
}
