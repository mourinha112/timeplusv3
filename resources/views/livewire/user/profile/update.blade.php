<div>
    <!-- Header do Perfil -->
    <div class="card card-md shadow-sm border border-slate-200 mb-10">
        <div class="card-body items-center justify-center">
            <!-- Avatar -->
            <h1 class="text-xl font-semibold">Foto de Perfil</h1>
            <x-carbon-user-avatar-filled class="text-info h-35 w-35" />

            <!-- Info do usuário -->
            <div>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Envie sua foto:</legend>
                    <input type="file" class="file-input" />
                    <label class="label">Imagem deve ser pelo menos 300x300px</label>
                </fieldset>
            </div>
        </div>
    </div>

    @if (session()->has('success_update_password'))
    <div class="alert alert-success">
        <span>{{ session('success_update_password') }}</span>
    </div>
    @endif

    <!-- Seção de Alteração de Senha -->
    <div class="card card-md shadow-sm border border-slate-200 mb-10 mt-2">
        <div class="card-body">
            <h2 class="text-2xl font-semibold text-base-content mb-6">Alterar Senha</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nova Senha:</legend>
                    <input type="password" class="input" wire:model="password" />
                    @error('password')
                    <span class="text-error">{{ $message }}</span>
                    @enderror
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Confirmação da Nova Senha:</legend>
                    <input type="password" class="input" wire:model="password_confirmation" />
                    @error('password_confirmation')
                    <span class="text-error">{{ $message }}</span>
                    @enderror
                </fieldset>
            </div>

            <div class="mt-6 flex justify-end">
                <button class="btn btn-info" wire:click="updatePassword">
                    <x-carbon-unlocked class="w-4 h-4" />
                    Alterar senha
                </button>
            </div>
        </div>
    </div>

    @if (session()->has('success_update_profile'))
    <div class="alert alert-success">
        <span>{{ session('success_update_profile') }}</span>
    </div>
    @endif

    <!-- Seção de Dados Pessoais -->
    <div class="card card-md shadow-sm border border-slate-200 mb-10 mt-2">
        <div class="card-body">
            <h2 class="text-2xl font-semibold text-base-content mb-6">Dados Pessoais</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome e Telefone -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nome:</legend>
                    <input type="text" wire:model="name" class="input" />
                    @error('name')
                    <span class="text-error">{{ $message }}</span>
                    @enderror
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Telefone:</legend>
                    <input type="text" wire:model="phone_number" class="input"  x-mask="(99) 99999-9999" />
                    @error('phone_number')
                    <span class="text-error">{{ $message }}</span>
                    @enderror
                </fieldset>

                <!-- CPF e Endereço -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">CPF:</legend>
                    <input type="text" class="input" wire:model="cpf" disabled x-mask="999.999.999-99" />
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Data de nascimento:</legend>
                    <input type="text" class="input" wire:model="birth_date" x-mask="99/99/9999" />
                    @error('birth_date')
                    <span class="text-error">{{ $message }}</span>
                    @enderror
                </fieldset>
            </div>

            <!-- Botão de Salvar -->
            <div class="mt-6 flex justify-end">
                <button class="btn btn-info" wire:click="updateProfile">
                    <x-carbon-save class="w-4 h-4" />
                    Salvar
                </button>
            </div>
        </div>
    </div>
</div>
