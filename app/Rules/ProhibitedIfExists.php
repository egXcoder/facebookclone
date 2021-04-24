<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ProhibitedIfExists implements Rule
{
    protected $attributeToCheck;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($attributeToCheck)
    {
        $this->attributeToCheck = $attributeToCheck;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !request()->has($this->attributeToCheck);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ":attribute is prohibited because $this->attributeToCheck already exists";
    }
}
