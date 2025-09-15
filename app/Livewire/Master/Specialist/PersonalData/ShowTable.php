<?php

namespace App\Livewire\Master\Specialist\PersonalData;

use App\Models\Specialist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Especialistas', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'specialist.personal_data.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Specialist::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('avatar_formatted', function (Specialist $model) {
                return $model->avatar
                    ? '<img src="' . asset('storage/' . $model->avatar) . '" class="w-10 h-10 rounded-full" />'
                    : '<img src="' . asset('images/avatar.png') . '" class="w-10 h-10 rounded-full" />';
            })
            ->add('name')
            ->add('email')
            ->add('crp')
            // ->add('is_active_formatted', fn(Specialist $model) => $model->is_active ? 'Ativo' : 'Inativo')
            ->add('created_at_formatted', fn (Specialist $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nome', 'name')->searchable()->sortable(),
            Column::make('E-mail', 'email')->searchable(),
            Column::make('CRP', 'crp')->searchable(),
            // Column::make('Situação', 'is_active_formatted', 'is_active')->sortable(),
            Column::make('Cadastrado em', 'created_at_formatted', 'created_at')
                ->sortable(),
            Column::action('Ações'),
        ];
    }

    #[\Livewire\Attributes\On('master::specialist-show')]
    public function show($rowId): void
    {
        $specialist = Specialist::findOrFail($rowId);
        $this->redirect(route('master.specialist.personal-data.show', ['specialist' => $specialist->id]));
    }

    public function actions(Specialist $row): array
    {
        return [
            Button::add('show')
                ->slot('Visualizar')
                ->id()
                ->class('btn btn-info btn-sm')
                ->dispatch('master::specialist-show', ['rowId' => $row->id]),
        ];
    }
}
