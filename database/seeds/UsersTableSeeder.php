<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        // Create few dummy users that we can identify and use for testing
        factory(User::class)->create([
            'name'       => 'john',
            'token'      => 'qwerty123',
            'is_enabled' => true,
        ]);

        factory(User::class)->create([
            'name'       => 'jane',
            'token'      => 'qwerty456',
            'is_enabled' => false,
        ]);

        // Create more random dummy users
        factory(User::class, 8)->create();
    }
}
