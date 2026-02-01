<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Testing\AssertableInertia;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (! $this->app->runningUnitTests()) {
            return;
        }

         AssertableInertia::macro('hasResource', function (string $key, JsonResource $resource) {
            $props = $this->toArray()['props'];

            $compiledResource = $resource->response()->getData(true);

            expect($props)
                ->toHaveKey($key, message: "Key \"{$key}\" not passed as a property to Inertia.")
                ->and($props[$key])
                ->toEqual($compiledResource);

            return $this;
        });

        AssertableInertia::macro('hasPaginatedResource', function (string $key, ResourceCollection $resource) {
            $props = $this->toArray()['props'];

            $compiledResource = $resource->response()->getData(true);

            expect($props)
                ->toHaveKey($key, message: "Key \"{$key}\" not passed as a property to Inertia.")
                ->and($props[$key])
                ->toHaveKeys(['data', 'links', 'meta']);

            return $this;
        });
    }
}
