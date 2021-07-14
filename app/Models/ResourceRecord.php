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

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Record model.
 *
 * Represents a DNS entry on a specified zone.
 *
 * Valid records types are defined on DNSHelper Class.
 *
 * @property int $id        The object unique id.
 * @property string $name      The name of the record.
 * @property int $ttl       The custom TTL value for this record.
 * @property string $type      The type of record, must be one of Record::$validRecordTypes
 * @property int $priority  The preference value for MX records.
 * @property string $data      The data value for this record.
 * @property object $zone      The zone object where this record belongs to.
 *
 * @link https://www.ietf.org/rfc/rfc1035.txt
 */
class Record extends Model
{

    /**
     * The database table used by the model.
     */
    protected $table = 'records';
    protected $fillable = [
        'name',
        'type',
        'ttl',
        'priority',
        'data',
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'ttl' => 'integer',
        'type' => 'string',
        'priority' => 'integer',
        'data' => 'string',
    ];
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['zone'];

    /**
     * Set the Record's type uppercase.
     *
     * @param  string  $value
     */
    public function setTypeAttribute(string $value)
    {
        $this->attributes['type'] = strtoupper($value);
    }

    /**
     * Set the Record's name lowercase.
     *
     * @param  string  $value
     */
    public function setNameAttribute(string $value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * A record belongs to a zone.
     *
     * This is a one-to-one relationship.
     */
    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function formatResourceRecord(): string
    {
        switch ($this->type) {
            case 'NS':
                return $this->formatNSResourceRecord();
            case 'MX':
                return $this->formatMXResourceRecord();
            case 'SRV':
                return $this->formatSRVResourceRecord();
            case 'NAPTR':
                return $this->formatNAPTRResourceRecord();
            default:
                // continue
        }

        return sprintf(
            "%-40s %s\tIN\t%s\t%s",
            $this->name,
            $this->ttl ?: '',
            $this->type,
            $this->data
        );
    }

    private function formatNSResourceRecord(): string
    {
        return sprintf(
            "%-40s %s\tIN\tNS\t%s",
            $this->name,
            $this->ttl ?: '',
            $this->data
        );
    }

    private function formatMXResourceRecord(): string
    {
        return sprintf(
            "%-40s %s\tIN\tMX\t%s %s",
            $this->name,
            $this->ttl ?: '',
            $this->priority,
            $this->data
        );
    }

    private function formatSRVResourceRecord(): string
    {
        return sprintf(
            "%-40s %s\tIN\tSRV\t%s %s",
            $this->name,
            $this->ttl ?: '',
            $this->priority,
            $this->data
        );
    }

    private function formatNAPTRResourceRecord(): string
    {
        return sprintf(
            "%-40s %s\tIN\tNAPTR\t%s %s",
            $this->name,
            $this->ttl ?: '',
            $this->priority,
            $this->data
        );
    }
}
