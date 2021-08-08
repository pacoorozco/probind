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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * User model, represents a ProBIND user.
 *
 * @property int $id                      The object unique id.
 * @property string $username                The username that represents this user.
 * @property string $name                    The name of this user.
 * @property string $email                   The email address of this user.
 * @property string $password                Encrypted password of this user.
 * @property bool $active                  The status of this user.
 */
class User extends Authenticatable
{
    use Notifiable;
    use LogsActivity;
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setUsernameAttribute(string $value): void
    {
        $this->attributes['username'] = strtolower($value);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => trans('user/messages.activity.' . $eventName, [
                'username' => $this->username,
            ]));
    }
}
