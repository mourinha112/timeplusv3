<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FormattedPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\(\d{2}\)\s\d{5}-\d{4}$/', $value)) {
            $fail('O :attribute está no formato inválido.');
        }
    }
}
