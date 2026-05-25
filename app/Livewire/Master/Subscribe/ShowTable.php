<?php

namespace App\Livewire\Master\Subscribe;

use App\Models\{Company, Plan, Subscribe};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\{Filter, PowerGrid};

#[Layout('components.layouts.app', ['title' => 'Assinaturas', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.subscribe.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Subscribe::query()
            ->join('users', 'subscribes.user_id', '=', 'users.id')
            ->join('plans', 'subscribes.plan_id', '=', 'plans.id')
            ->select('subscribes.*', 'users.name as user_name', 'users.cpf as user_cpf', 'plans.name as plan_name', 'plans.price as plan_price')
            ->selectSub(function ($query) {
                $query->from('companies')
                    ->join('company_user', 'companies.id', '=', 'company_user.company_id')
                    ->whereColumn('company_user.user_id', 'subscribes.user_id')
                    ->orderByDesc('company_user.is_active')
                    ->orderBy('companies.name')
                    ->limit(1)
                    ->select('companies.name');
            }, 'company_name')
            ->with('payments');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('user_name')
            ->add('user_cpf')
            ->add('plan_name')
            ->add('company_name', fn (Subscribe $model) => $model->company_name ?? '—')
            ->add('plan_price_formatted', fn (Subscribe $model) => 'R$ ' . number_format($model->plan_price, 2, ',', '.'))
            ->add('start_date_formatted', fn (Subscribe $model) => Carbon::parse($model->start_date)->format('d/m/Y'))
            ->add('end_date_formatted', fn (Subscribe $model) => Carbon::parse($model->end_date)->format('d/m/Y'))
            ->add('status_badge', function (Subscribe $model) {
                if ($model->cancelled_date) {
                    return '<span class="badge badge-error">Cancelada</span>';
                }
                if (Carbon::parse($model->end_date)->isPast()) {
                    return '<span class="badge badge-warning">Expirada</span>';
                }
                if (!$model->hasConfirmedPayment()) {
                    return '<span class="badge badge-warning">Aguardando pagamento</span>';
                }
                return '<span class="badge badge-success">Ativa</span>';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id', 'subscribes.id')->sortable(),
            Column::make('Usuário', 'user_name', 'users.name')->searchable()->sortable(),
            Column::make('CPF', 'user_cpf', 'users.cpf')->searchable(),
            Column::make('Empresa', 'company_name'),
            Column::make('Plano', 'plan_name', 'plans.name')->searchable()->sortable(),
            Column::make('Valor', 'plan_price_formatted', 'plans.price')->sortable(),
            Column::make('Inicio', 'start_date_formatted', 'subscribes.start_date')->sortable(),
            Column::make('Fim', 'end_date_formatted', 'subscribes.end_date')->sortable(),
            Column::make('Status', 'status_badge')->bodyAttribute('class', 'text-center'),
            Column::action('Acoes'),
        ];
    }

    public function filters(): array
    {
        $companies = Company::query()
            ->orderBy('name')
            ->get()
            ->map(fn (Company $c) => ['id' => $c->id, 'name' => $c->name])
            ->toArray();

        return [
            Filter::select('plan_name', 'plans.id')
                ->dataSource(
                    Plan::query()->orderBy('name')->get()
                        ->map(fn ($p) => ['id' => $p->id, 'name' => $p->name])
                        ->toArray()
                )
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('empresa_filter')
                ->dataSource($companies)
                ->optionLabel('name')
                ->optionValue('id')
                ->builder(function (Builder $builder, mixed $value) {
                    if ($value === null || $value === '') {
                        return $builder;
                    }

                    return $builder->whereIn('subscribes.user_id', function ($q) use ($value) {
                        $q->select('user_id')->from('company_user')->where('company_id', $value);
                    });
                }),
            Filter::datepicker('start_date_formatted', 'subscribes.start_date'),
            Filter::datepicker('end_date_formatted', 'subscribes.end_date'),
        ];
    }

    #[\Livewire\Attributes\On('master::subscribe-cancel')]
    public function cancel($rowId): void
    {
        $subscribe = Subscribe::findOrFail($rowId);

        if (!$subscribe->cancelled_date) {
            $subscribe->update(['cancelled_date' => now()]);
        }
    }

    public function actions(Subscribe $row): array
    {
        $actions = [];

        if (!$row->cancelled_date && !Carbon::parse($row->end_date)->isPast()) {
            $actions[] = Button::add('cancel')
                ->slot('Cancelar')
                ->id()
                ->class('btn btn-error btn-sm')
                ->dispatch('master::subscribe-cancel', ['rowId' => $row->id]);
        }

        return $actions;
    }
}
