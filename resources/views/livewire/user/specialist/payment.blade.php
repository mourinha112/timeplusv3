<div>
    @error('payment')
        <div role="alert" class="alert alert-error my-4">
            <x-carbon-info class="w-5" />
            <p>{{ $message }}</p>
        </div>
    @enderror

    @if ($this->appointment->payments->count() > 0)
        {{-- QR Code PIX --}}
        @if ($this->appointment->payments->first()->billing_type === 'pix')
            <livewire:user.appointment.billing-types.pix :appointment="$this->appointment" />
        @endif

        {{-- Cartão de Crédito --}}
        @if ($this->appointment->payments->first()->billing_type === 'credit_card')
            <livewire:user.appointment.billing-types.credit-card :appointment="$this->appointment" />
        @endif
    @else
        <div class="mb-4 text-left">
            <h1 class="text-3xl dark:text-base-content mb-4">Método de pagamento</h1>
            <p class="text-base-content/70">Selecione o método de pagamento para a consulta. </p>
        </div>

        <fieldset class="flex flex-col gap-2 justify-left my-4">
            <label class="label">
                <input wire:model.live="payment_method" name="assistant_qty" type="radio" value="pix"
                    checked="checked" class="radio radio-sm radio-info" />
                PIX
            </label>
            <label class="label">
                <input wire:model.live="payment_method" name="assistant_qty" type="radio" value="credit_card"
                    class="radio radio-sm radio-info" />
                Cartão de Crédito
            </label>
        </fieldset>

        <button class="btn btn-block btn-info" wire:click="selectBillingType">
            Avançar
        </button>
    @endif
</div>
