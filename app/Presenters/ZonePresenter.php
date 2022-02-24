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

use App\Models\Zone;
use Illuminate\Support\HtmlString;
use Laracodes\Presenter\Presenter;

class ZonePresenter extends Presenter
{
    /** @var Zone */
    protected $model;

    public function statusBadge(): HtmlString
    {
        if ($this->model->has_modifications) {
            return new HtmlString('<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . trans('zone/model.status_list.unsynced') . '</p>');
        }

        return new HtmlString('<p class="text-success"><i class="fa fa-check-circle"></i> ' . trans('zone/model.status_list.synced') . '</p>');
    }

    public function statusIcon(): HtmlString
    {
        if ($this->model->has_modifications) {
            return new HtmlString('<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . trans('general.yes') . '</p>');
        }

        return new HtmlString('<p class="text-success"><i class="fa fa-check-circle"></i> ' . trans('general.no') . '</p>');
    }

    public function customSettings(): HtmlString
    {
        if ($this->model->custom_settings) {
            return new HtmlString('<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . trans('zone/messages.settings.custom') . '</p>');
        }

        return new HtmlString('<p class="text-primary"><i class="fa fa-info-circle"></i> ' . trans('zone/messages.settings.default') . '</p>');
    }

    public function recordCount(): HtmlString
    {
        return new HtmlString(trans('zone/messages.resource_records', [
            'zone' => $this->model->domain,
            'number' => $this->model->recordsCount(),
        ]));
    }

    public function SOA(): string
    {
        $content = sprintf("%-16s IN\tSOA\t%s. %s. (\n", '@',
            $this->model->primaryNameServer,
            $this->model->hostmasterEmail
        );
        $content .= sprintf("%40s %-10d ; Serial (aaaammddvv)\n", ' ', $this->model->serial);
        $content .= sprintf("%40s %-10d ; Refresh\n", ' ', $this->refresh());
        $content .= sprintf("%40s %-10d ; Retry\n", ' ', $this->retry());
        $content .= sprintf("%40s %-10d ; Expire\n", ' ', $this->expire());
        $content .= sprintf("%40s %-10d ; Negative TTL\n", ' ', $this->negativeTTL());
        $content .= ')';

        return $content;
    }

    public function refresh(): string
    {
        return (true === $this->model->custom_settings)
            ? $this->model->refresh
            : setting()->get('zone_default_refresh');
    }

    public function retry(): string
    {
        return (true === $this->model->custom_settings)
            ? $this->model->retry
            : setting()->get('zone_default_retry');
    }

    public function expire(): string
    {
        return (true === $this->model->custom_settings)
            ? $this->model->expire
            : setting()->get('zone_default_expire');
    }

    public function negativeTTL(): string
    {
        return (true === $this->model->custom_settings)
            ? $this->model->negative_ttl
            : setting()->get('zone_default_negative_ttl');
    }

    public function defaultTTL(): string
    {
        return (true === $this->model->custom_settings)
            ? $this->model->default_ttl
            : setting()->get('zone_default_default_ttl');
    }
}
