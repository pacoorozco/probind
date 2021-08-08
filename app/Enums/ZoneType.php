<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ZoneType extends Enum
{
    const Primary = 'master';
    const Secondary = 'slave';
}
