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

#[Layout('components.layouts.app', ['title' => 'Importar Funcionários', 'guard' => 'company'])]
class Import extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $companyId;

    public $file = null;

    public array $preview = [];

    public array $errors = [];

    public array $results = [];

    public bool $showResults = false;

    public bool $sendEmails = true;

    public int $successCount = 0;

    public int $errorCount = 0;

    public function mount(): void
    {
        $this->companyId = Auth::guard('company')->id();
    }

    public function updatedFile(): void
    {
        $this->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $this->preview = [];
        $this->errors  = [];

        try {
            $path    = $this->file->getRealPath();
            $handle  = fopen($path, 'r');
            $headers = fgetcsv($handle, 0, ';');

            // Verificar headers obrigatórios
            $requiredHeaders = ['nome', 'cpf', 'email', 'telefone', 'data_nascimento'];
            $missingHeaders  = array_diff($requiredHeaders, array_map('strtolower', array_map('trim', $headers)));

            if (!empty($missingHeaders)) {
                $this->errors[] = 'Colunas obrigatórias faltando: ' . implode(', ', $missingHeaders);
                fclose($handle);

                return;
            }

            // Mapear índices das colunas
            $headerMap = [];
            foreach ($headers as $index => $header) {
                $headerMap[strtolower(trim($header))] = $index;
            }

            $row = 1;
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                $row++;
                if (count($data) < 5) {
                    continue;
                }

                $this->preview[] = [
                    'row'             => $row,
                    'nome'            => trim($data[$headerMap['nome']] ?? ''),
                    'cpf'             => trim($data[$headerMap['cpf']] ?? ''),
                    'email'           => trim($data[$headerMap['email']] ?? ''),
                    'telefone'        => trim($data[$headerMap['telefone']] ?? ''),
                    'data_nascimento' => trim($data[$headerMap['data_nascimento']] ?? ''),
                ];

                if (count($this->preview) >= 100) {
                    break; // Limite de preview
                }
            }

            fclose($handle);

        } catch (\Exception $e) {
            $this->errors[] = 'Erro ao ler arquivo: ' . $e->getMessage();
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

        foreach ($this->preview as $item) {
            $result = [
                'row'     => $item['row'],
                'nome'    => $item['nome'],
                'email'   => $item['email'],
                'status'  => 'pending',
                'message' => '',
            ];

            try {
                // Validar dados
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

                // Verificar se email já existe
                if (User::where('email', $item['email'])->exists()) {
                    $result['status']  = 'error';
                    $result['message'] = 'E-mail já cadastrado no sistema';
                    $this->results[]   = $result;
                    $this->errorCount++;

                    continue;
                }

                // Verificar se CPF já existe
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
                    'password'     => bcrypt($password),
                    'is_active'    => true,
                ]);

                // Criar customer no Asaas
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

                // Vincular à empresa
                $company->employees()->attach($user->id, ['is_active' => true]);

                DB::commit();

                // Enviar email
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
        $this->errors       = [];
        $this->results      = [];
        $this->showResults  = false;
        $this->successCount = 0;
        $this->errorCount   = 0;
    }

    public function render()
    {
        return view('livewire.company.employee.import');
    }
}
