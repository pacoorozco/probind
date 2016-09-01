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
 * Record model, represents a DNS entry on a specified zone.
 *
 * @property integer $id
 * @property string $name
 * @property integer $ttl
 * @property string $type
 * @property integer $priority
 * @property string $data
 * @property object $zone
 */
class Record extends Model
{

    /**
     * Valid Record Types. These will be used to validation.
     *
     * @var array
     */
    public static $validInputTypes = [
        'A'     => 'A (IPv4 address)',
        'AAAA'  => 'AAAA (IPv6 address)',
        'CNAME' => 'CNAME (canonical name)',
        'MX'    => 'MX (mail exchange)',
        'NS'    => 'NS (name server)',
        'PTR'   => 'PTR (pointer)',
        'SRV'   => 'SRV (service locator)',
        'TXT'   => 'TXT (text)',
    ];
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
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['zone'];

    /**
     * Set the Record's type uppercase.
     *
     * @param  string $value
     * @return string|null
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtoupper($value);
    }

    /**
     * Set the Record's name lowercase.
     *
     * @param  string $value
     * @return string|null
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * Set the Record's data lowercase.
     *
     * @param  string $value
     * @return string|null
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = strtolower($value);
    }

    /**
     * A record belongs to a zone.
     */
    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    /**
     * Returns a formatted Resource Record for a record
     *
     * @return string
     */
    public function getResourceRecord()
    {
        if ($this->ttl) {
            return sprintf("%-40s %d\tIN\t%s\t%s", $this->name, $this->ttl, $this->type, $this->data);
        }

        return sprintf("%-40s \tIN\t%s\t%s", $this->name, $this->type, $this->data);
    }
}
