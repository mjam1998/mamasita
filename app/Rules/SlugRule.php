<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class SlugRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^[-a-zA-Z0-9_.]+$/u', $value)) {
            $fail(__('فیلد :attribute باید فقط شامل حروف انگلیسی، اعداد، خط تیره (‑)، خط زیر (_) و نقطه (.) باشد.', [$attribute]));
        }
    }
}
