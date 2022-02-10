<?php

namespace App\Services\Pusher;

use App\Services\Contracts\Pusher;

class NoOpPusher implements Pusher
{
    public function sync()
    {
        // TODO: Implement sync() method.
    }
}
