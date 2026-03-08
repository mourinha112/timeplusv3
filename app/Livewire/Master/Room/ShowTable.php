<?php

namespace App\Livewire\Master\Room;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Salas de Video', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.room.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Room::query()
            ->leftJoin('appointments', 'rooms.appointment_id', '=', 'appointments.id')
            ->leftJoin('users', 'appointments.user_id', '=', 'users.id')
            ->leftJoin('specialists', 'appointments.specialist_id', '=', 'specialists.id')
            ->select(
                'rooms.*',
                'users.name as user_name',
                'specialists.name as specialist_name'
            )
            ->orderByDesc('rooms.created_at');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('code')
            ->add('user_name', fn (Room $model) => $model->user_name ?? '---')
            ->add('specialist_name', fn (Room $model) => $model->specialist_name ?? '---')
            ->add('status_badge', function (Room $model) {
                if ($model->status === 'open') {
                    return '<span class="badge badge-success">Aberta</span>';
                }
                return '<span class="badge badge-ghost">Fechada</span>';
            })
            ->add('created_at_formatted', fn (Room $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i'))
            ->add('closed_at_formatted', fn (Room $model) => $model->closed_at ? Carbon::parse($model->closed_at)->format('d/m/Y H:i') : '---');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Codigo', 'code')->searchable()->sortable(),
            Column::make('Cliente', 'user_name')->searchable()->sortable(),
            Column::make('Especialista', 'specialist_name')->searchable()->sortable(),
            Column::make('Status', 'status_badge')->bodyAttribute('class', 'text-center'),
            Column::make('Criada em', 'created_at_formatted', 'rooms.created_at')->sortable(),
            Column::make('Fechada em', 'closed_at_formatted', 'rooms.closed_at')->sortable(),
            Column::action('Acoes'),
        ];
    }

    #[\Livewire\Attributes\On('master::room-close')]
    public function closeRoom($rowId): void
    {
        $room = Room::findOrFail($rowId);

        if ($room->status === 'open') {
            $room->update(['status' => 'closed', 'closed_at' => now()]);
        }
    }

    public function actions(Room $row): array
    {
        $actions = [];

        if ($row->status === 'open') {
            $actions[] = Button::add('close')
                ->slot('Fechar Sala')
                ->id()
                ->class('btn btn-error btn-sm')
                ->dispatch('master::room-close', ['rowId' => $row->id]);
        }

        return $actions;
    }
}
