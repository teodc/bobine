<?php

/** @var Factory $factory */

use App\Models\Comment;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(
    Comment::class,
    function (Faker $faker)
    {
        return [
            'id'        => Str::uuid()->toString(),
            'author_id' => User::query()->inRandomOrder()->first()->id,
            'body'      => $faker->realText(100),
        ];
    }
);
