<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $users[] = User::create([
                'name' => fake()->name,
                'email' => fake()->email,
                'password' => 'password'
            ]);
        }

        $authors = [];
        for ($i = 0; $i < 10; $i++) {
            $authors[] = Author::create([
                'name' => fake()->name,
                'joined' => \Carbon\Carbon::now()->subMonths(rand(10, 20))
            ]);
        }

        $tags = [];
        for ($i = 0; $i < 10; $i++) {
            $tags[] = Tag::create([
                'name' => fake()->name
            ]);
        }

        $posts = [];
        foreach ($authors as $author) {
            for ($i = 0; $i < 50; $i++) {
                $posts[] = $author->posts()->create([
                    'title' => fake()->sentence(),
                    'body' => fake()->paragraphs(rand(3, 10), true)
                ]);
            }
        }

        $comments = [];
        foreach ($posts as $post) {
            for ($i = 0; $i < 20; $i++) {
                $comments[] = $post->comments()->create([
                    'user_id' => User::inRandomOrder()->first()->id,
                    'body' => fake()->paragraphs(rand(3, 10), true)
                ]);
            }

            $post->tags()->attach(Tag::inRandomOrder()->first()->id);
        }

        $replies = [];
        foreach ($comments as $comment) {
            for ($i = 0; $i < 20; $i++) {
                $replies[] = $comment->replies()->create([
                    'user_id' => User::inRandomOrder()->first()->id,
                    'body' => fake()->paragraphs(rand(3, 10), true)
                ]);
            }
        }
    }
}
