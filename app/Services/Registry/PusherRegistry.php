<?php

namespace App\Services\Registry;

use App\Services\Contracts\Pusher;

class PusherRegistry
{
    public array $pushers = [];

    public function register(string $name, Pusher $pusher): static
    {
        $this->pushers[$name] = $pusher;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function get(string $name): Pusher
    {
        if (in_array($name, $this->pushers)) {
            return $this->pushers[$name];
        }

        throw new \Exception("Unregistered pusher");

    }
}
