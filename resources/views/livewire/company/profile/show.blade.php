<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Perfil da Empresa</h1>
            <p class="text-gray-600">Gerencie as informações da sua empresa</p>
        </div>
    </div>

    <!-- Formulário de Perfil -->
    <x-card>
        <x-form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                <!-- Dados Básicos -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800">Dados Básicos</h3>
                </div>

                <x-form-group>
                    <x-label required>Nome da Empresa</x-label>
                    <x-input type="text" wire:model="name" placeholder="Nome da empresa" />
                </x-form-group>

                <x-form-group>
                    <x-label required>CNPJ</x-label>
                    <x-input type="text" wire:model="cnpj" placeholder="00.000.000/0000-00"
                        x-mask="99.999.999/9999-99" />
                </x-form-group>

                <x-form-group>
                    <x-label required>E-mail</x-label>
                    <x-input type="email" wire:model="email" placeholder="empresa@exemplo.com" />
                </x-form-group>

                <x-form-group>
                    <x-label>Telefone</x-label>
                    <x-input type="text" wire:model="phone" placeholder="(11) 99999-9999" x-mask="(99) 99999-9999" />
                </x-form-group>

                <!-- Endereço -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800">Endereço</h3>
                </div>

                <x-form-group>
                    <x-label>CEP</x-label>
                    <x-input type="text" wire:model="zip_code" placeholder="00000-000" x-mask="99999-999" />
                </x-form-group>

                <x-form-group>
                    <x-label>Endereço</x-label>
                    <x-input type="text" wire:model="address" placeholder="Rua, número" />
                </x-form-group>

                <x-form-group>
                    <x-label>Cidade</x-label>
                    <x-input type="text" wire:model="city" placeholder="Cidade" />
                </x-form-group>

                <x-form-group>
                    <x-label>Estado</x-label>
                    <x-select wire:model="state">
                        <option value="">Selecione um estado</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </x-select>
                </x-form-group>


                <!-- Botão Salvar na segunda coluna -->
                <div class="flex justify-start items-start">
                    <x-button type="submit">
                        <x-carbon-save class="w-4 h-4" />
                        Salvar Alterações
                    </x-button>
                </div>
            </div>

        </x-form>
    </x-card>

    <!-- Informações do Plano -->
    <x-card title="Informações do Plano">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4">
            <div class="text-center p-4 bg-primary/5 rounded-lg">
                <x-carbon-user-multiple class="w-6 text-primary mx-auto mb-2" />
                <p class="text-2xl font-bold text-primary">
                    {{ Auth::guard('company')->user()->activeEmployees()->count() }}</p>
                <p class="text-sm text-gray-600">Funcionários Ativos</p>
            </div>

            <div class="text-center p-4 bg-success/5 rounded-lg">
                <x-carbon-layers class="w-6 text-success mx-auto mb-2" />
                <p class="text-lg font-semibold text-success">Ativo</p>
                <p class="text-sm text-gray-600">Desde {{ Auth::guard('company')->user()->created_at->format('d/m/Y') }}
                </p>
            </div>

            <div class="text-center p-4 bg-info/5 rounded-lg">
                <x-carbon-time class="w-6 text-info mx-auto mb-2" />
                <p class="text-lg font-semibold text-info">
                    {{ Auth::guard('company')->user()->created_at->diffInDays(now()) }}</p>
                <p class="text-sm text-gray-600">Dias Cadastrado</p>
            </div>
        </div>
    </x-card>

    {{-- <!-- Segurança -->
    <x-card>
        <div class="space-y-4 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-medium">Senha</h4>
                    <p class="text-sm text-gray-600">Altere sua senha regularmente para manter sua conta segura</p>
                </div>
                <x-button color="primary" outline>
                    <x-carbon-password class="w-4 h-4" />
                    Alterar Senha
                </x-button>
            </div>



        </div>
    </x-card> --}}
</div>
