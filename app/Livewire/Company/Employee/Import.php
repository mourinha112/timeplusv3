<?php

namespace App\Livewire\Company\Employee;

use App\Facades\Asaas;
use App\Models\{Company, User};
use App\Notifications\EmployeeCredentialsNotification;
use Illuminate\Support\Facades\{Auth, DB, Log, Validator};
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Locked};
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('components.layouts.app', ['title' => 'Importar Funcionários', 'guard' => 'company'])]
class Import extends Component
{
    use WithFileUploads;

    #[Locked]
    public ?int $companyId = null;

    public $file = null;

    public array $preview = [];

    public array $importErrors = [];

    public array $results = [];

    public bool $showResults = false;

    public bool $sendEmails = true;

    public int $successCount = 0;

    public int $errorCount = 0;

    public function mount(): void
    {
        $company = Auth::guard('company')->user();

        if (!$company) {
            abort(403, 'Acesso não autorizado');
        }

        $this->companyId = $company->id;
    }

    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="modelo_funcionarios.csv"',
        ];

        return response()->streamDownload(function () {
            // BOM para Excel reconhecer UTF-8
            echo "\xEF\xBB\xBF";
            $out = fopen('php://output', 'w');
            fputcsv($out, ['nome', 'cpf', 'email', 'telefone', 'data_nascimento'], ';');
            fputcsv($out, ['João da Silva', '123.456.789-00', 'joao@email.com', '(11) 99999-9999', '15/03/1990'], ';');
            fputcsv($out, ['Maria Souza', '98765432100', 'maria@email.com', '11988887777', '02/12/1988'], ';');
            fclose($out);
        }, 'modelo_funcionarios.csv', $headers);
    }

    public function updatedFile(): void
    {
        $this->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $this->preview      = [];
        $this->importErrors = [];

        try {
            $path    = $this->file->getRealPath();
            $handle  = fopen($path, 'r');
            $headers = fgetcsv($handle, 0, ';');

            if (!$headers) {
                $this->importErrors[] = 'Arquivo CSV vazio ou ilegível.';
                fclose($handle);

                return;
            }

            // Remove BOM UTF-8 da primeira coluna (Excel adiciona)
            $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);

            // Normaliza headers: trim + lowercase
            $normalized = array_map(fn ($h) => mb_strtolower(trim((string) $h)), $headers);

            $requiredHeaders = ['nome', 'cpf', 'email', 'telefone', 'data_nascimento'];
            $missingHeaders  = array_diff($requiredHeaders, $normalized);

            if (!empty($missingHeaders)) {
                $this->importErrors[] = 'Colunas obrigatórias faltando: ' . implode(', ', $missingHeaders)
                    . '. Encontradas: ' . implode(', ', $normalized);
                fclose($handle);

                return;
            }

            $headerMap = array_flip($normalized);

            $row = 1;
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                $row++;

                if (count(array_filter($data, fn ($v) => $v !== null && $v !== '')) === 0) {
                    continue;
                }

                $cpfRaw   = trim($data[$headerMap['cpf']] ?? '');
                $phoneRaw = trim($data[$headerMap['telefone']] ?? '');

                $this->preview[] = [
                    'row'             => $row,
                    'nome'            => trim($data[$headerMap['nome']] ?? ''),
                    'cpf'             => $this->formatCpf($cpfRaw),
                    'email'           => trim($data[$headerMap['email']] ?? ''),
                    'telefone'        => $this->formatPhone($phoneRaw),
                    'data_nascimento' => trim($data[$headerMap['data_nascimento']] ?? ''),
                ];

                if (count($this->preview) >= 100) {
                    break;
                }
            }

            fclose($handle);

        } catch (\Exception $e) {
            $this->importErrors[] = 'Erro ao ler arquivo: ' . $e->getMessage();
        }
    }

    public function import(): void
    {
        if (empty($this->preview)) {
            LivewireAlert::title('Erro!')
                ->text('Nenhum dado para importar.')
                ->error()
                ->show();

            return;
        }

        $this->results      = [];
        $this->successCount = 0;
        $this->errorCount   = 0;

        $company = Company::findOrFail($this->companyId);

        $defaultPlanId = $company->companyPlans()
            ->where('is_active', true)
            ->orderBy('id')
            ->value('id');

        foreach ($this->preview as $item) {
            $result = [
                'row'     => $item['row'],
                'nome'    => $item['nome'],
                'email'   => $item['email'],
                'status'  => 'pending',
                'message' => '',
            ];

            try {
                $validator = Validator::make($item, [
                    'nome'            => 'required|string|max:255',
                    'cpf'             => 'required|string|size:14',
                    'email'           => 'required|email|max:255',
                    'telefone'        => 'required|string|max:20',
                    'data_nascimento' => 'required|date_format:d/m/Y',
                ]);

                if ($validator->fails()) {
                    $result['status']  = 'error';
                    $result['message'] = implode(', ', $validator->errors()->all());
                    $this->results[]   = $result;
                    $this->errorCount++;

                    continue;
                }

                if (User::where('email', $item['email'])->exists()) {
                    $result['status']  = 'error';
                    $result['message'] = 'E-mail já cadastrado no sistema';
                    $this->results[]   = $result;
                    $this->errorCount++;

                    continue;
                }

                if (User::where('cpf', $item['cpf'])->exists()) {
                    $result['status']  = 'error';
                    $result['message'] = 'CPF já cadastrado no sistema';
                    $this->results[]   = $result;
                    $this->errorCount++;

                    continue;
                }

                DB::beginTransaction();

                $password = Str::random(12);

                $user = User::create([
                    'name'         => $item['nome'],
                    'cpf'          => $item['cpf'],
                    'phone_number' => $item['telefone'],
                    'birth_date'   => $item['data_nascimento'],
                    'email'        => $item['email'],
                    'password'     => $password,
                    'is_active'    => true,
                ]);

                try {
                    $gateway = Asaas::customer()->create([
                        'code'         => $user->id,
                        'name'         => $user->name,
                        'email'        => $user->email,
                        'document'     => $user->cpf,
                        'mobile_phone' => $user->phone_number,
                    ]);
                    $user->update(['gateway_customer_id' => $gateway['id']]);
                } catch (\Exception $asaasError) {
                    Log::warning('Erro ao criar customer Asaas na importação', [
                        'user_id' => $user->id,
                        'error'   => $asaasError->getMessage(),
                    ]);
                }

                $company->employees()->attach($user->id, [
                    'is_active'       => true,
                    'company_plan_id' => $defaultPlanId,
                ]);

                DB::commit();

                if ($this->sendEmails) {
                    try {
                        $user->notify(new EmployeeCredentialsNotification(
                            companyName: $company->name,
                            email: $user->email,
                            password: $password
                        ));
                    } catch (\Exception $emailError) {
                        Log::warning('Erro ao enviar email na importação', [
                            'user_id' => $user->id,
                            'error'   => $emailError->getMessage(),
                        ]);
                    }
                }

                $result['status']  = 'success';
                $result['message'] = 'Importado com sucesso';
                $this->results[]   = $result;
                $this->successCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $result['status']  = 'error';
                $result['message'] = 'Erro: ' . $e->getMessage();
                $this->results[]   = $result;
                $this->errorCount++;

                Log::error('Erro na importação de funcionário', [
                    'row'     => $item['row'],
                    'email'   => $item['email'],
                    'error'   => $e->getMessage(),
                    'company' => $this->companyId,
                ]);
            }
        }

        $this->showResults = true;
    }

    public function resetImport(): void
    {
        $this->file         = null;
        $this->preview      = [];
        $this->importErrors = [];
        $this->results      = [];
        $this->showResults  = false;
        $this->successCount = 0;
        $this->errorCount   = 0;
    }

    private function formatCpf(string $raw): string
    {
        $digits = preg_replace('/\D/', '', $raw);

        if (strlen($digits) !== 11) {
            return $raw; // deixa falhar na validação com a string original
        }

        return substr($digits, 0, 3) . '.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-' . substr($digits, 9, 2);
    }

    private function formatPhone(string $raw): string
    {
        $digits = preg_replace('/\D/', '', $raw);

        if (strlen($digits) === 11) {
            return '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 5) . '-' . substr($digits, 7, 4);
        }

        if (strlen($digits) === 10) {
            return '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 4) . '-' . substr($digits, 6, 4);
        }

        return $raw;
    }

    public function render()
    {
        return view('livewire.company.employee.import');
    }
}
