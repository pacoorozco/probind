<?php

use App\Repositories\EnvironmentRepository;

class EnvironmentRepositoryTest extends TestCase
{
    public function testSetDatabaseSetting()
    {
        $environmentRepository = new EnvironmentRepository('.env_testing');

        // Missing two connection settings.
        $this->assertFalse($environmentRepository->SetDatabaseSetting([
            'database' => 'database_value',
            'username' => 'username_value'
        ]));

        $expectedConnectionSettings = [
            'database' => 'database_value',
            'username' => 'username_value',
            'password' => 'password_value',
            'host'     => 'host_value'
        ];

        // Success set connection settings.
        $this->assertTrue($environmentRepository->SetDatabaseSetting($expectedConnectionSettings));
    }
}
