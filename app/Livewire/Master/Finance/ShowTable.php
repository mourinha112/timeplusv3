<?php

namespace App\Livewire\Master\Finance;

use App\Models\{Appointment, Payment, Specialist};
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Financeiro - Especialistas', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.finance.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Specialist::query()
            ->leftJoin('specialist_payment_profiles', 'specialists.id', '=', 'specialist_payment_profiles.specialist_id')
            ->select(
                'specialists.id',
                'specialists.name',
                'specialists.email',
                'specialists.cpf',
                'specialist_payment_profiles.payment_type',
                'specialist_payment_profiles.pix_key',
                'specialist_payment_profiles.bank_name',
                'specialist_payment_profiles.agency',
                'specialist_payment_profiles.account_number',
                'specialist_payment_profiles.account_digit',
                'specialist_payment_profiles.is_verified',
                'specialist_payment_profiles.platform_fee_percentage',
            );
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
            ->add('email')
            ->add('payment_info', function (Specialist $model) {
                if (!$model->payment_type) {
                    return '<span class="badge badge-warning">Nao cadastrado</span>';
                }
                if ($model->payment_type === 'pix') {
                    $key = $model->pix_key ?? '';
                    $masked = strlen($key) > 6
                        ? substr($key, 0, 4) . '****' . substr($key, -4)
                        : $key;
                    return 'PIX: ' . $masked;
                }
                return ($model->bank_name ?? '') . ' Ag:' . ($model->agency ?? '') . ' Cc:' . ($model->account_number ?? '') . '-' . ($model->account_digit ?? '');
            })
            ->add('verified_badge', function (Specialist $model) {
                if (!$model->payment_type) {
                    return '<span class="badge badge-ghost">---</span>';
                }
                return $model->is_verified
                    ? '<span class="badge badge-success">Verificado</span>'
                    : '<span class="badge badge-warning">Pendente</span>';
            })
            ->add('fee_formatted', function (Specialist $model) {
                return ($model->platform_fee_percentage ?? 20) . '%';
            })
            ->add('total_earned', function (Specialist $model) {
                $total = Payment::where('status', 'paid')
                    ->whereHasMorph('payable', [Appointment::class], function ($query) use ($model) {
                        $query->where('specialist_id', $model->id);
                    })
                    ->sum('amount');
                return 'R$ ' . number_format($total, 2, ',', '.');
            })
            ->add('specialist_balance', function (Specialist $model) {
                $total = Payment::where('status', 'paid')
                    ->whereHasMorph('payable', [Appointment::class], function ($query) use ($model) {
                        $query->where('specialist_id', $model->id);
                    })
                    ->sum('amount');
                $fee = $model->platform_fee_percentage ?? 20;
                $balance = $total * (1 - $fee / 100);
                return 'R$ ' . number_format($balance, 2, ',', '.');
            })
            ->add('completed_sessions', function (Specialist $model) {
                return Appointment::where('specialist_id', $model->id)
                    ->where('status', 'completed')
                    ->count();
            });
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Especialista', 'name')->searchable()->sortable(),
            Column::make('E-mail', 'email')->searchable(),
            Column::make('Dados Pagamento', 'payment_info'),
            Column::make('Verificado', 'verified_badge')->bodyAttribute('class', 'text-center'),
            Column::make('Taxa', 'fee_formatted')->bodyAttribute('class', 'text-center'),
            Column::make('Sessoes', 'completed_sessions')->bodyAttribute('class', 'text-center'),
            Column::make('Total Bruto', 'total_earned'),
            Column::make('Saldo Especialista', 'specialist_balance'),
            Column::action('Acoes'),
        ];
    }

    #[\Livewire\Attributes\On('master::finance-detail')]
    public function detail($rowId): void
    {
        $this->redirect(route('master.finance.show', ['specialist' => $rowId]));
    }

    public function actions(Specialist $row): array
    {
        return [
            Button::add('detail')
                ->slot('Detalhes')
                ->id()
                ->class('btn btn-info btn-sm')
                ->dispatch('master::finance-detail', ['rowId' => $row->id]),
        ];
    }
}
