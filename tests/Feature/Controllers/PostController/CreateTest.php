<?php

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use Inertia\Testing\AssertableInertia;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\PostResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

it('requires authentication', function () {
    get(route('posts.create'))->assertRedirect(route('login'));
});

it('returns the correct component', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertComponent('Posts/Create');
});