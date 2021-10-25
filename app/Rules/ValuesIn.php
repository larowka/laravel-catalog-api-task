<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValuesIn implements Rule
{
    private array $allowedValues;
    private string $firstInvalidValue;

    public function __construct(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    public function passes($attribute, $value)
    {
        if (!is_countable($value))
            return false;

        foreach ($value as $item) {
            if (!in_array($item, $this->allowedValues)) {
                $this->firstInvalidValue = $item;
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return sprintf("Value '%s' is not valid", $this->firstInvalidValue);
    }
}
