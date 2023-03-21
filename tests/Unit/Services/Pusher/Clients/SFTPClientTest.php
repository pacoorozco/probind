<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2016 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace Tests\Unit\Services\Pusher\Clients;

use App\Services\Pusher\Clients\SFTPClient;
use Mockery\MockInterface;
use phpseclib3\Net\SFTP;
use Tests\TestCase;

class SFTPClientTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_fail_when_connecting_without_hostname(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Hostname not specified.');

        (new SFTPClient('localhost'))
            ->as('foo')
            ->withPassword('s3cr3t')
            ->connect();
    }

    /**
     * @test
     */
    public function it_should_fail_when_connecting_without_username(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Username not specified.');

        (new SFTPClient('localhost'))
            ->to('localhost')
            ->withPassword('s3cr3t')
            ->connect();
    }

    /**
     * @test
     */
    public function it_should_fail_when_connecting_without_credentials(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No password or private key specified.');

        (new SFTPClient('localhost'))
            ->to('localhost')
            ->as('foo')
            ->connect();
    }

    /**
     * @test
     */
    public function it_should_fail_when_setting_port_with_invalid_port(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Port must be a positive integer.');

        (new SFTPClient('localhost'))
            ->onPort(-1);
    }

    /**
     * @test
     */
    public function it_should_fail_when_setting_timeout_with_invalid_timeout(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Timeout must be a positive integer.');

        (new SFTPClient('localhost'))
            ->timeout(-1);
    }

    /**
     * @test
     */
    public function it_should_fail_when_setting_credentials_with_invalid_private_key_file(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid private key file specified.');

        (new SFTPClient('localhost'))
            ->withPrivateKeyFile('foo/bar');
    }

    /**
     * @test
     */
    public function it_should_fail_when_setting_credentials_with_invalid_private_key(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid private key specified.');

        (new SFTPClient('localhost'))
            ->as('foo')
            ->withPrivateKey('fooBarBaz');
    }

    /**
     * @test
     */
    public function it_should_fail_when_disconnecting_from_non_connected_server(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to disconnect. Not yet connected.');

        (new SFTPClient('localhost'))
            ->disconnect();
    }

    /**
     * @test
     *
     * @dataProvider providesFingerprintTypes
     */
    public function it_should_fail_when_getting_fingerprint_from_non_connected_server(
        string $type
    ): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to get fingerprint when not connected.');

        (new SFTPClient('localhost'))
            ->fingerprint($type);
    }

    public static function providesFingerprintTypes(): \Generator {
        yield 'MD5 Fingerprint' => [
            'type' => 'md5'
        ];

        yield 'SHA1 Fingerprint' => [
            'type' => 'sha1'
        ];
    }

    /**
     * @test
     */
    public function it_should_fail_when_uploading_from_non_connected_server(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to upload file when not connected.');

        (new SFTPClient('localhost'))
            ->upload('source', 'destination');
    }

    /**
     * @test
     */
    public function it_should_fail_when_downloading_from_non_connected_server(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to download file when not connected.');

        (new SFTPClient('localhost'))
            ->download('source', 'destination');
    }

    /**
     * @test
     */
    public function it_should_return_false_when_not_connected(): void
    {
        $client = (new SFTPClient('localhost'));

        $this->assertFalse($client->isConnected());
    }
}
