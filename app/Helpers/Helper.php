<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2016 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/probind
 */


namespace App\Helpers;


class Helper
{

    /**
     * Helper to transform a bool to a label active / inactive.
     *
     * @param bool        $condition        This is the bool value to be mapped.
     * @param string|null $text             This text will be concatenate to label. Optional.
     * @param array|null  $mapStatusToLabel The map to transform bool to label. Optional.
     *
     * @return string
     */
    public static function addStatusLabel(bool $condition, string $text = null, array $mapStatusToLabel = null): string
    {
        if (is_null($mapStatusToLabel)) {
            $mapStatusToLabel = [
                '0' => '<span class="label label-default">' . (string)__('general.inactive') . '</span>',
                '1' => '',
            ];
        }
        $concatenationSpace = empty($text) ? '' : ' ';
        return trim($text . $concatenationSpace . $mapStatusToLabel[$condition], ' ');
    }

}
