<?php

namespace App\Livewire\Master\Plan;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Planos da Plataforma', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.plan.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Plan::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('price_formatted', fn (Plan $model) => 'R$ ' . number_format($model->price, 2, ',', '.'))
            ->add('discount_badge', fn (Plan $model) => $model->discount_percentage . '%')
            ->add('duration_formatted', fn (Plan $model) => $model->duration_days . ' dia' . ($model->duration_days > 1 ? 's' : ''))
            ->add('created_at_formatted', fn (Plan $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nome', 'name')->searchable()->sortable(),
            Column::make('Preco', 'price_formatted', 'price')->bodyAttribute('class', 'text-center')->sortable(),
            Column::make('Desconto', 'discount_badge', 'discount_percentage')->bodyAttribute('class', 'text-center')->sortable(),
            Column::make('Duracao', 'duration_formatted', 'duration_days')->bodyAttribute('class', 'text-center')->sortable(),
            Column::make('Criado em', 'created_at_formatted', 'created_at')->sortable(),
            Column::action('Acoes'),
        ];
    }

    #[\Livewire\Attributes\On('master::plan-show')]
    public function show($rowId): void
    {
        $this->redirect(route('master.plan.show', ['plan' => $rowId]));
    }

    #[\Livewire\Attributes\On('master::plan-delete')]
    public function delete($rowId): void
    {
        $plan = Plan::findOrFail($rowId);
        $plan->delete();
    }

    public function actions(Plan $row): array
    {
        return [
            Button::add('show')
                ->slot('Visualizar')
                ->id()
                ->class('btn btn-info btn-sm')
                ->dispatch('master::plan-show', ['rowId' => $row->id]),

            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('btn btn-warning btn-sm')
                ->route('master.plan.edit', ['plan' => $row->id]),

            Button::add('delete')
                ->slot('Excluir')
                ->id()
                ->class('btn btn-error btn-sm')
                ->dispatch('master::plan-delete', ['rowId' => $row->id]),
        ];
    }
}
