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
            ['name' => 'Matematika', 'passing_grade' => 75, 'description' => "", 'group' => 'A', 'has_spiritual_grades' => false, 'has_social_grades' => false],
            ['name' => 'Pendidikan Kewarganegaraan', 'passing_grade' => 75, 'description' => "", 'group' => 'A', 'has_spiritual_grades' => false, 'has_social_grades' => true],
            ['name' => 'Ilmu Pengetahuan Alam', 'passing_grade' => 75, 'description' => "", 'group' => 'A', 'has_spiritual_grades' => false, 'has_social_grades' => false],
            ['name' => 'Ilmu Pengetahuan Sosial', 'passing_grade' => 75, 'description' => "", 'group' => 'A', 'has_spiritual_grades' => false, 'has_social_grades' => false],
            ['name' => 'Pendidikan Agama', 'passing_grade' => 75, 'description' => "", 'group' => 'A', 'has_spiritual_grades' => true, 'has_social_grades' => false],
            ['name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'passing_grade' => 75, 'description' => "", 'group' => 'A', 'has_spiritual_grades' => false, 'has_social_grades' => false],
            ['name' => 'Bahasa Indonesia', 'passing_grade' => 75, 'description' => "", 'group' => 'A', 'has_spiritual_grades' => false, 'has_social_grades' => false],
        ];

        foreach ($default_courses as $course) {
            Course::create($course);
        }
    }
}
