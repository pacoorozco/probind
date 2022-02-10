<?php

namespace App\Jobs;

use App\Enums\ZoneType;
use App\Models\Zone;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateZone
{

    use Dispatchable;

    private string      $domain;
    private string|null $server;
    private string|null $type;
    private bool        $custom_settings;
    private int         $refresh;
    private int         $retry;
    private int         $expire;
    private int         $negativeTTL;
    private int         $defaultTTL;

    public function __construct(string $domain, string $server = null, array $options = [])
    {
        $this->domain = $domain;
        $this->server = $server;
        $this->type = empty($this->server)
            ? ZoneType::Primary
            : ZoneType::Secondary;

        $this->custom_settings = !empty($options['custom_settings']) && $options['custom_settings'];

        if ($this->custom_settings === true) {
            $this->refresh = empty($options['refresh']) ? setting('zone_default_refresh') : (int) $options['refresh'];
            $this->retry = empty($options['retry']) ? setting('zone_default_retry') : (int) $options['retry'];
            $this->expire = empty($options['expire']) ? setting('zone_default_expire') : (int) $options['expire'];
            $this->negativeTTL = empty($options['negative_ttl']) ? setting('zone_default_negative_ttl') : (int) $options['negative_ttl'];
            $this->defaultTTL = empty($options['default_ttl']) ? setting('zone_default_default_ttl') : (int) $options['default_ttl'];
        }
    }

    public function handle(): Zone
    {
        $zone = new Zone();
        $zone->domain = $this->domain;
        $zone->reverse_zone = Zone::isReverseZoneName($this->domain);
        $zone->server = $this->server;

        if ($this->type == ZoneType::Primary) {
            $zone->server = null;
            $this->fillCustomSettings($zone);
            $zone->serial = $zone->calculateNewSerialNumber();
        }

        $zone->has_modifications = true;
        $zone->save();

        return $zone;
    }

    private function fillCustomSettings(Zone $zone): void
    {
        $zone->custom_settings = $this->custom_settings;

        if ($this->custom_settings === false) {
            return;
        }

        $zone->fill([
            'refresh' => $this->refresh,
            'retry' => $this->retry,
            'expire' => $this->expire,
            'negative_ttl' => $this->negativeTTL,
            'default_ttl' => $this->defaultTTL,
        ]);
    }
}
