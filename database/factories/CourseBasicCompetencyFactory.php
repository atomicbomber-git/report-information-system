<?php

use Faker\Generator as Faker;
use App\KnowledgeBasicCompetency;

$factory->define(KnowledgeBasicCompetency::class, function (Faker $faker) {
    return [
        'name' => $faker->catchPhrase,
        'even_odd' => $faker->randomElement(['even', 'odd'])
    ];
});
