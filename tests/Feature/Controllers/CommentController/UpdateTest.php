<?php

use function Pest\Laravel\put;
use function Pest\Laravel\actingAs;
use App\Models\Comment;
use App\Models\User;

it('requires authentication', function () {
    put(route('comments.update', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can update a comment', function () {
    $comment = Comment::factory()->create(['body' => 'This is the old body']);
    $newBody = 'This is the new body';

    $this->actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => $newBody]);

    $this->assertDatabaseHas(Comment::class, [
        'id' => $comment->id,
        'body' => $newBody
    ]);
});

it('redirects to the post show page', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => 'This is the new body'])
        ->assertRedirect(route('posts.show', $comment->post));
});

it('redirects to the correct page of comments', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page' => 2]), ['body' => 'This is the new body'])
        ->assertRedirect(route('posts.show', ['post' => $comment->post, 'page' => 2]));
});

it('cannot update a comment from another user', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create();

    actingAs($user)
        ->put(route('comments.update', ['comment' => $comment]), ['body' => 'This is the new body'])
        ->assertForbidden();
});

it('requires a valid body', function ($body) {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment]), ['body' => $body])
        ->assertInvalid('body');
})->with([
    null,
    true,
    1,
    1.5,
    str_repeat('a', 2501),
]);