<?php

use Faker\Generator as Faker;

$factory->define(App\Test::class, function (Faker $faker) {
    return [
        'name' => $faker->title,
        'answers_public' => true,
        'passing_public' => true,
        'description' => str_random(10),
        'duration' => rand(),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->state(App\Test::class, 'withAnonymousUser', function ($faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->state('anonymous')->create()->id;
        },
        'passing_public' => false,
        'link_to_stat_if_no_reg' => str_random(10)
    ];
});
