<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Translation\PotentiallyTranslatedString;

class Document implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) !== 11 && strlen($value) !== 14) {
            $fail('Insert a valid CPF or CNPJ!');
        }

        if (!is_numeric($value)) {
            $fail('Insert a valid CPF or CNPJ!');
        }
    }

    public function failure(): JsonResponse
    {
        return response()->json([
            'message' => 'Insert a valid CPF or CNPJ!'
        ], 422);
    }
}
