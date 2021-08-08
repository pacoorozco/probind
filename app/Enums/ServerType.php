<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ServerType extends Enum implements LocalizedEnum
{
    const Primary = 'primary';
    const Secondary = 'secondary';
}
