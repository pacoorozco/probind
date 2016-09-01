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
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Server model
 *
 * @property integer $id
 * @property string $hostname
 * @property string $ip_address
 * @property string $type
 * @property boolean $push_updates
 * @property boolean $ns_record
 * @property boolean active *
 */
class Server extends Model
{

    use LogsActivity;

    /**
     * Valid Server Types. These will be used to validation.
     *
     * @var array
     */
    public static $validServerTypes = [
        'master',
        'slave'
    ];
    /**
     * The database table used by the model.
     */
    protected $table = 'servers';
    protected $fillable = [
        'hostname',
        'ip_address',
        'type',
        'active'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'hostname'   => 'string',
        'ip_address' => 'string',
        'type'       => 'string',
    ];

    /**
     * Returns a customized message for Activity Log.
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName)
    {
        return trans('server/messages.activity.' . $eventName, [
            'hostname' => $this->hostname,
            'type'     => $this->type
        ]);
    }

    /**
     * Set the Server's hostname lowercase.
     *
     * @param  string $value
     * @return string|null
     */
    public function setHostnameAttribute($value)
    {
        $this->attributes['hostname'] = strtolower($value);
    }

    /**
     * Set the Server's ip_address lowercase.
     *
     * @param  string $value
     * @return string|null
     */
    public function setIpAddressAttribute($value)
    {
        $this->attributes['ip_address'] = strtolower($value);
    }

    /**
     * Set the Server's type lowercase and valid value.
     *
     * If $value does not exists on Server::$validServerTypes, we return de first one.
     *
     * @param  string $value
     * @return string|null
     */
    public function setTypeAttribute($value)
    {
        $lowerCaseValue = strtolower($value);
        $this->attributes['type'] = in_array($lowerCaseValue, self::$validServerTypes)
            ? $lowerCaseValue
            : head(self::$validServerTypes);
    }

    /**
     * Returns a formatted NS record for a server
     *
     * @return string
     */
    public function getNSRecord()
    {
        return sprintf("%-32s IN\tNS\t%s.", ' ', $this->hostname);
    }

    /**
     * Scope a query to include servers thant can be pushed.
     *
     * Criteria:
     *     - Has push_updates flag
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPushCapability($query)
    {
        return $query->where('push_updates', true);
    }
}
