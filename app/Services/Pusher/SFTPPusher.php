<?php

namespace App\Services\Pusher;

use App\Models\Server;
use App\Services\Pusher\Clients\SFTPClient;

class SFTPPusher implements PusherInterface
{
    const NAME = 'sftp';

    private SFTPClient $client;

    public function getName(): string
    {
        return self::NAME;
    }

    public function connect(Server $server): void
    {
        $this->client = (new SFTPClient($server->hostname))
            ->onPort(setting()->get('ssh_default_port', 22))
            ->as(setting()->get('ssh_default_user'))
            ->withPrivateKey(setting()->get('ssh_default_key'))
            ->connect();
    }

    public function sync(string $source, string $destination): bool
    {
        return $this->client->upload($source, $destination);
    }

    public function disconnect(): void
    {
        $this->client->disconnect();
    }
}
