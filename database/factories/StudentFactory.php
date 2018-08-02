<?php

use Faker\Generator as Faker;
use App\Student;

$factory->define(Student::class, function (Faker $faker) {

$faker = \Faker\Factory::create('id_ID');

    return [
        'student_id' => $faker->randomNumber(8),
        'sex' => $faker->randomElement(['male', 'female']),
        'birthplace' => $faker->city(),
        'birthdate' => $faker->dateTimeBetween('-18 years', '-15 years')->format('Y-m-d'),
        'religion' => $faker->randomElement(array_keys(Student::RELIGIONS)),
        'current_grade' => $faker->randomElement([7, 8, 9])
    ];
});
