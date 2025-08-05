<?php

namespace App\Helpers;

class Helper
{
    /**
     * Validação de CPF.
     *
     * @param string $cpf
     * @return boolean
     */
    public static function validateCpf(string $cpf): bool
    {
        /* Remove caracteres não numéricos */
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        /* Verifica se o CPF tem 11 dígitos */
        if (strlen($cpf) != 11) {
            return false;
        }

        /* Verifica se todos os dígitos são iguais */
        if (preg_match('/^(\d)\1+$/', $cpf)) {
            return false;
        }

        /* Calcula o primeiro dígito verificador */
        $soma = 0;

        for ($i = 0; $i < 9; $i++) {
            $soma += (int) $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $dv1   = ($resto < 2) ? 0 : 11 - $resto;

        /* Verifica o primeiro dígito verificador */
        if ($cpf[9] != $dv1) {
            return false;
        }

        /* Calcula o segundo dígito verificador */
        $soma = 0;

        for ($i = 0; $i < 10; $i++) {
            $soma += (int) $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $dv2   = ($resto < 2) ? 0 : 11 - $resto;

        /* Verifica o segundo dígito verificador */
        if ($cpf[10] != $dv2) {
            return false;
        }

        return true;
    }
}
