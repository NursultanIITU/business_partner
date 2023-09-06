<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    public function test_login_successfully()
    {
        $user = User::factory()->create();

        $response = $this->post('api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token', 'token_type', 'expires_in'
            ]);
    }

    public function test_profile() {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('api/v1/auth/profile');
        $response->assertStatus(200);
    }

    public function test_unauthorized_user_cannot_access_to_profile()
    {
        $response = $this->withHeader('Accept', 'application/json')->get('api/v1/auth/profile');

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_logout_successfully() {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->post('api/v1/auth/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Successfully logged out']);
    }

}
