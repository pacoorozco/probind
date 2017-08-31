<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 * This file is part of some open source application.
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2016 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/probind
 */

namespace App;

use Carbon\Carbon;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Setting;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Zone model, represents a DNS domain.
 *
 * The Zone model contains all DNS information of a zone / domain name.
 * Has one-to-many relationship in order to associate Record models for Master Zones.
 *
 * @property int    $id                    The object unique id.
 * @property string $domain                The domain name that represents this zone.
 * @property int    $serial                The serial number of this zone.
 * @property string $master_server         The IP address of the master server.
 *                                          If it's set to null, this zone is a master zone.
 * @property bool   $reverse_zone          This flag determines if this zone is a .IN-ADDR.ARPA. zone.
 * @property bool   $custom_settings       This flag determines if this zone has custom timers.
 * @property int    $refresh               Custom Refresh time value.
 * @property int    $retry                 Custom Retry time value.
 * @property int    $expire                Custom Expire time value.
 * @property int    $negative_ttl          Custom Negative TTL value.
 * @property int    $default_ttl           Custom TTL value.
 * @property bool   $has_modifications     This flag determines if this zone has been modified from last push.
 *
 * @link https://www.ietf.org/rfc/rfc1035.txt
 * @link https://www.ietf.org/rfc/rfc2782.txt
 *
 */
class Zone extends Model
{

    use SoftDeletes;
    use LogsActivity;
    use NullableFields;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * The database table used by the model.
     */
    protected $table = 'zones';
    protected $fillable = [
        'master_server',
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
        'domain'            => 'string',
        'serial'            => 'integer',
        'master_server'     => 'string',
        'has_modifications' => 'boolean',
        'custom_settings'   => 'boolean',
        'reverse_zone'      => 'boolean',
        'refresh'           => 'integer',
        'retry'             => 'integer',
        'expire'            => 'integer',
        'negative_ttl'      => 'integer',
        'default_ttl'       => 'integer',
    ];
    /**
     * The attributes that should be casted to null if is empty.
     *
     * @var array
     */
    protected $nullable = [
        'master_server',
        'refresh',
        'retry',
        'expire',
        'negative_ttl',
        'default_ttl'
    ];

    /**
     * Returns true if $domain is a valid NORMAL zone name.
     *
     * @param string $domain The domain to be validated.
     *
     * @return bool
     */
    public static function validateNormalDomainName(string $domain) : bool
    {
        return preg_match('/^(([a-zA-Z]{1})|([a-zA-Z]{1}[a-zA-Z]{1})|([a-zA-Z]{1}[0-9]{1})|([0-9]{1}[a-zA-Z]{1})|([a-zA-Z0-9][a-zA-Z0-9-_]{1,61}[a-zA-Z0-9]))\.([a-zA-Z]{2,6}|[a-zA-Z0-9-]{2,30}\.[a-zA-Z]{2,3})$/',
            $domain);

    }

    /**
     * Returns true if $domain is a valid REVERSE zone name.
     *
     * @param string $domain The domain to be validated.
     *
     * @return bool
     */
    public static function validateReverseDomainName(string $domain) : bool
    {
        return preg_match('/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){0,2}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.in-addr.arpa$/',
            $domain);
    }

    /**
     * Returns a customized message for Activity Log.
     *
     * @param string $eventName The event could be saved, updated or deleted.
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName) : string
    {
        return trans('zone/messages.activity.' . $eventName, [
            'domain' => $this->domain
        ]);
    }

    /**
     * Set the domain Zone attribute to lowercase.
     *
     * @param  string $value
     */
    public function setDomainAttribute(string $value)
    {
        $this->attributes['domain'] = strtolower($value);
    }

    /**
     * Contains all the records associate to this zone.
     *
     * A one-to-many relationship of Resource Records (RR's) for this zone.
     * Each item is a separate Record model item.
     *
     * @see Record model for definition of each one.
     */
    public function records()
    {
        return $this->hasMany('App\Record');
    }

    /**
     * Get New Serial Number for this zone.
     *
     * This generates a new serial, based on the often used format YYYYMMDDXX where XX is an ascending serial, allowing
     * up to 100 edits per day. After that the serial wraps into the next day and it still works.
     * We don't need to raise Serial Number in every change, only when last change was pushed.
     *
     * @param  bool $force This flag force to raise the Serial Number.
     *
     * @return int
     */
    public function getNewSerialNumber(bool $force = false) : int
    {
        // Get current Zone serial number.
        $currentSerial = $this->serial;

        // We need a new one ONLY if there isn't pending changes.
        if ($this->hasPendingChanges() && !$force) {
            return $currentSerial;
        }

        // Raise serial number
        $this->serial = $this->raiseSerialNumber($currentSerial);

        // Once Serial Number has changed, we have changes to push to servers.
        $this->setPendingChanges(true);
        $this->save();

        return intval($this->serial);
    }

