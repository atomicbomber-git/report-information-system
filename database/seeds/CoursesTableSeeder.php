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
            ['grade' => 8, 'name' => 'Pendidikan Agama dan Budi Pekerti', 'description' => "", 'group' => 'A', 'has_spiritual_grades' => true],
            ['grade' => 8, 'name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'description' => "", 'group' => 'A', 'has_social_grades' => true],
            ['grade' => 8, 'name' => 'Bahasa Indonesia', 'description' => "", 'group' => 'A'],
            ['grade' => 8, 'name' => 'Matematika', 'description' => "", 'group' => 'A'],
            ['grade' => 8, 'name' => 'Ilmu Pengetahuan Alam', 'description' => "", 'group' => 'A'],
            ['grade' => 8, 'name' => 'Ilmu Pengetahuan Sosial', 'description' => "", 'group' => 'A'],
            ['grade' => 8, 'name' => 'Bahasa Inggris', 'description' => "", 'group' => 'A'],
            
            // Kelompok B
            ['grade' => 8, 'name' => 'Seni Budaya', 'description' => '', 'group' => 'B'],
            ['grade' => 8, 'name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'description' => '', 'group' => 'B'],
            ['grade' => 8, 'name' => 'Prakarya', 'description' => '', 'group' => 'B']
        ];
        
        $term = DB::table('terms')->first();

        foreach ($default_courses as $course) {
            $course['term_id'] = $term->id;
            Course::create($course);
        }
    }
}
