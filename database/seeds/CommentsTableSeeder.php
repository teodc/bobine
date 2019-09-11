<?php

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        // Ensure that our two known dummy users have at least few comments each
        factory(Comment::class, 10)->create([
            'author_id' => User::findByName('john')->id,
        ]);

        factory(Comment::class, 10)->create([
            'author_id' => User::findByName('jane')->id,
        ]);

        // Create more dummy comments randomly assigned to users
        factory(Comment::class, 180)->create();
    }
}
