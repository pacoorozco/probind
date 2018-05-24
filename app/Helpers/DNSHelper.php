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
 * @link        https://github.com/pacoorozco/probind
 */

namespace App\Helpers;

class DNSHelper
{
    /**
     * Return an array of valid record types with its description.
     *
     * @link https://en.wikipedia.org/wiki/List_of_DNS_record_types
     *
     * @return array
     */
    public static function getValidRecordTypesWithDescription(): array
    {
        return [
            'A'     => trans('record/model.types_mapper.A'),
            'AAAA'  => trans('record/model.types_mapper.AAAA'),
            'CNAME' => trans('record/model.types_mapper.CNAME'),
            'MX'    => trans('record/model.types_mapper.MX'),
            'NAPTR' => trans('record/model.types_mapper.NAPTR'),
            'NS'    => trans('record/model.types_mapper.NS'),
            'PTR'   => trans('record/model.types_mapper.PTR'),
            'SRV'   => trans('record/model.types_mapper.SRV'),
            'TXT'   => trans('record/model.types_mapper.TXT'),
        ];
    }

    /**
     * Return an array of valid record types.
     *
     * @return array
     */
    public static function getValidRecordTypes(): array
    {
        return array_keys(self::getValidRecordTypesWithDescription());
    }

    /**
     * Check if a record type is valid.
     *
     * @param string $type
     *
     * @return bool
     */
    public static function validateRecordType(string $type): bool
    {
        return in_array($type, self::getValidRecordTypes());
    }
}
