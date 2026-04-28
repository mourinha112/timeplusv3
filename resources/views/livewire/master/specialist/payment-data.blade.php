<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-finance class="w-8 text-info" />
            Dados de Pagamento — {{ $specialist->name }}
        </x-title>
        <x-subtitle>
            Cadastre ou edite os dados bancários do especialista para receber repasses.
        </x-subtitle>
    </x-heading>

    <x-card>
        <x-card-body>
            <x-form wire:submit="save">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Titular</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form-group>
                            <x-label required>Nome do titular</x-label>
                            <x-input wire:model="holder_name" />
                        </x-form-group>

                        <x-form-group>
                            <x-label required>CPF do titular</x-label>
                            <x-input wire:model="holder_cpf" x-mask="999.999.999-99"
                                placeholder="000.000.000-00" />
                        </x-form-group>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Modalidade</h3>
                    <x-form-group>
                        <x-label required>Forma de recebimento</x-label>
                        <x-select wire:model.live="payment_type">
                            <option value="pix">PIX</option>
                            <option value="bank_account">Conta bancária</option>
                        </x-select>
                    </x-form-group>
                </div>

                @if ($payment_type === 'pix')
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Dados PIX</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form-group>
                                <x-label required>Tipo de chave</x-label>
                                <x-select wire:model="pix_key_type">
                                    <option value="">Selecione</option>
                                    <option value="cpf">CPF</option>
                                    <option value="email">E-mail</option>
                                    <option value="phone">Telefone</option>
                                    <option value="random">Aleatória</option>
                                </x-select>
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Chave PIX</x-label>
                                <x-input wire:model="pix_key" />
                            </x-form-group>
                        </div>
                    </div>
                @else
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Dados Bancários</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-form-group>
                                <x-label required>Código do banco</x-label>
                                <x-input wire:model="bank_code" placeholder="Ex.: 001" />
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Nome do banco</x-label>
                                <x-input wire:model="bank_name" />
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Agência</x-label>
                                <x-input wire:model="agency" />
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Conta</x-label>
                                <x-input wire:model="account_number" />
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Dígito</x-label>
                                <x-input wire:model="account_digit" maxlength="2" />
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Tipo de conta</x-label>
                                <x-select wire:model="account_type">
                                    <option value="">Selecione</option>
                                    <option value="checking">Corrente</option>
                                    <option value="savings">Poupança</option>
                                </x-select>
                            </x-form-group>
                        </div>
                    </div>
                @endif

                <div>
                    <h3 class="text-lg font-semibold mb-4">Repasse</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form-group>
                            <x-label required>Taxa da plataforma (%)</x-label>
                            <x-input type="number" step="0.01" min="0" max="100"
                                wire:model="platform_fee_percentage" />
                            <small class="text-base-content/60">
                                Padrão é 20% para particular. TimePlus tem repasse fixo de R$30 (valor não usa esta taxa).
                            </small>
                        </x-form-group>

                        <x-form-group>
                            <x-checkbox wire:model="is_verified">
                                Dados aprovados / verificados
                            </x-checkbox>
                            <small class="text-base-content/60">
                                Marque após validar os dados manualmente.
                            </small>
                        </x-form-group>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a wire:navigate
                        href="{{ route('master.specialist.personal-data.show', ['specialist' => $specialist->id]) }}"
                        class="btn btn-soft btn-error">
                        <x-carbon-arrow-left class="w-4 h-4" />
                        Voltar
                    </a>
                    <x-button type="submit" class="btn btn-info">
                        <x-carbon-save class="w-4 h-4" />
                        Salvar Dados de Pagamento
                    </x-button>
                </div>
            </x-form>
        </x-card-body>
    </x-card>
</div>
