<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Document implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): bool
    {
        //validate if the number has 11(cpf) or 14(cnpj) digits
        if (strlen($value) !== 11 && strlen($value) !== 14) {
            return false;
        }

        //validate if the document has only numbers
        for ($i = 0; $i < strlen($value); $i++) {
            if (!is_numeric($value[$i])) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return 'Insert a valid CPF or CNPJ!';
    }
}
