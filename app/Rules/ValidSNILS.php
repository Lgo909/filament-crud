<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidSNILS implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->validateSnils($value)) {
            $fail('The :attribute must be uppercase.');
        }
    }
    public function validateSnils($snils, &$error_message = null, &$error_code = null): bool

    {

        $result = false;
        $snils = (string) $snils;
        if (!$snils) {
            $error_code = 1;
            $error_message = 'СНИЛС пуст';
        } elseif (preg_match('/[^0-9]/', $snils)) {
            $error_code = 2;
            $error_message = 'СНИЛС может состоять только из цифр';
        } elseif (strlen($snils) !== 11) {
            $error_code = 3;
            $error_message = 'СНИЛС может состоять только из 11 цифр';
        } else {
            $sum = 0;
            for ($i = 0; $i < 9; $i++) {
                $sum += (int) $snils[$i] * (9 - $i);
            }
            $check_digit = 0;
            if ($sum < 100) {
                $check_digit = $sum;
            } elseif ($sum > 101) {
                $check_digit = $sum % 101;
                if ($check_digit === 100) {
                    $check_digit = 0;
                }
            }
            if ($check_digit === (int) substr($snils, -2)) {
                $result = true;
            } else {
                $error_code = 4;
                $error_message = 'Неправильное контрольное число';
            }
        }
        return $result;
    }

}
