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
        'current_grade' => $faker->randomElement([7, 8, 9]),
        'status_in_family' => $faker->randomElement(['Kandung', 'Adopsi']),
        'nth_child' => $faker->randomNumber(1) + 1,
        'address' => $faker->streetAddress,
        'phone' => $faker->phoneNumber,
        'alma_mater' => 'SD Negeri ' . $faker->randomNumber(2) . ' Pontianak',
        'acceptance_date' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
        'mother_name' => $faker->name('female'),
        'father_name' => $faker->name('male'),
        'mother_occupation' => $faker->jobTitle,
        'father_occupation' => $faker->jobTitle,
        'parents_address' => $faker->streetAddress,
        'parents_phone' => $faker->phoneNumber,
        'guardian_name' => $faker->name,
        'guardian_phone' => $faker->phoneNumber,
        'guardian_occupation' => $faker->jobTitle,
        'guardian_address' => $faker->streetAddress,
    ];
});
