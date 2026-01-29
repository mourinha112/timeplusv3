<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-upload class="w-8 text-info" />
            Importar Funcionários
        </x-title>
        <x-subtitle>
            Importe múltiplos funcionários de uma só vez através de um arquivo CSV
        </x-subtitle>
    </x-heading>

    @if (!$showResults)
        <x-card class="w-full">
            <x-card-body>
                {{-- Instruções --}}
                <div class="bg-info/10 border border-info/30 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <x-carbon-information class="w-6 h-6 text-info flex-shrink-0 mt-0.5" />
                        <div>
                            <h4 class="font-semibold text-base-content mb-2">Formato do arquivo CSV</h4>
                            <p class="text-sm text-base-content/70 mb-3">
                                O arquivo deve estar no formato CSV com separador <code
                                    class="bg-base-200 px-1 rounded">;</code> (ponto e vírgula) e conter as seguintes
                                colunas:
                            </p>
                            <div class="overflow-x-auto">
                                <table class="table table-xs bg-base-100 rounded">
                                    <thead>
                                        <tr>
                                            <th>Coluna</th>
                                            <th>Formato</th>
                                            <th>Exemplo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>nome</code></td>
                                            <td>Texto</td>
                                            <td>João da Silva</td>
                                        </tr>
                                        <tr>
                                            <td><code>cpf</code></td>
                                            <td>XXX.XXX.XXX-XX</td>
                                            <td>123.456.789-00</td>
                                        </tr>
                                        <tr>
                                            <td><code>email</code></td>
                                            <td>E-mail válido</td>
                                            <td>joao@email.com</td>
                                        </tr>
                                        <tr>
                                            <td><code>telefone</code></td>
                                            <td>(XX) XXXXX-XXXX</td>
                                            <td>(11) 99999-9999</td>
                                        </tr>
                                        <tr>
                                            <td><code>data_nascimento</code></td>
                                            <td>DD/MM/AAAA</td>
                                            <td>15/03/1990</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-sm text-base-content/70 mt-3">
                                <strong>Exemplo de linha:</strong><br>
                                <code class="bg-base-200 px-2 py-1 rounded text-xs block mt-1">
                                    nome;cpf;email;telefone;data_nascimento<br>
                                    João da Silva;123.456.789-00;joao@email.com;(11) 99999-9999;15/03/1990
                                </code>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Upload --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-base-content mb-2">
                        Selecione o arquivo CSV
                    </label>
                    <input type="file" wire:model="file" accept=".csv,.txt"
                        class="file-input file-input-bordered file-input-info w-full" />
                    @error('file')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Erros --}}
                @if (!empty($errors))
                    <div class="alert alert-error mb-6">
                        <x-carbon-warning class="w-5 h-5" />
                        <div>
                            <h4 class="font-semibold">Erros encontrados:</h4>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Preview --}}
                @if (!empty($preview))
                    <div class="mb-6">
                        <h4 class="font-semibold text-base-content mb-2">
                            Pré-visualização ({{ count($preview) }} registros)
                        </h4>
                        <div class="overflow-x-auto max-h-96">
                            <table class="table table-xs table-zebra">
                                <thead class="sticky top-0 bg-base-200">
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>CPF</th>
                                        <th>E-mail</th>
                                        <th>Telefone</th>
                                        <th>Nascimento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($preview as $item)
                                        <tr>
                                            <td>{{ $item['row'] }}</td>
                                            <td>{{ $item['nome'] }}</td>
                                            <td>{{ $item['cpf'] }}</td>
                                            <td>{{ $item['email'] }}</td>
                                            <td>{{ $item['telefone'] }}</td>
                                            <td>{{ $item['data_nascimento'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Opções --}}
                    <div class="form-control mb-6">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" wire:model="sendEmails" class="checkbox checkbox-info" />
                            <span class="label-text">Enviar e-mail com credenciais para cada funcionário importado</span>
                        </label>
                    </div>
                @endif

                {{-- Botões --}}
                <div class="flex justify-end gap-3">
                    <a wire:navigate href="{{ route('company.employee.index') }}" class="btn btn-soft btn-error">
                        <x-carbon-arrow-left class="w-4 h-4" />
                        Voltar
                    </a>
                    @if (!empty($preview))
                        <button type="button" wire:click="import" class="btn btn-info" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="import">
                                <x-carbon-upload class="w-4 h-4" />
                                Importar {{ count($preview) }} Funcionários
                            </span>
                            <span wire:loading wire:target="import" class="flex items-center gap-2">
                                <span class="loading loading-spinner loading-xs"></span>
                                Importando...
                            </span>
                        </button>
                    @endif
                </div>
            </x-card-body>
        </x-card>
    @else
        {{-- Resultados --}}
        <x-card class="w-full">
            <x-card-body>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-base-content">Resultado da Importação</h3>
                        <p class="text-base-content/70">
                            <span class="text-success font-semibold">{{ $successCount }}</span> importados com sucesso,
                            <span class="text-error font-semibold">{{ $errorCount }}</span> com erro
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="resetImport" class="btn btn-ghost">
                            <x-carbon-reset class="w-4 h-4" />
                            Nova Importação
                        </button>
                        <a wire:navigate href="{{ route('company.employee.index') }}" class="btn btn-info">
                            <x-carbon-arrow-left class="w-4 h-4" />
                            Voltar para Lista
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto max-h-96">
                    <table class="table table-xs">
                        <thead class="sticky top-0 bg-base-200">
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Status</th>
                                <th>Mensagem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                                <tr class="{{ $result['status'] === 'error' ? 'bg-error/10' : 'bg-success/10' }}">
                                    <td>{{ $result['row'] }}</td>
                                    <td>{{ $result['nome'] }}</td>
                                    <td>{{ $result['email'] }}</td>
                                    <td>
                                        @if ($result['status'] === 'success')
                                            <span class="badge badge-success badge-sm">Sucesso</span>
                                        @else
                                            <span class="badge badge-error badge-sm">Erro</span>
                                        @endif
                                    </td>
                                    <td class="text-xs">{{ $result['message'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card-body>
        </x-card>
    @endif
</div>
