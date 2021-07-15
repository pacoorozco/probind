<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class FullyQualifiedDomainName.
 *
 * Validates a Fully Qualified Domain Name (FQDN).
 * @see https://en.m.wikipedia.org/wiki/Fully_qualified_domain_name
 */
class FullyQualifiedDomainName implements Rule
{
    const VALID_FQDN_REGEXP = '/^(?=.{1,253}\.$)(?:(?!-|[^.]+_)[A-Za-z0-9-_]{1,63}(?<!-)(?:\.|$)){2,}$/';

    public function passes($attribute, $value): bool
    {
        return 1 === preg_match(self::VALID_FQDN_REGEXP, $value);
    }

    public function message(): string
    {
        return 'The :attribute must be a Fully Qualified Domain Name (FQDN).';
    }
}
