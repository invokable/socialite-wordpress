<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteServiceProvider;
use Revolution\Socialite\WordPress\WordPressServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SocialiteServiceProvider::class,
            WordPressServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            //
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('services.wordpress',
            [
                'host' => 'http://localhost',
                'api_me' => 'http://localhost/me/',
                'client_id' => 'test',
                'client_secret' => 'test',
                'redirect' => 'http://localhost',
            ],
        );
    }

    protected function defineWebRoutes($router): void
    {
        $router->get('/auth/redirect', function () {
            return Socialite::driver('wordpress')->redirect();
        });

        $router->get('/auth/callback', function () {
            $user = Socialite::driver('wordpress')->user();

            return response()->json($user);
        });
    }
}
