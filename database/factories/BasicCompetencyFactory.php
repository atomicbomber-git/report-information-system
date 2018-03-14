<?php

use Faker\Generator as Faker;
use App\BasicCompetency;

$factory->define(BasicCompetency::class, function (Faker $faker) {

    // $faker = \Faker\Factory::create('id_ID');

    return [
        // 'name' => $faker->catchPhrase(),
        'description' => $faker->catchPhrase(),
        // 'course_report_id' => $faker->phrase,
        'first_assignment' => $faker->numberBetween(50, 100),
        'second_assignment' => $faker->numberBetween(50, 100),
        'third_assignment' => $faker->numberBetween(50, 100),
        'first_exam' => $faker->numberBetween(50, 100),
        'first_remedial' => $faker->numberBetween(50, 100),
        'second_exam' => $faker->numberBetween(50, 100),
        'second_remedial' => $faker->numberBetween(50, 100)
    ];
});
