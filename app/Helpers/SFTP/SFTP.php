<?php
/**
 *  ProBIND v3 - Professional DNS management made easy.
 *
 *  Copyright (c) 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2021 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace App\Helpers\SFTP;

use phpseclib\Crypt\RSA;
use phpseclib\Net\SFTP as PHPSecLibSFTP;

class SFTP
{
    /**
     * SFTP connection.
     *
     * @var \phpseclib\Net\SFTP
     */
    private PHPSecLibSFTP $connection;

    /**
     * Connect to a remote host.
     *
     * @param  string  $hostname
     * @param  int  $port  By default port 22 is used.
     *
     * @throws \App\Helpers\SFTP\ConnectionException
     */
    public function __construct(string $hostname, int $port = 22)
    {
        $connection = new PHPSecLibSFTP($hostname, $port);
        if (false === $connection) {
            throw new ConnectionException('Unable to connect to ' . $hostname . ' (' . $port . ')');
        }
        $this->connection = $connection;
    }

    /**
     * Authenticate a user using a public key.
     *
     * @param  string  $username
     * @param  RSA  $publicKey
     *
     * @throws \App\Helpers\SFTP\AuthenticationException
     */
    public function authWithPublicKey(string $username, RSA $publicKey): void
    {
        if (false === $this->connection->login($username, $publicKey)) {
            throw new AuthenticationException('Invalid public key authentication for ' . $username);
        }
    }

    /**
     * Put a local file on the remote host. It creates folder structure if needed.
     *
     * @param  string  $localPath
     * @param  string  $remotePath
     * @return bool
     */
    public function put(string $localPath, string $remotePath): bool
    {
        $path = $this->getFolderFromFile($remotePath);
        if (false === $this->createFolderAndParents($path, 0755)) {
            return false;
        }

        return $this->connection->put($remotePath, $localPath, PHPSecLibSFTP::SOURCE_LOCAL_FILE);
    }

    /**
     * Creates a folder (including parents) on the remote host.
     *
     * @param  string  $path
     * @param  int  $mode
     * @return bool
     */
    private function createFolderAndParents(string $path, int $mode = 0755): bool
    {
        if (true === $this->connection->chdir($path)) {
            // The path already exist on the remote host.
            return true;
        }

        return $this->connection->mkdir($path, $mode, true);
    }

    /**
     * Returns the folder path of a file. This is done by removing the last part of the path.
     *
     * @param  string  $filePath
     * @return string
     */
    private function getFolderFromFile(string $filePath): string
    {
        return substr($filePath, 0, strrpos($filePath, '/'));
    }

    /**
     * Disconnect from the remote host.
     */
    public function disconnect(): void
    {
        $this->connection->disconnect();
    }
}
