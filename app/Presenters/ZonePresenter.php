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
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . (string) __('zone/model.status_list.unsynced') . '</p>'
            : '<p class="text-success"><i class="fa fa-check-circle"></i> ' . (string) __('zone/model.status_list.synced') . '</p>';

        return new HtmlString($badge);
    }

    public function statusIcon(): HtmlString
    {
        $badge = (true === $this->model->has_modifications)
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . (string) __('general.yes') . '</p>'
            : '<p class="text-success"><i class="fa fa-check-circle"></i> ' . (string) __('general.no') . '</p>';

        return new HtmlString($badge);
    }

    public function customSettings(): HtmlString
    {
        $alert = (true === $this->model->custom_settings)
            ? '<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> ' . (string) __('zone/messages.settings.custom') . '</p>'
            : '<p class="text-primary"><i class="fa fa-info-circle"></i> ' . (string) __('zone/messages.settings.default') . '</p>';

        return new HtmlString($alert);
    }

    public function recordCount(): HtmlString
    {
        return new HtmlString((string) __('zone/messages.resource_records', [
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
