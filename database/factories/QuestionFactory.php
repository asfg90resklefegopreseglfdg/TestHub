<?php

use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'question' => str_random(10),
        'points' => $faker->randomNumber(),
        'type_answer' => 'oneAnswer',
        'test_id' => function () {
            return factory(App\Test::class)->create()->id;
        },

    ];
});

$factory->state(App\Question::class, 'someAnswers', function ($faker) {
    return [
        'type_answer' => 'someAnswers',
    ];
});
