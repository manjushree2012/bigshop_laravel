<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use HasFactory, WithFaker;

    public function testSuccesfulRegister()
    {
        $response = $this->json('POST', '/api/register',
        [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'password'
        ],
        ['Accept' => 'application/json']);
        $response->assertStatus(200);
        // $this->assertArrayHasKey('access_token',$response->json());
    }

    public function testMissingRegisterData()
    {
        $response = $this->json(
            'POST',
            'api/register',
            [
                'email' => $this->faker->email,
                'password' => 'password'
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function testSuccesfulLogin()
    {
        $user = User::factory(User::class)->create([
            'email' => $this->faker->email,
            'password' => bcrypt('password')
        ]);
        $creds = ['email' => $user->email, 'password' => 'password'];

        $response = $this->json('POST', 'api/login', $creds, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $this->assertAuthenticated();
    }


    public function testLoginWithInvalidPassword()
    {
        $user = User::factory(User::class)->create([
            'email' => $this->faker->email,
            'password' => bcrypt('password')
        ]);
        $creds = ['email' => $user->email, 'password' => 'fakePwd'];

        $response = $this->json('POST', 'api/login', $creds, ['Accept' => 'application/json']);
        $response->assertStatus(401);
        $this->assertGuest();
    }
}
