<?php

namespace App\Jobs;

use App\Models\Zone;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateZoneSerialName
{
    use Dispatchable;

    private Zone $zone;

    private bool $force;

    public function __construct(Zone $zone, bool $force = false)
    {
        $this->zone = $zone;
        $this->force = $force;
    }

    public function handle(): Zone
    {
        // If there's not pending changes, we should not increment the serial number.
        if ($this->zone->has_modifications && ! $this->force) {
            return $this->zone;
        }

        // Update serial and flag pending changes
        $this->zone->serial = Zone::calculateNewSerialNumber($this->zone->serial);
        $this->zone->has_modifications = true; // Do not use setPendingChanges() to reduce one DB call.
        $this->zone->save();

        return $this->zone->refresh();
    }
}
