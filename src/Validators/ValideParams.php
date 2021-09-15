<?php

namespace Mouadbnl\Judge0\Validators;

use Illuminate\Contracts\Validation\Rule;
class ValideParams implements Rule
{
    protected $message;
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if(! is_array($value)) return false;
        foreach ($value as $key => $val) {
            $validator = validator([$key => $val], [
                $key => [new ValideParamsKey],
            ]);
            if ($validator->fails()) {
                $this->message = $validator->errors()->first();
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}