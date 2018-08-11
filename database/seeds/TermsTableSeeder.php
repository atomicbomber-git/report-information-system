<?php

use Illuminate\Database\Seeder;
use App\Term;

class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_terms = [
            ['start' => 2015, 'end' => 2016, 'passing_grade' => 76],
            ['start' => 2016, 'end' => 2017, 'passing_grade' => 76],
            // ['start' => 2017, 'end' => 2018]
        ];

        foreach ($default_terms as $term) {
            Term::create([
                'term_start' => $term['start'],
                'term_end' => $term['end'],
                'code' => $term['start'] . '-' . $term['end'],
                'passing_grade' => $term['passing_grade']
            ]);
        }
    }
}
