<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_find_a_user(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('user.show', $user->id));

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_can_delete_a_user(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('user.destroy', $user->id));

        $this->assertDatabaseEmpty('users');

        $response
            ->assertStatus(Response::HTTP_NO_CONTENT)
            ->assertNoContent();
    }
}
