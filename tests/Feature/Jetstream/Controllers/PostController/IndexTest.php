<?php

use function Pest\Laravel\get;
use Inertia\Testing\AssertableInertia;

it('should return the correct component', function () {
    $this->withoutExceptionHandling();

    get(route('posts.index'))
         ->assertInertia(fn (AssertableInertia $inertia) =>
             $inertia->component('Posts/Index', true)
         );
});

it('passes posts to the view', function () {
    $this->withoutExceptionHandling();

    get(route('posts.index'))
         ->assertInertia(fn (AssertableInertia $inertia) =>
             $inertia->has('posts')
         );
});