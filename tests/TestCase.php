<?php

namespace Tests;

use Mockery;
use App\RequestState;
use App\Models\Auth\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Testing\Assert;
use InvalidArgumentException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use PHPUnit\Framework\Assert as PHPUnit;
use Laravel\Socialite\Two\GithubProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        Assert::macro('is', function ($value) {
            PHPUnit::assertSame($value, $this->component, 'Unexpected Inertia page component.');

            $namespace = Str::before($value, '/');
            $path = Str::after($value, '/');
            $file = "{$namespace}/Pages/{$path}";

            try {
                app('inertia.testing.view-finder')->find($file);
            } catch (InvalidArgumentException $exception) {
                PHPUnit::fail(sprintf('Inertia page component file [%s] does not exist.', $file));
            }

            return $this;
        });
    }

    protected function registerNewUser($options = [])
    {
        $user = User::factory()->create($options);

        $this->be($user);

        event(new Registered($user));

        return $user;
    }

    public function mockSocialite(string $provider, \Laravel\Socialite\Two\User $user, array $state = [], array $scopes = [])
    {
        $state = RequestState::fromArray($state);
        $providerClass = match($provider) {
            'github' => GithubProvider::class,
        };

        $redirect = new RedirectResponse(
            route("accounts.callback", [
                'provider' => $provider,
                'state' => $state,
            ]),
        );

        $driver = Mockery::mock($providerClass);
        $driver->shouldReceive('scopes')->with($scopes)->andReturn($driver);
        $driver->shouldReceive('redirect')->andReturn($redirect);
        $driver->shouldReceive('stateless')->andReturn($driver);
        $driver->shouldReceive('with')->with(['state' => $state])->andReturn($driver);
        $driver->shouldReceive('user')->andReturn($user);

        Socialite::shouldReceive('driver')->with($provider)->andReturn($driver);
    }

}
