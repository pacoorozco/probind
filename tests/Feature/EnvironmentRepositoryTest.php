<?php

namespace Tests\Feature;

use App\Repositories\EnvironmentRepository;
use Tests\BrowserKitTestCase;

class EnvironmentRepositoryTest extends BrowserKitTestCase
{
    public function testSetDatabaseSetting()
    {
        // TODO: After upgrading to Laravel 5.6 this test always create .env
        /*
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
            'host' => 'host_value'
        ];

        // Success set connection settings.
        $this->assertTrue($environmentRepository->SetDatabaseSetting($expectedConnectionSettings));
        */
    }
}
