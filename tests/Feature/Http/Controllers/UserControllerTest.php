<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        /** @var User $user */
        $user = User::factory()->create();
        $this->user = $user;
    }

    /** @test */
    public function index_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('users.index'));

        $response->assertSuccessful();
        $response->assertViewIs('user.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('users.create'));

        $response->assertSuccessful();
        $response->assertViewIs('user.create');
    }

    /** @test */
    public function store_method_should_create_a_new_user(): void
    {
        /** @var User $testUser */
        $testUser = User::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('users.store'), [
                'username' => $testUser->username,
                'email' => $testUser->email,
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
            ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'username' => $testUser->username,
            'email' => $testUser->email,
        ]);
    }

    /** @test */
    public function edit_method_should_return_proper_view(): void
    {
        $testUser = User::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('users.edit', $testUser));

        $response->assertSuccessful();
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user', $testUser);
    }

    /** @test */
    public function update_method_should_modify_the_user(): void
    {
        /** @var User $testUser */
        $testUser = User::factory()->create([
            'active' => true,
        ]);

        /** @var User $want */
        $want = User::factory()->make([
            'active' => false,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->put(route('users.update', $testUser), [
                'email' => $want->email,
                'enabled' => $want->active,
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'id' => $testUser->id,
            'username' => $testUser->username,
            'email' => $want->email,
            'enabled' => $want->active,
        ]);
    }

    /** @test */
    public function destroy_method_should_return_success_and_delete_user(): void
    {
        $testUser = User::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('users.destroy', $testUser));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        $this->assertDeleted($testUser);
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('users.data'));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_should_return_data(): void
    {
        $users = User::factory()
            ->count(3)
            ->create([
                'active' => 'true',
            ]);

        $response = $this
            ->actingAs($this->user)
            ->ajaxGet(route('users.data'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'username',
                    'email',
                ],
            ],
        ]);
        foreach ($users as $testUser) {
            $response->assertJsonFragment([
                'username' => $testUser->username,
                'email' => $testUser->email,
            ]);
        }
    }
}
