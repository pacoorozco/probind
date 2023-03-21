<?php

namespace App\Services\Pusher;

use App\Models\Server;
use App\Services\Pusher\Clients\SFTPClient;

class SFTPPusher implements PusherInterface
{
    const NAME = 'sftp';

    private SFTPClient $sftp;

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws PusherException
     */
    public function sync(string $source, string $destination): bool
    {
        try {
            return $this->sftp->upload($source, $destination);
        } catch (\Throwable $exception) {
            throw new PusherException($exception->getMessage());
        }
    }

    /**
     * @throws PusherException
     */
    public function disconnect(): void
    {
        try {
            $this->sftp->disconnect();
        } catch (\Throwable $exception) {
            throw new PusherException($exception->getMessage());
        }
    }

    private function getSFTPClient(Server $server, SFTPClient $client): SFTPClient
    {
        return $client
            ->to($server->hostname)
            ->onPort(setting()->get('ssh_default_port', 22))
            ->as(setting()->get('ssh_default_user'))
            ->withPrivateKey(setting()->get('ssh_default_key'))
            ->connect();
    }

    /**
     * @throws PusherException
     */
    public function connect(Server $server): void
    {
        try {
            $this->sftp = $this->getSFTPClient($server);
        } catch (\Throwable $exception) {
            throw new PusherException($exception->getMessage());
        }
    }
}
