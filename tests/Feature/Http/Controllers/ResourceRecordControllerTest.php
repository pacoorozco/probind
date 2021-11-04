<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ResourceRecord;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceRecordControllerTest extends TestCase
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
        /** @var Zone $testZone */
        $testZone = Zone::factory()->primary()->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.records.index', $testZone));

        $response->assertSuccessful();
        $response->assertViewIs('record.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        /** @var Zone $testZone */
        $testZone = Zone::factory()->primary()->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.records.create', $testZone));

        $response->assertSuccessful();
        $response->assertViewIs('record.create');
    }

    /** @test */
    public function store_method_should_create_a_new_resource_record(): void
    {
        /** @var Zone $testZone */
        $testZone = Zone::factory()->primary()->create();

        /** @var \App\Models\ResourceRecord $want */
        $want = ResourceRecord::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('zones.records.store', $testZone), [
                'name' => $want->name,
                'type' => $want->type->value,
                'data' => $want->data,
                'ttl' => $want->ttl,
            ]);

        $response->assertRedirect(route('zones.records.index', $testZone));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('records', [
            'name' => $want->name,
            'type' => $want->type,
            'data' => $want->data,
            'ttl' => $want->ttl,
        ]);
    }

    /** @test */
    public function show_method_should_return_proper_data(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->primary()->create();

        /** @var \App\Models\ResourceRecord $testResourceRecord */
        $testResourceRecord = ResourceRecord::factory()->make();
        $zone->records()->save($testResourceRecord);

        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.records.show', ['zone' => $zone, 'record' => $testResourceRecord]));

        $response->assertSuccessful();
        $response->assertViewIs('record.show');
        $response->assertViewHas('record', $testResourceRecord);
    }

    /** @test */
    public function edit_method_should_return_proper_data(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->primary()->create();

        /** @var \App\Models\ResourceRecord $testResourceRecord */
        $testResourceRecord = ResourceRecord::factory()->make();
        $zone->records()->save($testResourceRecord);

        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.records.edit', ['zone' => $zone, 'record' => $testResourceRecord]));

        $response->assertSuccessful();
        $response->assertViewIs('record.edit');
        $response->assertViewHas('record', $testResourceRecord);
    }

    /** @test */
    public function update_method_should_modify_the_resource_record(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->primary()->create();

        /** @var \App\Models\ResourceRecord $testResourceRecord */
        $testResourceRecord = ResourceRecord::factory()->make();
        $zone->records()->save($testResourceRecord);

        /** @var \App\Models\ResourceRecord $want */
        $want = ResourceRecord::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->put(route('zones.records.update', ['zone' => $zone, 'record' => $testResourceRecord]), [
                'data' => $want->data,
                'ttl' => $want->ttl,
            ]);

        $response->assertRedirect(route('zones.records.index', $zone));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('records', [
            'name' => $testResourceRecord->name,
            'type' => $testResourceRecord->type,
            'data' => $want->data,
            'ttl' => $want->ttl,
        ]);
    }

    /** @test */
    public function delete_method_should_return_proper_data(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->primary()->create();

        /** @var \App\Models\ResourceRecord $testResourceRecord */
        $testResourceRecord = ResourceRecord::factory()->make();
        $zone->records()->save($testResourceRecord);

        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.records.delete', ['zone' => $zone, 'record' => $testResourceRecord]));

        $response->assertSuccessful();
        $response->assertViewIs('record.delete');
        $response->assertViewHas('record', $testResourceRecord);
    }

    /** @test */
    public function destroy_method_should_return_success_and_delete_resource_record(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->primary()->create();

        /** @var \App\Models\ResourceRecord $testResourceRecord */
        $testResourceRecord = ResourceRecord::factory()->make();
        $zone->records()->save($testResourceRecord);

        $response = $this
            ->actingAs($this->user)
            ->delete(route('zones.records.destroy', ['zone' => $zone, 'record' => $testResourceRecord]));

        $response->assertRedirect(route('zones.records.index', $zone));
        $response->assertSessionHas('success');
        $this->assertDeleted($testResourceRecord);
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->primary()->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('zones.records.data', $zone));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_should_return_data(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->primary()->create();

        $testResourceRecords = ResourceRecord::factory()->count(3)->make();
        $zone->records()->saveMany($testResourceRecords);

        $response = $this
            ->actingAs($this->user)
            ->ajaxGet(route('zones.records.data', $zone));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'type',
                    'data',
                ],
            ],
        ]);

        foreach ($testResourceRecords as $record) {
            $response->assertJsonFragment([
                'name' => $record->name,
                'type' => $record->type,
                'data' => $record->data,
            ]);
        }
    }
}
