<?php

namespace App\Rules;

use Badcow\DNS\Validator;
use Illuminate\Contracts\Validation\Rule;

/**
 * Validate the name for a Resource Record. This is distinct from validating a hostname in that this function
 * will permit '@' and wildcards as well as underscores used in SRV records.
 */
class ResourceRecordName implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Validator::resourceRecordName($value);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid resource name.';
    }
}
