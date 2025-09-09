<?php

namespace App\Livewire\Master\Appointment;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

#[Layout('components.layouts.app', ['title' => 'Agendamentos', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.appointment.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Appointment::query()->with(['user', 'specialist']);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('user_name', fn(Appointment $model) => $model->user?->name)
            ->add('specialist_name', fn(Appointment $model) => $model->specialist?->name)
            ->add('appointment_date_formatted', fn(Appointment $model) => $model->appointment_date ? Carbon::parse($model->appointment_date)->format('d/m/Y') : '-')
            ->add('appointment_time')
            ->add('status_formatted', function (Appointment $model) {
                return match ($model->status) {
                    'scheduled' => 'Agendado',
                    'canceled'  => 'Cancelado',
                    'completed' => 'Concluído',
                    default     => $model->status,
                };
            })
            ->add('total_value_formatted', fn(Appointment $model) => 'R$ ' . number_format((float) $model->total_value, 2, ',', '.'))
            ->add('created_at_formatted', fn(Appointment $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Cliente', 'user_name')->searchable()->sortable(),
            Column::make('Especialista', 'specialist_name')->searchable()->sortable(),
            Column::make('Data', 'appointment_date_formatted', 'appointment_date')->sortable(),
            Column::make('Hora', 'appointment_time')->sortable(),
            Column::make('Situação', 'status_formatted', 'status')->sortable(),
            Column::make('Valor', 'total_value_formatted', 'total_value')->sortable(),
            Column::make('Cadastrado em', 'created_at_formatted', 'created_at')->sortable(),
            Column::action('Ações'),
        ];
    }

    #[\Livewire\Attributes\On('master::appointment-show')]
    public function show($rowId): void
    {
        $this->redirect(route('master.appointment.show', ['appointment' => $rowId]));
    }

    public function actions(Appointment $row): array
    {
        return [
            Button::add('show')
                ->slot('Visualizar')
                ->id()
                ->class('btn btn-info btn-sm')
                ->dispatch('master::appointment-show', ['rowId' => $row->id])
        ];
    }
}
