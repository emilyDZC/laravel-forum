<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        $posts = Post::factory(200)
            ->has(Comment::factory(15)->recycle($users))
            ->recycle($users)->create(); // 'recycle' chooses a random user from $users

        $comments = Comment::factory(100)->recycle($users)->recycle($posts)->create();

        $emily = User::factory()
            ->has(Post::factory(20))
            ->has(Comment::factory(100)->recycle($posts))
            ->create([
                'name' => 'Emily Chaplais',
                'email' => 'test@example.com',
                'password' => 'letmein!'
            ]);
    }
}
