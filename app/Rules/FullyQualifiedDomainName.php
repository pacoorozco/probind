<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class FullyQualifiedDomainName
 *
 * Validates a Fully Qualified Domain Name (FQDN).
 * @see https://en.m.wikipedia.org/wiki/Fully_qualified_domain_name
 *
 * @package App\Rules
 */
class FullyQualifiedDomainName implements Rule
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
        return (1 === preg_match('/^(?=.{1,253}\.$)(?:(?!-|[^.]+_)[A-Za-z0-9-_]{1,63}(?<!-)(?:\.|$)){2,}$/', $value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a Fully Qualified Domain Name (FQDN).';
    }
}
