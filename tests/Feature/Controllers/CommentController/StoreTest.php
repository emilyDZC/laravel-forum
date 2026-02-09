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
    post(route('posts.comments.store', Post::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can store a comment', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $this->actingAs($user)->post(route('posts.comments.store', $post), [
        'body' => 'This is a comment'
    ]);

    $this->assertDatabaseHas(Comment::class, [
        'post_id' => $post->id,
        'user_id' => $user->id,
        'body' => 'This is a comment'
    ]);
});

it('redirects to the post show page', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $this->actingAs($user)->post(route('posts.comments.store', $post), [
        'body' => 'This is a comment'
    ])->assertRedirect(route('posts.show', $post));
});

it('requires a valid body', function ($value) {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $this->actingAs($user)->post(route('posts.comments.store', $post), [
        'body' => $value
    ])->assertInvalid('body');
})->with([
    null,
    1,
    1.5,
    true,
    false,
    str_repeat('a', 2501)
]);