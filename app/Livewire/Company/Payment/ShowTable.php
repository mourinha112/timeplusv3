<?php

namespace App\Livewire\Company\Payment;

use App\Models\{Appointment, Payment, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Pagamentos dos Funcionários', 'guard' => 'company'])]
class ShowTable extends PowerGridComponent
{
  public string $tableName = 'company.payment.table';

  public function setUp(): array
  {
    return [
      PowerGrid::header()->showSearchInput(),
      PowerGrid::footer()->showPerPage()->showRecordCount(),
    ];
  }

  public function datasource(): Builder
  {
    $company = Auth::guard('company')->user();

    return Payment::query()
      ->where('company_id', $company->id)
      ->where('payable_type', Appointment::class)
      ->with(['payable.user', 'payable.specialist'])
      ->orderBy('created_at', 'desc');
  }

  public function relationSearch(): array
  {
    return [
      'payable.user' => ['name', 'email'],
      'payable.specialist' => ['name'],
    ];
  }

  public function fields(): PowerGridFields
  {
    return PowerGrid::fields()
      ->add('id')
      ->add('employee_name', function (Payment $model) {
        return $model->payable?->user?->name ?? 'N/A';
      })
      ->add('employee_email', function (Payment $model) {
        return $model->payable?->user?->email ?? 'N/A';
      })

      ->add('session_date', function (Payment $model) {
        $appointment = $model->payable;
        if ($appointment && $appointment->appointment_date) {
          return Carbon::parse($appointment->appointment_date)->format('d/m/Y');
        }
        return 'N/A';
      })
      ->add('session_time', function (Payment $model) {
        $appointment = $model->payable;
        if ($appointment && $appointment->appointment_time) {
          return Carbon::parse($appointment->appointment_time)->format('H:i');
        }
        return 'N/A';
      })
      ->add('original_amount_formatted', function (Payment $model) {
        return 'R$ ' . number_format((float) $model->original_amount, 2, ',', '.');
      })
      ->add('discount_value_formatted', function (Payment $model) {
        return 'R$ ' . number_format((float) $model->discount_value, 2, ',', '.');
      })
      ->add('discount_percentage_formatted', function (Payment $model) {
        return $model->discount_percentage > 0 ? $model->discount_percentage . '%' : '-';
      })
      ->add('final_amount_formatted', function (Payment $model) {
        return 'R$ ' . number_format((float) $model->amount, 2, ',', '.');
      })
      ->add('company_plan_name', function (Payment $model) {
        return $model->company_plan_name ?? 'N/A';
      })
      ->add('status_formatted', function (Payment $model) {
        return match ($model->status) {
          'paid'    => '<span class="badge badge-success">Pago</span>',
          'pending' => '<span class="badge badge-warning">Pendente</span>',
          'failed'  => '<span class="badge badge-error">Falhou</span>',
          default   => '<span class="badge badge-neutral">' . ucfirst($model->status) . '</span>',
        };
      })
      ->add('payment_method_formatted', function (Payment $model) {
        return match ($model->payment_method) {
          'credit_card' => 'Cartão de Crédito',
          'pix'         => 'PIX',
          default       => ucfirst(str_replace('_', ' ', $model->payment_method)),
        };
      })
      ->add('paid_at_formatted', function (Payment $model) {
        return $model->paid_at ? Carbon::parse($model->paid_at)->format('d/m/Y H:i') : '-';
      });
  }

  public function columns(): array
  {
    return [
      Column::make('Funcionário', 'employee_name')
        ->searchable()
        ->sortable(),


      Column::make('Data da Sessão', 'session_date')
        ->sortable(),

      Column::make('Horário', 'session_time')
        ->sortable(),

      Column::make('Valor Original', 'original_amount_formatted', 'original_amount')
        ->sortable(),

      Column::make('Desconto', 'discount_percentage_formatted', 'discount_percentage')
        ->sortable(),

      Column::make('Valor Desconto', 'discount_value_formatted', 'discount_value')
        ->sortable(),

      Column::make('Valor Final', 'final_amount_formatted', 'amount')
        ->sortable(),

      Column::make('Plano', 'company_plan_name')
        ->searchable(),

      Column::make('Status', 'status_formatted', 'status')
        ->sortable(),

      Column::make('Método', 'payment_method_formatted', 'payment_method')
        ->sortable(),

      Column::make('Data Pagamento', 'paid_at_formatted', 'paid_at')
        ->sortable(),

      Column::action('Ações'),
    ];
  }

  #[\Livewire\Attributes\On('company::payment-show')]
  public function show($rowId): void
  {
    $this->redirect(route('company.payment.show', ['payment' => $rowId]));
  }

  public function actions(Payment $row): array
  {
    return [
      Button::add('show')
        ->slot('Visualizar')
        ->id()
        ->class('btn btn-info btn-sm tooltip')
        ->tooltip('Visualizar Detalhes')
        ->dispatch('company::payment-show', ['rowId' => $row->id]),
    ];
  }
}
