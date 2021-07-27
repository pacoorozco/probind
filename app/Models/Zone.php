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

use App\Enums\ZoneType;
use App\Presenters\ZonePresenter;
use App\Rules\FullyQualifiedDomainName;
use Badcow\DNS\Validator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
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
 * @property string $server     The IP address of the master server. If it's set to null, this zone is a primary zone.
 * @property bool $reverse_zone          This flag determines if this zone is a .IN-ADDR.ARPA. zone.
 * @property bool $custom_settings       This flag determines if this zone has custom timers.
 * @property int $refresh               Custom Refresh time value.
 * @property int $retry                 Custom Retry time value.
 * @property int $expire                Custom Expire time value.
 * @property int $negative_ttl          Custom Negative TTL value.
 * @property int $default_ttl           Custom TTL value.
 * @property string $primaryNameServer
 * @property string $hostmasterEmail
 * @property bool $has_modifications     This flag determines if this zone has been pending changes to sync.
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

    protected $table = 'zones';

    protected $fillable = [
        'domain',
        'server',
        'reverse_zone',
        'custom_settings',
        'refresh',
        'expire',
        'negative_ttl',
        'default_ttl',
    ];

    protected $casts = [
        'has_modifications' => 'boolean',
        'custom_settings' => 'boolean',
        'reverse_zone' => 'boolean',
    ];

    public static function isValidZoneName(string $domain): bool
    {
        $rule = new FullyQualifiedDomainName();

        return $rule->passes(null, $domain);
    }

    public static function isReverseZoneName(string $domain): bool
    {
        return Validator::reverseIpv4($domain) || Validator::reverseIpv6($domain);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => trans('zone/messages.activity.'.$eventName, [
                'domain' => $this->domain,
            ]));
    }

    public function setDomainAttribute(string $value): void
    {
        $this->attributes['domain'] = strtolower($value);
    }

    public function records(): HasMany
    {
        return $this->hasMany(ResourceRecord::class);
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

    public function scopePrimaryZones(Builder $query): Builder
    {
        return $query->whereNull('server');
    }

    public function getTypeOfZone(): string
    {
        return ($this->isPrimary())
            ? ZoneType::Primary
            : ZoneType::Secondary;
    }

    public function isPrimary(): bool
    {
        return is_null($this->server);
    }

    /**
     * Calculates a new Serial Number for this zone.
     *
     * This generates a new serial, based on the often used format YYYYMMDDXX where XX is an ascending serial, allowing
     * up to 100 edits per day. After that the serial wraps into the next day and it still works.
     *
     * @param int $currentSerialNumber
     *
     * @return int
     */
    public static function calculateNewSerialNumber(int $currentSerialNumber = 0): int
    {
        $newSerialNumber = intval(Carbon::now()->format('Ymd').'00');
        return ($currentSerialNumber >= $newSerialNumber)
            ? $currentSerialNumber + 1
            : $newSerialNumber;
    }

    public function getDefaultRecordType(): string
    {
        return ($this->reverse_zone)
            ? 'PTR'
            : 'A';
    }
}
