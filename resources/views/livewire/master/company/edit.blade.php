<div class="container mx-auto">
    <div class="space-y-6">
        <x-heading>
            <x-title class="flex items-start gap-3">
                <x-carbon-edit class="w-8 text-info" />
                Editar Empresa: {{ $company->name }}
            </x-title>
        </x-heading>

        <x-card class="card-compact">
            <x-card-body>
                <x-form wire:submit="save">
                    <div>
                        <h3 class="text-lg font-semibold text-base-content mb-4">Informações da Empresa</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form-group>
                                <x-label required>Nome da Empresa</x-label>
                                <x-input type="text" wire:model="name" />
                            </x-form-group>

                            <x-form-group>
                                <x-label required>CNPJ</x-label>
                                <x-input type="text" wire:model="cnpj" placeholder="00000000000000" maxlength="14" />
                            </x-form-group>

                            <x-form-group>
                                <x-label required>E-mail</x-label>
                                <x-input type="email" wire:model="email" />
                            </x-form-group>
                            <x-form-group>
                                <x-label required>Telefone</x-label>
                                <x-input type="text" wire:model="phone" x-mask="(99) 99999-9999"
                                    placeholder="(11) 99999-9999" maxlength="15" />
                            </x-form-group>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-base-content mb-4">Endereço</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <x-form-group>
                                    <x-label required>Endereço</x-label>
                                    <x-input type="text" wire:model="address" />
                                </x-form-group>
                            </div>

                            <x-form-group>
                                <x-label required>CEP</x-label>
                                <x-input type="text" wire:model="zip_code" placeholder="00000-000"
                                    x-mask="99999-999" />
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Cidade</x-label>
                                <x-input type="text" wire:model="city" />
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Estado</x-label>
                                <x-select wire:model="state">
                                    <option value="">Selecione</option>
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
                        </div>
                    </div>

                    <!-- Status -->

                    <x-form-group>
                        <x-checkbox wire:model="is_active">
                            Empresa ativa
                        </x-checkbox>
                    </x-form-group>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('master.company.index', ['company' => $company->id]) }}"
                            class="btn btn-soft btn-error">
                            <x-carbon-arrow-left class="w-4 h-4" />
                            Cancelar
                        </a>
                        <x-button type="submit" class="btn btn-soft btn-info">
                            <x-carbon-save class="w-4 h-4" />
                            Atualizar Empresa
                        </x-button>
                    </div>
                </x-form>
            </x-card-body>
        </x-card>
    </div>
</div>
