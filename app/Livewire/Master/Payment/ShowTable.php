<?php

namespace App\Livewire\Master\Payment;

use App\Models\{Appointment, Payment, Plan};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

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
        return Payment::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tipo_formatted', function (Payment $model) {
                return match ($model->payable_type) {
                    Appointment::class => 'Sessão',
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
                    'paid'    => 'Pago',
                    'pending' => 'Pendente',
                    'failed'  => 'Falhou',
                    default   => (string) $model->status,
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
            Column::make('Tipo', 'tipo_formatted')->searchable()->sortable(),
            Column::make('Método', 'payment_method_formatted', 'payment_method')->sortable(),
            Column::make('Status', 'status_formatted', 'status')->sortable(),
            Column::make('Valor', 'amount_formatted', 'amount')->sortable(),
            Column::make('Pago em', 'paid_at_formatted', 'paid_at')->sortable(),
            Column::make('Cadastrado em', 'created_at_formatted', 'created_at')->sortable(),
            Column::action('Ações'),
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
