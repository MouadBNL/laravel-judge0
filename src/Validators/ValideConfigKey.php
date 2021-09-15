<?php

namespace Mouadbnl\Judge0\Validators;

use Illuminate\Contracts\Validation\Rule;
use Mouadbnl\Judge0\Services\SubmissionConfig;

class ValideConfigKey implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $keys = SubmissionConfig::CONFIG_KEYS;
        foreach ($keys as $key => $val) {
            if($attribute == $key) return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valide config key.';
    }
}