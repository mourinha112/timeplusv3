<div>
    @error('payment')
        <div role="alert" class="alert alert-error my-4">
            <x-carbon-warning class="w-5" />
            <p>{{ $message }}</p>
        </div>
    @enderror


    @if ($this->ride->payments->count() > 0)
        {{-- QR Code PIX --}}
        @if ($this->ride->payments->first()->billing_type === 'pix')
            <livewire:user.ride.billing-types.pix :ride="$this->ride" />
        @endif

        {{-- Cartão de Crédito --}}
        @if ($this->ride->payments->first()->billing_type === 'credit_card')
            <livewire:user.ride.billing-types.credit-card :ride="$this->ride" />
        @endif
    @else
        <div class="mb-4 text-left">
            <h1 class="text-3xl dark:text-base-content mb-4">Método de pagamento</h1>
            <p class="text-base-content/70">Selecione o método de pagamento para a corrida. </p>
        </div>

        <fieldset class="flex flex-col gap-2 justify-left my-4">
            <label class="label">
                <input wire:model.live="payment_method" name="assistant_qty" type="radio" value="pix"
                    checked="checked" class="radio radio-sm radio-warning" />
                PIX
            </label>
            <label class="label">
                <input wire:model.live="payment_method" name="assistant_qty" type="radio" value="credit_card"
                    class="radio radio-sm radio-warning" />
                Cartão de Crédito
            </label>
        </fieldset>

        <button class="btn btn-block btn-warning" wire:click="selectBillingType">
            Avançar
        </button>
    @endif
</div>
