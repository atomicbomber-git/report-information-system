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
            ['grade' => 8, 'name' => 'Pendidikan Agama dan Budi Pekerti', 'passing_grade' => 75, 'description' => "", 'group' => 'A', 'has_spiritual_grades' => true],
            ['grade' => 8, 'name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'passing_grade' => 75, 'description' => "", 'group' => 'A', 'has_social_grades' => true],
            ['grade' => 8, 'name' => 'Bahasa Indonesia', 'passing_grade' => 75, 'description' => "", 'group' => 'A'],
            ['grade' => 8, 'name' => 'Matematika', 'passing_grade' => 75, 'description' => "", 'group' => 'A'],
            ['grade' => 8, 'name' => 'Ilmu Pengetahuan Alam', 'passing_grade' => 75, 'description' => "", 'group' => 'A'],
            ['grade' => 8, 'name' => 'Ilmu Pengetahuan Sosial', 'passing_grade' => 75, 'description' => "", 'group' => 'A'],
            ['grade' => 8, 'name' => 'Bahasa Inggris', 'passing_grade' => 75, 'description' => "", 'group' => 'A'],
            
            // Kelompok B
            ['grade' => 8, 'name' => 'Seni Budaya', 'passing_grade' => 75, 'description' => '', 'group' => 'B'],
            ['grade' => 8, 'name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'passing_grade' => 75, 'description' => '', 'group' => 'B'],
            ['grade' => 8, 'name' => 'Prakarya', 'passing_grade' => 75, 'description' => '', 'group' => 'B']
        ];
        
        foreach ($default_courses as $course) {
            Course::create($course);
        }
    }
}
