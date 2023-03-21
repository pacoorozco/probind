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

namespace App\Services\Pusher\Clients;

use InvalidArgumentException;
use phpseclib3\Crypt\Common\AsymmetricKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SFTP;
use RuntimeException;
use Throwable;

class SFTPClient
{
    const FINGERPRINT_MD5 = 'md5';

    const FINGERPRINT_SHA1 = 'sha1';

    private string $hostname;

    private int $port = 22;

    private string $username;

    private int $timeout = 10;

    private bool $connected = false;

    private SFTP $sftp;

    private AsymmetricKey|string|null $credentials = null;

    public function to(string $hostname): self
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function as(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function onPort(int $port): self
    {
        if ($port < 0) {
            throw new InvalidArgumentException('Port must be a positive integer.');
        }

        $this->port = $port;

        return $this;
    }

    public function withPassword(string $password): self
    {
        $this->credentials = $password;

        return $this;
    }

    public function withPrivateKeyFile(string $privateKeyPath): self
    {
        try {
            return $this->withPrivateKey($this->get_file_content($privateKeyPath));
        } catch (Throwable) {
            throw new InvalidArgumentException('Invalid private key file specified.');
        }
    }

    private function get_file_content(string $path): string
    {
        if (false === $content = file_get_contents($path)) {
            throw new RuntimeException('Could not read specified file.');
        }

        return $content;
    }

    public function withPrivateKey(string $privateKey): self
    {
        try {
            $this->credentials = PublicKeyLoader::load($privateKey);
        } catch (Throwable) {
            throw new InvalidArgumentException('Invalid private key specified.');
        }

        return $this;
    }

    public function timeout(int $timeout): self
    {
        if ($timeout < 0) {
            throw new InvalidArgumentException('Timeout must be a positive integer.');
        }

        $this->timeout = $timeout;

        return $this;
    }

    public function connect(): self
    {
        $this->sanityCheck();

        $this->sftp = new SFTP($this->hostname, $this->port, $this->timeout);

        $authenticated = $this->sftp->login($this->username, $this->credentials);
        if (! $authenticated) {
            throw new RuntimeException('Error authenticating with the provided credentials.');
        }

        if ($this->timeout) {
            $this->sftp->setTimeout($this->timeout);
        }

        $this->connected = true;

        return $this;
    }

    private function sanityCheck(): void
    {
        if (empty($this->hostname)) {
            throw new InvalidArgumentException('Hostname not specified.');
        }

        if (empty($this->username)) {
            throw new InvalidArgumentException('Username not specified.');
        }

        if (empty($this->credentials)) {
            throw new InvalidArgumentException('No password or private key specified.');
        }
    }

    public function disconnect(): void
    {
        if (! $this->connected) {
            throw new RuntimeException('Unable to disconnect. Not yet connected.');
        }

        $this->sftp->disconnect();
    }

    public function upload(string $localPath, string $remotePath): bool
    {
        if (! $this->connected) {
            throw new RuntimeException('Unable to upload file when not connected.');
        }

        if (! file_exists($localPath)) {
            throw new InvalidArgumentException('The local file does not exist.');
        }

        return $this->sftp->put($remotePath, $localPath, SFTP::SOURCE_LOCAL_FILE);
    }

    public function download(string $remotePath, string $localPath): bool
    {
        if (! $this->connected) {
            throw new RuntimeException('Unable to download file when not connected.');
        }

        return (bool) $this->sftp->get($remotePath, $localPath);
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }
}
