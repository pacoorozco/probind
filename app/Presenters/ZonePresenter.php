<?php

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
        $badge = (true === $this->model->has_modifications)
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> '.(string) __('zone/model.status_list.unsynced').'</p>'
            : '<p class="text-success"><i class="fa fa-check-circle"></i> '.(string) __('zone/model.status_list.synced').'</p>';

        return new HtmlString($badge);
    }

    public function statusIcon(): HtmlString
    {
        $badge = (true === $this->model->has_modifications)
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> '.(string) __('general.yes').'</p>'
            : '<p class="text-success"><i class="fa fa-check-circle"></i> '.(string) __('general.no').'</p>';

        return new HtmlString($badge);
    }

    public function customSettings(): HtmlString
    {
        $alert = (true === $this->model->custom_settings)
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> '.(string) __('zone/messages.settings.custom').'</p>'
            : '<p class="text-primary"><i class="fa fa-info-circle"></i> '.(string) __('zone/messages.settings.default').'</p>';

        return new HtmlString($alert);
    }

    public function recordCount(): HtmlString
    {
        return new HtmlString((string) __('zone/messages.resource_records', [
            'zone' => $this->model->domain,
            'number' => $this->model->records_count,
        ]));
    }

    public function SOA(): string
    {
        $content = sprintf("%-16s IN\tSOA\t%s. %s. (\n", '@',
            $this->model->primaryNameServer,
            $this->model->hostmasterEmail
        );
        $content .= sprintf("%40s %-10d ; Serial (aaaammddvv)\n", ' ', $this->model->serial);
        $content .= sprintf("%40s %-10d ; Refresh\n", ' ', $this->model->refresh);
        $content .= sprintf("%40s %-10d ; Retry\n", ' ', $this->model->retry);
        $content .= sprintf("%40s %-10d ; Expire\n", ' ', $this->model->expire);
        $content .= sprintf("%40s %-10d ; Negative TTL\n", ' ', $this->model->negative_ttl);
        $content .= ')';

        return $content;
    }
}
