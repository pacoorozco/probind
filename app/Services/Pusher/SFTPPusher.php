<?php
/*
 * Copyright (c) 2016-2023 Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of ProBIND v3.
 *
 * ProBIND v3 is free software: you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * ProBIND v3 is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with ProBIND v3. If not,
 * see <https://www.gnu.org/licenses/>.
 *
 */

namespace App\Services\Pusher;

use App\Models\Server;
use App\Services\Pusher\Clients\SFTPClient;

class SFTPPusher implements PusherInterface
{
    private SFTPClient $sftp;

    public function getName(): string
    {
        return 'sftp';
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

    private function getSFTPClient(Server $server): SFTPClient
    {
        return app(SFTPClient::class)
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
