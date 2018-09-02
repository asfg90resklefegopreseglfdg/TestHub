<?php

use Faker\Generator as Faker;

$factory->define(App\Answer::class, function (Faker $faker) {
    return [
        'answer' => str_random(10),
        'correct' => $faker->boolean(),
        'question_id' => function() {
            return factory(App\Question::class)->create()->id;
        },
    ];
});
