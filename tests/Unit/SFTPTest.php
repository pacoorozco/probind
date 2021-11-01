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

namespace Tests\Unit;

use App\Helpers\SFTP\AuthenticationException;
use App\Helpers\SFTP\SFTP;
use phpseclib\Crypt\RSA;
use PHPUnit\Framework\TestCase;

class SFTPTest extends TestCase
{
    public function tearDown(): void
    {
        \Mockery::close();
    }

    public function testAuthWithPublicKeyWithValidCredentials()
    {
        $username = 'testUser';
        $publicKey = new RSA();

        $mockedSFTP = \Mockery::mock('overload:phpseclib\Net\SFTP');
        $mockedSFTP->shouldReceive('login')
            ->once()
            ->andReturn(true);

        $this->expectNotToPerformAssertions();

        $sftp = new SFTP('localhost');
        $sftp->authWithPublicKey($username, $publicKey);
    }

    public function testAuthWithPublicKeyWithInvalidCredentials()
    {
        $username = 'testUser';
        $publicKey = new RSA();

        $mockedSFTP = \Mockery::mock('overload:phpseclib\Net\SFTP');
        $mockedSFTP->shouldReceive('login')
            ->once()
            ->andReturn(false);

        $this->expectException(AuthenticationException::class);

        $sftp = new SFTP('localhost');
        $sftp->authWithPublicKey($username, $publicKey);
    }
}
