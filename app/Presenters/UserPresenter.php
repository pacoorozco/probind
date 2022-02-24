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

namespace App\Presenters;

use App\Models\User;
use Illuminate\Support\HtmlString;
use Laracodes\Presenter\Presenter;

class UserPresenter extends Presenter
{
    /** @var User */
    protected $model;

    public function activeAsBadge(): HtmlString
    {
        if ($this->model->active) {
            return new HtmlString('<span class="badge badge-success">' . trans('general.enabled') . '</span>');
        }

        return new HtmlString('<span class="badge badge-secondary">' . trans('general.disabled') . '</span>');
    }
}
