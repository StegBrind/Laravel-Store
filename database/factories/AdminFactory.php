<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\Admin::class, function (Faker $faker) {
    return [
        'email' => 'admin@admin.com',
        'password' => \Illuminate\Support\Facades\Hash::make('adminpass'),
        'name' => 'Админ Владелец',
        'created_at' => \Carbon\Carbon::now()->toDateTime(),
        'updated_at' => \Carbon\Carbon::now()->toDateTime()
    ];
});
