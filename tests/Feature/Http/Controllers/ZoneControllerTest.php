<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\ZoneType;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZoneControllerTest extends TestCase
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

        setting()->set([
            'zone_default_mname' => 'dns1.domain.local',
            'zone_default_rname' => 'hostmaster@domain.local',
            'zone_default_refresh' => '86400',
            'zone_default_retry' => '7200',
            'zone_default_expire' => '3628800',
            'zone_default_negative_ttl' => '7200',
            'zone_default_default_ttl' => '172800',

            'ssh_default_user' => 'probinder',
            'ssh_default_key' => '-----BEGIN OPENSSH PRIVATE KEY-----',
            'ssh_default_port' => '22',
            'ssh_default_remote_path' => '/home/probinder/data',
        ]);
    }

    /** @test */
    public function index_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.index'));

        $response->assertSuccessful();
        $response->assertViewIs('zone.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.create'));

        $response->assertSuccessful();
        $response->assertViewIs('zone.create');
    }

    /** @test */
    public function store_method_should_create_a_new_primary_zone_with_custom_values(): void
    {
        /** @var Zone $testZone */
        $testZone = Zone::factory()->primary()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('zones.store'), [
                'zone_type' => ZoneType::Primary,
                'domain' => $testZone->domain,
                'custom_settings' => true,
                'refresh' => $testZone->refresh,
                'retry' => $testZone->retry,
                'expire' => $testZone->expire,
                'negative_ttl' => $testZone->negative_ttl,
                'default_ttl' => $testZone->default_ttl,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('zones.index'));
        $this->assertDatabaseHas('zones', [
            'domain' => $testZone->domain,
            'custom_settings' => true,
            'refresh' => $testZone->refresh,
            'retry' => $testZone->retry,
            'expire' => $testZone->expire,
            'negative_ttl' => $testZone->negative_ttl,
            'default_ttl' => $testZone->default_ttl,
        ]);
    }

    /** @test */
    public function store_method_should_create_a_new_primary_zone_with_default_values(): void
    {
        /** @var Zone $testZone */
        $testZone = Zone::factory()->primary()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('zones.store'), [
                'zone_type' => ZoneType::Primary,
                'domain' => $testZone->domain,
                'custom_settings' => false,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('zones.index'));
        $this->assertDatabaseHas('zones', [
            'domain' => $testZone->domain,
            'custom_settings' => false,
        ]);
    }

    /** @test */
    public function store_method_should_create_a_new_secondary_zone(): void
    {
        /** @var Zone $testZone */
        $testZone = Zone::factory()->secondary()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('zones.store'), [
                'zone_type' => ZoneType::Secondary,
                'domain' => $testZone->domain,
                'server' => $testZone->server,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('zones.index'));
        $this->assertDatabaseHas('zones', [
            'domain' => $testZone->domain,
            'server' => $testZone->server,
        ]);
    }

    /** @test */
    public function show_method_should_return_proper_data(): void
    {
        /** @var Zone $testZone */
        $testZone = Zone::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.show', $testZone));

        $response->assertSuccessful();
        $response->assertViewIs('zone.show');
        $response->assertViewHas('zone', $testZone);
    }

    /** @test */
    public function edit_method_should_return_proper_view(): void
    {
        /** @var Zone $testZone */
        $testZone = Zone::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.edit', $testZone));

        $response->assertSuccessful();
        $response->assertViewIs('zone.edit');
        $response->assertViewHas('zone', $testZone);
    }

    /** @test */
    public function update_method_should_modify_a_primary_zone(): void
    {
        /** @var Zone $testZone */
        $testZone = Zone::factory()->primary()->create();

        /** @var Zone $want */
        $want = Zone::factory()->primary()->make();

        $response = $this
            ->actingAs($this->user)
            ->put(route('zones.update', $testZone), [
                'zone_type' => ZoneType::Primary,
                'custom_settings' => true,
                'refresh' => $want->refresh,
                'retry' => $want->retry,
                'expire' => $want->expire,
                'negative_ttl' => $want->negative_ttl,
                'default_ttl' => $want->default_ttl,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('zones.index'));
        $this->assertDatabaseHas('zones', [
            'id' => $testZone->id,
            'domain' => $testZone->domain,
            'custom_settings' => true,
            'refresh' => $want->refresh,
            'retry' => $want->retry,
            'expire' => $want->expire,
            'negative_ttl' => $want->negative_ttl,
            'default_ttl' => $want->default_ttl,
        ]);
    }

    /** @test */
    public function update_method_should_modify_a_secondary_zone(): void
    {
        /** @var Zone $testZone */
        $testZone = Zone::factory()->secondary()->create();

        /** @var Zone $want */
        $want = Zone::factory()->secondary()->make();

        $response = $this
            ->actingAs($this->user)
            ->put(route('zones.update', $testZone), [
                'zone_type' => ZoneType::Secondary,
                'server' => $want->server,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('zones.index'));
        $this->assertDatabaseHas('zones', [
            'id' => $testZone->id,
            'domain' => $testZone->domain,
            'server' => $want->server,
        ]);
    }

    /** @test */
    public function destroy_method_should_return_success_and_delete_zone(): void
    {
        /** @var Zone $testZone */
        $testZone = Zone::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('zones.destroy', $testZone));

        $response->assertSessionHas('success');
        $response->assertRedirect(route('zones.index'));
        $this->assertSoftDeleted($testZone);
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.data'));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_should_return_data(): void
    {
        $servers = Zone::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->ajaxGet(route('zones.data'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'domain',
                    'type',
                ],
            ],
        ]);
        foreach ($servers as $testZone) {
            $response->assertJsonFragment([
                'domain' => $testZone->domain,
            ]);
        }
    }
}
