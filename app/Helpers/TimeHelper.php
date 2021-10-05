<?php
/**
 *  ProBIND v3 - Professional DNS management made easy.
 *
 *  Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace App\Helpers;

class TimeHelper
{
    /**
     * Converts a BIND-style timeout(1D, 2H, 15M) to seconds.
     *
     * @param  string  $time  Time to convert.
     * @return int
     */
    public static function parseToSeconds(string $time): int
    {
        if (is_numeric($time)) {
            // Already a number. Return.
            return intval($time);
        }

        $pattern = '/([0-9]+)([a-zA-Z]+)/';
        $split = preg_split($pattern, $time, null,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if (false === $split) {
            // Handle error on preg_split.
            return 0;
        }

        $seconds = 0;
        while (count($split)) {
            [$value, $key] = array_splice($split, 0, 2);
            $seconds += TimeHelper::translateCharToSeconds($key, $value);
        }

        return $seconds;
    }

    /**
     * This function calculates and translate a character to seconds.
     *
     * @param  string  $modifier  The modifier char: Week, Day, Minute, Month, Second
     * @param  int  $value  The amount of modifier.
     * @return int
     */
    private static function translateCharToSeconds(string $modifier, int $value = 1): int
    {
        // This map translate a character to seconds.
        $translateToSeconds = [
            'W' => 1 * 60 * 60 * 24 * 7, // Week
            'D' => 1 * 60 * 60 * 24, // Day
            'H' => 1 * 60 * 60, // Hour
            'M' => 1 * 60, // Minute
            'S' => 1, // Second
        ];

        return intval($value * $translateToSeconds[strtoupper($modifier)]);
    }
}
