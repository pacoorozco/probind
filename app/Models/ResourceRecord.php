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

namespace App\Models;

use App\Enums\ResourceRecordType;
use App\Presenters\ResourceRecordPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laracodes\Presenter\Traits\Presentable;

/**
 * ResourceRecord model.
 *
 * Represents a DNS entry on a specified zone.
 *
 * Valid records types are defined on DNSHelper Class.
 *
 * @property int $id        The object unique id.
 * @property string $name      The name of the record.
 * @property int $ttl       The custom TTL value for this record.
 * @property ResourceRecordType $type      The type of record, must be one of ResourceRecord::$validRecordTypes
 * @property int $priority  The preference value for MX records.
 * @property string $data      The data value for this record.
 * @property Zone $zone      The zone object where this record belongs to.
 *
 * @link https://www.ietf.org/rfc/rfc1035.txt
 */
class ResourceRecord extends Model
{
    use HasFactory;
    use Presentable;
    use HasFactory;

    protected string $presenter = ResourceRecordPresenter::class;

    protected $table = 'records';

    protected $fillable = [
        'name',
        'type',
        'ttl',
        'priority',
        'data',
    ];

    protected $casts = [
        'type' => ResourceRecordType::class,
    ];

    protected $touches = ['zone'];

    public function setTypeAttribute(string $value): void
    {
        $this->attributes['type'] = strtoupper($value);
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}
