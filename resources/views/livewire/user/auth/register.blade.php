<div>
    <form wire:submit.prevent="submit">
        <input wire:model="name" placeholder="Nome">
        <input wire:model="email" placeholder="E-mail">
        <input wire:model="password" type="password" placeholder="Senha">
        <input wire:model="password_confirmation" type="password" placeholder="Confirmação de senha">
        <button>Registrar</button>
        <button type="reset">Resetar</button>

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</div>
