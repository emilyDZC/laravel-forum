<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Resources\PostResource;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PostResource::withoutWrapping();

        Model::preventLazyLoading();
    }
}
