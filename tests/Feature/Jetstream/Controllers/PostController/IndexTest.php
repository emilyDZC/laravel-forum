<?php

use function Pest\Laravel\get;
use Inertia\Testing\AssertableInertia;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

it('should return the correct component', function () {
    $this->withoutExceptionHandling();

    $posts = Post::factory(3)->create();

    get(route('posts.index'))
         ->assertInertia(fn (AssertableInertia $inertia) =>
             $inertia->component('Posts/Index', true)
         );
});

it('passes posts to the view', function () {
   

    $this->withoutExceptionHandling();

    $posts = Post::factory(3)->create();

    get(route('posts.index'))
         ->assertInertia(fn (AssertableInertia $inertia) =>
             $inertia
                ->hasResource('post', PostResource::make($posts->first()))
                ->hasPaginatedResource('posts', PostResource::collection($posts->reverse()))
         );
});