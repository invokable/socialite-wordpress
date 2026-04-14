<?php

namespace Tests;

use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use Revolution\Socialite\WordPress\WordPressProvider;

class SocialiteTest extends TestCase
{
    public function test_instance(): void
    {
        $provider = Socialite::driver('wordpress');

        $this->assertInstanceOf(WordPressProvider::class, $provider);
    }

    public function test_redirect(): void
    {
        Socialite::fake('wordpress');

        $response = $this->get('/auth/redirect');

        $response->assertRedirect();
    }

    public function test_callback_user(): void
    {
        Socialite::fake('wordpress', (new User)->map([
            'id' => 12345,
            'name' => 'johndoe',
            'nickname' => 'John Doe',
            'email' => 'john@example.com',
            'avatar' => 'https://example.com/avatar.jpg',
        ]));

        $response = $this->get('/auth/callback');

        $response->assertOk();
        $response->assertJsonPath('id', 12345);
        $response->assertJsonPath('name', 'johndoe');
        $response->assertJsonPath('email', 'john@example.com');
    }
}
