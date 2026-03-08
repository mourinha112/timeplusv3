<?php

namespace App\Livewire\Master\Availability;

use App\Models\Availability;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Disponibilidades', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.availability.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Availability::query()
            ->join('specialists', 'availabilities.specialist_id', '=', 'specialists.id')
            ->select('availabilities.*', 'specialists.name as specialist_name')
            ->orderBy('availabilities.available_date', 'desc')
            ->orderBy('availabilities.available_time', 'asc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('specialist_name')
            ->add('available_date_formatted', fn (Availability $model) => Carbon::parse($model->available_date)->format('d/m/Y'))
            ->add('available_time_formatted', fn (Availability $model) => Carbon::parse($model->available_time)->format('H:i'))
            ->add('day_of_week', fn (Availability $model) => Carbon::parse($model->available_date)->translatedFormat('l'))
            ->add('status_badge', function (Availability $model) {
                if (Carbon::parse($model->available_date)->isPast()) {
                    return '<span class="badge badge-ghost">Passado</span>';
                }
                if (Carbon::parse($model->available_date)->isToday()) {
                    return '<span class="badge badge-info">Hoje</span>';
                }
                return '<span class="badge badge-success">Futuro</span>';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Especialista', 'specialist_name')->searchable()->sortable(),
            Column::make('Data', 'available_date_formatted', 'availabilities.available_date')->sortable(),
            Column::make('Dia', 'day_of_week'),
            Column::make('Horario', 'available_time_formatted', 'availabilities.available_time')->sortable(),
            Column::make('Status', 'status_badge')->bodyAttribute('class', 'text-center'),
        ];
    }
}
