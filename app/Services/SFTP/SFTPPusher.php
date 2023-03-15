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

use InvalidArgumentException;
use phpseclib3\Crypt\Common\AsymmetricKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SFTP;
use RuntimeException;

class SFTPPusher
{

    const FINGERPRINT_MD5 = 'md5';
    const FINGERPRINT_SHA1 = 'sha1';

    private string $hostname;
    private int $port = 22;
    private string $username;
    private string|null $password = null;
    private string|null $privateKeyPath = null;
    private int $timeout = 10;
    private bool $connected = false;
    private SFTP $sftp;
    private AsymmetricKey|null $privateKey = null;

    public function to(string $hostname): self
    {
        $this->hostname = $hostname;
        return $this;
    }

    public function onPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function as(string $username): self
    {
        $this->username = $username;
        return $this;
    }

//         public function withPassword(string $password): self
//         {
//             $this->password = $password;
//             return $this;
//         }
//
//         public function withPrivateKeyFile(string $privateKeyPath): self
//         {
//             $this->privateKeyPath = $privateKeyPath;
//             return $this;
//         }

    public function withPrivateKey(string $privateKey): self
    {
        $this->privateKey = PublicKeyLoader::load($privateKey);
        return $this;
    }

    public function timeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    private function sanityCheck(): void
    {
        if (!$this->hostname) {
            throw new InvalidArgumentException('Hostname not specified.');
        }

        if (!$this->username) {
            throw new InvalidArgumentException('Username not specified.');
        }

        if (!$this->password && !$this->privateKeyPath && !$this->privateKey) {
            throw new InvalidArgumentException('No password or private key specified.');
        }
    }

    public function connect(): self
    {
        $this->sanityCheck();

        $this->sftp = new SFTP($this->hostname, $this->port, $this->timeout);

        if ($this->privateKey) {
            $authenticated = $this->sftp->login($this->username, $this->privateKey);
            if (!$authenticated) {
                throw new RuntimeException('Error authenticating with public-private key pair.');
            }
        }

//            if ($this->privateKeyPath) {
//                $key = new RSA();
//                $key->loadKey(file_get_contents($this->privateKeyPath));
//                $authenticated = $this->ssh->login($this->username, $key);
//                if (!$authenticated) {
//                    throw new RuntimeException('Error authenticating with public-private key pair.');
//                }
//            }
//
//            if ($this->password) {
//                $authenticated = $this->ssh->login($this->username, $this->password);
//                if (!$authenticated) {
//                    throw new RuntimeException('Error authenticating with password.');
//                }
//            }

        if ($this->timeout) {
            $this->sftp->setTimeout($this->timeout);
        }

        $this->connected = true;

        return $this;
    }

    public function disconnect(): void
    {
        if (!$this->connected) {
            throw new RuntimeException('Unable to disconnect. Not yet connected.');
        }

        $this->sftp->disconnect();
    }

    public function fingerprint(string $type = self::FINGERPRINT_MD5): string
    {
        if (!$this->connected) {
            throw new RuntimeException('Unable to get fingerprint when not connected.');
        }

        $hostKey = substr($this->sftp->getServerPublicHostKey(), 8);

        switch ($type) {
            case 'md5':
                return strtoupper(md5($hostKey));

            case 'sha1':
                return strtoupper(sha1($hostKey));
        }

        throw new InvalidArgumentException('Invalid fingerprint type specified.');
    }

    public function upload(string $localPath, string $remotePath): bool
    {
        if (!$this->connected) {
            throw new RuntimeException('Unable to upload file when not connected.');
        }

        if (!file_exists($localPath)) {
            throw new InvalidArgumentException('The local file does not exist.');
        }

        return $this->sftp->put($remotePath, $localPath, SFTP::SOURCE_LOCAL_FILE);
    }

    public function download(string $remotePath, string $localPath): bool
    {
        if (!$this->connected) {
            throw new RuntimeException('Unable to download file when not connected.');
        }

        return $this->sftp->get($remotePath, $localPath);
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }
}
