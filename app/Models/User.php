<?php
/*
 * Copyright (c) 2016-2022 Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of ProBIND v3.
 *
 * ProBIND v3 is free software: you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * ProBIND v3 is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with ProBIND v3. If not,
 * see <https://www.gnu.org/licenses/>.
 *
 */

namespace App\Models;

use App\Presenters\UserPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laracodes\Presenter\Traits\Presentable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * User model, represents a ProBIND user.
 *
 * @property int $id The object unique id.
 * @property string $username The username that represents this user.
 * @property string $name The name of this user.
 * @property string $email The email address of this user.
 * @property string $password Encrypted password of this user.
 * @property bool $active The status of this user.
 */
class User extends Authenticatable
{
    use Notifiable;
    use LogsActivity;
    use HasFactory;
    use Presentable;

    protected string $presenter = UserPresenter::class;

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
            ->setDescriptionForEvent(fn (string $eventName) => trans('user/messages.activity.'.$eventName, [
                'username' => $this->username,
            ]));
    }
}
