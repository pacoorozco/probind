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
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2016 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/probind
 *
 */

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Zone model, represents a DNS domain / subdomain.
 *
 * @property integer $id
 * @property string $domain
 * @property integer $serial
 * @property string $master
 * @property boolean $custom_settings
 * @property integer $refresh
 * @property integer $retry
 * @property integer $expire
 * @property integer $negative_ttl
 * @property integer $default_ttl
 * @property boolean $updated
 */
class Zone extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The database table used by the model.
     */
    protected $table = 'zones';
    protected $fillable = [
        'domain',
        'master',
        'refresh',
        'retry',
        'expire',
        'negative_ttl',
        'default_ttl'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'domain'          => 'string',
        'serial'          => 'integer',
        'master'          => 'string',
        'updated'         => 'boolean',
        'custom_settings' => 'boolean',
        'refresh'         => 'integer',
        'retry'           => 'integer',
        'expire'          => 'integer',
        'negative_ttl'    => 'integer',
        'default_ttl'     => 'integer',
    ];

    /**
     * Set the Zone's domain lowercase.
     *
     * @param  string $value
     * @return string|null
     */
    public function setDomainAttribute($value)
    {
        $this->attributes['domain'] = strtolower($value);
    }

    /**
     * Set the Zone's master lowercase.
     *
     * @param  string $value
     * @return string|null
     */
    public function setMasterAttribute($value)
    {
        $this->attributes['master'] = strtolower($value);
    }

    /**
     * A zone will have some records.
     */
    public function records()
    {
        return $this->hasMany('App\Record');
    }

    /**
     * Set Zone's serial parameter if needed.
     *
     * We only need to modify this field is has been pushed to a server.
     *
     * @param  boolean $force
     * @return integer
     */
    public function setSerialNumber($force = false)
    {
        if ($this->updated && ! $force) {
            return $this->serial;
        }

        $currentSerial = $this->serial;
        $nowSerial = Zone::createSerialNumber();

        $this->serial = ($currentSerial >= $nowSerial)
            ? $currentSerial + 1
            : $nowSerial;
        $this->save();

        return $this->serial;
    }

    /**
     * Create a new Serial Number based on a specified format
     *
     * @return integer
     */
    public static function createSerialNumber()
    {
        return intval(Carbon::now()->format('Ymd') . '01');
    }

    /**
     * Returns if this is a master zone.
     *
     * The DNS server is the primary source for information about this zone, and it stores
     * the master copy of zone data in a local file.
     *
     * @return bool
     */
    public function isMasterZone()
    {
        return ( ! $this->master);
    }

    /**
     * Marks / unmark pending changes on a zone.
     *
     * @param bool $value
     * @return bool
     */
    public function setPendingChanges($value = true)
    {
        if ($this->hasPendingChanges() != $value) {
            $this->updated = $value;
            $this->save();
        }

        return $this->updated;
    }

    /**
     * Returns if this zone has changes to send to servers.
     *
     * @return bool
     */
    public function hasPendingChanges()
    {
        return $this->updated;
    }

    /**
     * Returns the Default TTL for this zone
     *
     * @return int
     */
    public function getDefaultTTL()
    {
        return intval(($this->custom_settings) ? $this->default_ttl : \Registry::get('zone_default_default_ttl'));
    }

    /**
     * Returns a formatted SOA record of a zone
     *
     * @return string
     */
    public function getSOARecord()
    {
        $content = sprintf("%-16s IN\tSOA\t%s. %s. (\n", '@', $this->getPrimaryNameServer(),
            $this->getHostmasterEmail());
        $content .= sprintf("%40s %-10d ; Serial (aaaammddvv)\n", ' ', $this->serial);
        $content .= sprintf("%40s %-10d ; Refresh\n", ' ', $this->getRefresh());
        $content .= sprintf("%40s %-10d ; Retry\n", ' ', $this->getRetry());
        $content .= sprintf("%40s %-10d ; Expire\n", ' ', $this->getExpire());
        $content .= sprintf("%40s %-10d ; Negative TTL\n", ' ', $this->getNegativeTTL());
        $content .= sprintf(")");

        return $content;
    }

    /**
     * Returns the Primary Name Server of a zone
     *
     * @return string
     */
    public function getPrimaryNameServer()
    {
        return \Registry::get('zone_default_mname');
    }

    /**
     * Returns the Hostmaster Email of a zone
     *
     * @return string
     */
    public function getHostmasterEmail()
    {
        return strtr(\Registry::get('zone_default_rname'), '@', '.');
    }

    /**
     * Returns the Refresh time for this zone
     *
     * @return int
     */
    public function getRefresh()
    {
        return intval(($this->custom_settings) ? $this->refresh : \Registry::get('zone_default_refresh'));
    }

    /**
     * Returns the Retry time for this zone
     *
     * @return int
     */
    public function getRetry()
    {
        return intval(($this->custom_settings) ? $this->retry : \Registry::get('zone_default_retry'));
    }

    /**
     * Returns the Expire time for this zone
     *
     * @return int
     */
    public function getExpire()
    {
        return intval(($this->custom_settings) ? $this->expire : \Registry::get('zone_default_expire'));
    }

    /**
     * Returns the Negative TTL for this zone
     *
     * @return int
     */
    public function getNegativeTTL()
    {
        return intval(($this->custom_settings) ? $this->negative_ttl : \Registry::get('zone_default_negative_ttl'));
    }

    /**
     * Scope a query to include zones to be pushed.
     *
     * Criteria:
     *     - Master zones
     *     - With pending changes
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPendingChanges($query)
    {
        return $query->where('updated', 1)
            ->where('master', '');
    }

    /**
     * Scope a query to include only Master zones.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyMasterZones($query)
    {
        return $query->where('master', '');
    }
}
