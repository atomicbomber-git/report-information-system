<?php

use Illuminate\Database\Seeder;
use App\Course;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_courses = [
            // Kelompok A
            ['grade' => 8, 'scoring_method' => 'spiritual', 'name' => 'Pendidikan Agama dan Budi Pekerti', 'group' => 'A'],
            ['grade' => 8, 'scoring_method' => 'social', 'name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'group' => 'A'],
            ['grade' => 8, 'scoring_method' => 'normal', 'name' => 'Bahasa Indonesia', 'group' => 'A'],
            ['grade' => 8, 'scoring_method' => 'normal', 'name' => 'Matematika', 'group' => 'A'],
            ['grade' => 8, 'scoring_method' => 'normal', 'name' => 'Ilmu Pengetahuan Alam', 'group' => 'A'],
            ['grade' => 8, 'scoring_method' => 'normal', 'name' => 'Ilmu Pengetahuan Sosial', 'group' => 'A'],
            ['grade' => 8, 'scoring_method' => 'normal', 'name' => 'Bahasa Inggris', 'group' => 'A'],
            
            // Kelompok B
            ['grade' => 8, 'scoring_method' => 'normal', 'name' => 'Seni Budaya', 'group' => 'B'],
            ['grade' => 8, 'scoring_method' => 'normal', 'name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'group' => 'B'],
            ['grade' => 8, 'scoring_method' => 'normal', 'name' => 'Prakarya', 'group' => 'B']
        ];
        
        $term = DB::table('terms')->first();

        foreach ($default_courses as $course) {
            $course['term_id'] = $term->id;
            Course::create($course);
        }
    }
}
