<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Larapacks\Setting\Setting;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
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
            ->get(route('settings.index'));

        $response->assertSuccessful();
        $response->assertViewIs('settings.index');
    }

    /** @test */
    public function update_method_should_modify_the_settings(): void
    {
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

        $want = [
            'zone_default_mname' => 'dns2.domain.local',
            'zone_default_rname' => 'hostmaster2@domain.local',
            'zone_default_refresh' => '3601',
            'zone_default_retry' => '3602',
            'zone_default_expire' => '3603',
            'zone_default_negative_ttl' => '3604',
            'zone_default_default_ttl' => '3605',

            'ssh_default_user' => 'probinder2',
            'ssh_default_key' => '-----BEGIN OPENSSH PRIVATE KEY 2-----',
            'ssh_default_port' => '22',
            'ssh_default_remote_path' => '/home/probinder/data',
        ];

        $response = $this
            ->actingAs($this->user)
            ->put(route('settings.update'), $want);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('settings.index'));

        $currentSettings = setting()->all();
        foreach ($want as $key => $expectedValue)
        {
            $this->assertEquals($expectedValue, $currentSettings[$key]);
        }
    }
}
