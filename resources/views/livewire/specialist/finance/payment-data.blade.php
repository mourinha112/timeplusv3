<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-wallet class="w-8 text-info" />
            Dados de Pagamento
        </x-title>
        <x-subtitle>
            Configure seus dados bancários para receber os pagamentos das sessões realizadas
        </x-subtitle>
    </x-heading>

    {{-- Aviso de status --}}
    @if ($hasProfile)
        @if ($is_verified)
            <div class="alert alert-success mb-6">
                <x-carbon-checkmark class="w-5 h-5" />
                <span>Seus dados de pagamento estão <strong>verificados</strong> e prontos para receber repasses.</span>
            </div>
        @else
            <div class="alert alert-warning mb-6">
                <x-carbon-warning class="w-5 h-5" />
                <span>Seus dados de pagamento estão cadastrados, mas ainda <strong>não foram verificados</strong>. Os
                    repasses serão liberados após a verificação.</span>
            </div>
        @endif
    @else
        <div class="alert alert-info mb-6">
            <x-carbon-information class="w-5 h-5" />
            <span>Cadastre seus dados de pagamento para começar a receber os repasses das sessões realizadas.</span>
        </div>
    @endif

    <x-card class="w-full">
        <x-card-body>
            <x-form wire:submit="save">
                {{-- Dados do Titular --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-base-content mb-4 flex items-center gap-2">
                        <x-carbon-user class="w-5 h-5 text-info" />
                        Dados do Titular
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form-group>
                            <x-label required>Nome Completo do Titular</x-label>
                            <x-input type="text" wire:model="holder_name"
                                placeholder="Nome como consta na conta/PIX" />
                            @error('holder_name')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </x-form-group>

                        <x-form-group>
                            <x-label required>CPF do Titular</x-label>
                            <x-input type="text" wire:model="holder_cpf" placeholder="000.000.000-00"
                                x-mask="999.999.999-99" />
                            @error('holder_cpf')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </x-form-group>
                    </div>
                </div>

                <div class="divider"></div>

                {{-- Tipo de Pagamento --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-base-content mb-4 flex items-center gap-2">
                        <x-carbon-currency-dollar class="w-5 h-5 text-info" />
                        Forma de Recebimento
                    </h3>

                    <div class="flex gap-4 mb-6">
                        <label
                            class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer transition-all {{ $payment_type === 'pix' ? 'border-info bg-info/10' : 'border-base-300' }}">
                            <input type="radio" wire:model.live="payment_type" value="pix" class="radio radio-info" />
                            <div>
                                <span class="font-semibold">PIX</span>
                                <p class="text-sm text-base-content/70">Receba instantaneamente via PIX</p>
                            </div>
                        </label>

                        <label
                            class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer transition-all {{ $payment_type === 'bank_account' ? 'border-info bg-info/10' : 'border-base-300' }}">
                            <input type="radio" wire:model.live="payment_type" value="bank_account"
                                class="radio radio-info" />
                            <div>
                                <span class="font-semibold">Conta Bancária</span>
                                <p class="text-sm text-base-content/70">Transferência para sua conta</p>
                            </div>
                        </label>
                    </div>

                    {{-- Campos PIX --}}
                    @if ($payment_type === 'pix')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-base-200 rounded-lg">
                            <x-form-group>
                                <x-label required>Tipo de Chave PIX</x-label>
                                <x-select wire:model.live="pix_key_type">
                                    <option value="cpf">CPF</option>
                                    <option value="email">E-mail</option>
                                    <option value="phone">Telefone</option>
                                    <option value="random">Chave Aleatória</option>
                                </x-select>
                                @error('pix_key_type')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Chave PIX</x-label>
                                @if ($pix_key_type === 'cpf')
                                    <x-input type="text" wire:model="pix_key" placeholder="000.000.000-00"
                                        x-mask="999.999.999-99" />
                                @elseif ($pix_key_type === 'phone')
                                    <x-input type="text" wire:model="pix_key" placeholder="(11) 99999-9999"
                                        x-mask="(99) 99999-9999" />
                                @elseif ($pix_key_type === 'email')
                                    <x-input type="email" wire:model="pix_key" placeholder="seu@email.com" />
                                @else
                                    <x-input type="text" wire:model="pix_key"
                                        placeholder="Chave aleatória do seu banco" />
                                @endif
                                @error('pix_key')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </x-form-group>
                        </div>
                    @endif

                    {{-- Campos Conta Bancária --}}
                    @if ($payment_type === 'bank_account')
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4 bg-base-200 rounded-lg">
                            <x-form-group>
                                <x-label required>Banco</x-label>
                                <x-select wire:model.live="bank_code">
                                    <option value="">Selecione o banco</option>
                                    @foreach ($this->banks as $code => $name)
                                        <option value="{{ $code }}">{{ $code }} - {{ $name }}
                                        </option>
                                    @endforeach
                                </x-select>
                                @error('bank_code')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Agência</x-label>
                                <x-input type="text" wire:model="agency" placeholder="0001" maxlength="10" />
                                @error('agency')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </x-form-group>

                            <x-form-group>
                                <x-label required>Tipo de Conta</x-label>
                                <x-select wire:model="account_type">
                                    <option value="checking">Conta Corrente</option>
                                    <option value="savings">Conta Poupança</option>
                                </x-select>
                                @error('account_type')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </x-form-group>

                            <x-form-group class="md:col-span-2 lg:col-span-2">
                                <x-label required>Número da Conta</x-label>
                                <div class="flex gap-2">
                                    <x-input type="text" wire:model="account_number" placeholder="12345678"
                                        maxlength="20" class="flex-1" />
                                    <span class="flex items-center">-</span>
                                    <x-input type="text" wire:model="account_digit" placeholder="0" maxlength="2"
                                        class="w-16 text-center" />
                                </div>
                                @error('account_number')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </x-form-group>
                        </div>
                    @endif
                </div>

                <div class="divider"></div>

                {{-- Informações sobre repasse --}}
                <div class="bg-info/10 border border-info/30 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <x-carbon-information class="w-6 h-6 text-info flex-shrink-0 mt-0.5" />
                        <div>
                            <h4 class="font-semibold text-base-content mb-1">Como funciona o repasse?</h4>
                            <ul class="text-sm text-base-content/70 space-y-1">
                                <li>• O valor da sessão é recebido pela plataforma quando o paciente efetua o
                                    pagamento.</li>
                                <li>• Após a confirmação do pagamento, o repasse é processado automaticamente.</li>
                                <li>• Você receberá o valor líquido diretamente na conta/PIX cadastrado.</li>
                                <li>• É possível acompanhar todos os repasses na seção "Minhas Finanças".</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Botões --}}
                <div class="flex justify-end gap-3">
                    <a wire:navigate href="{{ route('specialist.appointment.index') }}" class="btn btn-ghost">
                        Cancelar
                    </a>
                    <x-button type="submit" class="btn-info" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            <x-carbon-save class="w-4 h-4" />
                            Salvar Dados de Pagamento
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-xs"></span>
                            Salvando...
                        </span>
                    </x-button>
                </div>
            </x-form>
        </x-card-body>
    </x-card>
</div>
