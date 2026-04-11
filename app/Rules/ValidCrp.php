<?php

namespace App\Rules;

use App\Models\State;
use App\Services\Consultar\{ConsultarService, ConsultarServiceException};
use Closure;
use Illuminate\Contracts\Validation\{DataAwareRule, ValidationRule};

class ValidCrp implements ValidationRule, DataAwareRule
{
    private array $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $stateId = $this->data['state_id'] ?? null;

        if (empty($stateId)) {
            $fail('Selecione o estado (UF) do conselho para validar o CRP.');

            return;
        }

        $state = State::find($stateId);

        if (!$state) {
            $fail('Estado selecionado é inválido.');

            return;
        }

        try {
            $result = app(ConsultarService::class)->consultarCrp($state->abbreviation, (string) $value);
        } catch (ConsultarServiceException $e) {
            $fail('Não foi possível validar o CRP no momento. Tente novamente em instantes.');

            return;
        }

        if (!$result['found']) {
            $fail('CRP não encontrado no Conselho Regional de Psicologia. Consulte o CRP para regularizar.');

            return;
        }

        if (!$result['active']) {
            $fail('Este CRP consta como inativo no Conselho Regional de Psicologia. Consulte o CRP para regularizar.');
        }
    }
}
