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

beforeEach(function () {
    $this->validData = [
        'title' => 'Hello World',
        'body' => 'This is my very first post'
    ];
});

it('requires authentication', function () {
    post(route('posts.store', Post::factory()->create()))
        ->assertRedirect(route('login'));
});

it('stores a post', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('posts.store'), $this->validData);

    $this->assertDatabaseHas(Post::class, [
        ...$this->validData,
        'user_id' => $user->id
    ]);
});

it('redirects to the post show page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('posts.store'), $this->validData)
        ->assertRedirect(route('posts.show', Post::latest('id')->first()));
});

// Refactored into combined test below but keeping for ref
// it('requires a valid title', function ($badTitle) {
//     $user = User::factory()->create();

//     $this->actingAs($user)
//         ->post(route('posts.store'), [...$this->validData, 'title' => $badTitle])
//         ->assertInvalid('title');
// })->with([
//     null,
//     true,
//     1,
//     1.5,
//     str_repeat('a', 121),
//     str_repeat('a', 9),
// ]);

it('requires valid data', function (array $badData, array|string $errors) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('posts.store'), [...$this->validData, ...$badData])
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => true], 'title'],
    [['title' => 1], 'title'],
    [['title' => 1.5], 'title'],
    [['title' => str_repeat('a', 121)], 'title'],
    [['title' => str_repeat('a', 9)], 'title'],
    [['body' => null], 'body'],
    [['body' => true], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => str_repeat('a', 10_001)], 'body'],
    [['body' => str_repeat('a', 19)], 'body'],
]);