<?php

/** @var Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(
    User::class,
    function (Faker $faker)
    {
        return [
            'id'         => Str::uuid()->toString(),
            'name'       => $faker->userName,
            'email'      => null,
            'password'   => null,
            'token'      => Str::random(60),
            'is_enabled' => $faker->boolean,
        ];
    }
);

$factory->state(
    User::class,
    'enabled',
    [
        'is_enabled' => true,
    ]
);

$factory->state(
    User::class,
    'disabled',
    [
        'is_enabled' => false,
    ]
);
