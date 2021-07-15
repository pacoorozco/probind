<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Services\SFTP;

use App\Exceptions\PusherException;
use PacoOrozco\OpenSSH\PrivateKey;
use phpseclib3\Net\SFTP;

class SFTPPusher
{
    protected SFTP $sftp;

    public function __construct(string $hostname, int $port = 22, int $timeout = 10)
    {
        $this->sftp = new SFTP($hostname, $port, $timeout);
    }

    /**
     * Logs in the server using the provided credentials.
     *
     * @throws \App\Exceptions\PusherException
     */
    public function login(string $username, PrivateKey $privateKey): void
    {
        if (false === $this->sftp->login($username, $privateKey)) {
            throw new PusherException('Invalid credentials');
        }
    }

    /**
     * Pushes a file to the server, remote permission will be set if it's specified.
     *
     * @throws \App\Exceptions\PusherException
     */
    public function pushFileTo(string $localPath, string $remotePath, int $permission = 0700): void
    {
        if (false === $this->sftp->put($remotePath, $localPath, SFTP::SOURCE_LOCAL_FILE)) {
            throw new PusherException("Unable to push file {$localPath}");
        }

        if (false === $this->sftp->chmod($permission, $remotePath)) {
            throw new PusherException("Unable to push file {$localPath}");
        }
    }

    /**
     * Pushes the content to the server, remote permission will be set if it's specified.
     *
     * @throws \App\Exceptions\PusherException
     */
    public function pushDataTo(string $data, string $remotePath, int $permission = 0700): void
    {
        if (false === $this->sftp->put($remotePath, $data, SFTP::SOURCE_STRING)) {
            throw new PusherException('Unable to put file');
        }

        if (false === $this->sftp->chmod($permission, $remotePath)) {
            throw new PusherException('Unable to put file');
        }
    }

    /**
     * Executes a command in the remote server.
     *
     * @throws \App\Exceptions\PusherException
     */
    public function exec(string $command, bool $quietMode = true): void
    {
        if ($quietMode) {
            $this->sftp->enableQuietMode();
        }

        if (! $this->sftp->exec($command)) {
            throw new PusherException('Unable to exec command');
        }

        if ($quietMode) {
            $this->sftp->disableQuietMode();
        }
    }

    /**
     * Disconnects from the server.
     */
    public function disconnect(): void
    {
        $this->sftp->disconnect();
    }
}
