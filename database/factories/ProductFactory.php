<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Product::class, function (Faker $faker)
{
    $user = \App\User::query()->inRandomOrder()->select(['id', 'name_surname'])->first();
    return
    [
        'name' => $faker->realText(20),
        'description' => $faker->realText(350),
        'category_id' => \App\Category::query()->inRandomOrder()->select(['id'])->first()->id,
        'image_paths' => 'images/products/test.jpg',
        'price' => mt_rand(0, 10000) / 100,
        'author' => $user->name_surname,
        'author_id' => $user->id
    ];
});