    /**
     * Return true if this zone has been modified from last push.
     *
     * This checks whether the Zone has been modified from the last push.
     *
     * @return bool
     */
    public function hasPendingChanges() : bool
    {
        return $this->has_modifications;
    }

    /**
     * Raise a supplied Serial Number maintaining format YYYYMMDDXX.
     *
     * @param int $currentSerial The serial number to be increased.
     *
     * @return int
     */
    public function raiseSerialNumber(int $currentSerial) : int
    {
        // Create a new serial number YYYYMMDD00.
        $nowSerial = Zone::generateSerialNumber();

        return ($currentSerial >= $nowSerial)
            ? $currentSerial + 1
            : $nowSerial;
    }

    /**
     * Generate a Serial Number based on a specified format.
     *
     * @return int
     */
    public static function generateSerialNumber() : int
    {
        return intval(Carbon::now()->format('Ymd') . '00');
    }

    /**
     * Marks / unmark pending changes on this zone.
     *
     * @param bool $value The value to set pending changes on this zone.
     *
     * @return bool
     */
    public function setPendingChanges(bool $value = true) : bool
    {
        if ($this->hasPendingChanges() !== $value) {
            $this->has_modifications = $value;
            $this->save();
        }

        return $this->has_modifications;
    }

    /**
     * Returns the Default TTL for this zone.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getDefaultTTL() : int
    {
        return intval(($this->custom_settings) ? $this->default_ttl : Setting::get('zone_default_default_ttl'));
    }

    /**
     * Returns a formatted SOA record of a zone.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getSOARecord() : string
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
     * Returns the Master name server of this zone.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getPrimaryNameServer() : string
    {
        return Setting::get('zone_default_mname');
    }

    /**
     * Returns the Hostmaster Email of a zone.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getHostmasterEmail() : string
    {
        return strtr(Setting::get('zone_default_rname'), '@', '.');
    }

    /**
     * Returns the Refresh time for this zone.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getRefresh() : int
    {
        return intval(($this->custom_settings) ? $this->refresh : Setting::get('zone_default_refresh'));
    }

    /**
     * Returns the Retry time for this zone.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getRetry() : int
    {
        return intval(($this->custom_settings) ? $this->retry : Setting::get('zone_default_retry'));
    }

    /**
     * Returns the Expire time for this zone.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getExpire() : int
    {
        return intval(($this->custom_settings) ? $this->expire : Setting::get('zone_default_expire'));
    }

    /**
     * Returns the Negative TTL for this zone.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getNegativeTTL() : int
    {
        return intval(($this->custom_settings) ? $this->negative_ttl : Setting::get('zone_default_negative_ttl'));
    }

    /**
     * Scope a query to include zones with modifications.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPendingChanges(Builder $query)
    {
        return $query->where('has_modifications', true);
    }

    /**
     * Scope a query to include only Master zones.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyMasterZones(Builder $query)
    {
        return $query->where('master_server', null);
    }

    /**
     * Returns a normalized string indicating what type of zone is.
     *
     * It's useful to get a translated Zone type:
     *
     * echo trans('zone/model.types.' . $zone->getTypeOfZone());
     *
     * @return string
     */
    public function getTypeOfZone() : string
    {
        return ($this->isMasterZone()) ? 'master' : 'slave';
    }

    /**
     * Returns if this is a master zone.
     *
     * The DNS server is the primary source for information about this zone, and it stores the master copy of zone data
     * in a local file.
     *
     * @return bool
     */
    public function isMasterZone() : bool
    {
        return is_null($this->master_server);
    }

    /**
     * Returns an array of valid Record types for this zone.
     *
     * Reverse zone only has 'PTR' and 'NS' types.
     *
     * @return array
     */
    public function getValidRecordTypesForThisZone() : array
    {
        return ($this->reverse_zone)
            ? array_only(Record::getAllValidRecordTypes(), ['PTR', 'TXT', 'NS'])
            : array_except(Record::getAllValidRecordTypes(), ['PTR']);
    }
}
