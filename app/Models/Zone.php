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

namespace App\Models;

use App\Helpers\DNSHelper;
use App\Presenters\ZonePresenter;
use App\Rules\FullyQualifiedDomainName;
use Badcow\DNS\Validator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\Concerns\Has;
use Laracodes\Presenter\Traits\Presentable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Zone model, represents a DNS domain.
 *
 * The Zone model contains all DNS information of a zone / domain name.
 * Has one-to-many relationship in order to associate ResourceRecord models for Master Zones.
 *
 * @property int $id                    The object unique id.
 * @property string $domain                The domain name that represents this zone.
 * @property int $serial                The serial number of this zone.
 * @property string $master_server         The IP address of the master server.
 *                                          If it's set to null, this zone is a master zone.
 * @property bool $reverse_zone          This flag determines if this zone is a .IN-ADDR.ARPA. zone.
 * @property bool $custom_settings       This flag determines if this zone has custom timers.
 * @property int $refresh               Custom Refresh time value.
 * @property int $retry                 Custom Retry time value.
 * @property int $expire                Custom Expire time value.
 * @property int $negative_ttl          Custom Negative TTL value.
 * @property int $default_ttl           Custom TTL value.
 * @property string $primaryNameServer
 * @property string $hostmasterEmail
 * @property bool $has_modifications     This flag determines if this zone has been modified from last push.
 *
 * @link https://www.ietf.org/rfc/rfc1035.txt
 * @link https://www.ietf.org/rfc/rfc2782.txt
 */
class Zone extends Model
{
    use SoftDeletes;
    use LogsActivity;
    use Presentable;
    use HasFactory;

    protected string $presenter = ZonePresenter::class;
    protected $table   = 'zones';
    protected $guarded = [];

    protected $casts = [
        'has_modifications' => 'boolean',
        'custom_settings' => 'boolean',
        'reverse_zone' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => trans('zone/messages.activity.'.$eventName, [
                'domain' => $this->domain,
            ]));
    }

    public function records(): HasMany
    {
        return $this->hasMany(ResourceRecord::class);
    }

    public function setDomainAttribute(string $value): void
    {
        $this->attributes['domain'] = strtolower($value);
    }

    public function isMasterZone(): bool
    {
        return is_null($this->master_server);
    }

    public static function isValidZoneName(string $domain): bool
    {
        $rule = new FullyQualifiedDomainName();

        return $rule->passes(null, $domain);
    }

    public static function isReverseZoneName(string $domain): bool
    {
        return Validator::reverseIpv4($domain) || Validator::reverseIpv6($domain);
    }

    /**
     * Get New Serial Number for this zone.
     *
     * This generates a new serial, based on the often used format YYYYMMDDXX where XX is an ascending serial, allowing
     * up to 100 edits per day. After that the serial wraps into the next day and it still works.
     * We don't need to raise Serial Number in every change, only when last change was pushed.
     *
     * @param  bool  $force  This flag force to raise the Serial Number.
     *
     * @return int
     */
    public function getNewSerialNumber(bool $force = false): int
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
        $this->setPendingChanges();
        $this->save();

        return $this->serial;
    }

    public function hasPendingChanges(): bool
    {
        return true === $this->has_modifications;
    }

    /**
     * Raise a supplied Serial Number maintaining format YYYYMMDDXX.
     *
     * @param  int  $currentSerial  The serial number to be increased.
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

    public static function generateSerialNumber(): int
    {
        return intval(Carbon::now()->format('Ymd').'00');
    }

    public function setPendingChanges(bool $value = true): bool
    {
        if ($this->hasPendingChanges() !== $value) {
            $this->has_modifications = $value;
            $this->save();
        }

        return $this->has_modifications;
    }

    public function getRecordsCountAttribute(): int
    {
        return $this->records()->count();
    }

    public function getRefreshAttribute($value)
    {
        return (true === $this->custom_settings)
            ? $value
            : $this->refresh = setting()->get('zone_default_refresh');
    }

    public function getRetryAttribute($value)
    {
        return (true === $this->custom_settings)
            ? $value
            : $this->retry = setting()->get('zone_default_retry');
    }

    public function getExpireAttribute($value)
    {
        return (true === $this->custom_settings)
            ? $value
            : $this->expire = setting()->get('zone_default_expire');
    }

    public function getNegativeTtlAttribute($value)
    {
        return (true === $this->custom_settings)
            ? $value
            : $this->negative_ttl = setting()->get('zone_default_negative_ttl');
    }

    public function getDefaultTtlAttribute($value)
    {
        return (true === $this->custom_settings)
            ? $value
            : $this->default_ttl = setting()->get('zone_default_default_ttl');
    }

    public function getPrimaryNameServerAttribute(): string
    {
        return setting()->get('zone_default_mname');
    }

    public function getHostmasterEmailAttribute(): string
    {
        return strtr(setting()->get('zone_default_rname'), '@', '.');
    }

    public function scopeWithPendingChanges(Builder $query): Builder
    {
        return $query->where('has_modifications', true);
    }

    public function scopeOnlyMasterZones(Builder $query): Builder
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
     * Returns an array of valid ResourceRecord types for this zone.
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
