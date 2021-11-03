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

namespace App\Models;

use App\Enums\ServerType;
use App\Presenters\ServerPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Server model.
 *
 * @property int $id The object unique id.
 * @property string $hostname The hostname of this server. Will be used on NS records.
 * @property string $ip_address The IP address of this server. Will be used for glue records.
 * @property ServerType $type The type of this server. Could be 'primary' or 'secondary'.
 * @property bool $push_updates This flag determines if this server must be pushed with zone files.
 * @property bool $ns_record This flag determines if this server will be included as NS on zone files.
 * @property bool active         This flags determines if this server is active or inactive.
 */
class Server extends Model
{
    use LogsActivity;
    use Presentable;
    use HasFactory;

    protected string $presenter = ServerPresenter::class;

    protected $table = 'servers';

    protected $fillable = [
        'hostname',
        'ip_address',
        'type',
        'ns_record',
        'active',
        'push_updates',
    ];

    protected $casts = [
        'type' => ServerType::class,
        'ns_record' => 'boolean',
        'active' => 'boolean',
        'push_updates' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => trans('server/messages.activity.' . $eventName, [
                'hostname' => $this->hostname,
                'type' => $this->type,
            ]));
    }

    public function setHostnameAttribute(string $value): void
    {
        $this->attributes['hostname'] = strtolower($value);
    }

    public function scopeWithPushCapability(Builder $query): Builder
    {
        return $query
            ->where('push_updates', true)
            ->where('active', true);
    }
}
