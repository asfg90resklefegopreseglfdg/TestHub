<?php

use Faker\Generator as Faker;

$factory->define(App\Statistics::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'test_id' => function () {
            return factory(App\Test::class)->create()->id;
        },
        'points' => rand(0, 100),
        'test_end_time' => function() use($faker) {
            return $faker->dateTimeBetween('now', '+7 days');
        },
        'test_complete' => true,
    ];
});
