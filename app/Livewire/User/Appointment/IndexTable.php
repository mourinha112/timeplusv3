<?php

namespace App\Livewire\User\Appointment;

use App\Models\Appointment;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class IndexTable extends PowerGridComponent
{
    public string $tableName = 'index-table-ndaxkw-table';

    public function setUp(): array
    {
        // $this->showCheckBox();

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
        return Appointment::query()->with('specialist');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            // ->add('user_id')
            ->add('specialist_name', fn ($appointment) => e($appointment->specialist->name))
            ->add('appointment_date_formatted', fn(Appointment $model) => Carbon::parse($model->appointment_date)->format('d/m/Y'))
            ->add('appointment_time')
            // ->add('created_at');
            ->add('status');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id'),
            // Column::make('User ID', 'user_id'),
            Column::make('Especialista', 'specialist_name')
                ->searchable()
                ->sortable(),

            Column::make('Data', 'appointment_date_formatted', 'appointment_date')
                ->sortable(),

            Column::make('Hora', 'appointment_time')
                ->sortable()
                ->searchable(),

            Column::make('Situação', 'status')
                ->sortable()
                ->searchable(),

            // Column::make('Created at', 'created_at_formatted', 'created_at')
            //     ->sortable(),

            // Column::make('Created at', 'created_at')
            //     ->sortable()
            //     ->searchable(),

            Column::action('Ações')
        ];
    }

    // public function filters(): array
    // {
    //     return [
    //         Filter::datepicker('appointment_date'),
    //     ];
    // }

    #[\Livewire\Attributes\On('show')]
    public function show($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Appointment $row): array
    {
        return [
            Button::add('show')
                ->slot('Visualizar')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('show', ['rowId' => $row->id])
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
