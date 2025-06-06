<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Mathepa\Expression;

class FormulaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if (!$value) {
            return true;
        }

        try {
            $parser = new Expression($value);

            for ($i = 0; $i < 20000; $i++) {
                $parser->setVariable('C' . $i, 1);
            }

            $parser->evaluate();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute deve conter uma fórmula válida.';
    }
}
