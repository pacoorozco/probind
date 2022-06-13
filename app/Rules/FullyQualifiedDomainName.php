<?php

namespace App\Rules;

use Badcow\DNS\Validator;
use Illuminate\Contracts\Validation\Rule;

/**
 * Validate a valid Fully Qualified Domain Name (FQDN) in accordance with RFC 952
 * {@link https://tools.ietf.org/html/rfc952} and RFC 1123
 * {@link https://tools.ietf.org/html/rfc1123}.
 */
class FullyQualifiedDomainName implements Rule
{
    public function passes($attribute, $value): bool
    {
        if ('.' === $value) {
            return false;
        }

        return Validator::fullyQualifiedDomainName($value);
    }

    public function message(): string
    {
        return 'The :attribute must be a Fully Qualified Domain Name (FQDN).';
    }
}
