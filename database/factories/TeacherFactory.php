<?php

use Faker\Generator as Faker;
use App\Teacher;

$factory->define(Teacher::class, function (Faker $faker) {

    $faker = \Faker\Factory::create('id_ID');

    return [
        'teacher_id' => $faker->randomNumber(8)
    ];
});
