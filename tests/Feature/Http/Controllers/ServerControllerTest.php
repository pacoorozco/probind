<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Server;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServerControllerTest extends TestCase
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
            ->get(route('servers.index'));

        $response->assertSuccessful();
        $response->assertViewIs('server.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('servers.create'));

        $response->assertSuccessful();
        $response->assertViewIs('server.create');
    }

    /** @test */
    public function store_method_should_create_a_new_server(): void
    {
        /** @var Server $testServer */
        $testServer = Server::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('servers.store'), [
                'hostname' => $testServer->hostname,
                'ip_address' => $testServer->ip_address,
                'type' => $testServer->type->value,
                'ns_record' => $testServer->ns_record,
                'push_updates' => $testServer->push_updates,
                'active' => $testServer->active,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('servers.index'));
        $this->assertDatabaseHas('servers', [
            'hostname' => $testServer->hostname,
            'ip_address' => $testServer->ip_address,
        ]);
    }

    /** @test */
    public function show_method_should_return_proper_data(): void
    {
        $testServer = Server::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('servers.show', $testServer));

        $response->assertSuccessful();
        $response->assertViewIs('server.show');
        $response->assertViewHas('server', $testServer);
    }

    /** @test */
    public function edit_method_should_return_proper_view(): void
    {
        $testServer = Server::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('servers.edit', $testServer));

        $response->assertSuccessful();
        $response->assertViewIs('server.edit');
        $response->assertViewHas('server', $testServer);
    }

    /** @test */
    public function update_method_should_modify_the_server(): void
    {
        /** @var Server $testServer */
        $testServer = Server::factory()->create([
            'active' => true,
        ]);

        /** @var Server $want */
        $want = Server::factory()->make([
            'active' => false,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->put(route('servers.update', $testServer), [
                'hostname' => $want->hostname,
                'ip_address' => $want->ip_address,
                'type' => $want->type->value,
                'ns_record' => $want->ns_record,
                'push_updates' => $want->push_updates,
                'active' => $want->active,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('servers.index'));
        $this->assertDatabaseHas('servers', [
            'id' => $testServer->id,
            'hostname' => $want->hostname,
            'ip_address' => $want->ip_address,
            'type' => $want->type,
            'ns_record' => $want->ns_record,
            'push_updates' => $want->push_updates,
            'active' => $want->active,
        ]);
    }

    /** @test */
    public function delete_method_should_return_proper_data(): void
    {
        $testServer = Server::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('servers.delete', $testServer));

        $response->assertSuccessful();
        $response->assertViewIs('server.delete');
        $response->assertViewHas('server', $testServer);
    }

    /** @test */
    public function destroy_method_should_return_success_and_delete_server(): void
    {
        /** @var Server $testServer */
        $testServer = Server::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('servers.destroy', $testServer));

        $response->assertRedirect(route('servers.index'));
        $response->assertSessionHas('success');
        $this->assertModelMissing($testServer);
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('servers.data'));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_should_return_data(): void
    {
        $servers = Server::factory()
            ->count(3)
            ->create([
                'active' => true,
            ]);

        $response = $this
            ->actingAs($this->user)
            ->ajaxGet(route('servers.data'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'hostname',
                    'ip_address',
                ],
            ],
        ]);
        foreach ($servers as $testServer) {
            $response->assertJsonFragment([
                'hostname' => $testServer->hostname,
                'ip_address' => $testServer->ip_address,
            ]);
        }
    }
}
