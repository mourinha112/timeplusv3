<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FormattedCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $value)) {
            $fail('O :attribute está no formato inválido.');
        }
    }
}
