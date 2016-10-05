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

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

/**
 * Record model
 *
 * Represents a DNS entry on a specified zone.
 *
 * @property integer $id        The object unique id.
 * @property string  $name      The name of the record.
 * @property integer $ttl       The custom TTL value for this record.
 * @property string  $type      The type of record, must be one of Record::$validRecordTypes
 * @property integer $priority  The preference value for MX records.
 * @property string  $data      The data value for this record.
 * @property object  $zone      The zone object where this record belongs to.
 *
 * @link https://www.ietf.org/rfc/rfc1035.txt
 */
class Record extends Model
{
    use NullableFields;

    /**
     * The database table used by the model.
     */
    protected $table = 'records';
    protected $fillable = [
        'name',
        'type',
        'ttl',
        'data',
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name'     => 'string',
        'ttl'      => 'integer',
        'type'     => 'string',
        'priority' => 'integer',
        'data'     => 'string'
    ];
    /**
     * The attributes that should be casted to null if is empty.
     *
     * @var array
     */
    protected $nullable = [
        'ttl',
        'priority',
    ];
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['zone'];

    /**
     * Return an array with ALL valid Record types.
     *
     * @return array
     */
    public static function getAllValidRecordTypes() : array
    {
        return [
            'A'     => trans('record/model.types_mapper.A'),
            'AAAA'  => trans('record/model.types_mapper.AAAA'),
            'CNAME' => trans('record/model.types_mapper.CNAME'),
            'MX'    => trans('record/model.types_mapper.MX'),
            'NS'    => trans('record/model.types_mapper.NS'),
            'PTR'   => trans('record/model.types_mapper.PTR'),
            'SRV'   => trans('record/model.types_mapper.SRV'),
            'TXT'   => trans('record/model.types_mapper.TXT'),
        ];
    }

    /**
     * Set the Record's type uppercase.
     *
     * @param  string $value
     */
    public function setTypeAttribute(string $value)
    {
        $this->attributes['type'] = strtoupper($value);
    }

    /**
     * Set the Record's name lowercase.
     *
     * @param  string $value
     */
    public function setNameAttribute(string $value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * Set the Record's data lowercase.
     *
     * @param  string $value
     */
    public function setDataAttribute(string $value)
    {
        $this->attributes['data'] = strtolower($value);
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

    /**
     * Returns a formatted Resource Record for a record.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getResourceRecord() : string
    {
        if ($this->ttl) {
            return sprintf("%-40s %d\tIN\t%s\t%s", $this->name, $this->ttl, $this->type, $this->data);
        }

        return sprintf("%-40s \tIN\t%s\t%s", $this->name, $this->type, $this->data);
    }
}
