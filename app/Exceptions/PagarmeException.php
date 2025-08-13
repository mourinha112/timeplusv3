<?php

namespace App\Exceptions;

use Exception;

class PagarmeException extends Exception
{
    protected $errors;

    public function __construct(string $message, int $code = 400, array $errors = [])
    {
        parent::__construct($message, $code);

        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function render()
    {
        return response()->json([
            'code'    => $this->getCode(),
            'message' => $this->getMessage(),
            'line'    => $this->getLine(),
            'file'    => $this->getFile(),
            'errors'  => $this->getErrors(),
        ], $this->getCode());
    }
}
