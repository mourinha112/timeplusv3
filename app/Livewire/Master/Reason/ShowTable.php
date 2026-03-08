<?php

namespace App\Livewire\Master\Reason;

use App\Models\Reason;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Motivos de Consulta', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.reason.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Reason::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nome', 'name')->searchable()->sortable(),
            Column::action('Acoes'),
        ];
    }

    #[\Livewire\Attributes\On('master::reason-edit')]
    public function edit($rowId): void
    {
        $this->redirect(route('master.reason.edit', ['reason' => $rowId]));
    }

    #[\Livewire\Attributes\On('master::reason-delete')]
    public function deleteReason($rowId): void
    {
        Reason::findOrFail($rowId)->delete();
    }

    public function actions(Reason $row): array
    {
        return [
            Button::add('edit')->slot('Editar')->id()->class('btn btn-warning btn-sm')->dispatch('master::reason-edit', ['rowId' => $row->id]),
            Button::add('delete')->slot('Excluir')->id()->class('btn btn-error btn-sm')->dispatch('master::reason-delete', ['rowId' => $row->id]),
        ];
    }
}
