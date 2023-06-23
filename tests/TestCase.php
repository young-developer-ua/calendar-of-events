<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function createUser($password)
    {
        return User::factory()->create([
            'password' => $password
        ]);
    }

    public function postJsonAuthWithToken($uri, string $token, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        $headers = array_merge($headers, [
            'Authorization' => 'Bearer ' . $token
        ]);
        return $this->postJson($uri, $data, $headers);
    }

    public function getJsonAuthWithToken($uri, array $headers = []): \Illuminate\Testing\TestResponse
    {
        return $this->getJson($uri, $headers);
    }

    public function putJsonAuthWithToken($uri, string $token, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        $headers = array_merge($headers, [
            'Authorization' => 'Bearer ' . $token
        ]);
        return $this->putJson($uri, $data, $headers);
    }

    public function deleteJsonAuthWithToken($uri, string $token, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        $headers = array_merge($headers, [
            'Authorization' => 'Bearer ' . $token
        ]);
        return $this->deleteJson($uri, $data, $headers);
    }
}
