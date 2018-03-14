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
            'Matematika', 'Pendidikan Kewarganegaraan', 'Ilmu Pengetahuan Alam',
            'Ilmu Pengetahuan Sosial', 'Pendidikan Agama', 'Bahasa Inggris',
            'Pendidikan Jasmani dan Olahraga', 'Bahasa Indonesia'
        ];

        foreach ($default_courses as $course) {
            Course::create(['name' => $course]);
        }
    }
}
