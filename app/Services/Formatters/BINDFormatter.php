<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Services\Formatters;


use Illuminate\Support\Collection;

/**
 * BINDFormatter formats the data ensuring compatibility with the BIND format.
 *
 * @see https://downloads.isc.org/isc/bind9/9.17.19/doc/arm/html/reference.html#zone-file
 */
class BINDFormatter
{
    public static function deletedZones(Collection $deletedZones): string
    {
        return $deletedZones
            ->pluck('domain')
            ->join(PHP_EOL);
    }
}
