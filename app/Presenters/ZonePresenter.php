<?php

namespace App\Presenters;

use Illuminate\Support\HtmlString;
use Laracodes\Presenter\Presenter;
use Larapacks\Setting\Facades\Setting;


class ZonePresenter extends Presenter
{
    public function statusBadge()
    {
        $badge = (true === $this->model->hasPendingChanges())
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . (string)__('zone/model.status_list.unsynced') . '</p>'
            : '<p class="text-success"><i class="fa fa-check-circle"></i> ' . (string)__('zone/model.status_list.synced') . '</p>';
        return new HtmlString((string)$badge);
    }

    public function statusIcon()
    {
        $badge = (true === $this->model->hasPendingChanges())
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . (string)__('general.yes') . '</p>'
            : '<p class="text-success"><i class="fa fa-check-circle"></i> ' . (string)__('general.no') . '</p>';
        return new HtmlString((string)$badge);
    }

    public function customSettings()
    {
        $alert = (true === $this->model->custom_settings)
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . (string)__('zone/messages.settings.custom') . '</p>'
            : '<p class="text-primary"><i class="fa fa-info-circle"></i> ' . (string)__('zone/messages.settings.default') . '</p>';
        return new HtmlString((string)$alert);
    }

    public function recordCount()
    {
        return new HtmlString((string)__('zone/messages.resource_records', [
            'zone' => $this->model->domain,
            'number' => $this->model->records_count,
        ]));
    }

}
