<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2018 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author             Paco Orozco <paco@pacoorozco.info>
 * @copyright          2018 Paco Orozco
 * @license            GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link               https://github.com/pacoorozco/probind
 */

namespace Tests\Feature;

use App\Repositories\EnvironmentRepository;
use Tests\TestCase;

class EnvironmentRepositoryTest extends TestCase
{
    /**
     * @var string Filename where repository will be saved
     */
    private $filename = '.env_repository_test';

    /**
     * @var EnvironmentRepository
     */
    private $environmentRepository;

    public function setUp(): void
    {
        parent::setUp();

        if (file_exists($this->filename)) {
            unlink($this->filename);
        };

        $this->environmentRepository = new EnvironmentRepository($this->filename);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        if (file_exists($this->filename)) {
            unlink($this->filename);
        };
    }

    public function testEnvironmentRepositoryFileIsCreated()
    {
        $this->assertFileExists($this->filename);
    }

    public function testSetDatabaseSettingsFailure()
    {
        // Missing two connection settings.
        $this->assertFalse($this->environmentRepository->SetDatabaseSetting([
            'database' => 'database_value_test',
            'username' => 'username_value_test'
        ]));
    }

    public function testSetDatabaseSettingsSuccess()
    {
        $expectedConnectionSettings = [
            'database' => 'database_value_test',
            'username' => 'username_value_test',
            'password' => 'password_value_test',
            'host' => 'host_value_test'
        ];

        // Success set connection settings.
        $this->assertTrue($this->environmentRepository->SetDatabaseSetting($expectedConnectionSettings));
    }
}
