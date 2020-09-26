<?php

namespace App\Presenters;

use Illuminate\Support\HtmlString;
use Laracodes\Presenter\Presenter;
use Larapacks\Setting\Facades\Setting;


class ZonePresenter extends Presenter
{
    public function refresh()
    {
        return (true === $this->model->custom_settings)
            ? $this->model->refresh
            : Setting::get('zone_default_refresh');
    }

    public function retry()
    {
        return (true === $this->model->custom_settings)
            ? $this->model->retry
            : Setting::get('zone_default_retry');
    }

    public function expire()
    {
        return (true === $this->model->custom_settings)
            ? $this->model->expire
            : Setting::get('zone_default_expire');
    }

    public function negativeTtl()
    {
        return (true === $this->model->custom_settings)
            ? $this->model->negative_ttl
            : Setting::get('zone_default_negative_ttl');
    }

    public function defaultTtl()
    {
        return (true === $this->model->custom_settings)
            ? $this->model->default_ttl
            : Setting::get('zone_default_default_ttl');
    }

    public function statusBadge()
    {
        $badge = (true === $this->model->hasPendingChanges())
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . __('zone/model.status_list.unsynced') . '</p>'
            : '<p class="text-success"><i class="fa fa-check-circle"></i> ' . __('zone/model.status_list.synced') . '</p>';
        return new HtmlString((string)$badge);
    }

    public function statusIcon()
    {
        $badge = (true === $this->model->hasPendingChanges())
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . __('general.yes') . '</p>'
            : '<p class="text-success"><i class="fa fa-check-circle"></i> ' . __('general.no') . '</p>';
        return new HtmlString((string)$badge);
    }

    public function customSettings()
    {
        $alert = (true === $this->model->custom_settings)
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . __('zone/messages.settings.custom') . '</p>'
            : '<p class="text-primary"><i class="fa fa-info-circle"></i> ' . __('zone/messages.settings.default') . '</p>';
        return new HtmlString((string)$alert);
    }

    public function recordCount()
    {
        return new HtmlString((string)__('zone/messages.resource_records', ['zone' => $this->model->domain, 'number' => $this->model->records()->count()]));
    }

}
