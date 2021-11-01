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
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Server model.
 *
 * @property int $id The object unique id.
 * @property string $hostname The hostname of this server. Will be used on NS records.
 * @property string $ip_address The IP address of this server. Will be used for glue records.
 * @property string $type The type of this server. Could be 'master' or 'slave'.
 * @property bool $push_updates This flag determines if this server must be pushed with zone files.
 * @property bool $ns_record This flag determines if this server will be included as NS on zone files.
 * @property bool active         This flags determines if this server is active or inactive.
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
        'slave',
    ];
    /**
     * The database table used by the model.
     */
    protected $table = 'servers';
    protected $fillable = [
        'hostname',
        'ip_address',
        'type',
        'ns_record',
        'active',
        'push_updates',
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
     * @param  string  $eventName  The event could be: saved, updated or deleted.
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return sprintf('%s', trans('server/messages.activity.' . $eventName, [
            'hostname' => $this->hostname,
            'type'     => $this->type,
        ]));
    }

    /**
     * Set the Server's hostname lowercase.
     *
     * @param  string  $value
     */
    public function setHostnameAttribute(string $value)
    {
        $this->attributes['hostname'] = strtolower($value);
    }

    /**
     * Set the Server's ip_address lowercase.
     *
     * @param  string  $value
     */
    public function setIpAddressAttribute(string $value)
    {
        $this->attributes['ip_address'] = strtolower($value);
    }

    /**
     * Set the Server's type lowercase and valid value.
     *
     * If $value does not exists on Server::$validServerTypes, we return de first one.
     *
     * @param  string  $value
     */
    public function setTypeAttribute(string $value)
    {
        $lowerCaseValue = strtolower($value);
        $this->attributes['type'] = in_array($lowerCaseValue, self::$validServerTypes)
            ? $lowerCaseValue
            : head(self::$validServerTypes);
    }

    /**
     * Returns a formatted NS record for a server.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getNSRecord(): string
    {
        return sprintf("%-32s IN\tNS\t%s.", ' ', $this->hostname);
    }

    /**
     * Scope a query to include servers thant can be pushed.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPushCapability(Builder $query): Builder
    {
        return $query
            ->where('push_updates', true)
            ->where('active', true);
    }
}
