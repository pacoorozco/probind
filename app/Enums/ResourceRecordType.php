<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Arr;

final class ResourceRecordType extends Enum implements LocalizedEnum
{
    const A     = 'A';
    const AAAA  = 'AAAA';
    const CNAME = 'CNAME';
    const MX    = 'MX';
    const NAPTR = 'NAPTR';
    const NS    = 'NS';
    const PTR   = 'PTR';
    const SRV   = 'SRV';
    const TXT   = 'TXT';

    public static function asArrayForReverseZone(): array
    {
        return [
            self::PTR,
            self::TXT,
            self::NS,
        ];
    }

    public static function asArrayForForwardZone(): array
    {
        return Arr::except(self::asArray(), [self::PTR]);
    }
}
