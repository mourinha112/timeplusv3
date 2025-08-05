<?php

namespace App\Rules;

use App\Helpers\Helper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidatedCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Helper::validateCpf($value)) {
            $fail('O campo :attribute é inválido.');
        }
    }
}
