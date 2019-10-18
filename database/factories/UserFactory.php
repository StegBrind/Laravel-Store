<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;

use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name_surname' => $faker->firstName . ' ' . $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'login' => $faker->unique()->text(40),
        'password' => \Illuminate\Support\Facades\Hash::make('somepass'),
	'conversation_list' => ''
    ];
});