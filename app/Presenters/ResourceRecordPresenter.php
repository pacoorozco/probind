<?php

namespace App\Presenters;

use App\Enums\ResourceRecordType;
use App\Models\ResourceRecord;
use Badcow\DNS\Rdata\TXT;
use Laracodes\Presenter\Presenter;

class ResourceRecordPresenter extends Presenter
{
    /** @var ResourceRecord */
    protected $model;

    public function asString(): string
    {
        $formattedData = match ($this->model->type->key) {
            ResourceRecordType::MX,
            ResourceRecordType::SRV,
            ResourceRecordType::NAPTR => $this->formatRDataWithPriority(),
            ResourceRecordType::TXT => $this->formatRDataForTXTRecord(),
            default => $this->model->data,
        };

        return sprintf(
            "%-40s %s\tIN\t%s\t%s",
            $this->model->name,
            $this->model->ttl ?: '',
            $this->model->type,
            $formattedData
        );
    }

    private function formatRDataWithPriority(): string
    {
        return sprintf(
            "%s %s",
            $this->model->priority ?: '',
            $this->model->data
        );
    }

    private function formatRDataForTXTRecord(): string
    {
        $rData = new TXT();
        $rData->setText($this->model->data);
        return $rData->toText();
    }

}
