<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Testing\Assert;
use InvalidArgumentException;
use Illuminate\Auth\Events\Registered;
use PHPUnit\Framework\Assert as PHPUnit;
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

}
