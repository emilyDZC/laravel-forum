<?php

use function Pest\Laravel\get;
use Inertia\Testing\AssertableInertia;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;

it('can show a post', function () {
    $post = Post::factory()->create();

    get($post->showRoute())
        ->assertComponent('Posts/Show');
        
});

it('passes a post to the view', function () {
    $post = Post::factory()->create();

    $post->load('user');

    get($post->showRoute())
        ->assertHasResource('post', PostResource::make($post));
});

it('passes comments to the view', function () {
    $this->withoutExceptionHandling();

    // $post = Post::factory()->hasComments(10)->create(); // alternative way 
    $post = Post::factory()->create();

    $comments = Comment::factory(2)->for($post)->create();
    $comments->load('user');

    get($post->showRoute())
        ->assertHasPaginatedResource('comments', CommentResource::collection($comments->reverse()));
});

it('will redirect if the slug is incorrect', function () {
    $post = Post::factory()->create(['title' => 'Hello world']);

    get(route('posts.show', [$post, 'foo-bar', 'page' => 2]))
        ->assertRedirect($post->showRoute(['page' => 2]));
});