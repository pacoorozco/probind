<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_should_return_a_view(): void
    {
        $user = User::factory()
            ->create();

        $response = $this->actingAs($user)
            ->get(route('home'));

        $response->assertSuccessful();
    }

    /** @test */
    public function index_without_auth_should_return_login_form(): void
    {
        $response = $this->get(route('home'));

        $response->assertRedirect(route('login'));
    }
}
