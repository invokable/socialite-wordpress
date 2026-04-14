<?php

namespace Tests;

use Illuminate\Http\Request;
use Laravel\Socialite\Two\User;
use Revolution\Socialite\WordPress\WordPressProvider;

class WordPressProviderTest extends TestCase
{
    public function test_map_user_to_object_with_all_fields(): void
    {
        $request = Request::create('foo');
        $provider = new WordPressProvider($request, 'client_id', 'client_secret', 'redirect');

        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('mapUserToObject');
        $method->setAccessible(true);

        $userData = [
            'ID' => 12345,
            'display_name' => 'John Doe',
            'username' => 'johndoe',
            'user_login' => 'johndoe_login',
            'email' => 'john@example.com',
            'user_email' => 'john_alt@example.com',
            'avatar_URL' => 'https://example.com/avatar.jpg',
        ];

        $user = $method->invoke($provider, $userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(12345, $user->getId());
        $this->assertEquals('johndoe', $user->getName());
        $this->assertEquals('John Doe', $user->getNickname());
        $this->assertEquals('john@example.com', $user->getEmail());
        $this->assertEquals('https://example.com/avatar.jpg', $user->getAvatar());
    }

    public function test_map_user_to_object_with_fallback_fields(): void
    {
        $request = Request::create('foo');
        $provider = new WordPressProvider($request, 'client_id', 'client_secret', 'redirect');

        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('mapUserToObject');
        $method->setAccessible(true);

        $userData = [
            'ID' => 67890,
            'user_login' => 'fallback_user',
            'user_email' => 'fallback@example.com',
        ];

        $user = $method->invoke($provider, $userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(67890, $user->getId());
        $this->assertEquals('fallback_user', $user->getName());
        $this->assertEquals('fallback@example.com', $user->getEmail());
        $this->assertEquals('', $user->getNickname());
        $this->assertEquals('', $user->getAvatar());
    }

    public function test_map_user_to_object_with_minimal_data(): void
    {
        $request = Request::create('foo');
        $provider = new WordPressProvider($request, 'client_id', 'client_secret', 'redirect');

        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('mapUserToObject');
        $method->setAccessible(true);

        $userData = [
            'ID' => 99999,
        ];

        $user = $method->invoke($provider, $userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(99999, $user->getId());
        $this->assertEquals('', $user->getName());
        $this->assertEquals('', $user->getNickname());
        $this->assertEquals('', $user->getEmail());
        $this->assertEquals('', $user->getAvatar());
    }
}
