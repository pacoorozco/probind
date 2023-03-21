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

namespace Tests\Unit\Services\Pusher\Clients;

use App\Services\Pusher\Clients\SFTPClient;
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

        (new SFTPClient())
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

        (new SFTPClient())
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

        (new SFTPClient())
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

        (new SFTPClient())
            ->onPort(-1);
    }

    /**
     * @test
     */
    public function it_should_fail_when_setting_timeout_with_invalid_timeout(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Timeout must be a positive integer.');

        (new SFTPClient())
            ->timeout(-1);
    }

    /**
     * @test
     */
    public function it_should_fail_when_setting_credentials_with_invalid_private_key_file(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid private key file specified.');

        (new SFTPClient())
            ->withPrivateKeyFile('foo/bar');
    }

    /**
     * @test
     */
    public function it_should_fail_when_setting_credentials_with_invalid_private_key(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid private key specified.');

        (new SFTPClient())
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

        (new SFTPClient())
            ->disconnect();
    }

    /**
     * @test
     */
    public function it_should_fail_when_uploading_from_non_connected_server(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to upload file when not connected.');

        (new SFTPClient())
            ->upload('source', 'destination');
    }

    /**
     * @test
     */
    public function it_should_fail_when_downloading_from_non_connected_server(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to download file when not connected.');

        (new SFTPClient())
            ->download('source', 'destination');
    }

    /**
     * @test
     */
    public function it_should_return_false_when_not_connected(): void
    {
        $client = new SFTPClient();

        $this->assertFalse($client->isConnected());
    }
}
