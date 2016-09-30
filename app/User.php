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

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * User model, represents a ProBIND user.
 *
 * @property int    $id                      The object unique id.
 * @property string $username                The username that represents this user.
 * @property string $name                    The name of this user.
 * @property string $email                   The email address of this user.
 * @property string $password                Encrypted password of this user.
 * @property bool   $active                  The status of this user.
 *
 */
class User extends Authenticatable
{

    use Notifiable;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'username' => 'string',
        'name'     => 'string',
        'email'    => 'string',
        'password' => 'string',
        'active'   => 'boolean',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Returns a customized message for Activity Log.
     *
     * @param string $eventName The event could be saved, updated or deleted.
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName) : string
    {
        return trans('user/messages.activity.' . $eventName, [
            'username' => $this->username
        ]);
    }

    /**
     * Set the username User attribute to lowercase.
     *
     * @param  string $value
     */
    public function setUsernameAttribute(string $value)
    {
        $this->attributes['username'] = strtolower($value);
    }
}
