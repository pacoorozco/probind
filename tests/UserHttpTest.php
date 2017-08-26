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
 * @link        https://github.com/pacoorozco/probind
 */

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserHttpTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $admin = factory(User::class)->create();
        $this->actingAs($admin);
    }

    /**
     * Test a successful new User creation
     */
    public function testNewUserCreationSuccess()
    {
        $expectedUser = factory(User::class)->make();

        $this->visit('users/create')
            ->type($expectedUser->username, 'username')
            ->type($expectedUser->name, 'name')
            ->type($expectedUser->email, 'email')
            ->type('VeryS3cr3t', 'password')
            ->type('VeryS3cr3t', 'password_confirmation')
            ->press('submit');

        // Get from DB if User has been created.
        $user = User::where('username', $expectedUser->username)->first();

        $this->assertNotNull($user);
    }

    /**
     * Test a User view
     */
    public function testViewUser()
    {
        $user = factory(User::class)->create();

        $this->visit('users/' . $user->id)
            ->see($user->username);
    }

    /**
     * Test a successful User edition
     */
    public function testUserEditionSuccess()
    {
        $originalUser = factory(User::class)->create([
            'username' => 'user.test',
            'email'    => 'user.test@domain.local'
        ]);

        $this->visit('users/' . $originalUser->id . '/edit')
            ->type('new.email@domain.local', 'email')
            ->press('submit');

        // Get the zone once has been modified
        $modifiedUser = User::findOrFail($originalUser->id);

        // Test modified email field
        $this->assertEquals('new.email@domain.local', $modifiedUser->email);

        // Test field that has not been modified
        $this->assertEquals($originalUser->name, $modifiedUser->name);
        $this->assertEquals($originalUser->username, $modifiedUser->username);
    }

    /**
     * Test a Delete view
     */
    public function testDeleteUser()
    {
        $user = factory(User::class)->create();

        $this->visit('users/' . $user->id . '/delete')
            ->see($user->username);
    }

    /**
     * Test a successful User deletion
     */
    public function testDeleteUserSuccess()
    {
        $originalUser = factory(User::class)->create();

        $this->call('DELETE', 'users/' . $originalUser->id);

        $this->assertNull(User::find($originalUser->id));
    }

    /**
     * Test a failed User deletion
     */
    public function testDeleteUserFailure()
    {
        $originalUser = Auth::user();

        $this->call('DELETE', 'users/' . $originalUser->id);

        $this->assertNotNull(User::find($originalUser->id));
    }

    /**
     * Test JSON call listing all Users
     */
    public function testJSONGetZoneData()
    {
        $originalUser = factory(User::class)->create([
            'username' => 'user.test',
            'email'    => 'user.test@domain.local'
        ]);

        $this->json('GET', '/users/data')
            ->seeJson([
                'username' => $originalUser->username,
                'email'    => $originalUser->email,
            ]);
    }
}
