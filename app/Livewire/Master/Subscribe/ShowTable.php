<?php

namespace App\Livewire\Master\Subscribe;

use App\Models\Subscribe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

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
            ->select('subscribes.*', 'users.name as user_name', 'plans.name as plan_name', 'plans.price as plan_price');
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
            ->add('plan_name')
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
                return '<span class="badge badge-success">Ativa</span>';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Usuario', 'user_name')->searchable()->sortable(),
            Column::make('Plano', 'plan_name')->searchable()->sortable(),
            Column::make('Valor', 'plan_price_formatted', 'plans.price')->sortable(),
            Column::make('Inicio', 'start_date_formatted', 'start_date')->sortable(),
            Column::make('Fim', 'end_date_formatted', 'end_date')->sortable(),
            Column::make('Status', 'status_badge')->bodyAttribute('class', 'text-center'),
            Column::action('Acoes'),
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
