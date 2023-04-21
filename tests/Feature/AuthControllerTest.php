<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    public $mockConsoleOutput = false;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('passport:install', ['--uuids' => true, '--no-interaction' => true]);
    }

    public function test_user_can_login(): void
    {
        $password = "password";
        $user = User::factory(['password' => Hash::make($password)])->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertOk();
        $this->assertNotNull($response->json('access_token'));
    }

    public function test_user_can_register(): void
    {
        $user = User::factory()->make();

        $response = $this->post(route('register'), [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'role' => $user->role,
            'document' => $user->document,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
            'document' => $user->document,
        ]);
    }
}
