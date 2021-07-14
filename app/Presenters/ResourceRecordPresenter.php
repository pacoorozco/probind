<?php

namespace App\Presenters;

use App\Models\ResourceRecord;
use Laracodes\Presenter\Presenter;

class ResourceRecordPresenter extends Presenter
{
    /** @var ResourceRecord */
    protected $model;

    public function asString(): string
    {
        switch ($this->model->type) {
            case 'NS':
                return $this->formatNSResourceRecord();
            case 'MX':
                return $this->formatMXResourceRecord();
            case 'SRV':
                return $this->formatSRVResourceRecord();
            case 'NAPTR':
                return $this->formatNAPTRResourceRecord();
            default:
                // continue
        }

        return sprintf(
            "%-40s %s\tIN\t%s\t%s",
            $this->model->name,
            $this->model->ttl ?: '',
            $this->model->type,
            $this->model->data
        );
    }

    private function formatNSResourceRecord(): string
    {
        return sprintf(
            "%-40s %s\tIN\tNS\t%s",
            $this->model->name,
            $this->model->ttl ?: '',
            $this->model->data
        );
    }

    private function formatMXResourceRecord(): string
    {
        return sprintf(
            "%-40s %s\tIN\tMX\t%s %s",
            $this->model->name,
            $this->model->ttl ?: '',
            $this->model->priority,
            $this->model->data
        );
    }

    private function formatSRVResourceRecord(): string
    {
        return sprintf(
            "%-40s %s\tIN\tSRV\t%s %s",
            $this->model->name,
            $this->model->ttl ?: '',
            $this->model->priority,
            $this->model->data
        );
    }

    private function formatNAPTRResourceRecord(): string
    {
        return sprintf(
            "%-40s %s\tIN\tNAPTR\t%s %s",
            $this->model->name,
            $this->model->ttl ?: '',
            $this->model->priority,
            $this->model->data
        );
    }
}
