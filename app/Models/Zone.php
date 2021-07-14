<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 * This file is part of some open source application.
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2016 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/probind
 */

namespace App;

use App\Helpers\DNSHelper;
use App\Presenters\ZonePresenter;
use App\Rules\FullyQualifiedDomainName;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Laracodes\Presenter\Traits\Presentable;
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
 * @property bool   $records_count         The number of resource records associated to this zone..
 *
 * @link https://www.ietf.org/rfc/rfc1035.txt
 * @link https://www.ietf.org/rfc/rfc2782.txt
 */
class Zone extends Model
{
    use SoftDeletes;
    use LogsActivity;
    use Presentable;

    protected static $logUnguarded = true;

    protected $presenter = ZonePresenter::class;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    /**
     * The database table used by the model.
     */
    protected $table = 'zones';
    protected $guarded = [];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'domain' => 'string',
        'serial' => 'integer',
        'master_server' => 'string',
        'has_modifications' => 'boolean',
        'custom_settings' => 'boolean',
        'reverse_zone' => 'boolean',
        'refresh' => 'integer',
        'retry' => 'integer',
        'expire' => 'integer',
        'negative_ttl' => 'integer',
        'default_ttl' => 'integer',
    ];

    /**
     * Returns true if the provided string is a valid zone name.
     *
     * @see https://en.m.wikipedia.org/wiki/Fully_qualified_domain_name
     *
     * @param string $domain
     *
     * @return bool
     */
    public static function isValidZoneName(string $domain): bool
    {
        $rule = new FullyQualifiedDomainName();

        return $rule->passes(null, $domain);
    }

    /**
     * Returns true if the provided string is a valid reverse zone name.
     *
     * @param string $domain The domain to be validated.
     *
     * @return bool
     */
    public static function isReverseZoneName(string $domain): bool
    {
        return \Badcow\DNS\Validator::reverseIpv4($domain) || \Badcow\DNS\Validator::reverseIpv6($domain);
    }

    /**
     * Returns a customized message for Activity Log.
     *
     * @param string $eventName The event could be saved, updated or deleted.
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return (string) trans('zone/messages.activity.' . $eventName, [
            'domain' => $this->domain,
        ]);
    }

    /**
     * Set the domain Zone attribute to lowercase.
     *
     * @param string $value
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
     * @param bool $force This flag force to raise the Serial Number.
     *
     * @return int
     */
    public function getNewSerialNumber(bool $force = false): int
    {
        // Get current Zone serial number.
        $currentSerial = $this->serial;

        // We need a new one ONLY if there isn't pending changes.
        if ($this->hasPendingChanges() && ! $force) {
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
     * Returns the number of related resource records.
     *
     * @param $value
     *
     * @return int
     */
    public function getRecordsCountAttribute($value): int
    {
        return $value ?? $this->records_count = $this->records()->count();
    }

    public function getRefreshAttribute($value)
    {
        return (true === $this->custom_settings)
            ? $value
            : $this->refresh = Setting::get('zone_default_refresh');
    }

    public function getRetryAttribute($value)
    {
        return (true === $this->custom_settings)
            ? $value
            : $this->retry = Setting::get('zone_default_retry');
    }

    public function getExpireAttribute($value)
    {
        return (true === $this->custom_settings)
            ? $value
            : $this->expire = Setting::get('zone_default_expire');
    }

    public function getNegativeTtlAttribute($value)
    {
        return (true === $this->custom_settings)
            ? $value
            : $this->negative_ttl = Setting::get('zone_default_negative_ttl');
    }

    public function getDefaultTtlAttribute($value)
    {
        return (true === $this->custom_settings)
            ? $value
            : $this->default_ttl = Setting::get('zone_default_default_ttl');
    }

    /**
     * Return true if this zone has been modified from last push.
     *
     * This checks whether the Zone has been modified from the last push.
     *
     * @return bool
     */
    public function hasPendingChanges(): bool
    {
        return true === $this->has_modifications;
    }

    /**
     * Raise a supplied Serial Number maintaining format YYYYMMDDXX.
     *
     * @param int $currentSerial The serial number to be increased.
     *
     * @return int
     */
    public function raiseSerialNumber(int $currentSerial): int
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
    public static function generateSerialNumber(): int
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
    public function setPendingChanges(bool $value = true): bool
    {
        if ($this->hasPendingChanges() !== $value) {
            $this->has_modifications = $value;
            $this->save();
        }

        return $this->has_modifications;
    }

    /**
     * Returns a formatted SOA record of a zone.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getSOARecord(): string
    {
        $content = sprintf("%-16s IN\tSOA\t%s. %s. (\n", '@', $this->getPrimaryNameServer(),
            $this->getHostmasterEmail());
        $content .= sprintf("%40s %-10d ; Serial (aaaammddvv)\n", ' ', $this->serial);
        $content .= sprintf("%40s %-10d ; Refresh\n", ' ', $this->present()->refresh);
        $content .= sprintf("%40s %-10d ; Retry\n", ' ', $this->present()->retry);
        $content .= sprintf("%40s %-10d ; Expire\n", ' ', $this->present()->expire);
        $content .= sprintf("%40s %-10d ; Negative TTL\n", ' ', $this->present()->negative_ttl);
        $content .= sprintf(')');

        return $content;
    }

    /**
     * Returns the Master name server of this zone.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getPrimaryNameServer(): string
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
    public function getHostmasterEmail(): string
    {
        return strtr(Setting::get('zone_default_rname'), '@', '.');
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
    public function getTypeOfZone(): string
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
    public function isMasterZone(): bool
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
    public function getValidRecordTypesForThisZone(): array
    {
        return ($this->reverse_zone)
            ? Arr::only(DNSHelper::getValidRecordTypesWithDescription(), ['PTR', 'TXT', 'NS'])
            : Arr::except(DNSHelper::getValidRecordTypesWithDescription(), ['PTR']);
    }
}
