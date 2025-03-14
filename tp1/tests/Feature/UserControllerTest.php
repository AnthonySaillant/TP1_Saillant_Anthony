<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    
    const USER_LOGIN = 'johndoe';
    const USER_PASSWORD = 'password123';
    const USER_EMAIL = 'johndoe@example.com';
    const USER_FIRST_NAME = 'John';
    const USER_LAST_NAME = 'Doe';
    const INVALID_EMAIL = 'invalid-email';
    const SERVER_ERROR = 'Server error';
    const UPDATED_USER_LOGIN = 'johnsmith';
    const UPDATED_USER_EMAIL = 'john.smith@example.com';

    public function testStoreUserSuccessfully()
    {
        $data = [
            'login' => self::USER_LOGIN,
            'password' => self::USER_PASSWORD,
            'email' => self::USER_EMAIL,
            'first_name' => self::USER_FIRST_NAME,
            'last_name' => self::USER_LAST_NAME,
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(201);

        $response->assertJsonFragment([
            'login' => $data['login'],
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);
    }

    public function testStoreUserValidationFailure()
    {
        $data = [
            'login' => '',
            'password' => self::USER_PASSWORD,
            'email' => self::INVALID_EMAIL,
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['login', 'email']);
    }

    public function testUpdateUserSuccessfully()
    {
        $user = User::create([
            'login' => self::USER_LOGIN,
            'password' => self::USER_PASSWORD,
            'email' => self::USER_EMAIL,
            'first_name' => self::USER_FIRST_NAME,
            'last_name' => self::USER_LAST_NAME,
        ]);

        $data = [
            'login' => self::UPDATED_USER_LOGIN,
            'password' => self::USER_PASSWORD,
            'email' => self::UPDATED_USER_EMAIL,
            'first_name' => self::USER_FIRST_NAME,
            'last_name' => self::USER_LAST_NAME,
        ];

        $response = $this->putJson("/api/users/{$user->id}", $data);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'login' => $data['login'],
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);
    }

    public function testUpdateUserValidationFailure()
    {
        $user = User::create([
            'login' => self::USER_LOGIN,
            'password' => self::USER_PASSWORD,
            'email' => self::USER_EMAIL,
            'first_name' => self::USER_FIRST_NAME,
            'last_name' => self::USER_LAST_NAME,
        ]);

        $data = [
            'login' => '',
            'password' => self::USER_PASSWORD,
            'email' => self::INVALID_EMAIL,
        ];

        $response = $this->putJson("/api/users/{$user->id}", $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['login', 'email']);
    }
    
    public function testUpdateUserNotFound()
    {
        $data = [
            'login' => self::UPDATED_USER_LOGIN,
            'password' => self::USER_PASSWORD,
            'email' => self::UPDATED_USER_EMAIL,
            'first_name' => self::USER_FIRST_NAME,
            'last_name' => self::USER_LAST_NAME,
        ];

        $response = $this->putJson('/api/users/9999', $data);

        $response->assertStatus(404);

        $responseContent = json_decode($response->getContent(), true);

        $this->assertEquals('User not found', $responseContent['message']);
    }

}
